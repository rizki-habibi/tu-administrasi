<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Staff;

/*
|--------------------------------------------------------------------------
| Routes Khusus: INVENTARIS - Peminjaman Fasilitas
|--------------------------------------------------------------------------
*/

// Peminjaman Fasilitas
Route::prefix('peminjaman-fasilitas')->name('peminjaman.')->group(function () {
    Route::get('/', [Staff\PeminjamanFasilitasController::class, 'index'])->name('index');
    Route::get('/buat', [Staff\PeminjamanFasilitasController::class, 'create'])->name('create');
    Route::post('/', [Staff\PeminjamanFasilitasController::class, 'store'])->name('store');
    Route::get('/cek-ketersediaan', [Staff\PeminjamanFasilitasController::class, 'cekKetersediaan'])->name('cek');
    Route::get('/{peminjaman}', [Staff\PeminjamanFasilitasController::class, 'show'])->name('show');
    Route::patch('/{peminjaman}/setujui', [Staff\PeminjamanFasilitasController::class, 'setujui'])->name('setujui');
    Route::patch('/{peminjaman}/tolak', [Staff\PeminjamanFasilitasController::class, 'tolak'])->name('tolak');
    Route::patch('/{peminjaman}/selesai', [Staff\PeminjamanFasilitasController::class, 'selesai'])->name('selesai');
});
