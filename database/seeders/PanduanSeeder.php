<?php

namespace Database\Seeders;

use App\Models\Panduan;
use Illuminate\Database\Seeder;

class PanduanSeeder extends Seeder
{
    public function run(): void
    {
        $docsPath = base_path('docs');

        $items = [
            [
                'judul' => 'Panduan Penggunaan',
                'slug' => 'panduan-penggunaan',
                'deskripsi' => 'Panduan lengkap penggunaan 20 modul fitur dasar Sistem TU Administrasi',
                'ikon' => 'bi-book',
                'warna' => '#6366f1',
                'versi' => 'v0.1',
                'kategori' => 'panduan',
                'visibilitas' => 'semua',
                'urutan' => 1,
                'file' => 'PANDUAN-PENGGUNAAN.md',
            ],
            [
                'judul' => 'Pembaruan v0.2',
                'slug' => 'pembaruan-v02',
                'deskripsi' => 'Penyesuaian peran, halaman utama, halaman kinerja, scroll-to-top, versioning panduan',
                'ikon' => 'bi-arrow-up-circle',
                'warna' => '#10b981',
                'versi' => 'v0.2',
                'kategori' => 'changelog',
                'visibilitas' => 'semua',
                'urutan' => 2,
                'file' => 'PANDUAN-v0.2.md',
            ],
            [
                'judul' => 'Database & Peran',
                'slug' => 'database-dan-peran',
                'deskripsi' => 'Dokumentasi lengkap skema database, tabel, relasi, dan sistem peran pengguna',
                'ikon' => 'bi-database',
                'warna' => '#f59e0b',
                'versi' => 'v0.2',
                'kategori' => 'dokumentasi',
                'visibilitas' => 'admin',
                'urutan' => 3,
                'file' => 'DATABASE-DAN-PERAN.md',
            ],
            [
                'judul' => 'Panduan AI',
                'slug' => 'panduan-ai',
                'deskripsi' => 'Panduan penggunaan fitur AI untuk analisis dan rekomendasi di sistem administrasi',
                'ikon' => 'bi-robot',
                'warna' => '#8b5cf6',
                'versi' => 'v0.1',
                'kategori' => 'panduan',
                'visibilitas' => 'semua',
                'urutan' => 4,
                'file' => 'PANDUAN-AI.md',
            ],
            [
                'judul' => 'Panduan Google Drive',
                'slug' => 'panduan-google-drive',
                'deskripsi' => 'Integrasi dan penggunaan Google Drive untuk backup dan penyimpanan dokumen',
                'ikon' => 'bi-cloud-upload',
                'warna' => '#0ea5e9',
                'versi' => 'v0.1',
                'kategori' => 'panduan',
                'visibilitas' => 'semua',
                'urutan' => 5,
                'file' => 'PANDUAN-GOOGLE-DRIVE.md',
            ],
            [
                'judul' => 'Rekomendasi API AI',
                'slug' => 'rekomendasi-api-ai',
                'deskripsi' => 'Daftar rekomendasi API AI gratis dan berbayar untuk integrasi sistem',
                'ikon' => 'bi-cpu',
                'warna' => '#ec4899',
                'versi' => 'v0.1',
                'kategori' => 'referensi',
                'visibilitas' => 'admin',
                'urutan' => 6,
                'file' => 'REKOMENDASI-API-AI.md',
            ],
            [
                'judul' => 'Use Case Diagram',
                'slug' => 'use-case-diagram',
                'deskripsi' => 'Diagram use case lengkap untuk seluruh peran dan fitur sistem',
                'ikon' => 'bi-diagram-3',
                'warna' => '#14b8a6',
                'versi' => 'v0.2',
                'kategori' => 'dokumentasi',
                'visibilitas' => 'admin',
                'urutan' => 7,
                'file' => 'USE-CASE-DIAGRAM.md',
            ],
        ];

        foreach ($items as $item) {
            $filePath = $docsPath . DIRECTORY_SEPARATOR . $item['file'];
            $konten = file_exists($filePath) ? file_get_contents($filePath) : '# ' . $item['judul'] . "\n\nKonten belum tersedia.";

            Panduan::updateOrCreate(
                ['slug' => $item['slug']],
                [
                    'judul' => $item['judul'],
                    'deskripsi' => $item['deskripsi'],
                    'konten' => $konten,
                    'ikon' => $item['ikon'],
                    'warna' => $item['warna'],
                    'versi' => $item['versi'],
                    'kategori' => $item['kategori'],
                    'visibilitas' => $item['visibilitas'],
                    'urutan' => $item['urutan'],
                    'aktif' => true,
                    'dibuat_oleh' => 1,
                ]
            );
        }
    }
}
