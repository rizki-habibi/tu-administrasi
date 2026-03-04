{{-- Sidebar Navigation for Admin --}}
<aside class="sidebar" id="sidebar">
    {{-- Brand --}}
    <div class="sidebar-brand">
        <img src="{{ asset('storage/gambar/logo.png') }}" alt="Logo" onerror="this.style.display='none'">
        <h6>TU Administrasi</h6>
        <small>SMA Negeri 2 Jember</small>
    </div>

    {{-- Profile --}}
    <div class="sidebar-profile">
        <div class="avatar">{{ strtoupper(substr(Auth::user()->nama, 0, 2)) }}</div>
        <div class="info">
            <div class="nama">{{ Auth::user()->nama }}</div>
            <div class="peran"><i class="bi bi-shield-check"></i> Administrator</div>
        </div>
        <div class="status" title="Online"></div>
    </div>

    @php
        $pendingLeave = \App\Models\LeaveRequest::where('status', 'pending')->count();
        $unreadNotif  = \App\Models\Notification::where('sudah_dibaca', false)->count();
    @endphp

    <nav class="sidebar-nav">
        {{-- ═══════════════════════════════════════════
             Menu Utama
        ═══════════════════════════════════════════ --}}
        <div class="nav-label">Menu Utama</div>
        <div class="nav-item">
            <a href="{{ route('admin.beranda') }}" class="nav-link {{ request()->routeIs('admin.beranda') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill icon"></i> <span>Beranda</span>
            </a>
        </div>

        {{-- ═══════════════════════════════════════════
             Manajemen Pegawai
        ═══════════════════════════════════════════ --}}
        <div class="nav-label">Manajemen Pegawai</div>

        {{-- Data Staff --}}
        <div class="nav-item {{ request()->routeIs('admin.pegawai.*') ? 'open' : '' }}">
            <a class="nav-link {{ request()->routeIs('admin.pegawai.*') ? 'active' : '' }}" data-toggle="submenu">
                <i class="bi bi-people-fill icon"></i> <span>Data Staff</span> <i class="bi bi-chevron-right arrow"></i>
            </a>
            <div class="submenu">
                <a href="{{ route('admin.pegawai.index') }}" class="sub-link {{ request()->routeIs('admin.pegawai.index') ? 'active' : '' }}">Semua Staff</a>
                <a href="{{ route('admin.pegawai.create') }}" class="sub-link {{ request()->routeIs('admin.pegawai.create') ? 'active' : '' }}">Tambah Staff Baru</a>
                <a href="{{ route('admin.pegawai.ekspor', ['format' => 'pdf']) }}" class="sub-link" target="_blank">Cetak Data Staff</a>
            </div>
        </div>

        {{-- Kehadiran --}}
        <div class="nav-item {{ request()->routeIs('admin.kehadiran.*') ? 'open' : '' }}">
            <a class="nav-link {{ request()->routeIs('admin.kehadiran.*') ? 'active' : '' }}" data-toggle="submenu">
                <i class="bi bi-fingerprint icon"></i> <span>Kehadiran</span> <i class="bi bi-chevron-right arrow"></i>
            </a>
            <div class="submenu">
                <a href="{{ route('admin.kehadiran.index') }}" class="sub-link {{ request()->routeIs('admin.kehadiran.index') ? 'active' : '' }}">Absensi Hari Ini</a>
                <a href="{{ route('admin.kehadiran.laporan') }}" class="sub-link {{ request()->routeIs('admin.kehadiran.laporan') ? 'active' : '' }}">Rekap Kehadiran</a>
                <a href="{{ route('admin.kehadiran.pengaturan') }}" class="sub-link {{ request()->routeIs('admin.kehadiran.pengaturan') ? 'active' : '' }}">Pengaturan Absensi</a>
            </div>
        </div>

        {{-- Pengajuan Izin --}}
        <div class="nav-item {{ request()->routeIs('admin.izin.*') ? 'open' : '' }}">
            <a class="nav-link {{ request()->routeIs('admin.izin.*') ? 'active' : '' }}" data-toggle="submenu">
                <i class="bi bi-calendar2-check icon"></i> <span>Pengajuan Izin</span>
                @if($pendingLeave > 0)
                    <span class="badge bg-danger" style="font-size:.6rem;">{{ $pendingLeave }}</span>
                @endif
                <i class="bi bi-chevron-right arrow"></i>
            </a>
            <div class="submenu">
                <a href="{{ route('admin.izin.index') }}" class="sub-link {{ request()->routeIs('admin.izin.index') && !request('status') ? 'active' : '' }}">Semua Pengajuan</a>
                <a href="{{ route('admin.izin.index', ['status' => 'pending']) }}" class="sub-link {{ request('status') === 'pending' ? 'active' : '' }}">Menunggu Persetujuan</a>
                <a href="{{ route('admin.izin.index', ['status' => 'approved']) }}" class="sub-link {{ request('status') === 'approved' ? 'active' : '' }}">Disetujui</a>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════
             Administrasi Dokumen
        ═══════════════════════════════════════════ --}}
        <div class="nav-label">Administrasi Dokumen</div>

        {{-- Surat Menyurat --}}
        <div class="nav-item {{ request()->routeIs('admin.surat.*') ? 'open' : '' }}">
            <a class="nav-link {{ request()->routeIs('admin.surat.*') ? 'active' : '' }}" data-toggle="submenu">
                <i class="bi bi-envelope-paper-fill icon"></i> <span>Surat Menyurat</span> <i class="bi bi-chevron-right arrow"></i>
            </a>
            <div class="submenu">
                <a href="{{ route('admin.surat.index') }}" class="sub-link {{ request()->routeIs('admin.surat.index') && !request('jenis') ? 'active' : '' }}">Semua Surat</a>
                <a href="{{ route('admin.surat.index', ['jenis' => 'masuk']) }}" class="sub-link {{ request('jenis') === 'masuk' ? 'active' : '' }}">Surat Masuk</a>
                <a href="{{ route('admin.surat.index', ['jenis' => 'keluar']) }}" class="sub-link {{ request('jenis') === 'keluar' ? 'active' : '' }}">Surat Keluar</a>
                <a href="{{ route('admin.surat.create') }}" class="sub-link {{ request()->routeIs('admin.surat.create') ? 'active' : '' }}">Buat Surat Baru</a>
            </div>
        </div>

        {{-- Dokumen & Arsip --}}
        <div class="nav-item {{ request()->routeIs('admin.dokumen.*') ? 'open' : '' }}">
            <a class="nav-link {{ request()->routeIs('admin.dokumen.*') ? 'active' : '' }}" data-toggle="submenu">
                <i class="bi bi-archive-fill icon"></i> <span>Dokumen & Arsip</span> <i class="bi bi-chevron-right arrow"></i>
            </a>
            <div class="submenu">
                <a href="{{ route('admin.dokumen.index') }}" class="sub-link {{ request()->routeIs('admin.dokumen.index') && !request('kategori') ? 'active' : '' }}">Semua Dokumen</a>
                <a href="{{ route('admin.dokumen.create') }}" class="sub-link {{ request()->routeIs('admin.dokumen.create') ? 'active' : '' }}">Upload Dokumen</a>
                <a href="{{ route('admin.dokumen.index', ['kategori' => 'surat']) }}" class="sub-link {{ request('kategori') === 'surat' ? 'active' : '' }}">Surat Menyurat</a>
                <a href="{{ route('admin.dokumen.index', ['kategori' => 'keuangan']) }}" class="sub-link {{ request('kategori') === 'keuangan' ? 'active' : '' }}">Keuangan</a>
                <a href="{{ route('admin.dokumen.index', ['kategori' => 'kepegawaian']) }}" class="sub-link {{ request('kategori') === 'kepegawaian' ? 'active' : '' }}">Kepegawaian</a>
            </div>
        </div>

        {{-- Laporan --}}
        <div class="nav-item {{ request()->routeIs('admin.laporan.*') ? 'open' : '' }}">
            <a class="nav-link {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}" data-toggle="submenu">
                <i class="bi bi-journal-text icon"></i> <span>Laporan</span> <i class="bi bi-chevron-right arrow"></i>
            </a>
            <div class="submenu">
                <a href="{{ route('admin.laporan.index') }}" class="sub-link {{ request()->routeIs('admin.laporan.index') && !request('kategori') ? 'active' : '' }}">Semua Laporan</a>
                <a href="{{ route('admin.laporan.index', ['kategori' => 'keuangan']) }}" class="sub-link {{ request('kategori') === 'keuangan' ? 'active' : '' }}">Laporan Keuangan</a>
                <a href="{{ route('admin.laporan.index', ['kategori' => 'inventaris']) }}" class="sub-link {{ request('kategori') === 'inventaris' ? 'active' : '' }}">Laporan Inventaris</a>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════
             Akademik & Kurikulum
        ═══════════════════════════════════════════ --}}
        <div class="nav-label">Akademik & Kurikulum</div>

        {{-- Kurikulum --}}
        <div class="nav-item {{ request()->routeIs('admin.kurikulum.*') ? 'open' : '' }}">
            <a class="nav-link {{ request()->routeIs('admin.kurikulum.*') ? 'active' : '' }}" data-toggle="submenu">
                <i class="bi bi-book-half icon"></i> <span>Kurikulum</span> <i class="bi bi-chevron-right arrow"></i>
            </a>
            <div class="submenu">
                <a href="{{ route('admin.kurikulum.index') }}" class="sub-link {{ request()->routeIs('admin.kurikulum.index') && !request('jenis') ? 'active' : '' }}">Semua Dokumen</a>
                <a href="{{ route('admin.kurikulum.create') }}" class="sub-link {{ request()->routeIs('admin.kurikulum.create') ? 'active' : '' }}">Tambah Dokumen</a>
                <a href="{{ route('admin.kurikulum.index', ['jenis' => 'rpp']) }}" class="sub-link {{ request('jenis') === 'rpp' ? 'active' : '' }}">RPP / Modul Ajar</a>
                <a href="{{ route('admin.kurikulum.index', ['jenis' => 'silabus']) }}" class="sub-link {{ request('jenis') === 'silabus' ? 'active' : '' }}">Silabus / ATP</a>
                <a href="{{ route('admin.kurikulum.index', ['jenis' => 'jadwal']) }}" class="sub-link {{ request('jenis') === 'jadwal' ? 'active' : '' }}">Jadwal Pelajaran</a>
                <a href="{{ route('admin.kurikulum.index', ['jenis' => 'kalender']) }}" class="sub-link {{ request('jenis') === 'kalender' ? 'active' : '' }}">Kalender Pendidikan</a>
            </div>
        </div>

        {{-- Kesiswaan --}}
        <div class="nav-item {{ request()->routeIs('admin.kesiswaan.*') ? 'open' : '' }}">
            <a class="nav-link {{ request()->routeIs('admin.kesiswaan.*') ? 'active' : '' }}" data-toggle="submenu">
                <i class="bi bi-mortarboard-fill icon"></i> <span>Kesiswaan</span> <i class="bi bi-chevron-right arrow"></i>
            </a>
            <div class="submenu">
                <a href="{{ route('admin.kesiswaan.index') }}" class="sub-link {{ request()->routeIs('admin.kesiswaan.index') ? 'active' : '' }}">Data Siswa</a>
                <a href="{{ route('admin.kesiswaan.create') }}" class="sub-link {{ request()->routeIs('admin.kesiswaan.create') ? 'active' : '' }}">Tambah Siswa</a>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════
             Sarana & Keuangan
        ═══════════════════════════════════════════ --}}
        <div class="nav-label">Sarana & Keuangan</div>

        {{-- Inventaris --}}
        <div class="nav-item {{ request()->routeIs('admin.inventaris.*') ? 'open' : '' }}">
            <a class="nav-link {{ request()->routeIs('admin.inventaris.*') ? 'active' : '' }}" data-toggle="submenu">
                <i class="bi bi-box-seam-fill icon"></i> <span>Inventaris / Sarpras</span> <i class="bi bi-chevron-right arrow"></i>
            </a>
            <div class="submenu">
                <a href="{{ route('admin.inventaris.index') }}" class="sub-link {{ request()->routeIs('admin.inventaris.index') ? 'active' : '' }}">Daftar Inventaris</a>
                <a href="{{ route('admin.inventaris.create') }}" class="sub-link {{ request()->routeIs('admin.inventaris.create') ? 'active' : '' }}">Tambah Barang</a>
            </div>
        </div>

        {{-- Keuangan --}}
        <div class="nav-item {{ request()->routeIs('admin.keuangan.*') ? 'open' : '' }}">
            <a class="nav-link {{ request()->routeIs('admin.keuangan.*') ? 'active' : '' }}" data-toggle="submenu">
                <i class="bi bi-cash-coin icon"></i> <span>Keuangan</span> <i class="bi bi-chevron-right arrow"></i>
            </a>
            <div class="submenu">
                <a href="{{ route('admin.keuangan.index') }}" class="sub-link {{ request()->routeIs('admin.keuangan.index') ? 'active' : '' }}">Transaksi</a>
                <a href="{{ route('admin.keuangan.create') }}" class="sub-link {{ request()->routeIs('admin.keuangan.create') ? 'active' : '' }}">Tambah Transaksi</a>
                <a href="{{ route('admin.keuangan.anggaran') }}" class="sub-link {{ request()->routeIs('admin.keuangan.anggaran') ? 'active' : '' }}">RKAS / Anggaran</a>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════
             Evaluasi & Penilaian
        ═══════════════════════════════════════════ --}}
        <div class="nav-label">Evaluasi & Penilaian</div>

        {{-- Evaluasi Kinerja --}}
        <div class="nav-item {{ request()->routeIs('admin.evaluasi.*') ? 'open' : '' }}">
            <a class="nav-link {{ request()->routeIs('admin.evaluasi.*') ? 'active' : '' }}" data-toggle="submenu">
                <i class="bi bi-clipboard2-data-fill icon"></i> <span>Evaluasi Kinerja</span> <i class="bi bi-chevron-right arrow"></i>
            </a>
            <div class="submenu">
                <a href="{{ route('admin.evaluasi.pkg') }}" class="sub-link {{ request()->routeIs('admin.evaluasi.pkg*') ? 'active' : '' }}">PKG / BKD / SKP</a>
                <a href="{{ route('admin.evaluasi.p5') }}" class="sub-link {{ request()->routeIs('admin.evaluasi.p5*') ? 'active' : '' }}">Asesmen P5</a>
                <a href="{{ route('admin.evaluasi.star') }}" class="sub-link {{ request()->routeIs('admin.evaluasi.star*') ? 'active' : '' }}">Metode STAR</a>
                <a href="{{ route('admin.evaluasi.bukti-fisik') }}" class="sub-link {{ request()->routeIs('admin.evaluasi.bukti-fisik*') ? 'active' : '' }}">Bukti Fisik</a>
                <a href="{{ route('admin.evaluasi.pembelajaran') }}" class="sub-link {{ request()->routeIs('admin.evaluasi.pembelajaran*') ? 'active' : '' }}">Model Pembelajaran</a>
            </div>
        </div>

        {{-- Akreditasi --}}
        <div class="nav-item {{ request()->routeIs('admin.akreditasi.*') ? 'open' : '' }}">
            <a class="nav-link {{ request()->routeIs('admin.akreditasi.*') ? 'active' : '' }}" data-toggle="submenu">
                <i class="bi bi-award-fill icon"></i> <span>Akreditasi</span> <i class="bi bi-chevron-right arrow"></i>
            </a>
            <div class="submenu">
                <a href="{{ route('admin.akreditasi.index') }}" class="sub-link {{ request()->routeIs('admin.akreditasi.index') ? 'active' : '' }}">Dokumen Akreditasi</a>
                <a href="{{ route('admin.akreditasi.create') }}" class="sub-link {{ request()->routeIs('admin.akreditasi.create') ? 'active' : '' }}">Tambah Dokumen</a>
                <a href="{{ route('admin.akreditasi.eds') }}" class="sub-link {{ request()->routeIs('admin.akreditasi.eds*') ? 'active' : '' }}">Evaluasi Diri (EDS)</a>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════
             Kegiatan & Komunikasi
        ═══════════════════════════════════════════ --}}
        <div class="nav-label">Kegiatan & Komunikasi</div>

        {{-- Agenda & Event --}}
        <div class="nav-item {{ request()->routeIs('admin.agenda.*') ? 'open' : '' }}">
            <a class="nav-link {{ request()->routeIs('admin.agenda.*') ? 'active' : '' }}" data-toggle="submenu">
                <i class="bi bi-calendar-event-fill icon"></i> <span>Agenda & Event</span> <i class="bi bi-chevron-right arrow"></i>
            </a>
            <div class="submenu">
                <a href="{{ route('admin.agenda.index') }}" class="sub-link {{ request()->routeIs('admin.agenda.index') ? 'active' : '' }}">Semua Event</a>
                <a href="{{ route('admin.agenda.create') }}" class="sub-link {{ request()->routeIs('admin.agenda.create') ? 'active' : '' }}">Buat Event Baru</a>
            </div>
        </div>

        {{-- Notifikasi --}}
        <div class="nav-item {{ request()->routeIs('admin.notifikasi.*') ? 'open' : '' }}">
            <a class="nav-link {{ request()->routeIs('admin.notifikasi.*') ? 'active' : '' }}" data-toggle="submenu">
                <i class="bi bi-megaphone-fill icon"></i> <span>Notifikasi</span>
                @if($unreadNotif > 0)
                    <span class="badge bg-info" style="font-size:.6rem;">{{ $unreadNotif }}</span>
                @endif
                <i class="bi bi-chevron-right arrow"></i>
            </a>
            <div class="submenu">
                <a href="{{ route('admin.notifikasi.index') }}" class="sub-link {{ request()->routeIs('admin.notifikasi.index') ? 'active' : '' }}">Semua Notifikasi</a>
                <a href="{{ route('admin.notifikasi.create') }}" class="sub-link {{ request()->routeIs('admin.notifikasi.create') ? 'active' : '' }}">Kirim Pengumuman</a>
            </div>
        </div>

        {{-- Ulang Tahun --}}
        <div class="nav-item">
            <a href="{{ route('admin.ulang-tahun.index') }}" class="nav-link {{ request()->routeIs('admin.ulang-tahun.*') ? 'active' : '' }}">
                <i class="bi bi-gift-fill icon"></i> <span>Ulang Tahun</span>
            </a>
        </div>

        {{-- ═══════════════════════════════════════════
             Sistem
        ═══════════════════════════════════════════ --}}
        <div class="nav-label">Sistem</div>

        {{-- Pengingat --}}
        <div class="nav-item {{ request()->routeIs('admin.pengingat.*') ? 'open' : '' }}">
            <a class="nav-link {{ request()->routeIs('admin.pengingat.*') ? 'active' : '' }}" data-toggle="submenu">
                <i class="bi bi-bell-fill icon"></i> <span>Pengingat</span>
                @php $overdueReminders = \App\Models\Reminder::where('selesai', false)->where('tenggat', '<', now())->count(); @endphp
                @if($overdueReminders > 0)
                    <span class="badge bg-warning text-dark" style="font-size:.6rem;">{{ $overdueReminders }}</span>
                @endif
                <i class="bi bi-chevron-right arrow"></i>
            </a>
            <div class="submenu">
                <a href="{{ route('admin.pengingat.index') }}" class="sub-link {{ request()->routeIs('admin.pengingat.index') ? 'active' : '' }}">Semua Pengingat</a>
                <a href="{{ route('admin.pengingat.create') }}" class="sub-link {{ request()->routeIs('admin.pengingat.create') ? 'active' : '' }}">Buat Pengingat</a>
            </div>
        </div>

        {{-- Panduan --}}
        <div class="nav-item">
            <a href="{{ route('admin.panduan.index') }}" class="nav-link {{ request()->routeIs('admin.panduan.*') ? 'active' : '' }}">
                <i class="bi bi-book icon"></i> <span>Panduan</span>
            </a>
        </div>

        {{-- Word & AI --}}
        <div class="nav-item {{ request()->routeIs('admin.word-ai.*') ? 'open' : '' }}">
            <a class="nav-link {{ request()->routeIs('admin.word-ai.*') ? 'active' : '' }}" data-toggle="submenu">
                <i class="bi bi-file-earmark-word-fill icon"></i> <span>Word & AI</span> <i class="bi bi-chevron-right arrow"></i>
            </a>
            <div class="submenu">
                <a href="{{ route('admin.word-ai.index') }}" class="sub-link {{ request()->routeIs('admin.word-ai.index') ? 'active' : '' }}">Semua Dokumen</a>
                <a href="{{ route('admin.word-ai.create') }}" class="sub-link {{ request()->routeIs('admin.word-ai.create') ? 'active' : '' }}">Buat Dokumen Baru</a>
                <a href="{{ route('admin.word-ai.template') }}" class="sub-link {{ request()->routeIs('admin.word-ai.template') ? 'active' : '' }}">Template Dokumen</a>
            </div>
        </div>

        {{-- Pengaturan --}}
        <div class="nav-item {{ request()->routeIs('admin.kehadiran.pengaturan') && request()->is('*/pengaturan*') ? 'open' : '' }}">
            <a class="nav-link" data-toggle="submenu">
                <i class="bi bi-gear-fill icon"></i> <span>Pengaturan</span> <i class="bi bi-chevron-right arrow"></i>
            </a>
            <div class="submenu">
                <a href="{{ route('admin.kehadiran.pengaturan') }}" class="sub-link {{ request()->routeIs('admin.kehadiran.pengaturan') ? 'active' : '' }}">Pengaturan Absensi</a>
                <a href="{{ route('admin.pegawai.ekspor', ['format' => 'csv']) }}" class="sub-link">Export Data Staff</a>
                <a href="{{ route('admin.kehadiran.ekspor', ['format' => 'csv']) }}" class="sub-link">Export Kehadiran</a>
                <a href="{{ route('admin.dokumen.ekspor', ['format' => 'csv']) }}" class="sub-link">Export Dokumen</a>
            </div>
        </div>
    </nav>

    {{-- Sidebar Footer --}}
    <div class="sidebar-footer">
        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="bi bi-box-arrow-left"></i> <span>Logout</span>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
    </div>
</aside>
