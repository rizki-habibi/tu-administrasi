<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;

/**
 * Dev System Scan Controller
 * Mendeteksi SEMUA kemungkinan error dalam project Laravel secara otomatis.
 * Hanya bisa diakses jika APP_ENV=local (via LocalOnly middleware).
 */
class DevSystemScanController extends Controller
{
    /**
     * Tampilkan halaman system scan.
     */
    public function index()
    {
        $results = $this->runFullScan();
        return view('dev.system-scan', compact('results'));
    }

    /**
     * API endpoint — refresh scan via AJAX.
     */
    public function scan()
    {
        $results = $this->runFullScan();
        return response()->json($results);
    }

    /**
     * Clear laravel.log.
     */
    public function clearLog()
    {
        $logPath = storage_path('logs/laravel.log');
        if (File::exists($logPath)) {
            File::put($logPath, '');
        }
        return response()->json(['success' => true, 'pesan' => 'Log berhasil dibersihkan.']);
    }

    /**
     * Jalankan scan lengkap.
     */
    public function runFullScan(): array
    {
        $startTime = microtime(true);

        $logErrors    = $this->scanLogs();
        $syntaxErrors = $this->scanPhpSyntax();
        $bladeResult  = $this->scanBladeCompile();
        $routeResult  = $this->scanRoutes();
        $modelCheck   = $this->scanModels();
        $envCheck     = $this->scanEnvironment();

        $elapsed = round((microtime(true) - $startTime) * 1000);

        $totalErrors = count($syntaxErrors['errors'])
            + $logErrors['total_error_today']
            + ($bladeResult['success'] ? 0 : 1)
            + ($routeResult['success'] ? 0 : 1);

        return [
            'timestamp'      => now()->format('d M Y H:i:s'),
            'elapsed_ms'     => $elapsed,
            'status'         => $totalErrors === 0 ? 'SAFE' : 'HAS_ERROR',
            'total_errors'   => $totalErrors,
            'log'            => $logErrors,
            'syntax'         => $syntaxErrors,
            'blade'          => $bladeResult,
            'route'          => $routeResult,
            'model'          => $modelCheck,
            'environment'    => $envCheck,
        ];
    }

    /**
     * 1. LOG DETECTOR — Parse laravel.log.
     */
    private function scanLogs(): array
    {
        $logPath = storage_path('logs/laravel.log');
        $entries = [];
        $totalErrorToday  = 0;
        $totalParseError  = 0;
        $totalFatalError  = 0;

        if (!File::exists($logPath)) {
            return [
                'entries' => [],
                'total_error_today' => 0,
                'total_parse_error' => 0,
                'total_fatal_error' => 0,
                'file_size'         => '0 B',
            ];
        }

        $fileSize = $this->formatBytes(File::size($logPath));
        $content  = File::get($logPath);
        $today    = now()->format('Y-m-d');

        // Parse log entries — Laravel format: [YYYY-MM-DD HH:mm:ss] ENV.LEVEL: Message
        preg_match_all(
            '/\[(\d{4}-\d{2}-\d{2})\s(\d{2}:\d{2}:\d{2})\]\s\w+\.(ERROR|WARNING|INFO|CRITICAL|EMERGENCY|ALERT|NOTICE|DEBUG):\s(.+?)(?=\n\[\d{4}-|\Z)/s',
            $content,
            $matches,
            PREG_SET_ORDER
        );

        foreach (array_slice(array_reverse($matches), 0, 50) as $m) {
            $date    = $m[1];
            $time    = $m[2];
            $level   = $m[3];
            $message = trim($m[4]);

            // Extract file & line from stack trace
            $file = $line = null;
            if (preg_match('/in\s(.+?):(\d+)/', $message, $fm)) {
                $file = $fm[1];
                $line = $fm[2];
            }

            // Cek parse error + fatal error
            if (stripos($message, 'ParseError') !== false || stripos($message, 'Parse error') !== false) {
                $totalParseError++;
            }
            if (stripos($message, 'FatalError') !== false || stripos($message, 'Fatal error') !== false) {
                $totalFatalError++;
            }

            // Count today's errors
            if ($date === $today && in_array($level, ['ERROR', 'CRITICAL', 'EMERGENCY'])) {
                $totalErrorToday++;
            }

            // Truncate super long messages
            $shortMsg = mb_strlen($message) > 300 ? mb_substr($message, 0, 300) . '…' : $message;

            // Suggestion
            $suggestion = $this->suggestFix($message);

            $entries[] = [
                'date'       => $date,
                'time'       => $time,
                'level'      => $level,
                'message'    => $shortMsg,
                'file'       => $file,
                'line'       => $line,
                'suggestion' => $suggestion,
            ];
        }

        return [
            'entries'           => array_slice($entries, 0, 30),
            'total_error_today' => $totalErrorToday,
            'total_parse_error' => $totalParseError,
            'total_fatal_error' => $totalFatalError,
            'file_size'         => $fileSize,
        ];
    }

    /**
     * 2. FULL PROJECT SYNTAX SCANNER — php -l.
     */
    private function scanPhpSyntax(): array
    {
        $dirs = ['app', 'routes', 'config', 'database'];
        $errors  = [];
        $scanned = 0;

        foreach ($dirs as $dir) {
            $path = base_path($dir);
            if (!File::isDirectory($path)) continue;

            $files = File::allFiles($path);
            foreach ($files as $file) {
                if ($file->getExtension() !== 'php') continue;
                $scanned++;

                $output = [];
                $code   = 0;
                exec('php -l ' . escapeshellarg($file->getPathname()) . ' 2>&1', $output, $code);

                if ($code !== 0) {
                    $errorMsg = implode("\n", $output);
                    $suggestion = $this->suggestFix($errorMsg);

                    $errors[] = [
                        'file'       => str_replace(base_path() . DIRECTORY_SEPARATOR, '', $file->getPathname()),
                        'message'    => $errorMsg,
                        'suggestion' => $suggestion,
                    ];
                }
            }
        }

        return [
            'scanned' => $scanned,
            'errors'  => $errors,
        ];
    }

    /**
     * 3. BLADE SAFE CHECK.
     */
    private function scanBladeCompile(): array
    {
        try {
            Artisan::call('view:clear');
            Artisan::call('view:cache');
            $output = Artisan::output();

            return [
                'success' => true,
                'message' => 'Semua Blade template berhasil di-compile.',
                'output'  => trim($output),
            ];
        } catch (\Throwable $e) {
            return [
                'success'    => false,
                'message'    => $e->getMessage(),
                'file'       => $e->getFile(),
                'line'       => $e->getLine(),
                'suggestion' => $this->suggestFix($e->getMessage()),
            ];
        }
    }

    /**
     * 4. ROUTE COMPILER CHECK.
     */
    private function scanRoutes(): array
    {
        try {
            Artisan::call('route:list', ['--json' => true]);
            $json = json_decode(Artisan::output(), true);
            $count = is_array($json) ? count($json) : 0;

            return [
                'success'     => true,
                'total_routes' => $count,
                'message'     => "Total {$count} route terdaftar.",
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * 5. MODEL CHECK — pastikan semua model bisa diinstansiasi.
     */
    private function scanModels(): array
    {
        $modelPath = app_path('Models');
        $results   = [];

        if (!File::isDirectory($modelPath)) {
            return ['models' => []];
        }

        foreach (File::files($modelPath) as $file) {
            if ($file->getExtension() !== 'php') continue;

            $className = 'App\\Models\\' . $file->getFilenameWithoutExtension();

            try {
                if (class_exists($className)) {
                    $instance = new $className;
                    $table    = $instance->getTable();
                    $fillable = $instance->getFillable();

                    $results[] = [
                        'class'    => $className,
                        'table'    => $table,
                        'fillable' => count($fillable),
                        'status'   => 'OK',
                    ];
                }
            } catch (\Throwable $e) {
                $results[] = [
                    'class'   => $className,
                    'status'  => 'ERROR',
                    'message' => $e->getMessage(),
                ];
            }
        }

        return ['models' => $results];
    }

    /**
     * 6. ENVIRONMENT CHECK.
     */
    private function scanEnvironment(): array
    {
        return [
            'app_env'    => config('app.env'),
            'app_debug'  => config('app.debug'),
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'db_connection'   => config('database.default'),
            'cache_driver'    => config('cache.default'),
            'session_driver'  => config('session.driver'),
            'disk_free'       => $this->formatBytes(disk_free_space(base_path())),
        ];
    }

    /**
     * Auto-fix SUGGESTION berdasarkan pesan error.
     */
    private function suggestFix(string $message): ?string
    {
        $msg = strtolower($message);

        if (str_contains($msg, 'unclosed') || str_contains($msg, 'unexpected end of file')) {
            return '🔧 Periksa bracket/kurung yang belum ditutup. Pastikan setiap { } [ ] ( ) berpasangan.';
        }
        if (str_contains($msg, 'parseerror') || str_contains($msg, 'parse error') || str_contains($msg, 'syntax error')) {
            return '🔧 Syntax error. Cek tanda ; koma, bracket, atau keyword yang salah tulis.';
        }
        if (str_contains($msg, 'undefined variable')) {
            preg_match('/undefined variable[:\s]*\$?(\w+)/i', $message, $m);
            $var = $m[1] ?? '?';
            return "🔧 Variabel \${$var} belum didefinisikan. Pastikan dikirim dari controller atau dideklarasikan.";
        }
        if (str_contains($msg, 'undefined method')) {
            return '🔧 Method tidak ditemukan. Cek nama method dan pastikan model/class sudah benar.';
        }
        if (str_contains($msg, 'call to a member function') && str_contains($msg, 'on null')) {
            return '🔧 Memanggil method pada variabel NULL. Pastikan data tidak kosong sebelum memanggil method.';
        }
        if (str_contains($msg, 'class') && str_contains($msg, 'not found')) {
            return '🔧 Class tidak ditemukan. Jalankan `composer dump-autoload` atau cek namespace/import.';
        }
        if (str_contains($msg, 'column not found') || str_contains($msg, 'unknown column')) {
            return '🔧 Kolom tidak ditemukan di database. Cek migration atau nama kolom yang direferensikan.';
        }
        if (str_contains($msg, 'table') && (str_contains($msg, 'not found') || str_contains($msg, "doesn't exist"))) {
            return '🔧 Tabel tidak ada. Jalankan `php artisan migrate` untuk membuat tabel.';
        }
        if (str_contains($msg, 'view') && str_contains($msg, 'not found')) {
            return '🔧 View blade tidak ditemukan. Cek path view di controller return view().';
        }
        if (str_contains($msg, 'route') && str_contains($msg, 'not defined')) {
            return '🔧 Route belum didefinisikan. Cek file routes/ dan pastikan route name benar.';
        }
        if (str_contains($msg, 'csrf') || str_contains($msg, '419')) {
            return '🔧 CSRF token expired. Pastikan form memiliki @csrf directive.';
        }
        if (str_contains($msg, 'tokenmismatchexception')) {
            return '🔧 Token mismatch. Session mungkin expired. Coba refresh halaman.';
        }
        if (str_contains($msg, 'permission denied') || str_contains($msg, 'not writable')) {
            return '🔧 Masalah permission. Jalankan: chmod -R 775 storage bootstrap/cache';
        }

        return null;
    }

    /**
     * Format bytes ke human readable.
     */
    private function formatBytes($bytes): string
    {
        if ($bytes === false || $bytes < 0) return '0 B';
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        return round($bytes, 2) . ' ' . $units[$i];
    }
}
