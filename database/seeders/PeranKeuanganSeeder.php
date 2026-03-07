<?php

namespace Database\Seeders;

use App\Models\Kehadiran;
use App\Models\Anggaran;
use App\Models\Dokumen;
use App\Models\CatatanKeuangan;
use App\Models\PengajuanIzin;
use App\Models\Notifikasi;
use App\Models\Laporan;
use App\Models\Skp;
use App\Models\Surat;
use App\Models\Pengguna;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PeranKeuanganSeeder extends Seeder
{
    public function run(): void
    {
        $today = Carbon::today();
        $admin = Pengguna::where('email', 'admin@tu.test')->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | 1. AKUN KEUANGAN (IKI 3)
        |--------------------------------------------------------------------------
        */
        $ike = Pengguna::updateOrCreate(
            ['email' => 'ike.keuangan@tu.test'],
            [
                'nama'          => 'Ike Wijayanti',
                'password'      => Hash::make('password'),
                'peran'         => 'keuangan',
                'jabatan'       => 'Penata Layanan Operasional',
                'iki_pelaksana' => '3 KEUANGAN',
                'kode_depan'    => '14342',
                'telepon'       => '081298765006',
                'alamat'        => 'Jl. Papua No. 9, Jember',
                'aktif'         => true,
                'tanggal_lahir' => '1986-09-25',
            ]
        );

        $staffUsers = [$ike];

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
            ['pengguna_id' => $ike->id, 'tanggal_mulai' => $today->copy()->addDays(2)->format('Y-m-d')],
            ['jenis' => 'izin', 'tanggal_selesai' => $today->copy()->addDays(2)->format('Y-m-d'), 'alasan' => 'Mengantar anak kontrol ke rumah sakit', 'status' => 'pending']
        );

        /*
        |--------------------------------------------------------------------------
        | 4. LAPORAN
        |--------------------------------------------------------------------------
        */
        Laporan::updateOrCreate(
            ['pengguna_id' => $ike->id, 'judul' => 'Laporan Realisasi Anggaran BOS Triwulan I 2026'],
            ['deskripsi' => 'Realisasi dana BOS triwulan I. Total Rp 245jt dari Rp 300jt (81.67%).', 'kategori' => 'keuangan', 'prioritas' => 'tinggi', 'status' => 'submitted']
        );
        Laporan::updateOrCreate(
            ['pengguna_id' => $ike->id, 'judul' => 'Rekapitulasi Gaji Pegawai Februari 2026'],
            ['deskripsi' => 'Rekap gaji seluruh pegawai (PNS & honorer) Februari 2026.', 'kategori' => 'keuangan', 'prioritas' => 'tinggi', 'status' => 'completed']
        );

        /*
        |--------------------------------------------------------------------------
        | 5. SKP
        |--------------------------------------------------------------------------
        */
        Skp::updateOrCreate(
            ['pengguna_id' => $ike->id, 'sasaran_kinerja' => 'Penyusunan laporan keuangan BOS & RKAS'],
            ['periode' => 'Semester 1 2025/2026', 'tahun' => 2026, 'indikator_kinerja' => 'Ketepatan waktu penyusunan laporan keuangan', 'target_kuantitas' => 12, 'realisasi_kuantitas' => 12, 'target_kualitas' => 90, 'realisasi_kualitas' => 92, 'target_waktu' => 6, 'realisasi_waktu' => 5, 'nilai_capaian' => 97.41, 'predikat' => 'sangat_baik', 'status' => 'disetujui', 'disetujui_oleh' => $admin->id, 'disetujui_pada' => now()->subDays(rand(1, 30))]
        );

        /*
        |--------------------------------------------------------------------------
        | 6. ANGGARAN (Budget)
        |--------------------------------------------------------------------------
        */
        $anggaranData = [
            ['nama_anggaran' => 'Dana BOS Reguler 2026',    'tahun_anggaran' => '2026', 'sumber_dana' => 'BOS Reguler',    'total_anggaran' => 1_200_000_000, 'terpakai' => 450_000_000,   'status' => 'aktif',   'keterangan' => 'Anggaran BOS reguler TA 2025/2026 dari Kemendikbud.'],
            ['nama_anggaran' => 'Dana BOS Kinerja 2026',    'tahun_anggaran' => '2026', 'sumber_dana' => 'BOS Kinerja',    'total_anggaran' => 300_000_000,   'terpakai' => 75_000_000,    'status' => 'aktif',   'keterangan' => 'Dana BOS kinerja berdasarkan capaian mutu sekolah.'],
            ['nama_anggaran' => 'Dana Komite Sekolah 2026', 'tahun_anggaran' => '2026', 'sumber_dana' => 'Komite Sekolah', 'total_anggaran' => 500_000_000,   'terpakai' => 120_000_000,   'status' => 'aktif',   'keterangan' => 'Sumbangan sukarela orang tua siswa melalui komite.'],
            ['nama_anggaran' => 'Dana BOS Reguler 2025',    'tahun_anggaran' => '2025', 'sumber_dana' => 'BOS Reguler',    'total_anggaran' => 1_150_000_000, 'terpakai' => 1_140_000_000, 'status' => 'selesai', 'keterangan' => 'Anggaran BOS tahun sebelumnya, hampir terserap penuh.'],
        ];

        foreach ($anggaranData as $a) {
            Anggaran::updateOrCreate(
                ['nama_anggaran' => $a['nama_anggaran'], 'tahun_anggaran' => $a['tahun_anggaran']],
                array_merge($a, ['dibuat_oleh' => $ike->id])
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 7. CATATAN KEUANGAN (Finance Records)
        |--------------------------------------------------------------------------
        */
        $records = [
            ['kode' => 'TRX-2026-001', 'jenis' => 'pemasukan',   'kategori' => 'bos_reguler',  'uraian' => 'Penerimaan BOS Reguler Triwulan I',   'jumlah' => 300_000_000, 'tgl' => '2026-01-15', 'status' => 'diverifikasi'],
            ['kode' => 'TRX-2026-002', 'jenis' => 'pemasukan',   'kategori' => 'bos_kinerja',  'uraian' => 'Penerimaan BOS Kinerja Triwulan I',   'jumlah' => 75_000_000,  'tgl' => '2026-01-20', 'status' => 'diverifikasi'],
            ['kode' => 'TRX-2026-003', 'jenis' => 'pemasukan',   'kategori' => 'komite',       'uraian' => 'Iuran Komite Semester Genap',         'jumlah' => 120_000_000, 'tgl' => '2026-02-01', 'status' => 'diverifikasi'],
            ['kode' => 'TRX-2026-004', 'jenis' => 'pengeluaran', 'kategori' => 'gaji',         'uraian' => 'Honor GTT dan PTT Januari 2026',      'jumlah' => 85_000_000,  'tgl' => '2026-01-25', 'status' => 'diverifikasi'],
            ['kode' => 'TRX-2026-005', 'jenis' => 'pengeluaran', 'kategori' => 'operasional',  'uraian' => 'Pembayaran Listrik & Air Januari',    'jumlah' => 12_500_000,  'tgl' => '2026-01-28', 'status' => 'diverifikasi'],
            ['kode' => 'TRX-2026-006', 'jenis' => 'pengeluaran', 'kategori' => 'operasional',  'uraian' => 'ATK dan Perlengkapan Kantor',         'jumlah' => 8_750_000,   'tgl' => '2026-02-05', 'status' => 'diverifikasi'],
            ['kode' => 'TRX-2026-007', 'jenis' => 'pengeluaran', 'kategori' => 'kegiatan',     'uraian' => 'Biaya Class Meeting Semester Ganjil', 'jumlah' => 15_000_000,  'tgl' => '2026-01-10', 'status' => 'diverifikasi'],
            ['kode' => 'TRX-2026-008', 'jenis' => 'pengeluaran', 'kategori' => 'pemeliharaan', 'uraian' => 'Perbaikan AC Ruang Guru',             'jumlah' => 4_500_000,   'tgl' => '2026-02-12', 'status' => 'draft'],
            ['kode' => 'TRX-2026-009', 'jenis' => 'pengeluaran', 'kategori' => 'gaji',         'uraian' => 'Honor GTT dan PTT Februari 2026',     'jumlah' => 85_000_000,  'tgl' => '2026-02-25', 'status' => 'diverifikasi'],
            ['kode' => 'TRX-2026-010', 'jenis' => 'pengeluaran', 'kategori' => 'operasional',  'uraian' => 'Pembayaran Listrik & Air Februari',   'jumlah' => 11_800_000,  'tgl' => '2026-02-28', 'status' => 'draft'],
        ];

        foreach ($records as $r) {
            CatatanKeuangan::updateOrCreate(
                ['kode_transaksi' => $r['kode']],
                [
                    'jenis'             => $r['jenis'],
                    'kategori'          => $r['kategori'],
                    'uraian'            => $r['uraian'],
                    'jumlah'            => $r['jumlah'],
                    'tanggal'           => $r['tgl'],
                    'status'            => $r['status'],
                    'keterangan'        => null,
                    'dibuat_oleh'       => $ike->id,
                    'diverifikasi_oleh' => $r['status'] === 'diverifikasi' ? $admin->id : null,
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 8. DOKUMEN
        |--------------------------------------------------------------------------
        */
        Dokumen::updateOrCreate(
            ['judul' => 'RKAS 2026'],
            ['deskripsi' => 'Perencanaan anggaran.', 'kategori' => 'keuangan', 'path_file' => 'documents/rkas-2026.pdf', 'nama_file' => 'rkas-2026.pdf', 'tipe_file' => 'pdf', 'ukuran_file' => rand(102400, 5242880), 'diunggah_oleh' => $ike->id]
        );
        Dokumen::updateOrCreate(
            ['judul' => 'Laporan BOS Triwulan IV 2025'],
            ['deskripsi' => 'Pertanggungjawaban dana BOS.', 'kategori' => 'keuangan', 'path_file' => 'documents/laporan-bos-triwulan-iv-2025.pdf', 'nama_file' => 'laporan-bos-triwulan-iv-2025.pdf', 'tipe_file' => 'pdf', 'ukuran_file' => rand(102400, 5242880), 'diunggah_oleh' => $ike->id]
        );

        /*
        |--------------------------------------------------------------------------
        | 9. SURAT (Keuangan)
        |--------------------------------------------------------------------------
        */
        $tanggal1 = now()->subDays(rand(1, 30));
        Surat::updateOrCreate(
            ['perihal' => 'Laporan Realisasi BOS Triwulan II'],
            [
                'nomor_surat'   => Surat::generateNomor('keluar', 'dinas'),
                'jenis'         => 'keluar',
                'kategori'      => 'dinas',
                'isi'           => 'Laporan realisasi penggunaan dana BOS.',
                'tujuan'        => 'Dinas Pendidikan',
                'tanggal_surat' => $tanggal1,
                'status'        => 'dikirim',
                'sifat'         => 'penting',
                'dibuat_oleh'   => $ike->id,
                'disetujui_oleh' => $admin->id,
            ]
        );

        $tanggal2 = now()->subDays(rand(1, 30));
        Surat::updateOrCreate(
            ['perihal' => 'Petunjuk Teknis RKAS 2026'],
            [
                'nomor_surat'    => Surat::generateNomor('masuk', 'dinas'),
                'jenis'          => 'masuk',
                'kategori'       => 'dinas',
                'isi'            => 'Juknis penyusunan RKAS.',
                'asal'           => 'Dinas Pendidikan Provinsi',
                'tanggal_surat'  => $tanggal2,
                'tanggal_terima' => $tanggal2->copy()->addDays(rand(1, 3)),
                'status'         => 'diproses',
                'sifat'          => 'penting',
                'dibuat_oleh'    => $ike->id,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | 10. NOTIFIKASI
        |--------------------------------------------------------------------------
        */
        $this->seedNotifikasi($staffUsers);

        /*
        |--------------------------------------------------------------------------
        | OUTPUT
        |--------------------------------------------------------------------------
        */
        $this->command->info('');
        $this->command->info('  ✅ PERAN KEUANGAN (IKI 3)');
        $this->command->info('  ─────────────────────────────────────');
        $this->command->info('  Akun   : ike.keuangan@tu.test');
        $this->command->info('  Fitur  : Kehadiran 30 hari, 1 izin, 2 laporan, 1 SKP,');
        $this->command->info('           4 anggaran, 10 catatan keuangan, 2 dokumen,');
        $this->command->info('           2 surat, notifikasi');
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
                    case 'izin':      $note = collect(['Urusan keluarga','Mengurus dokumen pribadi','Keperluan mendadak'])->random(); break;
                    case 'sakit':     $note = collect(['Demam dan flu','Sakit perut','Periksa ke dokter','Masuk angin'])->random(); break;
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
            ['judul' => 'Laporan Anda telah di-review',              'msg' => 'Laporan keuangan Anda telah ditinjau. Cek status laporan.',  'jenis' => 'laporan'],
            ['judul' => 'Agenda baru: Pelatihan Google Workspace',   'msg' => 'Pelatihan di Lab Komputer 1. Bawa laptop.',                  'jenis' => 'event'],
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
