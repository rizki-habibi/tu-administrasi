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
Route::get('/beranda', [Admin\BerandaController::class, 'index'])->name('beranda');

/*
|--------------------------------------------------------------------------
| Manajemen Pegawai
|--------------------------------------------------------------------------
*/
Route::resource('pegawai', Admin\PegawaiController::class)->parameters(['pegawai' => 'staff']);
Route::patch('pegawai/{staff}/toggle-status', [Admin\PegawaiController::class, 'toggleStatus'])->name('pegawai.toggle-status');
Route::get('/pegawai-ekspor', [Admin\PegawaiController::class, 'export'])->name('pegawai.ekspor');
Route::post('/pegawai-impor', [Admin\PegawaiController::class, 'import'])->name('pegawai.impor');

/*
|--------------------------------------------------------------------------
| Kepegawaian (Riwayat Jabatan, Pangkat, Dokumen, Laporan)
|--------------------------------------------------------------------------
*/
Route::prefix('kepegawaian')->name('kepegawaian.')->group(function () {
    // Riwayat Jabatan
    Route::prefix('jabatan')->name('jabatan.')->group(function () {
        Route::get('/', [Admin\KepegawaianController::class, 'jabatanIndex'])->name('index');
        Route::get('/tambah', [Admin\KepegawaianController::class, 'jabatanCreate'])->name('create');
        Route::post('/', [Admin\KepegawaianController::class, 'jabatanStore'])->name('store');
        Route::get('/{jabatan}', [Admin\KepegawaianController::class, 'jabatanShow'])->name('show');
        Route::get('/{jabatan}/edit', [Admin\KepegawaianController::class, 'jabatanEdit'])->name('edit');
        Route::put('/{jabatan}', [Admin\KepegawaianController::class, 'jabatanUpdate'])->name('update');
        Route::delete('/{jabatan}', [Admin\KepegawaianController::class, 'jabatanDestroy'])->name('destroy');
    });

    // Riwayat Pangkat
    Route::prefix('pangkat')->name('pangkat.')->group(function () {
        Route::get('/', [Admin\KepegawaianController::class, 'pangkatIndex'])->name('index');
        Route::get('/tambah', [Admin\KepegawaianController::class, 'pangkatCreate'])->name('create');
        Route::post('/', [Admin\KepegawaianController::class, 'pangkatStore'])->name('store');
        Route::get('/{pangkat}', [Admin\KepegawaianController::class, 'pangkatShow'])->name('show');
        Route::get('/{pangkat}/edit', [Admin\KepegawaianController::class, 'pangkatEdit'])->name('edit');
        Route::put('/{pangkat}', [Admin\KepegawaianController::class, 'pangkatUpdate'])->name('update');
        Route::delete('/{pangkat}', [Admin\KepegawaianController::class, 'pangkatDestroy'])->name('destroy');
    });

    // Dokumen Kepegawaian
    Route::prefix('dokumen')->name('dokumen.')->group(function () {
        Route::get('/', [Admin\KepegawaianController::class, 'dokumenIndex'])->name('index');
        Route::get('/tambah', [Admin\KepegawaianController::class, 'dokumenCreate'])->name('create');
        Route::post('/', [Admin\KepegawaianController::class, 'dokumenStore'])->name('store');
        Route::get('/{dokumen}', [Admin\KepegawaianController::class, 'dokumenShow'])->name('show');
        Route::delete('/{dokumen}', [Admin\KepegawaianController::class, 'dokumenDestroy'])->name('destroy');
    });

    // Laporan Kepegawaian
    Route::get('/laporan', [Admin\KepegawaianController::class, 'laporanIndex'])->name('laporan');
});

/*
|--------------------------------------------------------------------------
| Kehadiran
|--------------------------------------------------------------------------
*/
Route::get('/kehadiran', [Admin\KehadiranController::class, 'index'])->name('kehadiran.index');
Route::get('/kehadiran/laporan', [Admin\KehadiranController::class, 'report'])->name('kehadiran.laporan');
Route::get('/kehadiran/pengaturan', [Admin\KehadiranController::class, 'settings'])->name('kehadiran.pengaturan');
Route::put('/kehadiran/pengaturan', [Admin\KehadiranController::class, 'updateSettings'])->name('kehadiran.pengaturan.update');
Route::post('/kehadiran/masuk', [Admin\KehadiranController::class, 'clockIn'])->name('kehadiran.masuk');
Route::post('/kehadiran/pulang', [Admin\KehadiranController::class, 'clockOut'])->name('kehadiran.pulang');
Route::get('/kehadiran-ekspor', [Admin\KehadiranController::class, 'export'])->name('kehadiran.ekspor');
Route::get('/kehadiran/{attendance}', [Admin\KehadiranController::class, 'show'])->name('kehadiran.show');

/*
|--------------------------------------------------------------------------
| Pengajuan Izin
|--------------------------------------------------------------------------
*/
Route::get('/izin', [Admin\IzinController::class, 'index'])->name('izin.index');
Route::get('/izin/{leaveRequest}', [Admin\IzinController::class, 'show'])->name('izin.show');
Route::patch('/izin/{leaveRequest}/setujui', [Admin\IzinController::class, 'approve'])->name('izin.setujui');
Route::patch('/izin/{leaveRequest}/tolak', [Admin\IzinController::class, 'reject'])->name('izin.tolak');

/*
|--------------------------------------------------------------------------
| Laporan
|--------------------------------------------------------------------------
*/
Route::get('/laporan', [Admin\LaporanController::class, 'index'])->name('laporan.index');
Route::get('/laporan/{report}', [Admin\LaporanController::class, 'show'])->name('laporan.show');
Route::patch('/laporan/{report}/status', [Admin\LaporanController::class, 'updateStatus'])->name('laporan.update-status');

/*
|--------------------------------------------------------------------------
| Agenda & Event
|--------------------------------------------------------------------------
*/
Route::resource('agenda', Admin\AgendaController::class)->parameters(['agenda' => 'event']);

/*
|--------------------------------------------------------------------------
| Notule Kegiatan (Header Date Popup)
|--------------------------------------------------------------------------
*/
Route::get('/notule', [Admin\NotuleController::class, 'index'])->name('notule.index');
Route::post('/notule', [Admin\NotuleController::class, 'store'])->name('notule.store');
Route::delete('/notule/{notule}', [Admin\NotuleController::class, 'destroy'])->name('notule.destroy');

/*
|--------------------------------------------------------------------------
| Notifikasi
|--------------------------------------------------------------------------
*/
Route::get('/notifikasi', [Admin\NotifikasiController::class, 'index'])->name('notifikasi.index');
Route::get('/notifikasi/json', [Admin\NotifikasiController::class, 'json'])->name('notifikasi.json');
Route::get('/notifikasi/buat', [Admin\NotifikasiController::class, 'create'])->name('notifikasi.create');
Route::post('/notifikasi', [Admin\NotifikasiController::class, 'store'])->name('notifikasi.store');
Route::delete('/notifikasi/{notification}', [Admin\NotifikasiController::class, 'destroy'])->name('notifikasi.destroy');

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
Route::resource('dokumen', Admin\DokumenController::class)->parameters(['dokumen' => 'document']);
Route::get('/dokumen-ekspor', [Admin\DokumenController::class, 'export'])->name('dokumen.ekspor');

/*
|--------------------------------------------------------------------------
| Kurikulum
|--------------------------------------------------------------------------
*/
Route::resource('kurikulum', Admin\KurikulumController::class);

/*
|--------------------------------------------------------------------------
| Kesiswaan
|--------------------------------------------------------------------------
*/
Route::resource('kesiswaan', Admin\KesiswaanController::class);

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
Route::get('/keuangan', [Admin\KeuanganAdminController::class, 'index'])->name('keuangan.index');
Route::get('/keuangan/buat', [Admin\KeuanganAdminController::class, 'create'])->name('keuangan.create');
Route::post('/keuangan', [Admin\KeuanganAdminController::class, 'store'])->name('keuangan.store');
Route::get('/keuangan/anggaran', [Admin\KeuanganAdminController::class, 'budgetIndex'])->name('keuangan.anggaran');
Route::post('/keuangan/anggaran', [Admin\KeuanganAdminController::class, 'budgetStore'])->name('keuangan.anggaran.store');
Route::get('/keuangan/{keuangan}', [Admin\KeuanganAdminController::class, 'show'])->name('keuangan.show');
Route::patch('/keuangan/{keuangan}/verifikasi', [Admin\KeuanganAdminController::class, 'verify'])->name('keuangan.verifikasi');
Route::delete('/keuangan/{keuangan}', [Admin\KeuanganAdminController::class, 'destroy'])->name('keuangan.destroy');

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
Route::get('/akreditasi', [Admin\AkreditasiController::class, 'index'])->name('akreditasi.index');
Route::get('/akreditasi/buat', [Admin\AkreditasiController::class, 'create'])->name('akreditasi.create');
Route::post('/akreditasi', [Admin\AkreditasiController::class, 'store'])->name('akreditasi.store');
Route::get('/akreditasi/eds', [Admin\AkreditasiController::class, 'edsIndex'])->name('akreditasi.eds');
Route::post('/akreditasi/eds', [Admin\AkreditasiController::class, 'edsStore'])->name('akreditasi.eds.store');
Route::get('/akreditasi/{akreditasi}', [Admin\AkreditasiController::class, 'show'])->name('akreditasi.show');
Route::delete('/akreditasi/{akreditasi}', [Admin\AkreditasiController::class, 'destroy'])->name('akreditasi.destroy');

/*
|--------------------------------------------------------------------------
| Pengingat
|--------------------------------------------------------------------------
*/
Route::get('/pengingat', [Admin\PengingatController::class, 'index'])->name('pengingat.index');
Route::get('/pengingat/buat', [Admin\PengingatController::class, 'create'])->name('pengingat.create');
Route::post('/pengingat', [Admin\PengingatController::class, 'store'])->name('pengingat.store');
Route::patch('/pengingat/{reminder}/toggle', [Admin\PengingatController::class, 'toggleComplete'])->name('pengingat.toggle');
Route::delete('/pengingat/{reminder}', [Admin\PengingatController::class, 'destroy'])->name('pengingat.destroy');

/*
|--------------------------------------------------------------------------
| Panduan
|--------------------------------------------------------------------------
*/
Route::get('/panduan', [Admin\PanduanController::class, 'index'])->name('panduan.index');
Route::get('/panduan/tambah', [Admin\PanduanController::class, 'create'])->name('panduan.create');
Route::post('/panduan', [Admin\PanduanController::class, 'store'])->name('panduan.store');
Route::get('/panduan/{panduan}', [Admin\PanduanController::class, 'show'])->name('panduan.show');
Route::get('/panduan/{panduan}/download', [Admin\PanduanController::class, 'download'])->name('panduan.download');
Route::post('/panduan/{panduan}/google-drive', [Admin\PanduanController::class, 'uploadDrive'])->name('panduan.upload-drive');
Route::get('/panduan/{panduan}/edit', [Admin\PanduanController::class, 'edit'])->name('panduan.edit');
Route::put('/panduan/{panduan}', [Admin\PanduanController::class, 'update'])->name('panduan.update');
Route::delete('/panduan/{panduan}', [Admin\PanduanController::class, 'destroy'])->name('panduan.destroy');

/*
|--------------------------------------------------------------------------
| Word & AI Dokumen
|--------------------------------------------------------------------------
*/
Route::prefix('word-ai')->name('word-ai.')->group(function () {
    Route::get('/', [Admin\DokumenWordController::class, 'index'])->name('index');
    Route::get('/buat', [Admin\DokumenWordController::class, 'create'])->name('create');
    Route::post('/', [Admin\DokumenWordController::class, 'store'])->name('store');
    Route::get('/template', [Admin\DokumenWordController::class, 'template'])->name('template');
    Route::post('/ai-generate', [Admin\DokumenWordController::class, 'aiGenerate'])->name('ai-generate');
    Route::get('/{word}', [Admin\DokumenWordController::class, 'show'])->name('show');
    Route::get('/{word}/edit', [Admin\DokumenWordController::class, 'edit'])->name('edit');
    Route::put('/{word}', [Admin\DokumenWordController::class, 'update'])->name('update');
    Route::delete('/{word}', [Admin\DokumenWordController::class, 'destroy'])->name('destroy');
    Route::get('/{word}/unduh', [Admin\DokumenWordController::class, 'download'])->name('unduh');
    Route::post('/{word}/autosave', [Admin\DokumenWordController::class, 'autosave'])->name('autosave');
});

/*
|--------------------------------------------------------------------------
| Ulang Tahun & Catatan Beranda
|--------------------------------------------------------------------------
*/
Route::get('/ulang-tahun', [Admin\BerandaController::class, 'birthdayList'])->name('ulang-tahun.index');
Route::post('/ulang-tahun/ucapan', [Admin\BerandaController::class, 'sendBirthdayGreeting'])->name('ulang-tahun.ucapan');

Route::post('/catatan', [Admin\BerandaController::class, 'storeCatatan'])->name('catatan.store');
Route::put('/catatan/{catatan}', [Admin\BerandaController::class, 'updateCatatan'])->name('catatan.update');
Route::delete('/catatan/{catatan}', [Admin\BerandaController::class, 'destroyCatatan'])->name('catatan.destroy');

/*
|--------------------------------------------------------------------------
| AI Assistant & Analisis
|--------------------------------------------------------------------------
*/
Route::middleware('throttle:10,1')->prefix('ai')->name('ai.')->group(function () {
    Route::post('/assistant', [Admin\BerandaController::class, 'aiAssistant'])->name('assistant');
    Route::get('/ringkasan', [Admin\BerandaController::class, 'aiRingkasan'])->name('ringkasan');
    Route::get('/analisis-kehadiran', [Admin\BerandaController::class, 'aiAnalisisKehadiran'])->name('analisis-kehadiran');
});

/*
|--------------------------------------------------------------------------
| Chat / Pesan
|--------------------------------------------------------------------------
*/
Route::prefix('chat')->name('chat.')->group(function () {
    Route::get('/', [Admin\PesanController::class, 'index'])->name('index');
    Route::post('/buat', [Admin\PesanController::class, 'buatPercakapan'])->name('buat');
    Route::get('/belum-dibaca', [Admin\PesanController::class, 'jumlahBelumDibaca'])->name('belum-dibaca');
    Route::get('/{percakapan}', [Admin\PesanController::class, 'show'])->name('show');
    Route::post('/{percakapan}/kirim', [Admin\PesanController::class, 'kirimPesan'])->name('kirim');
    Route::post('/{percakapan}/kirim-gambar', [Admin\PesanController::class, 'kirimGambar'])->name('kirim-gambar');
    Route::get('/{percakapan}/pesan-baru', [Admin\PesanController::class, 'pesanBaru'])->name('pesan-baru');
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

/*
|--------------------------------------------------------------------------
| Kelola Halaman Publik (Landing Page & Kinerja)
|--------------------------------------------------------------------------
*/
Route::prefix('halaman-publik')->name('halaman-publik.')->group(function () {
    Route::get('/', [Admin\KelolaHalamanController::class, 'index'])->name('index');
    Route::get('/buat', [Admin\KelolaHalamanController::class, 'create'])->name('create');
    Route::post('/', [Admin\KelolaHalamanController::class, 'store'])->name('store');
    Route::get('/statistik', [Admin\KelolaHalamanController::class, 'statistikPengunjung'])->name('statistik');
    Route::get('/saran', [Admin\KelolaHalamanController::class, 'saranIndex'])->name('saran');
    Route::patch('/saran/{saranPengunjung}/tanggapi', [Admin\KelolaHalamanController::class, 'saranTanggapi'])->name('saran.tanggapi');
    Route::delete('/saran/{saranPengunjung}', [Admin\KelolaHalamanController::class, 'saranDestroy'])->name('saran.destroy');
    Route::get('/{kontenPublik}', [Admin\KelolaHalamanController::class, 'show'])->name('show');
    Route::get('/{kontenPublik}/edit', [Admin\KelolaHalamanController::class, 'edit'])->name('edit');
    Route::put('/{kontenPublik}', [Admin\KelolaHalamanController::class, 'update'])->name('update');
    Route::patch('/{kontenPublik}/toggle-aktif', [Admin\KelolaHalamanController::class, 'toggleAktif'])->name('toggle-aktif');
    Route::delete('/{kontenPublik}', [Admin\KelolaHalamanController::class, 'destroy'])->name('destroy');
});

/*
|--------------------------------------------------------------------------
| Disposisi Surat
|--------------------------------------------------------------------------
*/
Route::prefix('disposisi')->name('disposisi.')->group(function () {
    Route::get('/', [Admin\DisposisiController::class, 'index'])->name('index');
    Route::get('/buat', [Admin\DisposisiController::class, 'create'])->name('create');
    Route::post('/', [Admin\DisposisiController::class, 'store'])->name('store');
    Route::get('/{disposisi}', [Admin\DisposisiController::class, 'show'])->name('show');
    Route::delete('/{disposisi}', [Admin\DisposisiController::class, 'destroy'])->name('destroy');
});

/*
|--------------------------------------------------------------------------
| Log Aktivitas
|--------------------------------------------------------------------------
*/
Route::get('/log-aktivitas', [Admin\LogAktivitasController::class, 'index'])->name('log-aktivitas.index');

/*
|--------------------------------------------------------------------------
| AI Chatbot SIMPEG-AI
|--------------------------------------------------------------------------
*/
Route::prefix('siatu-ai')->name('siatu-ai.')->group(function () {
    Route::get('/', [Admin\SiatuAiController::class, 'index'])->name('index');
    Route::post('/kirim', [Admin\SiatuAiController::class, 'kirim'])->name('kirim');
});

/*
|--------------------------------------------------------------------------
| Konfigurasi AI (Hanya Admin)
|--------------------------------------------------------------------------
*/
Route::prefix('pengaturan-ai')->name('pengaturan-ai.')->group(function () {
    Route::get('/', [Admin\PengaturanAiController::class, 'index'])->name('index');
    Route::post('/', [Admin\PengaturanAiController::class, 'store'])->name('store');
    Route::put('/{pengaturanAi}', [Admin\PengaturanAiController::class, 'update'])->name('update');
    Route::patch('/{pengaturanAi}/activate', [Admin\PengaturanAiController::class, 'activate'])->name('activate');
    Route::delete('/{pengaturanAi}', [Admin\PengaturanAiController::class, 'destroy'])->name('destroy');
    Route::post('/test', [Admin\PengaturanAiController::class, 'testConnection'])->name('test');
});

/*
|--------------------------------------------------------------------------
| Database Inspector & Cloud Drive (Admin Only)
|--------------------------------------------------------------------------
*/
Route::prefix('database')->name('database.')->group(function () {
    Route::get('/', [Admin\DatabaseController::class, 'index'])->name('index');
    Route::get('/tabel/{table}', [Admin\DatabaseController::class, 'showTable'])->name('show');
    Route::get('/cloud', [Admin\DatabaseController::class, 'cloudIndex'])->name('cloud');
    Route::post('/cloud', [Admin\DatabaseController::class, 'cloudStore'])->name('cloud.store');
    Route::put('/cloud/{cloud}', [Admin\DatabaseController::class, 'cloudUpdate'])->name('cloud.update');
    Route::delete('/cloud/{cloud}', [Admin\DatabaseController::class, 'cloudDestroy'])->name('cloud.destroy');
});

/*
|--------------------------------------------------------------------------
| Pusat Ekspor Data
|--------------------------------------------------------------------------
*/
Route::get('/ekspor', [Admin\EksporController::class, 'index'])->name('ekspor.index');
Route::get('/ekspor/staff', [Admin\PegawaiController::class, 'export'])->name('ekspor.staff');
Route::get('/ekspor/kehadiran', [Admin\KehadiranController::class, 'export'])->name('ekspor.kehadiran');
Route::get('/ekspor/dokumen', [Admin\DokumenController::class, 'export'])->name('ekspor.dokumen');
