<?php

namespace Database\Seeders;

use App\Models\Kehadiran;
use App\Models\Dokumen;
use App\Models\PengajuanIzin;
use App\Models\Notifikasi;
use App\Models\Laporan;
use App\Models\Skp;
use App\Models\Pengguna;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PeranPerpustakaanSeeder extends Seeder
{
    public function run(): void
    {
        $today = Carbon::today();
        $admin = Pengguna::where('email', 'admin@tu.test')->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | 1. AKUN PERPUSTAKAAN (IKI 5)
        |--------------------------------------------------------------------------
        */
        $anggra = Pengguna::updateOrCreate(
            ['email' => 'anggra.perpustakaan@tu.test'],
            [
                'nama'          => 'Anggra Dwi Putra',
                'password'      => Hash::make('password'),
                'peran'         => 'perpustakaan',
                'jabatan'       => 'Operator Layanan Operasional',
                'iki_pelaksana' => '5 PERPUSTAKAAN',
                'kode_depan'    => '14345',
                'telepon'       => '081298765010',
                'alamat'        => 'Jl. Nusantara No. 8, Jember',
                'aktif'         => true,
                'tanggal_lahir' => '1990-07-15',
            ]
        );

        $bagus = Pengguna::updateOrCreate(
            ['email' => 'bagus.perpustakaan@tu.test'],
            [
                'nama'          => 'Bagus Prasetyo',
                'password'      => Hash::make('password'),
                'peran'         => 'perpustakaan',
                'jabatan'       => 'Operator Layanan Operasional',
                'iki_pelaksana' => '5 PERPUSTAKAAN',
                'kode_depan'    => '14345',
                'telepon'       => '081298765011',
                'alamat'        => 'Jl. Kaliurang No. 3, Jember',
                'aktif'         => true,
                'tanggal_lahir' => '1988-03-22',
            ]
        );

        $sutrisno = Pengguna::updateOrCreate(
            ['email' => 'sutrisno.perpustakaan@tu.test'],
            [
                'nama'          => 'Sutrisno Hadi',
                'password'      => Hash::make('password'),
                'peran'         => 'perpustakaan',
                'jabatan'       => 'Operator Layanan Operasional',
                'iki_pelaksana' => '5 PERPUSTAKAAN',
                'kode_depan'    => '14345',
                'telepon'       => '081298765012',
                'alamat'        => 'Jl. Mastrip No. 44, Jember',
                'aktif'         => true,
                'tanggal_lahir' => '1985-11-30',
            ]
        );

        $staffUsers = [$anggra, $bagus, $sutrisno];

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
            ['pengguna_id' => $anggra->id, 'tanggal_mulai' => $today->copy()->addDays(3)->format('Y-m-d')],
            ['jenis' => 'izin', 'tanggal_selesai' => $today->copy()->addDays(3)->format('Y-m-d'), 'alasan' => 'Mengurus perpanjangan SIM', 'status' => 'rejected', 'disetujui_oleh' => $admin->id, 'catatan_admin' => 'Alasan tidak cukup kuat, lakukan di luar jam kerja']
        );
        PengajuanIzin::updateOrCreate(
            ['pengguna_id' => $bagus->id, 'tanggal_mulai' => $today->copy()->addDays(5)->format('Y-m-d')],
            ['jenis' => 'dinas_luar', 'tanggal_selesai' => $today->copy()->addDays(6)->format('Y-m-d'), 'alasan' => 'Pelatihan digitalisasi perpustakaan di Dinas Pendidikan', 'status' => 'pending']
        );

        /*
        |--------------------------------------------------------------------------
        | 4. LAPORAN
        |--------------------------------------------------------------------------
        */
        Laporan::updateOrCreate(
            ['pengguna_id' => $anggra->id, 'judul' => 'Laporan Stok Buku Semester Genap 2025/2026'],
            ['deskripsi' => 'Rekapitulasi stok buku perpustakaan: total 8.542 eksemplar, 215 judul baru ditambahkan.', 'kategori' => 'lainnya', 'prioritas' => 'sedang', 'status' => 'completed']
        );
        Laporan::updateOrCreate(
            ['pengguna_id' => $bagus->id, 'judul' => 'Laporan Peminjaman Buku Februari 2026'],
            ['deskripsi' => 'Total peminjaman 347 transaksi. 12 buku belum dikembalikan.', 'kategori' => 'lainnya', 'prioritas' => 'sedang', 'status' => 'submitted']
        );

        /*
        |--------------------------------------------------------------------------
        | 5. SKP
        |--------------------------------------------------------------------------
        */
        Skp::updateOrCreate(
            ['pengguna_id' => $anggra->id, 'sasaran_kinerja' => 'Pengelolaan koleksi perpustakaan'],
            ['periode' => 'Semester 1 2025/2026', 'tahun' => 2026, 'indikator_kinerja' => 'Persentase inventarisasi koleksi', 'target_kuantitas' => 100, 'realisasi_kuantitas' => 92, 'target_kualitas' => 85, 'realisasi_kualitas' => 88, 'target_waktu' => 6, 'realisasi_waktu' => 6, 'nilai_capaian' => 90.67, 'predikat' => 'baik', 'status' => 'disetujui', 'disetujui_oleh' => $admin->id, 'disetujui_pada' => now()->subDays(rand(1, 30))]
        );

        /*
        |--------------------------------------------------------------------------
        | 6. DOKUMEN
        |--------------------------------------------------------------------------
        */
        Dokumen::updateOrCreate(
            ['judul' => 'Katalog Perpustakaan Digital 2026'],
            ['deskripsi' => 'Katalog lengkap koleksi perpustakaan digital SMA Negeri 2 Jember.', 'kategori' => 'lainnya', 'path_file' => 'documents/katalog-perpustakaan-digital-2026.pdf', 'nama_file' => 'katalog-perpustakaan-digital-2026.pdf', 'tipe_file' => 'pdf', 'ukuran_file' => rand(102400, 5242880), 'diunggah_oleh' => $anggra->id]
        );

        /*
        |--------------------------------------------------------------------------
        | 7. NOTIFIKASI
        |--------------------------------------------------------------------------
        */
        $this->seedNotifikasi($staffUsers);

        /*
        |--------------------------------------------------------------------------
        | OUTPUT
        |--------------------------------------------------------------------------
        */
        $this->command->info('');
        $this->command->info('  ✅ PERAN PERPUSTAKAAN (IKI 5)');
        $this->command->info('  ─────────────────────────────────────');
        $this->command->info('  Akun   : anggra.perpustakaan@tu.test');
        $this->command->info('           bagus.perpustakaan@tu.test');
        $this->command->info('           sutrisno.perpustakaan@tu.test');
        $this->command->info('  Fitur  : Kehadiran 30 hari, 2 izin, 2 laporan, 1 SKP,');
        $this->command->info('           1 dokumen, notifikasi');
    }

    private function seedKehadiran(array $users, Carbon $today): void
    {
        $statuses  = ['hadir','hadir','hadir','hadir','hadir','terlambat','izin','sakit'];
        $addresses = ['SMA Negeri 2 Jember, Jl. Jawa No.16, Sumbersari, Jember','Halaman Parkir SMA Negeri 2 Jember','Ruang TU SMA Negeri 2 Jember, Jl. Jawa 16','Pos Satpam SMA Negeri 2 Jember','Lapangan Utama SMA Negeri 2 Jember'];

        foreach ($users as $staff) {
            for ($i = 29; $i >= 0; $i--) {
                $date = $today->copy()->subDays($i);
                if ($date->isWeekend()) continue;
                $status = $statuses[array_rand($statuses)];
                $clockIn = $clockOut = $note = $addrIn = $addrOut = null;
                switch ($status) {
                    case 'hadir':     $clockIn = sprintf('07:%02d', rand(10, 29)); $clockOut = sprintf('%02d:%02d', rand(15, 16), rand(0, 30)); $addrIn = $addresses[array_rand($addresses)]; $addrOut = $addresses[array_rand($addresses)]; break;
                    case 'terlambat': $clockIn = sprintf('07:%02d', rand(46, 59)); $clockOut = sprintf('%02d:%02d', rand(15, 16), rand(0, 30)); $note = 'Terlambat: ' . collect(['macet di jalan','ban bocor','antar anak sekolah','hujan deras'])->random(); $addrIn = $addresses[array_rand($addresses)]; $addrOut = $addresses[array_rand($addresses)]; break;
                    case 'izin':  $note = collect(['Urusan keluarga','Mengurus dokumen pribadi','Keperluan mendadak'])->random(); break;
                    case 'sakit': $note = collect(['Demam dan flu','Sakit perut','Periksa ke dokter','Masuk angin'])->random(); break;
                }
                Kehadiran::updateOrCreate(
                    ['pengguna_id' => $staff->id, 'tanggal' => $date->format('Y-m-d')],
                    ['jam_masuk' => $clockIn, 'jam_pulang' => $clockOut, 'status' => $status, 'lat_masuk' => $clockIn ? -8.165908 + (rand(-50, 50) / 100000) : null, 'lng_masuk' => $clockIn ? 113.706649 + (rand(-50, 50) / 100000) : null, 'alamat_masuk' => $addrIn, 'lat_pulang' => $clockOut ? -8.165908 + (rand(-50, 50) / 100000) : null, 'lng_pulang' => $clockOut ? 113.706649 + (rand(-50, 50) / 100000) : null, 'alamat_pulang' => $addrOut, 'catatan' => $note]
                );
            }
        }
    }

    private function seedNotifikasi(array $users): void
    {
        $templates = [
            ['judul' => 'Absensi berhasil tercatat',                 'msg' => 'Absensi masuk hari ini berhasil tercatat pukul 07:25 WIB.', 'jenis' => 'kehadiran'],
            ['judul' => 'Pengingat absen pulang',                    'msg' => 'Jangan lupa absen pulang sebelum meninggalkan area sekolah.', 'jenis' => 'kehadiran'],
            ['judul' => 'Pengajuan izin ditolak',                    'msg' => 'Pengajuan izin Anda tidak disetujui. Lihat catatan admin.',    'jenis' => 'izin'],
            ['judul' => 'Laporan Anda telah di-review',              'msg' => 'Laporan Anda ditinjau. Cek status laporan.',                 'jenis' => 'laporan'],
            ['judul' => 'Selamat datang di Sistem SIMPEG-SMART!', 'msg' => 'Akun Anda sudah aktif. Lengkapi profil.',                    'jenis' => 'sistem'],
            ['judul' => 'Pembaruan Sistem v3.0',                     'msg' => 'Fitur baru: SKP, Word AI, lokasi detail kehadiran.',         'jenis' => 'sistem'],
        ];

        foreach ($users as $staff) {
            $shuffled = collect($templates)->shuffle()->take(rand(3, 5));
            foreach ($shuffled as $idx => $n) {
                Notifikasi::create(['pengguna_id' => $staff->id, 'judul' => $n['judul'], 'pesan' => $n['msg'], 'jenis' => $n['jenis'], 'sudah_dibaca' => $idx < 2, 'created_at' => now()->subHours(rand(1, 168))]);
            }
        }
    }
}
