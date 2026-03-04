<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // 1. Akun pengguna & pengaturan kehadiran
            AkunSeeder::class,

            // 2. Data kehadiran
            KehadiranSeeder::class,

            // 3. Izin, laporan, acara, notifikasi
            AktivitasSeeder::class,

            // 4. Dokumen & surat
            DokumenArsipSeeder::class,

            // 5. SKP
            SkpSeeder::class,

            // 6. Template dokumen (10 template surat/SK/notulen/dll)
            TemplateDokumenSeeder::class,

            // 7. Keuangan (anggaran + catatan keuangan)
            KeuanganSeeder::class,

            // 8. Inventaris & laporan kerusakan
            InventarisSeeder::class,

            // 9. Kurikulum (silabus, RPP, modul ajar, dll)
            KurikulumSeeder::class,

            // 10. Data siswa, prestasi, pelanggaran
            SiswaSeeder::class,

            // 11. Akreditasi & EDS
            AkreditasiSeeder::class,

            // 12. Evaluasi (PKG, P5, STAR, bukti fisik, metode pembelajaran)
            EvaluasiSeeder::class,

            // 13. Dokumen Word AI, pengingat, catatan beranda, ucapan ultah
            FiturTambahanSeeder::class,
        ]);
    }
}
