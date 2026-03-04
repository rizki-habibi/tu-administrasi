<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Kepsek;

/*
|--------------------------------------------------------------------------
| Kepala Sekolah Routes
|--------------------------------------------------------------------------
| Prefix: /kepala-sekolah
| Name: kepala-sekolah.*
| Middleware: auth, role:kepala_sekolah
|--------------------------------------------------------------------------
*/

// Beranda
Route::get('/beranda', [Kepsek\DashboardController::class, 'index'])->name('beranda');

/*
|--------------------------------------------------------------------------
| Pegawai (Read-Only)
|--------------------------------------------------------------------------
*/
Route::get('/pegawai', [Kepsek\StaffController::class, 'index'])->name('pegawai.index');
Route::get('/pegawai/{pegawai}', [Kepsek\StaffController::class, 'show'])->name('pegawai.show');

/*
|--------------------------------------------------------------------------
| Kehadiran (Read-Only + Laporan)
|--------------------------------------------------------------------------
*/
Route::prefix('kehadiran')->name('kehadiran.')->group(function () {
    Route::get('/', [Kepsek\AttendanceController::class, 'index'])->name('index');
    Route::get('/laporan', [Kepsek\AttendanceController::class, 'report'])->name('laporan');
    Route::get('/{kehadiran}', [Kepsek\AttendanceController::class, 'show'])->name('show');
});

/*
|--------------------------------------------------------------------------
| Pengajuan Izin (Approve / Reject)
|--------------------------------------------------------------------------
*/
Route::prefix('izin')->name('izin.')->group(function () {
    Route::get('/', [Kepsek\LeaveController::class, 'index'])->name('index');
    Route::get('/{izin}', [Kepsek\LeaveController::class, 'show'])->name('show');
    Route::patch('/{izin}/setujui', [Kepsek\LeaveController::class, 'approve'])->name('setujui');
    Route::patch('/{izin}/tolak', [Kepsek\LeaveController::class, 'reject'])->name('tolak');
});

/*
|--------------------------------------------------------------------------
| SKP (Approve / Reject)
|--------------------------------------------------------------------------
*/
Route::prefix('skp')->name('skp.')->group(function () {
    Route::get('/', [Kepsek\SkpController::class, 'index'])->name('index');
    Route::get('/{skp}', [Kepsek\SkpController::class, 'show'])->name('show');
    Route::patch('/{skp}/setujui', [Kepsek\SkpController::class, 'approve'])->name('setujui');
    Route::patch('/{skp}/tolak', [Kepsek\SkpController::class, 'reject'])->name('tolak');
});

/*
|--------------------------------------------------------------------------
| Evaluasi (Read-Only)
|--------------------------------------------------------------------------
*/
Route::prefix('evaluasi')->name('evaluasi.')->group(function () {
    Route::get('/pkg', [Kepsek\EvaluasiController::class, 'pkgIndex'])->name('pkg');
    Route::get('/star', [Kepsek\EvaluasiController::class, 'starIndex'])->name('star');
    Route::get('/bukti-fisik', [Kepsek\EvaluasiController::class, 'buktiFisikIndex'])->name('bukti-fisik');
});

/*
|--------------------------------------------------------------------------
| Surat (Read-Only)
|--------------------------------------------------------------------------
*/
Route::get('/surat', [Kepsek\SuratController::class, 'index'])->name('surat.index');
Route::get('/surat/{surat}', [Kepsek\SuratController::class, 'show'])->name('surat.show');

/*
|--------------------------------------------------------------------------
| Laporan (Read-Only)
|--------------------------------------------------------------------------
*/
Route::get('/laporan', [Kepsek\ReportController::class, 'index'])->name('laporan.index');
Route::get('/laporan/{laporan}', [Kepsek\ReportController::class, 'show'])->name('laporan.show');

/*
|--------------------------------------------------------------------------
| Keuangan (Read-Only)
|--------------------------------------------------------------------------
*/
Route::get('/keuangan', [Kepsek\KeuanganController::class, 'index'])->name('keuangan.index');

/*
|--------------------------------------------------------------------------
| Agenda (Read-Only)
|--------------------------------------------------------------------------
*/
Route::get('/agenda', [Kepsek\EventController::class, 'index'])->name('agenda.index');
Route::get('/agenda/{agenda}', [Kepsek\EventController::class, 'show'])->name('agenda.show');

/*
|--------------------------------------------------------------------------
| Notifikasi
|--------------------------------------------------------------------------
*/
Route::prefix('notifikasi')->name('notifikasi.')->group(function () {
    Route::get('/', [Kepsek\NotificationController::class, 'index'])->name('index');
    Route::get('/json', [Kepsek\NotificationController::class, 'json'])->name('json');
    Route::patch('/{notifikasi}/baca', [Kepsek\NotificationController::class, 'markAsRead'])->name('baca');
});

/*
|--------------------------------------------------------------------------
| Profil
|--------------------------------------------------------------------------
*/
Route::prefix('profil')->name('profil.')->group(function () {
    Route::get('/', [Kepsek\ProfileController::class, 'edit'])->name('edit');
    Route::put('/', [Kepsek\ProfileController::class, 'update'])->name('update');
    Route::put('/password', [Kepsek\ProfileController::class, 'changePassword'])->name('password');
});

/*
|--------------------------------------------------------------------------
| Ulang Tahun & Catatan Beranda
|--------------------------------------------------------------------------
*/
Route::get('/ulang-tahun', [Kepsek\DashboardController::class, 'birthdayList'])->name('ulang-tahun.index');
Route::post('/ulang-tahun/ucapan', [Kepsek\DashboardController::class, 'sendBirthdayGreeting'])->name('ulang-tahun.ucapan');

Route::post('/catatan', [Kepsek\DashboardController::class, 'storeCatatan'])->name('catatan.store');
Route::put('/catatan/{catatan}', [Kepsek\DashboardController::class, 'updateCatatan'])->name('catatan.update');
Route::delete('/catatan/{catatan}', [Kepsek\DashboardController::class, 'destroyCatatan'])->name('catatan.destroy');

/*
|--------------------------------------------------------------------------
| Panduan
|--------------------------------------------------------------------------
*/
Route::get('/panduan', [Kepsek\PanduanController::class, 'index'])->name('panduan.index');
