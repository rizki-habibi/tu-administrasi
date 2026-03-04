<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Skp;
use Illuminate\Database\Seeder;

class SkpSeeder extends Seeder
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
        | SKP DATA — Sasaran Kinerja Pegawai
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
            if (!isset($staff[$sd['user']])) continue;
            Skp::updateOrCreate(
                ['pengguna_id' => $staff[$sd['user']]->id, 'sasaran_kinerja' => $sd['sasaran']],
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
        | OUTPUT
        |--------------------------------------------------------------------------
        */
        $this->command->info('');
        $this->command->info('=================================================');
        $this->command->info('  SKP SEEDER BERHASIL!');
        $this->command->info('=================================================');
        $this->command->info('  11 data SKP (Sasaran Kinerja Pegawai)');
        $this->command->info('=================================================');
    }
}
