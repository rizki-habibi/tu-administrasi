<?php

namespace Database\Seeders;

use App\Models\CatatanHarian;
use App\Models\DisposisiSurat;
use App\Models\LogAktivitas;
use App\Models\Pengguna;
use App\Models\Resolusi;
use App\Models\SaranPengunjung;
use App\Models\Surat;
use Illuminate\Database\Seeder;

class FiturAuditSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Pengguna::where('peran', 'admin')->first();
        $kepsek = Pengguna::where('peran', 'kepala_sekolah')->first();
        $staff = Pengguna::whereIn('peran', Pengguna::STAFF_ROLES)->first();

        if ($admin) {
            SaranPengunjung::updateOrCreate(
                ['nama' => 'Pengunjung Demo', 'subjek' => 'Peningkatan Layanan TU'],
                [
                    'email' => 'pengunjung.demo@example.com',
                    'pesan' => 'Mohon ditambahkan informasi jam layanan pada halaman publik agar lebih jelas bagi orang tua/wali.',
                    'status' => 'baru',
                ]
            );
        }

        if ($kepsek) {
            Resolusi::updateOrCreate(
                ['judul' => 'Penetapan Standar Waktu Layanan Administrasi'],
                [
                    'nomor_resolusi' => Resolusi::generateNomor(),
                    'latar_belakang' => 'Perlu standarisasi waktu layanan untuk meningkatkan kepuasan pengguna layanan tata usaha.',
                    'isi_keputusan' => 'Menetapkan standar waktu layanan administrasi maksimal 2 hari kerja untuk dokumen non-khusus.',
                    'tindak_lanjut' => 'Setiap unit wajib melakukan monitoring mingguan terhadap ketercapaian standar layanan.',
                    'kategori' => 'kebijakan',
                    'status' => 'berlaku',
                    'tanggal_berlaku' => now()->toDateString(),
                    'tanggal_berakhir' => null,
                    'dibuat_oleh' => $kepsek->id,
                ]
            );
        }

        if ($staff) {
            CatatanHarian::updateOrCreate(
                [
                    'pengguna_id' => $staff->id,
                    'tanggal' => now()->toDateString(),
                ],
                [
                    'kegiatan' => 'Input data surat masuk, verifikasi berkas, dan pembaruan arsip digital.',
                    'hasil' => 'Data surat masuk hari ini telah terarsip 100 persen.',
                    'kendala' => 'Koneksi internet sempat tidak stabil pada jam 10.00-10.30.',
                    'rencana_besok' => 'Melanjutkan validasi dokumen dan sinkronisasi data inventaris.',
                    'status' => 'final',
                ]
            );
        }

        if ($admin && $staff) {
            $surat = Surat::query()->first();
            if ($surat) {
                DisposisiSurat::firstOrCreate(
                    [
                        'surat_id' => $surat->id,
                        'dari_pengguna_id' => $admin->id,
                        'kepada_pengguna_id' => $staff->id,
                    ],
                    [
                        'instruksi' => 'Mohon diproses dan ditindaklanjuti sesuai SOP layanan persuratan.',
                        'prioritas' => 'sedang',
                        'tenggat' => now()->addDays(2)->toDateString(),
                        'status' => 'belum_dibaca',
                    ]
                );
            }

            LogAktivitas::firstOrCreate(
                [
                    'pengguna_id' => $admin->id,
                    'aksi' => 'seed',
                    'modul' => 'database',
                    'deskripsi' => 'Seeding data audit fitur tambahan.',
                ],
                [
                    'model_type' => null,
                    'model_id' => null,
                    'data_lama' => null,
                    'data_baru' => ['sumber' => 'FiturAuditSeeder'],
                    'ip_address' => '127.0.0.1',
                    'user_agent' => 'artisan-seeder',
                ]
            );
        }
    }
}
