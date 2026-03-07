<?php

namespace Database\Seeders;

use App\Models\KontenPublik;
use App\Models\Pengguna;
use Illuminate\Database\Seeder;

class KontenPublikSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Pengguna::where('email', 'admin@tu.test')->first();

        if (!$admin) {
            return;
        }

        $items = [
            [
                'judul' => 'Ringkasan Kinerja TU Semester Ganjil 2025/2026',
                'deskripsi' => 'Ringkasan capaian layanan tata usaha per semester.',
                'konten' => '<p>Dokumen ini berisi ringkasan capaian layanan administrasi, ketepatan layanan surat, respons saran pengunjung, serta progres digitalisasi arsip.</p>',
                'kategori' => 'dokumen',
                'tipe' => 'teks',
                'bagian' => 'kinerja',
                'unggulan' => true,
                'urutan' => 1,
            ],
            [
                'judul' => 'Laporan Kepatuhan Kehadiran Staff TU',
                'deskripsi' => 'Informasi statistik kepatuhan kehadiran staf per bulan.',
                'konten' => '<p>Data menampilkan tingkat kehadiran, keterlambatan, dan tren disiplin staf TU untuk pengambilan keputusan.</p>',
                'kategori' => 'prestasi',
                'tipe' => 'teks',
                'bagian' => 'kinerja',
                'unggulan' => false,
                'urutan' => 2,
            ],
            [
                'judul' => 'Panduan Standar Layanan Administrasi',
                'deskripsi' => 'Dokumen panduan standar waktu layanan administrasi dan alur verifikasi.',
                'konten' => '<p>Panduan ini menjadi acuan semua unit kerja agar pelayanan administrasi tetap konsisten, cepat, dan akuntabel.</p>',
                'kategori' => 'dokumen',
                'tipe' => 'teks',
                'bagian' => 'kinerja',
                'unggulan' => false,
                'urutan' => 3,
            ],
            [
                'judul' => 'Arsip Kinerja Tahunan (Drive)',
                'deskripsi' => 'Repositori dokumen kinerja tahunan pada penyimpanan cloud resmi sekolah.',
                'kategori' => 'dokumen',
                'tipe' => 'link',
                'url_external' => 'https://drive.google.com/',
                'bagian' => 'kinerja',
                'unggulan' => false,
                'urutan' => 4,
            ],
        ];

        foreach ($items as $item) {
            KontenPublik::updateOrCreate(
                ['judul' => $item['judul'], 'bagian' => 'kinerja'],
                array_merge($item, [
                    'aktif' => true,
                    'dibuat_oleh' => $admin->id,
                ])
            );
        }
    }
}
