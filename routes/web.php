<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BerandaUtamaController;
use App\Http\Controllers\HalamanUtamaController;
use App\Http\Controllers\KinerjaController;
use App\Http\Controllers\DokumenPublikController;
use App\Http\Controllers\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes (Public & Auth)
|--------------------------------------------------------------------------
| Route umum: login, redirect, home.
| Route per-role dimuat via bootstrap/app.php:
|   - routes/admin.php          (prefix: /admin)
|   - routes/staf.php           (prefix: /staf)
|   - routes/kepala-sekolah.php (prefix: /kepala-sekolah)
|--------------------------------------------------------------------------
*/

Route::get('/', [HalamanUtamaController::class, 'index'])->name('halaman-utama');

// Dokumen & Kinerja (portal publik dengan sidebar kiri)
Route::prefix('dokumen')->name('dokumen.')->group(function () {
    Route::get('/', [DokumenPublikController::class, 'beranda'])->name('beranda');
    Route::get('/arsip', [DokumenPublikController::class, 'arsip'])->name('arsip');
    Route::get('/saran', [DokumenPublikController::class, 'saran'])->name('saran');
    Route::post('/saran', [DokumenPublikController::class, 'storeSaran'])->name('saran.store');
    Route::get('/detail/{kontenPublik}', [DokumenPublikController::class, 'show'])->name('show');
    Route::get('/{kategori}', [DokumenPublikController::class, 'kategori'])->name('kategori');
});

// Backward compatibility: redirect /kinerja ke /dokumen
Route::get('/kinerja', fn() => redirect()->route('dokumen.beranda'))->name('kinerja');
Route::post('/saran', [DokumenPublikController::class, 'storeSaran'])->name('saran.store');

// Auth Routes (manual — pengganti Auth::routes karena controller sudah di-rename)
Route::get('login', [Auth\MasukController::class, 'showLoginForm'])->name('login');
Route::post('login', [Auth\MasukController::class, 'login']);
Route::post('logout', [Auth\MasukController::class, 'logout'])->name('logout');

Route::get('password/reset', [Auth\LupaKataSandiController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [Auth\LupaKataSandiController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [Auth\AturUlangKataSandiController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [Auth\AturUlangKataSandiController::class, 'reset'])->name('password.update');

Route::get('/home', [BerandaUtamaController::class, 'index'])->name('home');

/*
|--------------------------------------------------------------------------
| Dev System Scan (hanya di local)
|--------------------------------------------------------------------------
*/
if (app()->environment('local')) {
    Route::prefix('dev')->middleware(\App\Http\Middleware\HanyaLokal::class)->group(function () {
        Route::get('/system-scan', [\App\Http\Controllers\DevSystemScanController::class, 'index'])->name('dev.system-scan');
        Route::get('/system-scan/refresh', [\App\Http\Controllers\DevSystemScanController::class, 'scan'])->name('dev.system-scan.refresh');
        Route::post('/system-scan/clear-log', [\App\Http\Controllers\DevSystemScanController::class, 'clearLog'])->name('dev.system-scan.clear-log');
    });
}
