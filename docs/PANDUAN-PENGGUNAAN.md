# Panduan Penggunaan — Sistem TU Administrasi

> **Versi:** 0.1  
> **Terakhir diperbarui:** 3 Maret 2026  
> **Aplikasi:** Sistem Administrasi Tata Usaha — SMA Negeri 2 Jember

---

## Daftar Isi

1. [Pendahuluan](#1-pendahuluan)
2. [Login & Autentikasi](#2-login--autentikasi)
3. [Dashboard](#3-dashboard)
4. [Manajemen Staff / Pegawai](#4-manajemen-staff--pegawai)
5. [Kehadiran / Absensi](#5-kehadiran--absensi)
6. [Pengajuan Izin / Cuti](#6-pengajuan-izin--cuti)
7. [Surat Menyurat](#7-surat-menyurat)
8. [Dokumen & Arsip](#8-dokumen--arsip)
9. [Laporan](#9-laporan)
10. [Kurikulum & Akademik](#10-kurikulum--akademik)
11. [Kesiswaan](#11-kesiswaan)
12. [Inventaris / Sarana Prasarana](#12-inventaris--sarana-prasarana)
13. [Keuangan & Anggaran](#13-keuangan--anggaran)
14. [Evaluasi Kinerja](#14-evaluasi-kinerja)
15. [Akreditasi & EDS](#15-akreditasi--eds)
16. [Agenda & Event](#16-agenda--event)
17. [Notifikasi & Pengumuman](#17-notifikasi--pengumuman)
18. [Pengingat (Reminder)](#18-pengingat-reminder)
19. [Profil & Akun](#19-profil--akun)
20. [FAQ / Pertanyaan Umum](#20-faq--pertanyaan-umum)

---

## 1. Pendahuluan

Sistem **TU Administrasi** adalah aplikasi berbasis web yang dirancang untuk membantu pengelolaan administrasi Tata Usaha di SMA Negeri 2 Jember. Sistem ini memiliki **10 peran pengguna**:

| Peran | Akses |
|-------|-------|
| **Admin (Kepala TU)** | Akses penuh ke seluruh modul: kelola staff, kehadiran, surat, dokumen, keuangan, evaluasi, akreditasi, dll. |
| **Kepala Sekolah** | Supervisor, read-only + approve izin/SKP |
| **Staf Kepegawaian (IKI 1)** | Urusan kepegawaian, SKP, evaluasi guru |
| **Pramu Bakti (IKI 2)** | Laporan kerja, kerusakan & perbaikan |
| **Staf Keuangan (IKI 3)** | Laporan & dokumen keuangan |
| **Staf Persuratan (IKI 4)** | Surat masuk/keluar, arsip dokumen |
| **Staf Perpustakaan (IKI 5)** | Koleksi & dokumen perpustakaan |
| **Staf Inventaris (IKI 6)** | Barang inventaris, kerusakan, laporan |
| **Staf Kesiswaan & Kurikulum (IKI 7)** | Data siswa, kurikulum, evaluasi P5 |
| **Staf Umum** | Akses dasar (fallback) |

### Kebutuhan Sistem
- Browser modern (Chrome, Firefox, Edge)
- Koneksi internet stabil
- GPS aktif (untuk fitur absensi)

---

## 2. Login & Autentikasi

### Cara Login
1. Buka aplikasi di browser
2. Masukkan **Email** dan **Password**
3. Klik tombol **Login**
4. Sistem akan mengarahkan ke dashboard sesuai peran (Admin/Staff)

### Catatan Penting
- Akun yang tidak aktif tidak bisa login
- Jika lupa password, hubungi Admin untuk reset
- Sesi login akan berakhir jika tidak aktif dalam waktu tertentu

---

## 3. Dashboard

### Dashboard Admin
Menampilkan ringkasan:
- Jumlah total staff aktif
- Kehadiran hari ini
- Pengajuan izin yang menunggu persetujuan
- Pengingat yang jatuh tempo
- Grafik kehadiran bulanan
- Daftar event mendatang

### Dashboard Staff
Menampilkan:
- Status kehadiran hari ini (sudah/belum clock in)
- Pengajuan izin terakhir
- Notifikasi belum dibaca
- Event mendatang
- Pengingat aktif

---

## 4. Manajemen Staff / Pegawai

> **Akses:** Khusus Admin

### Lihat Daftar Staff
- Navigasi: **Sidebar → Data Staff → Semua Staff**
- Fitur: Pencarian, filter status aktif/nonaktif

### Tambah Staff Baru
1. Klik **Tambah Staff Baru** dari sidebar
2. Isi formulir:
   - Nama Lengkap
   - Email (harus unik)
   - Password
   - No. Telepon
   - Jabatan / Posisi
   - Alamat
3. Klik **Simpan**

### Edit Data Staff
1. Dari daftar staff, klik ikon **Edit** pada baris staff
2. Ubah data yang diperlukan
3. Klik **Simpan Perubahan**

### Aktifkan / Nonaktifkan Staff
- Klik tombol **Toggle Status** pada daftar staff
- Staff yang dinonaktifkan tidak bisa login

### Export & Import Data
- **Export:** Klik tombol **Cetak Data Staff** → file PDF/Excel akan diunduh
- **Import:** Klik tombol **Import** → pilih file Excel → data akan diproses

---

## 5. Kehadiran / Absensi

### Untuk Staff — Clock In / Clock Out
1. Navigasi: **Sidebar → Absensi → Absen Hari Ini**
2. Klik tombol **Clock In** di pagi hari
   - Sistem akan meminta izin GPS dan kamera
   - Foto selfie akan diambil otomatis
   - Lokasi GPS dicatat
3. Klik tombol **Clock Out** di sore/malam hari
4. Status kehadiran otomatis:
   - **Hadir** — jika clock-in sebelum batas waktu
   - **Terlambat** — jika clock-in melewati jam masuk + toleransi

### Pengaturan Kehadiran (Admin)
- Navigasi: **Sidebar → Kehadiran → Pengaturan Absensi**
- Atur:
  - Jam masuk & jam pulang
  - Toleransi terlambat (dalam menit)
  - Koordinat kantor (latitude & longitude)
  - Jarak maksimum dari kantor (dalam meter)

### Rekap Kehadiran (Admin)
- Navigasi: **Sidebar → Kehadiran → Rekap Kehadiran**
- Filter berdasarkan tanggal, bulan, staff tertentu
- Export ke PDF/Excel

---

## 6. Pengajuan Izin / Cuti

### Untuk Staff — Mengajukan Izin
1. Navigasi: **Sidebar → Pengajuan Izin → Ajukan Izin Baru**
2. Isi formulir:
   - Tipe: Cuti / Izin / Sakit
   - Tanggal Mulai & Tanggal Selesai
   - Alasan
   - Lampiran (opsional, misal surat dokter)
3. Klik **Ajukan**
4. Status awal: **Pending**

### Untuk Admin — Persetujuan Izin
1. Navigasi: **Sidebar → Pengajuan Izin → Menunggu Persetujuan**
2. Klik detail pengajuan
3. Pilih:
   - **Setujui** — status berubah menjadi Approved
   - **Tolak** — status berubah menjadi Rejected
4. Isi catatan admin (opsional)
5. Staff akan mendapat notifikasi

---

## 7. Surat Menyurat

### Membuat Surat Baru
1. Navigasi: **Sidebar → Surat Menyurat → Buat Surat Baru**
2. Isi formulir:
   - Jenis: **Masuk** atau **Keluar**
   - Kategori: Dinas, Undangan, Keterangan, Keputusan, Edaran, Surat Tugas, Pemberitahuan
   - Perihal, Isi, Tujuan/Asal
   - Tanggal Surat, Sifat
   - Upload file lampiran (opsional)
3. **Nomor surat akan di-generate otomatis** oleh sistem
   - Format: `{nomor}/{SM|SK}/{kode_kategori}/TU-SMA2/{bulan}/{tahun}`
   - Contoh: `001/SK/DN/TU-SMA2/03/2026`

### Alur Status Surat
```
Draft → Diproses → Dikirim → Diterima → Diarsipkan
```

### Update Status (Admin)
1. Buka detail surat
2. Ubah status sesuai tahapan
3. Isi catatan (opsional)
4. Staff terkait akan mendapat notifikasi

---

## 8. Dokumen & Arsip

### Upload Dokumen
1. Navigasi: **Sidebar → Dokumen & Arsip → Upload Dokumen** (Admin) atau **Semua Dokumen** (Staff)
2. Isi: Judul, Deskripsi, Kategori
3. Pilih file (PDF, DOC, XLS, PPT, Gambar)
4. Klik **Upload**

### Format File yang Didukung
| Format | Ikon |
|--------|------|
| PDF | 📕 Merah |
| DOC/DOCX | 📘 Biru |
| XLS/XLSX | 📗 Hijau |
| PPT/PPTX | 📙 Kuning |
| JPG/PNG/GIF | 🖼️ Biru Muda |

### Kategori Dokumen
- Surat Menyurat
- Keuangan
- Kepegawaian
- Administrasi
- Lainnya

---

## 9. Laporan

### Membuat Laporan (Staff)
1. Navigasi: **Sidebar → Laporan → Buat Laporan**
2. Isi formulir:
   - Judul
   - Deskripsi
   - Kategori: Surat Masuk, Surat Keluar, Inventaris, Keuangan, Kegiatan, Lainnya
   - Prioritas: Low, Medium, High
   - Lampiran (opsional)
3. Simpan sebagai Draft atau langsung Submit

### Alur Status Laporan
```
Draft → Submitted → Reviewed → Completed
```

### Review Laporan (Admin)
1. Navigasi: **Sidebar → Laporan → Semua Laporan**
2. Filter berdasarkan kategori (Keuangan, Inventaris, dll.)
3. Buka detail → ubah status
4. Staff pembuat akan mendapat notifikasi

---

## 10. Kurikulum & Akademik

### Upload Dokumen Kurikulum (Admin)
1. Navigasi: **Sidebar → Kurikulum → Tambah Dokumen**
2. Isi formulir:
   - Judul, Deskripsi
   - Tipe dokumen:

| Kode | Tipe Dokumen |
|------|--------------|
| kalender_pendidikan | Kalender Pendidikan |
| jadwal_pelajaran | Jadwal Pelajaran |
| rpp | RPP / Modul Ajar |
| silabus | Silabus |
| modul_ajar | Modul Ajar |
| kisi_kisi | Kisi-kisi Soal |
| analisis_butir_soal | Analisis Butir Soal |
| berita_acara_ujian | Berita Acara Ujian |
| daftar_nilai | Daftar Nilai Siswa |
| rekap_nilai | Rekap Nilai Semester |
| leger | Leger Nilai |
| raport | Raport |

   - Tahun Akademik, Semester, Mata Pelajaran, Tingkat Kelas
   - Upload file
3. Klik **Simpan**

### Untuk Staff
- Staff dapat **melihat** semua dokumen kurikulum
- Staff juga dapat **mengupload** dokumen kurikulum (RPP, Modul Ajar, dll.)

---

## 11. Kesiswaan

### Tambah Data Siswa (Admin)
1. Navigasi: **Sidebar → Kesiswaan → Tambah Siswa**
2. Isi formulir:
   - NIS, NISN, Nama Lengkap
   - Kelas, Tahun Akademik
   - Jenis Kelamin, Tempat & Tanggal Lahir, Agama
   - Alamat, Nama Orang Tua, No. HP Orang Tua
   - Foto, Tanggal Masuk
   - Status: Aktif, Mutasi Masuk, Mutasi Keluar, Lulus, Drop Out

### Fitur Kesiswaan
- **Lihat Detail Siswa** — menampilkan data lengkap, prestasi, dan pelanggaran
- **Tambah Prestasi** — catat prestasi siswa (judul, level, jenis, penyelenggara, hasil)
- **Tambah Pelanggaran** — catat pelanggaran siswa (jenis, deskripsi, tindakan)

### Untuk Staff
- Staff dapat **melihat** daftar siswa dan detail siswa

---

## 12. Inventaris / Sarana Prasarana

### Tambah Barang Inventaris (Admin)
1. Navigasi: **Sidebar → Inventaris → Tambah Barang**
2. Pilih Kategori:

| Kode | Kategori |
|------|----------|
| MBL | Mebeulair |
| ELK | Elektronik |
| BKU | Buku |
| LAB | Alat Lab |
| OLR | Olahraga |
| LNY | Lainnya |

3. **Kode barang otomatis** di-generate: `{PREFIX}-{0001}`
   - Contoh: `ELK-0001`, `BKU-0015`
4. Isi: Nama barang, deskripsi, lokasi, jumlah, kondisi, sumber dana, harga
5. Upload foto (opsional)

### Kondisi Barang
- **Baik** — 🟢 hijau
- **Rusak Ringan** — 🟡 kuning
- **Rusak Berat** — 🔴 merah

### Laporkan Kerusakan (Staff)
1. Navigasi: **Sidebar → Inventaris → Daftar Inventaris**
2. Pilih barang → klik **Laporkan Kerusakan**
3. Isi: deskripsi kerusakan, tingkat kerusakan, upload foto
4. Admin akan mendapat notifikasi

---

## 13. Keuangan & Anggaran

> **Akses:** Khusus Admin

### Tambah Transaksi
1. Navigasi: **Sidebar → Keuangan → Tambah Transaksi**
2. Isi formulir:
   - Jenis: **Pemasukan** atau **Pengeluaran**
   - Kategori, Uraian, Jumlah, Tanggal
   - Upload bukti transaksi (opsional)
3. **Kode transaksi otomatis**: `{IN|OUT}-{YYYYMM}-{0001}`
   - Contoh: `IN-202603-0001`, `OUT-202603-0003`

### Kelola Anggaran (RKAS)
1. Navigasi: **Sidebar → Keuangan → RKAS / Anggaran**
2. Tambah anggaran:
   - Nama anggaran, Tahun, Sumber dana
   - Total anggaran
3. Sistem **otomatis menghitung**:
   - Sisa anggaran = Total - Terpakai
   - Persentase terpakai

### Verifikasi Transaksi
- Admin dapat memverifikasi setiap transaksi yang masuk
- Status: belum diverifikasi → terverifikasi

---

## 14. Evaluasi Kinerja

### PKG / BKD / SKP (Admin)
1. Navigasi: **Sidebar → Evaluasi Kinerja → PKG / BKD / SKP**
2. Buat evaluasi baru:
   - Pilih staff yang dievaluasi
   - Jenis: PKG (Penilaian Kinerja Guru), BKD (Beban Kerja Dosen/Guru), SKP (Sasaran Kinerja Pegawai)
   - Periode, Nilai, Predikat, Catatan
   - Upload file pendukung

### Asesmen P5 — Projek Penguatan Profil Pelajar Pancasila
1. Navigasi: **Sidebar → Evaluasi Kinerja → Asesmen P5**
2. Isi: Tema, Judul Projek, Kelas, Fase, Dimensi:
   - Beriman, Bertakwa & Berakhlak Mulia
   - Mandiri
   - Gotong Royong
   - Berkebinekaan Global
   - Bernalar Kritis
   - Kreatif

### Metode STAR (Admin & Staff)
Analisis menggunakan metode **STAR**:
1. **S**ituation — Gambaran situasi
2. **T**ask — Tugas yang harus dilakukan
3. **A**ction — Tindakan yang diambil
4. **R**esult — Hasil yang dicapai
5. Tambahkan Refleksi & Tindak Lanjut

### Bukti Fisik (Admin & Staff)
- Upload bukti fisik untuk mendukung evaluasi/akreditasi
- Kategori: PKG, Akreditasi, Kurikulum, dll.

### Model Pembelajaran (Admin & Staff)
- Dokumentasikan model/metode pembelajaran yang digunakan
- Jenis: Model Pembelajaran, Teknologi Pembelajaran, Media Pembelajaran

---

## 15. Akreditasi & EDS

> **Akses:** Khusus Admin

### Upload Dokumen Akreditasi
1. Navigasi: **Sidebar → Akreditasi → Tambah Dokumen**
2. Pilih Standar:

| Standar | Deskripsi |
|---------|-----------|
| Standar Isi | Materi dan kompetensi yang diajarkan |
| Standar Proses | Proses pembelajaran |
| Standar Kompetensi Lulusan | Target kompetensi lulusan |
| Standar Pendidik | Kualifikasi guru & tenaga kependidikan |
| Standar Sarpras | Sarana & prasarana |
| Standar Pengelolaan | Manajemen sekolah |
| Standar Pembiayaan | Pengelolaan keuangan |
| Standar Penilaian | Sistem penilaian |

3. Isi: Komponen, Indikator, Deskripsi, Upload file

### Evaluasi Diri Sekolah (EDS)
1. Navigasi: **Sidebar → Akreditasi → Evaluasi Diri (EDS)**
2. Isi:
   - Tahun, Aspek yang dievaluasi
   - Kondisi Saat Ini
   - Target yang ingin dicapai
   - Program Tindak Lanjut

---

## 16. Agenda & Event

### Membuat Event (Admin)
1. Navigasi: **Sidebar → Agenda & Event → Buat Event Baru**
2. Isi formulir:
   - Judul, Deskripsi
   - Tanggal, Waktu Mulai, Waktu Selesai
   - Lokasi
   - Tipe: Rapat, Kegiatan, Upacara, Pelatihan, Lainnya
3. Semua staff akan mendapat notifikasi

### Status Event
- **Upcoming** — belum dimulai (🔵 biru)
- **Ongoing** — sedang berlangsung (🟢 hijau)
- **Completed** — selesai (⚫ abu)
- **Cancelled** — dibatalkan (🔴 merah)

### Untuk Staff
- Staff dapat **melihat** semua event
- Filter berdasarkan tipe (Rapat, Kegiatan, dll.)

---

## 17. Notifikasi & Pengumuman

### Untuk Admin — Kirim Pengumuman
1. Navigasi: **Sidebar → Notifikasi → Kirim Pengumuman**
2. Isi: Judul, Pesan, Pilih user tujuan, Tipe
3. Notifikasi akan muncul di dashboard dan dropdown staff

### Untuk Staff — Lihat Notifikasi
- Klik ikon 🔔 di header untuk melihat notifikasi terbaru
- Navigasi: **Sidebar → Notifikasi → Semua Notifikasi**
- **Tandai Sudah Dibaca** — klik per notifikasi
- **Tandai Semua Sudah Dibaca** — klik tombol di dropdown

### Tipe Notifikasi
| Tipe | Warna | Deskripsi |
|------|-------|-----------|
| Kehadiran | 🟢 Hijau | Terkait absensi |
| Izin | 🔵 Biru Muda | Pengajuan izin/cuti |
| Event | 🔵 Biru | Agenda kegiatan |
| Laporan | 🟡 Kuning | Status laporan |
| Sistem | 🔴 Merah | Notifikasi sistem |
| Pengumuman | ⚫ Gelap | Pengumuman resmi |

### Notifikasi Otomatis
Sistem mengirim notifikasi otomatis saat:
- Pengajuan izin disetujui/ditolak
- Event baru dibuat
- Status laporan/surat berubah
- Ada laporan kerusakan inventaris
- Pengingat jatuh tempo

---

## 18. Pengingat (Reminder)

### Membuat Pengingat (Admin)
1. Navigasi: **Sidebar → Pengingat → Buat Pengingat**
2. Isi formulir:
   - Judul, Deskripsi
   - Tipe: Deadline Laporan, Pengumpulan BKD, Evaluasi Semester, Tugas, Lainnya
   - Tanggal Jatuh Tempo
   - Pilih Staff tujuan
   - Recurring: Harian / Mingguan / Bulanan (opsional)
3. Staff akan mendapat notifikasi

### Status Pengingat
- **Aktif** — belum selesai, belum jatuh tempo
- **Jatuh Tempo** — sudah melewati deadline 🟡
- **Selesai** — sudah ditandai selesai ✅

### Untuk Staff
- Lihat pengingat yang ditugaskan
- Tandai pengingat sebagai **Selesai**

---

## 19. Profil & Akun

> **Akses:** Staff

### Edit Profil
1. Navigasi: **Sidebar → Akun Saya → Edit Profil**
2. Ubah: Nama, Email, Telepon, Jabatan, Alamat, Foto
3. Klik **Simpan**

### Ubah Password
1. Navigasi: **Sidebar → Akun Saya → Ubah Password**
2. Masukkan: Password Lama, Password Baru, Konfirmasi Password
3. Klik **Ubah Password**

---

## 20. FAQ / Pertanyaan Umum

### Q: Saya tidak bisa login, apa yang harus dilakukan?
**A:** Pastikan email dan password benar. Jika masih gagal, hubungi Admin karena mungkin akun Anda dinonaktifkan.

### Q: GPS saya tidak terdeteksi saat clock-in?
**A:** Pastikan izin lokasi (GPS) di browser Anda diaktifkan. Aktifkan GPS di perangkat dan refresh halaman.

### Q: Bagaimana cara melihat riwayat kehadiran saya?
**A:** Navigasi ke **Sidebar → Absensi → Riwayat Kehadiran**.

### Q: Saya lupa password, bagaimana cara reset?
**A:** Hubungi Admin untuk mereset password akun Anda.

### Q: Bagaimana cara upload dokumen?
**A:** Navigasi ke **Sidebar → Dokumen & Arsip → Semua Dokumen** → klik Upload, atau minta Admin untuk upload melalui menu admin.

### Q: Apa itu P5?
**A:** P5 (Projek Penguatan Profil Pelajar Pancasila) adalah asesmen berbasis proyek yang mengacu pada 6 dimensi Profil Pelajar Pancasila dalam Kurikulum Merdeka.

### Q: Apa itu Metode STAR?
**A:** STAR adalah metode analisis yang terdiri dari **Situation** (Situasi), **Task** (Tugas), **Action** (Aksi), dan **Result** (Hasil). Digunakan untuk mendokumentasikan dan mengevaluasi kinerja.

### Q: Bagaimana cara melaporkan kerusakan inventaris?
**A:** Navigasi ke **Sidebar → Inventaris → Daftar Inventaris** → pilih barang → klik **Laporkan Kerusakan**.

---

## Kontak Support

Jika mengalami kendala teknis, hubungi:
- **Admin TU:** Melalui menu Notifikasi di aplikasi
- **Developer:** Hubungi tim IT sekolah

---

> *Dokumen ini dibuat untuk pedoman penggunaan Sistem TU Administrasi SMA Negeri 2 Jember.*
