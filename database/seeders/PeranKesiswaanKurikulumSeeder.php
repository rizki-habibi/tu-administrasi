<?php

namespace Database\Seeders;

use App\Models\Kehadiran;
use App\Models\DokumenKurikulum;
use App\Models\Dokumen;
use App\Models\PengajuanIzin;
use App\Models\Notifikasi;
use App\Models\Laporan;
use App\Models\Skp;
use App\Models\PrestasiSiswa;
use App\Models\DataSiswa;
use App\Models\PelanggaranSiswa;
use App\Models\Surat;
use App\Models\Pengguna;
use App\Models\DokumenWord;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PeranKesiswaanKurikulumSeeder extends Seeder
{
    public function run(): void
    {
        $today = Carbon::today();
        $admin = Pengguna::where('email', 'admin@tu.test')->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | 1. AKUN KESISWAAN & KURIKULUM (IKI 7)
        |--------------------------------------------------------------------------
        */
        $bayu = Pengguna::updateOrCreate(
            ['email' => 'bayu.kesiswaan@tu.test'],
            [
                'nama'          => 'Bayu Adi Pratama',
                'password'      => Hash::make('password'),
                'peran'         => 'kesiswaan_kurikulum',
                'jabatan'       => 'Operator Layanan Operasional',
                'iki_pelaksana' => '7 KESISWAAN & KURIKULUM',
                'kode_depan'    => '14345',
                'telepon'       => '081298765015',
                'alamat'        => 'Jl. Gajah Mada No. 7, Jember',
                'aktif'         => true,
                'tanggal_lahir' => '1989-09-14',
            ]
        );

        $wikana = Pengguna::updateOrCreate(
            ['email' => 'wikana.kesiswaan@tu.test'],
            [
                'nama'          => 'Wikana Sari',
                'password'      => Hash::make('password'),
                'peran'         => 'kesiswaan_kurikulum',
                'jabatan'       => 'Operator Layanan Operasional',
                'iki_pelaksana' => '7 KESISWAAN & KURIKULUM',
                'kode_depan'    => '14345',
                'telepon'       => '081298765016',
                'alamat'        => 'Jl. Diponegoro No. 33, Jember',
                'aktif'         => true,
                'tanggal_lahir' => '1993-02-28',
            ]
        );

        $staffUsers = [$bayu, $wikana];

        /*
        |--------------------------------------------------------------------------
        | 2. KEHADIRAN (30 hari)
        |--------------------------------------------------------------------------
        */
        $this->seedKehadiran($staffUsers, $today);

        /*
        |--------------------------------------------------------------------------
        | 3. PENGAJUAN IZIN
        |--------------------------------------------------------------------------
        */
        PengajuanIzin::updateOrCreate(
            ['pengguna_id' => $bayu->id, 'tanggal_mulai' => $today->copy()->subDays(5)->format('Y-m-d')],
            ['jenis' => 'izin', 'tanggal_selesai' => $today->copy()->subDays(5)->format('Y-m-d'), 'alasan' => 'Mengantar istri periksa ke RS', 'status' => 'approved', 'disetujui_oleh' => $admin->id, 'catatan_admin' => 'Disetujui']
        );
        PengajuanIzin::updateOrCreate(
            ['pengguna_id' => $wikana->id, 'tanggal_mulai' => $today->copy()->addDays(8)->format('Y-m-d')],
            ['jenis' => 'cuti', 'tanggal_selesai' => $today->copy()->addDays(12)->format('Y-m-d'), 'alasan' => 'Cuti tahunan — liburan keluarga', 'status' => 'pending']
        );

        /*
        |--------------------------------------------------------------------------
        | 4. LAPORAN
        |--------------------------------------------------------------------------
        */
        Laporan::updateOrCreate(
            ['pengguna_id' => $bayu->id, 'judul' => 'Data PPDB TA 2025/2026'],
            ['deskripsi' => 'Rekapitulasi siswa baru: 288 pendaftar, 216 diterima, 72 cadangan.', 'kategori' => 'kegiatan', 'prioritas' => 'tinggi', 'status' => 'completed']
        );
        Laporan::updateOrCreate(
            ['pengguna_id' => $wikana->id, 'judul' => 'Rekap Absensi Siswa Semester Ganjil 2025/2026'],
            ['deskripsi' => 'Rata-rata kehadiran 94.5%. 12 siswa dengan absensi di bawah 75%.', 'kategori' => 'kegiatan', 'prioritas' => 'sedang', 'status' => 'submitted']
        );

        /*
        |--------------------------------------------------------------------------
        | 5. SKP
        |--------------------------------------------------------------------------
        */
        Skp::updateOrCreate(
            ['pengguna_id' => $bayu->id, 'sasaran_kinerja' => 'Pengelolaan data kesiswaan'],
            ['periode' => 'Semester 1 2025/2026', 'tahun' => 2026, 'indikator_kinerja' => 'Kelengkapan data siswa dalam sistem', 'target_kuantitas' => 100, 'realisasi_kuantitas' => 97, 'target_kualitas' => 90, 'realisasi_kualitas' => 92, 'target_waktu' => 6, 'realisasi_waktu' => 6, 'nilai_capaian' => 93.67, 'predikat' => 'sangat_baik', 'status' => 'disetujui', 'disetujui_oleh' => $admin->id, 'disetujui_pada' => now()->subDays(rand(1, 30))]
        );
        Skp::updateOrCreate(
            ['pengguna_id' => $wikana->id, 'sasaran_kinerja' => 'Administrasi dokumen kurikulum'],
            ['periode' => 'Semester 1 2025/2026', 'tahun' => 2026, 'indikator_kinerja' => 'Persentase dokumen kurikulum yang lengkap', 'target_kuantitas' => 100, 'realisasi_kuantitas' => 88, 'target_kualitas' => 85, 'realisasi_kualitas' => 85, 'target_waktu' => 6, 'realisasi_waktu' => 6, 'nilai_capaian' => 87.33, 'predikat' => 'baik', 'status' => 'draft']
        );

        /*
        |--------------------------------------------------------------------------
        | 6. DATA SISWA (12 records)
        |--------------------------------------------------------------------------
        */
        $siswaData = [
            ['nis' => '12001', 'nisn' => '0012345601', 'nama' => 'Ahmad Fauzi',       'kelas' => 'X IPA 1',   'jk' => 'L', 'tempat' => 'Jember', 'tgl' => '2008-03-15', 'agama' => 'Islam',    'ortu' => 'Budi Fauzi',          'telp' => '081300000001', 'status' => 'aktif'],
            ['nis' => '12002', 'nisn' => '0012345602', 'nama' => 'Siti Nurhaliza',     'kelas' => 'X IPA 1',   'jk' => 'P', 'tempat' => 'Jember', 'tgl' => '2008-07-22', 'agama' => 'Islam',    'ortu' => 'Hasan Nurhaliza',     'telp' => '081300000002', 'status' => 'aktif'],
            ['nis' => '12003', 'nisn' => '0012345603', 'nama' => 'Dimas Prasetyo',     'kelas' => 'X IPS 1',   'jk' => 'L', 'tempat' => 'Bondowoso', 'tgl' => '2008-11-05', 'agama' => 'Islam', 'ortu' => 'Joko Prasetyo',       'telp' => '081300000003', 'status' => 'aktif'],
            ['nis' => '12004', 'nisn' => '0012345604', 'nama' => 'Rina Wulandari',     'kelas' => 'X IPS 1',   'jk' => 'P', 'tempat' => 'Lumajang', 'tgl' => '2008-01-30', 'agama' => 'Islam',  'ortu' => 'Wawan Wulandari',     'telp' => '081300000004', 'status' => 'aktif'],
            ['nis' => '11001', 'nisn' => '0012345605', 'nama' => 'Rizky Firmansyah',   'kelas' => 'XI IPA 1',  'jk' => 'L', 'tempat' => 'Jember', 'tgl' => '2007-06-18', 'agama' => 'Islam',    'ortu' => 'Firman Syah',         'telp' => '081300000005', 'status' => 'aktif'],
            ['nis' => '11002', 'nisn' => '0012345606', 'nama' => 'Putri Amelia',       'kelas' => 'XI IPA 2',  'jk' => 'P', 'tempat' => 'Jember', 'tgl' => '2007-09-10', 'agama' => 'Kristen', 'ortu' => 'Amelia Sr.',          'telp' => '081300000006', 'status' => 'aktif'],
            ['nis' => '11003', 'nisn' => '0012345607', 'nama' => 'Yoga Pratama',       'kelas' => 'XI IPS 1',  'jk' => 'L', 'tempat' => 'Situbondo', 'tgl' => '2007-12-25', 'agama' => 'Hindu', 'ortu' => 'Wayan Pratama',       'telp' => '081300000007', 'status' => 'aktif'],
            ['nis' => '11004', 'nisn' => '0012345608', 'nama' => 'Dewi Safitri',       'kelas' => 'XI IPS 2',  'jk' => 'P', 'tempat' => 'Jember', 'tgl' => '2007-04-08', 'agama' => 'Islam',    'ortu' => 'Safitri Dewi',        'telp' => '081300000008', 'status' => 'mutasi_keluar'],
            ['nis' => '10001', 'nisn' => '0012345609', 'nama' => 'Gilang Ramadhan',    'kelas' => 'XII IPA 1', 'jk' => 'L', 'tempat' => 'Jember', 'tgl' => '2006-08-20', 'agama' => 'Islam',    'ortu' => 'Ramadhan Sr.',        'telp' => '081300000009', 'status' => 'aktif'],
            ['nis' => '10002', 'nisn' => '0012345610', 'nama' => 'Anisa Rahmawati',    'kelas' => 'XII IPA 2', 'jk' => 'P', 'tempat' => 'Banyuwangi', 'tgl' => '2006-02-14', 'agama' => 'Islam', 'ortu' => 'Rahmat Anisa',       'telp' => '081300000010', 'status' => 'aktif'],
            ['nis' => '10003', 'nisn' => '0012345611', 'nama' => 'Fajar Hidayat',      'kelas' => 'XII IPS 1', 'jk' => 'L', 'tempat' => 'Jember', 'tgl' => '2006-05-17', 'agama' => 'Islam',    'ortu' => 'Hidayat Sr.',         'telp' => '081300000011', 'status' => 'aktif'],
            ['nis' => '10004', 'nisn' => '0012345612', 'nama' => 'Larasati Putri',     'kelas' => 'XII IPS 2', 'jk' => 'P', 'tempat' => 'Probolinggo', 'tgl' => '2006-10-03', 'agama' => 'Islam', 'ortu' => 'Laras Putri Sr.',    'telp' => '081300000012', 'status' => 'aktif'],
        ];

        $siswaModels = [];
        foreach ($siswaData as $s) {
            $siswa = DataSiswa::updateOrCreate(
                ['nis' => $s['nis']],
                [
                    'nisn'              => $s['nisn'],
                    'nama'              => $s['nama'],
                    'kelas'             => $s['kelas'],
                    'tahun_ajaran'      => '2025/2026',
                    'jenis_kelamin'     => $s['jk'],
                    'tempat_lahir'      => $s['tempat'],
                    'tanggal_lahir'     => $s['tgl'],
                    'agama'             => $s['agama'],
                    'alamat'            => 'Jl. ' . collect(['Mawar','Melati','Kenanga','Anggrek','Dahlia'])->random() . ' No. ' . rand(1, 99) . ', ' . $s['tempat'],
                    'nama_orang_tua'    => $s['ortu'],
                    'telepon_orang_tua' => $s['telp'],
                    'status'            => $s['status'],
                    'tanggal_masuk'     => $s['status'] === 'mutasi_keluar' ? '2024-07-15' : '2025-07-15',
                    'tanggal_keluar'    => $s['status'] === 'mutasi_keluar' ? '2026-01-10' : null,
                    'catatan'           => $s['status'] === 'mutasi_keluar' ? 'Pindah ke SMA Negeri 1 Surabaya karena orang tua pindah tugas' : null,
                    'dibuat_oleh'       => $bayu->id,
                ]
            );
            $siswaModels[$s['nis']] = $siswa;
        }

        /*
        |--------------------------------------------------------------------------
        | 7. PRESTASI SISWA (5)
        |--------------------------------------------------------------------------
        */
        PrestasiSiswa::updateOrCreate(
            ['siswa_id' => $siswaModels['10001']->id, 'judul' => 'Juara 1 Olimpiade Matematika Kabupaten'],
            ['tingkat' => 'kabupaten', 'jenis' => 'akademik', 'tanggal' => now()->subMonths(3), 'penyelenggara' => 'Dinas Pendidikan Kab. Jember', 'hasil' => 'Juara 1']
        );
        PrestasiSiswa::updateOrCreate(
            ['siswa_id' => $siswaModels['10002']->id, 'judul' => 'Juara 2 Lomba Debat Bahasa Inggris Provinsi'],
            ['tingkat' => 'provinsi', 'jenis' => 'akademik', 'tanggal' => now()->subMonths(2), 'penyelenggara' => 'English First Jatim', 'hasil' => 'Juara 2']
        );
        PrestasiSiswa::updateOrCreate(
            ['siswa_id' => $siswaModels['11001']->id, 'judul' => 'Medali Emas Karate Kelas 60kg POPDA'],
            ['tingkat' => 'provinsi', 'jenis' => 'olahraga', 'tanggal' => now()->subMonths(4), 'penyelenggara' => 'KONI Jawa Timur', 'hasil' => 'Medali Emas']
        );
        PrestasiSiswa::updateOrCreate(
            ['siswa_id' => $siswaModels['12001']->id, 'judul' => 'Finalis Lomba Karya Ilmiah Remaja Nasional'],
            ['tingkat' => 'nasional', 'jenis' => 'akademik', 'tanggal' => now()->subMonths(1), 'penyelenggara' => 'Kemendikbudristek', 'hasil' => 'Finalis 10 Besar']
        );
        PrestasiSiswa::updateOrCreate(
            ['siswa_id' => $siswaModels['12002']->id, 'judul' => 'Juara 3 FLS2N Seni Tari Kabupaten'],
            ['tingkat' => 'kabupaten', 'jenis' => 'seni', 'tanggal' => now()->subMonths(5), 'penyelenggara' => 'Dinas Pendidikan Kab. Jember', 'hasil' => 'Juara 3']
        );

        /*
        |--------------------------------------------------------------------------
        | 8. PELANGGARAN SISWA (3)
        |--------------------------------------------------------------------------
        */
        PelanggaranSiswa::updateOrCreate(
            ['siswa_id' => $siswaModels['11003']->id, 'tanggal' => now()->subDays(15)->format('Y-m-d'), 'jenis' => 'ringan'],
            ['deskripsi' => 'Tidak memakai seragam lengkap (sepatu hitam)', 'tindakan' => 'Peringatan lisan dan catatan di buku pelanggaran', 'dilaporkan_oleh' => $bayu->id]
        );
        PelanggaranSiswa::updateOrCreate(
            ['siswa_id' => $siswaModels['10003']->id, 'tanggal' => now()->subDays(10)->format('Y-m-d'), 'jenis' => 'sedang'],
            ['deskripsi' => 'Membolos jam pelajaran ke-5 dan ke-6 tanpa izin', 'tindakan' => 'Surat peringatan 1 dan dipanggil orang tua', 'dilaporkan_oleh' => $bayu->id]
        );
        PelanggaranSiswa::updateOrCreate(
            ['siswa_id' => $siswaModels['11003']->id, 'tanggal' => now()->subDays(5)->format('Y-m-d'), 'jenis' => 'ringan'],
            ['deskripsi' => 'Terlambat masuk kelas lebih dari 15 menit', 'tindakan' => 'Membersihkan halaman sekolah selama istirahat', 'dilaporkan_oleh' => $wikana->id]
        );

        /*
        |--------------------------------------------------------------------------
        | 9. DOKUMEN KURIKULUM (10)
        |--------------------------------------------------------------------------
        */
        $kurikulumData = [
            ['judul' => 'Kalender Pendidikan TA 2025/2026',         'jenis' => 'kalender_pendidikan', 'mapel' => null,           'kelas' => null,    'sem' => 'ganjil',  'status' => 'aktif'],
            ['judul' => 'Jadwal Pelajaran Semester Genap 2025/2026', 'jenis' => 'jadwal_pelajaran',    'mapel' => null,           'kelas' => null,    'sem' => 'genap',   'status' => 'aktif'],
            ['judul' => 'RPP Matematika Kelas X Semester Genap',     'jenis' => 'rpp',                 'mapel' => 'Matematika',   'kelas' => 'X',     'sem' => 'genap',   'status' => 'aktif'],
            ['judul' => 'Silabus Bahasa Indonesia Kelas XI',         'jenis' => 'silabus',             'mapel' => 'Bahasa Indonesia', 'kelas' => 'XI', 'sem' => 'genap',   'status' => 'aktif'],
            ['judul' => 'Modul Ajar Fisika Kelas XII',               'jenis' => 'modul_ajar',          'mapel' => 'Fisika',       'kelas' => 'XII',   'sem' => 'genap',   'status' => 'draft'],
            ['judul' => 'Kisi-kisi UTS Genap Kelas X',              'jenis' => 'kisi_kisi',           'mapel' => null,           'kelas' => 'X',     'sem' => 'genap',   'status' => 'aktif'],
            ['judul' => 'Berita Acara UAS Ganjil 2025/2026',        'jenis' => 'berita_acara_ujian',  'mapel' => null,           'kelas' => null,    'sem' => 'ganjil',  'status' => 'aktif'],
            ['judul' => 'Daftar Nilai Kimia XI IPA 1 Ganjil',       'jenis' => 'daftar_nilai',        'mapel' => 'Kimia',        'kelas' => 'XI',    'sem' => 'ganjil',  'status' => 'aktif'],
            ['judul' => 'Rekap Nilai Semester Ganjil Kelas XII',     'jenis' => 'rekap_nilai',         'mapel' => null,           'kelas' => 'XII',   'sem' => 'ganjil',  'status' => 'aktif'],
            ['judul' => 'Leger Nilai Kelas X TA 2025/2026',         'jenis' => 'leger',               'mapel' => null,           'kelas' => 'X',     'sem' => 'ganjil',  'status' => 'draft'],
        ];

        foreach ($kurikulumData as $k) {
            DokumenKurikulum::updateOrCreate(
                ['judul' => $k['judul']],
                [
                    'deskripsi'      => 'Dokumen kurikulum: ' . $k['judul'],
                    'jenis'          => $k['jenis'],
                    'tahun_ajaran'   => '2025/2026',
                    'semester'       => $k['sem'],
                    'mata_pelajaran' => $k['mapel'],
                    'tingkat_kelas'  => $k['kelas'],
                    'path_file'      => 'kurikulum/' . \Illuminate\Support\Str::slug($k['judul']) . '.pdf',
                    'nama_file'      => \Illuminate\Support\Str::slug($k['judul']) . '.pdf',
                    'tipe_file'      => 'pdf',
                    'ukuran_file'    => rand(102400, 5242880),
                    'status'         => $k['status'],
                    'diunggah_oleh'  => collect([$bayu->id, $wikana->id])->random(),
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 10. DOKUMEN UMUM
        |--------------------------------------------------------------------------
        */
        Dokumen::updateOrCreate(
            ['judul' => 'KTSP SMA Negeri 2 Jember TA 2025/2026'],
            ['deskripsi' => 'Kurikulum Tingkat Satuan Pendidikan.', 'kategori' => 'kurikulum', 'path_file' => 'documents/ktsp-2025-2026.pdf', 'nama_file' => 'ktsp-2025-2026.pdf', 'tipe_file' => 'pdf', 'ukuran_file' => rand(204800, 10485760), 'diunggah_oleh' => $wikana->id]
        );
        Dokumen::updateOrCreate(
            ['judul' => 'Data Siswa Lengkap TA 2025/2026'],
            ['deskripsi' => 'Rekap data siswa aktif semua kelas.', 'kategori' => 'kesiswaan', 'path_file' => 'documents/data-siswa-2025-2026.xlsx', 'nama_file' => 'data-siswa-2025-2026.xlsx', 'tipe_file' => 'xlsx', 'ukuran_file' => rand(102400, 5242880), 'diunggah_oleh' => $bayu->id]
        );
        Dokumen::updateOrCreate(
            ['judul' => 'Tata Tertib Siswa TA 2025/2026'],
            ['deskripsi' => 'Peraturan tata tertib siswa terbaru.', 'kategori' => 'kesiswaan', 'path_file' => 'documents/tata-tertib-siswa-2025-2026.pdf', 'nama_file' => 'tata-tertib-siswa-2025-2026.pdf', 'tipe_file' => 'pdf', 'ukuran_file' => rand(102400, 5242880), 'diunggah_oleh' => $bayu->id]
        );

        /*
        |--------------------------------------------------------------------------
        | 11. SURAT
        |--------------------------------------------------------------------------
        */
        $tgl1 = now()->subDays(rand(1, 30));
        Surat::updateOrCreate(
            ['perihal' => 'Surat Tugas Pelatihan Kurikulum Merdeka'],
            ['nomor_surat' => Surat::generateNomor('keluar', 'tugas'), 'jenis' => 'keluar', 'kategori' => 'tugas', 'isi' => 'Menugaskan 5 guru untuk mengikuti pelatihan implementasi kurikulum merdeka di Malang.', 'tujuan' => 'Guru yang ditugaskan', 'tanggal_surat' => $tgl1, 'status' => 'dikirim', 'sifat' => 'biasa', 'dibuat_oleh' => $bayu->id, 'disetujui_oleh' => $admin->id]
        );

        $tgl2 = now()->subDays(rand(1, 20));
        Surat::updateOrCreate(
            ['perihal' => 'Pemberitahuan Jadwal UTS Genap 2025/2026'],
            ['nomor_surat' => Surat::generateNomor('keluar', 'pemberitahuan'), 'jenis' => 'keluar', 'kategori' => 'pemberitahuan', 'isi' => 'Pemberitahuan jadwal UTS semester genap 2025/2026 kepada seluruh siswa dan orang tua.', 'tujuan' => 'Orang Tua/Wali Siswa', 'tanggal_surat' => $tgl2, 'status' => 'dikirim', 'sifat' => 'penting', 'dibuat_oleh' => $wikana->id, 'disetujui_oleh' => $admin->id]
        );

        /*
        |--------------------------------------------------------------------------
        | 12. DOKUMEN WORD AI
        |--------------------------------------------------------------------------
        */
        DokumenWord::updateOrCreate(
            ['judul' => 'Laporan Kegiatan Class Meeting Semester Ganjil', 'pengguna_id' => $bayu->id],
            [
                'pengguna_id' => $bayu->id,
                'kategori'    => 'laporan',
                'konten'      => '<h2>Laporan Kegiatan Class Meeting Semester Ganjil 2025/2026</h2><p>Class meeting dilaksanakan pada 16-20 Desember 2025 dengan rangkaian kegiatan: lomba futsal, basket, badminton, paduan suara, dan bazar kelas.</p><p>Total peserta: 720 siswa. Juara umum diraih oleh kelas XII IPA 1 dengan perolehan 95 poin.</p>',
                'prompt_ai'   => 'Buat laporan class meeting semester ganjil 2025/2026',
                'templat'     => 'laporan_kegiatan',
                'status'      => 'final',
                'dibagikan'    => true,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | 13. NOTIFIKASI
        |--------------------------------------------------------------------------
        */
        $this->seedNotifikasi($staffUsers);

        /*
        |--------------------------------------------------------------------------
        | OUTPUT
        |--------------------------------------------------------------------------
        */
        $this->command->info('');
        $this->command->info('  ✅ PERAN KESISWAAN & KURIKULUM (IKI 7)');
        $this->command->info('  ─────────────────────────────────────');
        $this->command->info('  Akun   : bayu.kesiswaan@tu.test');
        $this->command->info('           wikana.kesiswaan@tu.test');
        $this->command->info('  Fitur  : Kehadiran 30 hari, 2 izin, 2 laporan, 2 SKP,');
        $this->command->info('           12 siswa, 5 prestasi, 3 pelanggaran, 10 dok kurikulum,');
        $this->command->info('           3 dokumen, 2 surat, 1 word AI, notifikasi');
    }

    private function seedKehadiran(array $users, Carbon $today): void
    {
        $statuses  = ['hadir','hadir','hadir','hadir','hadir','terlambat','izin','sakit'];
        $addresses = ['SMA Negeri 2 Jember, Jl. Jawa No.16, Sumbersari, Jember','Halaman Parkir SMA Negeri 2 Jember','Ruang TU SMA Negeri 2 Jember, Jl. Jawa 16','Pos Satpam SMA Negeri 2 Jember','Lapangan Utama SMA Negeri 2 Jember'];

        foreach ($users as $staff) {
            for ($i = 29; $i >= 0; $i--) {
                $date = $today->copy()->subDays($i);
                if ($date->isWeekend()) continue;
                $status = $statuses[array_rand($statuses)];
                $clockIn = $clockOut = $note = $addrIn = $addrOut = null;
                switch ($status) {
                    case 'hadir':     $clockIn = sprintf('07:%02d', rand(10, 29)); $clockOut = sprintf('%02d:%02d', rand(15, 16), rand(0, 30)); $addrIn = $addresses[array_rand($addresses)]; $addrOut = $addresses[array_rand($addresses)]; break;
                    case 'terlambat': $clockIn = sprintf('07:%02d', rand(46, 59)); $clockOut = sprintf('%02d:%02d', rand(15, 16), rand(0, 30)); $note = 'Terlambat: ' . collect(['macet di jalan','ban bocor','antar anak sekolah','hujan deras'])->random(); $addrIn = $addresses[array_rand($addresses)]; $addrOut = $addresses[array_rand($addresses)]; break;
                    case 'izin':  $note = collect(['Urusan keluarga','Mengurus dokumen pribadi','Keperluan mendadak'])->random(); break;
                    case 'sakit': $note = collect(['Demam dan flu','Sakit perut','Periksa ke dokter','Masuk angin'])->random(); break;
                }
                Kehadiran::updateOrCreate(
                    ['pengguna_id' => $staff->id, 'tanggal' => $date->format('Y-m-d')],
                    ['jam_masuk' => $clockIn, 'jam_pulang' => $clockOut, 'status' => $status, 'lat_masuk' => $clockIn ? -8.165908 + (rand(-50, 50) / 100000) : null, 'lng_masuk' => $clockIn ? 113.706649 + (rand(-50, 50) / 100000) : null, 'alamat_masuk' => $addrIn, 'lat_pulang' => $clockOut ? -8.165908 + (rand(-50, 50) / 100000) : null, 'lng_pulang' => $clockOut ? 113.706649 + (rand(-50, 50) / 100000) : null, 'alamat_pulang' => $addrOut, 'catatan' => $note]
                );
            }
        }
    }

    private function seedNotifikasi(array $users): void
    {
        $templates = [
            ['judul' => 'Absensi berhasil tercatat',                 'msg' => 'Absensi masuk hari ini berhasil tercatat pukul 07:25 WIB.', 'jenis' => 'kehadiran'],
            ['judul' => 'Pengingat absen pulang',                    'msg' => 'Jangan lupa absen pulang sebelum meninggalkan area sekolah.', 'jenis' => 'kehadiran'],
            ['judul' => 'Data siswa baru ditambahkan',               'msg' => 'Data siswa baru telah berhasil dimasukkan ke sistem.',       'jenis' => 'sistem'],
            ['judul' => 'Dokumen kurikulum diunggah',                'msg' => 'Dokumen kurikulum baru telah diunggah dan menunggu review.',  'jenis' => 'pengumuman'],
            ['judul' => 'Selamat datang di Sistem TU Administrasi!', 'msg' => 'Akun Anda sudah aktif. Lengkapi profil.',                    'jenis' => 'sistem'],
            ['judul' => 'Pembaruan Sistem v3.0',                     'msg' => 'Fitur baru: SKP, Word AI, lokasi detail kehadiran.',         'jenis' => 'sistem'],
        ];

        foreach ($users as $staff) {
            $shuffled = collect($templates)->shuffle()->take(rand(3, 5));
            foreach ($shuffled as $idx => $n) {
                Notifikasi::create(['pengguna_id' => $staff->id, 'judul' => $n['judul'], 'pesan' => $n['msg'], 'jenis' => $n['jenis'], 'sudah_dibaca' => $idx < 2, 'created_at' => now()->subHours(rand(1, 168))]);
            }
        }
    }
}
