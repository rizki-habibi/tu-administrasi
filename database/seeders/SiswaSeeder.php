<?php

namespace Database\Seeders;

use App\Models\StudentRecord;
use App\Models\StudentAchievement;
use App\Models\StudentViolation;
use App\Models\User;
use Illuminate\Database\Seeder;

class SiswaSeeder extends Seeder
{
    public function run(): void
    {
        $creator = User::where('email', 'bayu.kesiswaan@tu.test')->firstOrFail();
        $creator2 = User::where('email', 'wikana.kesiswaan@tu.test')->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | 1. DATA SISWA
        |--------------------------------------------------------------------------
        */
        $students = [
            ['nis' => '12001', 'nisn' => '0045678901', 'nama' => 'Ahmad Fauzi Pratama',   'kelas' => 'X-1',    'jk' => 'L', 'agama' => 'Islam',    'lahir' => ['Jember', '2010-05-12'], 'ortu' => ['Budi Pratama', '081234001001']],
            ['nis' => '12002', 'nisn' => '0045678902', 'nama' => 'Siti Aisyah Rahmawati', 'kelas' => 'X-1',    'jk' => 'P', 'agama' => 'Islam',    'lahir' => ['Jember', '2010-08-23'], 'ortu' => ['H. Rahmad', '081234001002']],
            ['nis' => '12003', 'nisn' => '0045678903', 'nama' => 'Budi Setiawan',         'kelas' => 'X-2',    'jk' => 'L', 'agama' => 'Islam',    'lahir' => ['Lumajang', '2010-03-08'], 'ortu' => ['Sumarno', '081234001003']],
            ['nis' => '12004', 'nisn' => '0045678904', 'nama' => 'Dewi Anggraini',        'kelas' => 'X-2',    'jk' => 'P', 'agama' => 'Islam',    'lahir' => ['Jember', '2010-11-17'], 'ortu' => ['Sumardi', '081234001004']],
            ['nis' => '11001', 'nisn' => '0045678801', 'nama' => 'Rizky Aditya Putra',    'kelas' => 'XI IPA-1','jk' => 'L','agama' => 'Islam',    'lahir' => ['Bondowoso', '2009-01-20'],'ortu' => ['Agus Salim', '081234001005']],
            ['nis' => '11002', 'nisn' => '0045678802', 'nama' => 'Putri Nuraini',         'kelas' => 'XI IPA-1','jk' => 'P','agama' => 'Islam',    'lahir' => ['Jember', '2009-06-30'], 'ortu' => ['Nurdin', '081234001006']],
            ['nis' => '11003', 'nisn' => '0045678803', 'nama' => 'Kevin Christianto',     'kelas' => 'XI IPA-2','jk' => 'L','agama' => 'Kristen',  'lahir' => ['Surabaya', '2009-12-25'],'ortu' => ['Paulus Christianto', '081234001007']],
            ['nis' => '11004', 'nisn' => '0045678804', 'nama' => 'Dian Fitriani',         'kelas' => 'XI IPS-1','jk' => 'P','agama' => 'Islam',    'lahir' => ['Jember', '2009-09-14'], 'ortu' => ['Suherman', '081234001008']],
            ['nis' => '10001', 'nisn' => '0045678701', 'nama' => 'Fajar Ramadhan',        'kelas' => 'XII IPA-1','jk'=> 'L','agama' => 'Islam',    'lahir' => ['Situbondo', '2008-04-05'],'ortu' => ['H. Maulana', '081234001009']],
            ['nis' => '10002', 'nisn' => '0045678702', 'nama' => 'Ayu Lestari Wulandari', 'kelas' => 'XII IPA-1','jk'=> 'P','agama' => 'Hindu',    'lahir' => ['Banyuwangi', '2008-07-18'],'ortu' => ['I Made Lestari', '081234001010']],
            ['nis' => '10003', 'nisn' => '0045678703', 'nama' => 'Mohammad Ilham',        'kelas' => 'XII IPA-2','jk'=> 'L','agama' => 'Islam',    'lahir' => ['Jember', '2008-02-28'], 'ortu' => ['Abdul Ghofur', '081234001011']],
            ['nis' => '10004', 'nisn' => '0045678704', 'nama' => 'Rina Oktaviani',        'kelas' => 'XII IPS-1','jk'=> 'P','agama' => 'Islam',    'lahir' => ['Jember', '2008-10-10'], 'ortu' => ['Sugeng', '081234001012']],
        ];

        $createdStudents = [];
        foreach ($students as $s) {
            $createdStudents[$s['nis']] = StudentRecord::updateOrCreate(
                ['nis' => $s['nis']],
                [
                    'nisn'             => $s['nisn'],
                    'nama'             => $s['nama'],
                    'kelas'            => $s['kelas'],
                    'tahun_ajaran'     => '2025/2026',
                    'jenis_kelamin'    => $s['jk'],
                    'tempat_lahir'     => $s['lahir'][0],
                    'tanggal_lahir'    => $s['lahir'][1],
                    'agama'            => $s['agama'],
                    'alamat'           => 'Kab. Jember, Jawa Timur',
                    'nama_orang_tua'   => $s['ortu'][0],
                    'telepon_orang_tua'=> $s['ortu'][1],
                    'status'           => 'aktif',
                    'tanggal_masuk'    => substr($s['nis'], 0, 2) === '12' ? '2025-07-14' : (substr($s['nis'], 0, 2) === '11' ? '2024-07-15' : '2023-07-17'),
                    'dibuat_oleh'      => $creator->id,
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 2. PRESTASI SISWA
        |--------------------------------------------------------------------------
        */
        $achievements = [
            ['nis' => '10001', 'judul' => 'Juara 1 OSN Matematika Tingkat Kabupaten',  'tingkat' => 'kabupaten', 'jenis' => 'akademik',     'tanggal' => '2025-09-15', 'penyelenggara' => 'Dinas Pendidikan Kab. Jember', 'hasil' => 'Emas'],
            ['nis' => '11001', 'judul' => 'Juara 2 Lomba Debat Bahasa Inggris',        'tingkat' => 'provinsi',  'jenis' => 'akademik',     'tanggal' => '2025-11-20', 'penyelenggara' => 'Dinas Pendidikan Jawa Timur',  'hasil' => 'Perak'],
            ['nis' => '10002', 'judul' => 'Juara 1 Tari Tradisional FLS2N',            'tingkat' => 'provinsi',  'jenis' => 'non_akademik', 'tanggal' => '2025-10-08', 'penyelenggara' => 'Kemendikbud Jawa Timur',        'hasil' => 'Emas'],
            ['nis' => '12001', 'judul' => 'Juara 3 Pramuka Jambore Daerah',            'tingkat' => 'kabupaten', 'jenis' => 'non_akademik', 'tanggal' => '2026-01-12', 'penyelenggara' => 'Kwarcab Jember',                'hasil' => 'Perunggu'],
            ['nis' => '11003', 'judul' => 'Peserta OSN Informatika Tingkat Nasional',  'tingkat' => 'nasional',  'jenis' => 'akademik',     'tanggal' => '2025-08-25', 'penyelenggara' => 'Kemendikbud RI',                'hasil' => 'Partisipasi'],
        ];

        foreach ($achievements as $a) {
            $student = $createdStudents[$a['nis']] ?? null;
            if (!$student) continue;

            StudentAchievement::updateOrCreate(
                ['siswa_id' => $student->id, 'judul' => $a['judul']],
                [
                    'tingkat'       => $a['tingkat'],
                    'jenis'         => $a['jenis'],
                    'tanggal'       => $a['tanggal'],
                    'penyelenggara' => $a['penyelenggara'],
                    'hasil'         => $a['hasil'],
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 3. PELANGGARAN SISWA
        |--------------------------------------------------------------------------
        */
        $violations = [
            ['nis' => '12003', 'tanggal' => '2026-02-05', 'jenis' => 'ringan',  'deskripsi' => 'Tidak memakai seragam lengkap (tanpa dasi).',                    'tindakan' => 'Peringatan lisan dan catatan di buku pelanggaran.'],
            ['nis' => '11004', 'tanggal' => '2026-01-20', 'jenis' => 'sedang',  'deskripsi' => 'Terlambat masuk sekolah 3x dalam seminggu tanpa keterangan.',     'tindakan' => 'Panggilan orang tua dan surat peringatan 1.'],
            ['nis' => '12001', 'tanggal' => '2026-02-18', 'jenis' => 'ringan',  'deskripsi' => 'Membawa HP saat jam pelajaran berlangsung.',                      'tindakan' => 'HP disita selama 1 minggu, dikembalikan ke orang tua.'],
        ];

        foreach ($violations as $v) {
            $student = $createdStudents[$v['nis']] ?? null;
            if (!$student) continue;

            StudentViolation::updateOrCreate(
                ['siswa_id' => $student->id, 'tanggal' => $v['tanggal'], 'jenis' => $v['jenis']],
                [
                    'deskripsi'       => $v['deskripsi'],
                    'tindakan'        => $v['tindakan'],
                    'dilaporkan_oleh' => $creator2->id,
                ]
            );
        }
    }
}
