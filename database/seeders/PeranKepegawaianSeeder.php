<?php

namespace Database\Seeders;

use App\Models\Kehadiran;
use App\Models\Dokumen;
use App\Models\DokumenKepegawaian;
use App\Models\PengajuanIzin;
use App\Models\Notifikasi;
use App\Models\Laporan;
use App\Models\Skp;
use App\Models\Surat;
use App\Models\Pengguna;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PeranKepegawaianSeeder extends Seeder
{
    public function run(): void
    {
        $today = Carbon::today();
        $admin = Pengguna::where('email', 'admin@tu.test')->firstOrFail();
        $todayMonthDay = $today->format('m-d');

        /*
        |--------------------------------------------------------------------------
        | 1. AKUN KEPEGAWAIAN (IKI 1)
        |--------------------------------------------------------------------------
        */
        $dwi = Pengguna::updateOrCreate(
            ['email' => 'dwi.kepegawaian@tu.test'],
            [
                'nama'           => 'Dwi Kriswahyudi',
                'password'       => Hash::make('password'),
                'peran'          => 'kepegawaian',
                'jabatan'        => 'Penata Layanan Operasional',
                'iki_pelaksana'  => '1 KEPEGAWAIAN',
                'kode_depan'     => '14344',
                'telepon'        => '081298765001',
                'alamat'         => 'Jl. Kalimantan No. 12, Jember',
                'aktif'          => true,
                'tanggal_lahir'  => '1985-' . $todayMonthDay,
            ]
        );

        $faizz = Pengguna::updateOrCreate(
            ['email' => 'faizz.kepegawaian@tu.test'],
            [
                'nama'           => 'Faizz Moch. Nur Adam',
                'password'       => Hash::make('password'),
                'peran'          => 'kepegawaian',
                'jabatan'        => 'Operator Layanan Operasional',
                'iki_pelaksana'  => '1 KEPEGAWAIAN',
                'kode_depan'     => '14344',
                'telepon'        => '081298765002',
                'alamat'         => 'Jl. Sumatera No. 8, Jember',
                'aktif'          => true,
                'tanggal_lahir'  => '1992-07-14',
            ]
        );

        $staffUsers = [$dwi, $faizz];

        /*
        |--------------------------------------------------------------------------
        | 2. KEHADIRAN (30 hari)
        |--------------------------------------------------------------------------
        */
        $this->seedKehadiran($staffUsers, $today);

        /*
        |--------------------------------------------------------------------------
        | 3. PENGAJUAN IZIN
        |--------------------------------------------------------------------------
        */
        PengajuanIzin::updateOrCreate(
            ['pengguna_id' => $dwi->id, 'tanggal_mulai' => $today->copy()->addDays(5)->format('Y-m-d')],
            ['jenis' => 'izin', 'tanggal_selesai' => $today->copy()->addDays(5)->format('Y-m-d'), 'alasan' => 'Menghadiri pernikahan saudara di Surabaya', 'status' => 'approved', 'disetujui_oleh' => $admin->id, 'catatan_admin' => 'Disetujui oleh Kasubag TU']
        );
        PengajuanIzin::updateOrCreate(
            ['pengguna_id' => $faizz->id, 'tanggal_mulai' => $today->copy()->addDays(3)->format('Y-m-d')],
            ['jenis' => 'sakit', 'tanggal_selesai' => $today->copy()->addDays(4)->format('Y-m-d'), 'alasan' => 'Demam tinggi, surat dokter terlampir', 'status' => 'approved', 'disetujui_oleh' => $admin->id, 'catatan_admin' => 'Disetujui oleh Kasubag TU']
        );

        /*
        |--------------------------------------------------------------------------
        | 4. LAPORAN
        |--------------------------------------------------------------------------
        */
        Laporan::updateOrCreate(
            ['pengguna_id' => $dwi->id, 'judul' => 'Laporan Data Pegawai Semester Ganjil 2025/2026'],
            ['deskripsi' => 'Rekapitulasi data kepegawaian seluruh guru & staff semester ganjil.', 'kategori' => 'lainnya', 'prioritas' => 'tinggi', 'status' => 'submitted']
        );
        Laporan::updateOrCreate(
            ['pengguna_id' => $faizz->id, 'judul' => 'Rekap SKP Pegawai Semester Ganjil'],
            ['deskripsi' => 'Rekapitulasi SKP seluruh TU. 16 pegawai sudah mengumpulkan.', 'kategori' => 'lainnya', 'prioritas' => 'sedang', 'status' => 'completed']
        );

        /*
        |--------------------------------------------------------------------------
        | 5. SKP (Sasaran Kinerja Pegawai)
        |--------------------------------------------------------------------------
        */
        Skp::updateOrCreate(
            ['pengguna_id' => $dwi->id, 'sasaran_kinerja' => 'Pengelolaan administrasi kepegawaian'],
            ['periode' => 'Semester 1 2025/2026', 'tahun' => 2026, 'indikator_kinerja' => 'Jumlah berkas kepegawaian yang diproses tepat waktu', 'target_kuantitas' => 100, 'realisasi_kuantitas' => 92, 'target_kualitas' => 85, 'realisasi_kualitas' => 88, 'target_waktu' => 6, 'realisasi_waktu' => 6, 'nilai_capaian' => 91.33, 'predikat' => 'sangat_baik', 'status' => 'disetujui', 'disetujui_oleh' => $admin->id, 'disetujui_pada' => now()->subDays(rand(1, 30))]
        );
        Skp::updateOrCreate(
            ['pengguna_id' => $faizz->id, 'sasaran_kinerja' => 'Update data SIMPEG dan DAPODIK'],
            ['periode' => 'Semester 1 2025/2026', 'tahun' => 2026, 'indikator_kinerja' => 'Persentase data pegawai yang terupdate', 'target_kuantitas' => 100, 'realisasi_kuantitas' => 95, 'target_kualitas' => 90, 'realisasi_kualitas' => 88, 'target_waktu' => 6, 'realisasi_waktu' => 6, 'nilai_capaian' => 94.33, 'predikat' => 'sangat_baik', 'status' => 'disetujui', 'disetujui_oleh' => $admin->id, 'disetujui_pada' => now()->subDays(rand(1, 30))]
        );

        /*
        |--------------------------------------------------------------------------
        | 6. DOKUMEN
        |--------------------------------------------------------------------------
        */
        Dokumen::updateOrCreate(
            ['judul' => 'Daftar Urut Kepangkatan (DUK) 2026'],
            ['deskripsi' => 'DUK seluruh PNS.', 'kategori' => 'kepegawaian', 'path_file' => 'documents/daftar-urut-kepangkatan-duk-2026.pdf', 'nama_file' => 'daftar-urut-kepangkatan-duk-2026.pdf', 'tipe_file' => 'pdf', 'ukuran_file' => rand(102400, 5242880), 'diunggah_oleh' => $dwi->id]
        );
        Dokumen::updateOrCreate(
            ['judul' => 'Rekap Data Guru & Karyawan'],
            ['deskripsi' => 'Data lengkap guru dan karyawan.', 'kategori' => 'kepegawaian', 'path_file' => 'documents/rekap-data-guru-karyawan.pdf', 'nama_file' => 'rekap-data-guru-karyawan.pdf', 'tipe_file' => 'pdf', 'ukuran_file' => rand(102400, 5242880), 'diunggah_oleh' => $faizz->id]
        );

        /*
        |--------------------------------------------------------------------------
        | 7. SURAT
        |--------------------------------------------------------------------------
        */
        Surat::updateOrCreate(
            ['perihal' => 'Surat Keterangan Aktif Bekerja'],
            [
                'nomor_surat'   => Surat::generateNomor('keluar', 'keterangan'),
                'jenis'         => 'keluar',
                'kategori'      => 'keterangan',
                'isi'           => 'Surat keterangan pegawai aktif.',
                'tujuan'        => 'Bank BRI',
                'tanggal_surat' => now()->subDays(rand(1, 30)),
                'status'        => 'dikirim',
                'sifat'         => 'biasa',
                'dibuat_oleh'   => $dwi->id,
                'disetujui_oleh' => $admin->id,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | 8. DOKUMEN KEPEGAWAIAN
        |--------------------------------------------------------------------------
        */
        DokumenKepegawaian::updateOrCreate(
            ['pengguna_id' => $dwi->id, 'judul' => 'SK PNS Dwi Kriswahyudi'],
            ['kategori' => 'sk_pns', 'nomor_dokumen' => 'BKN/SK-PNS/2010/001234', 'tanggal_dokumen' => '2010-04-01', 'keterangan' => 'Surat Keputusan pengangkatan sebagai PNS.']
        );
        DokumenKepegawaian::updateOrCreate(
            ['pengguna_id' => $dwi->id, 'judul' => 'SK Kenaikan Pangkat III/c'],
            ['kategori' => 'sk_kenaikan_pangkat', 'nomor_dokumen' => 'BKN/KP/2023/005678', 'tanggal_dokumen' => '2023-10-01', 'keterangan' => 'Kenaikan pangkat menjadi Penata (III/c).']
        );
        DokumenKepegawaian::updateOrCreate(
            ['pengguna_id' => $faizz->id, 'judul' => 'SK CPNS Faizz Moch. Nur Adam'],
            ['kategori' => 'sk_cpns', 'nomor_dokumen' => 'BKN/SK-CPNS/2018/009876', 'tanggal_dokumen' => '2018-03-01', 'keterangan' => 'Surat Keputusan pengangkatan sebagai CPNS.']
        );
        DokumenKepegawaian::updateOrCreate(
            ['pengguna_id' => $faizz->id, 'judul' => 'Sertifikat Pelatihan SIMPATIKA'],
            ['kategori' => 'sertifikat', 'nomor_dokumen' => 'SERT/SIMPATIKA/2024/0042', 'tanggal_dokumen' => '2024-06-15', 'keterangan' => 'Sertifikat pelatihan operator SIMPATIKA.']
        );

        /*
        |--------------------------------------------------------------------------
        | 9. NOTIFIKASI
        |--------------------------------------------------------------------------
        */
        $this->seedNotifikasi($staffUsers);

        /*
        |--------------------------------------------------------------------------
        | OUTPUT
        |--------------------------------------------------------------------------
        */
        $this->command->info('');
        $this->command->info('  ✅ PERAN KEPEGAWAIAN (IKI 1)');
        $this->command->info('  ─────────────────────────────────────');
        $this->command->info('  Akun   : dwi.kepegawaian@tu.test');
        $this->command->info('           faizz.kepegawaian@tu.test');
        $this->command->info('  Fitur  : Kehadiran 30 hari, 2 izin, 2 laporan, 2 SKP,');
        $this->command->info('           2 dokumen, 1 surat, 4 dokumen kepegawaian, notifikasi');
    }

    private function seedKehadiran(array $users, Carbon $today): void
    {
        $statuses  = ['hadir','hadir','hadir','hadir','hadir','terlambat','izin','sakit'];
        $addresses = [
            'SMA Negeri 2 Jember, Jl. Jawa No.16, Sumbersari, Jember',
            'Halaman Parkir SMA Negeri 2 Jember',
            'Ruang TU SMA Negeri 2 Jember, Jl. Jawa 16',
            'Pos Satpam SMA Negeri 2 Jember',
            'Lapangan Utama SMA Negeri 2 Jember',
        ];

        foreach ($users as $staff) {
            for ($i = 29; $i >= 0; $i--) {
                $date = $today->copy()->subDays($i);
                if ($date->isWeekend()) continue;

                $status = $statuses[array_rand($statuses)];
                $clockIn = $clockOut = $note = $addrIn = $addrOut = null;

                switch ($status) {
                    case 'hadir':
                        $clockIn  = sprintf('07:%02d', rand(10, 29));
                        $clockOut = sprintf('%02d:%02d', rand(15, 16), rand(0, 30));
                        $addrIn   = $addresses[array_rand($addresses)];
                        $addrOut  = $addresses[array_rand($addresses)];
                        break;
                    case 'terlambat':
                        $clockIn  = sprintf('07:%02d', rand(46, 59));
                        $clockOut = sprintf('%02d:%02d', rand(15, 16), rand(0, 30));
                        $note     = 'Terlambat: ' . collect(['macet di jalan','ban bocor','antar anak sekolah','hujan deras'])->random();
                        $addrIn   = $addresses[array_rand($addresses)];
                        $addrOut  = $addresses[array_rand($addresses)];
                        break;
                    case 'izin':
                        $note = collect(['Urusan keluarga','Mengurus dokumen pribadi','Keperluan mendadak'])->random();
                        break;
                    case 'sakit':
                        $note = collect(['Demam dan flu','Sakit perut','Periksa ke dokter','Masuk angin'])->random();
                        break;
                }

                Kehadiran::updateOrCreate(
                    ['pengguna_id' => $staff->id, 'tanggal' => $date->format('Y-m-d')],
                    [
                        'jam_masuk'     => $clockIn,
                        'jam_pulang'    => $clockOut,
                        'status'        => $status,
                        'lat_masuk'     => $clockIn ? -8.165908 + (rand(-50, 50) / 100000) : null,
                        'lng_masuk'     => $clockIn ? 113.706649 + (rand(-50, 50) / 100000) : null,
                        'alamat_masuk'  => $addrIn,
                        'lat_pulang'    => $clockOut ? -8.165908 + (rand(-50, 50) / 100000) : null,
                        'lng_pulang'    => $clockOut ? 113.706649 + (rand(-50, 50) / 100000) : null,
                        'alamat_pulang' => $addrOut,
                        'catatan'       => $note,
                    ]
                );
            }
        }
    }

    private function seedNotifikasi(array $users): void
    {
        $templates = [
            ['judul' => 'Absensi berhasil tercatat',                       'msg' => 'Absensi masuk hari ini berhasil tercatat pukul 07:25 WIB.',         'jenis' => 'kehadiran'],
            ['judul' => 'Pengingat absen pulang',                          'msg' => 'Jangan lupa absen pulang sebelum meninggalkan area sekolah.',       'jenis' => 'kehadiran'],
            ['judul' => 'Pengajuan izin disetujui',                        'msg' => 'Pengajuan izin Anda telah disetujui oleh Kasubag TU.',             'jenis' => 'izin'],
            ['judul' => 'Agenda baru: Pelatihan Google Workspace',         'msg' => 'Pelatihan di Lab Komputer 1. Bawa laptop.',                        'jenis' => 'event'],
            ['judul' => 'Selamat datang di Sistem SIMPEG-SMART!',       'msg' => 'Akun Anda sudah aktif. Lengkapi profil.',                          'jenis' => 'sistem'],
            ['judul' => 'Pembaruan Sistem v3.0',                           'msg' => 'Fitur baru: SKP, Word AI, lokasi detail kehadiran.',               'jenis' => 'sistem'],
        ];

        foreach ($users as $staff) {
            $shuffled = collect($templates)->shuffle()->take(rand(3, 5));
            foreach ($shuffled as $idx => $n) {
                Notifikasi::create([
                    'pengguna_id' => $staff->id,
                    'judul'       => $n['judul'],
                    'pesan'       => $n['msg'],
                    'jenis'       => $n['jenis'],
                    'sudah_dibaca' => $idx < 2,
                    'created_at'  => now()->subHours(rand(1, 168)),
                ]);
            }
        }
    }
}
