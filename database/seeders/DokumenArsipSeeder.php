<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Document;
use App\Models\Surat;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DokumenArsipSeeder extends Seeder
{
    public function run(): void
    {
        // Look up users by email
        $admin = User::where('email', 'admin@tu.test')->firstOrFail();

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

        $staffUsers = User::whereIn('email', $staffEmails)->get()->keyBy('email');

        // Map index to user for easy reference (same order as original AdminSeeder)
        $staff = [];
        foreach ($staffEmails as $idx => $email) {
            $staff[$idx] = $staffUsers->get($email);
        }

        /*
        |--------------------------------------------------------------------------
        | 1. DOCUMENTS
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
            if (!isset($staff[$dd['user']])) continue;
            Document::updateOrCreate(
                ['judul' => $dd['judul']],
                [
                    'deskripsi'  => $dd['desc'],
                    'kategori'     => $dd['cat'],
                    'path_file'    => 'documents/' . Str::slug($dd['judul']) . '.pdf',
                    'nama_file'    => Str::slug($dd['judul']) . '.pdf',
                    'tipe_file'    => 'pdf',
                    'ukuran_file'    => rand(102400, 5242880),
                    'diunggah_oleh'  => $staff[$dd['user']]->id,
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 2. SURAT
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
            if (!isset($staff[$sd['user']])) continue;
            $tanggal = now()->subDays(rand(1, 90));
            Surat::updateOrCreate(
                ['perihal' => $sd['perihal']],
                [
                    'nomor_surat'    => Surat::generateNomor($sd['jenis'], $sd['kategori']),
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
                    'dibuat_oleh'     => $staff[$sd['user']]->id,
                    'disetujui_oleh'    => in_array($sd['status'], ['dikirim','diterima','diarsipkan']) ? $admin->id : null,
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | OUTPUT
        |--------------------------------------------------------------------------
        */
        $this->command->info('');
        $this->command->info('=================================================');
        $this->command->info('  DOKUMEN & ARSIP SEEDER BERHASIL!');
        $this->command->info('=================================================');
        $this->command->info('  12 dokumen, 8 surat (masuk & keluar)');
        $this->command->info('=================================================');
    }
}
