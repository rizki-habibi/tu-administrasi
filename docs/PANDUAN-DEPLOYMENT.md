# 🚀 Panduan Deployment — Hosting Gratis untuk Lomba

Panduan ini berisi rekomendasi hosting gratis yang mendukung Laravel + MySQL/PostgreSQL + fitur cloud storage untuk keperluan lomba/kompetisi.

---

## 📋 Rekomendasi Hosting Gratis

### 1. **Railway.app** ⭐ (Paling Direkomendasikan)
- **URL**: https://railway.app
- **Fitur Gratis**: $5 free credit/bulan (cukup untuk demo & lomba)
- **Database**: MySQL & PostgreSQL tersedia
- **Storage**: Persistent volume
- **Deploy**: Git push otomatis dari GitHub
- **Kelebihan**: Setup termudah, support Laravel native, ada MySQL
- **Langkah**:
  1. Daftar akun di railway.app (pakai GitHub)
  2. Buat project baru → "Deploy from GitHub Repo"
  3. Tambah service MySQL dari marketplace
  4. Set environment variables (lihat bagian `.env` di bawah)
  5. Railway otomatis deteksi Laravel & deploy

### 2. **Render.com**
- **URL**: https://render.com
- **Fitur Gratis**: Free tier Web Service + PostgreSQL 256MB
- **Database**: PostgreSQL gratis (expire 90 hari, bisa recreate)
- **Deploy**: Otomatis dari GitHub
- **Kelebihan**: Cukup stabil, support background worker
- **Catatan**: Perlu ubah driver database ke PostgreSQL di `.env`

### 3. **Fly.io**
- **URL**: https://fly.io
- **Fitur Gratis**: 3 shared VMs, 1GB persistent volume
- **Database**: Bisa pakai PostgreSQL (Fly Postgres) atau MySQL external
- **Deploy**: Via CLI `flyctl deploy`
- **Kelebihan**: Performa bagus, bisa pilih region Asia

### 4. **Vercel + PlanetScale** (Frontend + API)
- **URL**: https://vercel.com + https://planetscale.com
- **Catatan**: Vercel cocok untuk frontend, tapi Laravel backend perlu serverless adapter

### 5. **InfinityFree / 000webhost** (Shared Hosting Tradisional)
- **URL**: https://infinityfree.com atau https://www.000webhost.com
- **Fitur**: PHP + MySQL gratis, cPanel
- **Kelebihan**: Familiar, support PHP native
- **Kekurangan**: Performa rendah, tidak support `php artisan`, perlu upload manual via FTP

---

## 🗄️ Database Cloud Gratis

### PostgreSQL
| Layanan | Limit Gratis | URL |
|---------|-------------|-----|
| **Neon.tech** | 512MB, 1 project | https://neon.tech |
| **Supabase** | 500MB, 2 project | https://supabase.com |
| **ElephantSQL** | 20MB (demo) | https://elephantsql.com |
| **Render** | 256MB, expire 90 hari | https://render.com |

### MySQL
| Layanan | Limit Gratis | URL |
|---------|-------------|-----|
| **Railway** | Termasuk dalam $5 credit | https://railway.app |
| **TiDB Cloud** | 5GB, 1 cluster | https://tidbcloud.com |
| **Aiven** | Free tier MySQL | https://aiven.io |

### pgAdmin4 Online
- Gunakan **Supabase** → sudah ada dashboard mirip pgAdmin
- Atau install pgAdmin4 lokal lalu koneksikan ke database cloud
- URL: https://www.pgadmin.org/download/

---

## ☁️ Cloud Storage / Google Drive Backup

Sistem sudah memiliki integrasi Google Drive via `LayananCadanganGoogleDrive`. Untuk mengaktifkan:

1. Buat project di [Google Cloud Console](https://console.cloud.google.com)
2. Aktifkan Google Drive API
3. Buat Service Account & download key JSON
4. Set di `.env`:
```env
GOOGLE_DRIVE_CLIENT_ID=xxx
GOOGLE_DRIVE_CLIENT_SECRET=xxx
GOOGLE_DRIVE_REFRESH_TOKEN=xxx
GOOGLE_DRIVE_FOLDER_ID=xxx
```

### Cloud Storage Alternatif (Gratis)
- **Cloudinary**: 25GB free (gambar & file)
- **AWS S3 Free Tier**: 5GB (12 bulan pertama)
- **Backblaze B2**: 10GB gratis forever
- **Supabase Storage**: 1GB gratis

---

## ⚙️ Konfigurasi `.env` untuk Deployment

```env
# ── App ──
APP_NAME="SIMPEG-SMART"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-app.railway.app

# ── Database (Railway MySQL) ──
DB_CONNECTION=mysql
DB_HOST=containers-us-west-xxx.railway.app
DB_PORT=3306
DB_DATABASE=railway
DB_USERNAME=root
DB_PASSWORD=xxxxx

# ── Database (Neon PostgreSQL) ──
# DB_CONNECTION=pgsql
# DB_HOST=ep-xxx.us-east-2.aws.neon.tech
# DB_PORT=5432
# DB_DATABASE=neondb
# DB_USERNAME=xxx
# DB_PASSWORD=xxx

# ── Mail (Gmail SMTP untuk notifikasi email) ──
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=email@gmail.com
MAIL_PASSWORD=xxxx-xxxx-xxxx-xxxx
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=email@gmail.com
MAIL_FROM_NAME="SIMPEG-SMART"

# ── Storage Limit (GB) untuk monitoring ──
STORAGE_LIMIT_GB=1

# ── Session & Cache ──
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
```

---

## 🔧 Langkah Deploy ke Railway (Step-by-Step)

### 1. Persiapan Lokal
```bash
# Pastikan .gitignore sudah benar (vendor/, node_modules/, .env tidak di-commit)
# Buat Procfile di root project
echo "web: php artisan serve --host=0.0.0.0 --port=$PORT" > Procfile

# Commit semua perubahan
git add -A
git commit -m "Ready for deployment"
git push origin main
```

### 2. Setup Railway
1. Login ke https://railway.app dengan akun GitHub
2. Klik **"New Project"** → **"Deploy from GitHub Repo"**
3. Pilih repository
4. Klik **"Add Service"** → **"MySQL"** (dari marketplace)
5. Klik service Laravel → **"Variables"** → tambahkan semua env variables
6. Railway otomatis menjalankan `composer install` dan deploy

### 3. Jalankan Migrasi
Di Railway dashboard → service Laravel → klik **"Settings"** → **"Start Command"**:
```
php artisan migrate --force; php artisan serve --host=0.0.0.0 --port=$PORT
```

Atau gunakan **Railway CLI**:
```bash
railway run php artisan migrate --force
railway run php artisan db:seed --force
```

### 4. Generate App Key
```bash
railway run php artisan key:generate --force
```

---

## 📱 Fitur Push Notification di Production

Untuk push notification browser bekerja di production:

1. **Generate VAPID Keys**:
```bash
# Install web-push library (opsional, untuk generate key)
# Atau gunakan online generator: https://vapidkeys.com
```

2. Tambahkan ke `.env`:
```env
VAPID_PUBLIC_KEY=Bxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
VAPID_PRIVATE_KEY=xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
```

3. Pastikan site menggunakan **HTTPS** (Railway sudah otomatis HTTPS)

---

## 📊 Monitoring & Backup Otomatis

### Backup Database Otomatis
```bash
# Tambahkan ke scheduler (app/Console/Kernel.php)
# Backup harian ke Google Drive
$schedule->command('backup:run --only-db')->daily()->at('02:00');
```

### Monitoring Storage
Sistem sudah memiliki fitur monitoring storage bawaan:
- Popup peringatan otomatis saat storage ≥ 80%
- Cek status di halaman **Pengaturan → Pemberitahuan**
- API endpoint: `GET /api-internal/cek-storage`

---

## 🏆 Tips untuk Lomba

1. **Gunakan Railway** → paling mudah & cepat setup
2. **Database MySQL** → tidak perlu ubah kode, langsung kompatibel
3. **Aktifkan HTTPS** → Railway sudah otomatis
4. **Setup email** → Gunakan Gmail App Password untuk SMTP
5. **Demo data** → Jalankan seeder untuk data contoh
6. **Screenshot backup** → Dokumentasikan fitur cloud backup, notifikasi, dan monitoring
7. **Custom domain** → Bisa pakai domain gratis dari Freenom/Railway subdomain

---

*Dokumen ini dibuat untuk persiapan lomba SIMPEG-SMART. Semua layanan yang disebutkan memiliki tier gratis yang cukup untuk demo dan presentasi.*
