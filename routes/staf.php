<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Staff;

/*
|--------------------------------------------------------------------------
| Staf Routes (Semua Role Staff / Pegawai TU)
|--------------------------------------------------------------------------
| Prefix: /staf
| Name: staf.*
| Middleware: auth, role:all_staff
|
| Role yang termasuk:
| - kepegawaian, pramu_bakti, keuangan, persuratan,
|   perpustakaan, inventaris, kesiswaan_kurikulum, staff
|--------------------------------------------------------------------------
*/

// Beranda
Route::get('/beranda', [Staff\DashboardController::class, 'index'])->name('beranda');

/*
|--------------------------------------------------------------------------
| Kehadiran
|--------------------------------------------------------------------------
*/
Route::prefix('kehadiran')->name('kehadiran.')->group(function () {
    Route::get('/', [Staff\AttendanceController::class, 'index'])->name('index');
    Route::post('/masuk', [Staff\AttendanceController::class, 'clockIn'])->name('masuk');
    Route::post('/pulang', [Staff\AttendanceController::class, 'clockOut'])->name('pulang');
    Route::get('/{kehadiran}', [Staff\AttendanceController::class, 'show'])->name('show');
    Route::patch('/{kehadiran}/catatan', [Staff\AttendanceController::class, 'updateNote'])->name('catatan');
});

/*
|--------------------------------------------------------------------------
| Pengajuan Izin
|--------------------------------------------------------------------------
*/
Route::prefix('izin')->name('izin.')->group(function () {
    Route::get('/', [Staff\LeaveRequestController::class, 'index'])->name('index');
    Route::get('/buat', [Staff\LeaveRequestController::class, 'create'])->name('create');
    Route::post('/', [Staff\LeaveRequestController::class, 'store'])->name('store');
    Route::get('/{izin}', [Staff\LeaveRequestController::class, 'show'])->name('show');
    Route::delete('/{izin}', [Staff\LeaveRequestController::class, 'destroy'])->name('destroy');
});

/*
|--------------------------------------------------------------------------
| Laporan
|--------------------------------------------------------------------------
*/
Route::resource('laporan', Staff\ReportController::class);

/*
|--------------------------------------------------------------------------
| Agenda
|--------------------------------------------------------------------------
*/
Route::get('/agenda', [Staff\EventController::class, 'index'])->name('agenda.index');
Route::get('/agenda/{agenda}', [Staff\EventController::class, 'show'])->name('agenda.show');

/*
|--------------------------------------------------------------------------
| Notifikasi
|--------------------------------------------------------------------------
*/
Route::prefix('notifikasi')->name('notifikasi.')->group(function () {
    Route::get('/', [Staff\NotificationController::class, 'index'])->name('index');
    Route::get('/json', [Staff\NotificationController::class, 'json'])->name('json');
    Route::patch('/{notifikasi}/baca', [Staff\NotificationController::class, 'markAsRead'])->name('baca');
    Route::post('/baca-semua', [Staff\NotificationController::class, 'markAllAsRead'])->name('baca-semua');
});

/*
|--------------------------------------------------------------------------
| Surat
|--------------------------------------------------------------------------
*/
Route::get('/surat', [Staff\SuratController::class, 'index'])->name('surat.index');
Route::get('/surat/buat', [Staff\SuratController::class, 'create'])->name('surat.create');
Route::post('/surat', [Staff\SuratController::class, 'store'])->name('surat.store');
Route::get('/surat/{surat}', [Staff\SuratController::class, 'show'])->name('surat.show');

/*
|--------------------------------------------------------------------------
| Dokumen
|--------------------------------------------------------------------------
*/
Route::get('/dokumen', [Staff\DocumentController::class, 'index'])->name('dokumen.index');
Route::get('/dokumen/{dokumen}', [Staff\DocumentController::class, 'show'])->name('dokumen.show');
Route::post('/dokumen', [Staff\DocumentController::class, 'upload'])->name('dokumen.upload');

/*
|--------------------------------------------------------------------------
| Kurikulum
|--------------------------------------------------------------------------
*/
Route::get('/kurikulum', [Staff\CurriculumController::class, 'index'])->name('kurikulum.index');
Route::get('/kurikulum/{kurikulum}', [Staff\CurriculumController::class, 'show'])->name('kurikulum.show');
Route::post('/kurikulum', [Staff\CurriculumController::class, 'store'])->name('kurikulum.store');

/*
|--------------------------------------------------------------------------
| Kesiswaan
|--------------------------------------------------------------------------
*/
Route::get('/kesiswaan', [Staff\StudentController::class, 'index'])->name('kesiswaan.index');
Route::get('/kesiswaan/{kesiswaan}', [Staff\StudentController::class, 'show'])->name('kesiswaan.show');

/*
|--------------------------------------------------------------------------
| Inventaris
|--------------------------------------------------------------------------
*/
Route::get('/inventaris', [Staff\InventarisController::class, 'index'])->name('inventaris.index');
Route::get('/inventaris/{inventaris}', [Staff\InventarisController::class, 'show'])->name('inventaris.show');
Route::post('/inventaris/kerusakan', [Staff\InventarisController::class, 'reportDamage'])->name('inventaris.kerusakan');

/*
|--------------------------------------------------------------------------
| Evaluasi
|--------------------------------------------------------------------------
*/
Route::prefix('evaluasi')->name('evaluasi.')->group(function () {
    Route::get('/pkg', [Staff\EvaluasiController::class, 'pkgIndex'])->name('pkg');
    Route::get('/p5', [Staff\EvaluasiController::class, 'p5Index'])->name('p5');

    Route::get('/star', [Staff\EvaluasiController::class, 'starIndex'])->name('star');
    Route::post('/star', [Staff\EvaluasiController::class, 'starStore'])->name('star.store');

    Route::get('/bukti-fisik', [Staff\EvaluasiController::class, 'buktiFisikIndex'])->name('bukti-fisik');
    Route::post('/bukti-fisik', [Staff\EvaluasiController::class, 'buktiFisikStore'])->name('bukti-fisik.store');

    Route::get('/pembelajaran', [Staff\EvaluasiController::class, 'learningIndex'])->name('pembelajaran');
    Route::post('/pembelajaran', [Staff\EvaluasiController::class, 'learningStore'])->name('pembelajaran.store');
});

/*
|--------------------------------------------------------------------------
| Pengingat
|--------------------------------------------------------------------------
*/
Route::get('/pengingat', [Staff\ReminderController::class, 'index'])->name('pengingat.index');
Route::patch('/pengingat/{pengingat}/selesai', [Staff\ReminderController::class, 'markComplete'])->name('pengingat.selesai');

/*
|--------------------------------------------------------------------------
| Profil
|--------------------------------------------------------------------------
*/
Route::prefix('profil')->name('profil.')->group(function () {
    Route::get('/', [Staff\ProfileController::class, 'edit'])->name('edit');
    Route::put('/', [Staff\ProfileController::class, 'update'])->name('update');
    Route::put('/password', [Staff\ProfileController::class, 'changePassword'])->name('password');
});

/*
|--------------------------------------------------------------------------
| SKP (Sasaran Kinerja Pegawai)
|--------------------------------------------------------------------------
*/
Route::prefix('skp')->name('skp.')->group(function () {
    Route::get('/', [Staff\SkpController::class, 'index'])->name('index');
    Route::get('/buat', [Staff\SkpController::class, 'create'])->name('create');
    Route::post('/', [Staff\SkpController::class, 'store'])->name('store');
    Route::get('/{skp}', [Staff\SkpController::class, 'show'])->name('show');
    Route::get('/{skp}/edit', [Staff\SkpController::class, 'edit'])->name('edit');
    Route::put('/{skp}', [Staff\SkpController::class, 'update'])->name('update');
    Route::delete('/{skp}', [Staff\SkpController::class, 'destroy'])->name('destroy');
});

/*
|--------------------------------------------------------------------------
| Panduan
|--------------------------------------------------------------------------
*/
Route::get('/panduan', [Staff\PanduanController::class, 'index'])->name('panduan.index');

/*
|--------------------------------------------------------------------------
| Word & AI Dokumen
|--------------------------------------------------------------------------
*/
Route::prefix('word-ai')->name('word-ai.')->group(function () {
    Route::get('/', [Staff\WordDocumentController::class, 'index'])->name('index');
    Route::get('/buat', [Staff\WordDocumentController::class, 'create'])->name('create');
    Route::post('/', [Staff\WordDocumentController::class, 'store'])->name('store');
    Route::get('/template', [Staff\WordDocumentController::class, 'template'])->name('template');
    Route::post('/ai-generate', [Staff\WordDocumentController::class, 'aiGenerate'])->name('ai-generate');
    Route::get('/{word}', [Staff\WordDocumentController::class, 'show'])->name('show');
    Route::get('/{word}/edit', [Staff\WordDocumentController::class, 'edit'])->name('edit');
    Route::put('/{word}', [Staff\WordDocumentController::class, 'update'])->name('update');
    Route::delete('/{word}', [Staff\WordDocumentController::class, 'destroy'])->name('destroy');
    Route::get('/{word}/unduh', [Staff\WordDocumentController::class, 'download'])->name('unduh');
    Route::post('/{word}/autosave', [Staff\WordDocumentController::class, 'autosave'])->name('autosave');
});

/*
|--------------------------------------------------------------------------
| Ulang Tahun & Catatan Beranda
|--------------------------------------------------------------------------
*/
Route::get('/ulang-tahun', [Staff\DashboardController::class, 'birthdayList'])->name('ulang-tahun.index');
Route::post('/ulang-tahun/ucapan', [Staff\DashboardController::class, 'sendBirthdayGreeting'])->name('ulang-tahun.ucapan');

Route::post('/catatan', [Staff\DashboardController::class, 'storeCatatan'])->name('catatan.store');
Route::put('/catatan/{catatan}', [Staff\DashboardController::class, 'updateCatatan'])->name('catatan.update');
Route::delete('/catatan/{catatan}', [Staff\DashboardController::class, 'destroyCatatan'])->name('catatan.destroy');
