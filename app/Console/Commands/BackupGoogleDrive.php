<?php

namespace App\Console\Commands;

use App\Models\BackupArchive;
use App\Services\GoogleDriveBackupService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use ZipArchive;

class BackupGoogleDrive extends Command
{
    protected $signature = 'backup:google-drive
                            {--db-only : Hanya backup database}
                            {--files-only : Hanya backup file uploads}
                            {--no-cleanup : Jangan hapus backup lama}';

    protected $description = 'Backup database dan file ke Google Drive secara otomatis';

    public function handle()
    {
        $this->info('🔄 Memulai proses backup...');

        $backupPath = config('backup.local_path', storage_path('app/backups'));
        if (!is_dir($backupPath)) {
            mkdir($backupPath, 0755, true);
        }

        $timestamp = now()->format('Y-m-d_His');
        $zipName = "backup_{$timestamp}.zip";
        $zipPath = "{$backupPath}/{$zipName}";

        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            $this->error('❌ Gagal membuat file ZIP');
            return 1;
        }

        // 1. Backup Database
        if (!$this->option('files-only')) {
            $this->info('📦 Backup database...');
            $sqlPath = $this->backupDatabase($backupPath, $timestamp);
            if ($sqlPath && file_exists($sqlPath)) {
                $zip->addFile($sqlPath, 'database/' . basename($sqlPath));
                $this->info("  ✅ Database berhasil di-dump: " . basename($sqlPath));
            } else {
                $this->warn('  ⚠️ Database dump gagal atau mysqldump tidak tersedia');
                $this->info('  📝 Menggunakan fallback PHP export...');
                $phpDump = $this->phpDatabaseExport($backupPath, $timestamp);
                if ($phpDump) {
                    $zip->addFile($phpDump, 'database/' . basename($phpDump));
                    $this->info("  ✅ PHP export berhasil");
                }
            }
        }

        // 2. Backup Files
        if (!$this->option('db-only')) {
            $this->info('📁 Backup file uploads...');
            $folders = config('backup.folders', []);
            foreach ($folders as $folder) {
                if (is_dir($folder)) {
                    $this->addFolderToZip($zip, $folder, 'files/' . basename($folder));
                    $this->info("  ✅ Folder: " . basename($folder));
                }
            }
        }

        $zip->close();

        $fileSizeBytes = filesize($zipPath);
        $fileSize = $this->formatBytes($fileSizeBytes);
        $this->info("📦 Backup lokal dibuat: {$zipName} ({$fileSize})");

        // Tentukan jenis backup
        $jenis = 'penuh';
        if ($this->option('db-only')) $jenis = 'database';
        if ($this->option('files-only')) $jenis = 'file';

        // Buat record arsip di database
        $arsip = BackupArchive::create([
            'nama_file'      => $zipName,
            'path_lokal'     => $zipPath,
            'jenis'          => $jenis,
            'ukuran_byte'    => $fileSizeBytes,
            'status'         => 'sedang_proses',
        ]);

        // 3. Upload to Google Drive
        $this->info('☁️  Mengunggah ke Google Drive...');
        $driveService = new GoogleDriveBackupService();

        if (!$driveService->initClient()) {
            $this->warn('⚠️  Google Drive tidak terkonfigurasi. Backup disimpan lokal.');
            $this->warn('   Jalankan: php artisan backup:auth untuk setup Google Drive');
            $this->info("✅ Backup lokal tersimpan di: {$zipPath}");
            $arsip->update([
                'status'  => 'berhasil',
                'catatan' => 'Disimpan lokal saja — Google Drive belum dikonfigurasi.',
            ]);
            return 0;
        }

        try {
            $fileId = $driveService->uploadFile($zipPath);
        } catch (\Exception $e) {
            $fileId = null;
            Log::error("Backup upload gagal: " . $e->getMessage());
        }

        if ($fileId) {
            $this->info("✅ Berhasil diunggah ke Google Drive (ID: {$fileId})");
            $arsip->update([
                'google_drive_id' => $fileId,
                'status'          => 'berhasil',
                'catatan'         => 'Berhasil diunggah ke Google Drive.',
            ]);

            if (!$this->option('no-cleanup')) {
                $keepCount = config('backup.keep_count', 5);
                $deleted = $driveService->cleanupOldBackups($keepCount);
                if ($deleted > 0) {
                    $this->info("🗑️  {$deleted} backup lama dihapus dari Google Drive");
                }
            }

            @unlink($zipPath);
            $arsip->update(['path_lokal' => null]);
        } else {
            $this->error('❌ Gagal mengunggah ke Google Drive. Backup lokal disimpan.');
            $arsip->update([
                'status'  => 'gagal',
                'catatan' => 'Upload ke Google Drive gagal. File disimpan di lokal.',
            ]);
        }

        // Cleanup temp files
        foreach (glob("{$backupPath}/*.sql") as $f) @unlink($f);
        foreach (glob("{$backupPath}/*.json") as $f) @unlink($f);

        $this->info('🎉 Proses backup selesai!');
        Log::info("Backup completed: {$zipName} ({$fileSize}) — status: {$arsip->fresh()->status}");

        return 0;
    }

    protected function backupDatabase(string $path, string $timestamp): ?string
    {
        $config = config('backup.database');
        $sqlFile = "{$path}/db_{$timestamp}.sql";

        $command = sprintf(
            'mysqldump -h%s -P%s -u%s %s %s > "%s" 2>&1',
            escapeshellarg($config['host']),
            escapeshellarg($config['port']),
            escapeshellarg($config['username']),
            $config['password'] ? '-p' . escapeshellarg($config['password']) : '',
            escapeshellarg($config['database']),
            $sqlFile
        );

        exec($command, $output, $returnCode);

        if ($returnCode === 0 && file_exists($sqlFile) && filesize($sqlFile) > 0) {
            return $sqlFile;
        }

        @unlink($sqlFile);
        return null;
    }

    protected function phpDatabaseExport(string $path, string $timestamp): ?string
    {
        try {
            $tables = \DB::select('SHOW TABLES');
            $dbName = config('backup.database.database');
            $key = "Tables_in_{$dbName}";

            $data = [];
            foreach ($tables as $table) {
                $tableName = $table->$key;
                $data[$tableName] = \DB::table($tableName)->get()->toArray();
            }

            $jsonFile = "{$path}/db_{$timestamp}.json";
            file_put_contents($jsonFile, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            return $jsonFile;
        } catch (\Exception $e) {
            $this->error("  ❌ PHP export gagal: " . $e->getMessage());
            return null;
        }
    }

    protected function addFolderToZip(ZipArchive $zip, string $folder, string $zipPath): void
    {
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($folder, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $file) {
            if ($file->isFile()) {
                $filePath = $file->getRealPath();
                $relativePath = $zipPath . '/' . substr($filePath, strlen($folder) + 1);
                $zip->addFile($filePath, str_replace('\\', '/', $relativePath));
            }
        }
    }

    protected function formatBytes(int $bytes): string
    {
        if ($bytes <= 0) return '0 B';
        $units = ['B', 'KB', 'MB', 'GB'];
        $power = floor(log($bytes, 1024));
        return number_format($bytes / pow(1024, $power), 2) . ' ' . $units[$power];
    }
}
