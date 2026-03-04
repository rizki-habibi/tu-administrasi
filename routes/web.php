<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

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

Auth::routes(['register' => false]);

Route::get('/home', [HomeController::class, 'index'])->name('home');

/*
|--------------------------------------------------------------------------
| Dev System Scan (hanya di local)
|--------------------------------------------------------------------------
*/
if (app()->environment('local')) {
    Route::prefix('dev')->middleware(\App\Http\Middleware\LocalOnly::class)->group(function () {
        Route::get('/system-scan', [\App\Http\Controllers\DevSystemScanController::class, 'index'])->name('dev.system-scan');
        Route::get('/system-scan/refresh', [\App\Http\Controllers\DevSystemScanController::class, 'scan'])->name('dev.system-scan.refresh');
        Route::post('/system-scan/clear-log', [\App\Http\Controllers\DevSystemScanController::class, 'clearLog'])->name('dev.system-scan.clear-log');
    });
}
