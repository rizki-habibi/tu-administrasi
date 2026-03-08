<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Staff;

/*
|--------------------------------------------------------------------------
| Routes Khusus: KEUANGAN
|--------------------------------------------------------------------------
*/

Route::prefix('keuangan')->name('keuangan.')->group(function () {
    Route::get('/', [Staff\KeuanganController::class, 'index'])->name('index');
    Route::get('/buat', [Staff\KeuanganController::class, 'create'])->name('create');
    Route::post('/', [Staff\KeuanganController::class, 'store'])->name('store');
    Route::get('/ekspor', [Staff\KeuanganController::class, 'export'])->name('ekspor');
    Route::get('/{catatan}', [Staff\KeuanganController::class, 'show'])->name('show');
    Route::get('/{catatan}/edit', [Staff\KeuanganController::class, 'edit'])->name('edit');
    Route::put('/{catatan}', [Staff\KeuanganController::class, 'update'])->name('update');
    Route::delete('/{catatan}', [Staff\KeuanganController::class, 'destroy'])->name('destroy');
});
