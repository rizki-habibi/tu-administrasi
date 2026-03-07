<?php

namespace Database\Seeders;

use App\Models\Kehadiran;
use App\Models\PengajuanIzin;
use App\Models\Notifikasi;
use App\Models\Laporan;
use App\Models\Skp;
use App\Models\Pengguna;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PeranPramuBaktiSeeder extends Seeder
{
    public function run(): void
    {
        $today = Carbon::today();
        $admin = Pengguna::where('email', 'admin@tu.test')->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | 1. AKUN PRAMU BAKTI (IKI 2)
        |--------------------------------------------------------------------------
        */
        $eko = Pengguna::updateOrCreate(
            ['email' => 'eko.pramubakti@tu.test'],
            [
                'nama'          => 'Eko Bagus Febrianto',
                'password'      => Hash::make('password'),
                'peran'         => 'pramu_bakti',
                'jabatan'       => 'Operator Layanan Operasional',
                'iki_pelaksana' => '2 PRAMU BAKTI',
                'kode_depan'    => '23304',
                'telepon'       => '081298765003',
                'alamat'        => 'Jl. Jawa No. 25, Jember',
                'aktif'         => true,
                'tanggal_lahir' => '1990-02-15',
            ]
        );

        $marsis = Pengguna::updateOrCreate(
            ['email' => 'marsis.pramubakti@tu.test'],
            [
                'nama'          => 'Marsis',
                'password'      => Hash::make('password'),
                'peran'         => 'pramu_bakti',
                'jabatan'       => 'Pengelola Umum Operasional',
                'iki_pelaksana' => '2 PRAMU BAKTI',
                'kode_depan'    => '23304',
                'telepon'       => '081298765004',
                'alamat'        => 'Jl. Sulawesi No. 3, Jember',
                'aktif'         => true,
                'tanggal_lahir' => '1978-11-20',
            ]
        );

        $miftahul = Pengguna::updateOrCreate(
            ['email' => 'miftahul.pramubakti@tu.test'],
            [
                'nama'          => 'Miftahul Ulum',
                'password'      => Hash::make('password'),
                'peran'         => 'pramu_bakti',
                'jabatan'       => 'Pengelola Umum Operasional',
                'iki_pelaksana' => '2 PRAMU BAKTI',
                'kode_depan'    => '23304',
                'telepon'       => '081298765005',
                'alamat'        => 'Jl. Borneo No. 17, Jember',
                'aktif'         => true,
                'tanggal_lahir' => '1988-06-03',
            ]
        );

        $staffUsers = [$eko, $marsis, $miftahul];

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
            ['pengguna_id' => $eko->id, 'tanggal_mulai' => $today->copy()->addDays(10)->format('Y-m-d')],
            ['jenis' => 'cuti', 'tanggal_selesai' => $today->copy()->addDays(12)->format('Y-m-d'), 'alasan' => 'Cuti tahunan untuk liburan keluarga', 'status' => 'approved', 'disetujui_oleh' => $admin->id, 'catatan_admin' => 'Disetujui oleh Kasubag TU']
        );
        PengajuanIzin::updateOrCreate(
            ['pengguna_id' => $marsis->id, 'tanggal_mulai' => $today->copy()->addDays(7)->format('Y-m-d')],
            ['jenis' => 'dinas_luar', 'tanggal_selesai' => $today->copy()->addDays(8)->format('Y-m-d'), 'alasan' => 'Pelatihan di Dinas Pendidikan Kab. Jember', 'status' => 'approved', 'disetujui_oleh' => $admin->id, 'catatan_admin' => 'Disetujui oleh Kasubag TU']
        );

        /*
        |--------------------------------------------------------------------------
        | 4. LAPORAN
        |--------------------------------------------------------------------------
        */
        Laporan::updateOrCreate(
            ['pengguna_id' => $eko->id, 'judul' => 'Laporan Kebersihan Gedung Februari 2026'],
            ['deskripsi' => 'Laporan kondisi kebersihan gedung A, B, C dan ruang kelas.', 'kategori' => 'kegiatan', 'prioritas' => 'rendah', 'status' => 'completed']
        );

        /*
        |--------------------------------------------------------------------------
        | 5. SKP
        |--------------------------------------------------------------------------
        */
        Skp::updateOrCreate(
            ['pengguna_id' => $eko->id, 'sasaran_kinerja' => 'Pemeliharaan kebersihan gedung sekolah'],
            ['periode' => 'Semester 1 2025/2026', 'tahun' => 2026, 'indikator_kinerja' => 'Persentase ruangan yang bersih & rapi', 'target_kuantitas' => 50, 'realisasi_kuantitas' => 48, 'target_kualitas' => 80, 'realisasi_kualitas' => 82, 'target_waktu' => 6, 'realisasi_waktu' => 6, 'nilai_capaian' => 88.27, 'predikat' => 'baik', 'status' => 'disetujui', 'disetujui_oleh' => $admin->id, 'disetujui_pada' => now()->subDays(rand(1, 30))]
        );
        Skp::updateOrCreate(
            ['pengguna_id' => $marsis->id, 'sasaran_kinerja' => 'Pelayanan kebersihan area publik'],
            ['periode' => 'Semester 1 2025/2026', 'tahun' => 2026, 'indikator_kinerja' => 'Jumlah area yang dipelihara per hari', 'target_kuantitas' => 30, 'realisasi_kuantitas' => 28, 'target_kualitas' => 80, 'realisasi_kualitas' => 78, 'target_waktu' => 6, 'realisasi_waktu' => 6, 'nilai_capaian' => 85.56, 'predikat' => 'baik', 'status' => 'disetujui', 'disetujui_oleh' => $admin->id, 'disetujui_pada' => now()->subDays(rand(1, 30))]
        );

        /*
        |--------------------------------------------------------------------------
        | 6. NOTIFIKASI
        |--------------------------------------------------------------------------
        */
        $this->seedNotifikasi($staffUsers);

        /*
        |--------------------------------------------------------------------------
        | OUTPUT
        |--------------------------------------------------------------------------
        */
        $this->command->info('');
        $this->command->info('  ✅ PERAN PRAMU BAKTI (IKI 2)');
        $this->command->info('  ─────────────────────────────────────');
        $this->command->info('  Akun   : eko.pramubakti@tu.test');
        $this->command->info('           marsis.pramubakti@tu.test');
        $this->command->info('           miftahul.pramubakti@tu.test');
        $this->command->info('  Fitur  : Kehadiran 30 hari, 2 izin, 1 laporan, 2 SKP, notifikasi');
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
            ['judul' => 'Absensi berhasil tercatat',                 'msg' => 'Absensi masuk hari ini berhasil tercatat pukul 07:25 WIB.', 'jenis' => 'kehadiran'],
            ['judul' => 'Pengingat absen pulang',                    'msg' => 'Jangan lupa absen pulang sebelum meninggalkan area sekolah.', 'jenis' => 'kehadiran'],
            ['judul' => 'Pengajuan cuti disetujui',                  'msg' => 'Pengajuan cuti Anda telah disetujui oleh Kasubag TU.',       'jenis' => 'izin'],
            ['judul' => 'Agenda baru: Pelatihan Google Workspace',   'msg' => 'Pelatihan di Lab Komputer 1. Bawa laptop.',                  'jenis' => 'event'],
            ['judul' => 'Selamat datang di Sistem SIMPEG-SMART!', 'msg' => 'Akun Anda sudah aktif. Lengkapi profil.',                    'jenis' => 'sistem'],
            ['judul' => 'Pembaruan Sistem v3.0',                     'msg' => 'Fitur baru: SKP, Word AI, lokasi detail kehadiran.',         'jenis' => 'sistem'],
        ];

        foreach ($users as $staff) {
            $shuffled = collect($templates)->shuffle()->take(rand(3, 5));
            foreach ($shuffled as $idx => $n) {
                Notifikasi::create([
                    'pengguna_id'  => $staff->id,
                    'judul'        => $n['judul'],
                    'pesan'        => $n['msg'],
                    'jenis'        => $n['jenis'],
                    'sudah_dibaca' => $idx < 2,
                    'created_at'   => now()->subHours(rand(1, 168)),
                ]);
            }
        }
    }
}
