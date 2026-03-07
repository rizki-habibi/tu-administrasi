<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Staff;

/*
|--------------------------------------------------------------------------
| Routes Khusus: KESISWAAN & KURIKULUM
|--------------------------------------------------------------------------
| Fitur tambahan untuk role kesiswaan_kurikulum:
| - Upload kurikulum (sudah ada di umum via kurikulum.store)
| - View kesiswaan (sudah ada di umum)
|--------------------------------------------------------------------------
*/

// Kurikulum upload khusus role kesiswaan_kurikulum
Route::post('/kurikulum', [Staff\KurikulumController::class, 'store'])->name('kurikulum.store');
