<?php

namespace Database\Seeders;

use App\Models\LaporanKerusakan;
use App\Models\Inventaris;
use App\Models\Pengguna;
use Illuminate\Database\Seeder;

class InventarisSeeder extends Seeder
{
    public function run(): void
    {
        $invUser  = Pengguna::where('email', 'fatkurahman.inventaris@tu.test')->firstOrFail();
        $invUser2 = Pengguna::where('email', 'imam.inventaris@tu.test')->firstOrFail();
        $pramuB   = Pengguna::where('email', 'eko.pramubakti@tu.test')->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | 1. INVENTARIS
        |--------------------------------------------------------------------------
        */
        $items = [
            ['kode' => 'INV-2026-001', 'nama' => 'Laptop ASUS VivoBook 14',     'kategori' => 'elektronik',  'lokasi' => 'Ruang TU',            'jumlah' => 5,  'kondisi' => 'baik',   'harga' => 8_500_000,  'tgl' => '2024-07-10', 'sumber' => 'BOS Reguler'],
            ['kode' => 'INV-2026-002', 'nama' => 'Printer Epson L3210',          'kategori' => 'elektronik',  'lokasi' => 'Ruang TU',            'jumlah' => 3,  'kondisi' => 'baik',   'harga' => 2_300_000,  'tgl' => '2024-07-10', 'sumber' => 'BOS Reguler'],
            ['kode' => 'INV-2026-003', 'nama' => 'Proyektor Epson EB-X51',      'kategori' => 'elektronik',  'lokasi' => 'Ruang Kelas',         'jumlah' => 24, 'kondisi' => 'baik',   'harga' => 7_500_000,  'tgl' => '2023-08-15', 'sumber' => 'BOS Kinerja'],
            ['kode' => 'INV-2026-004', 'nama' => 'AC Daikin 1.5 PK',            'kategori' => 'elektronik',  'lokasi' => 'Ruang Guru',          'jumlah' => 4,  'kondisi' => 'rusak_ringan', 'harga' => 5_200_000, 'tgl' => '2022-03-20', 'sumber' => 'Komite'],
            ['kode' => 'INV-2026-005', 'nama' => 'Meja Siswa Standar',           'kategori' => 'mebeler',     'lokasi' => 'Ruang Kelas',         'jumlah' => 960,'kondisi' => 'baik',   'harga' => 450_000,    'tgl' => '2021-07-01', 'sumber' => 'BOS Reguler'],
            ['kode' => 'INV-2026-006', 'nama' => 'Kursi Siswa Standar',          'kategori' => 'mebeler',     'lokasi' => 'Ruang Kelas',         'jumlah' => 960,'kondisi' => 'baik',   'harga' => 350_000,    'tgl' => '2021-07-01', 'sumber' => 'BOS Reguler'],
            ['kode' => 'INV-2026-007', 'nama' => 'Lemari Arsip Besi 4 Laci',    'kategori' => 'mebeler',     'lokasi' => 'Ruang TU',            'jumlah' => 6,  'kondisi' => 'baik',   'harga' => 2_800_000,  'tgl' => '2023-01-15', 'sumber' => 'BOS Reguler'],
            ['kode' => 'INV-2026-008', 'nama' => 'Papan Tulis Whiteboard',       'kategori' => 'alat_peraga', 'lokasi' => 'Ruang Kelas',         'jumlah' => 30, 'kondisi' => 'baik',   'harga' => 350_000,    'tgl' => '2023-07-10', 'sumber' => 'BOS Reguler'],
            ['kode' => 'INV-2026-009', 'nama' => 'Komputer Desktop Lab',         'kategori' => 'elektronik',  'lokasi' => 'Lab Komputer',        'jumlah' => 40, 'kondisi' => 'baik',   'harga' => 6_000_000,  'tgl' => '2024-01-20', 'sumber' => 'BOS Kinerja'],
            ['kode' => 'INV-2026-010', 'nama' => 'Buku Paket Kurikulum Merdeka','kategori' => 'buku',        'lokasi' => 'Perpustakaan',        'jumlah' => 2400,'kondisi' => 'baik', 'harga' => 65_000,     'tgl' => '2025-07-15', 'sumber' => 'BOS Reguler'],
            ['kode' => 'INV-2026-011', 'nama' => 'Alat Laboratorium Fisika',    'kategori' => 'alat_peraga', 'lokasi' => 'Lab Fisika',          'jumlah' => 15, 'kondisi' => 'baik',   'harga' => 1_200_000,  'tgl' => '2024-08-01', 'sumber' => 'BOS Reguler'],
            ['kode' => 'INV-2026-012', 'nama' => 'CCTV Hikvision 2MP',          'kategori' => 'elektronik',  'lokasi' => 'Seluruh Area Sekolah','jumlah' => 16, 'kondisi' => 'baik',   'harga' => 850_000,    'tgl' => '2025-01-10', 'sumber' => 'Komite'],
        ];

        $createdItems = [];
        foreach ($items as $item) {
            $createdItems[$item['kode']] = Inventaris::updateOrCreate(
                ['kode_barang' => $item['kode']],
                [
                    'nama_barang'       => $item['nama'],
                    'kategori'          => $item['kategori'],
                    'lokasi'            => $item['lokasi'],
                    'jumlah'            => $item['jumlah'],
                    'kondisi'           => $item['kondisi'],
                    'tanggal_perolehan' => $item['tgl'],
                    'sumber_dana'       => $item['sumber'],
                    'harga_perolehan'   => $item['harga'],
                    'deskripsi'         => null,
                    'dibuat_oleh'       => $invUser->id,
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 2. LAPORAN KERUSAKAN (Damage Reports)
        |--------------------------------------------------------------------------
        */
        $damages = [
            [
                'inv'        => 'INV-2026-004',
                'tanggal'    => '2026-02-10',
                'deskripsi'  => 'AC unit 2 di Ruang Guru bunyi berisik dan tidak dingin. Kemungkinan kompresor bermasalah.',
                'tingkat'    => 'sedang',
                'status'     => 'diperbaiki',
                'tindakan'   => 'Sudah dipanggil teknisi, perbaikan selesai 15 Februari 2026.',
                'pelapor'    => $pramuB->id,
            ],
            [
                'inv'        => 'INV-2026-009',
                'tanggal'    => '2026-02-20',
                'deskripsi'  => '3 unit komputer Lab Komputer mati total, tidak bisa booting. Dugaan kerusakan motherboard.',
                'tingkat'    => 'berat',
                'status'     => 'dilaporkan',
                'tindakan'   => null,
                'pelapor'    => $invUser2->id,
            ],
            [
                'inv'        => 'INV-2026-005',
                'tanggal'    => '2026-01-15',
                'deskripsi'  => '12 meja siswa kelas XII IPA 2 dan 3 kaki meja patah/goyang.',
                'tingkat'    => 'ringan',
                'status'     => 'diperbaiki',
                'tindakan'   => 'Sudah diperbaiki oleh pramu bakti pada 20 Januari 2026.',
                'pelapor'    => $pramuB->id,
            ],
        ];

        foreach ($damages as $d) {
            $invItem = $createdItems[$d['inv']] ?? null;
            if (!$invItem) continue;

            LaporanKerusakan::updateOrCreate(
                ['inventaris_id' => $invItem->id, 'tanggal_laporan' => $d['tanggal']],
                [
                    'deskripsi_kerusakan' => $d['deskripsi'],
                    'tingkat_kerusakan'   => $d['tingkat'],
                    'status'              => $d['status'],
                    'tindakan'            => $d['tindakan'],
                    'dilaporkan_oleh'     => $d['pelapor'],
                ]
            );
        }
    }
}
