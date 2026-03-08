<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Staff;

/*
|--------------------------------------------------------------------------
| Routes Khusus: KESISWAAN & KURIKULUM
|--------------------------------------------------------------------------
*/

// Kesiswaan CRUD (siswa)
Route::prefix('kesiswaan-kelola')->name('kesiswaan-kelola.')->group(function () {
    Route::get('/buat', [Staff\KesiswaanKelolaController::class, 'create'])->name('create');
    Route::post('/', [Staff\KesiswaanKelolaController::class, 'store'])->name('store');
    Route::get('/{siswa}/edit', [Staff\KesiswaanKelolaController::class, 'edit'])->name('edit');
    Route::put('/{siswa}', [Staff\KesiswaanKelolaController::class, 'update'])->name('update');
    Route::delete('/{siswa}', [Staff\KesiswaanKelolaController::class, 'destroy'])->name('destroy');
    Route::get('/ekspor', [Staff\KesiswaanKelolaController::class, 'export'])->name('ekspor');
});

// Kurikulum CRUD
Route::prefix('kurikulum-kelola')->name('kurikulum-kelola.')->group(function () {
    Route::get('/buat', [Staff\KurikulumKelolaController::class, 'create'])->name('create');
    Route::post('/', [Staff\KurikulumKelolaController::class, 'store'])->name('store');
    Route::get('/{kurikulum}/edit', [Staff\KurikulumKelolaController::class, 'edit'])->name('edit');
    Route::put('/{kurikulum}', [Staff\KurikulumKelolaController::class, 'update'])->name('update');
    Route::delete('/{kurikulum}', [Staff\KurikulumKelolaController::class, 'destroy'])->name('destroy');
});

// Pelanggaran Siswa
Route::prefix('pelanggaran')->name('pelanggaran.')->group(function () {
    Route::get('/', [Staff\PelanggaranController::class, 'index'])->name('index');
    Route::get('/buat', [Staff\PelanggaranController::class, 'create'])->name('create');
    Route::post('/', [Staff\PelanggaranController::class, 'store'])->name('store');
    Route::get('/{pelanggaran}', [Staff\PelanggaranController::class, 'show'])->name('show');
});

// Prestasi Siswa
Route::prefix('prestasi')->name('prestasi.')->group(function () {
    Route::get('/', [Staff\PrestasiController::class, 'index'])->name('index');
    Route::get('/buat', [Staff\PrestasiController::class, 'create'])->name('create');
    Route::post('/', [Staff\PrestasiController::class, 'store'])->name('store');
    Route::get('/{prestasi}', [Staff\PrestasiController::class, 'show'])->name('show');
});
