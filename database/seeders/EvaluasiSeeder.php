<?php

namespace Database\Seeders;

use App\Models\LearningMethod;
use App\Models\P5Assessment;
use App\Models\PhysicalEvidence;
use App\Models\StarAnalysis;
use App\Models\TeacherEvaluation;
use App\Models\User;
use Illuminate\Database\Seeder;

class EvaluasiSeeder extends Seeder
{
    public function run(): void
    {
        $admin   = User::where('email', 'admin@tu.test')->firstOrFail();
        $kepsek  = User::where('email', 'kepsek@tu.test')->firstOrFail();
        $staff1  = User::where('email', 'bayu.kesiswaan@tu.test')->firstOrFail();
        $staff2  = User::where('email', 'dwi.kepegawaian@tu.test')->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | 1. EVALUASI GURU (PKG — Penilaian Kinerja Guru)
        |--------------------------------------------------------------------------
        */
        $teachers = User::where('peran', '!=', 'kepala_sekolah')->where('aktif', true)->take(8)->get();

        $predikats = ['Amat Baik', 'Baik', 'Cukup'];
        foreach ($teachers as $i => $teacher) {
            $nilai = rand(70, 95) + (rand(0, 99) / 100);
            $predikat = $nilai >= 90 ? 'Amat Baik' : ($nilai >= 76 ? 'Baik' : 'Cukup');

            TeacherEvaluation::updateOrCreate(
                ['pengguna_id' => $teacher->id, 'periode' => 'Semester Ganjil 2025/2026'],
                [
                    'jenis'           => 'pkg',
                    'nilai'           => round($nilai, 2),
                    'predikat'        => $predikat,
                    'catatan'         => 'Evaluasi PKG semester ganjil. ' . ($predikat === 'Amat Baik' ? 'Kinerja sangat memuaskan.' : ($predikat === 'Baik' ? 'Kinerja sesuai standar.' : 'Perlu peningkatan kompetensi.')),
                    'status'          => 'final',
                    'dievaluasi_oleh' => $kepsek->id,
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 2. PENILAIAN P5 (Projek Penguatan Profil Pelajar Pancasila)
        |--------------------------------------------------------------------------
        */
        $p5Data = [
            [
                'tema'           => 'Kearifan Lokal',
                'judul_projek'   => 'Eksplorasi Budaya Pendhalungan Jember',
                'deskripsi'      => 'Siswa mengeksplorasi kebudayaan Pendhalungan yang menjadi kekayaan budaya Jember melalui riset, wawancara tokoh, dan pameran.',
                'kelas'          => 'X',
                'fase'           => 'E',
                'semester'       => 'genap',
                'dimensi'        => 'Berkebinekaan Global',
                'target_capaian' => 'Siswa mampu menghargai keragaman budaya dan mengkomunikasikannya.',
                'status'         => 'final',
            ],
            [
                'tema'           => 'Bangunlah Jiwa dan Raganya',
                'judul_projek'   => 'Kampanye Hidup Sehat di Lingkungan Sekolah',
                'deskripsi'      => 'Siswa merancang dan melaksanakan kampanye gaya hidup sehat.',
                'kelas'          => 'XI',
                'fase'           => 'F',
                'semester'       => 'genap',
                'dimensi'        => 'Bergotong-royong',
                'target_capaian' => 'Siswa mampu bekerja sama merencanakan dan melaksanakan kegiatan sosial.',
                'status'         => 'final',
            ],
            [
                'tema'           => 'Suara Demokrasi',
                'judul_projek'   => 'Simulasi Pemilu OSIS yang Demokratis',
                'deskripsi'      => 'Mengadakan simulasi pemilihan OSIS dengan prinsip demokrasi yang jujur dan adil.',
                'kelas'          => 'XI',
                'fase'           => 'F',
                'semester'       => 'ganjil',
                'dimensi'        => 'Bernalar Kritis',
                'target_capaian' => 'Siswa memahami proses demokrasi dan berpartisipasi.',
                'status'         => 'final',
            ],
            [
                'tema'           => 'Berekayasa dan Berteknologi',
                'judul_projek'   => 'Pembuatan Website Profil Sekolah',
                'deskripsi'      => 'Siswa belajar dasar pemrograman web dan membuat website profil sekolah.',
                'kelas'          => 'XII',
                'fase'           => 'F',
                'semester'       => 'genap',
                'dimensi'        => 'Kreatif',
                'target_capaian' => 'Siswa mampu menggunakan teknologi digital secara kreatif.',
                'status'         => 'draft',
            ],
        ];

        foreach ($p5Data as $p5) {
            P5Assessment::updateOrCreate(
                ['judul_projek' => $p5['judul_projek']],
                array_merge($p5, [
                    'tahun_ajaran' => '2025/2026',
                    'dibuat_oleh'  => $staff1->id,
                ])
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 3. ANALISIS STAR
        |--------------------------------------------------------------------------
        */
        $starData = [
            [
                'judul'     => 'Peningkatan Kedisiplinan Siswa Melalui Sistem Poin',
                'kategori'  => 'kesiswaan',
                'situasi'   => 'Angka keterlambatan siswa mencapai rata-rata 15 siswa/hari pada semester ganjil 2025.',
                'tugas'     => 'Menurunkan angka keterlambatan menjadi di bawah 5 siswa/hari.',
                'aksi'      => 'Menerapkan sistem poin pelanggaran dengan reward-punishment. Memasang fingerprint di pintu gerbang. Koordinasi dengan BK dan wali kelas.',
                'hasil'     => 'Angka keterlambatan turun menjadi rata-rata 3 siswa/hari pada bulan Februari 2026.',
                'refleksi'  => 'Sistem berjalan baik, perlu dievaluasi kembali setiap semester.',
                'tindak_lanjut' => 'Pertahankan sistem dan tambahkan apresiasi untuk kelas paling disiplin.',
            ],
            [
                'judul'     => 'Implementasi E-Rapor Kurikulum Merdeka',
                'kategori'  => 'kurikulum',
                'situasi'   => 'Rapor masih diisi manual di Excel, sering terjadi kesalahan input dan terlambat.',
                'tugas'     => 'Mengimplementasikan e-rapor terintegrasi yang efisien.',
                'aksi'      => 'Melakukan pelatihan e-rapor untuk semua guru. Menyiapkan infrastruktur server dan akses. Pendampingan selama 2 bulan.',
                'hasil'     => 'E-rapor berhasil digunakan oleh 100% guru pada semester ganjil 2025/2026.',
                'refleksi'  => 'Beberapa guru senior perlu pendampingan ekstra.',
                'tindak_lanjut' => 'Lanjutkan pelatihan rutin dan buat panduan video tutorial.',
            ],
            [
                'judul'     => 'Digitalisasi Arsip Persuratan',
                'kategori'  => 'administrasi',
                'situasi'   => 'Arsip surat masih berbasis fisik, sulit dicari dan rawan hilang.',
                'tugas'     => 'Migrasi arsip ke sistem digital dengan pencarian.',
                'aksi'      => 'Scan semua arsip surat 2 tahun terakhir. Input ke sistem TU Administrasi. Training untuk staf persuratan.',
                'hasil'     => '95% arsip surat 2024-2025 berhasil didigitalisasi. Waktu pencarian arsip turun dari 15 menit menjadi di bawah 1 menit.',
                'refleksi'  => 'Digitalisasi sangat membantu efisiensi administrasi.',
                'tindak_lanjut' => 'Arsip surat baru langsung diinput ke sistem.',
            ],
        ];

        foreach ($starData as $star) {
            StarAnalysis::updateOrCreate(
                ['judul' => $star['judul']],
                array_merge($star, ['dibuat_oleh' => $admin->id])
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 4. BUKTI FISIK
        |--------------------------------------------------------------------------
        */
        $evidences = [
            ['judul' => 'Foto Pelaksanaan ANBK 2025',          'kategori' => 'penilaian',   'deskripsi' => 'Dokumentasi foto pelaksanaan ANBK di Lab Komputer SMA Negeri 2 Jember.', 'terkait' => 'Standar Penilaian'],
            ['judul' => 'SK Pembagian Tugas Guru 2025/2026',   'kategori' => 'ptk',         'deskripsi' => 'Surat Keputusan pembagian tugas mengajar guru.',   'terkait' => 'Standar PTK'],
            ['judul' => 'MoU Kerjasama Industri DUDI',         'kategori' => 'kerjasama',   'deskripsi' => 'Nota kesepahaman dengan dunia usaha untuk magang dan kunjungan industri.',     'terkait' => 'Standar Pengelolaan'],
            ['judul' => 'Laporan Supervisi Akademik Sem. 1',   'kategori' => 'supervisi',   'deskripsi' => 'Hasil supervisi akademik oleh kepala sekolah semester ganjil 2025/2026.',       'terkait' => 'Standar Proses'],
            ['judul' => 'Rapor Pendidikan Sekolah 2025',       'kategori' => 'mutu',        'deskripsi' => 'Data rapor pendidikan dari platform Kemendikbud untuk SMA Negeri 2 Jember.',    'terkait' => 'Standar Kompetensi Lulusan'],
            ['judul' => 'Sertifikat Akreditasi A (2022-2027)', 'kategori' => 'akreditasi',  'deskripsi' => 'Sertifikat akreditasi A dari BAN-S/M berlaku sampai 2027.',                    'terkait' => 'Standar Pengelolaan'],
        ];

        foreach ($evidences as $e) {
            PhysicalEvidence::updateOrCreate(
                ['judul' => $e['judul']],
                [
                    'kategori'      => $e['kategori'],
                    'deskripsi'     => $e['deskripsi'],
                    'path_file'     => 'bukti-fisik/' . \Illuminate\Support\Str::slug($e['judul']) . '.pdf',
                    'nama_file'     => \Illuminate\Support\Str::slug($e['judul']) . '.pdf',
                    'terkait'       => $e['terkait'],
                    'diunggah_oleh' => $admin->id,
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 5. METODE PEMBELAJARAN
        |--------------------------------------------------------------------------
        */
        $methods = [
            [
                'nama_metode'          => 'Project Based Learning (PjBL)',
                'jenis'                => 'aktif',
                'deskripsi'            => 'Metode pembelajaran berbasis proyek yang mendorong siswa untuk belajar melalui penyelesaian proyek nyata.',
                'langkah_pelaksanaan'  => '1. Pertanyaan mendasar\n2. Merancang proyek\n3. Menyusun jadwal\n4. Monitoring\n5. Menguji hasil\n6. Evaluasi pengalaman',
                'kelebihan'            => 'Mendorong kreativitas, kolaborasi, dan kemampuan problem solving siswa.',
                'kekurangan'           => 'Membutuhkan waktu lebih lama dan persiapan yang matang.',
                'hasil'                => 'Diterapkan di kelas XI IPA untuk projek P5 dengan hasil sangat baik.',
                'mata_pelajaran'       => 'Lintas Mapel',
                'status'               => 'final',
            ],
            [
                'nama_metode'          => 'Diferensiasi Pembelajaran',
                'jenis'                => 'aktif',
                'deskripsi'            => 'Pengajaran yang disesuaikan dengan kebutuhan belajar masing-masing siswa berdasarkan kesiapan, minat, dan profil belajar.',
                'langkah_pelaksanaan'  => '1. Asesmen diagnostik\n2. Pemetaan siswa\n3. Diferensiasi konten/proses/produk\n4. Pelaksanaan\n5. Refleksi',
                'kelebihan'            => 'Mengakomodasi keberagaman siswa dan meningkatkan keterlibatan.',
                'kekurangan'           => 'Memerlukan perencanaan ekstra dan pemahaman mendalam tentang siswa.',
                'hasil'                => 'Diterapkan di kelas X pada mapel Matematika dan Bahasa Indonesia.',
                'mata_pelajaran'       => 'Matematika, Bahasa Indonesia',
                'status'               => 'final',
            ],
            [
                'nama_metode'          => 'Flipped Classroom',
                'jenis'                => 'blended',
                'deskripsi'            => 'Siswa mempelajari materi di rumah melalui video, lalu sesi di kelas digunakan untuk diskusi dan praktik.',
                'langkah_pelaksanaan'  => '1. Guru membuat video materi\n2. Siswa menonton di rumah\n3. Kuis awal kelas\n4. Diskusi dan tanya jawab\n5. Praktik/aktivitas',
                'kelebihan'            => 'Waktu kelas lebih produktif, siswa belajar sesuai kecepatan masing-masing.',
                'kekurangan'           => 'Membutuhkan akses internet dan perangkat di rumah.',
                'hasil'                => 'Uji coba di kelas XII IPA mapel Fisika — respons siswa positif.',
                'mata_pelajaran'       => 'Fisika',
                'status'               => 'draft',
            ],
        ];

        foreach ($methods as $m) {
            LearningMethod::updateOrCreate(
                ['nama_metode' => $m['nama_metode']],
                array_merge($m, ['dibuat_oleh' => $staff1->id])
            );
        }
    }
}
