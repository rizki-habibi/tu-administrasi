<?php

namespace Database\Seeders;

use App\Models\Pengguna;
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
            // ═══════════════════════════════════════════════════
            // SEEDER BERDASARKAN PERAN (Role-Based)
            // Setiap peran membawa akun + seluruh fitur miliknya
            // ═══════════════════════════════════════════════════

            // 1. Admin / KaTU (HARUS pertama — peran lain referensi admin)
            PeranAdminSeeder::class,

            // 1b. Konten halaman publik (landing/kinerja)
            KontenPublikSeeder::class,

            // 2. Kepala Sekolah
            PeranKepalaSekolahSeeder::class,

            // 3. IKI 1 — Kepegawaian
            PeranKepegawaianSeeder::class,

            // 4. IKI 2 — Pramu Bakti
            PeranPramuBaktiSeeder::class,

            // 5. IKI 3 — Keuangan
            PeranKeuanganSeeder::class,

            // 6. IKI 4 — Persuratan
            PeranPersuratanSeeder::class,

            // 7. IKI 5 — Perpustakaan
            PeranPerpustakaanSeeder::class,

            // 8. IKI 6 — Inventaris
            PeranInventarisSeeder::class,

            // 9. IKI 7 — Kesiswaan & Kurikulum
            PeranKesiswaanKurikulumSeeder::class,

            // 10. Seed data audit fitur publik & modul baru
            FiturAuditSeeder::class,
            // 11. Staff Magang
            PeranMagangSeeder::class,

            // 12. Berita / Konten Halaman Utama
            BeritaSeeder::class,
        ]);
    }
}
