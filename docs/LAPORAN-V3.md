# LAPORAN PENGEMBANGAN SISTEM — Versi 3.0

> **Nama Sistem:** SIMPEG-SMART (Sistem Informasi Manajemen Kepegawaian Sekolah Terintegrasi)  
> **Organisasi:** SMA Negeri 2 Jember  
> **Tanggal Rilis:** 7 Maret 2026  
> **Pengembang:** Rizki Habibi  
> **Repositori:** https://github.com/rizki-habibi/tu-administrasi  

---

## Daftar Isi

1. [Ringkasan Eksekutif](#1-ringkasan-eksekutif)
2. [Arsitektur Sistem](#2-arsitektur-sistem)
3. [Daftar Fitur Lengkap](#3-daftar-fitur-lengkap)
4. [Peran & Hak Akses](#4-peran--hak-akses)
5. [Modul Per Peran](#5-modul-per-peran)
6. [Statistik Kode](#6-statistik-kode)
7. [Perubahan dari Versi 2 ke Versi 3](#7-perubahan-dari-versi-2-ke-versi-3)
8. [Alur Kerja Utama](#8-alur-kerja-utama)
9. [Keamanan & Backup](#9-keamanan--backup)
10. [Rencana Hosting & Deployment](#10-rencana-hosting--deployment)

---

## 1. Ringkasan Eksekutif

SIMPEG-SMART adalah aplikasi administrasi kepegawaian berbasis web yang dikembangkan khusus untuk SMA Negeri 2 Jember. Sistem ini mengintegrasikan seluruh proses administrasi tata usaha mulai dari manajemen pegawai, kehadiran, surat menyurat, keuangan, inventaris, evaluasi kinerja, hingga kecerdasan buatan (AI) untuk membantu produktivitas.

### Capaian Versi 3.0
- **11 peran pengguna** dengan hak akses berbeda
- **450+ route** terdaftar dan terverifikasi
- **42+ model database** dengan 35+ tabel migrasi
- **Integrasi AI multi-provider** (Gemini, OpenAI, Anthropic, Custom)
- **Database Inspector** untuk monitoring real-time seluruh tabel
- **Cloud Drive Management** per peran (Google Drive, OneDrive, TeraBox, Custom)
- **Sistem Notifikasi Multi-Channel** (push, email, popup, storage alert)
- **Portal Publik** dengan konten dinamis, berita, galeri, dan saran pengunjung

---

## 2. Arsitektur Sistem

### Technology Stack

| Komponen | Teknologi | Versi |
|---|---|---|
| Backend | Laravel (PHP) | 12.x / PHP 8.5 |
| Database | MySQL | 8.x |
| Frontend | Bootstrap + Bootstrap Icons | 5.3 / 1.11 |
| Font | Poppins (via Google Fonts) | - |
| Alert/Dialog | SweetAlert2 | Latest CDN |
| Build Tool | Vite + Sass | 7.x |
| AI Service | Google Gemini API + OpenAI + Anthropic | Multi-provider |
| Cloud Backup | Google Drive API OAuth2 | v3 |
| Document | PHPWord (docx generation) | Latest |
| Auth | Laravel Socialite + JWT | - |

### Arsitektur MVC
```
┌──────────────┐     ┌──────────────┐     ┌──────────────┐
│   Browser    │────▶│   Routes     │────▶│  Controller  │
│   (User)     │◀────│  (web.php,   │◀────│  (Admin/,    │
│              │     │   admin.php, │     │   Staff/,    │
│              │     │   staf.php,  │     │   Kepsek/)   │
│              │     │   kepsek.php)│     │              │
└──────────────┘     └──────────────┘     └──────┬───────┘
                                                 │
                     ┌──────────────┐     ┌──────▼───────┐
                     │   Views      │◀────│   Models     │
                     │  (Blade)     │     │  (Eloquent)  │
                     │              │     │              │
                     └──────────────┘     └──────────────┘
                                                 │
                     ┌──────────────┐     ┌──────▼───────┐
                     │   Services   │     │   Database   │
                     │  (AI, Drive) │     │  (MySQL)     │
                     └──────────────┘     └──────────────┘
```

---

## 3. Daftar Fitur Lengkap

### A. Administrasi Umum
| No | Fitur | Deskripsi | Peran |
|---|---|---|---|
| 1 | Manajemen Pegawai | CRUD data pegawai, impor/ekspor CSV, toggle status | Admin |
| 2 | Kehadiran / Absensi | Clock In/Out dengan foto & GPS, rekap, pengaturan jam kerja | Semua peran |
| 3 | Pengajuan Izin | Pengajuan, persetujuan/penolakan, notifikasi otomatis | Staf → Kepsek |
| 4 | SKP | Sasaran Kinerja Pegawai dengan alur: diajukan → menunggu → disetujui/revisi/ditolak | Staf → Kepsek |
| 5 | Surat Menyurat | Pengelolaan surat masuk/keluar dengan disposisi | Admin, Staf Persuratan |
| 6 | Disposisi Surat | Delegasi surat ke staf terkait, proses & penyelesaian | Admin → Staf |
| 7 | Dokumen & Arsip | Manajemen dokumen digital, ekspor | Admin, Staf |

### B. Akademik & Kesiswaan
| No | Fitur | Deskripsi | Peran |
|---|---|---|---|
| 8 | Kurikulum | Manajemen dokumen kurikulum | Admin, Staf Kesiswaan |
| 9 | Kesiswaan | Data siswa, pelanggaran, prestasi | Admin, Staf Kesiswaan |
| 10 | Evaluasi PKG | Penilaian Kinerja Guru | Admin, Staf |
| 11 | Penilaian P5 | Proyek Penguatan Profil Pelajar Pancasila | Admin, Staf |
| 12 | Analisis STAR | Evaluasi berbasis metode STAR | Admin, Staf |
| 13 | Bukti Fisik | Upload bukti fisik evaluasi | Admin, Staf |
| 14 | Metode Pembelajaran | Dokumentasi metode pembelajaran | Admin, Staf |

### C. Sarana & Keuangan
| No | Fitur | Deskripsi | Peran |
|---|---|---|---|
| 15 | Inventaris/Sarpras | Pelacakan aset, laporan kerusakan | Admin, Staf Inventaris |
| 16 | Keuangan | Catatan keuangan, anggaran, verifikasi | Admin, Staf Keuangan |

### D. AI & Dokumen Cerdas
| No | Fitur | Deskripsi | Peran |
|---|---|---|---|
| 17 | SIMPEG-AI Chatbot | Asisten AI tiap peran (knowledge base lokal + API) | Semua peran |
| 18 | AI Chat Popup | Popup interaktif di setiap halaman dengan voice input | Semua peran |
| 19 | Word-AI | Pembuatan dokumen otomatis (PHPWord + Gemini) | Admin, Staf |
| 20 | Rekap Eksekutif | Ringkasan eksekutif dengan analisis AI | Kepala Sekolah |
| 21 | Konfigurasi AI | CRUD API key multi-provider (Gemini/OpenAI/Anthropic/Custom) | Admin only |

### E. Portal Publik
| No | Fitur | Deskripsi | Peran |
|---|---|---|---|
| 22 | Halaman Publik | Konten profil, visi-misi, galeri, kerjasama, berita | Admin (kelola), Publik (lihat) |
| 23 | Portal Dokumen | 9 kategori dokumen publik | Publik |
| 24 | Saran Pengunjung | Formulir saran dari pengunjung | Admin, Publik |
| 25 | Statistik Pengunjung | Data pengunjung per negara | Admin |

### F. Komunikasi & Produktivitas
| No | Fitur | Deskripsi | Peran |
|---|---|---|---|
| 26 | Chat/Pesan | Pesan internal + gambar + real-time | Semua peran |
| 27 | Notifikasi Multi-Channel | Push, email, popup, storage alert | Semua peran |
| 28 | Agenda/Event | Manajemen acara + notifikasi | Admin, Semua peran (lihat) |
| 29 | Pengingat | Reminder/tugas dengan overdue tracking | Admin, Staf |
| 30 | Ulang Tahun | Pelacakan & ucapan ulang tahun | Admin, Semua peran |
| 31 | Catatan Beranda | Sticky notes di dashboard | Semua peran |
| 32 | Catatan Harian | Jurnal kerja harian | Staf |
| 33 | Berita | Publikasi berita sekolah | Admin, Publik |

### G. Database & Cloud Storage (BARU v3)
| No | Fitur | Deskripsi | Peran |
|---|---|---|---|
| 34 | Database Inspector | Lihat semua tabel, struktur kolom, 10 data terbaru, ukuran DB | Admin only |
| 35 | Cloud Drive Management | Link cloud storage per peran (GDrive, OneDrive, TeraBox, Custom) | Semua peran |
| 36 | Admin Cloud Oversight | Admin bisa lihat & kelola semua cloud drive dari semua peran | Admin only |
| 37 | Data Protection | Data dari peran non-admin terkunci (bisa_dihapus=false), hanya admin bisa hapus | Sistem |

### H. Manajemen & Monitoring
| No | Fitur | Deskripsi | Peran |
|---|---|---|---|
| 38 | Log Aktivitas | Audit log + pencarian + deteksi anomali (hapus massal, login gagal) | Admin |
| 39 | Resolusi | Keputusan Kepala Sekolah | Kepala Sekolah |
| 40 | Panduan | Manajemen panduan + upload Google Drive + cetak/unduh | Admin, Semua peran |
| 41 | Pusat Ekspor | Ekspor terpusat (pegawai, kehadiran, dokumen) + PDF/CSV | Admin |
| 42 | Akreditasi & EDS | Dokumen akreditasi + Evaluasi Diri Sekolah | Admin |
| 43 | Laporan | Manajemen laporan per peran | Admin, Staf |
| 44 | Storage Monitor | Real-time monitoring penyimpanan server | Admin (settings drawer) |

### I. Pengaturan & Backup
| No | Fitur | Deskripsi | Peran |
|---|---|---|---|
| 45 | Profil & Pengaturan | Profil, password, preferensi tampilan | Semua peran |
| 46 | Google Drive Backup | Backup otomatis ke Google Drive (DB + uploads) | Admin |
| 47 | Dark Mode | Toggle tema gelap via Settings Drawer | Semua peran |
| 48 | Kepegawaian | Riwayat jabatan, pangkat, dokumen kepegawaian | Admin |

### J. UI/UX Modern
| No | Fitur | Deskripsi |
|---|---|---|
| 49 | Settings Right Drawer | Panel kanan dengan stat cepat, navigasi, storage monitor |
| 50 | AI Chat Popup FAB | Floating Action Button 3D animated di pojok kanan bawah |
| 51 | Responsive Design | Full responsive mobile/tablet/desktop |
| 52 | SweetAlert Confirmations | Popup konfirmasi "Yakin ingin menghapus?" pada semua aksi hapus |
| 53 | Sidebar Multi-level | Navigation groups, nested submenus, search |

---

## 4. Peran & Hak Akses

| # | Peran | Kode | Prefix Route | Tema | Hak Akses |
|---|---|---|---|---|---|
| 1 | Admin (Kepala TU) | `admin` | `/admin` | Indigo (#6366f1) | Akses penuh, kelola semua modul + database inspector + cloud drive semua peran |
| 2 | Kepala Sekolah | `kepala_sekolah` | `/kepala-sekolah` | Amber (#d97706) | Supervisor, approve izin/SKP, resolusi, rekap eksekutif, cloud drive sendiri |
| 3 | Staf Kepegawaian | `kepegawaian` | `/staf` | Hijau (#10b981) | Urusan kepegawaian + cloud drive sendiri |
| 4 | Pramu Bakti | `pramu_bakti` | `/staf` | Hijau | Laporan kerja, kerusakan + cloud drive sendiri |
| 5 | Staf Keuangan | `keuangan` | `/staf` | Hijau | Keuangan + cloud drive sendiri |
| 6 | Staf Persuratan | `persuratan` | `/staf` | Hijau | Surat masuk/keluar + cloud drive sendiri |
| 7 | Staf Perpustakaan | `perpustakaan` | `/staf` | Hijau | Perpustakaan + cloud drive sendiri |
| 8 | Staf Inventaris | `inventaris` | `/staf` | Hijau | Inventaris + cloud drive sendiri |
| 9 | Staf Kesiswaan & Kurikulum | `kesiswaan_kurikulum` | `/staf` | Hijau | Data siswa, kurikulum + cloud drive sendiri |
| 10 | Staf Umum | `staff` | `/staf` | Hijau | Akses dasar + cloud drive sendiri |
| 11 | Staff Magang | `magang` | `/magang` | Cyan (#06b6d4) | Akses terbatas + cloud drive sendiri |

### Matriks Hak Akses Cloud Drive

| Aksi | Admin | Kepala Sekolah | Staf | Magang |
|---|---|---|---|---|
| Lihat cloud drive sendiri | ✅ | ✅ | ✅ | ✅ |
| Tambah cloud drive | ✅ | ✅ | ✅ | ✅ |
| Edit cloud drive sendiri | ✅ | ✅ | ✅ | ✅ |
| Hapus cloud drive sendiri | ✅ | ❌ | ❌ | ❌ |
| Lihat cloud drive semua peran | ✅ | ❌ | ❌ | ❌ |
| Hapus cloud drive peran lain | ✅ | ❌ | ❌ | ❌ |

---

## 5. Modul Per Peran

### Admin (Kepala TU) — 28 modul
1. Beranda (ringkasan + catatan + AI)
2. Manajemen Pegawai (CRUD + impor/ekspor)
3. Kepegawaian (jabatan, pangkat, dokumen, laporan)
4. Kehadiran (clock in/out + laporan + pengaturan)
5. Pengajuan Izin (lihat + setujui/tolak)
6. Laporan (lihat + update status)
7. Agenda (CRUD)
8. Notifikasi (CRUD + pengumuman)
9. Surat Menyurat (CRUD + status update)
10. Dokumen & Arsip (CRUD + ekspor)
11. Kurikulum (CRUD)
12. Kesiswaan (CRUD)
13. Inventaris (CRUD)
14. Keuangan (CRUD + anggaran + verifikasi)
15. Evaluasi Kinerja (PKG, P5, STAR, Bukti Fisik, Pembelajaran)
16. Akreditasi & EDS
17. Pengingat (CRUD + toggle)
18. Panduan (CRUD + Google Drive upload)
19. Word-AI (CRUD + AI generate)
20. Chat (pesan internal)
21. Ulang Tahun
22. Halaman Publik (CRUD konten + statistik + saran)
23. Disposisi Surat (CRUD)
24. Log Aktivitas (pencarian + deteksi anomali)
25. SIMPEG-AI Chatbot
26. Konfigurasi AI (CRUD multi-provider)
27. **Database Inspector** (lihat tabel, struktur, data terbaru)
28. **Cloud Drive** (kelola semua peran)
29. Pusat Ekspor (CSV/PDF)
30. Pengaturan (profil, password, tampilan)

### Kepala Sekolah — 18 modul
1. Beranda
2. Pegawai (lihat saja)
3. Kehadiran (clock in/out + lihat)
4. Izin (lihat + setujui/tolak)
5. SKP (lihat + setujui/revisi/tolak)
6. Evaluasi (lihat PKG, STAR, bukti fisik)
7. Surat (lihat saja)
8. Laporan (lihat saja)
9. Keuangan (lihat saja)
10. Agenda (lihat saja)
11. Notifikasi
12. Resolusi (CRUD)
13. Rekap Eksekutif (+ analisis AI)
14. **Cloud Drive** (milik sendiri)
15. Chat
16. SIMPEG-AI
17. Panduan
18. Pengaturan

### Staf (Semua 8 Role) — 20+ modul
1. Beranda
2. Kehadiran (clock in/out)
3. Pengajuan Izin (CRUD)
4. SKP (CRUD)
5. Kinerja (lihat)
6. Notifikasi
7. Agenda (lihat)
8. Pengingat
9. Profil & Pengaturan
10. Word-AI
11. Ulang Tahun
12. Catatan Beranda
13. Catatan Harian
14. Chat
15. SIMPEG-AI
16. Panduan
17. Evaluasi
18. Laporan (CRUD)
19. **Cloud Drive** (milik sendiri)
20. Disposisi (lihat + proses)
21. Surat (lihat + buat)
22. Dokumen (lihat + upload)
23. + Modul khusus per bidang (inventaris, keuangan, perpustakaan, dll)

---

## 6. Statistik Kode

| Metrik | Jumlah |
|---|---|
| Total Route | 450+ |
| Model Database | 42+ |
| Migration File | 36+ |
| Controller Admin | 28+ |
| Controller Staf | 23+ |
| Controller Kepsek | 19+ |
| View Files | 200+ |
| Services | 3 (LayananGeminiAi, LayananCadanganGoogleDrive, LayananNotifikasi) |
| Seeder | 27 |
| Tabel Database | 35+ |

### Struktur Database (Tabel Utama)

| Tabel | Deskripsi |
|---|---|
| `pengguna` | Data pengguna (11 peran) |
| `kehadiran` | Data absensi harian (foto, GPS, jam) |
| `pengajuan_izin` | Izin/cuti/sakit/dinas luar |
| `skp` | Sasaran Kinerja Pegawai + alur approval |
| `surat` | Surat masuk & keluar |
| `disposisi_surat` | Delegasi surat |
| `dokumen` | Dokumen & arsip digital |
| `laporan` | Laporan per peran |
| `acara` | Agenda & event |
| `notifikasi` | Notifikasi multi-channel |
| `inventaris` | Data inventaris/sarpras |
| `catatan_keuangan` | Catatan keuangan |
| `anggaran` | Data anggaran |
| `evaluasi_guru` | PKG (Penilaian Kinerja Guru) |
| `penilaian_p5` | Penilaian P5 |
| `analisis_star` | Analisis STAR |
| `bukti_fisik` | Bukti fisik evaluasi |
| `metode_pembelajaran` | Metode pembelajaran |
| `data_siswa` | Data kesiswaan |
| `dokumen_kurikulum` | Dokumen kurikulum |
| `dokumen_akreditasi` | Akreditasi |
| `pengingat` | Reminder/tugas |
| `catatan_beranda` | Sticky notes dashboard |
| `catatan_harian` | Jurnal harian staf |
| `percakapan` / `pesan` | Chat internal |
| `konten_publik` | Konten website publik |
| `saran_pengunjung` | Saran dari publik |
| `pengunjung` | Data visitor |
| `log_aktivitas` | Audit trail |
| `arsip_cadangan` | Backup archive |
| `pengaturan_ai` | Konfigurasi AI multi-provider |
| `penyimpanan_cloud` | Cloud drive per peran (BARU v3) |
| `dokumen_word` | Dokumen Word-AI |
| `ucapan_ulang_tahun` | Ucapan ulang tahun |
| `pengaturan_kehadiran` | Setting jam kerja |
| `pengaturan_pengguna` | Preferensi pengguna |

---

## 7. Perubahan dari Versi 2 ke Versi 3

### Fitur Baru di v3
| # | Fitur | Deskripsi |
|---|---|---|
| 1 | **Database Inspector** | Admin bisa melihat semua tabel database, jumlah record, ukuran, struktur kolom, dan 10 data terbaru dari setiap tabel |
| 2 | **Cloud Drive Management** | Setiap peran punya cloud drive sendiri (link-based). Admin bisa melihat & mengelola semua. Support: Google Drive, Google Drive Bisnis, OneDrive, TeraBox, Custom |
| 3 | **Data Protection** | Data cloud drive dari peran non-admin dikunci (bisa_dihapus=false). Hanya admin yang bisa menghapus data penting |
| 4 | **Log Aktivitas Enhanced** | Ditambahkan pencarian full-text, filter pengguna, deteksi anomali otomatis (hapus massal >10/hari, login gagal >5/hari) |
| 5 | **Konfigurasi AI Multi-Provider** | Admin bisa mengelola API key untuk Gemini, OpenAI, Anthropic, atau Custom provider. Test connection tersedia |
| 6 | **SKP Workflow Lengkap** | Alur: draft → diajukan → menunggu → disetujui/revisi/ditolak. Dengan catatan revisi dan timestamp |
| 7 | **Kehadiran Semua Peran** | Clock in/out dengan foto + GPS tersedia untuk admin, kepala sekolah, staf, dan magang |
| 8 | **Social Media Integration** | Link YouTube, Instagram, TikTok, LinkedIn, Google Maps, Data Sekolah Kemdikdasmen |
| 9 | **Visitors by Country** | Widget pengunjung per negara di halaman publik |

### Perbaikan UI/UX di v3
| # | Perbaikan |
|---|---|
| 1 | AI Popup dipindahkan ke kanan bawah (tidak menutupi sidebar) |
| 2 | Kepala Sekolah layout dirombak total (header, sidebar, footer) agar seragam dengan admin |
| 3 | Settings Right Drawer ditambahkan ke semua peran |
| 4 | SweetAlert konfirmasi "Apakah Anda yakin?" pada semua aksi hapus |
| 5 | Duplikat peta di footer publik dihapus, diganti widget pengunjung |

---

## 8. Alur Kerja Utama

### A. Alur SKP (Sasaran Kinerja Pegawai)
```
Staf membuat SKP (draft)
    ↓
Staf mengajukan SKP (diajukan)
    ↓
Kepala Sekolah mereview
    ├── → Disetujui (+ notifikasi ke staf)
    ├── → Revisi (+ catatan revisi + notifikasi)
    └── → Ditolak (+ catatan + notifikasi)
    
    Jika Revisi → Staf memperbaiki → Ajukan kembali → Loop
```

### B. Alur Pengajuan Izin
```
Staf mengajukan izin (pending)
    ↓
Kepala Sekolah mereview
    ├── → Disetujui (+ notifikasi)
    └── → Ditolak (+ notifikasi)
```

### C. Alur Cloud Drive
```
Semua peran dapat menambah link cloud drive
    ↓
Data disimpan dengan peran_pemilik
    ↓
Admin bisa melihat semua cloud drive dari semua peran
    ↓
Data dari peran non-admin terkunci (bisa_dihapus = false)
    ↓
Hanya admin yang bisa menghapus data penting
```

### D. Alur Kehadiran
```
Login ke sistem
    ↓
Clock In (foto + lokasi GPS + waktu)
    ↓
Sistem mendeteksi keterlambatan (vs pengaturan jam masuk)
    ↓
Clock Out (foto + waktu)
    ↓
Data masuk ke rekap kehadiran
```

---

## 9. Keamanan & Backup

### Keamanan
| Aspek | Implementasi |
|---|---|
| Autentikasi | Laravel Auth + role-based middleware |
| Otorisasi | Middleware `MiddlewarePeran` per prefix route |
| API Key Protection | Encrypted via Laravel Crypt (AES-256-CBC) |
| CSRF Protection | Token pada semua form POST |
| Input Validation | Server-side validation di setiap controller |
| XSS Prevention | Blade escaping otomatis `{{ }}` |
| SQL Injection | Eloquent ORM + Parameterized queries |
| Audit Trail | Log aktivitas mencatat semua aksi CRUD |
| Anomaly Detection | Deteksi otomatis hapus massal & login gagal |
| Data Protection | Cloud drive data terkunci untuk peran non-admin |
| Konfirmasi Hapus | SweetAlert popup pada semua aksi penghapusan |

### Backup
| Metode | Deskripsi |
|---|---|
| Google Drive Backup | Upload otomatis database + file ke Google Drive |
| Cloud Drive Links | Setiap peran menyimpan link ke penyimpanan cloud masing-masing |
| Local Backup | Backup tersimpan di `storage/app/backups/` |
| Arsip Cadangan | Riwayat backup tersimpan di tabel `arsip_cadangan` |

---

## 10. Rencana Hosting & Deployment

### Kebutuhan Server
| Kebutuhan | Minimum |
|---|---|
| PHP | 8.2+ |
| MySQL | 8.0+ |
| RAM | 512 MB |
| Storage | 2 GB |
| SSL | Wajib (HTTPS) |

### Langkah Deployment
1. Clone repositori ke server
2. Install dependensi: `composer install --no-dev`
3. Konfigurasi `.env` (database, mail, Google Drive credentials)
4. Generate key: `php artisan key:generate`
5. Migrasi database: `php artisan migrate --seed`
6. Optimasi: `php artisan optimize`
7. Set cron job untuk jadwal backup
8. Konfigurasi web server (Nginx/Apache) dengan document root ke `public/`

### Rekomendasi Hosting
- **VPS**: DigitalOcean, Vultr, AWS Lightsail
- **Shared**: Niagahoster, Hostinger (pastikan PHP 8.2+)
- **PaaS**: Railway, Render
- **Domain**: Gunakan subdomain sekolah atau domain `.sch.id`

---

## Kesimpulan

Versi 3.0 SIMPEG-SMART merupakan pembaruan besar yang menambahkan:
- **Database Inspector** agar admin bisa memantau kesehatan database
- **Cloud Drive Management** agar setiap peran bisa menyimpan data secara aman di cloud
- **Data Protection** agar data penting tidak terhapus secara tidak sengaja
- **Log Aktivitas Enhanced** dengan pencarian dan deteksi anomali
- **Multi-Provider AI** agar tidak bergantung pada satu provider AI saja

Sistem sudah siap untuk di-hosting dan digunakan dalam lingkungan produksi.

---

*Dokumen ini dibuat otomatis pada 7 Maret 2026.*
