# Panduan Google Drive Backup

> **Versi:** 1.0  
> **Terakhir diperbarui:** 4 Maret 2026  
> **Aplikasi:** Sistem Administrasi Tata Usaha — SMA Negeri 2 Jember

---

## Daftar Isi

1. [Tentang Fitur Backup](#1-tentang-fitur-backup)
2. [Persiapan Google Cloud](#2-persiapan-google-cloud)
3. [Konfigurasi Aplikasi](#3-konfigurasi-aplikasi)
4. [Autentikasi Google Drive](#4-autentikasi-google-drive)
5. [Menjalankan Backup](#5-menjalankan-backup)
6. [Jadwal Otomatis](#6-jadwal-otomatis)
7. [Arsip di Database](#7-arsip-di-database)
8. [Troubleshooting](#8-troubleshooting)

---

## 1. Tentang Fitur Backup

Sistem ini memiliki fitur backup otomatis yang menyimpan:

- **Database** (MySQL dump atau PHP export sebagai fallback)
- **File uploads** (foto, dokumen yang diunggah pengguna)

Backup dikompres menjadi file `.zip` lalu diunggah ke **Google Drive**. Setiap backup juga dicatat di tabel `arsip_cadangan` di database.

### Alur Backup

```
[artisan backup:google-drive]
        ↓
  1. Dump database → .sql/.json
  2. Kumpulkan file uploads
  3. Kompres semua → .zip
  4. Catat di tabel arsip_cadangan (status: sedang_proses)
  5. Upload ke Google Drive
  6. Update status (berhasil / gagal)
  7. Hapus backup lama (maks 5 di Drive)
  8. Bersihkan file temp lokal
```

---

## 2. Persiapan Google Cloud

### 2.1. Buat Project di Google Cloud Console

1. Buka [https://console.cloud.google.com/](https://console.cloud.google.com/)
2. Klik **"Select a project"** → **"New Project"**
3. Isi nama project: `SIMPEG-SMART Backup`
4. Klik **Create**

### 2.2. Aktifkan Google Drive API

1. Di Google Cloud Console, buka **APIs & Services** → **Library**
2. Cari **"Google Drive API"**
3. Klik **Enable**

### 2.3. Buat OAuth 2.0 Credentials

1. Buka **APIs & Services** → **Credentials**
2. Klik **"+ Create Credentials"** → **"OAuth client ID"**
3. Jika diminta, konfigurasi **OAuth Consent Screen** dulu:
   - User Type: **External** (atau Internal jika pakai Google Workspace)
   - App Name: `SIMPEG-SMART Backup`  
   - User support email: email admin
   - Authorized domains: kosongkan
   - Klik **Save and Continue** sampai selesai
4. Kembali ke **Credentials** → **Create OAuth client ID**:
   - Application type: **Desktop app**
   - Name: `TU Admin CLI`
   - Klik **Create**
5. **Download file JSON** (tombol download di kanan)

### 2.4. Simpan File Credentials

Pindahkan file yang didownload ke:

```
storage/app/google/credentials.json
```

> ⚠️ **Jangan commit file ini ke Git!** File ini berisi rahasia OAuth.

---

## 3. Konfigurasi Aplikasi

### 3.1. File `.env`

Tambahkan variabel berikut ke `.env`:

```env
# Nama folder backup di Google Drive
GOOGLE_DRIVE_BACKUP_FOLDER=TU_Admin_Backup

# Jadwal backup otomatis (daily / weekly / hourly)
BACKUP_SCHEDULE=daily
```

### 3.2. File `config/backup.php`

Konfigurasi lengkap ada di `config/backup.php`:

| Key | Default | Keterangan |
|-----|---------|------------|
| `local_path` | `storage/app/backups` | Folder temp untuk backup lokal |
| `keep_count` | `5` | Jumlah backup yang disimpan di Drive |
| `google_drive.credentials_path` | `storage/app/google/credentials.json` | File OAuth credentials |
| `google_drive.token_path` | `storage/app/google/token.json` | Token akses (dibuat otomatis) |
| `google_drive.folder_name` | `TU_Admin_Backup` | Nama folder di Google Drive |
| `schedule` | `daily` | Jadwal backup cron |
| `folders` | `[storage/app/public]` | Folder yang di-backup |

---

## 4. Autentikasi Google Drive

Setelah credentials disimpan, jalankan perintah autentikasi:

```bash
php artisan backup:auth
```

Perintah ini akan:
1. Membuka URL autentikasi Google di terminal
2. Anda perlu membuka URL tersebut di browser
3. Login dengan akun Google yang akan menyimpan backup
4. Izinkan akses ke Google Drive
5. Salin kode otorisasi dan paste di terminal
6. Token akan disimpan otomatis di `storage/app/google/token.json`

> 💡 Proses ini hanya perlu dilakukan **sekali**. Token akan di-refresh otomatis.

---

## 5. Menjalankan Backup

### 5.1. Backup Lengkap (Database + File)

```bash
php artisan backup:google-drive
```

### 5.2. Hanya Database

```bash
php artisan backup:google-drive --db-only
```

### 5.3. Hanya File Uploads

```bash
php artisan backup:google-drive --files-only
```

### 5.4. Tanpa Hapus Backup Lama

```bash
php artisan backup:google-drive --no-cleanup
```

### Contoh Output

```
🔄 Memulai proses backup...
📦 Backup database...
  ✅ Database berhasil di-dump: db_2026-03-04_140000.sql
📁 Backup file uploads...
  ✅ Folder: public
📦 Backup lokal dibuat: backup_2026-03-04_140000.zip (12.45 MB)
☁️  Mengunggah ke Google Drive...
✅ Berhasil diunggah ke Google Drive (ID: 1AbC2dEfG3hIjKl)
🗑️  1 backup lama dihapus dari Google Drive
🎉 Proses backup selesai!
```

---

## 6. Jadwal Otomatis

### 6.1. Menggunakan Laravel Scheduler

Backup dijadwalkan otomatis melalui `routes/console.php` atau `app/Console/Kernel.php`.

Pastikan cron job Laravel berjalan di server:

```bash
* * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
```

Di Windows (Task Scheduler), buat task dengan:
- Program: `php`
- Arguments: `artisan schedule:run`
- Start in: `C:\laragon\www\ut adminisitrasi`
- Trigger: Every 1 minute

### 6.2. Opsi Jadwal

Atur di `.env`:

| Nilai | Keterangan |
|-------|------------|
| `daily` | Sekali sehari (default, jam 01:00) |
| `weekly` | Sekali seminggu (Senin jam 01:00) |
| `hourly` | Setiap jam |

---

## 7. Arsip di Database

Setiap backup dicatat di tabel **`arsip_cadangan`**:

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `id` | bigint | Primary key |
| `nama_file` | string | Nama file ZIP backup |
| `google_drive_id` | string | ID file di Google Drive (null jika lokal) |
| `path_lokal` | string | Path file lokal (null jika sudah dihapus) |
| `jenis` | enum | `penuh`, `database`, atau `file` |
| `ukuran_byte` | bigint | Ukuran file dalam byte |
| `status` | enum | `berhasil`, `gagal`, atau `sedang_proses` |
| `catatan` | text | Catatan tambahan (error message, dll) |
| `pengguna_id` | FK | ID pengguna yang menjalankan (opsional) |
| `created_at` | timestamp | Waktu backup dibuat |

### Model: `BackupArchive`

```php
use App\Models\BackupArchive;

// Ambil semua backup yang berhasil
$backups = BackupArchive::berhasil()->latest()->get();

// Format ukuran
$backup->ukuranFormat; // "12.45 MB"
```

---

## 8. Troubleshooting

### Masalah Umum

| Masalah | Solusi |
|---------|--------|
| "credentials file not found" | Pastikan `storage/app/google/credentials.json` ada |
| "No token file found" | Jalankan `php artisan backup:auth` |
| "Token expired" | Token otomatis di-refresh. Jika gagal, jalankan ulang `backup:auth` |
| "Upload gagal" | Cek koneksi internet server dan quota Google Drive |
| "mysqldump tidak tersedia" | Sistem akan otomatis fallback ke PHP export (JSON) |
| "Folder backup penuh" | Sistem hanya simpan 5 file terakhir (konfigurabel via `keep_count`) |

### Memeriksa Log

```bash
# Linux/Mac
tail -f storage/logs/laravel.log | grep -i "backup\|google"

# Windows PowerShell
Get-Content storage/logs/laravel.log -Tail 50 | Select-String "backup|google" -CaseSensitive:$false
```

### Cek Status Backup di Database

```bash
php artisan tinker
>>> App\Models\BackupArchive::latest()->take(5)->get(['nama_file','status','created_at'])
```

---

## Arsitektur Teknis

```
app/Console/Commands/BackupGoogleDrive.php   → Artisan command utama
app/Console/Commands/BackupAuth.php          → Command untuk autentikasi OAuth
app/Services/GoogleDriveBackupService.php    → Service Google Drive API
app/Models/BackupArchive.php                 → Model Eloquent arsip backup
config/backup.php                            → Konfigurasi backup
database/migrations/..._create_backup_archives_table.php → Migrasi tabel arsip

Lokasi File:
├── storage/app/google/credentials.json      → OAuth credentials (JANGAN commit)
├── storage/app/google/token.json            → Access token (dibuat otomatis)
└── storage/app/backups/                     → File backup sementara
```
