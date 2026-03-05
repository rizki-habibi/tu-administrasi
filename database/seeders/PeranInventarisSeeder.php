<?php

namespace Database\Seeders;

use App\Models\Kehadiran;
use App\Models\LaporanKerusakan;
use App\Models\Dokumen;
use App\Models\Inventaris;
use App\Models\PengajuanIzin;
use App\Models\Notifikasi;
use App\Models\Laporan;
use App\Models\Skp;
use App\Models\Pengguna;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PeranInventarisSeeder extends Seeder
{
    public function run(): void
    {
        $today = Carbon::today();
        $admin = Pengguna::where('email', 'admin@tu.test')->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | 1. AKUN INVENTARIS (IKI 6)
        |--------------------------------------------------------------------------
        */
        $fatkur = Pengguna::updateOrCreate(
            ['email' => 'fatkurahman.inventaris@tu.test'],
            [
                'nama'          => 'Fatkurahman',
                'password'      => Hash::make('password'),
                'peran'         => 'inventaris',
                'jabatan'       => 'Operator Layanan Operasional',
                'iki_pelaksana' => '6 INVENTARIS',
                'kode_depan'    => '14345',
                'telepon'       => '081298765013',
                'alamat'        => 'Jl. Karimata No. 9, Jember',
                'aktif'         => true,
                'tanggal_lahir' => '1987-06-20',
            ]
        );

        $imam = Pengguna::updateOrCreate(
            ['email' => 'imam.inventaris@tu.test'],
            [
                'nama'          => 'Imam Syafi\'i',
                'password'      => Hash::make('password'),
                'peran'         => 'inventaris',
                'jabatan'       => 'Operator Layanan Operasional',
                'iki_pelaksana' => '6 INVENTARIS',
                'kode_depan'    => '14345',
                'telepon'       => '081298765014',
                'alamat'        => 'Jl. Trunojoyo No. 18, Jember',
                'aktif'         => true,
                'tanggal_lahir' => '1992-01-05',
            ]
        );

        $staffUsers = [$fatkur, $imam];

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
            ['pengguna_id' => $fatkur->id, 'tanggal_mulai' => $today->copy()->format('Y-m-d')],
            ['jenis' => 'sakit', 'tanggal_selesai' => $today->copy()->addDays(1)->format('Y-m-d'), 'alasan' => 'Vertigo kambuh, istirahat rumah', 'status' => 'pending']
        );

        /*
        |--------------------------------------------------------------------------
        | 4. LAPORAN
        |--------------------------------------------------------------------------
        */
        Laporan::updateOrCreate(
            ['pengguna_id' => $fatkur->id, 'judul' => 'Laporan Stok Inventaris Semester Genap 2025/2026'],
            ['deskripsi' => 'Rekapitulasi seluruh inventaris: 1.247 item baik, 38 rusak ringan, 5 rusak berat.', 'kategori' => 'inventaris', 'prioritas' => 'tinggi', 'status' => 'completed']
        );
        Laporan::updateOrCreate(
            ['pengguna_id' => $imam->id, 'judul' => 'Usulan Pengadaan Barang TA 2026/2027'],
            ['deskripsi' => 'Daftar usulan pengadaan 45 item prioritas tinggi.', 'kategori' => 'inventaris', 'prioritas' => 'tinggi', 'status' => 'submitted']
        );

        /*
        |--------------------------------------------------------------------------
        | 5. SKP
        |--------------------------------------------------------------------------
        */
        Skp::updateOrCreate(
            ['pengguna_id' => $fatkur->id, 'sasaran_kinerja' => 'Pengelolaan inventaris sekolah'],
            ['periode' => 'Semester 1 2025/2026', 'tahun' => 2026, 'indikator_kinerja' => 'Persentase barang terinventarisasi dengan benar', 'target_kuantitas' => 100, 'realisasi_kuantitas' => 95, 'target_kualitas' => 85, 'realisasi_kualitas' => 90, 'target_waktu' => 6, 'realisasi_waktu' => 6, 'nilai_capaian' => 91.67, 'predikat' => 'baik', 'status' => 'disetujui', 'disetujui_oleh' => $admin->id, 'disetujui_pada' => now()->subDays(rand(1, 30))]
        );

        /*
        |--------------------------------------------------------------------------
        | 6. DATA INVENTARIS (12 item)
        |--------------------------------------------------------------------------
        */
        $inventarisData = [
            ['nama_barang' => 'Meja Guru Kayu Jati', 'kategori' => 'mebeulair', 'lokasi' => 'Ruang Guru', 'jumlah' => 30, 'kondisi' => 'baik', 'sumber_dana' => 'BOS', 'harga_perolehan' => 850000],
            ['nama_barang' => 'Kursi Guru Kayu Jati', 'kategori' => 'mebeulair', 'lokasi' => 'Ruang Guru', 'jumlah' => 30, 'kondisi' => 'baik', 'sumber_dana' => 'BOS', 'harga_perolehan' => 450000],
            ['nama_barang' => 'Meja Siswa Besi', 'kategori' => 'mebeulair', 'lokasi' => 'Kelas X-XII', 'jumlah' => 720, 'kondisi' => 'baik', 'sumber_dana' => 'APBN', 'harga_perolehan' => 650000],
            ['nama_barang' => 'Proyektor Epson EB-X51', 'kategori' => 'elektronik', 'lokasi' => 'Kelas X-XII', 'jumlah' => 24, 'kondisi' => 'baik', 'sumber_dana' => 'APBN', 'harga_perolehan' => 7500000],
            ['nama_barang' => 'Laptop ASUS VivoBook', 'kategori' => 'elektronik', 'lokasi' => 'Lab Komputer', 'jumlah' => 40, 'kondisi' => 'baik', 'sumber_dana' => 'APBN', 'harga_perolehan' => 8500000],
            ['nama_barang' => 'Printer HP LaserJet Pro', 'kategori' => 'elektronik', 'lokasi' => 'Ruang TU', 'jumlah' => 3, 'kondisi' => 'rusak_ringan', 'sumber_dana' => 'BOS', 'harga_perolehan' => 3200000],
            ['nama_barang' => 'AC Daikin 2 PK', 'kategori' => 'elektronik', 'lokasi' => 'Ruang Kepsek, Guru, Lab', 'jumlah' => 10, 'kondisi' => 'baik', 'sumber_dana' => 'APBD', 'harga_perolehan' => 6000000],
            ['nama_barang' => 'Buku Paket Matematika Kelas X', 'kategori' => 'buku', 'lokasi' => 'Perpustakaan', 'jumlah' => 180, 'kondisi' => 'baik', 'sumber_dana' => 'BOS', 'harga_perolehan' => 75000],
            ['nama_barang' => 'Buku Paket Bahasa Indonesia Kelas XI', 'kategori' => 'buku', 'lokasi' => 'Perpustakaan', 'jumlah' => 160, 'kondisi' => 'baik', 'sumber_dana' => 'BOS', 'harga_perolehan' => 80000],
            ['nama_barang' => 'Mikroskop Olympus CX23', 'kategori' => 'alat_lab', 'lokasi' => 'Lab Biologi', 'jumlah' => 15, 'kondisi' => 'baik', 'sumber_dana' => 'APBN', 'harga_perolehan' => 12500000],
            ['nama_barang' => 'Bola Basket Molten', 'kategori' => 'olahraga', 'lokasi' => 'Gudang Olahraga', 'jumlah' => 10, 'kondisi' => 'rusak_ringan', 'sumber_dana' => 'BOS', 'harga_perolehan' => 350000],
            ['nama_barang' => 'Lemari Arsip Besi 4 Laci', 'kategori' => 'mebeulair', 'lokasi' => 'Ruang TU', 'jumlah' => 8, 'kondisi' => 'baik', 'sumber_dana' => 'APBD', 'harga_perolehan' => 2800000],
        ];

        $inventarisModels = [];
        foreach ($inventarisData as $item) {
            $inv = Inventaris::updateOrCreate(
                ['nama_barang' => $item['nama_barang']],
                [
                    'kode_barang'       => Inventaris::generateKode($item['kategori']),
                    'deskripsi'         => 'Inventaris ' . $item['nama_barang'] . ' milik SMA Negeri 2 Jember',
                    'kategori'          => $item['kategori'],
                    'lokasi'            => $item['lokasi'],
                    'jumlah'            => $item['jumlah'],
                    'kondisi'           => $item['kondisi'],
                    'tanggal_perolehan' => now()->subMonths(rand(6, 60))->format('Y-m-d'),
                    'sumber_dana'       => $item['sumber_dana'],
                    'harga_perolehan'   => $item['harga_perolehan'],
                    'catatan'           => null,
                    'dibuat_oleh'       => $fatkur->id,
                ]
            );
            $inventarisModels[] = $inv;
        }

        /*
        |--------------------------------------------------------------------------
        | 7. LAPORAN KERUSAKAN (3)
        |--------------------------------------------------------------------------
        */
        // Printer rusak ringan
        LaporanKerusakan::updateOrCreate(
            ['inventaris_id' => $inventarisModels[5]->id, 'deskripsi_kerusakan' => 'Paper jam berulang, roller aus'],
            [
                'tanggal_laporan'    => now()->subDays(rand(1, 30)),
                'tingkat_kerusakan'  => 'ringan',
                'status'             => 'dalam_perbaikan',
                'tindakan'           => 'Dibawa ke service center HP untuk penggantian roller',
                'dilaporkan_oleh'    => $fatkur->id,
            ]
        );

        // Bola basket rusak ringan
        LaporanKerusakan::updateOrCreate(
            ['inventaris_id' => $inventarisModels[10]->id, 'deskripsi_kerusakan' => 'Beberapa bola sudah kempes dan lapisan kulit mengelupas'],
            [
                'tanggal_laporan'    => now()->subDays(rand(5, 45)),
                'tingkat_kerusakan'  => 'ringan',
                'status'             => 'selesai',
                'tindakan'           => 'Diganti dengan stok baru, bola lama dihapuskan dari inventaris',
                'dilaporkan_oleh'    => $imam->id,
            ]
        );

        // Proyektor salah satu rusak
        LaporanKerusakan::updateOrCreate(
            ['inventaris_id' => $inventarisModels[3]->id, 'deskripsi_kerusakan' => 'Proyektor di kelas XI IPA 3 lampu mati total setelah 3000 jam pemakaian'],
            [
                'tanggal_laporan'    => now()->subDays(rand(1, 15)),
                'tingkat_kerusakan'  => 'sedang',
                'status'             => 'menunggu_penggantian',
                'tindakan'           => 'Usulan penggantian lampu proyektor diajukan ke bendahara',
                'dilaporkan_oleh'    => $fatkur->id,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | 8. DOKUMEN
        |--------------------------------------------------------------------------
        */
        Dokumen::updateOrCreate(
            ['judul' => 'Buku Inventaris Barang Milik Negara 2026'],
            ['deskripsi' => 'Buku inventaris resmi BMN SMA Negeri 2 Jember TA 2025/2026.', 'kategori' => 'inventaris', 'path_file' => 'documents/buku-inventaris-bmn-2026.pdf', 'nama_file' => 'buku-inventaris-bmn-2026.pdf', 'tipe_file' => 'pdf', 'ukuran_file' => rand(102400, 5242880), 'diunggah_oleh' => $fatkur->id]
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
        $this->command->info('  ✅ PERAN INVENTARIS (IKI 6)');
        $this->command->info('  ─────────────────────────────────────');
        $this->command->info('  Akun   : fatkurahman.inventaris@tu.test');
        $this->command->info('           imam.inventaris@tu.test');
        $this->command->info('  Fitur  : Kehadiran 30 hari, 1 izin, 2 laporan, 1 SKP,');
        $this->command->info('           12 inventaris, 3 laporan kerusakan, 1 dokumen, notifikasi');
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
            ['judul' => 'Laporan kerusakan baru',                    'msg' => 'Ada laporan kerusakan baru yang perlu ditindaklanjuti.',     'jenis' => 'laporan'],
            ['judul' => 'Laporan Anda telah di-review',              'msg' => 'Laporan Anda ditinjau. Cek status laporan.',                 'jenis' => 'laporan'],
            ['judul' => 'Selamat datang di Sistem TU Administrasi!', 'msg' => 'Akun Anda sudah aktif. Lengkapi profil.',                    'jenis' => 'sistem'],
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
