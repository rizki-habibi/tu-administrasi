{{-- Sidebar Navigation for Admin --}}
@php
    $pendingLeave = \App\Models\PengajuanIzin::where('status', 'pending')->count();
    $unreadNotif  = \App\Models\Notifikasi::where('sudah_dibaca', false)->count();
    $saranBaru    = \App\Models\SaranPengunjung::baru()->count();
    $overdueReminders = \App\Models\Pengingat::where('selesai', false)->where('tenggat', '<', now())->count();
    $totalStaf    = \App\Models\Pengguna::whereIn('peran', \App\Models\Pengguna::STAFF_ROLES)->count();
    $stafAktif    = \App\Models\Pengguna::whereIn('peran', \App\Models\Pengguna::STAFF_ROLES)->where('aktif', true)->count();
    $totalSurat   = \App\Models\Surat::count();
    $totalDokumen = \App\Models\Dokumen::count();
    $totalInventaris = \App\Models\Inventaris::count();
    $totalSiswa   = \App\Models\DataSiswa::count();
@endphp

<aside class="sidebar" id="sidebar">
    {{-- Brand --}}
    <div class="sidebar-brand">
        <div class="d-flex align-items-center gap-2">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" onerror="this.style.display='none'">
            <div>
                <h6>SIMPEG-SMART</h6>
                <small>SMA Negeri 2 Jember</small>
            </div>
        </div>
    </div>

    {{-- User Profile --}}
    <div class="sidebar-profile">
        <div class="avatar">{{ strtoupper(substr(Auth::user()->nama, 0, 2)) }}</div>
        <div class="info">
            <div class="name">{{ Auth::user()->nama }}</div>
            <div class="role">{{ \App\Models\Pengguna::ROLES[Auth::user()->peran] ?? Auth::user()->peran }}</div>
        </div>
        <div class="status" title="Online"></div>
    </div>

    {{-- Search --}}
    <div class="sidebar-search">
        <i class="bi bi-search"></i>
        <input type="text" id="sidebarSearch" placeholder="Cari menu..." autocomplete="off">
    </div>

    <nav class="sidebar-nav" id="sidebarNav">

        {{-- ▸ Menu Utama --}}
        <div class="nav-group open">
            <div class="nav-group-label" data-toggle="nav-group">
                <span>Menu Utama</span>
                <i class="bi bi-chevron-down"></i>
            </div>
            <div class="nav-group-items">
                <div class="nav-item">
                    <a href="{{ route('admin.beranda') }}" class="nav-link {{ request()->routeIs('admin.beranda') ? 'active' : '' }}">
                        <i class="bi bi-grid-1x2-fill icon"></i> <span>Beranda</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- ▸ Halaman Publik — Kelola profil SMA, galeri, kerjasama & konten website publik --}}
        <div class="nav-group {{ request()->routeIs('admin.halaman-publik.*') ? 'open' : '' }}">
            <div class="nav-group-label" data-toggle="nav-group">
                <span>Halaman Publik</span>
                <i class="bi bi-chevron-down"></i>
            </div>
            <div class="nav-group-desc">Profil SMA, galeri, kerjasama & konten website</div>
            <div class="nav-group-items">
                <div class="nav-item {{ request()->routeIs('admin.halaman-publik.*') && !request()->routeIs('admin.halaman-publik.statistik') && !request()->routeIs('admin.halaman-publik.saran*') ? 'open' : '' }}">
                    <a class="nav-link {{ request()->routeIs('admin.halaman-publik.*') && !request()->routeIs('admin.halaman-publik.statistik') && !request()->routeIs('admin.halaman-publik.saran*') ? 'active' : '' }}" data-toggle="submenu">
                        <i class="bi bi-globe2 icon"></i> <span>Konten Website</span> <i class="bi bi-chevron-right arrow"></i>
                    </a>
                    <div class="submenu">
                        <a href="{{ route('admin.halaman-publik.index') }}" class="sub-link {{ request()->routeIs('admin.halaman-publik.index') && !request('bagian') && !request('kategori') ? 'active' : '' }}">
                            <i class="bi bi-list-ul sub-icon"></i> Semua Konten
                        </a>
                        <a href="{{ route('admin.halaman-publik.create') }}" class="sub-link {{ request()->routeIs('admin.halaman-publik.create') ? 'active' : '' }}">
                            <i class="bi bi-plus-circle sub-icon"></i> Tambah Konten
                        </a>
                        <a href="{{ route('admin.halaman-publik.index', ['bagian' => 'kinerja']) }}" class="sub-link {{ request('bagian') === 'kinerja' ? 'active' : '' }}">
                            <i class="bi bi-speedometer2 sub-icon"></i> Konten Kinerja
                        </a>
                        <a href="{{ route('admin.halaman-publik.index', ['kategori' => 'galeri']) }}" class="sub-link {{ request('kategori') === 'galeri' ? 'active' : '' }}">
                            <i class="bi bi-images sub-icon"></i> Galeri & Media
                        </a>
                        <a href="{{ route('admin.halaman-publik.index', ['kategori' => 'kerjasama']) }}" class="sub-link {{ request('kategori') === 'kerjasama' ? 'active' : '' }}">
                            <i class="bi bi-handshake sub-icon"></i> Kerjasama / MOU
                        </a>
                        <a href="{{ route('admin.halaman-publik.index', ['kategori' => 'dokumen']) }}" class="sub-link {{ request('kategori') === 'dokumen' ? 'active' : '' }}">
                            <i class="bi bi-file-earmark sub-icon"></i> Dokumen Publik
                        </a>
                    </div>
                </div>
                <div class="nav-item {{ request()->routeIs('admin.halaman-publik.statistik') || request()->routeIs('admin.halaman-publik.saran*') ? 'open' : '' }}">
                    <a class="nav-link {{ request()->routeIs('admin.halaman-publik.statistik') || request()->routeIs('admin.halaman-publik.saran*') ? 'active' : '' }}" data-toggle="submenu">
                        <i class="bi bi-bar-chart-line-fill icon"></i> <span>Kinerja & Saran</span>
                        @if($saranBaru > 0)<span class="badge bg-danger">{{ $saranBaru }}</span>@endif
                        <i class="bi bi-chevron-right arrow"></i>
                    </a>
                    <div class="submenu">
                        <a href="{{ route('admin.halaman-publik.statistik') }}" class="sub-link {{ request()->routeIs('admin.halaman-publik.statistik') ? 'active' : '' }}">
                            <i class="bi bi-pie-chart sub-icon"></i> Statistik Pengunjung
                        </a>
                        <a href="{{ route('admin.halaman-publik.saran') }}" class="sub-link {{ request()->routeIs('admin.halaman-publik.saran*') ? 'active' : '' }}">
                            <i class="bi bi-chat-heart sub-icon"></i> Saran Pengunjung
                            @if($saranBaru > 0)<span class="badge bg-danger ms-auto">{{ $saranBaru }}</span>@endif
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- ▸ Manajemen Pegawai — Data staf, absensi kehadiran & pengajuan izin/cuti --}}
        <div class="nav-group {{ request()->routeIs('admin.pegawai.*') || request()->routeIs('admin.kehadiran.*') || request()->routeIs('admin.izin.*') ? 'open' : '' }}">
            <div class="nav-group-label" data-toggle="nav-group">
                <span>Manajemen Pegawai</span>
                <span class="group-badge">{{ $totalStaf }}</span>
                <i class="bi bi-chevron-down"></i>
            </div>
            <div class="nav-group-desc">{{ $stafAktif }} aktif dari {{ $totalStaf }} staf — kelola data, kehadiran & izin</div>
            <div class="nav-group-items">
                <div class="nav-item {{ request()->routeIs('admin.pegawai.*') ? 'open' : '' }}">
                    <a class="nav-link {{ request()->routeIs('admin.pegawai.*') ? 'active' : '' }}" data-toggle="submenu">
                        <i class="bi bi-people-fill icon"></i> <span>Data Staf</span>
                        <span class="count-pill">{{ $totalStaf }}</span>
                        <i class="bi bi-chevron-right arrow"></i>
                    </a>
                    <div class="submenu">
                        <a href="{{ route('admin.pegawai.index') }}" class="sub-link {{ request()->routeIs('admin.pegawai.index') ? 'active' : '' }}">
                            <i class="bi bi-person-lines-fill sub-icon"></i> Semua Staf
                        </a>
                        <a href="{{ route('admin.pegawai.create') }}" class="sub-link {{ request()->routeIs('admin.pegawai.create') ? 'active' : '' }}">
                            <i class="bi bi-person-plus sub-icon"></i> Tambah Staf Baru
                        </a>
                    </div>
                </div>
                <div class="nav-item {{ request()->routeIs('admin.kehadiran.*') ? 'open' : '' }}">
                    <a class="nav-link {{ request()->routeIs('admin.kehadiran.*') ? 'active' : '' }}" data-toggle="submenu">
                        <i class="bi bi-fingerprint icon"></i> <span>Kehadiran</span> <i class="bi bi-chevron-right arrow"></i>
                    </a>
                    <div class="submenu">
                        <a href="{{ route('admin.kehadiran.index') }}" class="sub-link {{ request()->routeIs('admin.kehadiran.index') ? 'active' : '' }}">
                            <i class="bi bi-clock sub-icon"></i> Absensi Hari Ini
                        </a>
                        <a href="{{ route('admin.kehadiran.laporan') }}" class="sub-link {{ request()->routeIs('admin.kehadiran.laporan') ? 'active' : '' }}">
                            <i class="bi bi-file-bar-graph sub-icon"></i> Rekap Kehadiran
                        </a>
                        <a href="{{ route('admin.kehadiran.pengaturan') }}" class="sub-link {{ request()->routeIs('admin.kehadiran.pengaturan') ? 'active' : '' }}">
                            <i class="bi bi-sliders sub-icon"></i> Pengaturan Absensi
                        </a>
                    </div>
                </div>
                <div class="nav-item {{ request()->routeIs('admin.izin.*') ? 'open' : '' }}">
                    <a class="nav-link {{ request()->routeIs('admin.izin.*') ? 'active' : '' }}" data-toggle="submenu">
                        <i class="bi bi-calendar2-check icon"></i> <span>Pengajuan Izin</span>
                        @if($pendingLeave > 0)<span class="badge bg-danger">{{ $pendingLeave }}</span>@endif
                        <i class="bi bi-chevron-right arrow"></i>
                    </a>
                    <div class="submenu">
                        <a href="{{ route('admin.izin.index') }}" class="sub-link {{ request()->routeIs('admin.izin.index') && !request('status') ? 'active' : '' }}">
                            <i class="bi bi-list-check sub-icon"></i> Semua Pengajuan
                        </a>
                        <a href="{{ route('admin.izin.index', ['status' => 'pending']) }}" class="sub-link {{ request('status') === 'pending' ? 'active' : '' }}">
                            <i class="bi bi-hourglass-split sub-icon"></i> Menunggu Persetujuan
                            @if($pendingLeave > 0)<span class="badge bg-warning text-dark ms-auto">{{ $pendingLeave }}</span>@endif
                        </a>
                        <a href="{{ route('admin.izin.index', ['status' => 'approved']) }}" class="sub-link {{ request('status') === 'approved' ? 'active' : '' }}">
                            <i class="bi bi-check2-circle sub-icon"></i> Disetujui
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- ▸ Kepegawaian — Riwayat jabatan, pangkat, golongan & arsip dokumen SK --}}
        <div class="nav-group {{ request()->routeIs('admin.kepegawaian.*') ? 'open' : '' }}">
            <div class="nav-group-label" data-toggle="nav-group">
                <span>Kepegawaian</span>
                <i class="bi bi-chevron-down"></i>
            </div>
            <div class="nav-group-desc">Riwayat jabatan, pangkat & arsip dokumen SK pegawai</div>
            <div class="nav-group-items">
                <div class="nav-item">
                    <a href="{{ route('admin.kepegawaian.jabatan.index') }}" class="nav-link {{ request()->routeIs('admin.kepegawaian.jabatan.*') ? 'active' : '' }}">
                        <i class="bi bi-briefcase-fill icon"></i> <span>Riwayat Jabatan</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('admin.kepegawaian.pangkat.index') }}" class="nav-link {{ request()->routeIs('admin.kepegawaian.pangkat.*') ? 'active' : '' }}">
                        <i class="bi bi-award-fill icon"></i> <span>Riwayat Pangkat</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('admin.kepegawaian.dokumen.index') }}" class="nav-link {{ request()->routeIs('admin.kepegawaian.dokumen.*') ? 'active' : '' }}">
                        <i class="bi bi-folder2-open icon"></i> <span>Dokumen Kepegawaian</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('admin.kepegawaian.laporan') }}" class="nav-link {{ request()->routeIs('admin.kepegawaian.laporan') ? 'active' : '' }}">
                        <i class="bi bi-graph-up-arrow icon"></i> <span>Laporan Kepegawaian</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- ▸ Administrasi Dokumen — Surat masuk/keluar, arsip digital & laporan --}}
        <div class="nav-group {{ request()->routeIs('admin.surat.*') || request()->routeIs('admin.dokumen.*') || request()->routeIs('admin.laporan.*') ? 'open' : '' }}">
            <div class="nav-group-label" data-toggle="nav-group">
                <span>Administrasi Dokumen</span>
                <span class="group-badge">{{ $totalSurat + $totalDokumen }}</span>
                <i class="bi bi-chevron-down"></i>
            </div>
            <div class="nav-group-desc">{{ $totalSurat }} surat & {{ $totalDokumen }} dokumen arsip</div>
            <div class="nav-group-items">
                <div class="nav-item {{ request()->routeIs('admin.surat.*') ? 'open' : '' }}">
                    <a class="nav-link {{ request()->routeIs('admin.surat.*') ? 'active' : '' }}" data-toggle="submenu">
                        <i class="bi bi-envelope-paper-fill icon"></i> <span>Surat Menyurat</span>
                        <span class="count-pill">{{ $totalSurat }}</span>
                        <i class="bi bi-chevron-right arrow"></i>
                    </a>
                    <div class="submenu">
                        <a href="{{ route('admin.surat.index') }}" class="sub-link {{ request()->routeIs('admin.surat.index') && !request('jenis') ? 'active' : '' }}">
                            <i class="bi bi-inboxes sub-icon"></i> Semua Surat
                        </a>
                        <a href="{{ route('admin.surat.index', ['jenis' => 'masuk']) }}" class="sub-link {{ request('jenis') === 'masuk' ? 'active' : '' }}">
                            <i class="bi bi-box-arrow-in-down sub-icon"></i> Surat Masuk
                        </a>
                        <a href="{{ route('admin.surat.index', ['jenis' => 'keluar']) }}" class="sub-link {{ request('jenis') === 'keluar' ? 'active' : '' }}">
                            <i class="bi bi-box-arrow-up sub-icon"></i> Surat Keluar
                        </a>
                        <a href="{{ route('admin.surat.create') }}" class="sub-link {{ request()->routeIs('admin.surat.create') ? 'active' : '' }}">
                            <i class="bi bi-plus-circle sub-icon"></i> Buat Surat Baru
                        </a>
                    </div>
                </div>
                <div class="nav-item {{ request()->routeIs('admin.dokumen.*') ? 'open' : '' }}">
                    <a class="nav-link {{ request()->routeIs('admin.dokumen.*') ? 'active' : '' }}" data-toggle="submenu">
                        <i class="bi bi-archive-fill icon"></i> <span>Dokumen & Arsip</span>
                        <span class="count-pill">{{ $totalDokumen }}</span>
                        <i class="bi bi-chevron-right arrow"></i>
                    </a>
                    <div class="submenu">
                        <a href="{{ route('admin.dokumen.index') }}" class="sub-link {{ request()->routeIs('admin.dokumen.index') && !request('kategori') ? 'active' : '' }}">
                            <i class="bi bi-folder sub-icon"></i> Semua Dokumen
                        </a>
                        <a href="{{ route('admin.dokumen.create') }}" class="sub-link {{ request()->routeIs('admin.dokumen.create') ? 'active' : '' }}">
                            <i class="bi bi-cloud-upload sub-icon"></i> Upload Dokumen
                        </a>
                        <a href="{{ route('admin.dokumen.index', ['kategori' => 'surat']) }}" class="sub-link {{ request('kategori') === 'surat' ? 'active' : '' }}">
                            <i class="bi bi-envelope sub-icon"></i> Surat Menyurat
                        </a>
                        <a href="{{ route('admin.dokumen.index', ['kategori' => 'keuangan']) }}" class="sub-link {{ request('kategori') === 'keuangan' ? 'active' : '' }}">
                            <i class="bi bi-currency-dollar sub-icon"></i> Keuangan
                        </a>
                        <a href="{{ route('admin.dokumen.index', ['kategori' => 'kepegawaian']) }}" class="sub-link {{ request('kategori') === 'kepegawaian' ? 'active' : '' }}">
                            <i class="bi bi-person-badge sub-icon"></i> Kepegawaian
                        </a>
                    </div>
                </div>
                <div class="nav-item {{ request()->routeIs('admin.laporan.*') ? 'open' : '' }}">
                    <a class="nav-link {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}" data-toggle="submenu">
                        <i class="bi bi-journal-text icon"></i> <span>Laporan</span> <i class="bi bi-chevron-right arrow"></i>
                    </a>
                    <div class="submenu">
                        <a href="{{ route('admin.laporan.index') }}" class="sub-link {{ request()->routeIs('admin.laporan.index') && !request('kategori') ? 'active' : '' }}">
                            <i class="bi bi-journals sub-icon"></i> Semua Laporan
                        </a>
                        <a href="{{ route('admin.laporan.index', ['kategori' => 'keuangan']) }}" class="sub-link {{ request('kategori') === 'keuangan' ? 'active' : '' }}">
                            <i class="bi bi-cash-stack sub-icon"></i> Laporan Keuangan
                        </a>
                        <a href="{{ route('admin.laporan.index', ['kategori' => 'inventaris']) }}" class="sub-link {{ request('kategori') === 'inventaris' ? 'active' : '' }}">
                            <i class="bi bi-box sub-icon"></i> Laporan Inventaris
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- ▸ Akademik & Kurikulum — Dokumen kurikulum, jadwal, data siswa --}}
        <div class="nav-group {{ request()->routeIs('admin.kurikulum.*') || request()->routeIs('admin.kesiswaan.*') ? 'open' : '' }}">
            <div class="nav-group-label" data-toggle="nav-group">
                <span>Akademik & Kurikulum</span>
                <i class="bi bi-chevron-down"></i>
            </div>
            <div class="nav-group-desc">RPP, silabus, jadwal pelajaran & data {{ $totalSiswa }} siswa</div>
            <div class="nav-group-items">
                <div class="nav-item {{ request()->routeIs('admin.kurikulum.*') ? 'open' : '' }}">
                    <a class="nav-link {{ request()->routeIs('admin.kurikulum.*') ? 'active' : '' }}" data-toggle="submenu">
                        <i class="bi bi-book-half icon"></i> <span>Kurikulum</span> <i class="bi bi-chevron-right arrow"></i>
                    </a>
                    <div class="submenu">
                        <a href="{{ route('admin.kurikulum.index') }}" class="sub-link {{ request()->routeIs('admin.kurikulum.index') && !request('jenis') ? 'active' : '' }}">
                            <i class="bi bi-folder sub-icon"></i> Semua Dokumen
                        </a>
                        <a href="{{ route('admin.kurikulum.create') }}" class="sub-link {{ request()->routeIs('admin.kurikulum.create') ? 'active' : '' }}">
                            <i class="bi bi-plus-circle sub-icon"></i> Tambah Dokumen
                        </a>
                        <a href="{{ route('admin.kurikulum.index', ['jenis' => 'rpp']) }}" class="sub-link {{ request('jenis') === 'rpp' ? 'active' : '' }}">
                            <i class="bi bi-file-text sub-icon"></i> RPP / Modul Ajar
                        </a>
                        <a href="{{ route('admin.kurikulum.index', ['jenis' => 'silabus']) }}" class="sub-link {{ request('jenis') === 'silabus' ? 'active' : '' }}">
                            <i class="bi bi-file-ruled sub-icon"></i> Silabus / ATP
                        </a>
                        <a href="{{ route('admin.kurikulum.index', ['jenis' => 'jadwal']) }}" class="sub-link {{ request('jenis') === 'jadwal' ? 'active' : '' }}">
                            <i class="bi bi-table sub-icon"></i> Jadwal Pelajaran
                        </a>
                        <a href="{{ route('admin.kurikulum.index', ['jenis' => 'kalender']) }}" class="sub-link {{ request('jenis') === 'kalender' ? 'active' : '' }}">
                            <i class="bi bi-calendar3 sub-icon"></i> Kalender Pendidikan
                        </a>
                    </div>
                </div>
                <div class="nav-item {{ request()->routeIs('admin.kesiswaan.*') ? 'open' : '' }}">
                    <a class="nav-link {{ request()->routeIs('admin.kesiswaan.*') ? 'active' : '' }}" data-toggle="submenu">
                        <i class="bi bi-mortarboard-fill icon"></i> <span>Kesiswaan</span>
                        <span class="count-pill">{{ $totalSiswa }}</span>
                        <i class="bi bi-chevron-right arrow"></i>
                    </a>
                    <div class="submenu">
                        <a href="{{ route('admin.kesiswaan.index') }}" class="sub-link {{ request()->routeIs('admin.kesiswaan.index') ? 'active' : '' }}">
                            <i class="bi bi-person-lines-fill sub-icon"></i> Data Siswa
                        </a>
                        <a href="{{ route('admin.kesiswaan.create') }}" class="sub-link {{ request()->routeIs('admin.kesiswaan.create') ? 'active' : '' }}">
                            <i class="bi bi-person-plus sub-icon"></i> Tambah Siswa
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- ▸ Sarana & Keuangan — Inventaris barang, transaksi & anggaran RKAS --}}
        <div class="nav-group {{ request()->routeIs('admin.inventaris.*') || request()->routeIs('admin.keuangan.*') ? 'open' : '' }}">
            <div class="nav-group-label" data-toggle="nav-group">
                <span>Sarana & Keuangan</span>
                <span class="group-badge">{{ $totalInventaris }}</span>
                <i class="bi bi-chevron-down"></i>
            </div>
            <div class="nav-group-desc">{{ $totalInventaris }} item inventaris, transaksi & RKAS</div>
            <div class="nav-group-items">
                <div class="nav-item {{ request()->routeIs('admin.inventaris.*') ? 'open' : '' }}">
                    <a class="nav-link {{ request()->routeIs('admin.inventaris.*') ? 'active' : '' }}" data-toggle="submenu">
                        <i class="bi bi-box-seam-fill icon"></i> <span>Inventaris / Sarpras</span>
                        <span class="count-pill">{{ $totalInventaris }}</span>
                        <i class="bi bi-chevron-right arrow"></i>
                    </a>
                    <div class="submenu">
                        <a href="{{ route('admin.inventaris.index') }}" class="sub-link {{ request()->routeIs('admin.inventaris.index') ? 'active' : '' }}">
                            <i class="bi bi-list-ul sub-icon"></i> Daftar Inventaris
                        </a>
                        <a href="{{ route('admin.inventaris.create') }}" class="sub-link {{ request()->routeIs('admin.inventaris.create') ? 'active' : '' }}">
                            <i class="bi bi-plus-circle sub-icon"></i> Tambah Barang
                        </a>
                    </div>
                </div>
                <div class="nav-item {{ request()->routeIs('admin.keuangan.*') ? 'open' : '' }}">
                    <a class="nav-link {{ request()->routeIs('admin.keuangan.*') ? 'active' : '' }}" data-toggle="submenu">
                        <i class="bi bi-cash-coin icon"></i> <span>Keuangan</span> <i class="bi bi-chevron-right arrow"></i>
                    </a>
                    <div class="submenu">
                        <a href="{{ route('admin.keuangan.index') }}" class="sub-link {{ request()->routeIs('admin.keuangan.index') ? 'active' : '' }}">
                            <i class="bi bi-receipt sub-icon"></i> Transaksi
                        </a>
                        <a href="{{ route('admin.keuangan.create') }}" class="sub-link {{ request()->routeIs('admin.keuangan.create') ? 'active' : '' }}">
                            <i class="bi bi-plus-circle sub-icon"></i> Tambah Transaksi
                        </a>
                        <a href="{{ route('admin.keuangan.anggaran') }}" class="sub-link {{ request()->routeIs('admin.keuangan.anggaran') ? 'active' : '' }}">
                            <i class="bi bi-piggy-bank sub-icon"></i> RKAS / Anggaran
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- ▸ Evaluasi & Penilaian — PKG, BKD, SKP, akreditasi & asesmen --}}
        <div class="nav-group {{ request()->routeIs('admin.evaluasi.*') || request()->routeIs('admin.akreditasi.*') ? 'open' : '' }}">
            <div class="nav-group-label" data-toggle="nav-group">
                <span>Evaluasi & Penilaian</span>
                <i class="bi bi-chevron-down"></i>
            </div>
            <div class="nav-group-desc">PKG, BKD, SKP, akreditasi & penilaian kinerja</div>
            <div class="nav-group-items">
                <div class="nav-item {{ request()->routeIs('admin.evaluasi.*') ? 'open' : '' }}">
                    <a class="nav-link {{ request()->routeIs('admin.evaluasi.*') ? 'active' : '' }}" data-toggle="submenu">
                        <i class="bi bi-clipboard2-data-fill icon"></i> <span>Evaluasi Kinerja</span> <i class="bi bi-chevron-right arrow"></i>
                    </a>
                    <div class="submenu">
                        <a href="{{ route('admin.evaluasi.pkg') }}" class="sub-link {{ request()->routeIs('admin.evaluasi.pkg*') ? 'active' : '' }}">
                            <i class="bi bi-clipboard-check sub-icon"></i> PKG / BKD / SKP
                        </a>
                        <a href="{{ route('admin.evaluasi.p5') }}" class="sub-link {{ request()->routeIs('admin.evaluasi.p5*') ? 'active' : '' }}">
                            <i class="bi bi-pentagon sub-icon"></i> Asesmen P5
                        </a>
                        <a href="{{ route('admin.evaluasi.star') }}" class="sub-link {{ request()->routeIs('admin.evaluasi.star*') ? 'active' : '' }}">
                            <i class="bi bi-star sub-icon"></i> Metode STAR
                        </a>
                        <a href="{{ route('admin.evaluasi.bukti-fisik') }}" class="sub-link {{ request()->routeIs('admin.evaluasi.bukti-fisik*') ? 'active' : '' }}">
                            <i class="bi bi-camera sub-icon"></i> Bukti Fisik
                        </a>
                        <a href="{{ route('admin.evaluasi.pembelajaran') }}" class="sub-link {{ request()->routeIs('admin.evaluasi.pembelajaran*') ? 'active' : '' }}">
                            <i class="bi bi-lightbulb sub-icon"></i> Model Pembelajaran
                        </a>
                    </div>
                </div>
                <div class="nav-item {{ request()->routeIs('admin.akreditasi.*') ? 'open' : '' }}">
                    <a class="nav-link {{ request()->routeIs('admin.akreditasi.*') ? 'active' : '' }}" data-toggle="submenu">
                        <i class="bi bi-award-fill icon"></i> <span>Akreditasi</span> <i class="bi bi-chevron-right arrow"></i>
                    </a>
                    <div class="submenu">
                        <a href="{{ route('admin.akreditasi.index') }}" class="sub-link {{ request()->routeIs('admin.akreditasi.index') ? 'active' : '' }}">
                            <i class="bi bi-file-earmark-check sub-icon"></i> Dokumen Akreditasi
                        </a>
                        <a href="{{ route('admin.akreditasi.create') }}" class="sub-link {{ request()->routeIs('admin.akreditasi.create') ? 'active' : '' }}">
                            <i class="bi bi-plus-circle sub-icon"></i> Tambah Dokumen
                        </a>
                        <a href="{{ route('admin.akreditasi.eds') }}" class="sub-link {{ request()->routeIs('admin.akreditasi.eds*') ? 'active' : '' }}">
                            <i class="bi bi-clipboard-data sub-icon"></i> Evaluasi Diri (EDS)
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- ▸ Kegiatan & Komunikasi — Agenda, notifikasi, chat & ulang tahun --}}
        <div class="nav-group {{ request()->routeIs('admin.agenda.*') || request()->routeIs('admin.notifikasi.*') || request()->routeIs('admin.chat.*') || request()->routeIs('admin.ulang-tahun.*') ? 'open' : '' }}">
            <div class="nav-group-label" data-toggle="nav-group">
                <span>Kegiatan & Komunikasi</span>
                @if($unreadNotif > 0)<span class="group-badge bg-info">{{ $unreadNotif }}</span>@endif
                <i class="bi bi-chevron-down"></i>
            </div>
            <div class="nav-group-desc">Agenda, pengumuman, chat internal & ucapan</div>
            <div class="nav-group-items">
                <div class="nav-item {{ request()->routeIs('admin.agenda.*') ? 'open' : '' }}">
                    <a class="nav-link {{ request()->routeIs('admin.agenda.*') ? 'active' : '' }}" data-toggle="submenu">
                        <i class="bi bi-calendar-event-fill icon"></i> <span>Agenda & Event</span> <i class="bi bi-chevron-right arrow"></i>
                    </a>
                    <div class="submenu">
                        <a href="{{ route('admin.agenda.index') }}" class="sub-link {{ request()->routeIs('admin.agenda.index') ? 'active' : '' }}">
                            <i class="bi bi-calendar-week sub-icon"></i> Semua Event
                        </a>
                        <a href="{{ route('admin.agenda.create') }}" class="sub-link {{ request()->routeIs('admin.agenda.create') ? 'active' : '' }}">
                            <i class="bi bi-plus-circle sub-icon"></i> Buat Event Baru
                        </a>
                    </div>
                </div>
                <div class="nav-item {{ request()->routeIs('admin.notifikasi.*') ? 'open' : '' }}">
                    <a class="nav-link {{ request()->routeIs('admin.notifikasi.*') ? 'active' : '' }}" data-toggle="submenu">
                        <i class="bi bi-megaphone-fill icon"></i> <span>Notifikasi</span>
                        @if($unreadNotif > 0)<span class="badge bg-info">{{ $unreadNotif }}</span>@endif
                        <i class="bi bi-chevron-right arrow"></i>
                    </a>
                    <div class="submenu">
                        <a href="{{ route('admin.notifikasi.index') }}" class="sub-link {{ request()->routeIs('admin.notifikasi.index') ? 'active' : '' }}">
                            <i class="bi bi-bell sub-icon"></i> Semua Notifikasi
                        </a>
                        <a href="{{ route('admin.notifikasi.create') }}" class="sub-link {{ request()->routeIs('admin.notifikasi.create') ? 'active' : '' }}">
                            <i class="bi bi-send sub-icon"></i> Kirim Pengumuman
                        </a>
                    </div>
                </div>
                <div class="nav-item">
                    <a href="{{ route('admin.chat.index') }}" class="nav-link {{ request()->routeIs('admin.chat.*') ? 'active' : '' }}">
                        <i class="bi bi-chat-left-text-fill icon"></i> <span>Chat</span>
                        <span class="badge bg-primary" id="chat-badge-sidebar" style="display:none;">0</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('admin.ulang-tahun.index') }}" class="nav-link {{ request()->routeIs('admin.ulang-tahun.*') ? 'active' : '' }}">
                        <i class="bi bi-gift-fill icon"></i> <span>Ulang Tahun</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- ▸ Sistem — Pengingat, panduan, tools AI, ekspor & pengaturan --}}
        <div class="nav-group {{ request()->routeIs('admin.pengingat.*') || request()->routeIs('admin.panduan.*') || request()->routeIs('admin.word-ai.*') || request()->routeIs('admin.ekspor.*') || request()->routeIs('admin.pengaturan.*') || request()->routeIs('admin.pengaturan-ai.*') ? 'open' : '' }}">
            <div class="nav-group-label" data-toggle="nav-group">
                <span>Sistem</span>
                @if($overdueReminders > 0)<span class="group-badge bg-warning text-dark">{{ $overdueReminders }}</span>@endif
                <i class="bi bi-chevron-down"></i>
            </div>
            <div class="nav-group-desc">Pengingat, panduan, AI tools, ekspor & pengaturan</div>
            <div class="nav-group-items">
                <div class="nav-item {{ request()->routeIs('admin.pengingat.*') ? 'open' : '' }}">
                    <a class="nav-link {{ request()->routeIs('admin.pengingat.*') ? 'active' : '' }}" data-toggle="submenu">
                        <i class="bi bi-bell-fill icon"></i> <span>Pengingat</span>
                        @if($overdueReminders > 0)<span class="badge bg-warning text-dark">{{ $overdueReminders }}</span>@endif
                        <i class="bi bi-chevron-right arrow"></i>
                    </a>
                    <div class="submenu">
                        <a href="{{ route('admin.pengingat.index') }}" class="sub-link {{ request()->routeIs('admin.pengingat.index') ? 'active' : '' }}">
                            <i class="bi bi-list-task sub-icon"></i> Semua Pengingat
                        </a>
                        <a href="{{ route('admin.pengingat.create') }}" class="sub-link {{ request()->routeIs('admin.pengingat.create') ? 'active' : '' }}">
                            <i class="bi bi-plus-circle sub-icon"></i> Buat Pengingat
                        </a>
                    </div>
                </div>
                <div class="nav-item {{ request()->routeIs('admin.panduan.*') ? 'open' : '' }}">
                    <a class="nav-link {{ request()->routeIs('admin.panduan.*') ? 'active' : '' }}" data-toggle="submenu">
                        <i class="bi bi-book icon"></i> <span>Panduan</span> <i class="bi bi-chevron-right arrow"></i>
                    </a>
                    <div class="submenu">
                        <a href="{{ route('admin.panduan.index') }}" class="sub-link {{ request()->routeIs('admin.panduan.index') ? 'active' : '' }}">
                            <i class="bi bi-journal-bookmark sub-icon"></i> Pusat Panduan
                        </a>
                        <a href="{{ route('admin.panduan.create') }}" class="sub-link {{ request()->routeIs('admin.panduan.create') ? 'active' : '' }}">
                            <i class="bi bi-plus-circle sub-icon"></i> Tambah Panduan
                        </a>
                    </div>
                </div>
                <div class="nav-item {{ request()->routeIs('admin.word-ai.*') ? 'open' : '' }}">
                    <a class="nav-link {{ request()->routeIs('admin.word-ai.*') ? 'active' : '' }}" data-toggle="submenu">
                        <i class="bi bi-file-earmark-word-fill icon"></i> <span>Word & AI</span> <i class="bi bi-chevron-right arrow"></i>
                    </a>
                    <div class="submenu">
                        <a href="{{ route('admin.word-ai.index') }}" class="sub-link {{ request()->routeIs('admin.word-ai.index') ? 'active' : '' }}">
                            <i class="bi bi-files sub-icon"></i> Daftar Dokumen
                        </a>
                        <a href="{{ route('admin.word-ai.create') }}" class="sub-link {{ request()->routeIs('admin.word-ai.create') ? 'active' : '' }}">
                            <i class="bi bi-robot sub-icon"></i> Buat Dokumen AI
                        </a>
                    </div>
                </div>
                <div class="nav-item {{ request()->routeIs('admin.ekspor.*') ? 'open' : '' }}">
                    <a class="nav-link {{ request()->routeIs('admin.ekspor.*') ? 'active' : '' }}" data-toggle="submenu">
                        <i class="bi bi-cloud-download-fill icon"></i> <span>Ekspor & Backup</span> <i class="bi bi-chevron-right arrow"></i>
                    </a>
                    <div class="submenu">
                        <a href="{{ route('admin.ekspor.index') }}" class="sub-link {{ request()->routeIs('admin.ekspor.index') ? 'active' : '' }}">
                            <i class="bi bi-download sub-icon"></i> Ekspor Data
                        </a>
                    </div>
                </div>
                <div class="nav-item {{ request()->routeIs('admin.pengaturan.*') || request()->routeIs('admin.pengaturan-ai.*') ? 'open' : '' }}">
                    <a class="nav-link {{ request()->routeIs('admin.pengaturan.*') || request()->routeIs('admin.pengaturan-ai.*') ? 'active' : '' }}" data-toggle="submenu">
                        <i class="bi bi-gear-fill icon"></i> <span>Pengaturan</span> <i class="bi bi-chevron-right arrow"></i>
                    </a>
                    <div class="submenu">
                        <a href="{{ route('admin.pengaturan.index') }}" class="sub-link {{ request()->routeIs('admin.pengaturan.index') ? 'active' : '' }}">
                            <i class="bi bi-sliders sub-icon"></i> Umum
                        </a>
                        <a href="{{ route('admin.pengaturan-ai.index') }}" class="sub-link {{ request()->routeIs('admin.pengaturan-ai.*') ? 'active' : '' }}">
                            <i class="bi bi-cpu sub-icon"></i> Konfigurasi AI
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </nav>

    {{-- Sidebar Footer --}}
    <div class="sidebar-footer">
        <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form-s').submit();" title="Keluar">
            <i class="bi bi-box-arrow-left"></i> <span>Keluar</span>
        </a>
        <form id="logout-form-s" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
    </div>
</aside>
