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
Route::get('/beranda', [Kepsek\BerandaController::class, 'index'])->name('beranda');

/*
|--------------------------------------------------------------------------
| Pegawai (Read-Only)
|--------------------------------------------------------------------------
*/
Route::get('/pegawai', [Kepsek\PegawaiController::class, 'index'])->name('pegawai.index');
Route::get('/pegawai/{pegawai}', [Kepsek\PegawaiController::class, 'show'])->name('pegawai.show');

/*
|--------------------------------------------------------------------------
| Kehadiran (Read-Only + Laporan)
|--------------------------------------------------------------------------
*/
Route::prefix('kehadiran')->name('kehadiran.')->group(function () {
    Route::get('/', [Kepsek\KehadiranController::class, 'index'])->name('index');
    Route::get('/laporan', [Kepsek\KehadiranController::class, 'report'])->name('laporan');
    Route::get('/{kehadiran}', [Kepsek\KehadiranController::class, 'show'])->name('show');
});

/*
|--------------------------------------------------------------------------
| Pengajuan Izin (Approve / Reject)
|--------------------------------------------------------------------------
*/
Route::prefix('izin')->name('izin.')->group(function () {
    Route::get('/', [Kepsek\IzinController::class, 'index'])->name('index');
    Route::get('/{izin}', [Kepsek\IzinController::class, 'show'])->name('show');
    Route::patch('/{izin}/setujui', [Kepsek\IzinController::class, 'approve'])->name('setujui');
    Route::patch('/{izin}/tolak', [Kepsek\IzinController::class, 'reject'])->name('tolak');
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
Route::get('/laporan', [Kepsek\LaporanController::class, 'index'])->name('laporan.index');
Route::get('/laporan/{laporan}', [Kepsek\LaporanController::class, 'show'])->name('laporan.show');

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
Route::get('/agenda', [Kepsek\AgendaController::class, 'index'])->name('agenda.index');
Route::get('/agenda/{agenda}', [Kepsek\AgendaController::class, 'show'])->name('agenda.show');

/*
|--------------------------------------------------------------------------
| Notifikasi
|--------------------------------------------------------------------------
*/
Route::prefix('notifikasi')->name('notifikasi.')->group(function () {
    Route::get('/', [Kepsek\NotifikasiController::class, 'index'])->name('index');
    Route::get('/json', [Kepsek\NotifikasiController::class, 'json'])->name('json');
    Route::post('/baca-semua', [Kepsek\NotifikasiController::class, 'markAllAsRead'])->name('baca-semua');
    Route::patch('/{notifikasi}/baca', [Kepsek\NotifikasiController::class, 'markAsRead'])->name('baca');
});

/*
|--------------------------------------------------------------------------
| Profil
|--------------------------------------------------------------------------
*/
Route::prefix('profil')->name('profil.')->group(function () {
    Route::get('/', [Kepsek\ProfilController::class, 'edit'])->name('edit');
    Route::put('/', [Kepsek\ProfilController::class, 'update'])->name('update');
    Route::put('/password', [Kepsek\ProfilController::class, 'changePassword'])->name('password');
});

/*
|--------------------------------------------------------------------------
| Ulang Tahun & Catatan Beranda
|--------------------------------------------------------------------------
*/
Route::get('/ulang-tahun', [Kepsek\BerandaController::class, 'birthdayList'])->name('ulang-tahun.index');
Route::post('/ulang-tahun/ucapan', [Kepsek\BerandaController::class, 'sendBirthdayGreeting'])->name('ulang-tahun.ucapan');

Route::post('/catatan', [Kepsek\BerandaController::class, 'storeCatatan'])->name('catatan.store');
Route::put('/catatan/{catatan}', [Kepsek\BerandaController::class, 'updateCatatan'])->name('catatan.update');
Route::delete('/catatan/{catatan}', [Kepsek\BerandaController::class, 'destroyCatatan'])->name('catatan.destroy');

/*
|--------------------------------------------------------------------------
| AI Assistant
|--------------------------------------------------------------------------
*/
Route::middleware('throttle:10,1')->prefix('ai')->name('ai.')->group(function () {
    Route::post('/assistant', [Kepsek\BerandaController::class, 'aiAssistant'])->name('assistant');
    Route::get('/ringkasan', [Kepsek\BerandaController::class, 'aiRingkasan'])->name('ringkasan');
});

/*
|--------------------------------------------------------------------------
| Resolusi / Keputusan Kepala Sekolah
|--------------------------------------------------------------------------
*/
Route::resource('resolusi', Kepsek\ResolusiController::class);

/*
|--------------------------------------------------------------------------
| Rekap Eksekutif
|--------------------------------------------------------------------------
*/
Route::prefix('rekap-eksekutif')->name('rekap-eksekutif.')->group(function () {
    Route::get('/', [Kepsek\RekapEksekutifController::class, 'index'])->name('index');
    Route::get('/ai-analisis', [Kepsek\RekapEksekutifController::class, 'aiAnalisis'])->name('ai-analisis');
});

/*
|--------------------------------------------------------------------------
| AI Chatbot SIMPEG-AI
|--------------------------------------------------------------------------
*/
Route::prefix('siatu-ai')->name('siatu-ai.')->group(function () {
    Route::get('/', [Kepsek\SiatuAiController::class, 'index'])->name('index');
    Route::post('/kirim', [Kepsek\SiatuAiController::class, 'kirim'])->name('kirim');
});

/*
|--------------------------------------------------------------------------
| Chat / Pesan
|--------------------------------------------------------------------------
*/
Route::prefix('chat')->name('chat.')->group(function () {
    Route::get('/', [Kepsek\PesanController::class, 'index'])->name('index');
    Route::post('/buat', [Kepsek\PesanController::class, 'buatPercakapan'])->name('buat');
    Route::get('/belum-dibaca', [Kepsek\PesanController::class, 'jumlahBelumDibaca'])->name('belum-dibaca');
    Route::get('/{percakapan}', [Kepsek\PesanController::class, 'show'])->name('show');
    Route::post('/{percakapan}/kirim', [Kepsek\PesanController::class, 'kirimPesan'])->name('kirim');
    Route::post('/{percakapan}/kirim-gambar', [Kepsek\PesanController::class, 'kirimGambar'])->name('kirim-gambar');
    Route::get('/{percakapan}/pesan-baru', [Kepsek\PesanController::class, 'pesanBaru'])->name('pesan-baru');
});

/*
|--------------------------------------------------------------------------
| Pengaturan
|--------------------------------------------------------------------------
*/
Route::prefix('pengaturan')->name('pengaturan.')->group(function () {
    Route::get('/', [Kepsek\PengaturanController::class, 'index'])->name('index');
    Route::put('/profil', [Kepsek\PengaturanController::class, 'updateProfil'])->name('profil');
    Route::put('/password', [Kepsek\PengaturanController::class, 'updatePassword'])->name('password');
    Route::post('/tampilan', [Kepsek\PengaturanController::class, 'updateTampilan'])->name('tampilan');
});

/*
|--------------------------------------------------------------------------
| Panduan
|--------------------------------------------------------------------------
*/
Route::get('/panduan', [Kepsek\PanduanController::class, 'index'])->name('panduan.index');
Route::get('/panduan/{panduan}', [Kepsek\PanduanController::class, 'show'])->name('panduan.show');
Route::get('/panduan/{panduan}/download', [Kepsek\PanduanController::class, 'download'])->name('panduan.download');
Route::post('/panduan/{panduan}/google-drive', [Kepsek\PanduanController::class, 'uploadDrive'])->name('panduan.upload-drive');
