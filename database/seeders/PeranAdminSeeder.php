<?php

namespace Database\Seeders;

use App\Models\DokumenAkreditasi;
use App\Models\PengaturanKehadiran;
use App\Models\CatatanBeranda;
use App\Models\TemplateDokumen;
use App\Models\Acara;
use App\Models\Notifikasi;
use App\Models\BuktiFisik;
use App\Models\Pengingat;
use App\Models\EvaluasiSekolah;
use App\Models\AnalisisStar;
use App\Models\UcapanUlangTahun;
use App\Models\Pengguna;
use App\Models\DokumenWord;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PeranAdminSeeder extends Seeder
{
    public function run(): void
    {
        $today = Carbon::today();

        /*
        |--------------------------------------------------------------------------
        | 1. AKUN ADMIN (Kepala Tata Usaha)
        |--------------------------------------------------------------------------
        */
        $admin = Pengguna::updateOrCreate(
            ['email' => 'admin@tu.test'],
            [
                'nama'          => 'Drs. Bambang Supriyanto, M.Pd.',
                'nip'           => '196805151992031005',
                'password'      => Hash::make('password'),
                'peran'         => 'admin',
                'jabatan'       => 'Kepala Tata Usaha',
                'telepon'       => '081234567890',
                'alamat'        => 'Jl. Mastrip No. 45, Kel. Sumbersari, Kec. Sumbersari, Jember',
                'aktif'         => true,
                'tanggal_lahir' => '1968-05-15',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | 2. PENGATURAN KEHADIRAN
        |--------------------------------------------------------------------------
        */
        PengaturanKehadiran::updateOrCreate(
            ['id' => 1],
            [
                'jam_masuk'                 => '07:30',
                'jam_pulang'                => '16:00',
                'toleransi_terlambat_menit' => 15,
                'lat_kantor'                => -8.165908,
                'lng_kantor'                => 113.706649,
                'jarak_maksimal_meter'      => 200,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | 3. ACARA / EVENT
        |--------------------------------------------------------------------------
        */
        $eventsData = [
            ['judul' => 'Penerimaan Rapor Semester Ganjil',  'desc' => 'Pembagian rapor semester ganjil 2025/2026.',                           'date' => -30, 'start' => '08:00', 'end' => '12:00', 'loc' => 'Ruang Kelas',       'jenis' => 'kegiatan',   'status' => 'completed'],
            ['judul' => 'Rapat Pleno Dewan Guru',            'desc' => 'Evaluasi KBM semester ganjil dan persiapan genap.',                    'date' => -25, 'start' => '09:00', 'end' => '14:00', 'loc' => 'Ruang Guru',        'jenis' => 'rapat',      'status' => 'completed'],
            ['judul' => 'Upacara Bendera Hari Senin',        'desc' => 'Upacara rutin. Pembina: Kepala Sekolah.',                             'date' => 1,   'start' => '07:00', 'end' => '07:45', 'loc' => 'Lapangan Utama',    'jenis' => 'upacara',    'status' => 'upcoming'],
            ['judul' => 'Pelatihan Google Workspace',        'desc' => 'Pelatihan Google Classroom, Drive, Meet, Forms.',                     'date' => 3,   'start' => '08:00', 'end' => '15:00', 'loc' => 'Lab Komputer 1',    'jenis' => 'pelatihan',  'status' => 'upcoming'],
            ['judul' => 'Rapat Persiapan PPDB 2026/2027',    'desc' => 'Teknis PPDB: kuota per jalur, zona, jadwal, panitia.',                'date' => 5,   'start' => '13:00', 'end' => '15:00', 'loc' => 'Ruang Rapat',       'jenis' => 'rapat',      'status' => 'upcoming'],
            ['judul' => 'Class Meeting & Pentas Seni',       'desc' => 'Futsal, voli, cerdas cermat, lomba poster, pentas seni.',             'date' => 8,   'start' => '07:00', 'end' => '14:00', 'loc' => 'Aula & Lapangan',   'jenis' => 'kegiatan',   'status' => 'upcoming'],
            ['judul' => 'Ujian Tengah Semester Genap',       'desc' => 'UTS genap kelas X & XI. 18 mapel, 5 hari.',                           'date' => 30,  'start' => '07:30', 'end' => '12:00', 'loc' => 'Ruang Kelas',       'jenis' => 'kegiatan',   'status' => 'upcoming'],
        ];

        foreach ($eventsData as $ed) {
            Acara::updateOrCreate(
                ['judul' => $ed['judul']],
                [
                    'dibuat_oleh'   => $admin->id,
                    'deskripsi'     => $ed['desc'],
                    'tanggal_acara' => $today->copy()->addDays($ed['date'])->format('Y-m-d'),
                    'waktu_mulai'   => $ed['start'],
                    'waktu_selesai' => $ed['end'],
                    'lokasi'        => $ed['loc'],
                    'jenis'         => $ed['jenis'],
                    'status'        => $ed['status'],
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 4. CATATAN BERANDA
        |--------------------------------------------------------------------------
        */
        $notes = [
            ['judul' => 'Selamat Datang di Sistem TU!',  'isi' => 'Sistem informasi administrasi tata usaha SMA Negeri 2 Jember telah aktif. Silakan gunakan menu di sidebar untuk mengakses fitur yang tersedia.', 'warna' => 'primary', 'disematkan' => true,  'tanggal' => '2026-03-01'],
            ['judul' => 'Jadwal UTS Semester Genap',      'isi' => 'UTS semester genap dijadwalkan tanggal 14-18 April 2026. Pastikan semua persiapan administrasi sudah selesai.',                                  'warna' => 'warning', 'disematkan' => true,  'tanggal' => '2026-03-05'],
            ['judul' => 'Pembaruan Sistem Kehadiran',     'isi' => 'Sistem kehadiran online sudah diperbarui. Radius absensi diperluas menjadi 200m mengikuti batas terbaru area sekolah.',                          'warna' => 'info',    'disematkan' => false, 'tanggal' => '2026-02-28'],
            ['judul' => 'Pengumpulan SKP Semester 1',     'isi' => 'Semua staf TU diharapkan sudah mengisi SKP semester ganjil paling lambat 10 Maret 2026.',                                                       'warna' => 'danger',  'disematkan' => false, 'tanggal' => '2026-03-01'],
        ];

        foreach ($notes as $n) {
            CatatanBeranda::updateOrCreate(
                ['judul' => $n['judul'], 'pengguna_id' => $admin->id],
                [
                    'isi'        => $n['isi'],
                    'warna'      => $n['warna'],
                    'disematkan' => $n['disematkan'],
                    'tanggal'    => $n['tanggal'],
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 5. ANALISIS STAR
        |--------------------------------------------------------------------------
        */
        $starData = [
            [
                'judul'         => 'Peningkatan Kedisiplinan Siswa Melalui Sistem Poin',
                'kategori'      => 'kesiswaan',
                'situasi'       => 'Angka keterlambatan siswa mencapai rata-rata 15 siswa/hari pada semester ganjil 2025.',
                'tugas'         => 'Menurunkan angka keterlambatan menjadi di bawah 5 siswa/hari.',
                'aksi'          => 'Menerapkan sistem poin pelanggaran dengan reward-punishment. Memasang fingerprint di pintu gerbang. Koordinasi dengan BK dan wali kelas.',
                'hasil'         => 'Angka keterlambatan turun menjadi rata-rata 3 siswa/hari pada bulan Februari 2026.',
                'refleksi'      => 'Sistem berjalan baik, perlu dievaluasi kembali setiap semester.',
                'tindak_lanjut' => 'Pertahankan sistem dan tambahkan apresiasi untuk kelas paling disiplin.',
            ],
            [
                'judul'         => 'Implementasi E-Rapor Kurikulum Merdeka',
                'kategori'      => 'kurikulum',
                'situasi'       => 'Rapor masih diisi manual di Excel, sering terjadi kesalahan input dan terlambat.',
                'tugas'         => 'Mengimplementasikan e-rapor terintegrasi yang efisien.',
                'aksi'          => 'Melakukan pelatihan e-rapor untuk semua guru. Menyiapkan infrastruktur server dan akses. Pendampingan selama 2 bulan.',
                'hasil'         => 'E-rapor berhasil digunakan oleh 100% guru pada semester ganjil 2025/2026.',
                'refleksi'      => 'Beberapa guru senior perlu pendampingan ekstra.',
                'tindak_lanjut' => 'Lanjutkan pelatihan rutin dan buat panduan video tutorial.',
            ],
            [
                'judul'         => 'Digitalisasi Arsip Persuratan',
                'kategori'      => 'administrasi',
                'situasi'       => 'Arsip surat masih berbasis fisik, sulit dicari dan rawan hilang.',
                'tugas'         => 'Migrasi arsip ke sistem digital dengan pencarian.',
                'aksi'          => 'Scan semua arsip surat 2 tahun terakhir. Input ke sistem SIMPEG-SMART. Training untuk staf persuratan.',
                'hasil'         => '95% arsip surat 2024-2025 berhasil didigitalisasi. Waktu pencarian arsip turun dari 15 menit menjadi di bawah 1 menit.',
                'refleksi'      => 'Digitalisasi sangat membantu efisiensi administrasi.',
                'tindak_lanjut' => 'Arsip surat baru langsung diinput ke sistem.',
            ],
        ];

        foreach ($starData as $star) {
            AnalisisStar::updateOrCreate(
                ['judul' => $star['judul']],
                array_merge($star, ['dibuat_oleh' => $admin->id])
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 6. EVALUASI DIRI SEKOLAH (EDS)
        |--------------------------------------------------------------------------
        */
        $eds = [
            ['aspek' => 'Mutu Lulusan',           'kondisi' => 'Rata-rata kelulusan 3 tahun terakhir 100%. Rata-rata nilai UTBK meningkat 5% per tahun.',                                                               'target' => 'Mempertahankan kelulusan 100% dan meningkatkan jumlah siswa diterima PTN.',     'program' => 'Bimbingan intensif UTBK dan program tutor sebaya.',                      'status' => 'final'],
            ['aspek' => 'Proses Pembelajaran',     'kondisi' => 'Implementasi Kurikulum Merdeka sudah berjalan 80%. Diferensiasi pembelajaran sudah diterapkan di beberapa kelas.',                                      'target' => '100% guru menerapkan kurikulum merdeka dengan diferensiasi.',                   'program' => 'Pelatihan IKM dan Lesson Study kolaboratif.',                            'status' => 'final'],
            ['aspek' => 'Kualitas Guru',           'kondisi' => '85% guru sudah S1/S2. 60% guru telah mengikuti PPG.',                                                                                                  'target' => '100% guru mengikuti PPG dalam 2 tahun ke depan.',                              'program' => 'Fasilitasi pendaftaran PPG dan workshop kompetensi pedagogik.',           'status' => 'final'],
            ['aspek' => 'Sarana & Prasarana',       'kondisi' => 'Lab komputer perlu pembaruan perangkat. WiFi sekolah sudah memadai.',                                                                                  'target' => 'Semua lab memiliki peralatan sesuai standar minimal.',                         'program' => 'Pengadaan 20 unit komputer baru dari dana BOS.',                         'status' => 'draft'],
            ['aspek' => 'Manajemen Sekolah',       'kondisi' => 'RKS dan RKAS sudah tersedia. Sistem informasi administrasi baru diimplementasikan.',                                                                   'target' => 'Digitalisasi penuh administrasi sekolah.',                                     'program' => 'Pengembangan sistem SIMPEG-SMART dan pelatihan SDM.',                 'status' => 'final'],
            ['aspek' => 'Iklim & Budaya Sekolah',  'kondisi' => 'Program literasi berjalan rutin. Angka pelanggaran tata tertib menurun 15%.',                                                                          'target' => 'Zero bullying dan peningkatan budaya literasi.',                               'program' => 'Gerakan Literasi Sekolah (GLS) dan program anti-perundungan.',           'status' => 'draft'],
        ];

        foreach ($eds as $e) {
            EvaluasiSekolah::updateOrCreate(
                ['tahun' => '2026', 'aspek' => $e['aspek']],
                [
                    'kondisi_saat_ini'      => $e['kondisi'],
                    'target'                => $e['target'],
                    'program_tindak_lanjut' => $e['program'],
                    'status'                => $e['status'],
                    'dibuat_oleh'           => $admin->id,
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 7. DOKUMEN AKREDITASI
        |--------------------------------------------------------------------------
        */
        $akreditasi = [
            ['standar' => 'Standar Kompetensi Lulusan', 'komponen' => 'Lulusan memiliki kompetensi pada dimensi sikap',            'indikator' => '1.1 Integritas, karakter, dan kepribadian',              'status' => 'lengkap'],
            ['standar' => 'Standar Kompetensi Lulusan', 'komponen' => 'Lulusan memiliki kompetensi pada dimensi pengetahuan',      'indikator' => '1.2 Penguasaan ilmu pengetahuan dan teknologi',          'status' => 'lengkap'],
            ['standar' => 'Standar Kompetensi Lulusan', 'komponen' => 'Lulusan memiliki kompetensi pada dimensi keterampilan',     'indikator' => '1.3 Keterampilan berpikir kritis dan kreatif',           'status' => 'lengkap'],
            ['standar' => 'Standar Isi',                'komponen' => 'Perangkat kurikulum satuan pendidikan',                     'indikator' => '2.1 Dokumen KTSP disusun dan ditetapkan oleh kepala sekolah', 'status' => 'lengkap'],
            ['standar' => 'Standar Isi',                'komponen' => 'Kurikulum sekolah dikembangkan sesuai prosedur',             'indikator' => '2.2 Silabus dikembangkan sesuai pedoman',                'status' => 'belum_lengkap'],
            ['standar' => 'Standar Proses',             'komponen' => 'Perencanaan proses pembelajaran',                           'indikator' => '3.1 RPP/modul ajar tersedia untuk seluruh mata pelajaran', 'status' => 'lengkap'],
            ['standar' => 'Standar Proses',             'komponen' => 'Pelaksanaan proses pembelajaran',                           'indikator' => '3.2 Pembelajaran sesuai kurikulum merdeka',              'status' => 'lengkap'],
            ['standar' => 'Standar Penilaian',          'komponen' => 'Teknik penilaian sesuai karakteristik kompetensi',           'indikator' => '4.1 Penilaian formatif dan sumatif dilaksanakan',        'status' => 'belum_lengkap'],
            ['standar' => 'Standar PTK',                'komponen' => 'Kualifikasi dan kompetensi guru',                           'indikator' => '5.1 Guru memenuhi kualifikasi akademik',                 'status' => 'lengkap'],
            ['standar' => 'Standar PTK',                'komponen' => 'Kualifikasi dan kompetensi tenaga kependidikan',             'indikator' => '5.2 Tenaga kependidikan memenuhi kualifikasi',           'status' => 'lengkap'],
            ['standar' => 'Standar Sarana Prasarana',   'komponen' => 'Sarana dan prasarana pembelajaran',                          'indikator' => '6.1 Ruang kelas memenuhi standar',                       'status' => 'lengkap'],
            ['standar' => 'Standar Pengelolaan',        'komponen' => 'Perencanaan program sekolah',                                'indikator' => '7.1 Visi, misi, tujuan sekolah tersedia',               'status' => 'lengkap'],
            ['standar' => 'Standar Pembiayaan',         'komponen' => 'Pembiayaan pendidikan',                                      'indikator' => '8.1 Sekolah memiliki RKAS sesuai ketentuan',            'status' => 'lengkap'],
        ];

        foreach ($akreditasi as $d) {
            DokumenAkreditasi::updateOrCreate(
                ['standar' => $d['standar'], 'indikator' => $d['indikator']],
                [
                    'komponen'      => $d['komponen'],
                    'deskripsi'     => 'Dokumen bukti pemenuhan ' . $d['indikator'],
                    'status'        => $d['status'],
                    'catatan'       => $d['status'] === 'belum_lengkap' ? 'Perlu dilengkapi sebelum visitasi akreditasi.' : null,
                    'diunggah_oleh' => $admin->id,
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 8. BUKTI FISIK
        |--------------------------------------------------------------------------
        */
        $evidences = [
            ['judul' => 'Foto Pelaksanaan ANBK 2025',          'kategori' => 'penilaian',  'deskripsi' => 'Dokumentasi foto pelaksanaan ANBK di Lab Komputer SMA Negeri 2 Jember.',                    'terkait' => 'Standar Penilaian'],
            ['judul' => 'SK Pembagian Tugas Guru 2025/2026',   'kategori' => 'ptk',        'deskripsi' => 'Surat Keputusan pembagian tugas mengajar guru.',                                             'terkait' => 'Standar PTK'],
            ['judul' => 'MoU Kerjasama Industri DUDI',         'kategori' => 'kerjasama',  'deskripsi' => 'Nota kesepahaman dengan dunia usaha untuk magang dan kunjungan industri.',                   'terkait' => 'Standar Pengelolaan'],
            ['judul' => 'Laporan Supervisi Akademik Sem. 1',   'kategori' => 'supervisi',  'deskripsi' => 'Hasil supervisi akademik oleh kepala sekolah semester ganjil 2025/2026.',                    'terkait' => 'Standar Proses'],
            ['judul' => 'Rapor Pendidikan Sekolah 2025',       'kategori' => 'mutu',       'deskripsi' => 'Data rapor pendidikan dari platform Kemendikbud untuk SMA Negeri 2 Jember.',                 'terkait' => 'Standar Kompetensi Lulusan'],
            ['judul' => 'Sertifikat Akreditasi A (2022-2027)', 'kategori' => 'akreditasi', 'deskripsi' => 'Sertifikat akreditasi A dari BAN-S/M berlaku sampai 2027.',                                  'terkait' => 'Standar Pengelolaan'],
        ];

        foreach ($evidences as $e) {
            BuktiFisik::updateOrCreate(
                ['judul' => $e['judul']],
                [
                    'kategori'      => $e['kategori'],
                    'deskripsi'     => $e['deskripsi'],
                    'path_file'     => 'bukti-fisik/' . \Illuminate\Support\Str::slug($e['judul']) . '.pdf',
                    'nama_file'     => \Illuminate\Support\Str::slug($e['judul']) . '.pdf',
                    'terkait'       => $e['terkait'],
                    'diunggah_oleh' => $admin->id,
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 9. TEMPLATE DOKUMEN (10 template)
        |--------------------------------------------------------------------------
        */
        $templates = [
            ['nama' => 'Surat Undangan Rapat',           'kode' => 'SURAT-UNDANGAN-RAPAT',   'kategori' => 'surat',    'konten' => '<header style="text-align:center;"><h3>PEMERINTAH KABUPATEN JEMBER<br>DINAS PENDIDIKAN<br>SMA NEGERI 2 JEMBER</h3><p>Jl. Jawa No. 16 Jember — Telp. (0331) 321375</p><hr></header><p>&nbsp;</p><p>Nomor &nbsp;&nbsp;&nbsp;: {{nomor_surat}}<br>Lampiran : {{lampiran}}<br>Perihal &nbsp;&nbsp;: <strong>Undangan Rapat</strong></p><p>&nbsp;</p><p>Yth. {{tujuan}}<br>di Tempat</p><p>&nbsp;</p><p>Dengan hormat,</p><p>Sehubungan dengan {{perihal}}, kami mengundang Bapak/Ibu untuk hadir pada:</p><table><tr><td>Hari/Tanggal</td><td>: {{tanggal}}</td></tr><tr><td>Waktu</td><td>: {{waktu}}</td></tr><tr><td>Tempat</td><td>: {{tempat}}</td></tr><tr><td>Acara</td><td>: {{acara}}</td></tr></table><p>Demikian undangan ini kami sampaikan, atas kehadiran Bapak/Ibu kami ucapkan terima kasih.</p><p>&nbsp;</p><p style="text-align:right;">Jember, {{tanggal_surat}}<br>Kepala Sekolah,<br><br><br><br><strong>{{nama_kepsek}}</strong><br>NIP. {{nip_kepsek}}</p>', 'kolom_isian' => json_encode(['nomor_surat','lampiran','tujuan','perihal','tanggal','waktu','tempat','acara','tanggal_surat','nama_kepsek','nip_kepsek']), 'format' => 'docx'],
            ['nama' => 'Surat Keterangan Aktif Mengajar', 'kode' => 'SURAT-KET-AKTIF',       'kategori' => 'surat',    'konten' => '<header style="text-align:center;"><h3>PEMERINTAH KABUPATEN JEMBER<br>DINAS PENDIDIKAN<br>SMA NEGERI 2 JEMBER</h3><hr></header><p style="text-align:center;"><strong><u>SURAT KETERANGAN AKTIF MENGAJAR</u></strong><br>Nomor: {{nomor_surat}}</p><p>&nbsp;</p><p>Yang bertanda tangan di bawah ini, Kepala SMA Negeri 2 Jember, menerangkan bahwa:</p><table><tr><td>Nama</td><td>: {{nama_pegawai}}</td></tr><tr><td>NIP</td><td>: {{nip}}</td></tr><tr><td>Pangkat/Gol</td><td>: {{pangkat}}</td></tr><tr><td>Jabatan</td><td>: {{jabatan}}</td></tr></table><p>Adalah benar yang bersangkutan aktif melaksanakan tugas mengajar di SMA Negeri 2 Jember pada tahun ajaran {{tahun_ajaran}}.</p><p>Demikian surat keterangan ini dibuat untuk dipergunakan sebagaimana mestinya.</p><p style="text-align:right;">Jember, {{tanggal_surat}}<br>Kepala Sekolah,<br><br><br><br><strong>{{nama_kepsek}}</strong><br>NIP. {{nip_kepsek}}</p>', 'kolom_isian' => json_encode(['nomor_surat','nama_pegawai','nip','pangkat','jabatan','tahun_ajaran','tanggal_surat','nama_kepsek','nip_kepsek']), 'format' => 'docx'],
            ['nama' => 'Surat Keterangan Siswa Aktif',    'kode' => 'SURAT-KET-SISWA-AKTIF', 'kategori' => 'surat',    'konten' => '<header style="text-align:center;"><h3>SMA NEGERI 2 JEMBER</h3><hr></header><p style="text-align:center;"><strong><u>SURAT KETERANGAN</u></strong><br>Nomor: {{nomor_surat}}</p><p>Yang bertanda tangan di bawah ini menerangkan bahwa:</p><table><tr><td>Nama</td><td>: {{nama_siswa}}</td></tr><tr><td>NIS/NISN</td><td>: {{nis}} / {{nisn}}</td></tr><tr><td>Kelas</td><td>: {{kelas}}</td></tr><tr><td>Tahun Ajaran</td><td>: {{tahun_ajaran}}</td></tr></table><p>Adalah benar siswa aktif di SMA Negeri 2 Jember. Surat keterangan ini dibuat untuk keperluan {{keperluan}}.</p><p style="text-align:right;">Jember, {{tanggal}}<br>Kepala Sekolah,<br><br><br><br><strong>{{nama_kepsek}}</strong><br>NIP. {{nip_kepsek}}</p>', 'kolom_isian' => json_encode(['nomor_surat','nama_siswa','nis','nisn','kelas','tahun_ajaran','keperluan','tanggal','nama_kepsek','nip_kepsek']), 'format' => 'docx'],
            ['nama' => 'Surat Tugas',                      'kode' => 'SURAT-TUGAS',           'kategori' => 'surat',    'konten' => '<header style="text-align:center;"><h3>PEMERINTAH KABUPATEN JEMBER<br>DINAS PENDIDIKAN<br>SMA NEGERI 2 JEMBER</h3><hr></header><p style="text-align:center;"><strong><u>SURAT TUGAS</u></strong><br>Nomor: {{nomor_surat}}</p><p>Kepala SMA Negeri 2 Jember menugaskan kepada:</p><table><tr><td>Nama</td><td>: {{nama_pegawai}}</td></tr><tr><td>NIP</td><td>: {{nip}}</td></tr><tr><td>Jabatan</td><td>: {{jabatan}}</td></tr></table><p>Untuk melaksanakan tugas sebagai berikut:<br>{{uraian_tugas}}</p><p>Pada tanggal {{tanggal_pelaksanaan}} di {{tempat}}.</p><p style="text-align:right;">Jember, {{tanggal_surat}}<br>Kepala Sekolah,<br><br><br><br><strong>{{nama_kepsek}}</strong><br>NIP. {{nip_kepsek}}</p>', 'kolom_isian' => json_encode(['nomor_surat','nama_pegawai','nip','jabatan','uraian_tugas','tanggal_pelaksanaan','tempat','tanggal_surat','nama_kepsek','nip_kepsek']), 'format' => 'docx'],
            ['nama' => 'SK Kepala Sekolah',                 'kode' => 'SK-KEPSEK',             'kategori' => 'sk',       'konten' => '<header style="text-align:center;"><h3>SMA NEGERI 2 JEMBER</h3><hr></header><p style="text-align:center;"><strong>SURAT KEPUTUSAN KEPALA SEKOLAH</strong><br>Nomor: {{nomor_sk}}<br>Tentang<br><strong>{{tentang}}</strong></p><p>Menimbang: {{menimbang}}</p><p>Mengingat: {{mengingat}}</p><p style="text-align:center;"><strong>MEMUTUSKAN</strong></p><p>Menetapkan:<br>{{isi_keputusan}}</p><p>Surat Keputusan ini berlaku sejak tanggal ditetapkan.</p><p style="text-align:right;">Ditetapkan di Jember<br>Pada tanggal {{tanggal}}<br>Kepala SMA Negeri 2 Jember,<br><br><br><br><strong>{{nama_kepsek}}</strong><br>NIP. {{nip_kepsek}}</p>', 'kolom_isian' => json_encode(['nomor_sk','tentang','menimbang','mengingat','isi_keputusan','tanggal','nama_kepsek','nip_kepsek']), 'format' => 'docx'],
            ['nama' => 'Notulen Rapat',                     'kode' => 'NOTULEN-RAPAT',         'kategori' => 'notulen',  'konten' => '<h3 style="text-align:center;">NOTULEN RAPAT<br>SMA NEGERI 2 JEMBER</h3><hr><table><tr><td>Hari/Tanggal</td><td>: {{tanggal}}</td></tr><tr><td>Waktu</td><td>: {{waktu}}</td></tr><tr><td>Tempat</td><td>: {{tempat}}</td></tr><tr><td>Agenda</td><td>: {{agenda}}</td></tr><tr><td>Pemimpin Rapat</td><td>: {{pemimpin_rapat}}</td></tr><tr><td>Notulis</td><td>: {{notulis}}</td></tr><tr><td>Hadir</td><td>: {{jumlah_hadir}} orang</td></tr></table><h4>Pembahasan:</h4><p>{{pembahasan}}</p><h4>Keputusan:</h4><p>{{keputusan}}</p><h4>Tindak Lanjut:</h4><p>{{tindak_lanjut}}</p><p>&nbsp;</p><p>Jember, {{tanggal}}<br>Notulis,<br><br><br><strong>{{notulis}}</strong></p>', 'kolom_isian' => json_encode(['tanggal','waktu','tempat','agenda','pemimpin_rapat','notulis','jumlah_hadir','pembahasan','keputusan','tindak_lanjut']), 'format' => 'docx'],
            ['nama' => 'Laporan Kegiatan',                  'kode' => 'LAPORAN-KEGIATAN',      'kategori' => 'laporan',  'konten' => '<h3 style="text-align:center;">LAPORAN KEGIATAN<br>{{nama_kegiatan}}</h3><hr><h4>I. Pendahuluan</h4><p>{{pendahuluan}}</p><h4>II. Dasar Pelaksanaan</h4><p>{{dasar}}</p><h4>III. Tujuan</h4><p>{{tujuan}}</p><h4>IV. Pelaksanaan</h4><table><tr><td>Tanggal</td><td>: {{tanggal}}</td></tr><tr><td>Tempat</td><td>: {{tempat}}</td></tr><tr><td>Peserta</td><td>: {{peserta}}</td></tr></table><h4>V. Hasil</h4><p>{{hasil}}</p><h4>VI. Penutup</h4><p>{{penutup}}</p><p style="text-align:right;">Jember, {{tanggal_laporan}}<br>Penanggung Jawab,<br><br><br><strong>{{penanggung_jawab}}</strong></p>', 'kolom_isian' => json_encode(['nama_kegiatan','pendahuluan','dasar','tujuan','tanggal','tempat','peserta','hasil','penutup','tanggal_laporan','penanggung_jawab']), 'format' => 'docx'],
            ['nama' => 'Surat Edaran',                      'kode' => 'SURAT-EDARAN',          'kategori' => 'surat',    'konten' => '<header style="text-align:center;"><h3>SMA NEGERI 2 JEMBER</h3><hr></header><p style="text-align:center;"><strong><u>SURAT EDARAN</u></strong><br>Nomor: {{nomor_surat}}</p><p>Kepada Yth.<br>{{tujuan}}<br>di Lingkungan SMA Negeri 2 Jember</p><p>&nbsp;</p><p>{{isi_edaran}}</p><p>Demikian surat edaran ini disampaikan untuk dilaksanakan sebagaimana mestinya.</p><p style="text-align:right;">Jember, {{tanggal}}<br>Kepala Sekolah,<br><br><br><br><strong>{{nama_kepsek}}</strong><br>NIP. {{nip_kepsek}}</p>', 'kolom_isian' => json_encode(['nomor_surat','tujuan','isi_edaran','tanggal','nama_kepsek','nip_kepsek']), 'format' => 'docx'],
            ['nama' => 'Proposal Kegiatan',                 'kode' => 'PROPOSAL-KEGIATAN',     'kategori' => 'proposal', 'konten' => '<h3 style="text-align:center;">PROPOSAL KEGIATAN<br>{{nama_kegiatan}}<br>SMA NEGERI 2 JEMBER</h3><hr><h4>I. Latar Belakang</h4><p>{{latar_belakang}}</p><h4>II. Tujuan</h4><p>{{tujuan}}</p><h4>III. Sasaran</h4><p>{{sasaran}}</p><h4>IV. Waktu & Tempat</h4><p>{{waktu_tempat}}</p><h4>V. Susunan Panitia</h4><p>{{susunan_panitia}}</p><h4>VI. Rencana Anggaran</h4><p>{{rencana_anggaran}}</p><h4>VII. Penutup</h4><p>{{penutup}}</p><p style="text-align:right;">Jember, {{tanggal}}<br>Ketua Panitia,<br><br><br><strong>{{ketua_panitia}}</strong></p><p style="text-align:left;">Mengetahui,<br>Kepala Sekolah<br><br><br><strong>{{nama_kepsek}}</strong><br>NIP. {{nip_kepsek}}</p>', 'kolom_isian' => json_encode(['nama_kegiatan','latar_belakang','tujuan','sasaran','waktu_tempat','susunan_panitia','rencana_anggaran','penutup','tanggal','ketua_panitia','nama_kepsek','nip_kepsek']), 'format' => 'docx'],
            ['nama' => 'Berita Acara',                      'kode' => 'BERITA-ACARA',          'kategori' => 'lainnya',  'konten' => '<h3 style="text-align:center;">BERITA ACARA<br>{{judul_berita_acara}}</h3><hr><p>Pada hari ini {{hari}}, tanggal {{tanggal}}, bertempat di {{tempat}}, telah dilaksanakan {{kegiatan}} dengan dihadiri oleh pihak-pihak yang tercantum dalam daftar hadir terlampir.</p><h4>Uraian:</h4><p>{{uraian}}</p><h4>Kesimpulan:</h4><p>{{kesimpulan}}</p><p>Demikian berita acara ini dibuat dengan sebenarnya.</p><table><tr><td style="text-align:center;">Pihak Pertama,<br><br><br><br><strong>{{pihak_1}}</strong></td><td style="text-align:center;">Pihak Kedua,<br><br><br><br><strong>{{pihak_2}}</strong></td></tr></table>', 'kolom_isian' => json_encode(['judul_berita_acara','hari','tanggal','tempat','kegiatan','uraian','kesimpulan','pihak_1','pihak_2']), 'format' => 'docx'],
        ];

        foreach ($templates as $tpl) {
            TemplateDokumen::updateOrCreate(
                ['kode' => $tpl['kode']],
                array_merge($tpl, [
                    'aktif'       => true,
                    'dibuat_oleh' => $admin->id,
                ])
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 10. DOKUMEN WORD AI (Admin)
        |--------------------------------------------------------------------------
        */
        $wordDocs = [
            [
                'judul'     => 'Surat Undangan Rapat Komite Maret 2026',
                'kategori'  => 'surat',
                'konten'    => '<p>Kepada Yth. Bapak/Ibu Anggota Komite Sekolah SMA Negeri 2 Jember.</p><p>Dengan hormat, mengundang Bapak/Ibu untuk hadir dalam rapat komite sekolah pada hari Rabu, 12 Maret 2026 pukul 09.00 WIB di Aula SMA Negeri 2 Jember.</p><p>Agenda: Pembahasan program semester genap dan rencana kegiatan.</p>',
                'prompt_ai' => 'Buat surat undangan rapat komite sekolah tanggal 12 Maret 2026 pukul 09.00 di aula sekolah',
                'templat'   => 'surat_resmi',
                'status'    => 'final',
                'dibagikan'  => true,
            ],
            [
                'judul'     => 'SK Panitia PPDB 2026/2027',
                'kategori'  => 'sk',
                'konten'    => '<p>SURAT KEPUTUSAN KEPALA SMA NEGERI 2 JEMBER tentang Pembentukan Panitia PPDB Tahun Ajaran 2026/2027. Menetapkan susunan panitia terlampir.</p>',
                'prompt_ai' => null,
                'templat'   => 'sk',
                'status'    => 'draft',
                'dibagikan'  => false,
            ],
        ];

        foreach ($wordDocs as $doc) {
            DokumenWord::updateOrCreate(
                ['judul' => $doc['judul'], 'pengguna_id' => $admin->id],
                array_merge($doc, ['pengguna_id' => $admin->id])
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 11. PENGINGAT (Admin mengelola pengingat)
        |--------------------------------------------------------------------------
        */
        $reminders = [
            ['judul' => 'Deadline Laporan BOS Triwulan I', 'deskripsi' => 'Batas waktu pengiriman laporan pertanggungjawaban BOS triwulan I 2026.', 'jenis' => 'keuangan',  'tenggat' => '2026-03-31', 'selesai' => false],
            ['judul' => 'Rapat Dinas Bulanan Maret',       'deskripsi' => 'Rapat bulanan seluruh staf TU dan pimpinan.',                            'jenis' => 'rapat',     'tenggat' => '2026-03-10', 'selesai' => false],
            ['judul' => 'Perpanjangan Domain Sekolah',     'deskripsi' => 'Domain website sekolah perlu diperpanjang sebelum expired.',               'jenis' => 'umum',      'tenggat' => '2026-03-20', 'selesai' => false],
            ['judul' => 'Evaluasi PKG Semester Ganjil',    'deskripsi' => 'Finalisasi nilai PKG semester ganjil 2025/2026.',                         'jenis' => 'evaluasi',  'tenggat' => '2026-02-28', 'selesai' => true],
            ['judul' => 'Perawatan AC Ruang Kelas',        'deskripsi' => 'Jadwal servis AC ruangan kelas secara berkala.',                          'jenis' => 'sarana',    'tenggat' => '2026-03-15', 'selesai' => false, 'berulang' => true, 'jenis_pengulangan' => 'bulanan'],
        ];

        foreach ($reminders as $r) {
            Pengingat::updateOrCreate(
                ['judul' => $r['judul'], 'pengguna_id' => $admin->id],
                [
                    'deskripsi'         => $r['deskripsi'],
                    'jenis'             => $r['jenis'],
                    'tenggat'           => $r['tenggat'],
                    'berulang'          => $r['berulang'] ?? false,
                    'jenis_pengulangan' => $r['jenis_pengulangan'] ?? null,
                    'pengguna_id'       => $admin->id,
                    'dibuat_oleh'       => $admin->id,
                    'selesai'           => $r['selesai'],
                    'sudah_diberitahu'  => $r['selesai'],
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 12. UCAPAN ULANG TAHUN
        |--------------------------------------------------------------------------
        */
        $kepsek = Pengguna::where('email', 'kepsek@tu.test')->first();
        if ($kepsek) {
            UcapanUlangTahun::updateOrCreate(
                ['pengirim_id' => $admin->id, 'penerima_id' => $kepsek->id, 'tahun' => 2026],
                [
                    'pesan'        => 'Selamat ulang tahun Bapak Kepala Sekolah! Semoga sehat selalu, panjang umur, dan sukses memimpin SMA Negeri 2 Jember. 🎂🎉',
                    'sudah_dibaca' => true,
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 13. NOTIFIKASI ADMIN
        |--------------------------------------------------------------------------
        */
        Notifikasi::create(['pengguna_id' => $admin->id, 'judul' => 'Pengajuan izin baru menunggu persetujuan',   'pesan' => 'Ada pengajuan izin baru yang memerlukan persetujuan Anda.',     'jenis' => 'izin',    'sudah_dibaca' => false]);
        Notifikasi::create(['pengguna_id' => $admin->id, 'judul' => 'Laporan baru perlu ditinjau',                'pesan' => 'Ada laporan baru yang perlu ditinjau dan diverifikasi.',        'jenis' => 'laporan', 'sudah_dibaca' => false]);
        Notifikasi::create(['pengguna_id' => $admin->id, 'judul' => 'SKP baru menunggu persetujuan',              'pesan' => 'Ada SKP yang diajukan staf dan menunggu persetujuan Anda.',     'jenis' => 'sistem',  'sudah_dibaca' => false]);
        Notifikasi::create(['pengguna_id' => $admin->id, 'judul' => 'Surat baru menunggu persetujuan',            'pesan' => 'Ada surat keluar yang baru dibuat dan menunggu persetujuan.',   'jenis' => 'sistem',  'sudah_dibaca' => false]);
        Notifikasi::create(['pengguna_id' => $admin->id, 'judul' => 'Selamat datang di Sistem SIMPEG-SMART!',  'pesan' => 'Akun admin Anda sudah aktif. Kelola seluruh fitur TU.', 'jenis' => 'sistem',  'sudah_dibaca' => true, 'created_at' => now()->subDays(30)]);

        /*
        |--------------------------------------------------------------------------
        | OUTPUT
        |--------------------------------------------------------------------------
        */
        $this->command->info('');
        $this->command->info('  ✅ PERAN ADMIN (Kepala Tata Usaha)');
        $this->command->info('  ─────────────────────────────────────');
        $this->command->info('  Akun   : admin@tu.test (password: password)');
        $this->command->info('  Fitur  : Pengaturan kehadiran, 7 event, 4 catatan beranda,');
        $this->command->info('           3 STAR analysis, 6 EDS, 13 akreditasi, 6 bukti fisik,');
        $this->command->info('           10 template dokumen, 2 word AI, 5 pengingat,');
        $this->command->info('           ucapan ultah, 5 notifikasi');
    }
}
