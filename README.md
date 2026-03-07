# SIATU — Sistem Informasi Administrasi Tata Usaha

> Aplikasi manajemen administrasi Tata Usaha SMA Negeri 2 Jember berbasis Laravel 12 dengan multi-peran dan integrasi AI.

## Tech Stack

| Komponen | Versi |
|---|---|
| PHP | ^8.2 |
| Laravel | ^12.0 |
| Database | MySQL / SQLite |
| Frontend | Bootstrap 5.3, Bootstrap Icons 1.11, Poppins, SweetAlert2 |
| Build | Vite 7, Sass |
| Paket Utama | `laravel/ui`, `laravel/socialite`, `phpoffice/phpword`, `firebase/php-jwt` |
| AI | Google Gemini API (via `LayananGeminiAi`) |
| Cloud | Google Drive Backup (via `LayananCadanganGoogleDrive`) |

## Peran Pengguna

| Peran | Prefix Rute | Tema |
|---|---|---|
| Admin (Kepala TU) | `/admin` | Indigo |
| Kepala Sekolah | `/kepala-sekolah` | Amber |
| Staf Kepegawaian | `/staf` | Hijau |
| Staf Keuangan | `/staf` | Hijau |
| Staf Persuratan | `/staf` | Hijau |
| Staf Perpustakaan | `/staf` | Hijau |
| Staf Inventaris/Sarpras | `/staf` | Hijau |
| Staf Kesiswaan & Kurikulum | `/staf` | Hijau |
| Pramu Bakti | `/staf` | Hijau |
| Staf Umum | `/staf` | Hijau |

Middleware `MiddlewarePeran` mengatur akses berbasis peran. Staf menggunakan prefix `/staf` dengan sub-file rute sesuai bidang.

## Fitur Utama

### Administrasi Umum
- **Manajemen Pegawai** — CRUD, impor/ekspor, toggle status aktif
- **Kehadiran** — Clock in/out, laporan, pengaturan jam kerja, ekspor
- **Pengajuan Izin** — Pengajuan, persetujuan/penolakan oleh Kepala Sekolah
- **SKP** — Sasaran Kinerja Pegawai dengan alur persetujuan
- **Surat Menyurat** — Pengelolaan surat masuk/keluar dengan disposisi
- **Disposisi Surat** — Delegasi surat kepada staf terkait
- **Dokumen & Arsip** — Manajemen dokumen dengan ekspor

### Akademik & Kesiswaan
- **Kurikulum** — Manajemen dokumen kurikulum
- **Kesiswaan** — Data siswa, pelanggaran, prestasi
- **Evaluasi Kinerja** — PKG, PenilaianP5, Analisis STAR, Bukti Fisik, Metode Pembelajaran

### Sarana & Keuangan
- **Inventaris/Sarpras** — Pelacakan aset, laporan kerusakan
- **Keuangan** — Catatan keuangan, anggaran, verifikasi

### AI & Dokumen Cerdas
- **SIATU-AI Chatbot** — Asisten AI untuk setiap peran (Gemini API)
- **Word-AI** — Pembuatan dokumen otomatis (PHPWord + Gemini)
- **Rekap Eksekutif** — Ringkasan eksekutif dengan analisis AI (Kepala Sekolah)

### Portal Publik
- **Halaman Publik** — Pengelolaan konten publik oleh admin
- **Portal Dokumen** — Portal publik `/dokumen` dengan 9 kategori (Profil, Kinerja, Akademik, Keuangan, Sarana, Kurikulum, Kesiswaan, Tata Usaha, Lainnya)
- **Saran Pengunjung** — Formulir saran dari pengunjung publik

### Komunikasi & Produktivitas
- **Chat/Pesan** — Sistem pesan internal dengan dukungan gambar
- **Notifikasi** — Sistem notifikasi per peran
- **Agenda/Event** — Manajemen acara
- **Pengingat** — Sistem reminder/tugas
- **Ulang Tahun** — Pelacakan & ucapan ulang tahun
- **Catatan Beranda** — Sticky notes di dashboard
- **Catatan Harian** — Jurnal kerja harian (staf)

### Manajemen & Monitoring
- **Resolusi** — Keputusan Kepala Sekolah
- **Panduan** — Manajemen panduan/manual dengan upload Google Drive & cetak/unduh
- **Log Aktivitas** — Audit log aktivitas pengguna
- **Pusat Ekspor** — Ekspor terpusat (pegawai, kehadiran, dokumen)
- **Akreditasi** — Dokumen akreditasi & EDS (Evaluasi Diri Sekolah)
- **Laporan** — Manajemen laporan per peran

### Pengaturan & Backup
- **Profil & Pengaturan** — Profil pengguna, password, preferensi tampilan
- **Google Drive Backup** — Backup otomatis ke Google Drive
- **Dev System Scan** — Diagnostik sistem (lokal saja)

## Struktur Rute

| File Rute | Prefix | Middleware | Estimasi Rute |
|---|---|---|---|
| `routes/web.php` | `/` | `web` | ~17 |
| `routes/admin.php` | `/admin` | `auth, role:admin` | ~105 |
| `routes/staf.php` | `/staf` | `auth, role:all_staff` | ~95 |
| `routes/kepala-sekolah.php` | `/kepala-sekolah` | `auth, role:kepala_sekolah` | ~60 |
| **Total** | | | **~280+** |

Rute staf dimuat dari 8 sub-file di `routes/staf/`:
`umum.php`, `inventaris.php`, `kesiswaan-kurikulum.php`, `kepegawaian.php`, `keuangan.php`, `persuratan.php`, `perpustakaan.php`, `pramu-bakti.php`

## Struktur Proyek

```
app/
├── Console/Commands/          # 3 artisan commands
├── Http/
│   ├── Controllers/
│   │   ├── Admin/             # 25 controllers
│   │   ├── Staff/             # 23 controllers
│   │   ├── Kepsek/            # 18 controllers
│   │   ├── Auth/              # 6 controllers
│   │   └── Shared/            # 2 traits (Pengaturan, Pesan)
│   └── Middleware/
├── Models/                    # 42 models
├── Services/                  # 2 services (Google Drive, Gemini AI)
└── Providers/

resources/views/
├── admin/                     # 26 subdirektori modul
├── staf/                      # 24 subdirektori modul
├── kepala-sekolah/            # 19 subdirektori modul
├── dokumen/                   # Portal publik (5 views)
├── layouts/                   # Layout dokumen publik
├── peran/                     # Sidebar & header per peran
├── komponen/                  # Komponen shared
├── autentikasi/               # Halaman autentikasi
└── dev/                       # System scan

database/
├── migrations/                # 29 migration files
└── seeders/                   # 27 seeders (termasuk 9 peran)
```

## Menjalankan Proyek

```bash
# 1. Clone dan masuk folder proyek
git clone https://github.com/rizki-habibi/tu-administrasi.git
cd tu-administrasi

# 2. Install dependency
composer install
npm install

# 3. Salin dan atur environment
cp .env.example .env
# Edit .env → atur DB_*, GEMINI_API_KEY, GOOGLE_DRIVE_*

# 4. Generate key, migrasi, dan seed
php artisan key:generate
php artisan migrate --seed

# 5. Build asset & jalankan server
npm run build
php artisan serve
```

## Seeder Data

Seeder utama: `database/seeders/DatabaseSeeder.php`

| Seeder | Keterangan |
|---|---|
| `AkunSeeder` | Akun default per peran |
| `PeranAdminSeeder` | Data khusus admin |
| `PeranKepalaSekolahSeeder` | Data khusus kepala sekolah |
| `PeranKepegawaianSeeder` — `PeranPramuBaktiSeeder` | Data per bidang staf (7 seeder) |
| `KontenPublikSeeder` | Konten portal dokumen publik |
| `PanduanSeeder` | Data panduan awal |
| `KehadiranSeeder`, `SkpSeeder`, `EvaluasiSeeder` | Data operasional |
| `InventarisSeeder`, `KeuanganSeeder`, `DokumenArsipSeeder` | Data aset & keuangan |
| `KurikulumSeeder`, `SiswaSeeder`, `AkreditasiSeeder` | Data akademik |
| `TemplateDokumenSeeder` | Template dokumen Word-AI |

## Workflow GitHub (CI)

### 1. `laravel-error-check.yml`
- Syntax check PHP (`php -l`)
- Compile check Blade
- Route list check
- Migrasi database testing
- Menjalankan test Laravel
- Upload artifact diagnostik log

### 2. `github-ai-codeql.yml`
- Analisis keamanan berbasis CodeQL untuk PHP
- Deteksi potensi bug/kerentanan di pull request dan push

## Dokumentasi Tambahan

| Dokumen | Keterangan |
|---|---|
| `docs/DATABASE-DAN-PERAN.md` | Skema database & definisi peran |
| `docs/PANDUAN-AI.md` | Panduan integrasi AI |
| `docs/PANDUAN-GOOGLE-DRIVE.md` | Setup Google Drive |
| `docs/PANDUAN-PENGGUNAAN.md` | Panduan penggunaan umum |
| `docs/USE-CASE-DIAGRAM.md` | Diagram use case |
| `docs/REKOMENDASI-API-AI.md` | Rekomendasi API AI |

## Perintah Berguna

```bash
php artisan optimize:clear      # Bersihkan semua cache
php artisan route:list           # Daftar semua rute
php artisan test                 # Jalankan test
php artisan migrate:fresh --seed # Reset database + seed
npm run dev                      # Development mode (Vite)
npm run build                    # Build untuk produksi
```

## Lisensi

Hak Cipta © 2026 — SMA Negeri 2 Jember. Internal use only.
