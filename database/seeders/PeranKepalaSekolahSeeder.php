<?php

namespace Database\Seeders;

use App\Models\CatatanBeranda;
use App\Models\MetodePembelajaran;
use App\Models\Notifikasi;
use App\Models\PenilaianP5;
use App\Models\Pengingat;
use App\Models\EvaluasiGuru;
use App\Models\UcapanUlangTahun;
use App\Models\Pengguna;
use App\Models\DokumenWord;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PeranKepalaSekolahSeeder extends Seeder
{
    public function run(): void
    {
        $today = Carbon::today();

        /*
        |--------------------------------------------------------------------------
        | 1. AKUN KEPALA SEKOLAH
        |--------------------------------------------------------------------------
        */
        $kepsek = Pengguna::updateOrCreate(
            ['email' => 'kepsek@tu.test'],
            [
                'nama'          => 'Dr. H. Sugianto, M.Pd.',
                'nip'           => '196701011991031001',
                'password'      => Hash::make('password'),
                'peran'         => 'kepala_sekolah',
                'jabatan'       => 'Kepala Sekolah',
                'telepon'       => '081234567800',
                'alamat'        => 'Jl. Kaliurang No. 10, Jember',
                'aktif'         => true,
                'tanggal_lahir' => '1967-01-01',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | 2. EVALUASI GURU (PKG — Penilaian Kinerja Guru)
        |--------------------------------------------------------------------------
        | Kepala Sekolah mengevaluasi kinerja guru/staf.
        */
        $teachers = Pengguna::where('peran', '!=', 'kepala_sekolah')
            ->where('aktif', true)
            ->take(8)
            ->get();

        foreach ($teachers as $teacher) {
            $nilai    = rand(70, 95) + (rand(0, 99) / 100);
            $predikat = $nilai >= 90 ? 'Amat Baik' : ($nilai >= 76 ? 'Baik' : 'Cukup');

            EvaluasiGuru::updateOrCreate(
                ['pengguna_id' => $teacher->id, 'periode' => 'Semester Ganjil 2025/2026'],
                [
                    'jenis'           => 'pkg',
                    'nilai'           => round($nilai, 2),
                    'predikat'        => $predikat,
                    'catatan'         => 'Evaluasi PKG semester ganjil. ' . match (true) {
                        $predikat === 'Amat Baik' => 'Kinerja sangat memuaskan.',
                        $predikat === 'Baik'      => 'Kinerja sesuai standar.',
                        default                   => 'Perlu peningkatan kompetensi.',
                    },
                    'status'          => 'final',
                    'dievaluasi_oleh' => $kepsek->id,
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 3. PENILAIAN P5 (Projek Penguatan Profil Pelajar Pancasila)
        |--------------------------------------------------------------------------
        | Kepala Sekolah mengawasi pelaksanaan P5.
        */
        $p5Creator = Pengguna::where('email', 'bayu.kesiswaan@tu.test')->first();

        $p5Data = [
            ['tema' => 'Kearifan Lokal',               'judul_projek' => 'Eksplorasi Budaya Pendhalungan Jember',      'deskripsi' => 'Siswa mengeksplorasi kebudayaan Pendhalungan yang menjadi kekayaan budaya Jember melalui riset, wawancara tokoh, dan pameran.',            'kelas' => 'X',   'fase' => 'E', 'semester' => 'genap',  'dimensi' => 'Berkebinekaan Global',   'target_capaian' => 'Siswa mampu menghargai keragaman budaya dan mengkomunikasikannya.',                       'status' => 'final'],
            ['tema' => 'Bangunlah Jiwa dan Raganya',    'judul_projek' => 'Kampanye Hidup Sehat di Lingkungan Sekolah', 'deskripsi' => 'Siswa merancang dan melaksanakan kampanye gaya hidup sehat.',                                                                              'kelas' => 'XI',  'fase' => 'F', 'semester' => 'genap',  'dimensi' => 'Bergotong-royong',       'target_capaian' => 'Siswa mampu bekerja sama merencanakan dan melaksanakan kegiatan sosial.',                 'status' => 'final'],
            ['tema' => 'Suara Demokrasi',               'judul_projek' => 'Simulasi Pemilu OSIS yang Demokratis',      'deskripsi' => 'Mengadakan simulasi pemilihan OSIS dengan prinsip demokrasi yang jujur dan adil.',                                                        'kelas' => 'XI',  'fase' => 'F', 'semester' => 'ganjil', 'dimensi' => 'Bernalar Kritis',        'target_capaian' => 'Siswa memahami proses demokrasi dan berpartisipasi.',                                    'status' => 'final'],
            ['tema' => 'Berekayasa dan Berteknologi',    'judul_projek' => 'Pembuatan Website Profil Sekolah',          'deskripsi' => 'Siswa belajar dasar pemrograman web dan membuat website profil sekolah.',                                                                  'kelas' => 'XII', 'fase' => 'F', 'semester' => 'genap',  'dimensi' => 'Kreatif',                'target_capaian' => 'Siswa mampu menggunakan teknologi digital secara kreatif.',                               'status' => 'draft'],
        ];

        foreach ($p5Data as $p5) {
            PenilaianP5::updateOrCreate(
                ['judul_projek' => $p5['judul_projek']],
                array_merge($p5, [
                    'tahun_ajaran' => '2025/2026',
                    'dibuat_oleh'  => $p5Creator ? $p5Creator->id : $kepsek->id,
                ])
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 4. METODE PEMBELAJARAN
        |--------------------------------------------------------------------------
        | Kepala Sekolah mengawasi metode pembelajaran yang diterapkan guru.
        */
        $methodCreator = Pengguna::where('email', 'bayu.kesiswaan@tu.test')->first();

        $methods = [
            [
                'nama_metode'         => 'Project Based Learning (PjBL)',
                'jenis'               => 'aktif',
                'deskripsi'           => 'Metode pembelajaran berbasis proyek yang mendorong siswa untuk belajar melalui penyelesaian proyek nyata.',
                'langkah_pelaksanaan' => '1. Pertanyaan mendasar\n2. Merancang proyek\n3. Menyusun jadwal\n4. Monitoring\n5. Menguji hasil\n6. Evaluasi pengalaman',
                'kelebihan'           => 'Mendorong kreativitas, kolaborasi, dan kemampuan problem solving siswa.',
                'kekurangan'          => 'Membutuhkan waktu lebih lama dan persiapan yang matang.',
                'hasil'               => 'Diterapkan di kelas XI IPA untuk projek P5 dengan hasil sangat baik.',
                'mata_pelajaran'      => 'Lintas Mapel',
                'status'              => 'final',
            ],
            [
                'nama_metode'         => 'Diferensiasi Pembelajaran',
                'jenis'               => 'aktif',
                'deskripsi'           => 'Pengajaran yang disesuaikan dengan kebutuhan belajar masing-masing siswa berdasarkan kesiapan, minat, dan profil belajar.',
                'langkah_pelaksanaan' => '1. Asesmen diagnostik\n2. Pemetaan siswa\n3. Diferensiasi konten/proses/produk\n4. Pelaksanaan\n5. Refleksi',
                'kelebihan'           => 'Mengakomodasi keberagaman siswa dan meningkatkan keterlibatan.',
                'kekurangan'          => 'Memerlukan perencanaan ekstra dan pemahaman mendalam tentang siswa.',
                'hasil'               => 'Diterapkan di kelas X pada mapel Matematika dan Bahasa Indonesia.',
                'mata_pelajaran'      => 'Matematika, Bahasa Indonesia',
                'status'              => 'final',
            ],
            [
                'nama_metode'         => 'Flipped Classroom',
                'jenis'               => 'blended',
                'deskripsi'           => 'Siswa mempelajari materi di rumah melalui video, lalu sesi di kelas digunakan untuk diskusi dan praktik.',
                'langkah_pelaksanaan' => '1. Guru membuat video materi\n2. Siswa menonton di rumah\n3. Kuis awal kelas\n4. Diskusi dan tanya jawab\n5. Praktik/aktivitas',
                'kelebihan'           => 'Waktu kelas lebih produktif, siswa belajar sesuai kecepatan masing-masing.',
                'kekurangan'          => 'Membutuhkan akses internet dan perangkat di rumah.',
                'hasil'               => 'Uji coba di kelas XII IPA mapel Fisika — respons siswa positif.',
                'mata_pelajaran'      => 'Fisika',
                'status'              => 'draft',
            ],
        ];

        foreach ($methods as $m) {
            MetodePembelajaran::updateOrCreate(
                ['nama_metode' => $m['nama_metode']],
                array_merge($m, ['dibuat_oleh' => $methodCreator ? $methodCreator->id : $kepsek->id])
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 5. CATATAN BERANDA KEPALA SEKOLAH
        |--------------------------------------------------------------------------
        */
        $notes = [
            ['judul' => 'Agenda Prioritas Semester Genap',      'isi' => 'Fokus: PPDB 2026/2027, peningkatan akreditasi, supervisi akademik guru-guru muda, dan pelaksanaan UTS/UAS semester genap.', 'warna' => 'primary', 'disematkan' => true,  'tanggal' => '2026-03-01'],
            ['judul' => 'Hasil Supervisi Akademik',             'isi' => 'Supervisi kelas sudah dilakukan ke 12 guru. Hasil: 8 Amat Baik, 4 Baik. Perlu pendampingan ekstra untuk guru-guru baru.', 'warna' => 'success', 'disematkan' => true,  'tanggal' => '2026-02-28'],
            ['judul' => 'Capaian Akreditasi',                   'isi' => 'Skor akreditasi terakhir A (93). Target: mempertahankan A dengan peningkatan pada Standar Isi dan Standar Penilaian.',     'warna' => 'info',    'disematkan' => false, 'tanggal' => '2026-02-20'],
            ['judul' => 'Evaluasi Kinerja Staf TU',             'isi' => 'Rata-rata SKP staf TU semester ganjil: 89.5 (Baik). 5 staf mendapat predikat Sangat Baik. Berikan apresiasi.',            'warna' => 'warning', 'disematkan' => false, 'tanggal' => '2026-03-03'],
        ];

        foreach ($notes as $n) {
            CatatanBeranda::updateOrCreate(
                ['judul' => $n['judul'], 'pengguna_id' => $kepsek->id],
                [
                    'isi'        => $n['isi'],
                    'warna'      => $n['warna'],
                    'disematkan' => $n['disematkan'],
                    'tanggal'    => $n['tanggal'],
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 6. DOKUMEN WORD AI (Kepala Sekolah)
        |--------------------------------------------------------------------------
        */
        $wordDocs = [
            [
                'judul'     => 'Sambutan Kepala Sekolah di Rapat Komite',
                'kategori'  => 'laporan',
                'konten'    => '<h3>SAMBUTAN KEPALA SEKOLAH</h3><p>Assalamualaikum Wr. Wb. Yang terhormat Bapak/Ibu Anggota Komite Sekolah SMA Negeri 2 Jember.</p><p>Pada kesempatan ini saya ingin menyampaikan perkembangan sekolah di semester ganjil 2025/2026. Alhamdulillah capaian akademik dan non-akademik sekolah mengalami peningkatan signifikan.</p><p>Beberapa poin penting: kelulusan 100%, juara OSN tingkat provinsi, implementasi kurikulum merdeka berjalan baik, dan sistem administrasi digital telah aktif.</p>',
                'prompt_ai' => 'Buat sambutan kepala sekolah untuk rapat komite, membahas capaian semester ganjil dan rencana semester genap',
                'templat'   => 'laporan',
                'status'    => 'final',
                'dibagikan'  => true,
            ],
            [
                'judul'     => 'Catatan Supervisi Akademik Semester Ganjil',
                'kategori'  => 'laporan',
                'konten'    => '<h3>CATATAN SUPERVISI AKADEMIK</h3><p>Supervisi akademik dilaksanakan pada bulan Januari-Februari 2026. Total 12 guru disupervisi melalui observasi kelas dan review perangkat pembelajaran.</p><p>Temuan: sebagian besar guru sudah menerapkan diferensiasi pembelajaran. Area yang perlu ditingkatkan: pemanfaatan teknologi dalam asesmen formatif.</p>',
                'prompt_ai' => 'Buat catatan supervisi akademik semester ganjil 2025/2026 untuk 12 guru',
                'templat'   => 'laporan',
                'status'    => 'draft',
                'dibagikan'  => false,
            ],
            [
                'judul'     => 'Surat Rekomendasi Guru Berprestasi',
                'kategori'  => 'surat',
                'konten'    => '<p>Kepada Yth. Kepala Dinas Pendidikan Kabupaten Jember.</p><p>Dengan ini saya merekomendasikan guru SMA Negeri 2 Jember untuk mengikuti seleksi Guru Berprestasi Tingkat Kabupaten tahun 2026. Yang bersangkutan telah menunjukkan kinerja istimewa dalam pembelajaran dan pengembangan profesi.</p>',
                'prompt_ai' => 'Buat surat rekomendasi guru berprestasi untuk seleksi tingkat kabupaten',
                'templat'   => 'surat_resmi',
                'status'    => 'final',
                'dibagikan'  => true,
            ],
        ];

        foreach ($wordDocs as $doc) {
            DokumenWord::updateOrCreate(
                ['judul' => $doc['judul'], 'pengguna_id' => $kepsek->id],
                array_merge($doc, ['pengguna_id' => $kepsek->id])
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 7. PENGINGAT (KEPALA SEKOLAH)
        |--------------------------------------------------------------------------
        */
        $admin = Pengguna::where('email', 'admin@tu.test')->first();

        $reminders = [
            ['judul' => 'Supervisi Akademik Semester Genap',     'deskripsi' => 'Jadwal supervisi kelas untuk guru semester genap.',                        'jenis' => 'evaluasi',  'tenggat' => '2026-04-01', 'selesai' => false],
            ['judul' => 'Review RKAS 2026',                       'deskripsi' => 'Review dan persetujuan RKAS 2026 bersama komite sekolah.',                'jenis' => 'keuangan',  'tenggat' => '2026-03-15', 'selesai' => false],
            ['judul' => 'Pengumpulan Nilai Semester Genap',      'deskripsi' => 'Semua guru harus mengumpulkan nilai UTS semester genap.',                  'jenis' => 'akademik',  'tenggat' => '2026-04-15', 'selesai' => false],
            ['judul' => 'Rapat Koordinasi dengan Pengawas Sekolah', 'deskripsi' => 'Pertemuan rutin dengan pengawas sekolah dari Dinas Pendidikan.',        'jenis' => 'rapat',     'tenggat' => '2026-03-20', 'selesai' => false],
        ];

        foreach ($reminders as $r) {
            Pengingat::updateOrCreate(
                ['judul' => $r['judul'], 'pengguna_id' => $kepsek->id],
                [
                    'deskripsi'         => $r['deskripsi'],
                    'jenis'             => $r['jenis'],
                    'tenggat'           => $r['tenggat'],
                    'berulang'          => false,
                    'jenis_pengulangan' => null,
                    'pengguna_id'       => $kepsek->id,
                    'dibuat_oleh'       => $admin ? $admin->id : $kepsek->id,
                    'selesai'           => $r['selesai'],
                    'sudah_diberitahu'  => $r['selesai'],
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 8. UCAPAN ULANG TAHUN
        |--------------------------------------------------------------------------
        */
        if ($admin) {
            UcapanUlangTahun::updateOrCreate(
                ['pengirim_id' => $kepsek->id, 'penerima_id' => $admin->id, 'tahun' => 2026],
                [
                    'pesan'        => 'Selamat ulang tahun Pak Bambang! Terima kasih atas dedikasi dan kerja keras mengelola tata usaha sekolah. Sukses selalu! 🎂',
                    'sudah_dibaca' => false,
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 9. NOTIFIKASI KEPALA SEKOLAH
        |--------------------------------------------------------------------------
        */
        Notifikasi::create(['pengguna_id' => $kepsek->id, 'judul' => 'Pengajuan izin baru menunggu persetujuan',      'pesan' => 'Ada pengajuan izin staf yang memerlukan persetujuan Anda.',                    'jenis' => 'izin',      'sudah_dibaca' => false]);
        Notifikasi::create(['pengguna_id' => $kepsek->id, 'judul' => 'Laporan baru perlu ditinjau',                   'pesan' => 'Ada laporan baru yang perlu ditinjau.',                                        'jenis' => 'laporan',   'sudah_dibaca' => false]);
        Notifikasi::create(['pengguna_id' => $kepsek->id, 'judul' => 'Hasil PKG Semester Ganjil tersedia',            'pesan' => 'Laporan Penilaian Kinerja Guru semester ganjil sudah selesai dan siap review.', 'jenis' => 'sistem',    'sudah_dibaca' => false]);
        Notifikasi::create(['pengguna_id' => $kepsek->id, 'judul' => 'SKP staf menunggu persetujuan',                 'pesan' => 'Ada 3 SKP yang diajukan staf dan menunggu persetujuan Anda.',                   'jenis' => 'sistem',    'sudah_dibaca' => false]);
        Notifikasi::create(['pengguna_id' => $kepsek->id, 'judul' => 'Surat keluar menunggu persetujuan',             'pesan' => 'Ada surat keluar yang perlu disetujui sebelum dikirim.',                        'jenis' => 'sistem',    'sudah_dibaca' => false]);
        Notifikasi::create(['pengguna_id' => $kepsek->id, 'judul' => 'Rapor Pendidikan Sekolah 2025 tersedia',        'pesan' => 'Data rapor pendidikan dari platform Kemendikbud sudah tersedia.',               'jenis' => 'sistem',    'sudah_dibaca' => true, 'created_at' => now()->subDays(14)]);
        Notifikasi::create(['pengguna_id' => $kepsek->id, 'judul' => 'Agenda: Rapat Persiapan PPDB',                  'pesan' => 'Rapat persiapan PPDB 2026/2027 dijadwalkan 3 hari lagi di Ruang Rapat.',       'jenis' => 'event',     'sudah_dibaca' => false]);
        Notifikasi::create(['pengguna_id' => $kepsek->id, 'judul' => 'Selamat datang di Sistem SIMPEG-SMART!',     'pesan' => 'Akun Kepala Sekolah sudah aktif. Pantau kinerja staf dan approval dari sini.', 'jenis' => 'sistem',    'sudah_dibaca' => true, 'created_at' => now()->subDays(30)]);

        /*
        |--------------------------------------------------------------------------
        | OUTPUT
        |--------------------------------------------------------------------------
        */
        $this->command->info('');
        $this->command->info('  ✅ PERAN KEPALA SEKOLAH');
        $this->command->info('  ─────────────────────────────────────');
        $this->command->info('  Akun   : kepsek@tu.test (password: password)');
        $this->command->info('  Fitur  : 8 evaluasi PKG, 4 penilaian P5, 3 metode pembelajaran,');
        $this->command->info('           4 catatan beranda, 3 word AI, 4 pengingat,');
        $this->command->info('           ucapan ultah, 8 notifikasi');
    }
}
