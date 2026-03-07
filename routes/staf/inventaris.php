<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Staff;

/*
|--------------------------------------------------------------------------
| Routes Khusus: INVENTARIS / SARPRAS
|--------------------------------------------------------------------------
| Fitur tambahan untuk role inventaris:
| - CRUD inventaris (tambah, edit, hapus barang)
| - Laporan kerusakan CRUD
|--------------------------------------------------------------------------
*/

// Inventaris CRUD khusus role inventaris
Route::get('/inventaris/buat', [Staff\InventarisController::class, 'create'])->name('inventaris.create');
Route::post('/inventaris', [Staff\InventarisController::class, 'store'])->name('inventaris.store');
Route::get('/inventaris/{inventaris}/edit', [Staff\InventarisController::class, 'edit'])->name('inventaris.edit');
Route::put('/inventaris/{inventaris}', [Staff\InventarisController::class, 'update'])->name('inventaris.update');
Route::delete('/inventaris/{inventaris}', [Staff\InventarisController::class, 'destroy'])->name('inventaris.destroy');

// Laporan Kerusakan CRUD khusus role inventaris
Route::get('/kerusakan', [Staff\InventarisController::class, 'kerusakanIndex'])->name('kerusakan.index');
Route::get('/kerusakan/buat', [Staff\InventarisController::class, 'kerusakanCreate'])->name('kerusakan.create');
Route::post('/kerusakan', [Staff\InventarisController::class, 'kerusakanStore'])->name('kerusakan.store');
Route::get('/kerusakan/{kerusakan}', [Staff\InventarisController::class, 'kerusakanShow'])->name('kerusakan.show');
Route::patch('/kerusakan/{kerusakan}/status', [Staff\InventarisController::class, 'kerusakanUpdateStatus'])->name('kerusakan.update-status');
