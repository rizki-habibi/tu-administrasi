<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Magang;

/*
|--------------------------------------------------------------------------
| Magang Routes (Staff Magang / Intern)
|--------------------------------------------------------------------------
| Prefix: /magang
| Name: magang.*
| Middleware: auth, role:magang
|--------------------------------------------------------------------------
*/

// Beranda
Route::get('/beranda', [Magang\BerandaController::class, 'index'])->name('beranda');

/*
|--------------------------------------------------------------------------
| Kehadiran
|--------------------------------------------------------------------------
*/
Route::prefix('kehadiran')->name('kehadiran.')->group(function () {
    Route::get('/', [Magang\KehadiranController::class, 'index'])->name('index');
    Route::post('/masuk', [Magang\KehadiranController::class, 'clockIn'])->name('masuk');
    Route::post('/pulang', [Magang\KehadiranController::class, 'clockOut'])->name('pulang');
    Route::get('/ekspor', [Magang\KehadiranController::class, 'export'])->name('ekspor');
    Route::get('/{kehadiran}', [Magang\KehadiranController::class, 'show'])->name('show');
});

/*
|--------------------------------------------------------------------------
| Logbook Harian
|--------------------------------------------------------------------------
*/
Route::resource('logbook', Magang\LogbookController::class)->parameters(['logbook' => 'logbook']);
Route::get('logbook-ekspor', [Magang\LogbookController::class, 'export'])->name('logbook.ekspor');

/*
|--------------------------------------------------------------------------
| Pengajuan Izin
|--------------------------------------------------------------------------
*/
Route::prefix('izin')->name('izin.')->group(function () {
    Route::get('/', [Magang\IzinController::class, 'index'])->name('index');
    Route::get('/buat', [Magang\IzinController::class, 'create'])->name('create');
    Route::post('/', [Magang\IzinController::class, 'store'])->name('store');
    Route::get('/{izin}', [Magang\IzinController::class, 'show'])->name('show');
    Route::delete('/{izin}', [Magang\IzinController::class, 'destroy'])->name('destroy');
});

/*
|--------------------------------------------------------------------------
| Kegiatan / Penugasan
|--------------------------------------------------------------------------
*/
Route::resource('kegiatan', Magang\KegiatanController::class);
Route::get('kegiatan-ekspor', [Magang\KegiatanController::class, 'export'])->name('kegiatan.ekspor');

/*
|--------------------------------------------------------------------------
| Notifikasi
|--------------------------------------------------------------------------
*/
Route::prefix('notifikasi')->name('notifikasi.')->group(function () {
    Route::get('/', [Magang\NotifikasiController::class, 'index'])->name('index');
    Route::get('/json', [Magang\NotifikasiController::class, 'json'])->name('json');
    Route::patch('/{notifikasi}/baca', [Magang\NotifikasiController::class, 'baca'])->name('baca');
    Route::post('/baca-semua', [Magang\NotifikasiController::class, 'bacaSemua'])->name('baca-semua');
});

/*
|--------------------------------------------------------------------------
| Profil
|--------------------------------------------------------------------------
*/
Route::prefix('profil')->name('profil.')->group(function () {
    Route::get('/', [Magang\ProfilController::class, 'edit'])->name('edit');
    Route::put('/', [Magang\ProfilController::class, 'update'])->name('update');
    Route::put('/password', [Magang\ProfilController::class, 'changePassword'])->name('password');
});
