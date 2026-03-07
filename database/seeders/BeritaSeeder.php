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
                'judul'     => 'Workshop Digitalisasi Arsip Surat Menyurat',
                'deskripsi' => 'Pelatihan internal digitalisasi surat masuk dan keluar menggunakan fitur SIMPEG-SMART untuk seluruh staf TU.',
                'konten'    => '<p>Kegiatan ini dilaksanakan pada tanggal 4 Maret 2026 di ruang multimedia SMA Negeri 2 Jember. Seluruh staf tata usaha mengikuti pelatihan cara mengelola surat masuk/keluar secara digital melalui platform SIMPEG-SMART.</p><p>Materi meliputi: upload dokumen surat, disposisi digital, tracking status surat, hingga arsip otomatis ke Google Drive. Diharapkan produktivitas layanan persuratan meningkat 40% setelah pelatihan ini.</p>',
                'kategori'  => 'berita',
                'tipe'      => 'teks',
                'bagian'    => 'halaman_utama',
                'unggulan'  => true,
                'urutan'    => 1,
            ],
            [
                'judul'     => 'Penerapan Sistem Absensi GPS & Selfie Mulai Berlaku',
                'deskripsi' => 'Mulai bulan Maret 2026, absensi staf TU wajib menggunakan fitur GPS dan foto selfie melalui SIMPEG-SMART.',
                'konten'    => '<p>Kebijakan baru ini bertujuan untuk meningkatkan akurasi dan transparansi data kehadiran staf. Setiap pegawai wajib melakukan clock-in dan clock-out melalui aplikasi SIMPEG-SMART yang dilengkapi verifikasi lokasi GPS dan foto selfie.</p><p>Sistem ini otomatis merekam koordinat lokasi, waktu, dan foto pegawai saat absensi. Data terintegasi langsung ke dashboard monitoring kepala sekolah.</p>',
                'kategori'  => 'berita',
                'tipe'      => 'teks',
                'bagian'    => 'halaman_utama',
                'unggulan'  => true,
                'urutan'    => 2,
            ],
            [
                'judul'     => 'Rapat Koordinasi Penyusunan RKJM & RKAS Tahun Ajaran 2026/2027',
                'deskripsi' => 'SMA Negeri 2 Jember menggelar rapat koordinasi penyusunan Rencana Kerja Jangka Menengah dan RKAS.',
                'konten'    => '<p>Rapat yang dihadiri oleh kepala sekolah, wakil kepala sekolah, KaTU, dan seluruh kepala unit kerja berlangsung pada 1 Maret 2026. Agenda utama adalah finalisasi draf RKJM 2026-2030 dan penyusunan RKAS tahun ajaran 2026/2027.</p><p>Seluruh dokumen hasil rapat diarsipkan secara digital melalui modul dokumen SIMPEG-SMART dan backup ke Google Drive sekolah.</p>',
                'kategori'  => 'berita',
                'tipe'      => 'teks',
                'bagian'    => 'halaman_utama',
                'unggulan'  => false,
                'urutan'    => 3,
            ],
            [
                'judul'     => 'Update Fitur: Evaluasi Kinerja PKG/BKD Sudah Terintegrasi AI',
                'deskripsi' => 'Modul evaluasi kinerja guru dan tenaga kependidikan kini dilengkapi rekomendasi cerdas berbasis AI.',
                'konten'    => '<p>Pembaruan terbaru pada SIMPEG-SMART menghadirkan fitur analisis otomatis untuk penilaian PKG, BKD, dan SKP. Sistem AI akan memberikan rekomendasi tindak lanjut berdasarkan data kinerja historis pegawai.</p><p>Fitur ini mempermudah evaluator dalam memberikan penilaian objektif dan mendokumentasikan bukti fisik secara terstruktur.</p>',
                'kategori'  => 'berita',
                'tipe'      => 'teks',
                'bagian'    => 'halaman_utama',
                'unggulan'  => false,
                'urutan'    => 4,
            ],
            [
                'judul'     => 'Kunjungan Studi Banding dari SMAN 1 Lumajang',
                'deskripsi' => 'Delegasi dari SMAN 1 Lumajang melakukan studi banding terkait penerapan sistem informasi administrasi digital.',
                'konten'    => '<p>Pada tanggal 28 Februari 2026, SMA Negeri 2 Jember menerima kunjungan studi banding dari tim tata usaha SMAN 1 Lumajang. Delegasi yang berjumlah 8 orang ini tertarik mempelajari implementasi platform SIMPEG-SMART.</p><p>Dalam kunjungan tersebut, tim TU SMA 2 Jember mempresentasikan fitur-fitur utama seperti manajemen pegawai, persuratan digital, evaluasi kinerja, dan integrasi AI.</p>',
                'kategori'  => 'berita',
                'tipe'      => 'teks',
                'bagian'    => 'halaman_utama',
                'unggulan'  => true,
                'urutan'    => 5,
            ],
            [
                'judul'     => 'Sosialisasi Penggunaan Cloud Backup untuk Keamanan Data',
                'deskripsi' => 'Seluruh staf TU mendapat pelatihan tentang pentingnya backup data ke cloud dan cara menggunakan fitur Google Drive di SIMPEG-SMART.',
                'konten'    => '<p>Pelatihan ini menekankan kebiasaan backup rutin untuk mengamankan dokumen penting sekolah. SIMPEG-SMART menyediakan fitur otomatis untuk menyimpan salinan dokumen ke Google Drive.</p><p>Staf juga diperkenalkan dengan fitur deteksi kapasitas penyimpanan dan notifikasi otomatis saat kuota mendekati batas maksimal.</p>',
                'kategori'  => 'berita',
                'tipe'      => 'teks',
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
