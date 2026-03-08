<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Staff;

/*
|--------------------------------------------------------------------------
| Routes Khusus: KEPEGAWAIAN
|--------------------------------------------------------------------------
*/

// Dokumen Kepegawaian CRUD (manage untuk semua staf)
Route::prefix('dok-kepegawaian')->name('dok-kepegawaian.')->group(function () {
    Route::get('/', [Staff\DokKepegawaianController::class, 'index'])->name('index');
    Route::get('/buat', [Staff\DokKepegawaianController::class, 'create'])->name('create');
    Route::post('/', [Staff\DokKepegawaianController::class, 'store'])->name('store');
    Route::get('/{dokumen}', [Staff\DokKepegawaianController::class, 'show'])->name('show');
    Route::get('/{dokumen}/edit', [Staff\DokKepegawaianController::class, 'edit'])->name('edit');
    Route::put('/{dokumen}', [Staff\DokKepegawaianController::class, 'update'])->name('update');
    Route::delete('/{dokumen}', [Staff\DokKepegawaianController::class, 'destroy'])->name('destroy');
    Route::get('-ekspor', [Staff\DokKepegawaianController::class, 'export'])->name('ekspor');
});
