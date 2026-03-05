<?php

namespace Database\Seeders;

use App\Models\DokumenKurikulum;
use App\Models\Pengguna;
use Illuminate\Database\Seeder;

class KurikulumSeeder extends Seeder
{
    public function run(): void
    {
        $kurStaff1 = Pengguna::where('email', 'bayu.kesiswaan@tu.test')->firstOrFail();
        $kurStaff2 = Pengguna::where('email', 'wikana.kesiswaan@tu.test')->firstOrFail();

        $docs = [
            ['judul' => 'KTSP SMA Negeri 2 Jember TA 2025/2026',               'jenis' => 'ktsp',                'ta' => '2025/2026', 'smt' => 'genap',   'mapel' => null,                  'kelas' => null,    'status' => 'final',    'user' => $kurStaff1],
            ['judul' => 'Silabus Matematika Kelas X Fase E',                    'jenis' => 'silabus',             'ta' => '2025/2026', 'smt' => 'genap',   'mapel' => 'Matematika',          'kelas' => 'X',     'status' => 'final',    'user' => $kurStaff2],
            ['judul' => 'Silabus Bahasa Indonesia Kelas XI Fase F',             'jenis' => 'silabus',             'ta' => '2025/2026', 'smt' => 'genap',   'mapel' => 'Bahasa Indonesia',    'kelas' => 'XI',    'status' => 'final',    'user' => $kurStaff2],
            ['judul' => 'RPP Fisika Kelas XII — Gelombang Elektromagnetik',     'jenis' => 'rpp',                 'ta' => '2025/2026', 'smt' => 'genap',   'mapel' => 'Fisika',              'kelas' => 'XII',   'status' => 'final',    'user' => $kurStaff1],
            ['judul' => 'Modul Ajar Informatika Kelas X — Pemrograman Dasar',   'jenis' => 'modul_ajar',          'ta' => '2025/2026', 'smt' => 'genap',   'mapel' => 'Informatika',         'kelas' => 'X',     'status' => 'draft',    'user' => $kurStaff2],
            ['judul' => 'ATP Bahasa Inggris Kelas XI Fase F',                   'jenis' => 'atp',                 'ta' => '2025/2026', 'smt' => 'genap',   'mapel' => 'Bahasa Inggris',      'kelas' => 'XI',    'status' => 'final',    'user' => $kurStaff1],
            ['judul' => 'Jadwal Pelajaran Semester Genap 2025/2026',            'jenis' => 'jadwal',              'ta' => '2025/2026', 'smt' => 'genap',   'mapel' => null,                  'kelas' => null,    'status' => 'final',    'user' => $kurStaff2],
            ['judul' => 'Kalender Pendidikan TA 2025/2026',                     'jenis' => 'kalender_pendidikan', 'ta' => '2025/2026', 'smt' => null,      'mapel' => null,                  'kelas' => null,    'status' => 'final',    'user' => $kurStaff1],
            ['judul' => 'RPP Biologi Kelas X — Keanekaragaman Hayati',          'jenis' => 'rpp',                 'ta' => '2025/2026', 'smt' => 'genap',   'mapel' => 'Biologi',             'kelas' => 'X',     'status' => 'draft',    'user' => $kurStaff2],
            ['judul' => 'Program Tahunan (Prota) Kimia Kelas XI',              'jenis' => 'prota',               'ta' => '2025/2026', 'smt' => null,      'mapel' => 'Kimia',               'kelas' => 'XI',    'status' => 'final',    'user' => $kurStaff1],
        ];

        foreach ($docs as $d) {
            DokumenKurikulum::updateOrCreate(
                ['judul' => $d['judul']],
                [
                    'deskripsi'      => 'Dokumen kurikulum ' . strtolower($d['jenis']) . ' untuk SMA Negeri 2 Jember.',
                    'jenis'          => $d['jenis'],
                    'tahun_ajaran'   => $d['ta'],
                    'semester'       => $d['smt'],
                    'mata_pelajaran' => $d['mapel'],
                    'tingkat_kelas'  => $d['kelas'],
                    'status'         => $d['status'],
                    'diunggah_oleh'  => $d['user']->id,
                ]
            );
        }
    }
}
