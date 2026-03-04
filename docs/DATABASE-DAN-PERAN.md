# Dokumentasi Database & Sistem Peran

## Sistem Administrasi Tata Usaha — SMA Negeri 2 Jember

> **Stack**: Laravel 12 · PHP 8.5 · MySQL  
> **Total Tabel**: 32 (termasuk tabel sistem Laravel)

---

## Daftar Isi

1. [Sistem Peran (Role)](#1-sistem-peran-role)
2. [Hak Akses per Peran](#2-hak-akses-per-peran)
3. [Struktur Menu Sidebar per Peran](#3-struktur-menu-sidebar-per-peran)
4. [Skema Database](#4-skema-database)
5. [Relasi Antar Tabel](#5-relasi-antar-tabel)

---

## 1. Sistem Peran (Role)

Sistem menggunakan kolom `peran` (enum) pada tabel `pengguna` untuk menentukan peran pengguna.

| Kode Peran | Nama Tampilan | IKI | Keterangan |
|---|---|---|---|
| `admin` | Administrator / Kepala TU | — | Akses penuh ke seluruh modul |
| `kepala_sekolah` | Kepala Sekolah | — | Supervisor, read-only + approve |
| `kepegawaian` | Staf Kepegawaian | IKI 1 | Urusan kepegawaian, SKP, evaluasi |
| `pramu_bakti` | Pramu Bakti | IKI 2 | Laporan kerja, kerusakan & perbaikan |
| `keuangan` | Staf Keuangan | IKI 3 | Laporan & dokumen keuangan |
| `persuratan` | Staf Persuratan | IKI 4 | Surat masuk/keluar, arsip dokumen |
| `perpustakaan` | Staf Perpustakaan | IKI 5 | Koleksi & dokumen perpustakaan |
| `inventaris` | Staf Inventaris | IKI 6 | Barang inventaris, kerusakan, laporan |
| `kesiswaan_kurikulum` | Staf Kesiswaan & Kurikulum | IKI 7 | Data siswa, kurikulum, evaluasi P5 |
| `staff` | Staf Umum | — | Akses dasar (fallback) |

### Routing

| Peran | Prefix URL | Route Name | Middleware |
|---|---|---|---|
| Admin | `/admin` | `admin.*` | `auth, role:admin` |
| Kepala Sekolah | `/kepala-sekolah` | `kepala-sekolah.*` | `auth, role:kepala_sekolah` |
| Semua Staf (IKI 1-7 + staff) | `/staf` | `staf.*` | `auth, role:all_staff` |

---

## 2. Hak Akses per Peran

### Admin (Kepala TU)
| Modul | Akses |
|---|---|
| Beranda | Dashboard statistik lengkap |
| Pegawai | CRUD + ekspor/impor + toggle status |
| Kehadiran | Lihat semua + laporan + pengaturan lokasi |
| Pengajuan Izin | Lihat + setujui/tolak |
| Laporan | Lihat semua + update status |
| Agenda/Acara | CRUD lengkap |
| Notifikasi | Lihat + buat + hapus |
| Surat | CRUD + update status |
| Dokumen & Arsip | CRUD + ekspor |
| Kurikulum | CRUD |
| Kesiswaan | CRUD |
| Inventaris | CRUD |
| Keuangan | CRUD + anggaran + verifikasi |
| Evaluasi | PKG, P5, STAR, Bukti Fisik, Pembelajaran (CRUD) |
| Akreditasi | CRUD + EDS |
| Pengingat | CRUD + toggle selesai |
| Word & AI | CRUD + AI generate + template + unduh |
| Ulang Tahun | Lihat + kirim ucapan |
| Catatan Beranda | CRUD |
| Panduan | Lihat |

### Kepala Sekolah (Supervisor)
| Modul | Akses |
|---|---|
| Beranda | Dashboard statistik |
| Pegawai | **Read-only** |
| Kehadiran | **Read-only** + laporan |
| Pengajuan Izin | Lihat + setujui/tolak |
| SKP | Lihat + setujui/tolak |
| Evaluasi | PKG, STAR, Bukti Fisik (**read-only**) |
| Surat | **Read-only** |
| Laporan | **Read-only** |
| Keuangan | **Read-only** |
| Agenda | **Read-only** |
| Notifikasi | Lihat + baca semua |
| Profil | Edit profil + ganti password |
| Ulang Tahun | Lihat + kirim ucapan |
| Catatan Beranda | CRUD |
| Panduan | Lihat |

### Staf (Semua IKI — Menu Bersama)
| Modul | Akses |
|---|---|
| Beranda | Dashboard personal |
| Kehadiran | Clock in/out + lihat riwayat + catatan |
| Pengajuan Izin | CRUD (milik sendiri) |
| Laporan | CRUD (milik sendiri) |
| Agenda | **Read-only** |
| Notifikasi | Lihat + tandai baca |
| Surat | Lihat + buat |
| Dokumen | Lihat + upload |
| Kurikulum | Lihat + upload |
| Kesiswaan | Lihat |
| Inventaris | Lihat + lapor kerusakan |
| Evaluasi | PKG/P5 (read-only), STAR/Bukti Fisik/Pembelajaran (CRUD) |
| Pengingat | Lihat + selesaikan |
| SKP | CRUD (milik sendiri) |
| Profil | Edit profil + ganti password |
| Word & AI | CRUD + AI generate + template + unduh |
| Ulang Tahun | Lihat + kirim ucapan |
| Catatan Beranda | CRUD |
| Panduan | Lihat |

---

## 3. Struktur Menu Sidebar per Peran

### Menu IKI 1 — Kepegawaian (`menu-kepegawaian.blade.php`)
- SKP (Sasaran Kinerja Pegawai)
- Evaluasi Guru (PKG, STAR, Bukti Fisik)

### Menu IKI 2 — Pramu Bakti (`menu-pramu-bakti.blade.php`)
- Laporan Kerja
- Kerusakan & Perbaikan

### Menu IKI 3 — Keuangan (`menu-keuangan.blade.php`)
- Laporan Keuangan
- Dokumen Keuangan

### Menu IKI 4 — Persuratan (`menu-persuratan.blade.php`)
- Surat Masuk / Surat Keluar / Buat Surat
- Arsip Dokumen

### Menu IKI 5 — Perpustakaan (`menu-perpustakaan.blade.php`)
- Koleksi & Dokumen
- Laporan

### Menu IKI 6 — Inventaris (`menu-inventaris.blade.php`)
- Daftar Inventaris
- Laporan Kerusakan
- Dokumen Inventaris

### Menu IKI 7 — Kesiswaan & Kurikulum (`menu-kesiswaan-kurikulum.blade.php`)
- Kesiswaan (Data Siswa, Prestasi, Pelanggaran)
- Kurikulum (Dokumen Kurikulum)
- Evaluasi (P5, Pembelajaran)
- Laporan

---

## 4. Skema Database

### 4.1 Tabel `pengguna` _(users → pengguna)_

Tabel utama data pengguna/pegawai sistem.

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | bigint (PK, AI) | Primary Key |
| `nama` | string | Nama lengkap _(name → nama)_ |
| `nip` | string, nullable | Nomor Induk Pegawai |
| `email` | string, unique | Email login |
| `email_verified_at` | timestamp, nullable | Waktu verifikasi email |
| `password` | string | Password terenkripsi |
| `peran` | enum | Peran pengguna (lihat Bab 1) |
| `telepon` | string, nullable | Nomor telepon _(phone → telepon)_ |
| `tanggal_lahir` | date, nullable | Tanggal lahir |
| `jabatan` | string, nullable | Jabatan _(position → jabatan)_ |
| `kode_depan` | string, nullable | Kode depan gelar |
| `iki_pelaksana` | string, nullable | Kode IKI pelaksana |
| `foto` | string, nullable | Path foto profil _(photo → foto)_ |
| `alamat` | text, nullable | Alamat _(address → alamat)_ |
| `aktif` | boolean, default true | Status aktif _(is_active → aktif)_ |
| `remember_token` | string | Token "remember me" |
| `created_at` | timestamp | Dibuat pada |
| `updated_at` | timestamp | Diperbarui pada |

---

### 4.2 Tabel `kehadiran` _(attendances → kehadiran)_

Catatan kehadiran harian pegawai dengan GPS & foto.

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | bigint (PK) | |
| `pengguna_id` | bigint unsigned, FK → `pengguna` | _(user_id → pengguna_id)_ |
| `tanggal` | date | Tanggal kehadiran _(date → tanggal)_ |
| `jam_masuk` | time, nullable | _(clock_in → jam_masuk)_ |
| `jam_pulang` | time, nullable | _(clock_out → jam_pulang)_ |
| `status` | enum('hadir','terlambat','izin','sakit','alpha','cuti') | Default: 'hadir' |
| `lat_masuk` | decimal(10,8), nullable | Latitude masuk |
| `lng_masuk` | decimal(11,8), nullable | Longitude masuk |
| `alamat_masuk` | string, nullable | Alamat lokasi masuk |
| `lat_pulang` | decimal(10,8), nullable | Latitude pulang |
| `lng_pulang` | decimal(11,8), nullable | Longitude pulang |
| `alamat_pulang` | string, nullable | Alamat lokasi pulang |
| `foto_masuk` | string, nullable | Foto selfie masuk |
| `foto_pulang` | string, nullable | Foto selfie pulang |
| `catatan` | text, nullable | Catatan tambahan _(note → catatan)_ |
| `created_at` | timestamp | |
| `updated_at` | timestamp | |

> **UNIQUE**: (`pengguna_id`, `tanggal`)

---

### 4.3 Tabel `pengajuan_izin` _(leave_requests → pengajuan_izin)_

Pengajuan izin/cuti pegawai yang memerlukan persetujuan.

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | bigint (PK) | |
| `pengguna_id` | bigint unsigned, FK → `pengguna` | Pemohon |
| `jenis` | enum('izin','sakit','cuti','dinas_luar') | _(type → jenis)_ |
| `tanggal_mulai` | date | _(start_date → tanggal_mulai)_ |
| `tanggal_selesai` | date | _(end_date → tanggal_selesai)_ |
| `alasan` | text | _(reason → alasan)_ |
| `lampiran` | string, nullable | File lampiran _(attachment → lampiran)_ |
| `status` | enum('pending','approved','rejected') | Default: 'pending' |
| `disetujui_oleh` | bigint unsigned, nullable, FK → `pengguna` | _(approved_by → disetujui_oleh)_ |
| `catatan_admin` | text, nullable | Catatan dari admin _(admin_note → catatan_admin)_ |
| `created_at` | timestamp | |
| `updated_at` | timestamp | |

---

### 4.4 Tabel `pengaturan_kehadiran` _(attendance_settings → pengaturan_kehadiran)_

Pengaturan jam kerja dan radius lokasi kehadiran.

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | bigint (PK) | |
| `jam_masuk` | time, default '08:00:00' | Jam masuk standar |
| `jam_pulang` | time, default '16:00:00' | Jam pulang standar |
| `toleransi_terlambat_menit` | integer, default 15 | Toleransi keterlambatan |
| `lat_kantor` | decimal(10,8), nullable | Latitude kantor |
| `lng_kantor` | decimal(11,8), nullable | Longitude kantor |
| `jarak_maksimal_meter` | integer, default 200 | Radius maksimal absen |
| `created_at` | timestamp | |
| `updated_at` | timestamp | |

---

### 4.5 Tabel `laporan` _(reports → laporan)_

Laporan kerja pegawai.

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | bigint (PK) | |
| `pengguna_id` | bigint unsigned, FK → `pengguna` | Pembuat laporan |
| `judul` | string | _(title → judul)_ |
| `deskripsi` | text | _(description → deskripsi)_ |
| `kategori` | enum('surat_masuk','surat_keluar','inventaris','keuangan','kegiatan','lainnya') | Default: 'lainnya' |
| `prioritas` | enum('rendah','sedang','tinggi') | Default: 'sedang' |
| `status` | enum('draft','submitted','reviewed','completed') | Default: 'draft' |
| `lampiran` | string, nullable | _(attachment → lampiran)_ |
| `created_at` | timestamp | |
| `updated_at` | timestamp | |

---

### 4.6 Tabel `acara` _(events → acara)_

Agenda dan kegiatan sekolah.

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | bigint (PK) | |
| `dibuat_oleh` | bigint unsigned, FK → `pengguna` | _(created_by → dibuat_oleh)_ |
| `judul` | string | _(title → judul)_ |
| `deskripsi` | text, nullable | _(description → deskripsi)_ |
| `tanggal_acara` | date | _(event_date → tanggal_acara)_ |
| `waktu_mulai` | time, nullable | _(start_time → waktu_mulai)_ |
| `waktu_selesai` | time, nullable | _(end_time → waktu_selesai)_ |
| `lokasi` | string, nullable | _(location → lokasi)_ |
| `jenis` | enum('rapat','kegiatan','upacara','pelatihan','lainnya') | Default: 'kegiatan' |
| `status` | enum('upcoming','ongoing','completed','cancelled') | Default: 'upcoming' |
| `created_at` | timestamp | |
| `updated_at` | timestamp | |

---

### 4.7 Tabel `notifikasi` _(notifications → notifikasi)_

Notifikasi sistem untuk pengguna.

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | bigint (PK) | |
| `pengguna_id` | bigint unsigned, FK → `pengguna` | Penerima notifikasi |
| `judul` | string | _(title → judul)_ |
| `pesan` | text | _(message → pesan)_ |
| `jenis` | enum('kehadiran','izin','event','laporan','sistem','pengumuman') | Jenis notifikasi |
| `sudah_dibaca` | boolean, default false | _(is_read → sudah_dibaca)_ |
| `tautan` | string, nullable | URL tautan _(link → tautan)_ |
| `created_at` | timestamp | |
| `updated_at` | timestamp | |

---

### 4.8 Tabel `surat`

Manajemen surat masuk dan keluar.

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | bigint (PK) | |
| `nomor_surat` | string, unique | Nomor surat |
| `jenis` | enum('masuk','keluar') | Default: 'keluar' |
| `kategori` | enum('dinas','undangan','keterangan','keputusan','edaran','tugas','pemberitahuan','lainnya') | Default: 'dinas' |
| `perihal` | string | Perihal surat |
| `isi` | text, nullable | Isi/konten surat |
| `tujuan` | string, nullable | Tujuan surat |
| `asal` | string, nullable | Asal surat |
| `tanggal_surat` | date | Tanggal surat |
| `tanggal_terima` | date, nullable | Tanggal diterima |
| `status` | enum('draft','diproses','dikirim','diterima','diarsipkan') | Default: 'draft' |
| `sifat` | enum('biasa','penting','segera','rahasia') | Default: 'biasa' |
| `path_file` | string, nullable | Path file lampiran |
| `nama_file` | string, nullable | Nama file asli |
| `dibuat_oleh` | bigint unsigned, FK → `pengguna` | Pembuat surat |
| `disetujui_oleh` | bigint unsigned, nullable, FK → `pengguna` | Yang menyetujui |
| `catatan` | text, nullable | Catatan tambahan |
| `created_at` | timestamp | |
| `updated_at` | timestamp | |

---

### 4.9 Tabel `dokumen` _(documents → dokumen)_

Dokumen dan arsip umum.

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | bigint (PK) | |
| `judul` | string | _(title → judul)_ |
| `deskripsi` | text, nullable | _(description → deskripsi)_ |
| `kategori` | enum('kurikulum','administrasi','keuangan','kepegawaian','kesiswaan','surat','inventaris','lainnya') | Default: 'lainnya' |
| `path_file` | string | Path file _(file_path → path_file)_ |
| `nama_file` | string | Nama file asli _(file_name → nama_file)_ |
| `tipe_file` | string, nullable | Tipe MIME _(file_type → tipe_file)_ |
| `ukuran_file` | bigint unsigned, default 0 | Ukuran byte _(file_size → ukuran_file)_ |
| `diunggah_oleh` | bigint unsigned, FK → `pengguna` | _(uploaded_by → diunggah_oleh)_ |
| `created_at` | timestamp | |
| `updated_at` | timestamp | |

---

### 4.10 Tabel `template_dokumen` _(document_templates → template_dokumen)_

Template dokumen untuk pembuatan surat/dokumen otomatis.

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | bigint (PK) | |
| `nama` | string | Nama template _(name → nama)_ |
| `kode` | string, unique | Kode unik template _(code → kode)_ |
| `kategori` | string | Kategori _(category → kategori)_ |
| `konten` | text | Isi template HTML _(content → konten)_ |
| `kolom_isian` | json, nullable | Field yang bisa diisi _(fields → kolom_isian)_ |
| `format` | string, default 'pdf' | Format output |
| `aktif` | boolean, default true | Status aktif _(is_active → aktif)_ |
| `dibuat_oleh` | bigint unsigned, FK → `pengguna` | Pembuat template |
| `created_at` | timestamp | |
| `updated_at` | timestamp | |

---

### 4.11 Tabel `dokumen_word` _(word_documents → dokumen_word)_

Dokumen Word yang dibuat via editor + AI.

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | bigint (PK) | |
| `pengguna_id` | bigint unsigned, FK → `pengguna` | Pemilik dokumen |
| `judul` | string | _(title → judul)_ |
| `kategori` | string, default 'umum' | _(category → kategori)_ |
| `konten` | longText, nullable | Isi HTML dokumen _(content → konten)_ |
| `prompt_ai` | longText, nullable | Prompt AI yang digunakan _(ai_prompt → prompt_ai)_ |
| `templat` | string, nullable | Template yang digunakan _(template → templat)_ |
| `path_file` | string, nullable | Path file ekspor |
| `status` | string, default 'draft' | Status dokumen |
| `dibagikan` | boolean, default false | Dibagikan ke publik _(is_shared → dibagikan)_ |
| `created_at` | timestamp | |
| `updated_at` | timestamp | |

---

### 4.12 Tabel `dokumen_kurikulum` _(curriculum_documents → dokumen_kurikulum)_

Dokumen kurikulum sekolah.

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | bigint (PK) | |
| `judul` | string | _(title → judul)_ |
| `deskripsi` | text, nullable | _(description → deskripsi)_ |
| `jenis` | string | Jenis dokumen _(type → jenis)_ |
| `tahun_ajaran` | string | _(academic_year → tahun_ajaran)_ |
| `semester` | string, nullable | Semester |
| `mata_pelajaran` | string, nullable | _(subject → mata_pelajaran)_ |
| `tingkat_kelas` | string, nullable | _(class_level → tingkat_kelas)_ |
| `path_file` | string, nullable | Path file |
| `nama_file` | string, nullable | Nama file asli |
| `tipe_file` | string, nullable | Tipe MIME |
| `ukuran_file` | integer, nullable | Ukuran byte |
| `status` | string, default 'draft' | Status |
| `diunggah_oleh` | bigint unsigned, FK → `pengguna` | Yang mengunggah |
| `created_at` | timestamp | |
| `updated_at` | timestamp | |

---

### 4.13 Tabel `data_siswa` _(student_records → data_siswa)_

Data pokok siswa.

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | bigint (PK) | |
| `nis` | string, unique | Nomor Induk Siswa |
| `nisn` | string, nullable, unique | Nomor Induk Siswa Nasional |
| `nama` | string | Nama siswa _(name → nama)_ |
| `kelas` | string | Kelas _(class → kelas)_ |
| `tahun_ajaran` | string | _(academic_year → tahun_ajaran)_ |
| `jenis_kelamin` | enum('L','P') | _(gender → jenis_kelamin)_ |
| `tempat_lahir` | string, nullable | _(place_of_birth → tempat_lahir)_ |
| `tanggal_lahir` | date, nullable | _(date_of_birth → tanggal_lahir)_ |
| `agama` | string, nullable | _(religion → agama)_ |
| `alamat` | text, nullable | _(address → alamat)_ |
| `nama_orang_tua` | string, nullable | _(parent_name → nama_orang_tua)_ |
| `telepon_orang_tua` | string, nullable | _(parent_phone → telepon_orang_tua)_ |
| `foto` | string, nullable | Foto siswa |
| `status` | enum('aktif','mutasi_masuk','mutasi_keluar','lulus','do') | Default: 'aktif' |
| `tanggal_masuk` | date, nullable | _(entry_date → tanggal_masuk)_ |
| `tanggal_keluar` | date, nullable | _(exit_date → tanggal_keluar)_ |
| `catatan` | text, nullable | _(notes → catatan)_ |
| `dibuat_oleh` | bigint unsigned, FK → `pengguna` | Yang menginput |
| `created_at` | timestamp | |
| `updated_at` | timestamp | |

---

### 4.14 Tabel `prestasi_siswa` _(student_achievements → prestasi_siswa)_

Prestasi dan penghargaan siswa.

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | bigint (PK) | |
| `siswa_id` | bigint unsigned, FK → `data_siswa` | _(student_id → siswa_id)_ |
| `judul` | string | Nama prestasi _(title → judul)_ |
| `tingkat` | string | Tingkat (kabupaten/provinsi/nasional) _(level → tingkat)_ |
| `jenis` | string | Jenis (akademik/non-akademik) _(type → jenis)_ |
| `tanggal` | date | Tanggal perlombaan _(date → tanggal)_ |
| `penyelenggara` | string, nullable | _(organizer → penyelenggara)_ |
| `hasil` | string, nullable | Hasil/peringkat _(result → hasil)_ |
| `path_file` | string, nullable | File sertifikat |
| `created_at` | timestamp | |
| `updated_at` | timestamp | |

---

### 4.15 Tabel `pelanggaran_siswa` _(student_violations → pelanggaran_siswa)_

Catatan pelanggaran siswa.

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | bigint (PK) | |
| `siswa_id` | bigint unsigned, FK → `data_siswa` | |
| `tanggal` | date | Tanggal pelanggaran _(date → tanggal)_ |
| `jenis` | string | Jenis pelanggaran _(type → jenis)_ |
| `deskripsi` | text | Detail pelanggaran _(description → deskripsi)_ |
| `tindakan` | text, nullable | Tindakan yang diambil _(action_taken → tindakan)_ |
| `dilaporkan_oleh` | bigint unsigned, FK → `pengguna` | _(reported_by → dilaporkan_oleh)_ |
| `created_at` | timestamp | |
| `updated_at` | timestamp | |

---

### 4.16 Tabel `inventaris`

Data barang inventaris sekolah. _(Sudah berbahasa Indonesia sejak awal)_

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | bigint (PK) | |
| `kode_barang` | string, unique | Kode unik barang |
| `nama_barang` | string | Nama barang |
| `deskripsi` | text, nullable | Deskripsi |
| `kategori` | string | Kategori barang |
| `lokasi` | string | Lokasi penyimpanan |
| `jumlah` | integer, default 1 | Jumlah unit |
| `kondisi` | string, default 'baik' | Kondisi barang |
| `tanggal_perolehan` | date, nullable | Tanggal pembelian/perolehan |
| `sumber_dana` | string, nullable | Sumber dana |
| `harga_perolehan` | decimal(15,2), nullable | Harga perolehan |
| `foto` | string, nullable | Foto barang |
| `catatan` | text, nullable | Catatan |
| `dibuat_oleh` | bigint unsigned, FK → `pengguna` | Yang menginput |
| `created_at` | timestamp | |
| `updated_at` | timestamp | |

---

### 4.17 Tabel `laporan_kerusakan` _(damage_reports → laporan_kerusakan)_

Laporan kerusakan barang inventaris.

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | bigint (PK) | |
| `inventaris_id` | bigint unsigned, FK → `inventaris` | Barang yang rusak |
| `tanggal_laporan` | date | Tanggal laporan |
| `deskripsi_kerusakan` | text | Detail kerusakan |
| `tingkat_kerusakan` | string | Tingkat (ringan/sedang/berat) |
| `foto` | string, nullable | Foto kerusakan |
| `status` | string, default 'dilaporkan' | Status penanganan |
| `tindakan` | text, nullable | Tindakan perbaikan |
| `dilaporkan_oleh` | bigint unsigned, FK → `pengguna` | Yang melaporkan |
| `created_at` | timestamp | |
| `updated_at` | timestamp | |

---

### 4.18 Tabel `catatan_keuangan` _(finance_records → catatan_keuangan)_

Catatan transaksi keuangan sekolah.

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | bigint (PK) | |
| `kode_transaksi` | string, unique | Kode transaksi |
| `jenis` | string | Jenis (pemasukan/pengeluaran) |
| `kategori` | string | Kategori transaksi |
| `uraian` | string | Uraian transaksi |
| `jumlah` | decimal(15,2) | Nominal |
| `tanggal` | date | Tanggal transaksi |
| `bukti_path` | string, nullable | Path file bukti |
| `bukti_nama` | string, nullable | Nama file bukti |
| `keterangan` | text, nullable | Keterangan tambahan |
| `status` | string, default 'draft' | Status verifikasi |
| `dibuat_oleh` | bigint unsigned, FK → `pengguna` | Pembuat catatan |
| `diverifikasi_oleh` | bigint unsigned, nullable, FK → `pengguna` | Yang memverifikasi |
| `created_at` | timestamp | |
| `updated_at` | timestamp | |

---

### 4.19 Tabel `anggaran` _(budgets → anggaran)_

Data anggaran sekolah.

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | bigint (PK) | |
| `nama_anggaran` | string | Nama anggaran |
| `tahun_anggaran` | string | Tahun anggaran |
| `sumber_dana` | string | Sumber dana |
| `total_anggaran` | decimal(15,2) | Total anggaran |
| `terpakai` | decimal(15,2), default 0 | Yang sudah terpakai |
| `keterangan` | text, nullable | Keterangan |
| `status` | string, default 'draft' | Status |
| `dibuat_oleh` | bigint unsigned, FK → `pengguna` | Pembuat |
| `created_at` | timestamp | |
| `updated_at` | timestamp | |

---

### 4.20 Tabel `dokumen_akreditasi` _(accreditation_documents → dokumen_akreditasi)_

Dokumen akreditasi sekolah per standar/komponen.

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | bigint (PK) | |
| `standar` | string | Standar akreditasi |
| `komponen` | string | Komponen |
| `indikator` | string | Indikator |
| `deskripsi` | text, nullable | Deskripsi |
| `path_file` | string, nullable | Path file bukti |
| `nama_file` | string, nullable | Nama file |
| `status` | string, default 'belum_lengkap' | Status kelengkapan |
| `catatan` | text, nullable | Catatan |
| `diunggah_oleh` | bigint unsigned, FK → `pengguna` | Yang mengunggah |
| `created_at` | timestamp | |
| `updated_at` | timestamp | |

---

### 4.21 Tabel `evaluasi_sekolah` _(school_evaluations → evaluasi_sekolah)_

Evaluasi Diri Sekolah (EDS).

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | bigint (PK) | |
| `tahun` | string | Tahun evaluasi |
| `aspek` | string | Aspek yang dievaluasi |
| `kondisi_saat_ini` | text, nullable | Kondisi saat ini |
| `target` | text, nullable | Target yang ingin dicapai |
| `program_tindak_lanjut` | text, nullable | Program tindak lanjut |
| `status` | string, default 'draft' | Status |
| `dibuat_oleh` | bigint unsigned, FK → `pengguna` | Pembuat |
| `created_at` | timestamp | |
| `updated_at` | timestamp | |

---

### 4.22 Tabel `evaluasi_guru` _(teacher_evaluations → evaluasi_guru)_

Evaluasi kinerja guru/pegawai (PKG).

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | bigint (PK) | |
| `pengguna_id` | bigint unsigned, FK → `pengguna` | Yang dievaluasi |
| `periode` | string | Periode evaluasi |
| `jenis` | string | Jenis evaluasi (PKG, dll) |
| `nilai` | decimal(5,2), nullable | Nilai angka |
| `predikat` | string, nullable | Predikat (Amat Baik/Baik/dll) |
| `catatan` | text, nullable | Catatan |
| `path_file` | string, nullable | Path file dokumen |
| `nama_file` | string, nullable | Nama file |
| `status` | string, default 'draft' | Status |
| `dievaluasi_oleh` | bigint unsigned, nullable, FK → `pengguna` | Penilai |
| `created_at` | timestamp | |
| `updated_at` | timestamp | |

---

### 4.23 Tabel `penilaian_p5` _(p5_assessments → penilaian_p5)_

Penilaian Proyek Penguatan Profil Pelajar Pancasila.

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | bigint (PK) | |
| `tema` | string | Tema P5 |
| `judul_projek` | string | Judul proyek |
| `deskripsi` | text, nullable | Deskripsi proyek |
| `kelas` | string | Kelas |
| `fase` | string | Fase (A/B/C/D/E/F) |
| `tahun_ajaran` | string | Tahun ajaran |
| `semester` | string | Semester |
| `dimensi` | string | Dimensi profil pelajar |
| `target_capaian` | text, nullable | Target capaian |
| `path_file` | string, nullable | Path file dokumen |
| `nama_file` | string, nullable | Nama file |
| `status` | string, default 'draft' | Status |
| `dibuat_oleh` | bigint unsigned, FK → `pengguna` | Pembuat |
| `created_at` | timestamp | |
| `updated_at` | timestamp | |

---

### 4.24 Tabel `analisis_star` _(star_analyses → analisis_star)_

Analisis STAR (Situation, Task, Action, Result).

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | bigint (PK) | |
| `judul` | string | Judul analisis |
| `kategori` | string | Kategori |
| `situasi` | text | Situation _(situation → situasi)_ |
| `tugas` | text | Task _(task → tugas)_ |
| `aksi` | text | Action _(action → aksi)_ |
| `hasil` | text | Result _(result → hasil)_ |
| `refleksi` | text, nullable | Refleksi diri |
| `tindak_lanjut` | text, nullable | Rencana tindak lanjut |
| `path_file` | string, nullable | File pendukung |
| `dibuat_oleh` | bigint unsigned, FK → `pengguna` | Pembuat |
| `created_at` | timestamp | |
| `updated_at` | timestamp | |

---

### 4.25 Tabel `bukti_fisik` _(physical_evidences → bukti_fisik)_

Bukti fisik untuk evaluasi dan akreditasi.

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | bigint (PK) | |
| `judul` | string | Judul bukti |
| `kategori` | string | Kategori |
| `deskripsi` | text, nullable | Deskripsi |
| `path_file` | string | Path file |
| `nama_file` | string | Nama file asli |
| `tipe_file` | string, nullable | Tipe MIME |
| `ukuran_file` | integer, nullable | Ukuran byte |
| `terkait` | string, nullable | Terkait dengan (evaluasi/akreditasi) |
| `diunggah_oleh` | bigint unsigned, FK → `pengguna` | Yang mengunggah |
| `created_at` | timestamp | |
| `updated_at` | timestamp | |

---

### 4.26 Tabel `metode_pembelajaran` _(learning_methods → metode_pembelajaran)_

Dokumentasi metode pembelajaran.

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | bigint (PK) | |
| `nama_metode` | string | Nama metode |
| `jenis` | string | Jenis metode |
| `deskripsi` | text | Deskripsi lengkap |
| `langkah_pelaksanaan` | text, nullable | Langkah pelaksanaan |
| `kelebihan` | text, nullable | Kelebihan metode |
| `kekurangan` | text, nullable | Kekurangan metode |
| `hasil` | text, nullable | Hasil penerapan |
| `mata_pelajaran` | string, nullable | Mata pelajaran terkait |
| `path_file` | string, nullable | File pendukung |
| `nama_file` | string, nullable | Nama file |
| `status` | string, default 'draft' | Status |
| `dibuat_oleh` | bigint unsigned, FK → `pengguna` | Pembuat |
| `created_at` | timestamp | |
| `updated_at` | timestamp | |

---

### 4.27 Tabel `pengingat` _(reminders → pengingat)_

Pengingat/reminder untuk deadline dan tugas.

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | bigint (PK) | |
| `judul` | string | _(title → judul)_ |
| `deskripsi` | text, nullable | _(description → deskripsi)_ |
| `jenis` | string | Jenis reminder _(type → jenis)_ |
| `tenggat` | date | Tanggal deadline _(due_date → tenggat)_ |
| `waktu_pengingat` | time, nullable | Jam notifikasi _(reminder_time → waktu_pengingat)_ |
| `berulang` | boolean, default false | Pengingat berulang _(is_recurring → berulang)_ |
| `jenis_pengulangan` | string, nullable | Harian/mingguan/bulanan _(recurring_type → jenis_pengulangan)_ |
| `pengguna_id` | bigint unsigned, FK → `pengguna` | Ditujukan untuk |
| `dibuat_oleh` | bigint unsigned, FK → `pengguna` | Pembuat |
| `selesai` | boolean, default false | Status selesai _(is_completed → selesai)_ |
| `sudah_diberitahu` | boolean, default false | _(is_notified → sudah_diberitahu)_ |
| `created_at` | timestamp | |
| `updated_at` | timestamp | |

---

### 4.28 Tabel `skp` _(sudah bahasa Indonesia)_

Sasaran Kinerja Pegawai (SKP).

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | bigint (PK) | |
| `pengguna_id` | bigint unsigned, FK → `pengguna` | Pemilik SKP |
| `periode` | string | Periode SKP |
| `tahun` | year | Tahun |
| `sasaran_kinerja` | string | Sasaran yang ingin dicapai |
| `indikator_kinerja` | text, nullable | Indikator keberhasilan |
| `target_kuantitas` | decimal(8,2), default 0 | Target kuantitas |
| `realisasi_kuantitas` | decimal(8,2), default 0 | Realisasi kuantitas |
| `target_kualitas` | decimal(5,2), default 0 | Target kualitas |
| `realisasi_kualitas` | decimal(5,2), default 0 | Realisasi kualitas |
| `target_waktu` | decimal(8,2), default 0 | Target waktu |
| `realisasi_waktu` | decimal(8,2), default 0 | Realisasi waktu |
| `nilai_capaian` | decimal(5,2), default 0 | Nilai akhir |
| `predikat` | enum('sangat_baik','baik','cukup','kurang','buruk') | Default: 'baik' |
| `catatan` | text, nullable | Catatan |
| `status` | enum('draft','diajukan','disetujui','ditolak') | Default: 'draft' |
| `disetujui_oleh` | bigint unsigned, nullable, FK → `pengguna` | Yang menyetujui |
| `disetujui_pada` | timestamp, nullable | Waktu persetujuan |
| `created_at` | timestamp | |
| `updated_at` | timestamp | |

---

### 4.29 Tabel `ucapan_ulang_tahun` _(sudah bahasa Indonesia)_

Ucapan ulang tahun antar pengguna.

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | bigint (PK) | |
| `pengirim_id` | bigint unsigned, FK → `pengguna` | Yang mengirim ucapan |
| `penerima_id` | bigint unsigned, FK → `pengguna` | Yang berulang tahun |
| `pesan` | text | Isi ucapan |
| `tahun` | year | Tahun ucapan |
| `sudah_dibaca` | boolean, default false | Status baca |
| `created_at` | timestamp | |
| `updated_at` | timestamp | |

---

### 4.30 Tabel `catatan_beranda` _(sudah bahasa Indonesia)_

Catatan/sticky note di beranda pengguna.

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | bigint (PK) | |
| `pengguna_id` | bigint unsigned, FK → `pengguna` | Pemilik catatan |
| `judul` | string | Judul catatan |
| `isi` | text | Isi catatan |
| `warna` | string(20), default 'primary' | Warna kartu |
| `tanggal` | date, nullable | Tanggal terkait |
| `disematkan` | boolean, default false | Disematkan di atas |
| `created_at` | timestamp | |
| `updated_at` | timestamp | |

---

### 4.31 Tabel Sistem Laravel (Tidak Direname)

| Tabel | Keterangan |
|---|---|
| `password_reset_tokens` | Token reset password |
| `sessions` | Sesi pengguna aktif |
| `cache` | Cache aplikasi |
| `cache_locks` | Lock cache |
| `jobs` | Antrian pekerjaan |
| `job_batches` | Batch pekerjaan |
| `failed_jobs` | Pekerjaan yang gagal |

---

## 5. Relasi Antar Tabel

```
pengguna (PK: id)
├── kehadiran.pengguna_id
├── pengajuan_izin.pengguna_id
├── pengajuan_izin.disetujui_oleh
├── laporan.pengguna_id
├── acara.dibuat_oleh
├── notifikasi.pengguna_id
├── surat.dibuat_oleh
├── surat.disetujui_oleh
├── dokumen.diunggah_oleh
├── template_dokumen.dibuat_oleh
├── dokumen_word.pengguna_id
├── dokumen_kurikulum.diunggah_oleh
├── data_siswa.dibuat_oleh
├── pelanggaran_siswa.dilaporkan_oleh
├── inventaris.dibuat_oleh
├── laporan_kerusakan.dilaporkan_oleh
├── catatan_keuangan.dibuat_oleh
├── catatan_keuangan.diverifikasi_oleh
├── anggaran.dibuat_oleh
├── dokumen_akreditasi.diunggah_oleh
├── evaluasi_sekolah.dibuat_oleh
├── evaluasi_guru.pengguna_id
├── evaluasi_guru.dievaluasi_oleh
├── penilaian_p5.dibuat_oleh
├── analisis_star.dibuat_oleh
├── bukti_fisik.diunggah_oleh
├── metode_pembelajaran.dibuat_oleh
├── pengingat.pengguna_id
├── pengingat.dibuat_oleh
├── skp.pengguna_id
├── skp.disetujui_oleh
├── ucapan_ulang_tahun.pengirim_id
├── ucapan_ulang_tahun.penerima_id
└── catatan_beranda.pengguna_id

data_siswa (PK: id)
├── prestasi_siswa.siswa_id
└── pelanggaran_siswa.siswa_id

inventaris (PK: id)
└── laporan_kerusakan.inventaris_id
```

---

> **Catatan**: Semua nama tabel dan kolom sudah direname dari Bahasa Inggris ke Bahasa Indonesia, kecuali tabel sistem Laravel (`password_reset_tokens`, `sessions`, `cache`, `jobs`, dll.) dan kolom standar (`created_at`, `updated_at`, `remember_token`, `status`, `semester`, `format`).
