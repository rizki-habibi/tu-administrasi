<?php

namespace Database\Seeders;

use App\Models\AccreditationDocument;
use App\Models\SchoolEvaluation;
use App\Models\User;
use Illuminate\Database\Seeder;

class AkreditasiSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@tu.test')->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | 1. DOKUMEN AKREDITASI
        |--------------------------------------------------------------------------
        */
        $docs = [
            // Standar 1: Kompetensi Lulusan
            ['standar' => 'Standar Kompetensi Lulusan', 'komponen' => 'Lulusan memiliki kompetensi pada dimensi sikap',                            'indikator' => '1.1 Integritas, karakter, dan kepribadian',              'status' => 'lengkap'],
            ['standar' => 'Standar Kompetensi Lulusan', 'komponen' => 'Lulusan memiliki kompetensi pada dimensi pengetahuan',                      'indikator' => '1.2 Penguasaan ilmu pengetahuan dan teknologi',          'status' => 'lengkap'],
            ['standar' => 'Standar Kompetensi Lulusan', 'komponen' => 'Lulusan memiliki kompetensi pada dimensi keterampilan',                     'indikator' => '1.3 Keterampilan berpikir kritis dan kreatif',           'status' => 'lengkap'],

            // Standar 2: Isi
            ['standar' => 'Standar Isi',                'komponen' => 'Perangkat kurikulum satuan pendidikan',                                     'indikator' => '2.1 Dokumen KTSP disusun dan ditetapkan oleh kepala sekolah',    'status' => 'lengkap'],
            ['standar' => 'Standar Isi',                'komponen' => 'Kurikulum sekolah dikembangkan sesuai prosedur',                             'indikator' => '2.2 Silabus dikembangkan sesuai pedoman',                         'status' => 'belum_lengkap'],

            // Standar 3: Proses
            ['standar' => 'Standar Proses',             'komponen' => 'Perencanaan proses pembelajaran',                                           'indikator' => '3.1 RPP/modul ajar tersedia untuk seluruh mata pelajaran',       'status' => 'lengkap'],
            ['standar' => 'Standar Proses',             'komponen' => 'Pelaksanaan proses pembelajaran',                                           'indikator' => '3.2 Pembelajaran sesuai kurikulum merdeka',                      'status' => 'lengkap'],

            // Standar 4: Penilaian
            ['standar' => 'Standar Penilaian',          'komponen' => 'Teknik penilaian sesuai karakteristik kompetensi',                           'indikator' => '4.1 Penilaian formatif dan sumatif dilaksanakan',                'status' => 'belum_lengkap'],

            // Standar 5: PTK
            ['standar' => 'Standar PTK',                'komponen' => 'Kualifikasi dan kompetensi guru',                                           'indikator' => '5.1 Guru memenuhi kualifikasi akademik',                         'status' => 'lengkap'],
            ['standar' => 'Standar PTK',                'komponen' => 'Kualifikasi dan kompetensi tenaga kependidikan',                             'indikator' => '5.2 Tenaga kependidikan memenuhi kualifikasi',                   'status' => 'lengkap'],

            // Standar 6: Sarpras
            ['standar' => 'Standar Sarana Prasarana',   'komponen' => 'Sarana dan prasarana pembelajaran',                                          'indikator' => '6.1 Ruang kelas memenuhi standar',                               'status' => 'lengkap'],

            // Standar 7: Pengelolaan
            ['standar' => 'Standar Pengelolaan',        'komponen' => 'Perencanaan program sekolah',                                                'indikator' => '7.1 Visi, misi, tujuan sekolah tersedia',                        'status' => 'lengkap'],

            // Standar 8: Pembiayaan
            ['standar' => 'Standar Pembiayaan',         'komponen' => 'Pembiayaan pendidikan',                                                      'indikator' => '8.1 Sekolah memiliki RKAS sesuai ketentuan',                     'status' => 'lengkap'],
        ];

        foreach ($docs as $d) {
            AccreditationDocument::updateOrCreate(
                ['standar' => $d['standar'], 'indikator' => $d['indikator']],
                [
                    'komponen'     => $d['komponen'],
                    'deskripsi'    => 'Dokumen bukti pemenuhan ' . $d['indikator'],
                    'status'       => $d['status'],
                    'catatan'      => $d['status'] === 'belum_lengkap' ? 'Perlu dilengkapi sebelum visitasi akreditasi.' : null,
                    'diunggah_oleh'=> $admin->id,
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 2. EVALUASI DIRI SEKOLAH (EDS)
        |--------------------------------------------------------------------------
        */
        $eds = [
            ['aspek' => 'Mutu Lulusan',               'kondisi' => 'Rata-rata kelulusan 3 tahun terakhir 100%. Rata-rata nilai UTBK meningkat 5% per tahun.', 'target' => 'Mempertahankan kelulusan 100% dan meningkatkan jumlah siswa diterima PTN.', 'program' => 'Bimbingan intensif UTBK dan program tutor sebaya.', 'status' => 'final'],
            ['aspek' => 'Proses Pembelajaran',         'kondisi' => 'Implementasi Kurikulum Merdeka sudah berjalan 80%. Diferensiasi pembelajaran sudah diterapkan di beberapa kelas.', 'target' => '100% guru menerapkan kurikulum merdeka dengan diferensiasi.', 'program' => 'Pelatihan IKM dan Lesson Study kolaboratif.', 'status' => 'final'],
            ['aspek' => 'Kualitas Guru',               'kondisi' => '85% guru sudah S1/S2. 60% guru telah mengikuti PPG.', 'target' => '100% guru mengikuti PPG dalam 2 tahun ke depan.', 'program' => 'Fasilitasi pendaftaran PPG dan workshop kompetensi pedagogik.', 'status' => 'final'],
            ['aspek' => 'Sarana & Prasarana',           'kondisi' => 'Lab komputer perlu pembaruan perangkat. WiFi sekolah sudah memadai.', 'target' => 'Semua lab memiliki peralatan sesuai standar minimal.', 'program' => 'Pengadaan 20 unit komputer baru dari dana BOS.', 'status' => 'draft'],
            ['aspek' => 'Manajemen Sekolah',           'kondisi' => 'RKS dan RKAS sudah tersedia. Sistem informasi administrasi baru diimplementasikan.', 'target' => 'Digitalisasi penuh administrasi sekolah.', 'program' => 'Pengembangan sistem TU Administrasi dan pelatihan SDM.', 'status' => 'final'],
            ['aspek' => 'Iklim & Budaya Sekolah',      'kondisi' => 'Program literasi berjalan rutin. Angka pelanggaran tata tertib menurun 15%.', 'target' => 'Zero bullying dan peningkatan budaya literasi.', 'program' => 'Gerakan Literasi Sekolah (GLS) dan program anti-perundungan.', 'status' => 'draft'],
        ];

        foreach ($eds as $e) {
            SchoolEvaluation::updateOrCreate(
                ['tahun' => '2026', 'aspek' => $e['aspek']],
                [
                    'kondisi_saat_ini'       => $e['kondisi'],
                    'target'                 => $e['target'],
                    'program_tindak_lanjut'  => $e['program'],
                    'status'                 => $e['status'],
                    'dibuat_oleh'            => $admin->id,
                ]
            );
        }
    }
}
