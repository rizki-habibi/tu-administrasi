<?php

namespace Database\Seeders;

use App\Models\Pengguna;
use App\Models\PengajuanIzin;
use App\Models\Laporan;
use App\Models\Acara;
use App\Models\Notifikasi;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AktivitasSeeder extends Seeder
{
    public function run(): void
    {
        $today = Carbon::today();

        // Look up users by email
        $admin  = Pengguna::where('email', 'admin@tu.test')->firstOrFail();
        $kepsek = Pengguna::where('email', 'kepsek@tu.test')->firstOrFail();

        $staffEmails = [
            'dwi.kepegawaian@tu.test',       // 0
            'faizz.kepegawaian@tu.test',      // 1
            'eko.pramubakti@tu.test',         // 2
            'marsis.pramubakti@tu.test',      // 3
            'miftahul.pramubakti@tu.test',    // 4
            'ike.keuangan@tu.test',           // 5
            'aris.persuratan@tu.test',        // 6
            'ginabul.persuratan@tu.test',     // 7
            'herman.persuratan@tu.test',      // 8
            'anggra.perpustakaan@tu.test',    // 9
            'bagus.perpustakaan@tu.test',     // 10
            'sutrisno.perpustakaan@tu.test',  // 11
            'fatkurahman.inventaris@tu.test', // 12
            'imam.inventaris@tu.test',        // 13
            'bayu.kesiswaan@tu.test',         // 14
            'wikana.kesiswaan@tu.test',       // 15
        ];

        $staffUsers = Pengguna::whereIn('email', $staffEmails)->get()->keyBy('email');

        // Map index to user for easy reference (same order as original AdminSeeder)
        $staff = [];
        foreach ($staffEmails as $idx => $email) {
            $staff[$idx] = $staffUsers->get($email);
        }

        /*
        |--------------------------------------------------------------------------
        | 1. LEAVE REQUESTS
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
            if (!isset($staff[$ld['user']])) continue;
            PengajuanIzin::updateOrCreate(
                ['pengguna_id' => $staff[$ld['user']]->id, 'tanggal_mulai' => $today->copy()->addDays($ld['start'])->format('Y-m-d')],
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
        | 2. REPORTS
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
            if (!isset($staff[$rd['user']])) continue;
            Laporan::updateOrCreate(
                ['pengguna_id' => $staff[$rd['user']]->id, 'judul' => $rd['judul']],
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
        | 3. EVENTS
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
        | 4. NOTIFICATIONS
        |--------------------------------------------------------------------------
        */
        $notifTemplates = [
            ['judul' => 'Absensi berhasil tercatat', 'msg' => 'Absensi masuk hari ini berhasil tercatat pukul 07:25 WIB.', 'jenis' => 'kehadiran'],
            ['judul' => 'Pengingat absen pulang', 'msg' => 'Jangan lupa absen pulang sebelum meninggalkan area sekolah.', 'jenis' => 'kehadiran'],
            ['judul' => 'Pengajuan izin disetujui', 'msg' => 'Pengajuan izin Anda telah disetujui oleh Kasubag TU.', 'jenis' => 'izin'],
            ['judul' => 'Agenda baru: Pelatihan Google Workspace', 'msg' => 'Pelatihan di Lab Komputer 1. Bawa laptop.', 'jenis' => 'event'],
            ['judul' => 'Laporan Anda telah di-review', 'msg' => 'Laporan Anda ditinjau. Cek status laporan.', 'jenis' => 'laporan'],
            ['judul' => 'Selamat datang di Sistem TU Administrasi!', 'msg' => 'Akun Anda sudah aktif. Lengkapi profil.', 'jenis' => 'sistem'],
            ['judul' => 'Pembaruan Sistem v3.0', 'msg' => 'Fitur baru: SKP, Word AI, lokasi detail kehadiran.', 'jenis' => 'sistem'],
        ];

        $totalNotif = 0;
        foreach ($staffUsers->values() as $staffUser) {
            $count = rand(3, 5);
            $shuffled = collect($notifTemplates)->shuffle()->take($count);
            foreach ($shuffled as $idx => $n) {
                Notifikasi::create([
                    'pengguna_id' => $staffUser->id, 'judul' => $n['judul'], 'pesan' => $n['msg'],
                    'jenis' => $n['jenis'], 'sudah_dibaca' => $idx < 2, 'created_at' => now()->subHours(rand(1, 168)),
                ]);
            }
            $totalNotif += $count;
        }

        foreach ([$admin, $kepsek] as $u) {
            Notifikasi::create(['pengguna_id' => $u->id, 'judul' => 'Pengajuan izin baru menunggu persetujuan', 'pesan' => 'Ada pengajuan izin baru yang memerlukan persetujuan.', 'jenis' => 'izin', 'sudah_dibaca' => false]);
            Notifikasi::create(['pengguna_id' => $u->id, 'judul' => 'Laporan baru perlu ditinjau', 'pesan' => 'Ada laporan baru yang perlu ditinjau.', 'jenis' => 'laporan', 'sudah_dibaca' => false]);
            $totalNotif += 2;
        }

        /*
        |--------------------------------------------------------------------------
        | OUTPUT
        |--------------------------------------------------------------------------
        */
        $this->command->info('');
        $this->command->info('=================================================');
        $this->command->info('  AKTIVITAS SEEDER BERHASIL!');
        $this->command->info('=================================================');
        $this->command->info("  12 izin/cuti, 13 laporan, 7 event, ~{$totalNotif} notifikasi");
        $this->command->info('=================================================');
    }
}
