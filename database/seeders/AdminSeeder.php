<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Attendance;
use App\Models\AttendanceSetting;
use App\Models\LeaveRequest;
use App\Models\Report;
use App\Models\Event;
use App\Models\Notification;
use App\Models\Document;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | 1. ADMIN ACCOUNT
        |--------------------------------------------------------------------------
        */
        $admin = User::updateOrCreate(
            ['email' => 'admin@tu.test'],
            [
                'name' => 'Drs. Bambang Supriyanto, M.Pd.',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'position' => 'Kepala Tata Usaha',
                'phone' => '081234567890',
                'address' => 'Jl. Mastrip No. 45, Kel. Sumbersari, Kec. Sumbersari, Jember',
                'is_active' => true,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | 2. STAFF ACCOUNTS — Setiap bagian TU sekolah
        |--------------------------------------------------------------------------
        | Struktur organisasi TU SMA Negeri 2 Jember:
        |
        | ┌──────────────────────────────────────────────────┐
        | │  STAFF KEUANGAN           │  Pengelolaan dana BOS, gaji, SPJ,
        | │  Siti Nurhaliza, S.E.     │  anggaran sekolah, pembelian barang
        | │  Lestari Wulandari, S.E.  │
        | ├──────────────────────────────────────────────────┤
        | │  STAFF KEPEGAWAIAN        │  Data guru/pegawai, kenaikan pangkat,
        | │  Ahmad Fauzi, S.Pd.       │  DUK, SKP, sertifikasi, cuti
        | ├──────────────────────────────────────────────────┤
        | │  STAFF AKADEMIK           │  Kurikulum, jadwal pelajaran, rapor,
        | │  Dewi Rahmawati, S.Pd.    │  UTS/UAS, kalender pendidikan
        | ├──────────────────────────────────────────────────┤
        | │  STAFF SURAT MENYURAT     │  Surat masuk/keluar, arsip, disposisi,
        | │  Budi Santoso             │  legalisir, surat keterangan
        | │  Dimas Ardiansyah         │
        | ├──────────────────────────────────────────────────┤
        | │  STAFF KESISWAAN          │  Data siswa, PPDB, absensi siswa,
        | │  Rina Agustina, S.Pd.     │  tata tertib, bimbingan
        | ├──────────────────────────────────────────────────┤
        | │  STAFF INVENTARIS         │  Aset sekolah, pengadaan barang,
        | │  Hendra Prasetyo          │  pemeliharaan, penghapusan
        | ├──────────────────────────────────────────────────┤
        | │  STAFF IT & SISTEM INFO   │  Server, jaringan, website, DAPODIK,
        | │  Fajar Ramadhan, S.Kom.   │  aplikasi sekolah
        | ├──────────────────────────────────────────────────┤
        | │  STAFF UMUM & LOGISTIK    │  Kebersihan, keamanan, logistik,
        | │  Yuni Kartika             │  pemeliharaan gedung
        | ├──────────────────────────────────────────────────┤
        | │  STAFF PERPUSTAKAAN       │  Koleksi buku, peminjaman, katalog,
        | │  Ratna Dewi, A.Md.        │  literasi
        | ├──────────────────────────────────────────────────┤
        | │  STAFF HUMAS              │  Media sosial, website berita,
        | │  Yoga Pratama, S.I.Kom.   │  kerja sama, publikasi
        | ├──────────────────────────────────────────────────┤
        | │  STAFF ADMINISTRASI       │  Administrasi umum, pelayanan harian,
        | │  Staff TU Demo            │  koordinasi antar bagian
        | └──────────────────────────────────────────────────┘
        */

        $staffData = [
            [
                'name'     => 'Siti Nurhaliza, S.E.',
                'email'    => 'siti.keuangan@tu.test',
                'position' => 'Staff Keuangan',
                'phone'    => '081298765001',
                'address'  => 'Jl. Kalimantan No. 12, Jember',
            ],
            [
                'name'     => 'Ahmad Fauzi, S.Pd.',
                'email'    => 'ahmad.kepegawaian@tu.test',
                'position' => 'Staff Kepegawaian',
                'phone'    => '081298765002',
                'address'  => 'Jl. Sumatera No. 8, Jember',
            ],
            [
                'name'     => 'Dewi Rahmawati, S.Pd.',
                'email'    => 'dewi.akademik@tu.test',
                'position' => 'Staff Akademik & Kurikulum',
                'phone'    => '081298765003',
                'address'  => 'Jl. Jawa No. 25, Jember',
            ],
            [
                'name'     => 'Budi Santoso',
                'email'    => 'budi.surat@tu.test',
                'position' => 'Staff Surat Menyurat',
                'phone'    => '081298765004',
                'address'  => 'Jl. Sulawesi No. 3, Jember',
            ],
            [
                'name'     => 'Rina Agustina, S.Pd.',
                'email'    => 'rina.kesiswaan@tu.test',
                'position' => 'Staff Kesiswaan',
                'phone'    => '081298765005',
                'address'  => 'Jl. Borneo No. 17, Jember',
            ],
            [
                'name'     => 'Hendra Prasetyo',
                'email'    => 'hendra.inventaris@tu.test',
                'position' => 'Staff Inventaris & Aset',
                'phone'    => '081298765006',
                'address'  => 'Jl. Papua No. 9, Jember',
            ],
            [
                'name'     => 'Fajar Ramadhan, S.Kom.',
                'email'    => 'fajar.it@tu.test',
                'position' => 'Staff IT & Sistem Informasi',
                'phone'    => '081298765007',
                'address'  => 'Jl. Bali No. 22, Jember',
            ],
            [
                'name'     => 'Yuni Kartika',
                'email'    => 'yuni.umum@tu.test',
                'position' => 'Staff Umum & Logistik',
                'phone'    => '081298765008',
                'address'  => 'Jl. Flores No. 5, Jember',
            ],
            [
                'name'     => 'Ratna Dewi, A.Md.',
                'email'    => 'ratna.perpustakaan@tu.test',
                'position' => 'Staff Perpustakaan',
                'phone'    => '081298765009',
                'address'  => 'Jl. Lombok No. 14, Jember',
            ],
            [
                'name'     => 'Yoga Pratama, S.I.Kom.',
                'email'    => 'yoga.humas@tu.test',
                'position' => 'Staff Humas & Publikasi',
                'phone'    => '081298765010',
                'address'  => 'Jl. Timor No. 7, Jember',
            ],
            [
                'name'     => 'Lestari Wulandari, S.E.',
                'email'    => 'lestari.keuangan2@tu.test',
                'position' => 'Staff Keuangan',
                'phone'    => '081298765011',
                'address'  => 'Jl. Madura No. 33, Jember',
            ],
            [
                'name'     => 'Dimas Ardiansyah',
                'email'    => 'dimas.surat2@tu.test',
                'position' => 'Staff Surat Menyurat',
                'phone'    => '081298765012',
                'address'  => 'Jl. Nusa Tenggara No. 11, Jember',
            ],
            [
                'name'     => 'Staff TU Demo',
                'email'    => 'staff@tu.test',
                'position' => 'Staff Administrasi',
                'phone'    => '081298765432',
                'address'  => 'Jl. Gajah Mada No. 10, Jember',
            ],
        ];

        $staffUsers = [];
        foreach ($staffData as $data) {
            $staffUsers[] = User::updateOrCreate(
                ['email' => $data['email']],
                array_merge($data, [
                    'password' => Hash::make('password'),
                    'role'     => 'staff',
                    'is_active' => true,
                ])
            );
        }

        // Non-aktifkan 1 staff untuk variasi data
        $staffUsers[8]->update(['is_active' => false]);

        /*
        |--------------------------------------------------------------------------
        | 3. ATTENDANCE SETTINGS
        |--------------------------------------------------------------------------
        */
        AttendanceSetting::updateOrCreate(
            ['id' => 1],
            [
                'clock_in_time'          => '07:30',
                'clock_out_time'         => '16:00',
                'late_tolerance_minutes'  => 15,
                'office_latitude'         => -8.165908,
                'office_longitude'        => 113.706649,
                'max_distance_meters'     => 200,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | 4. ATTENDANCE DATA — 30 hari terakhir untuk setiap staff aktif
        |--------------------------------------------------------------------------
        */
        $statuses = ['hadir','hadir','hadir','hadir','hadir','terlambat','izin','sakit'];
        $today = Carbon::today();

        foreach ($staffUsers as $staff) {
            if (!$staff->is_active) continue;

            for ($i = 29; $i >= 0; $i--) {
                $date = $today->copy()->subDays($i);
                if ($date->isWeekend()) continue;

                $status   = $statuses[array_rand($statuses)];
                $clockIn  = null;
                $clockOut = null;
                $note     = null;

                switch ($status) {
                    case 'hadir':
                        $clockIn  = sprintf('07:%02d', rand(10, 29));
                        $clockOut = sprintf('%02d:%02d', rand(15, 16), rand(0, 30));
                        break;
                    case 'terlambat':
                        $clockIn  = sprintf('07:%02d', rand(46, 59));
                        $clockOut = sprintf('%02d:%02d', rand(15, 16), rand(0, 30));
                        $note = 'Terlambat: ' . collect(['macet di jalan', 'ban bocor', 'antar anak sekolah', 'hujan deras'])->random();
                        break;
                    case 'izin':
                        $note = collect(['Urusan keluarga', 'Mengurus dokumen pribadi', 'Keperluan mendadak'])->random();
                        break;
                    case 'sakit':
                        $note = collect(['Demam dan flu', 'Sakit perut', 'Periksa ke dokter', 'Masuk angin'])->random();
                        break;
                }

                Attendance::updateOrCreate(
                    ['user_id' => $staff->id, 'date' => $date->format('Y-m-d')],
                    [
                        'clock_in'      => $clockIn,
                        'clock_out'     => $clockOut,
                        'status'        => $status,
                        'latitude_in'   => $clockIn ? -8.165908 + (rand(-50, 50) / 100000) : null,
                        'longitude_in'  => $clockIn ? 113.706649 + (rand(-50, 50) / 100000) : null,
                        'latitude_out'  => $clockOut ? -8.165908 + (rand(-50, 50) / 100000) : null,
                        'longitude_out' => $clockOut ? 113.706649 + (rand(-50, 50) / 100000) : null,
                        'note'          => $note,
                    ]
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 5. LEAVE REQUESTS — Pengajuan izin dari berbagai staff
        |--------------------------------------------------------------------------
        */
        $leaveData = [
            ['user' => 0,  'type' => 'izin',       'start' => 5,   'end' => 5,   'reason' => 'Menghadiri pernikahan saudara di Surabaya',                       'status' => 'approved'],
            ['user' => 1,  'type' => 'sakit',       'start' => 3,   'end' => 4,   'reason' => 'Demam tinggi, surat dokter terlampir',                            'status' => 'approved'],
            ['user' => 2,  'type' => 'cuti',        'start' => 10,  'end' => 12,  'reason' => 'Cuti tahunan untuk liburan keluarga',                             'status' => 'approved'],
            ['user' => 3,  'type' => 'dinas_luar',  'start' => 7,   'end' => 8,   'reason' => 'Pelatihan pengarsipan digital di Dinas Pendidikan Kab. Jember',   'status' => 'approved'],
            ['user' => 4,  'type' => 'izin',        'start' => 2,   'end' => 2,   'reason' => 'Mengantar anak kontrol ke rumah sakit',                           'status' => 'pending'],
            ['user' => 5,  'type' => 'sakit',       'start' => 1,   'end' => 2,   'reason' => 'Kecelakaan ringan, istirahat di rumah',                           'status' => 'pending'],
            ['user' => 6,  'type' => 'cuti',        'start' => -3,  'end' => -1,  'reason' => 'Mudik ke kampung halaman menengok orang tua',                     'status' => 'approved'],
            ['user' => 7,  'type' => 'izin',        'start' => 4,   'end' => 4,   'reason' => 'Mengurus perpanjangan SIM',                                      'status' => 'rejected'],
            ['user' => 9,  'type' => 'dinas_luar',  'start' => 6,   'end' => 7,   'reason' => 'Workshop Media Sosial Sekolah di Malang',                        'status' => 'pending'],
            ['user' => 10, 'type' => 'sakit',       'start' => 0,   'end' => 1,   'reason' => 'Vertigo kambuh, butuh istirahat',                                'status' => 'pending'],
            ['user' => 11, 'type' => 'izin',        'start' => -5,  'end' => -5,  'reason' => 'Menghadiri wisuda anak',                                         'status' => 'approved'],
            ['user' => 12, 'type' => 'cuti',        'start' => 8,   'end' => 12,  'reason' => 'Cuti menemani istri melahirkan',                                 'status' => 'pending'],
        ];

        foreach ($leaveData as $ld) {
            LeaveRequest::updateOrCreate(
                ['user_id' => $staffUsers[$ld['user']]->id, 'start_date' => $today->copy()->addDays($ld['start'])->format('Y-m-d')],
                [
                    'type'        => $ld['type'],
                    'end_date'    => $today->copy()->addDays($ld['end'])->format('Y-m-d'),
                    'reason'      => $ld['reason'],
                    'status'      => $ld['status'],
                    'approved_by' => $ld['status'] !== 'pending' ? $admin->id : null,
                    'admin_note'  => match($ld['status']) {
                        'rejected' => 'Mohon ajukan di hari yang lebih tepat',
                        'approved' => 'Disetujui oleh Kasubag TU',
                        default    => null,
                    },
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 6. REPORTS — Laporan sesuai bidang masing-masing staff
        |--------------------------------------------------------------------------
        */
        $reportsData = [
            // ── Staff Keuangan (Siti & Lestari) ──
            ['user' => 0,  'title' => 'Laporan Realisasi Anggaran BOS Triwulan I 2026',
             'desc' => 'Laporan realisasi penggunaan dana BOS triwulan I tahun 2026. Meliputi belanja pegawai, belanja barang, dan belanja modal. Total realisasi Rp 245.000.000 dari anggaran Rp 300.000.000 (81.67%). Sisa anggaran dialokasikan untuk triwulan II.',
             'cat' => 'keuangan', 'priority' => 'tinggi', 'status' => 'submitted'],
            ['user' => 0,  'title' => 'Rekapitulasi Gaji Pegawai Februari 2026',
             'desc' => 'Rekap gaji seluruh pegawai (PNS & honorer) bulan Februari 2026. Total 45 pegawai: 30 PNS, 15 honorer. Rincian pembayaran: gaji pokok, tunjangan fungsional, tunjangan keluarga, potongan pajak. Total pembayaran Rp 189.500.000.',
             'cat' => 'keuangan', 'priority' => 'tinggi', 'status' => 'completed'],
            ['user' => 10, 'title' => 'SPJ Dana Komite Sekolah Semester Ganjil 2025/2026',
             'desc' => 'Surat pertanggungjawaban penggunaan dana komite untuk kegiatan semester ganjil. Meliputi: renovasi toilet Rp 25jt, pengadaan alat lab Rp 15jt, kegiatan kesiswaan Rp 10jt.',
             'cat' => 'keuangan', 'priority' => 'sedang', 'status' => 'reviewed'],

            // ── Staff Kepegawaian (Ahmad) ──
            ['user' => 1,  'title' => 'Data Usulan Kenaikan Pangkat Periode April 2026',
             'desc' => 'Daftar guru dan pegawai yang memenuhi syarat kenaikan pangkat periode April 2026. Total 8 orang: 5 guru (III/c ke III/d), 3 staff TU (II/d ke III/a). Berkas lengkap: PAK, DUPAK, SK terakhir, ijazah.',
             'cat' => 'lainnya', 'priority' => 'tinggi', 'status' => 'submitted'],
            ['user' => 1,  'title' => 'Rekap Sasaran Kinerja Pegawai (SKP) Semester Ganjil',
             'desc' => 'Rekapitulasi SKP seluruh guru dan staff semester ganjil 2025/2026. 42 dari 45 pegawai sudah mengumpulkan. 3 pegawai (cuti/sakit berkepanjangan) menyusul. Rata-rata capaian kinerja 86.5%.',
             'cat' => 'lainnya', 'priority' => 'sedang', 'status' => 'completed'],

            // ── Staff Akademik (Dewi) ──
            ['user' => 2,  'title' => 'Draft Jadwal UAS Semester Genap 2025/2026',
             'desc' => 'Jadwal Ujian Akhir Semester genap untuk kelas X, XI, XII. Periode pelaksanaan 15-26 Juni 2026. Mencakup 18 mata pelajaran dengan sistem pengawas silang dari guru lintas mapel.',
             'cat' => 'lainnya', 'priority' => 'tinggi', 'status' => 'draft'],
            ['user' => 2,  'title' => 'Rekap Nilai Rapor Semester Ganjil 2025/2026',
             'desc' => 'Rekapitulasi nilai rapor seluruh siswa kelas X-XII. Total 960 siswa. Rata-rata nilai: X (77.2), XI (78.8), XII (79.5). Mapel tertinggi: Matematika (82.1), terendah: Bahasa Jerman (71.3).',
             'cat' => 'lainnya', 'priority' => 'sedang', 'status' => 'completed'],

            // ── Staff Surat Menyurat (Budi & Dimas) ──
            ['user' => 3,  'title' => 'Buku Agenda Surat Masuk Februari 2026',
             'desc' => 'Rekapitulasi surat masuk bulan Februari 2026. Total 47 surat terdiri dari: 12 surat Dinas Pendidikan, 8 surat instansi pemerintah, 15 undangan, 12 surat lain-lain. Semua sudah didisposisi.',
             'cat' => 'surat_masuk', 'priority' => 'sedang', 'status' => 'completed'],
            ['user' => 3,  'title' => 'Buku Agenda Surat Keluar Februari 2026',
             'desc' => 'Rekapitulasi surat keluar bulan Februari 2026. Total 35 surat: 10 surat keterangan siswa, 8 surat tugas guru, 7 undangan rapat, 10 surat resmi lainnya.',
             'cat' => 'surat_keluar', 'priority' => 'sedang', 'status' => 'submitted'],
            ['user' => 11, 'title' => 'Pembuatan Surat Keterangan Pindah Siswa',
             'desc' => 'Pembuatan 3 surat keterangan pindah sekolah untuk siswa kelas XI IPA yang pindah domisili ke luar kota.',
             'cat' => 'surat_keluar', 'priority' => 'rendah', 'status' => 'completed'],

            // ── Staff Kesiswaan (Rina) ──
            ['user' => 4,  'title' => 'Progress Data PPDB Online 2026/2027',
             'desc' => 'Monitoring pendaftaran PPDB Online. Kuota 320 siswa, pendaftar 478 siswa. Jalur zonasi 80% (256 siswa), prestasi 15% (48 siswa), afirmasi 5% (16 siswa). Seleksi dimulai minggu depan.',
             'cat' => 'lainnya', 'priority' => 'tinggi', 'status' => 'submitted'],
            ['user' => 4,  'title' => 'Rekap Absensi Siswa Kelas XII Semester Ganjil',
             'desc' => 'Rekapitulasi kehadiran 288 siswa kelas XII (8 rombel). Rata-rata kehadiran 92.3%. 12 siswa (4.2%) dengan kehadiran di bawah 75% diberikan surat peringatan dan pembinaan oleh BK.',
             'cat' => 'kegiatan', 'priority' => 'sedang', 'status' => 'completed'],

            // ── Staff Inventaris (Hendra) ──
            ['user' => 5,  'title' => 'Laporan Stok Barang Inventaris Semester I 2026',
             'desc' => 'Audit inventaris semester 1: 45 unit komputer (3 rusak berat), 30 proyektor (2 perlu service), 960 set meja-kursi siswa (15 rusak ringan). Total aset tercatat Rp 2.1 miliar.',
             'cat' => 'inventaris', 'priority' => 'sedang', 'status' => 'submitted'],
            ['user' => 5,  'title' => 'Usulan Pengadaan Barang TA 2026',
             'desc' => 'Daftar kebutuhan pengadaan: 10 unit komputer lab, 5 proyektor interaktif, 50 set meja-kursi baru, 3 unit AC untuk ruang guru. Estimasi total anggaran Rp 385.000.000.',
             'cat' => 'inventaris', 'priority' => 'tinggi', 'status' => 'draft'],

            // ── Staff IT (Fajar) ──
            ['user' => 6,  'title' => 'Laporan Maintenance Server & Jaringan Februari 2026',
             'desc' => 'Maintenance bulanan: update antivirus server, backup database DAPODIK & e-Rapor, perbaikan switch lantai 2 gedung B, penggantian kabel UTP 30m di lab komputer 3. Server uptime 99.2%.',
             'cat' => 'lainnya', 'priority' => 'sedang', 'status' => 'completed'],
            ['user' => 6,  'title' => 'Proposal Upgrade Infrastruktur WiFi Sekolah',
             'desc' => 'Usulan upgrade dari 100Mbps ke 500Mbps dedicated. Penambahan 8 access point UniFi untuk coverage seluruh gedung A, B, C, aula, dan lapangan. Estimasi biaya Rp 45.000.000.',
             'cat' => 'lainnya', 'priority' => 'tinggi', 'status' => 'submitted'],

            // ── Staff Umum (Yuni) ──
            ['user' => 7,  'title' => 'Laporan Kebersihan & Pemeliharaan Gedung Feb 2026',
             'desc' => 'Laporan bulanan: 12 ruang kelas, 4 lab, perpustakaan, aula dicek. Perbaikan: cat dinding lorong lt.2 gedung B, perbaikan keran toilet gedung A, penggantian 8 lampu TL ruang kelas.',
             'cat' => 'kegiatan', 'priority' => 'rendah', 'status' => 'completed'],

            // ── Staff Humas (Yoga) ──
            ['user' => 9,  'title' => 'Laporan Publikasi Media Sosial Februari 2026',
             'desc' => 'Instagram @sman2jember: 15 postingan, reach 45K, followers naik 320 (+2.1%). Website: 12 artikel berita, 8.500 unique pageviews. YouTube: 3 video kegiatan, 2.100 total views.',
             'cat' => 'kegiatan', 'priority' => 'sedang', 'status' => 'completed'],
            ['user' => 9,  'title' => 'Proposal MoU Kerjasama Media Lokal Jember',
             'desc' => 'Proposal MoU dengan Radar Jember dan Radio Suara Jember untuk publikasi kegiatan sekolah secara berkala. Manfaat: peningkatan branding sekolah dan informasi PPDB.',
             'cat' => 'lainnya', 'priority' => 'sedang', 'status' => 'draft'],

            // ── Staff Administrasi Demo ──
            ['user' => 12, 'title' => 'Laporan Harian Tata Usaha 01 Maret 2026',
             'desc' => 'Rekap aktivitas TU: 5 surat masuk diproses, 3 surat keluar dikirim, 2 tamu institusi diterima, 1 rapat koordinasi dengan kepsek. Semua berjalan sesuai SOP.',
             'cat' => 'lainnya', 'priority' => 'rendah', 'status' => 'submitted'],
        ];

        foreach ($reportsData as $rd) {
            Report::updateOrCreate(
                ['user_id' => $staffUsers[$rd['user']]->id, 'title' => $rd['title']],
                [
                    'description' => $rd['desc'],
                    'category'    => $rd['cat'],
                    'priority'    => $rd['priority'],
                    'status'      => $rd['status'],
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 7. EVENTS — Agenda sekolah realistis (lalu & mendatang)
        |--------------------------------------------------------------------------
        */
        $eventsData = [
            // Past events
            ['title' => 'Penerimaan Rapor Semester Ganjil',               'desc' => 'Pembagian rapor semester ganjil 2025/2026 kepada orang tua/wali murid kelas X-XII.', 'date' => -30, 'start' => '08:00', 'end' => '12:00', 'loc' => 'Ruang Kelas masing-masing', 'type' => 'kegiatan', 'status' => 'completed'],
            ['title' => 'Rapat Pleno Dewan Guru',                         'desc' => 'Rapat dewan guru membahas evaluasi KBM semester ganjil dan persiapan semester genap.', 'date' => -25, 'start' => '09:00', 'end' => '14:00', 'loc' => 'Ruang Guru', 'type' => 'rapat', 'status' => 'completed'],
            ['title' => 'Rapat Koordinasi KBM Semester Genap',            'desc' => 'Koordinasi pembagian tugas mengajar, jadwal pelajaran, program remedial & pengayaan.', 'date' => -7, 'start' => '08:00', 'end' => '11:00', 'loc' => 'Ruang Rapat Utama', 'type' => 'rapat', 'status' => 'completed'],

            // Upcoming events
            ['title' => 'Upacara Bendera Hari Senin',                     'desc' => 'Upacara rutin hari Senin. Pembina: Kepala Sekolah. Petugas upacara: Kelas XI IPA 3.', 'date' => 1, 'start' => '07:00', 'end' => '07:45', 'loc' => 'Lapangan Utama', 'type' => 'upacara', 'status' => 'upcoming'],
            ['title' => 'Pelatihan Google Workspace for Education',       'desc' => 'Pelatihan pemanfaatan Google Classroom, Drive, Meet, Forms untuk guru dan staff TU. Narasumber: Tim IT Dinas Pendidikan Jember.', 'date' => 3, 'start' => '08:00', 'end' => '15:00', 'loc' => 'Lab Komputer 1', 'type' => 'pelatihan', 'status' => 'upcoming'],
            ['title' => 'Rapat Persiapan PPDB 2026/2027',                 'desc' => 'Membahas teknis PPDB: kuota per jalur, zona, alur pendaftaran online, jadwal, pembentukan panitia.', 'date' => 5, 'start' => '13:00', 'end' => '15:00', 'loc' => 'Ruang Rapat Utama', 'type' => 'rapat', 'status' => 'upcoming'],
            ['title' => 'Class Meeting & Pentas Seni',                    'desc' => 'Class meeting antar kelas: futsal, voli, cerdas cermat, lomba poster. Ditutup dengan pentas seni dan band.', 'date' => 8, 'start' => '07:00', 'end' => '14:00', 'loc' => 'Aula & Lapangan', 'type' => 'kegiatan', 'status' => 'upcoming'],
            ['title' => 'Rapat Komite Sekolah Triwulan I',                'desc' => 'Rapat rutin komite membahas penggunaan dana, evaluasi program, dan rencana pengembangan fasilitas.', 'date' => 12, 'start' => '09:00', 'end' => '12:00', 'loc' => 'Ruang Kepala Sekolah', 'type' => 'rapat', 'status' => 'upcoming'],
            ['title' => 'Peringatan Isra Mi\'raj Nabi Muhammad SAW',       'desc' => 'Ceramah agama, sholawat, doa bersama. Wajib dihadiri seluruh warga sekolah.', 'date' => 15, 'start' => '07:30', 'end' => '10:00', 'loc' => 'Masjid Sekolah', 'type' => 'kegiatan', 'status' => 'upcoming'],
            ['title' => 'Workshop Penulisan Karya Ilmiah Remaja (KIR)',    'desc' => 'Workshop KIR untuk siswa dan guru pembimbing. Materi: metode penelitian, teknik penulisan, presentasi ilmiah.', 'date' => 18, 'start' => '08:00', 'end' => '14:00', 'loc' => 'Perpustakaan', 'type' => 'pelatihan', 'status' => 'upcoming'],
            ['title' => 'Audit Internal Persiapan Akreditasi',            'desc' => 'Tim audit internal memeriksa kelengkapan: KTSP, RPP, sarana-prasarana, administrasi, dan dokumen mutu.', 'date' => 22, 'start' => '08:00', 'end' => '16:00', 'loc' => 'Seluruh Area Sekolah', 'type' => 'kegiatan', 'status' => 'upcoming'],
            ['title' => 'Ujian Tengah Semester Genap',                    'desc' => 'UTS semester genap kelas X & XI. 18 mata pelajaran, pengawas silang, jadwal 5 hari kerja.', 'date' => 30, 'start' => '07:30', 'end' => '12:00', 'loc' => 'Ruang Kelas', 'type' => 'kegiatan', 'status' => 'upcoming'],
        ];

        foreach ($eventsData as $ed) {
            Event::updateOrCreate(
                ['title' => $ed['title']],
                [
                    'created_by'  => $admin->id,
                    'description' => $ed['desc'],
                    'event_date'  => $today->copy()->addDays($ed['date'])->format('Y-m-d'),
                    'start_time'  => $ed['start'],
                    'end_time'    => $ed['end'],
                    'location'    => $ed['loc'],
                    'type'        => $ed['type'],
                    'status'      => $ed['status'],
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 8. NOTIFICATIONS — Berbagai jenis notifikasi untuk semua staff
        |--------------------------------------------------------------------------
        */
        $notifTemplates = [
            ['title' => 'Absensi berhasil tercatat',                    'msg' => 'Absensi masuk Anda hari ini berhasil tercatat pada pukul 07:25 WIB. Selamat bekerja dan semangat!',                                    'type' => 'kehadiran'],
            ['title' => 'Pengingat absen pulang',                       'msg' => 'Jangan lupa melakukan absen pulang sebelum meninggalkan area sekolah hari ini.',                                                          'type' => 'kehadiran'],
            ['title' => 'Pengajuan izin disetujui',                     'msg' => 'Pengajuan izin Anda telah disetujui oleh Kasubag TU. Pastikan tugas sudah didelegasikan.',                                                'type' => 'izin'],
            ['title' => 'Pengajuan cuti disetujui',                     'msg' => 'Permohonan cuti Anda telah disetujui. Selamat beristirahat dan semoga lekas kembali.',                                                    'type' => 'izin'],
            ['title' => 'Agenda baru: Pelatihan Google Workspace',      'msg' => 'Pelatihan Google Workspace for Education dijadwalkan di Lab Komputer 1. Harap membawa laptop masing-masing.',                              'type' => 'event'],
            ['title' => 'Pengingat: Rapat Persiapan PPDB',              'msg' => 'Rapat Persiapan PPDB 2026/2027 dijadwalkan minggu depan pukul 13:00 di Ruang Rapat Utama. Kehadiran wajib.',                               'type' => 'event'],
            ['title' => 'Agenda: Class Meeting & Pentas Seni',          'msg' => 'Class Meeting dan Pentas Seni akan dilaksanakan minggu depan. Staff TU panitia diharapkan hadir pukul 06:30.',                             'type' => 'event'],
            ['title' => 'Laporan Anda telah di-review',                  'msg' => 'Laporan yang Anda submit telah ditinjau oleh Kasubag TU. Silakan cek status laporan untuk melihat catatan.',                               'type' => 'laporan'],
            ['title' => 'Selamat datang di Sistem TU Administrasi!',    'msg' => 'Akun Anda sudah aktif. Silakan lengkapi profil dan gunakan fitur absensi, pengajuan izin, laporan, dan dokumen.',                          'type' => 'sistem'],
            ['title' => 'Pembaruan Sistem v2.0',                         'msg' => 'Sistem TU Administrasi diperbarui dengan fitur baru: manajemen dokumen, export data, tampilan modern. Selamat mencoba!',                  'type' => 'sistem'],
            ['title' => 'Jadwal Libur Nasional Maret 2026',             'msg' => 'Libur Maret 2026: Nyepi (19 Maret), Isra Mi\'raj (20 Maret). Staff tidak perlu absen pada tanggal tersebut.',                              'type' => 'pengumuman'],
            ['title' => 'Pengumuman: Seragam Baru Staff TU',            'msg' => 'Mulai April 2026 staff TU wajib menggunakan seragam baru. Pengukuran di ruang TU tanggal 10-12 Maret 2026.',                              'type' => 'pengumuman'],
        ];

        foreach ($staffUsers as $staff) {
            $count = rand(4, 6);
            $shuffled = collect($notifTemplates)->shuffle()->take($count);
            foreach ($shuffled as $idx => $n) {
                Notification::create([
                    'user_id'    => $staff->id,
                    'title'      => $n['title'],
                    'message'    => $n['msg'],
                    'type'       => $n['type'],
                    'is_read'    => $idx < 2,
                    'created_at' => now()->subHours(rand(1, 168)),
                ]);
            }
        }

        // Notif khusus admin
        Notification::create(['user_id' => $admin->id, 'title' => 'Pengajuan izin baru menunggu persetujuan', 'message' => 'Ada 5 pengajuan izin baru yang memerlukan persetujuan Anda. Silakan cek halaman Pengajuan Izin.', 'type' => 'izin', 'is_read' => false]);
        Notification::create(['user_id' => $admin->id, 'title' => 'Laporan baru perlu ditinjau', 'message' => 'Ada 3 laporan baru yang perlu ditinjau: Laporan Keuangan BOS, Rekap Kepegawaian, dan Audit Inventaris.', 'type' => 'laporan', 'is_read' => false]);

        /*
        |--------------------------------------------------------------------------
        | 9. DOCUMENTS — Dokumen sekolah per kategori
        |--------------------------------------------------------------------------
        */
        $documentsData = [
            // ── Kurikulum ──
            ['title' => 'KTSP SMA Negeri 2 Jember TA 2025/2026',          'desc' => 'Dokumen Kurikulum Tingkat Satuan Pendidikan: visi-misi, struktur kurikulum, kalender akademik, silabus, pedoman penilaian (Kurikulum Merdeka).', 'cat' => 'kurikulum', 'user' => 2],
            ['title' => 'Jadwal Pelajaran Semester Genap 2025/2026',       'desc' => 'Jadwal KBM kelas X, XI, XII semester genap. Termasuk jadwal guru, ruangan, dan jam mengajar 18 mapel.',                                            'cat' => 'kurikulum', 'user' => 2],
            ['title' => 'Program Tahunan & Semester 2025/2026',            'desc' => 'Prota dan Prosem seluruh mata pelajaran berdasarkan minggu efektif dan hari libur nasional.',                                                          'cat' => 'kurikulum', 'user' => 2],
            ['title' => 'Kalender Pendidikan TA 2025/2026',                'desc' => 'Kalender akademik resmi: jadwal masuk, libur, ujian, penerimaan rapor, PPDB, dan kegiatan sekolah.',                                                   'cat' => 'kurikulum', 'user' => 2],

            // ── Administrasi ──
            ['title' => 'SK Pembagian Tugas Mengajar Semester Genap',      'desc' => 'SK Kepala Sekolah tentang pembagian tugas mengajar guru semester genap 2025/2026. Total 45 guru, 18 mapel.',                                           'cat' => 'administrasi', 'user' => 12],
            ['title' => 'Profil Sekolah SMA Negeri 2 Jember 2026',         'desc' => 'Profil lengkap: sejarah, visi-misi, data guru-karyawan (45 orang), data siswa (960), sarana-prasarana, prestasi, program unggulan.',                    'cat' => 'administrasi', 'user' => 12],
            ['title' => 'SOP Tata Usaha SMA Negeri 2 Jember',              'desc' => 'Standar Operasional Prosedur layanan TU: surat keterangan, legalisir, pengarsipan, pelayanan tamu, dan pengelolaan dokumen.',                          'cat' => 'administrasi', 'user' => 12],

            // ── Keuangan ──
            ['title' => 'RKAS (Rencana Kegiatan dan Anggaran) 2026',       'desc' => 'Perencanaan anggaran tahun 2026: sumber dana BOS Rp 1.5M, komite Rp 800jt, hibah Rp 500jt. Total Rp 2.8 miliar.',                                    'cat' => 'keuangan', 'user' => 0],
            ['title' => 'Laporan BOS Triwulan IV 2025',                    'desc' => 'Pertanggungjawaban dana BOS triwulan IV 2025. Realisasi 97.5% dari total alokasi Rp 375.000.000.',                                                    'cat' => 'keuangan', 'user' => 0],
            ['title' => 'Daftar Gaji Pegawai Honorer 2026',                'desc' => 'Daftar 15 pegawai honorer beserta rincian gaji pokok, tunjangan transport, dan potongan BPJS.',                                                       'cat' => 'keuangan', 'user' => 10],

            // ── Kepegawaian ──
            ['title' => 'Daftar Urut Kepangkatan (DUK) 2026',             'desc' => 'DUK seluruh PNS per Januari 2026 diurutkan berdasarkan pangkat/golongan, TMT, dan masa kerja.',                                                        'cat' => 'kepegawaian', 'user' => 1],
            ['title' => 'Rekap Data Guru & Karyawan Februari 2026',         'desc' => 'Data lengkap: 30 guru PNS, 10 guru honorer, 5 staff TU PNS, 10 staff honorer. NIP, NUPTK, pangkat, sertifikasi.',                                     'cat' => 'kepegawaian', 'user' => 1],
            ['title' => 'Berkas Usul Kenaikan Pangkat April 2026',          'desc' => 'Kompilasi berkas 8 pegawai: PAK, DUPAK, SK terakhir, ijazah, sertifikat pendidik.',                                                                    'cat' => 'kepegawaian', 'user' => 1],

            // ── Kesiswaan ──
            ['title' => 'Data Pokok Siswa TA 2025/2026',                    'desc' => 'Data 960 siswa aktif: X (320), XI (320), XII (320). Termasuk data orang tua, alamat, dan riwayat akademik.',                                          'cat' => 'kesiswaan', 'user' => 4],
            ['title' => 'Tata Tertib Siswa SMAN 2 Jember',                  'desc' => 'Peraturan, tata tertib, sanksi, dan poin pelanggaran. Disetujui komite sekolah dan berlaku TA 2025/2026.',                                             'cat' => 'kesiswaan', 'user' => 4],
            ['title' => 'Rekap Data PPDB 2025/2026',                        'desc' => 'Rekapitulasi PPDB: 478 pendaftar, 320 diterima. Zonasi 256 (80%), prestasi 48 (15%), afirmasi 16 (5%).',                                              'cat' => 'kesiswaan', 'user' => 4],

            // ── Surat ──
            ['title' => 'Template Surat Keterangan Aktif Siswa',           'desc' => 'Template resmi surat keterangan masih aktif sebagai siswa SMAN 2 Jember. Kop surat dan nomor registrasi.',                                              'cat' => 'surat', 'user' => 3],
            ['title' => 'Arsip Surat Masuk Januari 2026',                   'desc' => 'Scan digital 52 surat masuk bulan Januari dari berbagai instansi, tersusun kronologis.',                                                                'cat' => 'surat', 'user' => 3],
            ['title' => 'Template Surat Tugas Dinas Luar',                  'desc' => 'Template surat tugas untuk guru/staff mengikuti kegiatan di luar sekolah (diklat, workshop, seminar).',                                                 'cat' => 'surat', 'user' => 11],

            // ── Inventaris ──
            ['title' => 'Buku Inventaris Barang Sekolah 2026',              'desc' => 'Daftar seluruh inventaris 2.500+ item: meja-kursi, komputer, proyektor, lab, alat olahraga. Total aset Rp 2.1M.',                                     'cat' => 'inventaris', 'user' => 5],
            ['title' => 'Berita Acara Penghapusan Barang 2025',            'desc' => 'BA penghapusan 35 item inventaris rusak berat dan tidak ekonomis diperbaiki. Disetujui kepala sekolah.',                                                 'cat' => 'inventaris', 'user' => 5],

            // ── Lainnya ──
            ['title' => 'Panduan Penggunaan Sistem TU Administrasi',       'desc' => 'Buku panduan lengkap untuk admin dan staff: absensi, pengajuan izin, dokumen, laporan, event, export.',                                                 'cat' => 'lainnya', 'user' => 6],
            ['title' => 'Dokumentasi Foto Kegiatan Semester Ganjil 2025',  'desc' => 'Kumpulan foto: upacara, class meeting, pameran seni, lomba akademik, studi tour, peringatan hari besar.',                                               'cat' => 'lainnya', 'user' => 9],
        ];

        foreach ($documentsData as $dd) {
            Document::updateOrCreate(
                ['title' => $dd['title']],
                [
                    'description'  => $dd['desc'],
                    'category'     => $dd['cat'],
                    'file_path'    => 'documents/' . \Illuminate\Support\Str::slug($dd['title']) . '.pdf',
                    'file_name'    => \Illuminate\Support\Str::slug($dd['title']) . '.pdf',
                    'file_type'    => 'pdf',
                    'file_size'    => rand(102400, 5242880), // 100KB - 5MB
                    'uploaded_by'  => $staffUsers[$dd['user']]->id,
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | SURAT (Official Letters) — 20 surat masuk & keluar
        |--------------------------------------------------------------------------
        */
        $suratData = [
            ['jenis'=>'keluar','kategori'=>'dinas','perihal'=>'Permohonan Bantuan Dana Operasional Sekolah Semester Genap','tujuan'=>'Dinas Pendidikan Kota','sifat'=>'penting','status'=>'dikirim','user'=>3,'isi'=>'Dengan hormat, bersama surat ini kami mengajukan permohonan bantuan dana operasional sekolah untuk semester genap tahun ajaran 2025/2026. Dana tersebut akan digunakan untuk pembiayaan kegiatan belajar mengajar, pemeliharaan sarana prasarana, dan kegiatan ekstrakurikuler siswa.'],
            ['jenis'=>'masuk','kategori'=>'undangan','perihal'=>'Undangan Rapat Koordinasi Kepala Sekolah se-Kota','asal'=>'Dinas Pendidikan Kota','sifat'=>'penting','status'=>'diterima','user'=>3,'isi'=>'Mengundang Bapak/Ibu Kepala Sekolah untuk menghadiri Rapat Koordinasi yang akan dilaksanakan pada hari Senin, 15 Juli 2025 pukul 09.00 WIB di Aula Dinas Pendidikan Kota.'],
            ['jenis'=>'keluar','kategori'=>'keterangan','perihal'=>'Surat Keterangan Aktif Bekerja a.n. Ahmad Fauzi, S.Pd.','tujuan'=>'Bank BRI Cabang Utama','sifat'=>'biasa','status'=>'dikirim','user'=>1,'isi'=>'Yang bertanda tangan di bawah ini, Kepala Tata Usaha SMA Negeri 2, menerangkan bahwa Ahmad Fauzi, S.Pd. adalah benar pegawai aktif pada SMA Negeri 2 sejak tahun 2018 dengan jabatan Staff Bagian Kepegawaian.'],
            ['jenis'=>'keluar','kategori'=>'tugas','perihal'=>'Surat Tugas Mengikuti Pelatihan Kurikulum Merdeka Belajar','tujuan'=>'Guru dan Staff SMA Negeri 2','sifat'=>'penting','status'=>'dikirim','user'=>2,'isi'=>'Menugaskan kepada guru dan staff yang namanya tercantum dalam lampiran untuk mengikuti Pelatihan Kurikulum Merdeka Belajar yang diselenggarakan oleh Kementerian Pendidikan pada tanggal 20-22 Juli 2025.'],
            ['jenis'=>'masuk','kategori'=>'edaran','perihal'=>'Edaran Tentang Pelaksanaan PPDB Tahun Ajaran 2025/2026','asal'=>'Dinas Pendidikan Provinsi','sifat'=>'segera','status'=>'diterima','user'=>3,'isi'=>'Sehubungan dengan pelaksanaan Penerimaan Peserta Didik Baru (PPDB) tahun ajaran 2025/2026, dengan ini disampaikan beberapa hal penting mengenai jadwal, mekanisme, dan persyaratan PPDB.'],
            ['jenis'=>'keluar','kategori'=>'pemberitahuan','perihal'=>'Pemberitahuan Jadwal UTS Semester Genap 2025','tujuan'=>'Orang Tua / Wali Murid','sifat'=>'biasa','status'=>'dikirim','user'=>2,'isi'=>'Dengan ini kami sampaikan bahwa Ujian Tengah Semester (UTS) Genap tahun ajaran 2024/2025 akan dilaksanakan pada tanggal 10-15 Maret 2025. Dimohon kerja sama orang tua untuk mempersiapkan putra/putri mengikuti ujian.'],
            ['jenis'=>'masuk','kategori'=>'dinas','perihal'=>'Persetujuan Anggaran Renovasi Laboratorium Komputer','asal'=>'Komite Sekolah','sifat'=>'penting','status'=>'diterima','user'=>0,'isi'=>'Berdasarkan rapat komite sekolah tanggal 5 Juni 2025, dengan ini disampaikan persetujuan anggaran renovasi Laboratorium Komputer sebesar Rp 75.000.000 yang bersumber dari dana komite dan BOS.'],
            ['jenis'=>'keluar','kategori'=>'keputusan','perihal'=>'SK Pembagian Tugas Mengajar Guru Semester Genap','tujuan'=>'Seluruh Guru SMA Negeri 2','sifat'=>'penting','status'=>'diarsipkan','user'=>2,'isi'=>'Memutuskan pembagian tugas mengajar guru untuk semester genap tahun ajaran 2024/2025 sebagaimana tercantum dalam lampiran surat keputusan ini.'],
            ['jenis'=>'masuk','kategori'=>'undangan','perihal'=>'Undangan Workshop Implementasi Kurikulum Merdeka','asal'=>'LPMP Provinsi','sifat'=>'biasa','status'=>'diarsipkan','user'=>3,'isi'=>'Mengundang perwakilan sekolah untuk menghadiri Workshop Implementasi Kurikulum Merdeka Belajar yang dilaksanakan selama 3 hari di Hotel Grand Ballroom.'],
            ['jenis'=>'keluar','kategori'=>'dinas','perihal'=>'Laporan Realisasi Anggaran BOS Triwulan II','tujuan'=>'Dinas Pendidikan Kota','sifat'=>'penting','status'=>'dikirim','user'=>0,'isi'=>'Bersama ini kami sampaikan Laporan Realisasi Penggunaan Dana Bantuan Operasional Sekolah (BOS) Triwulan II Tahun 2025 SMA Negeri 2.'],
            ['jenis'=>'keluar','kategori'=>'keterangan','perihal'=>'Surat Keterangan Lulus a.n. Siswa Kelas XII','tujuan'=>'Perguruan Tinggi','sifat'=>'biasa','status'=>'draft','user'=>4,'isi'=>'Menerangkan bahwa siswa yang bersangkutan telah menyelesaikan seluruh program pendidikan di SMA Negeri 2 dan dinyatakan LULUS.'],
            ['jenis'=>'masuk','kategori'=>'dinas','perihal'=>'Instruksi Pelaporan Data Pokok Pendidikan (Dapodik)','asal'=>'Kementerian Pendidikan','sifat'=>'segera','status'=>'diterima','user'=>6,'isi'=>'Menginstruksikan kepada seluruh satuan pendidikan untuk segera melakukan pemutakhiran data pada aplikasi Dapodik paling lambat tanggal 30 Juni 2025.'],
            ['jenis'=>'keluar','kategori'=>'edaran','perihal'=>'Edaran Internal: Disiplin Kehadiran Pegawai','tujuan'=>'Seluruh Staff TU SMA 2','sifat'=>'penting','status'=>'dikirim','user'=>1,'isi'=>'Menghimbau seluruh pegawai TU untuk mematuhi jam kerja yang berlaku. Absen masuk paling lambat pukul 07.15 WIB dan pulang tidak lebih awal dari pukul 15.30 WIB.'],
            ['jenis'=>'masuk','kategori'=>'pemberitahuan','perihal'=>'Pemberitahuan Jadwal Supervisi Akademik','asal'=>'Pengawas Sekolah','sifat'=>'biasa','status'=>'diterima','user'=>2,'isi'=>'Memberitahukan bahwa kegiatan supervisi akademik akan dilaksanakan pada minggu kedua bulan Agustus 2025.'],
            ['jenis'=>'keluar','kategori'=>'undangan','perihal'=>'Undangan Rapat Pleno Komite Sekolah','tujuan'=>'Anggota Komite Sekolah','sifat'=>'penting','status'=>'dikirim','user'=>0,'isi'=>'Mengundang seluruh anggota Komite Sekolah untuk menghadiri Rapat Pleno membahas program kerja dan anggaran tahun ajaran 2025/2026.'],
            ['jenis'=>'keluar','kategori'=>'tugas','perihal'=>'Surat Tugas Panitia PPDB Online 2025','tujuan'=>'Panitia PPDB','sifat'=>'penting','status'=>'dikirim','user'=>2,'isi'=>'Menugaskan kepada panitia PPDB yang tercantum untuk melaksanakan kegiatan Penerimaan Peserta Didik Baru secara online.'],
            ['jenis'=>'masuk','kategori'=>'dinas','perihal'=>'Petunjuk Teknis Penyusunan RKAS Tahun 2026','asal'=>'Dinas Pendidikan Provinsi','sifat'=>'penting','status'=>'diproses','user'=>0,'isi'=>'Petunjuk teknis penyusunan Rencana Kegiatan dan Anggaran Sekolah (RKAS) tahun 2026 sebagai pedoman bagi satuan pendidikan.'],
            ['jenis'=>'keluar','kategori'=>'pemberitahuan','perihal'=>'Pemberitahuan Libur Hari Raya dan Cuti Bersama','tujuan'=>'Seluruh Warga Sekolah','sifat'=>'biasa','status'=>'dikirim','user'=>7,'isi'=>'Memberitahukan bahwa libur Hari Raya Idul Fitri dan cuti bersama tahun 2025 dimulai tanggal 28 Maret s/d 7 April 2025.'],
            ['jenis'=>'masuk','kategori'=>'keputusan','perihal'=>'SK Akreditasi Sekolah Tahun 2025','asal'=>'BAN-S/M','sifat'=>'rahasia','status'=>'diarsipkan','user'=>2,'isi'=>'Berdasarkan hasil visitasi dan penilaian akreditasi, SMA Negeri 2 mendapatkan peringkat akreditasi A (Unggul) yang berlaku selama 5 tahun.'],
            ['jenis'=>'keluar','kategori'=>'dinas','perihal'=>'Pengiriman Berkas Usulan Kenaikan Pangkat Pegawai','tujuan'=>'BKD Kota','sifat'=>'penting','status'=>'diproses','user'=>1,'isi'=>'Bersama ini kami kirimkan berkas usulan kenaikan pangkat untuk 3 orang pegawai SMA Negeri 2 periode April 2025.'],
        ];

        foreach ($suratData as $idx => $sd) {
            $jenis = $sd['jenis'];
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
                    'tanggal_terima' => $jenis == 'masuk' ? $tanggal->addDays(rand(1,3)) : null,
                    'status'         => $sd['status'],
                    'sifat'          => $sd['sifat'],
                    'created_by'     => $staffUsers[$sd['user']]->id,
                    'approved_by'    => in_array($sd['status'], ['dikirim','diterima','diarsipkan']) ? $admin->id : null,
                    'catatan'        => $idx % 3 == 0 ? 'Sudah diverifikasi dan dicatat dalam buku agenda surat.' : null,
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | OUTPUT — Informasi akun untuk login
        |--------------------------------------------------------------------------
        */
        $this->command->info('');
        $this->command->info('=================================================');
        $this->command->info('  SEEDER BERHASIL DIJALANKAN!');
        $this->command->info('=================================================');
        $this->command->info('');
        $this->command->info('  AKUN LOGIN (password: password)');
        $this->command->info('  ─────────────────────────────────');
        $this->command->info('  Admin        : admin@tu.test');
        $this->command->info('  Keuangan     : siti.keuangan@tu.test');
        $this->command->info('  Kepegawaian  : ahmad.kepegawaian@tu.test');
        $this->command->info('  Akademik     : dewi.akademik@tu.test');
        $this->command->info('  Surat        : budi.surat@tu.test');
        $this->command->info('  Kesiswaan    : rina.kesiswaan@tu.test');
        $this->command->info('  Inventaris   : hendra.inventaris@tu.test');
        $this->command->info('  IT           : fajar.it@tu.test');
        $this->command->info('  Umum         : yuni.umum@tu.test');
        $this->command->info('  Perpustakaan : ratna.perpustakaan@tu.test');
        $this->command->info('  Humas        : yoga.humas@tu.test');
        $this->command->info('  Keuangan 2   : lestari.keuangan2@tu.test');
        $this->command->info('  Surat 2      : dimas.surat2@tu.test');
        $this->command->info('  Demo         : staff@tu.test');
        $this->command->info('');
        $this->command->info('  DATA DUMMY:');
        $this->command->info('  1 admin, 13 staff, ~300 absensi,');
        $this->command->info('  12 pengajuan izin, 22 laporan,');
        $this->command->info('  12 event, 60+ notifikasi, 24 dokumen,');
        $this->command->info('  20 surat (masuk & keluar)');
        $this->command->info('=================================================');
    }
}
