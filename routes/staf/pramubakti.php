<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Staff;

/*
|--------------------------------------------------------------------------
| Routes Khusus: PRAMU BAKTI
|--------------------------------------------------------------------------
*/

// Laporan Kebersihan / Pemeliharaan
Route::prefix('pemeliharaan')->name('pemeliharaan.')->group(function () {
    Route::get('/', [Staff\PemeliharaanController::class, 'index'])->name('index');
    Route::get('/buat', [Staff\PemeliharaanController::class, 'create'])->name('create');
    Route::post('/', [Staff\PemeliharaanController::class, 'store'])->name('store');
    Route::get('/{laporan}', [Staff\PemeliharaanController::class, 'show'])->name('show');
    Route::get('/{laporan}/edit', [Staff\PemeliharaanController::class, 'edit'])->name('edit');
    Route::put('/{laporan}', [Staff\PemeliharaanController::class, 'update'])->name('update');
    Route::delete('/{laporan}', [Staff\PemeliharaanController::class, 'destroy'])->name('destroy');
});
