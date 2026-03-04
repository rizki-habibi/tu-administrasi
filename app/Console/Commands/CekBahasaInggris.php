<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CekBahasaInggris extends Command
{
    protected $signature = 'cek:bahasa
                            {--fix : Tampilkan saran perbaikan otomatis}
                            {--folder= : Cek folder tertentu saja (misal: resources/views)}';

    protected $description = 'Deteksi nama file, folder, variabel, dan kolom yang masih menggunakan bahasa Inggris';

    /**
     * Daftar kata Inggris umum yang sering tertinggal di codebase Indonesia.
     * Key = Inggris, Value = Saran Indonesia
     */
    private array $kamusInggrisIndonesia = [
        // === File / Folder names ===
        'leave'           => 'izin',
        'attendance'      => 'kehadiran',
        'notification'    => 'notifikasi',
        'document'        => 'dokumen',
        'report'          => 'laporan',
        'event'           => 'agenda',
        'reminder'        => 'pengingat',
        'profile'         => 'profil',
        'setting'         => 'pengaturan',
        'dashboard'       => 'beranda',
        'student'         => 'siswa',
        'teacher'         => 'guru',
        'schedule'        => 'jadwal',
        'budget'          => 'anggaran',
        'letter'          => 'surat',
        'achievement'     => 'prestasi',
        'violation'       => 'pelanggaran',
        'evaluation'      => 'evaluasi',
        'curriculum'      => 'kurikulum',

        // === Column / Variable names ===
        'name'            => 'nama',
        'title'           => 'judul',
        'description'     => 'deskripsi',
        'message'         => 'pesan',
        'type'            => 'jenis',
        'category'        => 'kategori',
        'status'          => 'status',  // ini boleh tetap
        'priority'        => 'prioritas',
        'address'         => 'alamat',
        'phone'           => 'telepon',
        'email'           => 'email',   // ini boleh tetap
        'password'        => 'password', // ini boleh tetap
        'content'         => 'konten',
        'attachment'      => 'lampiran',
        'reason'          => 'alasan',
        'note'            => 'catatan',
        'role'            => 'peran',
        'active'          => 'aktif',
        'read_at'         => 'sudah_dibaca',
        'is_read'         => 'sudah_dibaca',
        'start_date'      => 'tanggal_mulai',
        'end_date'        => 'tanggal_selesai',
        'start_time'      => 'waktu_mulai',
        'end_time'        => 'waktu_selesai',
        'due_date'        => 'tenggat',
        'event_date'      => 'tanggal_acara',
        'location'        => 'lokasi',
        'created_by'      => 'dibuat_oleh',
        'approved_by'     => 'disetujui_oleh',
        'admin_notes'     => 'catatan_admin',
        'class'           => 'kelas',
        'subject'         => 'mata_pelajaran',
        'academic_year'   => 'tahun_ajaran',
        'class_level'     => 'tingkat_kelas',
        'is_shared'       => 'dibagikan',
        'template'        => 'templat',
        'user_id'         => 'pengguna_id',
        'clock_in'        => 'jam_masuk',
        'clock_out'       => 'jam_pulang',

        // === Route / URL patterns ===
        'create'          => 'buat',
        'edit'            => 'edit',  // ini boleh tetap
        'delete'          => 'hapus',
        'update'          => 'perbarui',
        'store'           => 'simpan',
        'index'           => 'index', // ini boleh tetap
        'show'            => 'lihat',
        'destroy'         => 'hapus',
    ];

    /**
     * Kata-kata yang boleh tetap bahasa Inggris (pengecualian).
     */
    private array $pengecualian = [
        'status', 'email', 'password', 'edit', 'index', 'id', 'pdf', 'csv',
        'api', 'url', 'http', 'https', 'ajax', 'json', 'html', 'css', 'js',
        'php', 'sql', 'app', 'web', 'vue', 'blade', 'auth', 'csrf', 'token',
        'admin', 'middleware', 'controller', 'model', 'migration', 'seeder',
        'factory', 'route', 'config', 'public', 'storage', 'vendor', 'node_modules',
        'bootstrap', 'cache', 'log', 'queue', 'session', 'mail', 'console',
        'artisan', 'composer', 'package', 'webpack', 'vite', 'mix',
        'store', 'create', 'show', 'destroy', 'update', // Laravel CRUD conventions
        'event', // DB enum value di tabel notifikasi
    ];

    public function handle(): int
    {
        $this->info('');
        $this->info('╔══════════════════════════════════════════════════╗');
        $this->info('║   🔍 CEK BAHASA INGGRIS - TU Administrasi      ║');
        $this->info('║   Deteksi otomatis kata Inggris di codebase     ║');
        $this->info('╚══════════════════════════════════════════════════╝');
        $this->info('');

        $basePath = base_path();
        $folder = $this->option('folder');
        $showFix = $this->option('fix');

        $temuanFolder = $this->cekNamaFolder($basePath, $folder);
        $temuanFile = $this->cekNamaFile($basePath, $folder);
        $temuanKonten = $this->cekKontenFile($basePath, $folder);

        $totalTemuan = count($temuanFolder) + count($temuanFile) + count($temuanKonten);

        // === Laporan Folder ===
        if (count($temuanFolder) > 0) {
            $this->warn('');
            $this->warn('📁 FOLDER DENGAN NAMA INGGRIS (' . count($temuanFolder) . ' temuan):');
            $this->line(str_repeat('─', 60));
            foreach ($temuanFolder as $t) {
                $this->line("  ❌ {$t['path']}");
                if ($showFix && isset($t['saran'])) {
                    $this->info("     ✅ Saran: {$t['saran']}");
                }
            }
        }

        // === Laporan File ===
        if (count($temuanFile) > 0) {
            $this->warn('');
            $this->warn('📄 FILE DENGAN NAMA INGGRIS (' . count($temuanFile) . ' temuan):');
            $this->line(str_repeat('─', 60));
            foreach ($temuanFile as $t) {
                $this->line("  ❌ {$t['path']}");
                if ($showFix && isset($t['saran'])) {
                    $this->info("     ✅ Saran: {$t['saran']}");
                }
            }
        }

        // === Laporan Konten ===
        if (count($temuanKonten) > 0) {
            $this->warn('');
            $this->warn('📝 KONTEN DENGAN KATA INGGRIS (' . count($temuanKonten) . ' temuan):');
            $this->line(str_repeat('─', 60));

            $grouped = collect($temuanKonten)->groupBy('file');
            foreach ($grouped as $file => $items) {
                $this->line("  📄 {$file}");
                foreach ($items as $item) {
                    $this->line("     Baris {$item['baris']}: \"{$item['kata']}\"");
                    if ($showFix && isset($item['saran'])) {
                        $this->info("       ✅ Saran: \"{$item['kata']}\" → \"{$item['saran']}\"");
                    }
                }
            }
        }

        // === Ringkasan ===
        $this->info('');
        $this->info('═══════════════════════════════════════════════════');
        if ($totalTemuan === 0) {
            $this->info('✅ Tidak ditemukan kata Inggris yang perlu diperbaiki!');
        } else {
            $this->warn("⚠️  Total temuan: {$totalTemuan}");
            $this->line("   📁 Folder: " . count($temuanFolder));
            $this->line("   📄 File  : " . count($temuanFile));
            $this->line("   📝 Konten: " . count($temuanKonten));

            if (!$showFix) {
                $this->info('');
                $this->info('💡 Jalankan dengan --fix untuk melihat saran perbaikan:');
                $this->info('   php artisan cek:bahasa --fix');
            }
        }
        $this->info('═══════════════════════════════════════════════════');
        $this->info('');

        return $totalTemuan > 0 ? 1 : 0;
    }

    private function cekNamaFolder(string $basePath, ?string $folder): array
    {
        $temuan = [];
        $scanDirs = $folder
            ? [base_path($folder)]
            : [
                app_path(),
                resource_path('views'),
                database_path('migrations'),
                base_path('routes'),
            ];

        $kataFolder = [
            'leave' => 'izin',
            'attendance' => 'kehadiran',
            'notification' => 'notifikasi',
            'notifications' => 'notifikasi',
            'document' => 'dokumen',
            'documents' => 'dokumen',
            'report' => 'laporan',
            'reports' => 'laporan',
            'event' => 'agenda',
            'events' => 'agenda',
            'reminder' => 'pengingat',
            'reminders' => 'pengingat',
            'profile' => 'profil',
            'setting' => 'pengaturan',
            'settings' => 'pengaturan',
            'dashboard' => 'beranda',
            'student' => 'kesiswaan',
            'students' => 'kesiswaan',
            'teacher' => 'guru',
            'teachers' => 'guru',
            'schedule' => 'jadwal',
            'budget' => 'anggaran',
            'letter' => 'surat',
            'letters' => 'surat',
            'layouts' => 'tata-letak',
        ];

        foreach ($scanDirs as $dir) {
            if (!is_dir($dir)) continue;
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::SELF_FIRST
            );
            foreach ($iterator as $item) {
                if (!$item->isDir()) continue;
                $dirName = strtolower($item->getBasename());
                $relPath = str_replace($basePath . DIRECTORY_SEPARATOR, '', $item->getPathname());

                foreach ($kataFolder as $en => $id) {
                    if ($dirName === $en || str_contains($dirName, $en)) {
                        $temuan[] = [
                            'path'  => $relPath,
                            'kata'  => $en,
                            'saran' => str_replace($en, $id, $relPath),
                        ];
                    }
                }
            }
        }

        return $temuan;
    }

    private function cekNamaFile(string $basePath, ?string $folder): array
    {
        $temuan = [];
        $scanDirs = $folder
            ? [base_path($folder)]
            : [resource_path('views')];

        $kataFile = [
            'leave'         => 'izin',
            'notification'  => 'notifikasi',
            'dashboard'     => 'beranda',
            'setting'       => 'pengaturan',
            'layouts'       => 'tata-letak',
        ];

        foreach ($scanDirs as $dir) {
            if (!is_dir($dir)) continue;
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS)
            );
            foreach ($iterator as $file) {
                if (!$file->isFile()) continue;
                $fileName = strtolower($file->getBasename());
                $relPath = str_replace($basePath . DIRECTORY_SEPARATOR, '', $file->getPathname());

                foreach ($kataFile as $en => $id) {
                    if (str_contains($fileName, $en)) {
                        $temuan[] = [
                            'path'  => $relPath,
                            'kata'  => $en,
                            'saran' => str_replace($en, $id, $relPath),
                        ];
                    }
                }
            }
        }

        return $temuan;
    }

    private function cekKontenFile(string $basePath, ?string $folder): array
    {
        $temuan = [];
        $scanDirs = $folder
            ? [base_path($folder)]
            : [resource_path('views')];

        // Hanya cek kata yang penting (bukan pengecualian Laravel)
        $kataKonten = array_filter($this->kamusInggrisIndonesia, function ($saran, $kata) {
            return !in_array($kata, $this->pengecualian) && $kata !== $saran;
        }, ARRAY_FILTER_USE_BOTH);

        foreach ($scanDirs as $dir) {
            if (!is_dir($dir)) continue;
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS)
            );
            foreach ($iterator as $file) {
                if (!$file->isFile()) continue;
                $ext = strtolower($file->getExtension());
                if (!in_array($ext, ['php', 'blade'])) {
                    // Check .blade.php
                    if (!str_ends_with(strtolower($file->getFilename()), '.blade.php')) continue;
                }

                $relPath = str_replace($basePath . DIRECTORY_SEPARATOR, '', $file->getPathname());
                $lines = file($file->getPathname(), FILE_IGNORE_NEW_LINES);

                foreach ($lines as $lineNum => $line) {
                    foreach ($kataKonten as $en => $id) {
                        // Cek dalam name="...", kolom database, atau variabel
                        $patterns = [
                            "/name=['\"]" . preg_quote($en, '/') . "['\"]/i",
                            "/->whereNull\(['\"]" . preg_quote($en, '/') . "['\"]\)/i",
                            "/->where\(['\"]" . preg_quote($en, '/') . "['\"]/i",
                            "/\\\$request->" . preg_quote($en, '/') . "\\b/i",
                            "/['\"]" . preg_quote($en, '/') . "['\"]\\s*=>/i",
                        ];

                        foreach ($patterns as $pattern) {
                            if (preg_match($pattern, $line)) {
                                $temuan[] = [
                                    'file'  => $relPath,
                                    'baris' => $lineNum + 1,
                                    'kata'  => $en,
                                    'saran' => $id,
                                    'baris_teks' => trim($line),
                                ];
                                break; // Satu kata per baris cukup
                            }
                        }
                    }
                }
            }
        }

        return $temuan;
    }
}
