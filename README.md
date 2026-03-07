# TU Administrasi - SMA Negeri 2 Jember

Aplikasi Laravel untuk manajemen administrasi Tata Usaha dengan multi-peran:
`admin`, `kepala_sekolah`, dan seluruh role staf (`kepegawaian`, `pramu_bakti`, `keuangan`, `persuratan`, `perpustakaan`, `inventaris`, `kesiswaan_kurikulum`, `staff`).

## Menjalankan Proyek

1. Clone repo dan masuk folder proyek.
2. Install dependency:
```bash
composer install
npm install
```
3. Salin file environment:
```bash
cp .env.example .env
```
4. Atur koneksi database di `.env`.
5. Generate key dan jalankan migrasi + seeder:
```bash
php artisan key:generate
php artisan migrate --seed
```
6. Jalankan server:
```bash
php artisan serve
```

## Seeder Data

Seeder utama ada di `database/seeders/DatabaseSeeder.php` dan menjalankan seeder berbasis peran.

Seeder penting:
- `PeranAdminSeeder`
- `PeranKepalaSekolahSeeder`
- `PeranKepegawaianSeeder`
- `PeranPramuBaktiSeeder`
- `PeranKeuanganSeeder`
- `PeranPersuratanSeeder`
- `PeranPerpustakaanSeeder`
- `PeranInventarisSeeder`
- `PeranKesiswaanKurikulumSeeder`
- `KontenPublikSeeder`

## Workflow GitHub (Deteksi Error)

Tersedia dua workflow di `.github/workflows`:

1. `laravel-error-check.yml`
- Syntax check PHP (`php -l`)
- Compile check Blade
- Route list check
- Migrasi database testing
- Menjalankan test Laravel
- Upload artifact diagnostik log

2. `github-ai-codeql.yml`
- Analisis keamanan berbasis CodeQL untuk PHP
- Membantu deteksi potensi bug/kerentanan di pull request dan push

## Pembaruan Terbaru (2026-03-06)

- Perbaikan error cache view terkait referensi model event (`optimize:clear`).
- Penambahan modul `Kinerja` khusus staf dengan layout dashboard (`header`, `sidebar`, `footer`):
  - `GET /staf/kinerja`
  - `GET /staf/kinerja/{kinerja}`
  - `GET /staf/kinerja/{kinerja}/unduh`
- Akses staf untuk modul kinerja bersifat baca dan unduh (tanpa edit/hapus).
- Admin tetap mengelola konten melalui CRUD `admin/halaman-publik`.
- Penambahan `KontenPublikSeeder` agar data awal halaman kinerja tersedia.
- Penguatan CI GitHub untuk audit error dan analisis CodeQL.

## Catatan

Untuk pengembangan lokal, jalankan:
```bash
php artisan optimize:clear
php artisan route:list
php artisan test
```
