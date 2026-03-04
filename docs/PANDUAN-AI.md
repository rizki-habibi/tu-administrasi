# Panduan Fitur AI — Gemini API

> **Versi:** 1.0  
> **Terakhir diperbarui:** 4 Maret 2026  
> **Aplikasi:** Sistem Administrasi Tata Usaha — SMA Negeri 2 Jember

---

## Daftar Isi

1. [Tentang Fitur AI](#1-tentang-fitur-ai)
2. [Mendapatkan API Key](#2-mendapatkan-api-key)
3. [Konfigurasi di Aplikasi](#3-konfigurasi-di-aplikasi)
4. [Cara Menggunakan](#4-cara-menggunakan)
5. [Template Dokumen](#5-template-dokumen)
6. [Troubleshooting](#6-troubleshooting)
7. [Batasan & Catatan](#7-batasan--catatan)

---

## 1. Tentang Fitur AI

Sistem TU Administrasi menggunakan **Google Gemini AI** untuk menghasilkan konten dokumen secara otomatis. Fitur ini tersedia di modul **Dokumen Word** dan membantu staff/admin membuat:

- Surat resmi (undangan, edaran, keputusan)
- Laporan kegiatan
- Proposal kegiatan
- SK (Surat Keputusan)
- Notulen rapat
- Dan berbagai dokumen administrasi lainnya

### Cara Kerja

```
[Pengguna menulis prompt] → [Gemini AI memproses] → [Konten dokumen dihasilkan]
                                                            ↓
                                              [Fallback ke template lokal jika gagal]
```

Jika Gemini AI tidak tersedia (API key belum diatur, kuota habis, dll), sistem otomatis menggunakan **template lokal** sebagai cadangan.

---

## 2. Mendapatkan API Key

### Langkah-langkah:

1. **Buka Google AI Studio**  
   Kunjungi: [https://aistudio.google.com/apikey](https://aistudio.google.com/apikey)

2. **Login dengan akun Google**  
   Gunakan akun Google pribadi atau akun sekolah.

3. **Klik "Create API Key"**  
   Pilih project yang tersedia atau buat project baru.

4. **Salin API Key**  
   API key akan ditampilkan sekali. Salin dan simpan di tempat aman.

> ⚠️ **Penting:**  
> - API key bersifat rahasia. Jangan bagikan atau simpan di repository publik.  
> - Gemini API memiliki **tier gratis** dengan batasan:
>   - 15 request per menit (RPM)
>   - 1 juta token per menit (TPM)  
>   - 1.500 request per hari (RPD)
> - Cukup untuk penggunaan sekolah sehari-hari.

---

## 3. Konfigurasi di Aplikasi

### 3.1. Tambahkan ke File `.env`

Buka file `.env` di root project dan tambahkan:

```env
GEMINI_API_KEY=your-api-key-here
GEMINI_MODEL=gemini-2.0-flash
```

### 3.2. Model yang Tersedia

| Model | Kecepatan | Kualitas | Gratis | Rekomendasi |
|-------|-----------|----------|--------|-------------|
| `gemini-2.0-flash` | ⚡ Sangat cepat | Bagus | ✅ | **Default — cocok untuk dokumen** |
| `gemini-2.0-flash-lite` | ⚡⚡ Tercepat | Cukup | ✅ | Untuk dokumen sederhana |
| `gemini-2.5-pro-preview-05-06` | 🐢 Lambat | Terbaik | ✅ (terbatas) | Untuk dokumen kompleks |
| `gemini-2.5-flash-preview-04-17` | ⚡ Cepat | Sangat bagus | ✅ | Alternatif terbaik |

> 💡 **Rekomendasi:** Gunakan `gemini-2.0-flash` (default). Cepat, gratis, dan kualitas cukup untuk dokumen administrasi.

### 3.3. Verifikasi Konfigurasi

Setelah mengatur `.env`, jalankan:

```bash
php artisan config:clear
```

Pastikan konfigurasi terbaca di `config/services.php` → bagian `gemini`.

---

## 4. Cara Menggunakan

### Di Halaman Dokumen Word:

1. Buka menu **Dokumen Word** (admin atau staf)
2. Klik tombol **"Buat dengan AI"** atau **"Generate AI"**
3. Isi form:
   - **Judul Dokumen**: Nama dokumen yang akan dibuat
   - **Jenis/Template**: Pilih jenis dokumen (opsional)
   - **Prompt AI**: Deskripsikan isi dokumen yang diinginkan
4. Klik **Generate**
5. Konten akan dihasilkan otomatis oleh Gemini AI
6. Edit hasil jika perlu, lalu simpan

### Contoh Prompt yang Baik:

| ❌ Kurang Baik | ✅ Lebih Baik |
|----------------|---------------|
| "buat surat" | "Buat surat undangan rapat komite sekolah tanggal 15 Maret 2026 pukul 09.00 di aula sekolah" |
| "buat laporan" | "Buat laporan kegiatan class meeting semester genap 2025/2026, berlangsung 3 hari, diikuti 500 siswa" |
| "SK" | "Buat SK pengangkatan panitia PPDB tahun ajaran 2026/2027, ketua panitia Bapak Ahmad" |

---

## 5. Template Dokumen

Sistem mendukung 8 jenis template dokumen:

| Jenis | Keterangan |
|-------|------------|
| `surat_resmi` | Surat dinas dengan format resmi |
| `laporan` | Laporan kegiatan/aktivitas |
| `proposal` | Proposal kegiatan atau anggaran |
| `sk` | Surat Keputusan resmi |
| `notulen` | Notulen / catatan rapat |
| `undangan` | Surat undangan kegiatan |
| `pengumuman` | Pengumuman resmi sekolah |
| `lainnya` | Format bebas sesuai kebutuhan |

AI akan menyesuaikan format dan bahasa berdasarkan template yang dipilih.

---

## 6. Troubleshooting

### AI tidak menghasilkan konten

| Masalah | Solusi |
|---------|--------|
| "API key belum dikonfigurasi" | Pastikan `GEMINI_API_KEY` sudah diisi di `.env` |
| Konten kosong / error | Cek koneksi internet server |
| "429 Too Many Requests" | Kuota harian habis, tunggu 24 jam atau upgrade plan |
| Konten tidak sesuai | Perjelas prompt dengan detail spesifik |
| Fallback ke template | Gemini tidak tersedia, konten dari template lokal |

### Memeriksa Log

```bash
# Lihat log error terbaru
tail -f storage/logs/laravel.log | grep -i "gemini"
```

Di Windows (PowerShell):
```powershell
Get-Content storage/logs/laravel.log -Tail 50 | Select-String "gemini" -CaseSensitive:$false
```

---

## 7. Batasan & Catatan

- **Bahasa**: AI sudah dikonfigurasi untuk menghasilkan konten dalam **Bahasa Indonesia** dengan format formal.
- **Konteks**: AI mengetahui konteks SMA Negeri 2 Jember (nama sekolah, kurikulum merdeka, dll).
- **Akurasi**: Hasil AI perlu ditinjau dan diedit sebelum digunakan secara resmi.
- **Privasi**: Prompt yang dikirim diproses oleh Google. Jangan kirim data sensitif (NIP, NIK, dll) dalam prompt.
- **Fallback**: Jika AI gagal, sistem otomatis menggunakan template lokal — fitur tetap berfungsi.

---

## Arsitektur Teknis

```
app/Services/GeminiAiService.php    → Service utama untuk komunikasi dengan Gemini API
config/services.php                 → Konfigurasi API key dan model
.env                                → Penyimpanan API key (GEMINI_API_KEY, GEMINI_MODEL)

Controllers yang menggunakan:
├── app/Http/Controllers/Admin/WordDocumentController.php
└── app/Http/Controllers/Staff/WordDocumentController.php
```
