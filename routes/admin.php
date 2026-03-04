<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin;

/*
|--------------------------------------------------------------------------
| Admin Routes (Administrator / Kepala Tata Usaha)
|--------------------------------------------------------------------------
| Prefix: /admin
| Name: admin.*
| Middleware: auth, role:admin
|--------------------------------------------------------------------------
*/

// Beranda
Route::get('/beranda', [Admin\DashboardController::class, 'index'])->name('beranda');

/*
|--------------------------------------------------------------------------
| Manajemen Pegawai
|--------------------------------------------------------------------------
*/
Route::resource('pegawai', Admin\StaffController::class)->parameters(['pegawai' => 'staff']);
Route::patch('pegawai/{staff}/toggle-status', [Admin\StaffController::class, 'toggleStatus'])->name('pegawai.toggle-status');
Route::get('/pegawai-ekspor', [Admin\StaffController::class, 'export'])->name('pegawai.ekspor');
Route::post('/pegawai-impor', [Admin\StaffController::class, 'import'])->name('pegawai.impor');

/*
|--------------------------------------------------------------------------
| Kehadiran
|--------------------------------------------------------------------------
*/
Route::get('/kehadiran', [Admin\AttendanceController::class, 'index'])->name('kehadiran.index');
Route::get('/kehadiran/laporan', [Admin\AttendanceController::class, 'report'])->name('kehadiran.laporan');
Route::get('/kehadiran/pengaturan', [Admin\AttendanceController::class, 'settings'])->name('kehadiran.pengaturan');
Route::put('/kehadiran/pengaturan', [Admin\AttendanceController::class, 'updateSettings'])->name('kehadiran.pengaturan.update');
Route::get('/kehadiran-ekspor', [Admin\AttendanceController::class, 'export'])->name('kehadiran.ekspor');
Route::get('/kehadiran/{attendance}', [Admin\AttendanceController::class, 'show'])->name('kehadiran.show');

/*
|--------------------------------------------------------------------------
| Pengajuan Izin
|--------------------------------------------------------------------------
*/
Route::get('/izin', [Admin\LeaveRequestController::class, 'index'])->name('izin.index');
Route::get('/izin/{leaveRequest}', [Admin\LeaveRequestController::class, 'show'])->name('izin.show');
Route::patch('/izin/{leaveRequest}/setujui', [Admin\LeaveRequestController::class, 'approve'])->name('izin.setujui');
Route::patch('/izin/{leaveRequest}/tolak', [Admin\LeaveRequestController::class, 'reject'])->name('izin.tolak');

/*
|--------------------------------------------------------------------------
| Laporan
|--------------------------------------------------------------------------
*/
Route::get('/laporan', [Admin\ReportController::class, 'index'])->name('laporan.index');
Route::get('/laporan/{report}', [Admin\ReportController::class, 'show'])->name('laporan.show');
Route::patch('/laporan/{report}/status', [Admin\ReportController::class, 'updateStatus'])->name('laporan.update-status');

/*
|--------------------------------------------------------------------------
| Agenda & Event
|--------------------------------------------------------------------------
*/
Route::resource('agenda', Admin\EventController::class)->parameters(['agenda' => 'event']);

/*
|--------------------------------------------------------------------------
| Notifikasi
|--------------------------------------------------------------------------
*/
Route::get('/notifikasi', [Admin\NotificationController::class, 'index'])->name('notifikasi.index');
Route::get('/notifikasi/json', [Admin\NotificationController::class, 'json'])->name('notifikasi.json');
Route::get('/notifikasi/buat', [Admin\NotificationController::class, 'create'])->name('notifikasi.create');
Route::post('/notifikasi', [Admin\NotificationController::class, 'store'])->name('notifikasi.store');
Route::delete('/notifikasi/{notification}', [Admin\NotificationController::class, 'destroy'])->name('notifikasi.destroy');

/*
|--------------------------------------------------------------------------
| Surat Menyurat
|--------------------------------------------------------------------------
*/
Route::resource('surat', Admin\SuratController::class);
Route::patch('/surat/{surat}/status', [Admin\SuratController::class, 'updateStatus'])->name('surat.update-status');

/*
|--------------------------------------------------------------------------
| Dokumen & Arsip
|--------------------------------------------------------------------------
*/
Route::resource('dokumen', Admin\DocumentController::class)->parameters(['dokumen' => 'document']);
Route::get('/dokumen-ekspor', [Admin\DocumentController::class, 'export'])->name('dokumen.ekspor');

/*
|--------------------------------------------------------------------------
| Kurikulum
|--------------------------------------------------------------------------
*/
Route::resource('kurikulum', Admin\CurriculumController::class);

/*
|--------------------------------------------------------------------------
| Kesiswaan
|--------------------------------------------------------------------------
*/
Route::resource('kesiswaan', Admin\StudentController::class);

/*
|--------------------------------------------------------------------------
| Inventaris / Sarpras
|--------------------------------------------------------------------------
*/
Route::resource('inventaris', Admin\InventarisController::class);

/*
|--------------------------------------------------------------------------
| Keuangan
|--------------------------------------------------------------------------
*/
Route::get('/keuangan', [Admin\FinanceController::class, 'index'])->name('keuangan.index');
Route::get('/keuangan/buat', [Admin\FinanceController::class, 'create'])->name('keuangan.create');
Route::post('/keuangan', [Admin\FinanceController::class, 'store'])->name('keuangan.store');
Route::get('/keuangan/anggaran', [Admin\FinanceController::class, 'budgetIndex'])->name('keuangan.anggaran');
Route::post('/keuangan/anggaran', [Admin\FinanceController::class, 'budgetStore'])->name('keuangan.anggaran.store');
Route::get('/keuangan/{keuangan}', [Admin\FinanceController::class, 'show'])->name('keuangan.show');
Route::patch('/keuangan/{keuangan}/verifikasi', [Admin\FinanceController::class, 'verify'])->name('keuangan.verifikasi');
Route::delete('/keuangan/{keuangan}', [Admin\FinanceController::class, 'destroy'])->name('keuangan.destroy');

/*
|--------------------------------------------------------------------------
| Evaluasi Kinerja
|--------------------------------------------------------------------------
*/
Route::prefix('evaluasi')->name('evaluasi.')->group(function () {
    // PKG
    Route::get('/pkg', [Admin\EvaluasiController::class, 'pkgIndex'])->name('pkg');
    Route::get('/pkg/buat', [Admin\EvaluasiController::class, 'pkgCreate'])->name('pkg.create');
    Route::post('/pkg', [Admin\EvaluasiController::class, 'pkgStore'])->name('pkg.store');

    // P5
    Route::get('/p5', [Admin\EvaluasiController::class, 'p5Index'])->name('p5');
    Route::get('/p5/buat', [Admin\EvaluasiController::class, 'p5Create'])->name('p5.create');
    Route::post('/p5', [Admin\EvaluasiController::class, 'p5Store'])->name('p5.store');

    // STAR
    Route::get('/star', [Admin\EvaluasiController::class, 'starIndex'])->name('star');
    Route::get('/star/buat', [Admin\EvaluasiController::class, 'starCreate'])->name('star.create');
    Route::post('/star', [Admin\EvaluasiController::class, 'starStore'])->name('star.store');

    // Bukti Fisik
    Route::get('/bukti-fisik', [Admin\EvaluasiController::class, 'buktiFisikIndex'])->name('bukti-fisik');
    Route::post('/bukti-fisik', [Admin\EvaluasiController::class, 'buktiFisikStore'])->name('bukti-fisik.store');
    Route::delete('/bukti-fisik/{evidence}', [Admin\EvaluasiController::class, 'buktiFisikDestroy'])->name('bukti-fisik.destroy');

    // Pembelajaran
    Route::get('/pembelajaran', [Admin\EvaluasiController::class, 'learningIndex'])->name('pembelajaran');
    Route::get('/pembelajaran/buat', [Admin\EvaluasiController::class, 'learningCreate'])->name('pembelajaran.create');
    Route::post('/pembelajaran', [Admin\EvaluasiController::class, 'learningStore'])->name('pembelajaran.store');
});

/*
|--------------------------------------------------------------------------
| Akreditasi
|--------------------------------------------------------------------------
*/
Route::get('/akreditasi', [Admin\AccreditationController::class, 'index'])->name('akreditasi.index');
Route::get('/akreditasi/buat', [Admin\AccreditationController::class, 'create'])->name('akreditasi.create');
Route::post('/akreditasi', [Admin\AccreditationController::class, 'store'])->name('akreditasi.store');
Route::get('/akreditasi/eds', [Admin\AccreditationController::class, 'edsIndex'])->name('akreditasi.eds');
Route::post('/akreditasi/eds', [Admin\AccreditationController::class, 'edsStore'])->name('akreditasi.eds.store');
Route::get('/akreditasi/{akreditasi}', [Admin\AccreditationController::class, 'show'])->name('akreditasi.show');
Route::delete('/akreditasi/{akreditasi}', [Admin\AccreditationController::class, 'destroy'])->name('akreditasi.destroy');

/*
|--------------------------------------------------------------------------
| Pengingat
|--------------------------------------------------------------------------
*/
Route::get('/pengingat', [Admin\ReminderController::class, 'index'])->name('pengingat.index');
Route::get('/pengingat/buat', [Admin\ReminderController::class, 'create'])->name('pengingat.create');
Route::post('/pengingat', [Admin\ReminderController::class, 'store'])->name('pengingat.store');
Route::patch('/pengingat/{reminder}/toggle', [Admin\ReminderController::class, 'toggleComplete'])->name('pengingat.toggle');
Route::delete('/pengingat/{reminder}', [Admin\ReminderController::class, 'destroy'])->name('pengingat.destroy');

/*
|--------------------------------------------------------------------------
| Panduan
|--------------------------------------------------------------------------
*/
Route::get('/panduan', [Admin\PanduanController::class, 'index'])->name('panduan.index');

/*
|--------------------------------------------------------------------------
| Word & AI Dokumen
|--------------------------------------------------------------------------
*/
Route::prefix('word-ai')->name('word-ai.')->group(function () {
    Route::get('/', [Admin\WordDocumentController::class, 'index'])->name('index');
    Route::get('/buat', [Admin\WordDocumentController::class, 'create'])->name('create');
    Route::post('/', [Admin\WordDocumentController::class, 'store'])->name('store');
    Route::get('/template', [Admin\WordDocumentController::class, 'template'])->name('template');
    Route::post('/ai-generate', [Admin\WordDocumentController::class, 'aiGenerate'])->name('ai-generate');
    Route::get('/{word}', [Admin\WordDocumentController::class, 'show'])->name('show');
    Route::get('/{word}/edit', [Admin\WordDocumentController::class, 'edit'])->name('edit');
    Route::put('/{word}', [Admin\WordDocumentController::class, 'update'])->name('update');
    Route::delete('/{word}', [Admin\WordDocumentController::class, 'destroy'])->name('destroy');
    Route::get('/{word}/unduh', [Admin\WordDocumentController::class, 'download'])->name('unduh');
    Route::post('/{word}/autosave', [Admin\WordDocumentController::class, 'autosave'])->name('autosave');
});

/*
|--------------------------------------------------------------------------
| Ulang Tahun & Catatan Beranda
|--------------------------------------------------------------------------
*/
Route::get('/ulang-tahun', [Admin\DashboardController::class, 'birthdayList'])->name('ulang-tahun.index');
Route::post('/ulang-tahun/ucapan', [Admin\DashboardController::class, 'sendBirthdayGreeting'])->name('ulang-tahun.ucapan');

Route::post('/catatan', [Admin\DashboardController::class, 'storeCatatan'])->name('catatan.store');
Route::put('/catatan/{catatan}', [Admin\DashboardController::class, 'updateCatatan'])->name('catatan.update');
Route::delete('/catatan/{catatan}', [Admin\DashboardController::class, 'destroyCatatan'])->name('catatan.destroy');

/*
|--------------------------------------------------------------------------
| Chat / Pesan
|--------------------------------------------------------------------------
*/
Route::prefix('chat')->name('chat.')->group(function () {
    Route::get('/', [Admin\ChatController::class, 'index'])->name('index');
    Route::post('/buat', [Admin\ChatController::class, 'buatPercakapan'])->name('buat');
    Route::get('/belum-dibaca', [Admin\ChatController::class, 'jumlahBelumDibaca'])->name('belum-dibaca');
    Route::get('/{percakapan}', [Admin\ChatController::class, 'show'])->name('show');
    Route::post('/{percakapan}/kirim', [Admin\ChatController::class, 'kirimPesan'])->name('kirim');
    Route::get('/{percakapan}/pesan-baru', [Admin\ChatController::class, 'pesanBaru'])->name('pesan-baru');
});

/*
|--------------------------------------------------------------------------
| Pengaturan
|--------------------------------------------------------------------------
*/
Route::prefix('pengaturan')->name('pengaturan.')->group(function () {
    Route::get('/', [Admin\PengaturanController::class, 'index'])->name('index');
    Route::put('/profil', [Admin\PengaturanController::class, 'updateProfil'])->name('profil');
    Route::put('/password', [Admin\PengaturanController::class, 'updatePassword'])->name('password');
    Route::post('/tampilan', [Admin\PengaturanController::class, 'updateTampilan'])->name('tampilan');
});
