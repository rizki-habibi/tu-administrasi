# Pembaruan Fitur v0.2 — Sistem TU Administrasi

> **Versi:** 0.2  
> **Tanggal Rilis:** 7 Maret 2026  
> **Aplikasi:** Sistem Administrasi Tata Usaha — SMA Negeri 2 Jember

---

## Daftar Isi

1. [Ringkasan Pembaruan](#1-ringkasan-pembaruan)
2. [Penyesuaian Peran (Role System)](#2-penyesuaian-peran-role-system)
3. [Halaman Utama (Public Page)](#3-halaman-utama-public-page)
4. [Halaman Kinerja](#4-halaman-kinerja)
5. [Pembaruan Sidebar & Navigasi](#5-pembaruan-sidebar--navigasi)
6. [Panduan Versi Dokumen](#6-panduan-versi-dokumen)
7. [Tombol Scroll ke Atas](#7-tombol-scroll-ke-atas)
8. [Fitur AI — Rencana Integrasi](#8-fitur-ai--rencana-integrasi)

---

## 1. Ringkasan Pembaruan

Versi 0.2 membawa sejumlah peningkatan pada sistem administrasi, khususnya:

| No | Fitur | Status |
|----|-------|--------|
| 1 | Penyesuaian sistem peran (10 peran lengkap) | ✅ Selesai |
| 2 | Halaman Utama — Kelola konten publik | ✅ Selesai |
| 3 | Halaman Kinerja — Konten kinerja sekolah | ✅ Selesai |
| 4 | Pembaruan sidebar panduan dengan submenu | ✅ Selesai |
| 5 | Sistem versioning dokumen panduan | ✅ Selesai |
| 6 | Tombol scroll ke atas di panduan | ✅ Selesai |
| 7 | Rencana integrasi AI lanjutan | 📋 Direncanakan |

---

## 2. Penyesuaian Peran (Role System)

### 2.1. Dari 2 Peran → 10 Peran

Sistem sebelumnya (v0.1) hanya mendokumentasikan 2 peran utama (Admin dan Staff). Pada v0.2, sistem telah disesuaikan dengan **10 peran pengguna** yang lebih granular:

| Kode Peran | Nama Tampilan | IKI | Prefix URL |
|---|---|---|---|
| `admin` | Administrator / Kepala TU | — | `/admin` |
| `kepala_sekolah` | Kepala Sekolah | — | `/kepala-sekolah` |
| `kepegawaian` | Staf Kepegawaian | IKI 1 | `/staf` |
| `pramu_bakti` | Pramu Bakti | IKI 2 | `/staf` |
| `keuangan` | Staf Keuangan | IKI 3 | `/staf` |
| `persuratan` | Staf Persuratan | IKI 4 | `/staf` |
| `perpustakaan` | Staf Perpustakaan | IKI 5 | `/staf` |
| `inventaris` | Staf Inventaris | IKI 6 | `/staf` |
| `kesiswaan_kurikulum` | Staf Kesiswaan & Kurikulum | IKI 7 | `/staf` |
| `staff` | Staf Umum | — | `/staf` |

### 2.2. Routing per Peran

| Peran | Prefix | Middleware |
|---|---|---|
| Admin | `/admin` | `auth, role:admin` |
| Kepala Sekolah | `/kepala-sekolah` | `auth, role:kepala_sekolah` |
| Semua Staf (IKI 1–7 + staff) | `/staf` | `auth, role:all_staff` |

### 2.3. Menu Sidebar Dinamis

Setiap peran staf memiliki **menu tambahan** yang dimuat secara dinamis berdasarkan peran:

- **IKI 1 — Kepegawaian:** SKP, Evaluasi Guru (PKG, STAR, Bukti Fisik)
- **IKI 2 — Pramu Bakti:** Laporan Kerja, Kerusakan & Perbaikan
- **IKI 3 — Keuangan:** Laporan Keuangan, Dokumen Keuangan
- **IKI 4 — Persuratan:** Surat Masuk/Keluar, Arsip Dokumen
- **IKI 5 — Perpustakaan:** Koleksi & Dokumen, Laporan
- **IKI 6 — Inventaris:** Data Inventaris, Kerusakan, Dokumen
- **IKI 7 — Kesiswaan & Kurikulum:** Data Siswa, Kurikulum, Evaluasi (P5, Pembelajaran), Laporan

---

## 3. Halaman Utama (Public Page)

### 3.1. Tentang

Halaman Utama adalah fitur baru yang memungkinkan Admin mengelola **konten publik** yang dapat diakses oleh pengunjung tanpa login. Fitur ini diakses melalui:

**Sidebar → Halaman Publik → Halaman Utama**

### 3.2. Fitur Halaman Utama

| Fitur | Deskripsi |
|-------|-----------|
| **Semua Konten** | Kelola seluruh konten yang tampil di halaman publik |
| **Tambah Konten** | Buat konten baru (berita, pengumuman, profil sekolah) |
| **Galeri & Media** | Upload dan kelola foto/video kegiatan |
| **Kerjasama / MOU** | Dokumentasi kerjasama dengan pihak lain |
| **Dokumen Publik** | Dokumen yang dapat diakses oleh publik |

### 3.3. Kategori Konten

- Berita & Pengumuman
- Profil Sekolah
- Galeri & Media
- Kerjasama / MOU
- Dokumen Publik

### 3.4. Cara Menambah Konten

1. Navigasi: **Sidebar → Halaman Publik → Halaman Utama → Tambah Konten**
2. Isi formulir:
   - Judul konten
   - Kategori
   - Bagian (Kinerja / Umum)
   - Isi konten (editor teks)
   - Gambar/media (opsional)
   - Status: Draft / Terbit
3. Klik **Simpan**

---

## 4. Halaman Kinerja

### 4.1. Tentang

Halaman Kinerja menampilkan **konten kinerja sekolah** yang dapat dipublikasikan ke halaman publik. Konten ini menampilkan pencapaian, statistik, dan informasi kinerja sekolah.

**Sidebar → Halaman Publik → Halaman Utama → Konten Kinerja**

### 4.2. Jenis Konten Kinerja

| Jenis | Deskripsi |
|-------|-----------|
| Prestasi Sekolah | Pencapaian dan penghargaan yang diraih |
| Statistik Kelulusan | Data kelulusan per tahun |
| Program Unggulan | Program-program unggulan sekolah |
| Kerjasama & MOU | Kerjasama dengan instansi/perusahaan |
| Laporan Tahunan | Laporan kinerja tahunan sekolah |

### 4.3. Statistik & Saran Pengunjung

Admin juga dapat memantau:
- **Statistik Pengunjung** — grafik jumlah pengunjung halaman publik
- **Saran Pengunjung** — masukan dari pengunjung yang perlu ditinjau

**Sidebar → Halaman Publik → Kinerja & Saran**

---

## 5. Pembaruan Sidebar & Navigasi

### 5.1. Menu Panduan Baru

Menu **Panduan** di sidebar kini menjadi submenu dengan daftar dokumen:

```
📖 Panduan
  ├── Pusat Panduan          — Hub semua dokumen
  ├── Panduan Penggunaan     — Panduan lengkap sistem (v0.1)
  └── Pembaruan v0.2         — Changelog versi terbaru
```

### 5.2. Keuntungan

- Dokumen terorganisir per versi
- Mudah melanjutkan ke versi berikutnya
- Setiap pembaruan memiliki dokumen tersendiri
- Tidak perlu scroll panjang untuk satu dokumen besar

---

## 6. Panduan Versi Dokumen

### 6.1. Struktur Versioning

| Versi | Tanggal | Dokumen | Keterangan |
|-------|---------|---------|------------|
| v0.1 | 3 Maret 2026 | `PANDUAN-PENGGUNAAN.md` | Panduan lengkap fitur dasar (20 modul) |
| v0.2 | 7 Maret 2026 | `PANDUAN-v0.2.md` | Penyesuaian peran, halaman utama, kinerja |
| v0.3+ | TBD | — | Versi berikutnya |

### 6.2. Cara Menambah Versi Baru

1. Buat file baru di folder `docs/` dengan nama `PANDUAN-v{X.Y}.md`
2. Daftarkan di controller `PanduanController` method `index()`
3. Dokumen akan otomatis muncul di Pusat Panduan

---

## 7. Tombol Scroll ke Atas

### 7.1. Fitur Baru

Di setiap halaman panduan kini terdapat tombol **Scroll ke Atas** (⬆) yang muncul ketika pengguna sudah scroll ke bawah. Tombol ini membantu navigasi cepat kembali ke bagian atas dokumen.

### 7.2. Cara Kerja

- Tombol muncul otomatis setelah scroll 300px ke bawah
- Klik tombol untuk langsung ke atas dengan animasi smooth
- Tombol tersembunyi saat sudah di posisi atas

---

## 8. Fitur AI — Rencana Integrasi

### 8.1. AI yang Sudah Tersedia

Sistem sudah memiliki integrasi **Google Gemini AI** untuk:
- Generate dokumen Word otomatis
- Template dokumen cerdas
- SIATU-AI — asisten AI berbasis chat

### 8.2. Rencana Pengembangan AI

Lihat dokumen **[Rekomendasi API AI](REKOMENDASI-API-AI.md)** untuk daftar lengkap API yang direkomendasikan untuk pengembangan fitur AI selanjutnya.

---

## Riwayat Perubahan

| Tanggal | Versi | Perubahan |
|---------|-------|-----------|
| 3 Maret 2026 | v0.1 | Rilis awal — 20 modul panduan penggunaan |
| 7 Maret 2026 | v0.2 | Penyesuaian peran (10 peran), halaman utama, halaman kinerja, scroll-to-top, versioning panduan |

---

> *Dokumen ini adalah bagian dari Panduan Sistem TU Administrasi SMA Negeri 2 Jember.*
