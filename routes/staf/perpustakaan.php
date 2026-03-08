<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Staff;

/*
|--------------------------------------------------------------------------
| Routes Khusus: PERPUSTAKAAN
|--------------------------------------------------------------------------
*/

// Buku CRUD
Route::prefix('buku')->name('buku.')->group(function () {
    Route::get('/', [Staff\BukuController::class, 'index'])->name('index');
    Route::get('/buat', [Staff\BukuController::class, 'create'])->name('create');
    Route::post('/', [Staff\BukuController::class, 'store'])->name('store');
    Route::get('/ekspor', [Staff\BukuController::class, 'export'])->name('ekspor');
    Route::get('/{buku}', [Staff\BukuController::class, 'show'])->name('show');
    Route::get('/{buku}/edit', [Staff\BukuController::class, 'edit'])->name('edit');
    Route::put('/{buku}', [Staff\BukuController::class, 'update'])->name('update');
    Route::delete('/{buku}', [Staff\BukuController::class, 'destroy'])->name('destroy');
});

// Peminjaman Buku
Route::prefix('peminjaman-buku')->name('peminjaman-buku.')->group(function () {
    Route::get('/', [Staff\PeminjamanBukuController::class, 'index'])->name('index');
    Route::get('/buat', [Staff\PeminjamanBukuController::class, 'create'])->name('create');
    Route::post('/', [Staff\PeminjamanBukuController::class, 'store'])->name('store');
    Route::patch('/{peminjaman}/kembali', [Staff\PeminjamanBukuController::class, 'kembalikan'])->name('kembali');
});
