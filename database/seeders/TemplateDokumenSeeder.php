<?php

namespace Database\Seeders;

use App\Models\DocumentTemplate;
use App\Models\User;
use Illuminate\Database\Seeder;

class TemplateDokumenSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@tu.test')->firstOrFail();

        $templates = [
            [
                'nama'     => 'Surat Undangan Rapat',
                'kode'     => 'SURAT-UNDANGAN-RAPAT',
                'kategori' => 'surat',
                'konten'   => '<header style="text-align:center;"><h3>PEMERINTAH KABUPATEN JEMBER<br>DINAS PENDIDIKAN<br>SMA NEGERI 2 JEMBER</h3><p>Jl. Jawa No. 16 Jember — Telp. (0331) 321375</p><hr></header><p>&nbsp;</p><p>Nomor &nbsp;&nbsp;&nbsp;: {{nomor_surat}}<br>Lampiran : {{lampiran}}<br>Perihal &nbsp;&nbsp;: <strong>Undangan Rapat</strong></p><p>&nbsp;</p><p>Yth. {{tujuan}}<br>di Tempat</p><p>&nbsp;</p><p>Dengan hormat,</p><p>Sehubungan dengan {{perihal}}, kami mengundang Bapak/Ibu untuk hadir pada:</p><table><tr><td>Hari/Tanggal</td><td>: {{tanggal}}</td></tr><tr><td>Waktu</td><td>: {{waktu}}</td></tr><tr><td>Tempat</td><td>: {{tempat}}</td></tr><tr><td>Acara</td><td>: {{acara}}</td></tr></table><p>Demikian undangan ini kami sampaikan, atas kehadiran Bapak/Ibu kami ucapkan terima kasih.</p><p>&nbsp;</p><p style="text-align:right;">Jember, {{tanggal_surat}}<br>Kepala Sekolah,<br><br><br><br><strong>{{nama_kepsek}}</strong><br>NIP. {{nip_kepsek}}</p>',
                'kolom_isian' => json_encode([
                    'nomor_surat', 'lampiran', 'tujuan', 'perihal',
                    'tanggal', 'waktu', 'tempat', 'acara',
                    'tanggal_surat', 'nama_kepsek', 'nip_kepsek',
                ]),
                'format' => 'docx',
            ],
            [
                'nama'     => 'Surat Keterangan Aktif Mengajar',
                'kode'     => 'SURAT-KET-AKTIF',
                'kategori' => 'surat',
                'konten'   => '<header style="text-align:center;"><h3>PEMERINTAH KABUPATEN JEMBER<br>DINAS PENDIDIKAN<br>SMA NEGERI 2 JEMBER</h3><hr></header><p style="text-align:center;"><strong><u>SURAT KETERANGAN AKTIF MENGAJAR</u></strong><br>Nomor: {{nomor_surat}}</p><p>&nbsp;</p><p>Yang bertanda tangan di bawah ini, Kepala SMA Negeri 2 Jember, menerangkan bahwa:</p><table><tr><td>Nama</td><td>: {{nama_pegawai}}</td></tr><tr><td>NIP</td><td>: {{nip}}</td></tr><tr><td>Pangkat/Gol</td><td>: {{pangkat}}</td></tr><tr><td>Jabatan</td><td>: {{jabatan}}</td></tr></table><p>Adalah benar yang bersangkutan aktif melaksanakan tugas mengajar di SMA Negeri 2 Jember pada tahun ajaran {{tahun_ajaran}}.</p><p>Demikian surat keterangan ini dibuat untuk dipergunakan sebagaimana mestinya.</p><p style="text-align:right;">Jember, {{tanggal_surat}}<br>Kepala Sekolah,<br><br><br><br><strong>{{nama_kepsek}}</strong><br>NIP. {{nip_kepsek}}</p>',
                'kolom_isian' => json_encode([
                    'nomor_surat', 'nama_pegawai', 'nip', 'pangkat',
                    'jabatan', 'tahun_ajaran', 'tanggal_surat',
                    'nama_kepsek', 'nip_kepsek',
                ]),
                'format' => 'docx',
            ],
            [
                'nama'     => 'Surat Keterangan Siswa Aktif',
                'kode'     => 'SURAT-KET-SISWA-AKTIF',
                'kategori' => 'surat',
                'konten'   => '<header style="text-align:center;"><h3>SMA NEGERI 2 JEMBER</h3><hr></header><p style="text-align:center;"><strong><u>SURAT KETERANGAN</u></strong><br>Nomor: {{nomor_surat}}</p><p>Yang bertanda tangan di bawah ini menerangkan bahwa:</p><table><tr><td>Nama</td><td>: {{nama_siswa}}</td></tr><tr><td>NIS/NISN</td><td>: {{nis}} / {{nisn}}</td></tr><tr><td>Kelas</td><td>: {{kelas}}</td></tr><tr><td>Tahun Ajaran</td><td>: {{tahun_ajaran}}</td></tr></table><p>Adalah benar siswa aktif di SMA Negeri 2 Jember. Surat keterangan ini dibuat untuk keperluan {{keperluan}}.</p><p style="text-align:right;">Jember, {{tanggal}}<br>Kepala Sekolah,<br><br><br><br><strong>{{nama_kepsek}}</strong><br>NIP. {{nip_kepsek}}</p>',
                'kolom_isian' => json_encode([
                    'nomor_surat', 'nama_siswa', 'nis', 'nisn',
                    'kelas', 'tahun_ajaran', 'keperluan', 'tanggal',
                    'nama_kepsek', 'nip_kepsek',
                ]),
                'format' => 'docx',
            ],
            [
                'nama'     => 'Surat Tugas',
                'kode'     => 'SURAT-TUGAS',
                'kategori' => 'surat',
                'konten'   => '<header style="text-align:center;"><h3>PEMERINTAH KABUPATEN JEMBER<br>DINAS PENDIDIKAN<br>SMA NEGERI 2 JEMBER</h3><hr></header><p style="text-align:center;"><strong><u>SURAT TUGAS</u></strong><br>Nomor: {{nomor_surat}}</p><p>Kepala SMA Negeri 2 Jember menugaskan kepada:</p><table><tr><td>Nama</td><td>: {{nama_pegawai}}</td></tr><tr><td>NIP</td><td>: {{nip}}</td></tr><tr><td>Jabatan</td><td>: {{jabatan}}</td></tr></table><p>Untuk melaksanakan tugas sebagai berikut:<br>{{uraian_tugas}}</p><p>Pada tanggal {{tanggal_pelaksanaan}} di {{tempat}}.</p><p style="text-align:right;">Jember, {{tanggal_surat}}<br>Kepala Sekolah,<br><br><br><br><strong>{{nama_kepsek}}</strong><br>NIP. {{nip_kepsek}}</p>',
                'kolom_isian' => json_encode([
                    'nomor_surat', 'nama_pegawai', 'nip', 'jabatan',
                    'uraian_tugas', 'tanggal_pelaksanaan', 'tempat',
                    'tanggal_surat', 'nama_kepsek', 'nip_kepsek',
                ]),
                'format' => 'docx',
            ],
            [
                'nama'     => 'SK Kepala Sekolah',
                'kode'     => 'SK-KEPSEK',
                'kategori' => 'sk',
                'konten'   => '<header style="text-align:center;"><h3>SMA NEGERI 2 JEMBER</h3><hr></header><p style="text-align:center;"><strong>SURAT KEPUTUSAN KEPALA SEKOLAH</strong><br>Nomor: {{nomor_sk}}<br>Tentang<br><strong>{{tentang}}</strong></p><p>Menimbang: {{menimbang}}</p><p>Mengingat: {{mengingat}}</p><p style="text-align:center;"><strong>MEMUTUSKAN</strong></p><p>Menetapkan:<br>{{isi_keputusan}}</p><p>Surat Keputusan ini berlaku sejak tanggal ditetapkan.</p><p style="text-align:right;">Ditetapkan di Jember<br>Pada tanggal {{tanggal}}<br>Kepala SMA Negeri 2 Jember,<br><br><br><br><strong>{{nama_kepsek}}</strong><br>NIP. {{nip_kepsek}}</p>',
                'kolom_isian' => json_encode([
                    'nomor_sk', 'tentang', 'menimbang', 'mengingat',
                    'isi_keputusan', 'tanggal', 'nama_kepsek', 'nip_kepsek',
                ]),
                'format' => 'docx',
            ],
            [
                'nama'     => 'Notulen Rapat',
                'kode'     => 'NOTULEN-RAPAT',
                'kategori' => 'notulen',
                'konten'   => '<h3 style="text-align:center;">NOTULEN RAPAT<br>SMA NEGERI 2 JEMBER</h3><hr><table><tr><td>Hari/Tanggal</td><td>: {{tanggal}}</td></tr><tr><td>Waktu</td><td>: {{waktu}}</td></tr><tr><td>Tempat</td><td>: {{tempat}}</td></tr><tr><td>Agenda</td><td>: {{agenda}}</td></tr><tr><td>Pemimpin Rapat</td><td>: {{pemimpin_rapat}}</td></tr><tr><td>Notulis</td><td>: {{notulis}}</td></tr><tr><td>Hadir</td><td>: {{jumlah_hadir}} orang</td></tr></table><h4>Pembahasan:</h4><p>{{pembahasan}}</p><h4>Keputusan:</h4><p>{{keputusan}}</p><h4>Tindak Lanjut:</h4><p>{{tindak_lanjut}}</p><p>&nbsp;</p><p>Jember, {{tanggal}}<br>Notulis,<br><br><br><strong>{{notulis}}</strong></p>',
                'kolom_isian' => json_encode([
                    'tanggal', 'waktu', 'tempat', 'agenda',
                    'pemimpin_rapat', 'notulis', 'jumlah_hadir',
                    'pembahasan', 'keputusan', 'tindak_lanjut',
                ]),
                'format' => 'docx',
            ],
            [
                'nama'     => 'Laporan Kegiatan',
                'kode'     => 'LAPORAN-KEGIATAN',
                'kategori' => 'laporan',
                'konten'   => '<h3 style="text-align:center;">LAPORAN KEGIATAN<br>{{nama_kegiatan}}</h3><hr><h4>I. Pendahuluan</h4><p>{{pendahuluan}}</p><h4>II. Dasar Pelaksanaan</h4><p>{{dasar}}</p><h4>III. Tujuan</h4><p>{{tujuan}}</p><h4>IV. Pelaksanaan</h4><table><tr><td>Tanggal</td><td>: {{tanggal}}</td></tr><tr><td>Tempat</td><td>: {{tempat}}</td></tr><tr><td>Peserta</td><td>: {{peserta}}</td></tr></table><h4>V. Hasil</h4><p>{{hasil}}</p><h4>VI. Penutup</h4><p>{{penutup}}</p><p style="text-align:right;">Jember, {{tanggal_laporan}}<br>Penanggung Jawab,<br><br><br><strong>{{penanggung_jawab}}</strong></p>',
                'kolom_isian' => json_encode([
                    'nama_kegiatan', 'pendahuluan', 'dasar', 'tujuan',
                    'tanggal', 'tempat', 'peserta', 'hasil', 'penutup',
                    'tanggal_laporan', 'penanggung_jawab',
                ]),
                'format' => 'docx',
            ],
            [
                'nama'     => 'Surat Edaran',
                'kode'     => 'SURAT-EDARAN',
                'kategori' => 'surat',
                'konten'   => '<header style="text-align:center;"><h3>SMA NEGERI 2 JEMBER</h3><hr></header><p style="text-align:center;"><strong><u>SURAT EDARAN</u></strong><br>Nomor: {{nomor_surat}}</p><p>Kepada Yth.<br>{{tujuan}}<br>di Lingkungan SMA Negeri 2 Jember</p><p>&nbsp;</p><p>{{isi_edaran}}</p><p>Demikian surat edaran ini disampaikan untuk dilaksanakan sebagaimana mestinya.</p><p style="text-align:right;">Jember, {{tanggal}}<br>Kepala Sekolah,<br><br><br><br><strong>{{nama_kepsek}}</strong><br>NIP. {{nip_kepsek}}</p>',
                'kolom_isian' => json_encode([
                    'nomor_surat', 'tujuan', 'isi_edaran',
                    'tanggal', 'nama_kepsek', 'nip_kepsek',
                ]),
                'format' => 'docx',
            ],
            [
                'nama'     => 'Proposal Kegiatan',
                'kode'     => 'PROPOSAL-KEGIATAN',
                'kategori' => 'proposal',
                'konten'   => '<h3 style="text-align:center;">PROPOSAL KEGIATAN<br>{{nama_kegiatan}}<br>SMA NEGERI 2 JEMBER</h3><hr><h4>I. Latar Belakang</h4><p>{{latar_belakang}}</p><h4>II. Tujuan</h4><p>{{tujuan}}</p><h4>III. Sasaran</h4><p>{{sasaran}}</p><h4>IV. Waktu & Tempat</h4><p>{{waktu_tempat}}</p><h4>V. Susunan Panitia</h4><p>{{susunan_panitia}}</p><h4>VI. Rencana Anggaran</h4><p>{{rencana_anggaran}}</p><h4>VII. Penutup</h4><p>{{penutup}}</p><p style="text-align:right;">Jember, {{tanggal}}<br>Ketua Panitia,<br><br><br><strong>{{ketua_panitia}}</strong></p><p style="text-align:left;">Mengetahui,<br>Kepala Sekolah<br><br><br><strong>{{nama_kepsek}}</strong><br>NIP. {{nip_kepsek}}</p>',
                'kolom_isian' => json_encode([
                    'nama_kegiatan', 'latar_belakang', 'tujuan', 'sasaran',
                    'waktu_tempat', 'susunan_panitia', 'rencana_anggaran',
                    'penutup', 'tanggal', 'ketua_panitia',
                    'nama_kepsek', 'nip_kepsek',
                ]),
                'format' => 'docx',
            ],
            [
                'nama'     => 'Berita Acara',
                'kode'     => 'BERITA-ACARA',
                'kategori' => 'lainnya',
                'konten'   => '<h3 style="text-align:center;">BERITA ACARA<br>{{judul_berita_acara}}</h3><hr><p>Pada hari ini {{hari}}, tanggal {{tanggal}}, bertempat di {{tempat}}, telah dilaksanakan {{kegiatan}} dengan dihadiri oleh pihak-pihak yang tercantum dalam daftar hadir terlampir.</p><h4>Uraian:</h4><p>{{uraian}}</p><h4>Kesimpulan:</h4><p>{{kesimpulan}}</p><p>Demikian berita acara ini dibuat dengan sebenarnya.</p><table><tr><td style="text-align:center;">Pihak Pertama,<br><br><br><br><strong>{{pihak_1}}</strong></td><td style="text-align:center;">Pihak Kedua,<br><br><br><br><strong>{{pihak_2}}</strong></td></tr></table>',
                'kolom_isian' => json_encode([
                    'judul_berita_acara', 'hari', 'tanggal', 'tempat',
                    'kegiatan', 'uraian', 'kesimpulan',
                    'pihak_1', 'pihak_2',
                ]),
                'format' => 'docx',
            ],
        ];

        foreach ($templates as $tpl) {
            DocumentTemplate::updateOrCreate(
                ['kode' => $tpl['kode']],
                array_merge($tpl, [
                    'aktif'       => true,
                    'dibuat_oleh' => $admin->id,
                ])
            );
        }
    }
}
