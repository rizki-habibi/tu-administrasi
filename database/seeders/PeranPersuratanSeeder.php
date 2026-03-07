<?php

namespace Database\Seeders;

use App\Models\Kehadiran;
use App\Models\Dokumen;
use App\Models\PengajuanIzin;
use App\Models\Notifikasi;
use App\Models\Laporan;
use App\Models\Skp;
use App\Models\Surat;
use App\Models\Pengguna;
use App\Models\DokumenWord;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PeranPersuratanSeeder extends Seeder
{
    public function run(): void
    {
        $today = Carbon::today();
        $admin = Pengguna::where('email', 'admin@tu.test')->firstOrFail();
        $todayMonthDay = $today->format('m-d');

        /*
        |--------------------------------------------------------------------------
        | 1. AKUN PERSURATAN (IKI 4)
        |--------------------------------------------------------------------------
        */
        $aris = Pengguna::updateOrCreate(
            ['email' => 'aris.persuratan@tu.test'],
            [
                'nama'          => 'Aris Sugito',
                'password'      => Hash::make('password'),
                'peran'         => 'persuratan',
                'jabatan'       => 'Operator Layanan Operasional',
                'iki_pelaksana' => '4 PERSURATAN',
                'kode_depan'    => '14345',
                'telepon'       => '081298765007',
                'alamat'        => 'Jl. Bali No. 22, Jember',
                'aktif'         => true,
                'tanggal_lahir' => '1983-12-10',
            ]
        );

        $ginabul = Pengguna::updateOrCreate(
            ['email' => 'ginabul.persuratan@tu.test'],
            [
                'nama'          => 'Ginabul Rahayu',
                'password'      => Hash::make('password'),
                'peran'         => 'persuratan',
                'jabatan'       => 'Operator Layanan Operasional',
                'iki_pelaksana' => '4 PERSURATAN',
                'kode_depan'    => '14345',
                'telepon'       => '081298765008',
                'alamat'        => 'Jl. Flores No. 5, Jember',
                'aktif'         => true,
                'tanggal_lahir' => '1991-04-18',
            ]
        );

        $herman = Pengguna::updateOrCreate(
            ['email' => 'herman.persuratan@tu.test'],
            [
                'nama'          => 'Herman Budi Santoso',
                'password'      => Hash::make('password'),
                'peran'         => 'persuratan',
                'jabatan'       => 'Operator Layanan Operasional',
                'iki_pelaksana' => '4 PERSURATAN',
                'kode_depan'    => '14345',
                'telepon'       => '081298765009',
                'alamat'        => 'Jl. Lombok No. 14, Jember',
                'aktif'         => true,
                'tanggal_lahir' => '1980-' . $todayMonthDay,
            ]
        );

        $staffUsers = [$aris, $ginabul, $herman];

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
            ['pengguna_id' => $aris->id, 'tanggal_mulai' => $today->copy()->addDays(1)->format('Y-m-d')],
            ['jenis' => 'sakit', 'tanggal_selesai' => $today->copy()->addDays(2)->format('Y-m-d'), 'alasan' => 'Kecelakaan ringan, istirahat di rumah', 'status' => 'pending']
        );
        PengajuanIzin::updateOrCreate(
            ['pengguna_id' => $ginabul->id, 'tanggal_mulai' => $today->copy()->addDays(-3)->format('Y-m-d')],
            ['jenis' => 'cuti', 'tanggal_selesai' => $today->copy()->addDays(-1)->format('Y-m-d'), 'alasan' => 'Mudik ke kampung halaman', 'status' => 'approved', 'disetujui_oleh' => $admin->id, 'catatan_admin' => 'Disetujui oleh Kasubag TU']
        );

        /*
        |--------------------------------------------------------------------------
        | 4. LAPORAN
        |--------------------------------------------------------------------------
        */
        Laporan::updateOrCreate(
            ['pengguna_id' => $aris->id, 'judul' => 'Buku Agenda Surat Masuk Februari 2026'],
            ['deskripsi' => 'Rekapitulasi 47 surat masuk bulan Februari.', 'kategori' => 'surat_masuk', 'prioritas' => 'sedang', 'status' => 'completed']
        );
        Laporan::updateOrCreate(
            ['pengguna_id' => $ginabul->id, 'judul' => 'Buku Agenda Surat Keluar Februari 2026'],
            ['deskripsi' => 'Rekapitulasi 35 surat keluar bulan Februari.', 'kategori' => 'surat_keluar', 'prioritas' => 'sedang', 'status' => 'submitted']
        );

        /*
        |--------------------------------------------------------------------------
        | 5. SKP
        |--------------------------------------------------------------------------
        */
        Skp::updateOrCreate(
            ['pengguna_id' => $aris->id, 'sasaran_kinerja' => 'Pengelolaan surat masuk dan keluar'],
            ['periode' => 'Semester 1 2025/2026', 'tahun' => 2026, 'indikator_kinerja' => 'Jumlah surat yang diproses dengan benar', 'target_kuantitas' => 200, 'realisasi_kuantitas' => 185, 'target_kualitas' => 85, 'realisasi_kualitas' => 87, 'target_waktu' => 6, 'realisasi_waktu' => 6, 'nilai_capaian' => 89.08, 'predikat' => 'baik', 'status' => 'disetujui', 'disetujui_oleh' => $admin->id, 'disetujui_pada' => now()->subDays(rand(1, 30))]
        );
        Skp::updateOrCreate(
            ['pengguna_id' => $ginabul->id, 'sasaran_kinerja' => 'Pengarsipan dokumen surat'],
            ['periode' => 'Semester 1 2025/2026', 'tahun' => 2026, 'indikator_kinerja' => 'Persentase surat yang terarsipkan digital', 'target_kuantitas' => 100, 'realisasi_kuantitas' => 85, 'target_kualitas' => 80, 'realisasi_kualitas' => 82, 'target_waktu' => 6, 'realisasi_waktu' => 6, 'nilai_capaian' => 85.83, 'predikat' => 'baik', 'status' => 'draft']
        );

        /*
        |--------------------------------------------------------------------------
        | 6. DOKUMEN
        |--------------------------------------------------------------------------
        */
        Dokumen::updateOrCreate(
            ['judul' => 'Template Surat Keterangan Aktif'],
            ['deskripsi' => 'Template resmi surat.', 'kategori' => 'surat', 'path_file' => 'documents/template-surat-keterangan-aktif.pdf', 'nama_file' => 'template-surat-keterangan-aktif.pdf', 'tipe_file' => 'pdf', 'ukuran_file' => rand(102400, 5242880), 'diunggah_oleh' => $aris->id]
        );
        Dokumen::updateOrCreate(
            ['judul' => 'Arsip Surat Masuk Januari 2026'],
            ['deskripsi' => 'Scan digital surat masuk.', 'kategori' => 'surat', 'path_file' => 'documents/arsip-surat-masuk-januari-2026.pdf', 'nama_file' => 'arsip-surat-masuk-januari-2026.pdf', 'tipe_file' => 'pdf', 'ukuran_file' => rand(102400, 5242880), 'diunggah_oleh' => $ginabul->id]
        );

        /*
        |--------------------------------------------------------------------------
        | 7. SURAT
        |--------------------------------------------------------------------------
        */
        $tanggal1 = now()->subDays(rand(1, 30));
        Surat::updateOrCreate(
            ['perihal' => 'Permohonan Bantuan Dana Operasional'],
            ['nomor_surat' => Surat::generateNomor('keluar', 'dinas'), 'jenis' => 'keluar', 'kategori' => 'dinas', 'isi' => 'Permohonan bantuan dana operasional sekolah semester genap.', 'tujuan' => 'Dinas Pendidikan Kota', 'tanggal_surat' => $tanggal1, 'status' => 'dikirim', 'sifat' => 'penting', 'dibuat_oleh' => $aris->id, 'disetujui_oleh' => $admin->id]
        );

        $tanggal2 = now()->subDays(rand(1, 30));
        Surat::updateOrCreate(
            ['perihal' => 'Undangan Rapat Koordinasi Kepsek'],
            ['nomor_surat' => Surat::generateNomor('masuk', 'undangan'), 'jenis' => 'masuk', 'kategori' => 'undangan', 'isi' => 'Undangan rapat koordinasi kepala sekolah se-Kota.', 'asal' => 'Dinas Pendidikan Kota', 'tanggal_surat' => $tanggal2, 'tanggal_terima' => $tanggal2->copy()->addDays(rand(1, 3)), 'status' => 'diterima', 'sifat' => 'penting', 'dibuat_oleh' => $ginabul->id, 'disetujui_oleh' => $admin->id]
        );

        $tanggal3 = now()->subDays(rand(1, 30));
        Surat::updateOrCreate(
            ['perihal' => 'Edaran PPDB 2025/2026'],
            ['nomor_surat' => Surat::generateNomor('masuk', 'edaran'), 'jenis' => 'masuk', 'kategori' => 'edaran', 'isi' => 'Edaran pelaksanaan PPDB.', 'asal' => 'Dinas Pendidikan Provinsi', 'tanggal_surat' => $tanggal3, 'tanggal_terima' => $tanggal3->copy()->addDays(rand(1, 3)), 'status' => 'diterima', 'sifat' => 'segera', 'dibuat_oleh' => $herman->id, 'disetujui_oleh' => $admin->id]
        );

        /*
        |--------------------------------------------------------------------------
        | 8. DOKUMEN WORD AI (Persuratan)
        |--------------------------------------------------------------------------
        */
        DokumenWord::updateOrCreate(
            ['judul' => 'Surat Edaran Libur Hari Raya Nyepi', 'pengguna_id' => $aris->id],
            [
                'pengguna_id' => $aris->id,
                'kategori'    => 'surat',
                'konten'      => '<p>Disampaikan kepada seluruh warga SMA Negeri 2 Jember bahwa dalam rangka Hari Raya Nyepi Tahun Baru Saka 1948, sekolah diliburkan pada hari Jumat, 28 Maret 2026.</p><p>Kegiatan belajar mengajar kembali normal pada Senin, 31 Maret 2026.</p>',
                'prompt_ai'   => 'Buat surat edaran libur Nyepi 28 Maret 2026',
                'templat'     => 'surat_resmi',
                'status'      => 'final',
                'dibagikan'    => true,
            ]
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
        $this->command->info('  ✅ PERAN PERSURATAN (IKI 4)');
        $this->command->info('  ─────────────────────────────────────');
        $this->command->info('  Akun   : aris.persuratan@tu.test');
        $this->command->info('           ginabul.persuratan@tu.test');
        $this->command->info('           herman.persuratan@tu.test');
        $this->command->info('  Fitur  : Kehadiran 30 hari, 2 izin, 2 laporan, 2 SKP,');
        $this->command->info('           2 dokumen, 3 surat, 1 word AI, notifikasi');
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
            ['judul' => 'Pengajuan izin disetujui',                  'msg' => 'Pengajuan izin Anda telah disetujui oleh Kasubag TU.',       'jenis' => 'izin'],
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
