<?php

namespace Database\Seeders;

use App\Models\Pengguna;
use App\Models\Kehadiran;
use App\Models\PengaturanKehadiran;
use App\Models\PengajuanIzin;
use App\Models\Laporan;
use App\Models\Acara;
use App\Models\Notifikasi;
use App\Models\Dokumen;
use App\Models\Skp;
use App\Models\LogbookMagang;
use App\Models\KegiatanMagang;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $today = Carbon::today();
        $todayMonthDay = $today->format('m-d');
        /*
        |--------------------------------------------------------------------------
        | 1. ADMIN ACCOUNT (Kepala Tata Usaha)
        |--------------------------------------------------------------------------
        */
        $admin = Pengguna::updateOrCreate(
            ['email' => 'admin@tu.test'],
            [
                'nama'     => 'Drs. Bambang Supriyanto, M.Pd.',
                'nip'      => '196805151992031005',
                'password' => Hash::make('password'),
                'peran'     => 'admin',
                'jabatan' => 'Kepala Tata Usaha',
                'telepon'    => '081234567890',
                'alamat'  => 'Jl. Mastrip No. 45, Kel. Sumbersari, Kec. Sumbersari, Jember',
                'aktif' => true,
                'tanggal_lahir' => '1968-05-15',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | 2. KEPALA SEKOLAH
        |--------------------------------------------------------------------------
        */
        $kepsek = Pengguna::updateOrCreate(
            ['email' => 'kepsek@tu.test'],
            [
                'nama'     => 'Dr. H. Sugianto, M.Pd.',
                'nip'      => '196701011991031001',
                'password' => Hash::make('password'),
                'peran'     => 'kepala_sekolah',
                'jabatan' => 'Kepala Sekolah',
                'telepon'    => '081234567800',
                'alamat'  => 'Jl. Kaliurang No. 10, Jember',
                'aktif' => true,
                'tanggal_lahir' => '1967-01-01',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | 3. STAFF ACCOUNTS — Berdasarkan data IKI Pelaksana
        |--------------------------------------------------------------------------
        | Roles:
        | 1. kepegawaian         = IKI 1 KEPEGAWAIAN
        | 2. pramu_bakti         = IKI 2 PRAMU BAKTI
        | 3. keuangan            = IKI 3 KEUANGAN
        | 4. persuratan          = IKI 4 PERSURATAN
        | 5. perpustakaan        = IKI 5 PERPUSTAKAAN
        | 6. inventaris          = IKI 6 INVENTARIS/SARPRAS
        | 7. kesiswaan_kurikulum = IKI 7 KESISWAAN/KURIKULUM
        */
        $staffData = [
            // === IKI 1: KEPEGAWAIAN ===
            [
                'nama'           => 'Dwi Kriswahyudi',
                'email'          => 'dwi.kepegawaian@tu.test',
                'peran'           => 'kepegawaian',
                'jabatan'       => 'Penata Layanan Operasional',
                'iki_pelaksana'  => '1 KEPEGAWAIAN',
                'kode_depan'     => '14344',
                'telepon'          => '081298765001',
                'alamat'        => 'Jl. Kalimantan No. 12, Jember',
                'tanggal_lahir'  => '1985-' . $todayMonthDay,
            ],
            [
                'nama'           => 'Faizz Moch. Nur Adam',
                'email'          => 'faizz.kepegawaian@tu.test',
                'peran'           => 'kepegawaian',
                'jabatan'       => 'Operator Layanan Operasional',
                'iki_pelaksana'  => '1 KEPEGAWAIAN',
                'kode_depan'     => '14344',
                'telepon'          => '081298765002',
                'alamat'        => 'Jl. Sumatera No. 8, Jember',
                'tanggal_lahir'  => '1992-07-14',
            ],

            // === IKI 2: PRAMU BAKTI ===
            [
                'nama'           => 'Eko Bagus Febrianto',
                'email'          => 'eko.pramubakti@tu.test',
                'peran'           => 'pramu_bakti',
                'jabatan'       => 'Operator Layanan Operasional',
                'iki_pelaksana'  => '2 PRAMU BAKTI',
                'kode_depan'     => '23304',
                'telepon'          => '081298765003',
                'alamat'        => 'Jl. Jawa No. 25, Jember',
                'tanggal_lahir'  => '1990-02-15',
            ],
            [
                'nama'           => 'Marsis',
                'email'          => 'marsis.pramubakti@tu.test',
                'peran'           => 'pramu_bakti',
                'jabatan'       => 'Pengelola Umum Operasional',
                'iki_pelaksana'  => '2 PRAMU BAKTI',
                'kode_depan'     => '23304',
                'telepon'          => '081298765004',
                'alamat'        => 'Jl. Sulawesi No. 3, Jember',
                'tanggal_lahir'  => '1978-11-20',
            ],
            [
                'nama'           => 'Miftahul Ulum',
                'email'          => 'miftahul.pramubakti@tu.test',
                'peran'           => 'pramu_bakti',
                'jabatan'       => 'Pengelola Umum Operasional',
                'iki_pelaksana'  => '2 PRAMU BAKTI',
                'kode_depan'     => '23304',
                'telepon'          => '081298765005',
                'alamat'        => 'Jl. Borneo No. 17, Jember',
                'tanggal_lahir'  => '1988-06-03',
            ],

            // === IKI 3: KEUANGAN ===
            [
                'nama'           => 'Ike Wijayanti',
                'email'          => 'ike.keuangan@tu.test',
                'peran'           => 'keuangan',
                'jabatan'       => 'Penata Layanan Operasional',
                'iki_pelaksana'  => '3 KEUANGAN',
                'kode_depan'     => '14342',
                'telepon'          => '081298765006',
                'alamat'        => 'Jl. Papua No. 9, Jember',
                'tanggal_lahir'  => '1986-09-25',
            ],

            // === IKI 4: PERSURATAN ===
            [
                'nama'           => 'Aris Sugito',
                'email'          => 'aris.persuratan@tu.test',
                'peran'           => 'persuratan',
                'jabatan'       => 'Operator Layanan Operasional',
                'iki_pelaksana'  => '4 PERSURATAN',
                'kode_depan'     => '14345',
                'telepon'          => '081298765007',
                'alamat'        => 'Jl. Bali No. 22, Jember',
                'tanggal_lahir'  => '1983-12-10',
            ],
            [
                'nama'           => 'Ginabul Rahayu',
                'email'          => 'ginabul.persuratan@tu.test',
                'peran'           => 'persuratan',
                'jabatan'       => 'Operator Layanan Operasional',
                'iki_pelaksana'  => '4 PERSURATAN',
                'kode_depan'     => '14345',
                'telepon'          => '081298765008',
                'alamat'        => 'Jl. Flores No. 5, Jember',
                'tanggal_lahir'  => '1991-04-18',
            ],
            [
                'nama'           => 'Herman Budi Santoso',
                'email'          => 'herman.persuratan@tu.test',
                'peran'           => 'persuratan',
                'jabatan'       => 'Operator Layanan Operasional',
                'iki_pelaksana'  => '4 PERSURATAN',
                'kode_depan'     => '14345',
                'telepon'          => '081298765009',
                'alamat'        => 'Jl. Lombok No. 14, Jember',
                'tanggal_lahir'  => '1980-' . $todayMonthDay,
            ],

            // === IKI 5: PERPUSTAKAAN ===
            [
                'nama'           => 'Anggra Okta Wijaya',
                'email'          => 'anggra.perpustakaan@tu.test',
                'peran'           => 'perpustakaan',
                'jabatan'       => 'Penata Layanan Operasional',
                'iki_pelaksana'  => '5 PERPUSTAKAAN',
                'kode_depan'     => '19463',
                'telepon'          => '081298765010',
                'alamat'        => 'Jl. Timor No. 7, Jember',
                'tanggal_lahir'  => '1993-10-08',
            ],
            [
                'nama'           => 'Bagus Pribadi',
                'email'          => 'bagus.perpustakaan@tu.test',
                'peran'           => 'perpustakaan',
                'jabatan'       => 'Operator Layanan Operasional',
                'iki_pelaksana'  => '5 PERPUSTAKAAN',
                'kode_depan'     => '19463',
                'telepon'          => '081298765011',
                'alamat'        => 'Jl. Madura No. 33, Jember',
                'tanggal_lahir'  => '1987-01-27',
            ],
            [
                'nama'           => 'Moh. Sutrisno',
                'email'          => 'sutrisno.perpustakaan@tu.test',
                'peran'           => 'perpustakaan',
                'jabatan'       => 'Operator Layanan Operasional',
                'iki_pelaksana'  => '5 PERPUSTAKAAN',
                'kode_depan'     => '19463',
                'telepon'          => '081298765012',
                'alamat'        => 'Jl. Nusa Tenggara No. 11, Jember',
                'tanggal_lahir'  => '1979-08-30',
            ],

            // === IKI 6: INVENTARIS/SARPRAS ===
            [
                'nama'           => 'Fatkurahman',
                'email'          => 'fatkurahman.inventaris@tu.test',
                'peran'           => 'inventaris',
                'jabatan'       => 'Operator Layanan Operasional',
                'iki_pelaksana'  => '6 INVENTARIS/SARPRAS',
                'kode_depan'     => '14343',
                'telepon'          => '081298765013',
                'alamat'        => 'Jl. Kartini No. 20, Jember',
                'tanggal_lahir'  => '1984-03-22',
            ],
            [
                'nama'           => 'Imam Basori',
                'email'          => 'imam.inventaris@tu.test',
                'peran'           => 'inventaris',
                'jabatan'       => 'Operator Layanan Operasional',
                'iki_pelaksana'  => '6 INVENTARIS/SARPRAS',
                'kode_depan'     => '14343',
                'telepon'          => '081298765014',
                'alamat'        => 'Jl. Diponegoro No. 15, Jember',
                'tanggal_lahir'  => '1989-05-11',
            ],

            // === IKI 7: KESISWAAN/KURIKULUM ===
            [
                'nama'           => 'Bayu Kurniawan',
                'email'          => 'bayu.kesiswaan@tu.test',
                'peran'           => 'kesiswaan_kurikulum',
                'jabatan'       => 'Penata Layanan Operasional',
                'iki_pelaksana'  => '7 KESISWAAN/KURIKULUM',
                'kode_depan'     => '23305',
                'telepon'          => '081298765015',
                'alamat'        => 'Jl. Gajah Mada No. 10, Jember',
                'tanggal_lahir'  => '1991-12-01',
            ],
            [
                'nama'           => 'Wikana Subadra Subowo',
                'email'          => 'wikana.kesiswaan@tu.test',
                'peran'           => 'kesiswaan_kurikulum',
                'jabatan'       => 'Operator Layanan Operasional',
                'iki_pelaksana'  => '7 KESISWAAN/KURIKULUM',
                'kode_depan'     => '23305',
                'telepon'          => '081298765016',
                'alamat'        => 'Jl. Sudirman No. 42, Jember',
                'tanggal_lahir'  => '1982-08-19',
            ],

            // === STAFF UMUM (Tenaga Kependidikan) ===
            [
                'nama'           => 'Siti Aminah',
                'email'          => 'siti.staff@tu.test',
                'peran'           => 'staff',
                'jabatan'       => 'Tenaga Kependidikan',
                'iki_pelaksana'  => null,
                'kode_depan'     => null,
                'telepon'          => '081298765017',
                'alamat'        => 'Jl. Ahmad Yani No. 55, Jember',
                'tanggal_lahir'  => '1990-04-12',
            ],
        ];

        $staffUsers = [];
        foreach ($staffData as $data) {
            $role = $data['peran'];
            unset($data['peran']);
            $staffUsers[] = Pengguna::updateOrCreate(
                ['email' => $data['email']],
                array_merge($data, [
                    'password'  => Hash::make('password'),
                    'peran'      => $role,
                    'aktif' => true,
                ])
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 4. ATTENDANCE SETTINGS
        |--------------------------------------------------------------------------
        */
        PengaturanKehadiran::updateOrCreate(
            ['id' => 1],
            [
                'jam_masuk'          => '07:30',
                'jam_pulang'         => '16:00',
                'toleransi_terlambat_menit'  => 15,
                'lat_kantor'         => -8.165908,
                'lng_kantor'        => 113.706649,
                'jarak_maksimal_meter'     => 200,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | 5. ATTENDANCE DATA — 30 hari terakhir
        |--------------------------------------------------------------------------
        */
        $statuses   = ['hadir','hadir','hadir','hadir','hadir','terlambat','izin','sakit'];
        $today      = Carbon::today();
        $addresses  = [
            'SMA Negeri 2 Jember, Jl. Jawa No.16, Sumbersari, Jember',
            'Halaman Parkir SMA Negeri 2 Jember',
            'Ruang TU SMA Negeri 2 Jember, Jl. Jawa 16',
            'Pos Satpam SMA Negeri 2 Jember',
            'Lapangan Utama SMA Negeri 2 Jember',
        ];

        foreach ($staffUsers as $staff) {
            if (!$staff->aktif) continue;

            for ($i = 29; $i >= 0; $i--) {
                $date = $today->copy()->subDays($i);
                if ($date->isWeekend()) continue;

                $status   = $statuses[array_rand($statuses)];
                $clockIn  = null;
                $clockOut = null;
                $note     = null;
                $addrIn   = null;
                $addrOut  = null;

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
                        $note     = 'Terlambat: ' . collect(['macet di jalan', 'ban bocor', 'antar anak sekolah', 'hujan deras'])->random();
                        $addrIn   = $addresses[array_rand($addresses)];
                        $addrOut  = $addresses[array_rand($addresses)];
                        break;
                    case 'izin':
                        $note = collect(['Urusan keluarga', 'Mengurus dokumen pribadi', 'Keperluan mendadak'])->random();
                        break;
                    case 'sakit':
                        $note = collect(['Demam dan flu', 'Sakit perut', 'Periksa ke dokter', 'Masuk angin'])->random();
                        break;
                }

                Kehadiran::updateOrCreate(
                    ['pengguna_id' => $staff->id, 'tanggal' => $date->format('Y-m-d')],
                    [
                        'jam_masuk'      => $clockIn,
                        'jam_pulang'     => $clockOut,
                        'status'        => $status,
                        'lat_masuk'   => $clockIn ? -8.165908 + (rand(-50, 50) / 100000) : null,
                        'lng_masuk'  => $clockIn ? 113.706649 + (rand(-50, 50) / 100000) : null,
                        'alamat_masuk'    => $addrIn,
                        'lat_pulang'  => $clockOut ? -8.165908 + (rand(-50, 50) / 100000) : null,
                        'lng_pulang' => $clockOut ? 113.706649 + (rand(-50, 50) / 100000) : null,
                        'alamat_pulang'   => $addrOut,
                        'catatan'          => $note,
                    ]
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 6. LEAVE REQUESTS
        |--------------------------------------------------------------------------
        */
        $leaveData = [
            ['user' => 0,  'jenis' => 'izin',       'start' => 5,   'end' => 5,   'alasan' => 'Menghadiri pernikahan saudara di Surabaya',          'status' => 'approved'],
            ['user' => 1,  'jenis' => 'sakit',       'start' => 3,   'end' => 4,   'alasan' => 'Demam tinggi, surat dokter terlampir',               'status' => 'approved'],
            ['user' => 2,  'jenis' => 'cuti',        'start' => 10,  'end' => 12,  'alasan' => 'Cuti tahunan untuk liburan keluarga',                'status' => 'approved'],
            ['user' => 3,  'jenis' => 'dinas_luar',  'start' => 7,   'end' => 8,   'alasan' => 'Pelatihan di Dinas Pendidikan Kab. Jember',          'status' => 'approved'],
            ['user' => 5,  'jenis' => 'izin',        'start' => 2,   'end' => 2,   'alasan' => 'Mengantar anak kontrol ke rumah sakit',              'status' => 'pending'],
            ['user' => 6,  'jenis' => 'sakit',       'start' => 1,   'end' => 2,   'alasan' => 'Kecelakaan ringan, istirahat di rumah',              'status' => 'pending'],
            ['user' => 7,  'jenis' => 'cuti',        'start' => -3,  'end' => -1,  'alasan' => 'Mudik ke kampung halaman',                           'status' => 'approved'],
            ['user' => 9,  'jenis' => 'izin',        'start' => 4,   'end' => 4,   'alasan' => 'Mengurus perpanjangan SIM',                          'status' => 'rejected'],
            ['user' => 10, 'jenis' => 'dinas_luar',  'start' => 6,   'end' => 7,   'alasan' => 'Workshop Perpustakaan Digital di Malang',            'status' => 'pending'],
            ['user' => 12, 'jenis' => 'sakit',       'start' => 0,   'end' => 1,   'alasan' => 'Vertigo kambuh, butuh istirahat',                   'status' => 'pending'],
            ['user' => 14, 'jenis' => 'izin',        'start' => -5,  'end' => -5,  'alasan' => 'Menghadiri wisuda anak',                             'status' => 'approved'],
            ['user' => 15, 'jenis' => 'cuti',        'start' => 8,   'end' => 12,  'alasan' => 'Cuti menemani istri melahirkan',                     'status' => 'pending'],
        ];

        foreach ($leaveData as $ld) {
            if (!isset($staffUsers[$ld['user']])) continue;
            PengajuanIzin::updateOrCreate(
                ['pengguna_id' => $staffUsers[$ld['user']]->id, 'tanggal_mulai' => $today->copy()->addDays($ld['start'])->format('Y-m-d')],
                [
                    'jenis'        => $ld['jenis'],
                    'tanggal_selesai'    => $today->copy()->addDays($ld['end'])->format('Y-m-d'),
                    'alasan'      => $ld['alasan'],
                    'status'      => $ld['status'],
                    'disetujui_oleh' => $ld['status'] !== 'pending' ? $admin->id : null,
                    'catatan_admin'  => match($ld['status']) {
                        'rejected' => 'Mohon ajukan di hari yang lebih tepat',
                        'approved' => 'Disetujui oleh Kasubag TU',
                        default    => null,
                    },
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 7. REPORTS
        |--------------------------------------------------------------------------
        */
        $reportsData = [
            ['user' => 0,  'judul' => 'Laporan Data Pegawai Semester Ganjil 2025/2026',      'desc' => 'Rekapitulasi data kepegawaian seluruh guru & staff semester ganjil.', 'cat' => 'lainnya', 'prioritas' => 'tinggi', 'status' => 'submitted'],
            ['user' => 1,  'judul' => 'Rekap SKP Pegawai Semester Ganjil',                    'desc' => 'Rekapitulasi SKP seluruh TU. 16 pegawai sudah mengumpulkan.', 'cat' => 'lainnya', 'prioritas' => 'sedang', 'status' => 'completed'],
            ['user' => 2,  'judul' => 'Laporan Kebersihan Gedung Februari 2026',               'desc' => 'Laporan kondisi kebersihan gedung A, B, C dan ruang kelas.', 'cat' => 'kegiatan', 'prioritas' => 'rendah', 'status' => 'completed'],
            ['user' => 5,  'judul' => 'Laporan Realisasi Anggaran BOS Triwulan I 2026',       'desc' => 'Realisasi dana BOS triwulan I. Total Rp 245jt dari Rp 300jt (81.67%).', 'cat' => 'keuangan', 'prioritas' => 'tinggi', 'status' => 'submitted'],
            ['user' => 5,  'judul' => 'Rekapitulasi Gaji Pegawai Februari 2026',              'desc' => 'Rekap gaji seluruh pegawai (PNS & honorer) Februari 2026.', 'cat' => 'keuangan', 'prioritas' => 'tinggi', 'status' => 'completed'],
            ['user' => 6,  'judul' => 'Buku Agenda Surat Masuk Februari 2026',                 'desc' => 'Rekapitulasi 47 surat masuk bulan Februari.', 'cat' => 'surat_masuk', 'prioritas' => 'sedang', 'status' => 'completed'],
            ['user' => 7,  'judul' => 'Buku Agenda Surat Keluar Februari 2026',                'desc' => 'Rekapitulasi 35 surat keluar bulan Februari.', 'cat' => 'surat_keluar', 'prioritas' => 'sedang', 'status' => 'submitted'],
            ['user' => 9,  'judul' => 'Laporan Stok Buku Perpustakaan 2026',                   'desc' => 'Audit koleksi perpustakaan: 12.000 buku, 500 judul baru.', 'cat' => 'lainnya', 'prioritas' => 'sedang', 'status' => 'submitted'],
            ['user' => 10, 'judul' => 'Laporan Peminjaman Buku Semester Ganjil',               'desc' => '2.500 transaksi peminjaman, 98% pengembalian tepat waktu.', 'cat' => 'lainnya', 'prioritas' => 'rendah', 'status' => 'completed'],
            ['user' => 12, 'judul' => 'Laporan Stok Barang Inventaris Semester I 2026',        'desc' => 'Audit inventaris: 45 komputer, 30 proyektor, total aset Rp 2.1M.', 'cat' => 'inventaris', 'prioritas' => 'sedang', 'status' => 'submitted'],
            ['user' => 13, 'judul' => 'Usulan Pengadaan Barang TA 2026',                       'desc' => '10 komputer, 5 proyektor, 50 meja-kursi. Estimasi Rp 385jt.', 'cat' => 'inventaris', 'prioritas' => 'tinggi', 'status' => 'draft'],
            ['user' => 14, 'judul' => 'Data PPDB Online 2026/2027',                            'desc' => 'Kuota 320. Pendaftar 478. Zonasi 80%, prestasi 15%, afirmasi 5%.', 'cat' => 'lainnya', 'prioritas' => 'tinggi', 'status' => 'submitted'],
            ['user' => 15, 'judul' => 'Rekap Absensi Siswa Kelas XII Semester Ganjil',         'desc' => 'Kehadiran 288 siswa XII. Rata-rata 92.3%. 12 siswa <75%.', 'cat' => 'kegiatan', 'prioritas' => 'sedang', 'status' => 'completed'],
        ];

        foreach ($reportsData as $rd) {
            if (!isset($staffUsers[$rd['user']])) continue;
            Laporan::updateOrCreate(
                ['pengguna_id' => $staffUsers[$rd['user']]->id, 'judul' => $rd['judul']],
                [
                    'deskripsi' => $rd['desc'],
                    'kategori'    => $rd['cat'],
                    'prioritas'    => $rd['prioritas'],
                    'status'      => $rd['status'],
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 8. EVENTS
        |--------------------------------------------------------------------------
        */
        $eventsData = [
            ['judul' => 'Penerimaan Rapor Semester Ganjil', 'desc' => 'Pembagian rapor semester ganjil 2025/2026.', 'date' => -30, 'start' => '08:00', 'end' => '12:00', 'loc' => 'Ruang Kelas', 'jenis' => 'kegiatan', 'status' => 'completed'],
            ['judul' => 'Rapat Pleno Dewan Guru', 'desc' => 'Evaluasi KBM semester ganjil dan persiapan genap.', 'date' => -25, 'start' => '09:00', 'end' => '14:00', 'loc' => 'Ruang Guru', 'jenis' => 'rapat', 'status' => 'completed'],
            ['judul' => 'Upacara Bendera Hari Senin', 'desc' => 'Upacara rutin. Pembina: Kepala Sekolah.', 'date' => 1, 'start' => '07:00', 'end' => '07:45', 'loc' => 'Lapangan Utama', 'jenis' => 'upacara', 'status' => 'upcoming'],
            ['judul' => 'Pelatihan Google Workspace', 'desc' => 'Pelatihan Google Classroom, Drive, Meet, Forms.', 'date' => 3, 'start' => '08:00', 'end' => '15:00', 'loc' => 'Lab Komputer 1', 'jenis' => 'pelatihan', 'status' => 'upcoming'],
            ['judul' => 'Rapat Persiapan PPDB 2026/2027', 'desc' => 'Teknis PPDB: kuota per jalur, zona, jadwal, panitia.', 'date' => 5, 'start' => '13:00', 'end' => '15:00', 'loc' => 'Ruang Rapat', 'jenis' => 'rapat', 'status' => 'upcoming'],
            ['judul' => 'Class Meeting & Pentas Seni', 'desc' => 'Futsal, voli, cerdas cermat, lomba poster, pentas seni.', 'date' => 8, 'start' => '07:00', 'end' => '14:00', 'loc' => 'Aula & Lapangan', 'jenis' => 'kegiatan', 'status' => 'upcoming'],
            ['judul' => 'Ujian Tengah Semester Genap', 'desc' => 'UTS genap kelas X & XI. 18 mapel, 5 hari.', 'date' => 30, 'start' => '07:30', 'end' => '12:00', 'loc' => 'Ruang Kelas', 'jenis' => 'kegiatan', 'status' => 'upcoming'],
        ];

        foreach ($eventsData as $ed) {
            Acara::updateOrCreate(
                ['judul' => $ed['judul']],
                [
                    'dibuat_oleh'  => $admin->id,
                    'deskripsi' => $ed['desc'],
                    'tanggal_acara'  => $today->copy()->addDays($ed['date'])->format('Y-m-d'),
                    'waktu_mulai'  => $ed['start'],
                    'waktu_selesai'    => $ed['end'],
                    'lokasi'    => $ed['loc'],
                    'jenis'        => $ed['jenis'],
                    'status'      => $ed['status'],
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 9. NOTIFICATIONS
        |--------------------------------------------------------------------------
        */
        $notifTemplates = [
            ['judul' => 'Absensi berhasil tercatat', 'msg' => 'Absensi masuk hari ini berhasil tercatat pukul 07:25 WIB.', 'jenis' => 'kehadiran'],
            ['judul' => 'Pengingat absen pulang', 'msg' => 'Jangan lupa absen pulang sebelum meninggalkan area sekolah.', 'jenis' => 'kehadiran'],
            ['judul' => 'Pengajuan izin disetujui', 'msg' => 'Pengajuan izin Anda telah disetujui oleh Kasubag TU.', 'jenis' => 'izin'],
            ['judul' => 'Agenda baru: Pelatihan Google Workspace', 'msg' => 'Pelatihan di Lab Komputer 1. Bawa laptop.', 'jenis' => 'event'],
            ['judul' => 'Laporan Anda telah di-review', 'msg' => 'Laporan Anda ditinjau. Cek status laporan.', 'jenis' => 'laporan'],
            ['judul' => 'Selamat datang di Sistem SIMPEG-SMART!', 'msg' => 'Akun Anda sudah aktif. Lengkapi profil.', 'jenis' => 'sistem'],
            ['judul' => 'Pembaruan Sistem v3.0', 'msg' => 'Fitur baru: SKP, Word AI, lokasi detail kehadiran.', 'jenis' => 'sistem'],
        ];

        foreach ($staffUsers as $staff) {
            $count = rand(3, 5);
            $shuffled = collect($notifTemplates)->shuffle()->take($count);
            foreach ($shuffled as $idx => $n) {
                Notifikasi::create([
                    'pengguna_id' => $staff->id, 'judul' => $n['judul'], 'pesan' => $n['msg'],
                    'jenis' => $n['jenis'], 'sudah_dibaca' => $idx < 2, 'created_at' => now()->subHours(rand(1, 168)),
                ]);
            }
        }

        foreach ([$admin, $kepsek] as $u) {
            Notifikasi::create(['pengguna_id' => $u->id, 'judul' => 'Pengajuan izin baru menunggu persetujuan', 'pesan' => 'Ada pengajuan izin baru yang memerlukan persetujuan.', 'jenis' => 'izin', 'sudah_dibaca' => false]);
            Notifikasi::create(['pengguna_id' => $u->id, 'judul' => 'Laporan baru perlu ditinjau', 'pesan' => 'Ada laporan baru yang perlu ditinjau.', 'jenis' => 'laporan', 'sudah_dibaca' => false]);
        }

        /*
        |--------------------------------------------------------------------------
        | 10. DOCUMENTS
        |--------------------------------------------------------------------------
        */
        $documentsData = [
            ['judul' => 'KTSP SMA Negeri 2 Jember TA 2025/2026', 'desc' => 'Dokumen KTSP lengkap.', 'cat' => 'kurikulum', 'user' => 14],
            ['judul' => 'Jadwal Pelajaran Semester Genap', 'desc' => 'Jadwal KBM kelas X-XII.', 'cat' => 'kurikulum', 'user' => 15],
            ['judul' => 'Daftar Urut Kepangkatan (DUK) 2026', 'desc' => 'DUK seluruh PNS.', 'cat' => 'kepegawaian', 'user' => 0],
            ['judul' => 'Rekap Data Guru & Karyawan', 'desc' => 'Data lengkap guru dan karyawan.', 'cat' => 'kepegawaian', 'user' => 1],
            ['judul' => 'RKAS 2026', 'desc' => 'Perencanaan anggaran.', 'cat' => 'keuangan', 'user' => 5],
            ['judul' => 'Laporan BOS Triwulan IV 2025', 'desc' => 'Pertanggungjawaban dana BOS.', 'cat' => 'keuangan', 'user' => 5],
            ['judul' => 'Template Surat Keterangan Aktif', 'desc' => 'Template resmi surat.', 'cat' => 'surat', 'user' => 6],
            ['judul' => 'Arsip Surat Masuk Januari 2026', 'desc' => 'Scan digital surat masuk.', 'cat' => 'surat', 'user' => 7],
            ['judul' => 'Buku Inventaris Barang 2026', 'desc' => '2.500+ item inventaris.', 'cat' => 'inventaris', 'user' => 12],
            ['judul' => 'Data Pokok Siswa TA 2025/2026', 'desc' => 'Data 960 siswa aktif.', 'cat' => 'kesiswaan', 'user' => 14],
            ['judul' => 'Tata Tertib Siswa', 'desc' => 'Peraturan dan tata tertib.', 'cat' => 'kesiswaan', 'user' => 15],
            ['judul' => 'Katalog Perpustakaan Digital', 'desc' => '12.000 judul buku.', 'cat' => 'lainnya', 'user' => 9],
        ];

        foreach ($documentsData as $dd) {
            if (!isset($staffUsers[$dd['user']])) continue;
            Dokumen::updateOrCreate(
                ['judul' => $dd['judul']],
                [
                    'deskripsi'  => $dd['desc'],
                    'kategori'     => $dd['cat'],
                    'path_file'    => 'documents/' . \Illuminate\Support\Str::slug($dd['judul']) . '.pdf',
                    'nama_file'    => \Illuminate\Support\Str::slug($dd['judul']) . '.pdf',
                    'tipe_file'    => 'pdf',
                    'ukuran_file'    => rand(102400, 5242880),
                    'diunggah_oleh'  => $staffUsers[$dd['user']]->id,
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 11. SURAT
        |--------------------------------------------------------------------------
        */
        $suratData = [
            ['jenis'=>'keluar','kategori'=>'dinas','perihal'=>'Permohonan Bantuan Dana Operasional','tujuan'=>'Dinas Pendidikan Kota','sifat'=>'penting','status'=>'dikirim','user'=>6,'isi'=>'Permohonan bantuan dana operasional sekolah semester genap.'],
            ['jenis'=>'masuk','kategori'=>'undangan','perihal'=>'Undangan Rapat Koordinasi Kepsek','asal'=>'Dinas Pendidikan Kota','sifat'=>'penting','status'=>'diterima','user'=>7,'isi'=>'Undangan rapat koordinasi kepala sekolah se-Kota.'],
            ['jenis'=>'keluar','kategori'=>'keterangan','perihal'=>'Surat Keterangan Aktif Bekerja','tujuan'=>'Bank BRI','sifat'=>'biasa','status'=>'dikirim','user'=>0,'isi'=>'Surat keterangan pegawai aktif.'],
            ['jenis'=>'keluar','kategori'=>'tugas','perihal'=>'Surat Tugas Pelatihan Kurikulum','tujuan'=>'Guru & Staff','sifat'=>'penting','status'=>'dikirim','user'=>14,'isi'=>'Surat tugas pelatihan kurikulum merdeka.'],
            ['jenis'=>'masuk','kategori'=>'edaran','perihal'=>'Edaran PPDB 2025/2026','asal'=>'Dinas Pendidikan Provinsi','sifat'=>'segera','status'=>'diterima','user'=>8,'isi'=>'Edaran pelaksanaan PPDB.'],
            ['jenis'=>'keluar','kategori'=>'dinas','perihal'=>'Laporan Realisasi BOS Triwulan II','tujuan'=>'Dinas Pendidikan','sifat'=>'penting','status'=>'dikirim','user'=>5,'isi'=>'Laporan realisasi penggunaan dana BOS.'],
            ['jenis'=>'masuk','kategori'=>'dinas','perihal'=>'Petunjuk Teknis RKAS 2026','asal'=>'Dinas Pendidikan Provinsi','sifat'=>'penting','status'=>'diproses','user'=>5,'isi'=>'Juknis penyusunan RKAS.'],
            ['jenis'=>'keluar','kategori'=>'pemberitahuan','perihal'=>'Pemberitahuan Jadwal UTS','tujuan'=>'Orang Tua / Wali','sifat'=>'biasa','status'=>'dikirim','user'=>15,'isi'=>'Jadwal UTS semester genap.'],
        ];

        foreach ($suratData as $sd) {
            if (!isset($staffUsers[$sd['user']])) continue;
            $tanggal = now()->subDays(rand(1, 90));
            \App\Models\Surat::updateOrCreate(
                ['perihal' => $sd['perihal']],
                [
                    'nomor_surat'    => \App\Models\Surat::generateNomor($sd['jenis'], $sd['kategori']),
                    'jenis'          => $sd['jenis'],
                    'kategori'       => $sd['kategori'],
                    'perihal'        => $sd['perihal'],
                    'isi'            => $sd['isi'],
                    'tujuan'         => $sd['tujuan'] ?? null,
                    'asal'           => $sd['asal'] ?? null,
                    'tanggal_surat'  => $tanggal,
                    'tanggal_terima' => $sd['jenis'] == 'masuk' ? $tanggal->addDays(rand(1,3)) : null,
                    'status'         => $sd['status'],
                    'sifat'          => $sd['sifat'],
                    'dibuat_oleh'     => $staffUsers[$sd['user']]->id,
                    'disetujui_oleh'    => in_array($sd['status'], ['dikirim','diterima','diarsipkan']) ? $admin->id : null,
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 12. SKP DATA — Sasaran Kinerja Pegawai
        |--------------------------------------------------------------------------
        */
        $skpData = [
            ['user' => 0, 'sasaran' => 'Pengelolaan administrasi kepegawaian', 'indikator' => 'Jumlah berkas kepegawaian yang diproses tepat waktu', 'tgt_kuan' => 100, 'real_kuan' => 92, 'tgt_kual' => 85, 'real_kual' => 88, 'tgt_waktu' => 6, 'real_waktu' => 6, 'nilai' => 91.33, 'predikat' => 'sangat_baik', 'status' => 'disetujui'],
            ['user' => 1, 'sasaran' => 'Update data SIMPEG dan DAPODIK', 'indikator' => 'Persentase data pegawai yang terupdate', 'tgt_kuan' => 100, 'real_kuan' => 95, 'tgt_kual' => 90, 'real_kual' => 88, 'tgt_waktu' => 6, 'real_waktu' => 6, 'nilai' => 94.33, 'predikat' => 'sangat_baik', 'status' => 'disetujui'],
            ['user' => 2, 'sasaran' => 'Pemeliharaan kebersihan gedung sekolah', 'indikator' => 'Persentase ruangan yang bersih & rapi', 'tgt_kuan' => 50, 'real_kuan' => 48, 'tgt_kual' => 80, 'real_kual' => 82, 'tgt_waktu' => 6, 'real_waktu' => 6, 'nilai' => 88.27, 'predikat' => 'baik', 'status' => 'disetujui'],
            ['user' => 3, 'sasaran' => 'Pelayanan kebersihan area publik', 'indikator' => 'Jumlah area yang dipelihara per hari', 'tgt_kuan' => 30, 'real_kuan' => 28, 'tgt_kual' => 80, 'real_kual' => 78, 'tgt_waktu' => 6, 'real_waktu' => 6, 'nilai' => 85.56, 'predikat' => 'baik', 'status' => 'disetujui'],
            ['user' => 5, 'sasaran' => 'Penyusunan laporan keuangan BOS & RKAS', 'indikator' => 'Ketepatan waktu penyusunan laporan keuangan', 'tgt_kuan' => 12, 'real_kuan' => 12, 'tgt_kual' => 90, 'real_kual' => 92, 'tgt_waktu' => 6, 'real_waktu' => 5, 'nilai' => 97.41, 'predikat' => 'sangat_baik', 'status' => 'disetujui'],
            ['user' => 6, 'sasaran' => 'Pengelolaan surat masuk dan keluar', 'indikator' => 'Jumlah surat yang diproses dengan benar', 'tgt_kuan' => 200, 'real_kuan' => 185, 'tgt_kual' => 85, 'real_kual' => 87, 'tgt_waktu' => 6, 'real_waktu' => 6, 'nilai' => 89.08, 'predikat' => 'baik', 'status' => 'disetujui'],
            ['user' => 7, 'sasaran' => 'Pengarsipan dokumen surat', 'indikator' => 'Persentase surat yang terarsipkan digital', 'tgt_kuan' => 100, 'real_kuan' => 85, 'tgt_kual' => 80, 'real_kual' => 82, 'tgt_waktu' => 6, 'real_waktu' => 6, 'nilai' => 85.83, 'predikat' => 'baik', 'status' => 'draft'],
            ['user' => 9, 'sasaran' => 'Pengelolaan koleksi perpustakaan', 'indikator' => 'Jumlah transaksi peminjaman/pengembalian', 'tgt_kuan' => 500, 'real_kuan' => 480, 'tgt_kual' => 85, 'real_kual' => 90, 'tgt_waktu' => 6, 'real_waktu' => 6, 'nilai' => 90.53, 'predikat' => 'sangat_baik', 'status' => 'diajukan'],
            ['user' => 12, 'sasaran' => 'Pendataan dan pemeliharaan inventaris', 'indikator' => 'Persentase inventaris yang terdata', 'tgt_kuan' => 100, 'real_kuan' => 90, 'tgt_kual' => 80, 'real_kual' => 78, 'tgt_waktu' => 6, 'real_waktu' => 6, 'nilai' => 86.17, 'predikat' => 'baik', 'status' => 'disetujui'],
            ['user' => 14, 'sasaran' => 'Pengelolaan data kesiswaan & PPDB', 'indikator' => 'Persentase data siswa akurat & lengkap', 'tgt_kuan' => 960, 'real_kuan' => 945, 'tgt_kual' => 90, 'real_kual' => 88, 'tgt_waktu' => 6, 'real_waktu' => 6, 'nilai' => 92.74, 'predikat' => 'sangat_baik', 'status' => 'disetujui'],
            ['user' => 15, 'sasaran' => 'Pengelolaan dokumen kurikulum', 'indikator' => 'Kelengkapan administrasi kurikulum', 'tgt_kuan' => 20, 'real_kuan' => 18, 'tgt_kual' => 85, 'real_kual' => 83, 'tgt_waktu' => 6, 'real_waktu' => 6, 'nilai' => 87.65, 'predikat' => 'baik', 'status' => 'draft'],
        ];

        foreach ($skpData as $sd) {
            if (!isset($staffUsers[$sd['user']])) continue;
            Skp::updateOrCreate(
                ['pengguna_id' => $staffUsers[$sd['user']]->id, 'sasaran_kinerja' => $sd['sasaran']],
                [
                    'periode'            => 'Semester 1 2025/2026',
                    'tahun'              => 2026,
                    'indikator_kinerja'  => $sd['indikator'],
                    'target_kuantitas'   => $sd['tgt_kuan'],
                    'realisasi_kuantitas' => $sd['real_kuan'],
                    'target_kualitas'    => $sd['tgt_kual'],
                    'realisasi_kualitas' => $sd['real_kual'],
                    'target_waktu'       => $sd['tgt_waktu'],
                    'realisasi_waktu'    => $sd['real_waktu'],
                    'nilai_capaian'      => $sd['nilai'],
                    'predikat'           => $sd['predikat'],
                    'status'             => $sd['status'],
                    'disetujui_oleh'        => $sd['status'] === 'disetujui' ? $admin->id : null,
                    'disetujui_pada'        => $sd['status'] === 'disetujui' ? now()->subDays(rand(1, 30)) : null,
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 13. AKUN MAGANG
        |--------------------------------------------------------------------------
        */
        $magang = Pengguna::updateOrCreate(
            ['email' => 'magang@tu.test'],
            [
                'nama'                   => 'Andi Pratama',
                'password'               => Hash::make('password'),
                'peran'                  => 'magang',
                'jabatan'                => 'Staff Magang',
                'telepon'                => '081298765432',
                'alamat'                 => 'Jl. Kalimantan No. 10, Kel. Sumbersari, Kec. Sumbersari, Jember',
                'aktif'                  => true,
                'tanggal_lahir'          => '2002-08-15',
                'pembimbing_lapangan'    => 'Drs. Bambang Supriyanto, M.Pd.',
                'instansi_asal'          => 'Universitas Jember',
                'tanggal_mulai_magang'   => $today->copy()->subDays(30)->format('Y-m-d'),
                'tanggal_selesai_magang' => $today->copy()->addDays(60)->format('Y-m-d'),
            ]
        );

        // Sample Logbook Magang
        for ($i = 5; $i >= 1; $i--) {
            LogbookMagang::updateOrCreate(
                [
                    'pengguna_id' => $magang->id,
                    'tanggal'     => $today->copy()->subDays($i)->format('Y-m-d'),
                ],
                [
                    'jam_mulai'      => '08:00',
                    'jam_selesai'    => '16:00',
                    'kegiatan'       => "Kegiatan magang hari ke-" . (31 - $i) . ": membantu administrasi umum dan pendataan arsip.",
                    'hasil'          => 'Input data selesai dan arsip tertata rapi.',
                    'kendala'        => $i % 2 === 0 ? 'Tidak ada kendala.' : 'Sistem sempat lambat saat jam sibuk.',
                    'rencana_besok'  => 'Melanjutkan pendataan dan membantu persuratan.',
                    'status'         => $i > 2 ? 'final' : 'draft',
                ]
            );
        }

        // Sample Kegiatan Magang
        KegiatanMagang::updateOrCreate(
            ['pengguna_id' => $magang->id, 'judul' => 'Input Data Siswa Baru'],
            [
                'deskripsi'       => 'Membantu proses input data siswa baru ke sistem administrasi sekolah.',
                'tanggal_mulai'   => $today->copy()->subDays(10)->format('Y-m-d'),
                'tanggal_selesai' => $today->copy()->subDays(3)->format('Y-m-d'),
                'status'          => 'selesai',
                'prioritas'       => 'tinggi',
                'catatan'         => 'Data 120 siswa berhasil diinput.',
            ]
        );

        KegiatanMagang::updateOrCreate(
            ['pengguna_id' => $magang->id, 'judul' => 'Penataan Arsip Surat'],
            [
                'deskripsi'       => 'Menata dan mendigitalkan arsip surat masuk/keluar tahun 2024.',
                'tanggal_mulai'   => $today->copy()->subDays(5)->format('Y-m-d'),
                'tanggal_selesai' => $today->copy()->addDays(10)->format('Y-m-d'),
                'status'          => 'berlangsung',
                'prioritas'       => 'sedang',
                'catatan'         => null,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | OUTPUT
        |--------------------------------------------------------------------------
        */
        $this->command->info('');
        $this->command->info('=================================================');
        $this->command->info('  ADMIN SEEDER BERHASIL DIJALANKAN!');
        $this->command->info('=================================================');
        $this->command->info('');
        $this->command->info('  AKUN LOGIN (password: password)');
        $this->command->info('  ─────────────────────────────────');
        $this->command->info('  Admin (KaTU)        : admin@tu.test');
        $this->command->info('  Kepala Sekolah      : kepsek@tu.test');
        $this->command->info('  ─── IKI 1: KEPEGAWAIAN ───');
        $this->command->info('  Dwi Kriswahyudi     : dwi.kepegawaian@tu.test');
        $this->command->info('  Faizz Moch. N.A.    : faizz.kepegawaian@tu.test');
        $this->command->info('  ─── IKI 2: PRAMU BAKTI ───');
        $this->command->info('  Eko Bagus F.         : eko.pramubakti@tu.test');
        $this->command->info('  Marsis              : marsis.pramubakti@tu.test');
        $this->command->info('  Miftahul Ulum       : miftahul.pramubakti@tu.test');
        $this->command->info('  ─── IKI 3: KEUANGAN ───');
        $this->command->info('  Ike Wijayanti       : ike.keuangan@tu.test');
        $this->command->info('  ─── IKI 4: PERSURATAN ───');
        $this->command->info('  Aris Sugito         : aris.persuratan@tu.test');
        $this->command->info('  Ginabul Rahayu      : ginabul.persuratan@tu.test');
        $this->command->info('  Herman Budi S.      : herman.persuratan@tu.test');
        $this->command->info('  ─── IKI 5: PERPUSTAKAAN ───');
        $this->command->info('  Anggra Okta W.      : anggra.perpustakaan@tu.test');
        $this->command->info('  Bagus Pribadi       : bagus.perpustakaan@tu.test');
        $this->command->info('  Moh. Sutrisno       : sutrisno.perpustakaan@tu.test');
        $this->command->info('  ─── IKI 6: INVENTARIS ───');
        $this->command->info('  Fatkurahman         : fatkurahman.inventaris@tu.test');
        $this->command->info('  Imam Basori         : imam.inventaris@tu.test');
        $this->command->info('  ─── IKI 7: KESISWAAN/KURIKULUM ───');
        $this->command->info('  Bayu Kurniawan      : bayu.kesiswaan@tu.test');
        $this->command->info('  Wikana S.S.         : wikana.kesiswaan@tu.test');
        $this->command->info('  ─── STAFF UMUM ───');
        $this->command->info('  Siti Aminah         : siti.staff@tu.test');
        $this->command->info('  ─── MAGANG ───');
        $this->command->info('  Andi Pratama        : magang@tu.test');
        $this->command->info('');
        $this->command->info('  Ulang tahun hari ini (testing):');
        $this->command->info('  - Dwi Kriswahyudi (dwi.kepegawaian@tu.test)');
        $this->command->info('  - Herman Budi Santoso (herman.persuratan@tu.test)');
        $this->command->info('');
        $this->command->info('  DATA DUMMY:');
        $this->command->info('  1 admin, 1 kepsek, 17 staff (8 role), 1 magang,');
        $this->command->info('  ~320 absensi, 12 izin, 13 laporan, 11 SKP,');
        $this->command->info('  7 event, 70+ notifikasi, 12 dokumen, 8 surat,');
        $this->command->info('  5 logbook magang, 2 kegiatan magang');
        $this->command->info('=================================================');
    }
}
