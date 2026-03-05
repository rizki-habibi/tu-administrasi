<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BerandaUtamaController;
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

Route::get('/', function () {
    return redirect()->route('login');
});

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
