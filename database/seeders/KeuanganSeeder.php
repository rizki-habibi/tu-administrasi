<?php

namespace Database\Seeders;

use App\Models\Anggaran;
use App\Models\CatatanKeuangan;
use App\Models\Pengguna;
use Illuminate\Database\Seeder;

class KeuanganSeeder extends Seeder
{
    public function run(): void
    {
        $admin   = Pengguna::where('email', 'admin@tu.test')->firstOrFail();
        $finance = Pengguna::where('email', 'ike.keuangan@tu.test')->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | 1. ANGGARAN (Budgets)
        |--------------------------------------------------------------------------
        */
        $anggaranData = [
            [
                'nama_anggaran'  => 'Dana BOS Reguler 2026',
                'tahun_anggaran' => '2026',
                'sumber_dana'    => 'BOS Reguler',
                'total_anggaran' => 1_200_000_000,
                'terpakai'       => 450_000_000,
                'status'         => 'aktif',
                'keterangan'     => 'Anggaran BOS reguler TA 2025/2026 dari Kemendikbud.',
            ],
            [
                'nama_anggaran'  => 'Dana BOS Kinerja 2026',
                'tahun_anggaran' => '2026',
                'sumber_dana'    => 'BOS Kinerja',
                'total_anggaran' => 300_000_000,
                'terpakai'       => 75_000_000,
                'status'         => 'aktif',
                'keterangan'     => 'Dana BOS kinerja berdasarkan capaian mutu sekolah.',
            ],
            [
                'nama_anggaran'  => 'Dana Komite Sekolah 2026',
                'tahun_anggaran' => '2026',
                'sumber_dana'    => 'Komite Sekolah',
                'total_anggaran' => 500_000_000,
                'terpakai'       => 120_000_000,
                'status'         => 'aktif',
                'keterangan'     => 'Sumbangan sukarela orang tua siswa melalui komite.',
            ],
            [
                'nama_anggaran'  => 'Dana BOS Reguler 2025',
                'tahun_anggaran' => '2025',
                'sumber_dana'    => 'BOS Reguler',
                'total_anggaran' => 1_150_000_000,
                'terpakai'       => 1_140_000_000,
                'status'         => 'selesai',
                'keterangan'     => 'Anggaran BOS tahun sebelumnya, hampir terserap penuh.',
            ],
        ];

        foreach ($anggaranData as $a) {
            Anggaran::updateOrCreate(
                ['nama_anggaran' => $a['nama_anggaran'], 'tahun_anggaran' => $a['tahun_anggaran']],
                array_merge($a, ['dibuat_oleh' => $finance->id])
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 2. CATATAN KEUANGAN (Finance Records)
        |--------------------------------------------------------------------------
        */
        $records = [
            // Pemasukan
            ['kode' => 'TRX-2026-001', 'jenis' => 'pemasukan', 'kategori' => 'bos_reguler',   'uraian' => 'Penerimaan BOS Reguler Triwulan I', 'jumlah' => 300_000_000, 'tgl' => '2026-01-15', 'status' => 'diverifikasi'],
            ['kode' => 'TRX-2026-002', 'jenis' => 'pemasukan', 'kategori' => 'bos_kinerja',   'uraian' => 'Penerimaan BOS Kinerja Triwulan I', 'jumlah' => 75_000_000,  'tgl' => '2026-01-20', 'status' => 'diverifikasi'],
            ['kode' => 'TRX-2026-003', 'jenis' => 'pemasukan', 'kategori' => 'komite',        'uraian' => 'Iuran Komite Semester Genap',        'jumlah' => 120_000_000, 'tgl' => '2026-02-01', 'status' => 'diverifikasi'],

            // Pengeluaran
            ['kode' => 'TRX-2026-004', 'jenis' => 'pengeluaran', 'kategori' => 'gaji',        'uraian' => 'Honor GTT dan PTT Januari 2026',       'jumlah' => 85_000_000,  'tgl' => '2026-01-25', 'status' => 'diverifikasi'],
            ['kode' => 'TRX-2026-005', 'jenis' => 'pengeluaran', 'kategori' => 'operasional', 'uraian' => 'Pembayaran Listrik & Air Januari',      'jumlah' => 12_500_000,  'tgl' => '2026-01-28', 'status' => 'diverifikasi'],
            ['kode' => 'TRX-2026-006', 'jenis' => 'pengeluaran', 'kategori' => 'operasional', 'uraian' => 'ATK dan Perlengkapan Kantor',           'jumlah' => 8_750_000,   'tgl' => '2026-02-05', 'status' => 'diverifikasi'],
            ['kode' => 'TRX-2026-007', 'jenis' => 'pengeluaran', 'kategori' => 'kegiatan',    'uraian' => 'Biaya Class Meeting Semester Ganjil',   'jumlah' => 15_000_000,  'tgl' => '2026-01-10', 'status' => 'diverifikasi'],
            ['kode' => 'TRX-2026-008', 'jenis' => 'pengeluaran', 'kategori' => 'pemeliharaan','uraian' => 'Perbaikan AC Ruang Guru',               'jumlah' => 4_500_000,   'tgl' => '2026-02-12', 'status' => 'draft'],
            ['kode' => 'TRX-2026-009', 'jenis' => 'pengeluaran', 'kategori' => 'gaji',        'uraian' => 'Honor GTT dan PTT Februari 2026',      'jumlah' => 85_000_000,  'tgl' => '2026-02-25', 'status' => 'diverifikasi'],
            ['kode' => 'TRX-2026-010', 'jenis' => 'pengeluaran', 'kategori' => 'operasional', 'uraian' => 'Pembayaran Listrik & Air Februari',     'jumlah' => 11_800_000,  'tgl' => '2026-02-28', 'status' => 'draft'],
        ];

        foreach ($records as $r) {
            CatatanKeuangan::updateOrCreate(
                ['kode_transaksi' => $r['kode']],
                [
                    'jenis'              => $r['jenis'],
                    'kategori'           => $r['kategori'],
                    'uraian'             => $r['uraian'],
                    'jumlah'             => $r['jumlah'],
                    'tanggal'            => $r['tgl'],
                    'status'             => $r['status'],
                    'keterangan'         => null,
                    'dibuat_oleh'        => $finance->id,
                    'diverifikasi_oleh'  => $r['status'] === 'diverifikasi' ? $admin->id : null,
                ]
            );
        }
    }
}
