<?php

namespace Database\Seeders;

use App\Models\Pengguna;
use App\Models\LogbookMagang;
use App\Models\KegiatanMagang;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PeranMagangSeeder extends Seeder
{
    public function run(): void
    {
        $today = Carbon::today();

        /*
        |--------------------------------------------------------------------------
        | 1. AKUN STAFF MAGANG
        |--------------------------------------------------------------------------
        */
        $magang = Pengguna::updateOrCreate(
            ['email' => 'magang@tu.test'],
            [
                'nama'                   => 'Andi Pratama',
                'password'               => Hash::make('password'),
                'peran'                  => 'magang',
                'jabatan'                => 'Staff Magang',
                'telepon'                => '081298765432',
                'alamat'                 => 'Jl. Kalimantan No. 10, Kel. Sumbersari, Kec. Sumbersari, Jember',
                'aktif'                  => true,
                'tanggal_lahir'          => '2002-08-15',
                'pembimbing_lapangan'    => 'Drs. Bambang Supriyanto, M.Pd.',
                'instansi_asal'          => 'Universitas Jember',
                'tanggal_mulai_magang'   => $today->copy()->subDays(30)->format('Y-m-d'),
                'tanggal_selesai_magang' => $today->copy()->addDays(60)->format('Y-m-d'),
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | 2. SAMPLE LOGBOOK
        |--------------------------------------------------------------------------
        */
        for ($i = 5; $i >= 1; $i--) {
            LogbookMagang::updateOrCreate(
                [
                    'pengguna_id' => $magang->id,
                    'tanggal'     => $today->copy()->subDays($i)->format('Y-m-d'),
                ],
                [
                    'jam_mulai'      => '08:00',
                    'jam_selesai'    => '16:00',
                    'kegiatan'       => "Kegiatan magang hari ke-" . (31 - $i) . ": membantu administrasi umum dan pendataan arsip.",
                    'hasil'          => 'Input data selesai dan arsip tertata rapi.',
                    'kendala'        => $i % 2 === 0 ? 'Tidak ada kendala.' : 'Sistem sempat lambat saat jam sibuk.',
                    'rencana_besok'  => 'Melanjutkan pendataan dan membantu persuratan.',
                    'status'         => $i > 2 ? 'final' : 'draft',
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 3. SAMPLE KEGIATAN
        |--------------------------------------------------------------------------
        */
        KegiatanMagang::updateOrCreate(
            ['pengguna_id' => $magang->id, 'judul' => 'Input Data Siswa Baru'],
            [
                'deskripsi'       => 'Membantu proses input data siswa baru ke sistem administrasi sekolah.',
                'tanggal_mulai'   => $today->copy()->subDays(10)->format('Y-m-d'),
                'tanggal_selesai' => $today->copy()->subDays(3)->format('Y-m-d'),
                'status'          => 'selesai',
                'prioritas'       => 'tinggi',
                'catatan'         => 'Data 120 siswa berhasil diinput.',
            ]
        );

        KegiatanMagang::updateOrCreate(
            ['pengguna_id' => $magang->id, 'judul' => 'Penataan Arsip Surat'],
            [
                'deskripsi'       => 'Menata dan mendigitalkan arsip surat masuk/keluar tahun 2024.',
                'tanggal_mulai'   => $today->copy()->subDays(5)->format('Y-m-d'),
                'tanggal_selesai' => $today->copy()->addDays(10)->format('Y-m-d'),
                'status'          => 'berlangsung',
                'prioritas'       => 'sedang',
                'catatan'         => null,
            ]
        );

        KegiatanMagang::updateOrCreate(
            ['pengguna_id' => $magang->id, 'judul' => 'Inventarisasi Barang Lab'],
            [
                'deskripsi'       => 'Membuat daftar inventaris barang di laboratorium komputer.',
                'tanggal_mulai'   => $today->copy()->addDays(5)->format('Y-m-d'),
                'tanggal_selesai' => null,
                'status'          => 'belum_mulai',
                'prioritas'       => 'rendah',
                'catatan'         => null,
            ]
        );
    }
}
