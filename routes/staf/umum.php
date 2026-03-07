<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Staff;

/*
|--------------------------------------------------------------------------
| Routes Umum Semua Staff
|--------------------------------------------------------------------------
| Digunakan oleh semua role staff:
| kepegawaian, pramu_bakti, keuangan, persuratan,
| perpustakaan, inventaris, kesiswaan_kurikulum, staff
|--------------------------------------------------------------------------
*/

// Beranda
Route::get('/beranda', [Staff\BerandaController::class, 'index'])->name('beranda');

/*
|--------------------------------------------------------------------------
| Kehadiran (Semua Staff)
|--------------------------------------------------------------------------
*/
Route::prefix('kehadiran')->name('kehadiran.')->group(function () {
    Route::get('/', [Staff\KehadiranController::class, 'index'])->name('index');
    Route::post('/masuk', [Staff\KehadiranController::class, 'clockIn'])->name('masuk');
    Route::post('/pulang', [Staff\KehadiranController::class, 'clockOut'])->name('pulang');
    Route::get('/{kehadiran}', [Staff\KehadiranController::class, 'show'])->name('show');
    Route::patch('/{kehadiran}/catatan', [Staff\KehadiranController::class, 'updateNote'])->name('catatan');
});

/*
|--------------------------------------------------------------------------
| Pengajuan Izin (Semua Staff)
|--------------------------------------------------------------------------
*/
Route::prefix('izin')->name('izin.')->group(function () {
    Route::get('/', [Staff\IzinController::class, 'index'])->name('index');
    Route::get('/buat', [Staff\IzinController::class, 'create'])->name('create');
    Route::post('/', [Staff\IzinController::class, 'store'])->name('store');
    Route::get('/{izin}', [Staff\IzinController::class, 'show'])->name('show');
    Route::delete('/{izin}', [Staff\IzinController::class, 'destroy'])->name('destroy');
});

/*
|--------------------------------------------------------------------------
| SKP - Sasaran Kinerja Pegawai (Semua Staff)
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
| Kinerja (Semua Staff - baca & unduh)
|--------------------------------------------------------------------------
*/
Route::prefix('kinerja')->name('kinerja.')->group(function () {
    Route::get('/', [Staff\KinerjaController::class, 'index'])->name('index');
    Route::get('/{kinerja}', [Staff\KinerjaController::class, 'show'])->name('show');
    Route::get('/{kinerja}/unduh', [Staff\KinerjaController::class, 'download'])->name('download');
});

/*
|--------------------------------------------------------------------------
| Notifikasi (Semua Staff)
|--------------------------------------------------------------------------
*/
Route::prefix('notifikasi')->name('notifikasi.')->group(function () {
    Route::get('/', [Staff\NotifikasiController::class, 'index'])->name('index');
    Route::get('/json', [Staff\NotifikasiController::class, 'json'])->name('json');
    Route::patch('/{notifikasi}/baca', [Staff\NotifikasiController::class, 'markAsRead'])->name('baca');
    Route::post('/baca-semua', [Staff\NotifikasiController::class, 'markAllAsRead'])->name('baca-semua');
});

/*
|--------------------------------------------------------------------------
| Agenda (Semua Staff - Lihat Saja)
|--------------------------------------------------------------------------
*/
Route::get('/agenda', [Staff\AgendaController::class, 'index'])->name('agenda.index');
Route::get('/agenda/{agenda}', [Staff\AgendaController::class, 'show'])->name('agenda.show');

/*
|--------------------------------------------------------------------------
| Pengingat (Semua Staff)
|--------------------------------------------------------------------------
*/
Route::get('/pengingat', [Staff\PengingatController::class, 'index'])->name('pengingat.index');
Route::patch('/pengingat/{pengingat}/selesai', [Staff\PengingatController::class, 'markComplete'])->name('pengingat.selesai');

/*
|--------------------------------------------------------------------------
| Profil (Semua Staff)
|--------------------------------------------------------------------------
*/
Route::prefix('profil')->name('profil.')->group(function () {
    Route::get('/', [Staff\ProfilController::class, 'edit'])->name('edit');
    Route::put('/', [Staff\ProfilController::class, 'update'])->name('update');
    Route::put('/password', [Staff\ProfilController::class, 'changePassword'])->name('password');
});

/*
|--------------------------------------------------------------------------
| Pengaturan (Semua Staff)
|--------------------------------------------------------------------------
*/
Route::prefix('pengaturan')->name('pengaturan.')->group(function () {
    Route::get('/', [Staff\PengaturanController::class, 'index'])->name('index');
    Route::put('/profil', [Staff\PengaturanController::class, 'updateProfil'])->name('profil');
    Route::put('/password', [Staff\PengaturanController::class, 'updatePassword'])->name('password');
    Route::post('/tampilan', [Staff\PengaturanController::class, 'updateTampilan'])->name('tampilan');
});

/*
|--------------------------------------------------------------------------
| Word & AI Dokumen (Semua Staff)
|--------------------------------------------------------------------------
*/
Route::prefix('word-ai')->name('word-ai.')->group(function () {
    Route::get('/', [Staff\DokumenWordController::class, 'index'])->name('index');
    Route::get('/buat', [Staff\DokumenWordController::class, 'create'])->name('create');
    Route::post('/', [Staff\DokumenWordController::class, 'store'])->name('store');
    Route::get('/template', [Staff\DokumenWordController::class, 'template'])->name('template');
    Route::post('/ai-generate', [Staff\DokumenWordController::class, 'aiGenerate'])->name('ai-generate');
    Route::get('/{word}', [Staff\DokumenWordController::class, 'show'])->name('show');
    Route::get('/{word}/edit', [Staff\DokumenWordController::class, 'edit'])->name('edit');
    Route::put('/{word}', [Staff\DokumenWordController::class, 'update'])->name('update');
    Route::delete('/{word}', [Staff\DokumenWordController::class, 'destroy'])->name('destroy');
    Route::get('/{word}/unduh', [Staff\DokumenWordController::class, 'download'])->name('unduh');
    Route::post('/{word}/autosave', [Staff\DokumenWordController::class, 'autosave'])->name('autosave');
});

/*
|--------------------------------------------------------------------------
| Ulang Tahun & Catatan Beranda (Semua Staff)
|--------------------------------------------------------------------------
*/
Route::get('/ulang-tahun', [Staff\BerandaController::class, 'birthdayList'])->name('ulang-tahun.index');
Route::post('/ulang-tahun/ucapan', [Staff\BerandaController::class, 'sendBirthdayGreeting'])->name('ulang-tahun.ucapan');

Route::post('/catatan', [Staff\BerandaController::class, 'storeCatatan'])->name('catatan.store');
Route::put('/catatan/{catatan}', [Staff\BerandaController::class, 'updateCatatan'])->name('catatan.update');
Route::delete('/catatan/{catatan}', [Staff\BerandaController::class, 'destroyCatatan'])->name('catatan.destroy');

/*
|--------------------------------------------------------------------------
| Chat / Pesan (Semua Staff)
|--------------------------------------------------------------------------
*/
Route::prefix('chat')->name('chat.')->group(function () {
    Route::get('/', [Staff\PesanController::class, 'index'])->name('index');
    Route::post('/buat', [Staff\PesanController::class, 'buatPercakapan'])->name('buat');
    Route::get('/belum-dibaca', [Staff\PesanController::class, 'jumlahBelumDibaca'])->name('belum-dibaca');
    Route::get('/{percakapan}', [Staff\PesanController::class, 'show'])->name('show');
    Route::post('/{percakapan}/kirim', [Staff\PesanController::class, 'kirimPesan'])->name('kirim');
    Route::post('/{percakapan}/kirim-gambar', [Staff\PesanController::class, 'kirimGambar'])->name('kirim-gambar');
    Route::get('/{percakapan}/pesan-baru', [Staff\PesanController::class, 'pesanBaru'])->name('pesan-baru');
});

/*
|--------------------------------------------------------------------------
| AI Chatbot SIATU-AI (Semua Staff)
|--------------------------------------------------------------------------
*/
Route::prefix('siatu-ai')->name('siatu-ai.')->group(function () {
    Route::get('/', [Staff\SiatuAiController::class, 'index'])->name('index');
    Route::post('/kirim', [Staff\SiatuAiController::class, 'kirim'])->name('kirim');
});

/*
|--------------------------------------------------------------------------
| Panduan (Semua Staff)
|--------------------------------------------------------------------------
*/
Route::get('/panduan', [Staff\PanduanController::class, 'index'])->name('panduan.index');
Route::get('/panduan/{panduan}', [Staff\PanduanController::class, 'show'])->name('panduan.show');
Route::get('/panduan/{panduan}/download', [Staff\PanduanController::class, 'download'])->name('panduan.download');
Route::post('/panduan/{panduan}/google-drive', [Staff\PanduanController::class, 'uploadDrive'])->name('panduan.upload-drive');

/*
|--------------------------------------------------------------------------
| Evaluasi (Semua Staff)
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
| Laporan (Semua Staff)
|--------------------------------------------------------------------------
*/
Route::resource('laporan', Staff\LaporanController::class);

/*
|--------------------------------------------------------------------------
| Catatan Harian (Semua Staff)
|--------------------------------------------------------------------------
*/
Route::resource('catatan-harian', Staff\CatatanHarianController::class)->parameters(['catatan-harian' => 'catatan']);

/*
|--------------------------------------------------------------------------
| Disposisi Masuk (Semua Staff)
|--------------------------------------------------------------------------
*/
Route::prefix('disposisi')->name('disposisi.')->group(function () {
    Route::get('/', [Staff\DisposisiController::class, 'index'])->name('index');
    Route::get('/{disposisi}', [Staff\DisposisiController::class, 'show'])->name('show');
    Route::post('/{disposisi}/proses', [Staff\DisposisiController::class, 'proses'])->name('proses');
    Route::post('/{disposisi}/selesai', [Staff\DisposisiController::class, 'selesai'])->name('selesai');
});

/*
|--------------------------------------------------------------------------
| Surat (Semua Staff - akses dasar)
|--------------------------------------------------------------------------
*/
Route::get('/surat', [Staff\SuratController::class, 'index'])->name('surat.index');
Route::get('/surat/buat', [Staff\SuratController::class, 'create'])->name('surat.create');
Route::post('/surat', [Staff\SuratController::class, 'store'])->name('surat.store');
Route::get('/surat/{surat}', [Staff\SuratController::class, 'show'])->name('surat.show');

/*
|--------------------------------------------------------------------------
| Dokumen (Semua Staff - akses dasar)
|--------------------------------------------------------------------------
*/
Route::get('/dokumen', [Staff\DokumenController::class, 'index'])->name('dokumen.index');
Route::get('/dokumen/{dokumen}', [Staff\DokumenController::class, 'show'])->name('dokumen.show');
Route::post('/dokumen', [Staff\DokumenController::class, 'upload'])->name('dokumen.upload');

/*
|--------------------------------------------------------------------------
| Kurikulum (Semua Staff - lihat saja)
|--------------------------------------------------------------------------
*/
Route::get('/kurikulum', [Staff\KurikulumController::class, 'index'])->name('kurikulum.index');
Route::get('/kurikulum/{kurikulum}', [Staff\KurikulumController::class, 'show'])->name('kurikulum.show');

/*
|--------------------------------------------------------------------------
| Kesiswaan (Semua Staff - lihat saja)
|--------------------------------------------------------------------------
*/
Route::get('/kesiswaan', [Staff\KesiswaanController::class, 'index'])->name('kesiswaan.index');
Route::get('/kesiswaan/{kesiswaan}', [Staff\KesiswaanController::class, 'show'])->name('kesiswaan.show');

/*
|--------------------------------------------------------------------------
| Inventaris (Semua Staff - lihat & lapor kerusakan)
|--------------------------------------------------------------------------
*/
Route::get('/inventaris', [Staff\InventarisController::class, 'index'])->name('inventaris.index');
Route::get('/inventaris/{inventaris}', [Staff\InventarisController::class, 'show'])->name('inventaris.show');
Route::post('/inventaris/kerusakan', [Staff\InventarisController::class, 'reportDamage'])->name('inventaris.kerusakan');
