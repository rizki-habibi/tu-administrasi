<?php

namespace Database\Seeders;

use App\Models\KontenPublik;
use App\Models\Pengguna;
use Illuminate\Database\Seeder;

class BeritaSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Pengguna::where('email', 'admin@tu.test')->first();
        if (!$admin) return;

        $berita = [
            [
                'judul'     => 'Kepala Sekolah Baru, Arah Baru SMAN 2 Jember',
                'deskripsi' => 'New leadership, new direction! SMA Negeri 2 Jember memulai babak baru bersama Kepala Sekolah yang siap mengangkat kualitas pendidikan.',
                'konten'    => '<p>SMA Negeri 2 Jember menyambut era baru dengan kepemimpinan yang segar. Kepala Sekolah baru membawa visi untuk mengangkat kualitas pendidikan, karakter, dan prestasi siswa ke level berikutnya.</p><p>Berbagai program inovatif direncanakan termasuk digitalisasi administrasi melalui platform SIMPEG-SMART.</p>',
                'kategori'  => 'berita',
                'tipe'      => 'video',
                'url_external' => 'https://youtu.be/lU4jiQrfOik',
                'thumbnail' => 'https://img.youtube.com/vi/lU4jiQrfOik/hqdefault.jpg',
                'bagian'    => 'halaman_utama',
                'unggulan'  => true,
                'urutan'    => 1,
            ],
            [
                'judul'     => 'Profil Guru SMAN 2 Jember',
                'deskripsi' => 'Mengenal lebih dekat para pendidik SMA Negeri 2 Jember yang berdedikasi tinggi dalam mencerdaskan anak bangsa.',
                'konten'    => '<p>Video profil menampilkan para guru SMA Negeri 2 Jember dengan dedikasi tinggi. Masing-masing membawa keahlian dan semangat dalam membimbing siswa menuju prestasi terbaik.</p>',
                'kategori'  => 'berita',
                'tipe'      => 'video',
                'url_external' => 'https://youtu.be/iLBBTDctlVU',
                'thumbnail' => 'https://img.youtube.com/vi/iLBBTDctlVU/hqdefault.jpg',
                'bagian'    => 'halaman_utama',
                'unggulan'  => true,
                'urutan'    => 2,
            ],
            [
                'judul'     => 'SMAN 2 Jember Laksanakan Program Makan Bergizi Gratis',
                'deskripsi' => 'SMAN 2 Jember mulai melaksanakan Program Makan Bergizi Gratis dari Pemerintah untuk seluruh peserta didik.',
                'konten'    => '<p>Dalam rangka mendukung program pemerintah, SMA Negeri 2 Jember telah memulai pelaksanaan Program Makan Bergizi Gratis. Program ini bertujuan untuk meningkatkan gizi dan kesehatan seluruh peserta didik.</p><p>Pelaksanaan dikoordinasikan dengan baik oleh tim sekolah untuk memastikan kualitas dan kebersihan makanan yang disajikan.</p>',
                'kategori'  => 'berita',
                'tipe'      => 'teks',
                'url_external' => 'https://sman2jember.sch.id/elementor-2038/',
                'thumbnail' => 'https://img.youtube.com/vi/lU4jiQrfOik/mqdefault.jpg',
                'bagian'    => 'halaman_utama',
                'unggulan'  => false,
                'urutan'    => 3,
            ],
            [
                'judul'     => '7 Kebiasaan Anak Indonesia Hebat - SMAN 2 Jember',
                'deskripsi' => 'Program pembentukan karakter melalui 7 Kebiasaan Anak Indonesia Hebat yang diterapkan di lingkungan SMAN 2 Jember.',
                'konten'    => '<p>SMA Negeri 2 Jember menerapkan program 7 Kebiasaan Anak Indonesia Hebat sebagai bagian dari pembentukan karakter siswa. Program ini mencakup kebiasaan positif yang ditanamkan dalam kegiatan sehari-hari di sekolah.</p>',
                'kategori'  => 'berita',
                'tipe'      => 'video',
                'url_external' => 'https://youtu.be/FjvxaQ_3Kbc',
                'thumbnail' => 'https://img.youtube.com/vi/FjvxaQ_3Kbc/hqdefault.jpg',
                'bagian'    => 'halaman_utama',
                'unggulan'  => true,
                'urutan'    => 4,
            ],
            [
                'judul'     => 'Workshop Digitalisasi Arsip Surat Menyurat',
                'deskripsi' => 'Pelatihan internal digitalisasi surat masuk dan keluar menggunakan fitur SIMPEG-SMART untuk seluruh staf TU.',
                'konten'    => '<p>Kegiatan ini dilaksanakan pada tanggal 4 Maret 2026 di ruang multimedia SMA Negeri 2 Jember. Seluruh staf tata usaha mengikuti pelatihan cara mengelola surat masuk/keluar secara digital melalui platform SIMPEG-SMART.</p><p>Materi meliputi: upload dokumen surat, disposisi digital, tracking status surat, hingga arsip otomatis ke Google Drive.</p>',
                'kategori'  => 'berita',
                'tipe'      => 'teks',
                'thumbnail' => 'https://img.youtube.com/vi/iLBBTDctlVU/mqdefault.jpg',
                'bagian'    => 'halaman_utama',
                'unggulan'  => false,
                'urutan'    => 5,
            ],
            [
                'judul'     => 'Penerapan Sistem Absensi GPS & Selfie Mulai Berlaku',
                'deskripsi' => 'Mulai bulan Maret 2026, absensi staf TU wajib menggunakan fitur GPS dan foto selfie melalui SIMPEG-SMART.',
                'konten'    => '<p>Kebijakan baru ini bertujuan untuk meningkatkan akurasi dan transparansi data kehadiran staf. Setiap pegawai wajib melakukan clock-in dan clock-out melalui aplikasi SIMPEG-SMART yang dilengkapi verifikasi lokasi GPS dan foto selfie.</p>',
                'kategori'  => 'berita',
                'tipe'      => 'teks',
                'thumbnail' => 'https://img.youtube.com/vi/FjvxaQ_3Kbc/mqdefault.jpg',
                'bagian'    => 'halaman_utama',
                'unggulan'  => false,
                'urutan'    => 6,
            ],
        ];

        foreach ($berita as $item) {
            KontenPublik::updateOrCreate(
                ['judul' => $item['judul']],
                array_merge($item, [
                    'aktif' => true,
                    'dibuat_oleh' => $admin->id,
                ])
            );
        }
    }
}
