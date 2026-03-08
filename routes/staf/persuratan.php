<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Staff;

/*
|--------------------------------------------------------------------------
| Routes Khusus: PERSURATAN
|--------------------------------------------------------------------------
*/

// Full CRUD Surat (mengganti route umum yang hanya create+view)
Route::prefix('surat-kelola')->name('surat-kelola.')->group(function () {
    Route::get('/{surat}/edit', [Staff\SuratKelolaController::class, 'edit'])->name('edit');
    Route::put('/{surat}', [Staff\SuratKelolaController::class, 'update'])->name('update');
    Route::delete('/{surat}', [Staff\SuratKelolaController::class, 'destroy'])->name('destroy');
    Route::patch('/{surat}/status', [Staff\SuratKelolaController::class, 'updateStatus'])->name('status');
    Route::get('/ekspor', [Staff\SuratKelolaController::class, 'export'])->name('ekspor');
});

// Disposisi - buat disposisi (tambahan dari hanya lihat)
Route::post('/disposisi', [Staff\DisposisiController::class, 'store'])->name('disposisi.store');
Route::get('/disposisi/buat', [Staff\DisposisiController::class, 'create'])->name('disposisi.create');
