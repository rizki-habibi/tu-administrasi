<?php

namespace App\Console\Commands;

use App\Http\Controllers\DevSystemScanController;
use Illuminate\Console\Command;

/**
 * Artisan command: php artisan dev:scan-all
 * Jalankan full system scan dari terminal.
 */
class DevScanAll extends Command
{
    protected $signature = 'dev:scan-all {--json : Output dalam format JSON}';
    protected $description = 'Scan seluruh project Laravel untuk mendeteksi error (syntax, blade, log, route, model)';

    public function handle(): int
    {
        $this->info('');
        $this->info('╔══════════════════════════════════════════════════╗');
        $this->info('║       🔍 DEV SYSTEM SCAN — FULL PROJECT        ║');
        $this->info('╚══════════════════════════════════════════════════╝');
        $this->info('');

        $scanner = new DevSystemScanController();
        $results = $scanner->runFullScan();

        // JSON mode
        if ($this->option('json')) {
            $this->line(json_encode($results, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            return $results['status'] === 'SAFE' ? self::SUCCESS : self::FAILURE;
        }

        // === STATUS ===
        $status = $results['status'];
        if ($status === 'SAFE') {
            $this->info("  ✅ STATUS: SAFE — Tidak ada error terdeteksi");
        } else {
            $this->error("  ❌ STATUS: HAS ERROR — {$results['total_errors']} error terdeteksi");
        }
        $this->info("  ⏱  Waktu scan: {$results['elapsed_ms']}ms");
        $this->info("  📅 Timestamp: {$results['timestamp']}");
        $this->newLine();

        // === ENVIRONMENT ===
        $this->info('━━━ ENVIRONMENT ━━━');
        $env = $results['environment'];
        $this->line("  PHP: {$env['php_version']} | Laravel: {$env['laravel_version']}");
        $this->line("  ENV: {$env['app_env']} | Debug: " . ($env['app_debug'] ? 'ON' : 'OFF'));
        $this->line("  DB: {$env['db_connection']} | Cache: {$env['cache_driver']} | Session: {$env['session_driver']}");
        $this->line("  Disk Free: {$env['disk_free']}");
        $this->newLine();

        // === PHP SYNTAX ===
        $this->info('━━━ PHP SYNTAX SCAN ━━━');
        $syntax = $results['syntax'];
        $this->line("  File di-scan: {$syntax['scanned']}");
        if (empty($syntax['errors'])) {
            $this->info('  ✅ Tidak ada syntax error.');
        } else {
            foreach ($syntax['errors'] as $err) {
                $this->error("  ✗ {$err['file']}");
                $this->line("    {$err['message']}");
                if ($err['suggestion']) {
                    $this->warn("    {$err['suggestion']}");
                }
            }
        }
        $this->newLine();

        // === BLADE COMPILE ===
        $this->info('━━━ BLADE COMPILE ━━━');
        $blade = $results['blade'];
        if ($blade['success']) {
            $this->info("  ✅ {$blade['message']}");
        } else {
            $this->error("  ✗ {$blade['message']}");
            if (!empty($blade['file'])) {
                $this->line("    File: {$blade['file']}:{$blade['line']}");
            }
            if (!empty($blade['suggestion'])) {
                $this->warn("    {$blade['suggestion']}");
            }
        }
        $this->newLine();

        // === ROUTES ===
        $this->info('━━━ ROUTE CHECK ━━━');
        $route = $results['route'];
        if ($route['success']) {
            $this->info("  ✅ {$route['message']}");
        } else {
            $this->error("  ✗ {$route['message']}");
        }
        $this->newLine();

        // === LOG ERRORS ===
        $this->info('━━━ LOG ERRORS ━━━');
        $log = $results['log'];
        $this->line("  Ukuran log: {$log['file_size']}");
        $this->line("  Error hari ini: {$log['total_error_today']}");
        $this->line("  Parse Error: {$log['total_parse_error']} | Fatal Error: {$log['total_fatal_error']}");

        if (!empty($log['entries'])) {
            $this->newLine();
            $this->line('  10 Error Terbaru:');
            $this->line('  ─────────────────');
            foreach (array_slice($log['entries'], 0, 10) as $i => $entry) {
                $n = $i + 1;
                $levelColor = match ($entry['level']) {
                    'ERROR', 'CRITICAL', 'EMERGENCY' => 'error',
                    'WARNING' => 'warn',
                    default => 'info',
                };
                $this->{$levelColor}("  {$n}. [{$entry['date']} {$entry['time']}] {$entry['level']}");
                $msg = mb_strlen($entry['message']) > 120 ? mb_substr($entry['message'], 0, 120) . '…' : $entry['message'];
                $this->line("     {$msg}");
                if ($entry['file']) {
                    $this->line("     📁 {$entry['file']}:{$entry['line']}");
                }
                if ($entry['suggestion']) {
                    $this->warn("     {$entry['suggestion']}");
                }
            }
        }
        $this->newLine();

        // === MODEL CHECK ===
        $this->info('━━━ MODEL CHECK ━━━');
        $models = $results['model']['models'];
        $okCount  = count(array_filter($models, fn($m) => $m['status'] === 'OK'));
        $errCount = count(array_filter($models, fn($m) => $m['status'] === 'ERROR'));
        $this->line("  Total model: " . count($models) . " (OK: {$okCount}, Error: {$errCount})");

        if ($errCount > 0) {
            foreach ($models as $m) {
                if ($m['status'] === 'ERROR') {
                    $this->error("  ✗ {$m['class']}: {$m['message']}");
                }
            }
        } else {
            $this->info('  ✅ Semua model bisa diinstansiasi.');
        }

        // === FOOTER ===
        $this->newLine();
        $this->info('══════════════════════════════════════════════════');
        if ($status === 'SAFE') {
            $this->info('  🎉 Project aman! Tidak ditemukan masalah.');
        } else {
            $this->error("  ⚠️  Ditemukan {$results['total_errors']} masalah. Perbaiki segera!");
        }
        $this->info('══════════════════════════════════════════════════');
        $this->newLine();

        return $status === 'SAFE' ? self::SUCCESS : self::FAILURE;
    }
}
