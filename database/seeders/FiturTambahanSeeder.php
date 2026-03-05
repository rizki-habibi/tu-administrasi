<?php

namespace Database\Seeders;

use App\Models\CatatanBeranda;
use App\Models\Pengingat;
use App\Models\UcapanUlangTahun;
use App\Models\Pengguna;
use App\Models\DokumenWord;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class FiturTambahanSeeder extends Seeder
{
    public function run(): void
    {
        $admin   = Pengguna::where('email', 'admin@tu.test')->firstOrFail();
        $kepsek  = Pengguna::where('email', 'kepsek@tu.test')->firstOrFail();
        $staff1  = Pengguna::where('email', 'aris.persuratan@tu.test')->firstOrFail();
        $staff2  = Pengguna::where('email', 'ike.keuangan@tu.test')->firstOrFail();
        $staff3  = Pengguna::where('email', 'bayu.kesiswaan@tu.test')->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | 1. DOKUMEN WORD (AI)
        |--------------------------------------------------------------------------
        */
        $wordDocs = [
            [
                'pengguna_id' => $admin->id,
                'judul'       => 'Surat Undangan Rapat Komite Maret 2026',
                'kategori'    => 'surat',
                'konten'      => '<p>Kepada Yth. Bapak/Ibu Anggota Komite Sekolah SMA Negeri 2 Jember.</p><p>Dengan hormat, mengundang Bapak/Ibu untuk hadir dalam rapat komite sekolah pada hari Rabu, 12 Maret 2026 pukul 09.00 WIB di Aula SMA Negeri 2 Jember.</p><p>Agenda: Pembahasan program semester genap dan rencana kegiatan.</p>',
                'prompt_ai'   => 'Buat surat undangan rapat komite sekolah tanggal 12 Maret 2026 pukul 09.00 di aula sekolah',
                'templat'     => 'surat_resmi',
                'status'      => 'final',
                'dibagikan'   => true,
            ],
            [
                'pengguna_id' => $staff1->id,
                'judul'       => 'Surat Edaran Libur Hari Raya Nyepi',
                'kategori'    => 'surat',
                'konten'      => '<p>Disampaikan kepada seluruh warga SMA Negeri 2 Jember bahwa dalam rangka Hari Raya Nyepi Tahun Baru Saka 1948, sekolah diliburkan pada hari Jumat, 28 Maret 2026.</p><p>Kegiatan belajar mengajar kembali normal pada Senin, 31 Maret 2026.</p>',
                'prompt_ai'   => 'Buat surat edaran libur Nyepi 28 Maret 2026',
                'templat'     => 'surat_resmi',
                'status'      => 'final',
                'dibagikan'   => true,
            ],
            [
                'pengguna_id' => $staff3->id,
                'judul'       => 'Laporan Class Meeting Semester Ganjil',
                'kategori'    => 'laporan',
                'konten'      => '<h3>LAPORAN KEGIATAN CLASS MEETING</h3><p>Class Meeting diselenggarakan pada 6-8 Januari 2026 diikuti oleh seluruh siswa kelas X-XII. Kegiatan meliputi: futsal, voli, badminton, lomba kreasi, dan pentas seni.</p><p>Total peserta: 960 siswa. Kegiatan berjalan lancar tanpa kendala berarti.</p>',
                'prompt_ai'   => 'Buat laporan class meeting semester ganjil 2025/2026, 3 hari, 960 siswa',
                'templat'     => 'laporan',
                'status'      => 'draft',
                'dibagikan'   => false,
            ],
            [
                'pengguna_id' => $admin->id,
                'judul'       => 'SK Panitia PPDB 2026/2027',
                'kategori'    => 'sk',
                'konten'      => '<p>SURAT KEPUTUSAN KEPALA SMA NEGERI 2 JEMBER tentang Pembentukan Panitia PPDB Tahun Ajaran 2026/2027. Menetapkan susunan panitia terlampir.</p>',
                'prompt_ai'   => null,
                'templat'     => 'sk',
                'status'      => 'draft',
                'dibagikan'   => false,
            ],
        ];

        foreach ($wordDocs as $doc) {
            DokumenWord::updateOrCreate(
                ['judul' => $doc['judul'], 'pengguna_id' => $doc['pengguna_id']],
                $doc
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 2. PENGINGAT (Reminders)
        |--------------------------------------------------------------------------
        */
        $today = Carbon::today();

        $reminders = [
            ['judul' => 'Deadline Laporan BOS Triwulan I',    'deskripsi' => 'Batas waktu pengiriman laporan pertanggungjawaban BOS triwulan I 2026.',             'jenis' => 'keuangan',    'tenggat' => '2026-03-31', 'user' => $staff2->id, 'creator' => $admin->id, 'selesai' => false],
            ['judul' => 'Rapat Dinas Bulanan Maret',          'deskripsi' => 'Rapat bulanan seluruh staf TU dan pimpinan.',                                        'jenis' => 'rapat',       'tenggat' => '2026-03-10', 'user' => $admin->id,  'creator' => $admin->id, 'selesai' => false],
            ['judul' => 'Pengumpulan Nilai Semester Genap',   'deskripsi' => 'Semua guru harus mengumpulkan nilai UTS semester genap.',                              'jenis' => 'akademik',    'tenggat' => '2026-04-15', 'user' => $staff3->id, 'creator' => $admin->id, 'selesai' => false],
            ['judul' => 'Perpanjangan Domain Sekolah',        'deskripsi' => 'Domain website sekolah perlu diperpanjang sebelum expired.',                           'jenis' => 'umum',        'tenggat' => '2026-03-20', 'user' => $admin->id,  'creator' => $admin->id, 'selesai' => false],
            ['judul' => 'Evaluasi PKG Semester Ganjil',       'deskripsi' => 'Finalisasi nilai PKG semester ganjil 2025/2026.',                                     'jenis' => 'evaluasi',    'tenggat' => '2026-02-28', 'user' => $admin->id,  'creator' => $kepsek->id, 'selesai' => true],
            ['judul' => 'Perawatan AC Ruang Kelas',           'deskripsi' => 'Jadwal servis AC ruangan kelas secara berkala.',                                      'jenis' => 'sarana',      'tenggat' => '2026-03-15', 'user' => $admin->id,  'creator' => $admin->id, 'berulang' => true, 'jenis_pengulangan' => 'bulanan', 'selesai' => false],
        ];

        foreach ($reminders as $r) {
            Pengingat::updateOrCreate(
                ['judul' => $r['judul'], 'pengguna_id' => $r['user']],
                [
                    'deskripsi'          => $r['deskripsi'],
                    'jenis'              => $r['jenis'],
                    'tenggat'            => $r['tenggat'],
                    'berulang'           => $r['berulang'] ?? false,
                    'jenis_pengulangan'  => $r['jenis_pengulangan'] ?? null,
                    'pengguna_id'        => $r['user'],
                    'dibuat_oleh'        => $r['creator'],
                    'selesai'            => $r['selesai'],
                    'sudah_diberitahu'   => $r['selesai'],
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 3. CATATAN BERANDA
        |--------------------------------------------------------------------------
        */
        $notes = [
            ['judul' => 'Selamat Datang di Sistem TU!',            'isi' => 'Sistem informasi administrasi tata usaha SMA Negeri 2 Jember telah aktif. Silakan gunakan menu di sidebar untuk mengakses fitur yang tersedia.',   'warna' => 'primary',   'disematkan' => true,  'tanggal' => '2026-03-01', 'user' => $admin->id],
            ['judul' => 'Jadwal UTS Semester Genap',                'isi' => 'UTS semester genap dijadwalkan tanggal 14-18 April 2026. Pastikan semua persiapan administrasi sudah selesai.',                                   'warna' => 'warning',   'disematkan' => true,  'tanggal' => '2026-03-05', 'user' => $admin->id],
            ['judul' => 'Pembaruan Sistem Kehadiran',               'isi' => 'Sistem kehadiran online sudah diperbarui. Radius absensi diperluas menjadi 200m mengikuti batas terbaru area sekolah.',                           'warna' => 'info',      'disematkan' => false, 'tanggal' => '2026-02-28', 'user' => $admin->id],
            ['judul' => 'Pengumpulan SKP Semester 1',               'isi' => 'Semua staf TU diharapkan sudah mengisi SKP semester ganjil paling lambat 10 Maret 2026.',                                                        'warna' => 'danger',    'disematkan' => false, 'tanggal' => '2026-03-01', 'user' => $admin->id],
        ];

        foreach ($notes as $n) {
            CatatanBeranda::updateOrCreate(
                ['judul' => $n['judul'], 'pengguna_id' => $n['user']],
                [
                    'isi'        => $n['isi'],
                    'warna'      => $n['warna'],
                    'disematkan' => $n['disematkan'],
                    'tanggal'    => $n['tanggal'],
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 4. UCAPAN ULANG TAHUN (sample — hanya jika ada yg ulang tahun)
        |--------------------------------------------------------------------------
        */
        // Birthday greetings from admin to kepsek
        UcapanUlangTahun::updateOrCreate(
            ['pengirim_id' => $admin->id, 'penerima_id' => $kepsek->id, 'tahun' => 2026],
            [
                'pesan'        => 'Selamat ulang tahun Bapak Kepala Sekolah! Semoga sehat selalu, panjang umur, dan sukses memimpin SMA Negeri 2 Jember. 🎂🎉',
                'sudah_dibaca' => true,
            ]
        );

        UcapanUlangTahun::updateOrCreate(
            ['pengirim_id' => $kepsek->id, 'penerima_id' => $admin->id, 'tahun' => 2026],
            [
                'pesan'        => 'Selamat ulang tahun Pak Bambang! Terima kasih atas dedikasi dan kerja keras mengelola tata usaha sekolah. Sukses selalu! 🎂',
                'sudah_dibaca' => false,
            ]
        );
    }
}
