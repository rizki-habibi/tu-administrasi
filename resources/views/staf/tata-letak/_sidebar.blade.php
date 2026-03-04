@php
    $user = Auth::user();
    $userRole = $user->peran;
    $unreadCount = \App\Models\Notification::where('pengguna_id', $user->id)->whereNull('read_at')->count();
    $currentRoute = Route::currentRouteName() ?? '';
@endphp

<aside class="sidebar" id="sidebar">
    <style>
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--sidebar-bg);
            position: fixed;
            top: 0; left: 0;
            z-index: 1050;
            display: flex;
            flex-direction: column;
            transition: all var(--transition);
            overflow: hidden;
        }

        /* ── Brand ── */
        .sidebar-brand {
            padding: 22px 20px 18px;
            border-bottom: 1px solid rgba(255,255,255,.08);
        }
        .sidebar-brand-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .sidebar-brand-title .brand-icon {
            width: 36px; height: 36px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            display: flex; align-items: center; justify-content: center;
            font-size: .95rem;
            color: #fff;
            flex-shrink: 0;
        }
        .sidebar-brand-sub {
            font-size: .72rem;
            color: rgba(255,255,255,.5);
            margin-top: 4px;
            padding-left: 46px;
        }

        /* ── Profile ── */
        .sidebar-profile {
            padding: 18px 20px;
            border-bottom: 1px solid rgba(255,255,255,.08);
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .sidebar-avatar {
            width: 42px; height: 42px;
            border-radius: 12px;
            overflow: hidden;
            flex-shrink: 0;
            border: 2px solid var(--primary);
        }
        .sidebar-avatar img {
            width: 100%; height: 100%;
            object-fit: cover;
        }
        .sidebar-avatar-placeholder {
            width: 100%; height: 100%;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            display: flex; align-items: center; justify-content: center;
            font-weight: 700;
            color: #fff;
            font-size: .9rem;
        }
        .sidebar-profile-info { flex: 1; min-width: 0; }
        .sidebar-profile-name {
            font-size: .85rem;
            font-weight: 600;
            color: #fff;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .sidebar-profile-role {
            font-size: .72rem;
            color: var(--primary-light);
            margin-top: 2px;
        }
        .sidebar-profile-status {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: .68rem;
            color: rgba(255,255,255,.45);
            margin-top: 3px;
        }
        .status-dot {
            width: 7px; height: 7px;
            background: var(--primary);
            border-radius: 50%;
            animation: pulse-dot 2s infinite;
        }
        @keyframes pulse-dot {
            0%, 100% { opacity: 1; }
            50% { opacity: .4; }
        }

        /* ── Nav ── */
        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            padding: 14px 0;
        }
        .sidebar-nav::-webkit-scrollbar { width: 3px; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,.15); border-radius: 2px; }

        .nav-section {
            margin-bottom: 6px;
        }
        .nav-section-title {
            padding: 8px 22px 6px;
            font-size: .65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .1em;
            color: rgba(255,255,255,.35);
        }

        .nav-item {
            position: relative;
        }
        .nav-link {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 9px 22px;
            color: rgba(255,255,255,.65);
            text-decoration: none;
            font-size: .84rem;
            font-weight: 500;
            transition: all var(--transition);
            border-left: 3px solid transparent;
        }
        .nav-link:hover {
            color: #fff;
            background: rgba(255,255,255,.06);
        }
        .nav-link.active {
            color: #fff;
            background: rgba(16,185,129,.12);
            border-left-color: var(--primary);
        }
        .nav-link .nav-icon {
            width: 20px;
            text-align: center;
            font-size: .85rem;
            flex-shrink: 0;
        }
        .nav-link .nav-badge {
            margin-left: auto;
            background: var(--primary);
            color: #fff;
            font-size: .65rem;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 8px;
            min-width: 20px;
            text-align: center;
        }
        .nav-link .nav-arrow {
            margin-left: auto;
            font-size: .65rem;
            transition: transform var(--transition);
            color: rgba(255,255,255,.3);
        }
        .nav-item.open > .nav-link .nav-arrow {
            transform: rotate(90deg);
        }

        /* Submenu */
        .nav-submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height .3s ease;
            background: rgba(0,0,0,.12);
        }
        .nav-item.open > .nav-submenu {
            max-height: 500px;
        }
        .nav-submenu .nav-link {
            padding: 7px 22px 7px 53px;
            font-size: .8rem;
            border-left: none;
        }


        /* ── Sidebar Footer ── */
        .sidebar-footer {
            padding: 14px 20px;
            border-top: 1px solid rgba(255,255,255,.08);
        }
        .sidebar-footer .logout-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            color: rgba(255,255,255,.6);
            text-decoration: none;
            font-size: .84rem;
            font-weight: 500;
            border-radius: 8px;
            transition: all var(--transition);
        }
        .sidebar-footer .logout-link:hover {
            color: #fca5a5;
            background: rgba(239,68,68,.12);
        }
    </style>

    {{-- Brand --}}
    <div class="sidebar-brand">
        <div class="sidebar-brand-title">
            <span class="brand-icon"><i class="fas fa-school"></i></span>
            TU Administrasi
        </div>
        <div class="sidebar-brand-sub">SMA Negeri 2 Jember</div>
    </div>

    {{-- Profile --}}
    <div class="sidebar-profile">
        <div class="sidebar-avatar">
            @if($user->foto)
                <img src="{{ asset('storage/' . $user->foto) }}" alt="{{ $user->nama }}">
            @else
                <div class="sidebar-avatar-placeholder">{{ strtoupper(substr($user->nama, 0, 1)) }}</div>
            @endif
        </div>
        <div class="sidebar-profile-info">
            <div class="sidebar-profile-name">{{ $user->nama }}</div>
            <div class="sidebar-profile-role">{{ $user->role_label }}</div>
            <div class="sidebar-profile-status">
                <span class="status-dot"></span> Online
            </div>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="sidebar-nav">

        {{-- ═══════════════════════════════════════════
             MENU UTAMA — Semua Staff
        ═══════════════════════════════════════════ --}}
        <div class="nav-section">
            <div class="nav-section-title">Menu Utama</div>
            <div class="nav-item">
                <a href="{{ route('staf.beranda') }}" class="nav-link {{ $currentRoute == 'staf.beranda' ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fas fa-home"></i></span> Beranda
                </a>
            </div>
        </div>

        {{-- ── Kehadiran ── --}}
        <div class="nav-section">
            <div class="nav-section-title">Kehadiran</div>
            <div class="nav-item {{ Str::startsWith($currentRoute, 'staf.kehadiran') ? 'open' : '' }}">
                <a href="#" class="nav-link submenu-toggle">
                    <span class="nav-icon"><i class="fas fa-fingerprint"></i></span> Absensi
                    <span class="nav-arrow"><i class="fas fa-chevron-right"></i></span>
                </a>
                <div class="nav-submenu">
                    <a href="{{ route('staf.kehadiran.index') }}" class="nav-link {{ $currentRoute == 'staf.kehadiran.index' && !request('view') ? 'active' : '' }}">
                        Absen Hari Ini
                    </a>
                    <a href="{{ route('staf.kehadiran.index', ['view' => 'history']) }}" class="nav-link {{ $currentRoute == 'staf.kehadiran.index' && request('view') == 'history' ? 'active' : '' }}">
                        Riwayat Kehadiran
                    </a>
                </div>
            </div>
            <div class="nav-item {{ Str::startsWith($currentRoute, 'staf.izin') ? 'open' : '' }}">
                <a href="#" class="nav-link submenu-toggle">
                    <span class="nav-icon"><i class="fas fa-calendar-check"></i></span> Pengajuan Izin
                    <span class="nav-arrow"><i class="fas fa-chevron-right"></i></span>
                </a>
                <div class="nav-submenu">
                    <a href="{{ route('staf.izin.index') }}" class="nav-link {{ $currentRoute == 'staf.izin.index' ? 'active' : '' }}">
                        Daftar Izin
                    </a>
                    <a href="{{ route('staf.izin.create') }}" class="nav-link {{ $currentRoute == 'staf.izin.create' ? 'active' : '' }}">
                        Ajukan Izin
                    </a>
                </div>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════
             ROLE-SPECIFIC MENUS
        ═══════════════════════════════════════════ --}}

        {{-- Kepegawaian --}}
        @if($userRole === 'kepegawaian')
        <div class="nav-section">
            <div class="nav-section-title">Kepegawaian</div>
            <div class="nav-item {{ Str::startsWith($currentRoute, 'staf.skp') ? 'open' : '' }}">
                <a href="#" class="nav-link submenu-toggle">
                    <span class="nav-icon"><i class="fas fa-user-tie"></i></span> SKP
                    <span class="nav-arrow"><i class="fas fa-chevron-right"></i></span>
                </a>
                <div class="nav-submenu">
                    <a href="{{ route('staf.skp.index') }}" class="nav-link {{ $currentRoute == 'staf.skp.index' ? 'active' : '' }}">
                        Daftar SKP
                    </a>
                    <a href="{{ route('staf.skp.create') }}" class="nav-link {{ $currentRoute == 'staf.skp.create' ? 'active' : '' }}">
                        Buat SKP
                    </a>
                </div>
            </div>
        </div>
        @endif

        {{-- Keuangan --}}
        @if($userRole === 'keuangan')
        <div class="nav-section">
            <div class="nav-section-title">Keuangan</div>
            <div class="nav-item {{ Str::startsWith($currentRoute, 'staf.laporan') ? 'open' : '' }}">
                <a href="#" class="nav-link submenu-toggle">
                    <span class="nav-icon"><i class="fas fa-money-bill-wave"></i></span> Keuangan
                    <span class="nav-arrow"><i class="fas fa-chevron-right"></i></span>
                </a>
                <div class="nav-submenu">
                    <a href="{{ route('staf.laporan.index', ['kategori' => 'keuangan']) }}" class="nav-link {{ $currentRoute == 'staf.laporan.index' && request('kategori') == 'keuangan' ? 'active' : '' }}">
                        Laporan Keuangan
                    </a>
                    <a href="{{ route('staf.dokumen.index', ['kategori' => 'keuangan']) }}" class="nav-link {{ $currentRoute == 'staf.dokumen.index' && request('kategori') == 'keuangan' ? 'active' : '' }}">
                        Dokumen Keuangan
                    </a>
                </div>
            </div>
        </div>
        @endif

        {{-- Persuratan --}}
        @if($userRole === 'persuratan')
        <div class="nav-section">
            <div class="nav-section-title">Persuratan</div>
            <div class="nav-item {{ Str::startsWith($currentRoute, 'staf.surat') ? 'open' : '' }}">
                <a href="#" class="nav-link submenu-toggle">
                    <span class="nav-icon"><i class="fas fa-envelope-open-text"></i></span> Surat Menyurat
                    <span class="nav-arrow"><i class="fas fa-chevron-right"></i></span>
                </a>
                <div class="nav-submenu">
                    <a href="{{ route('staf.surat.index') }}" class="nav-link {{ $currentRoute == 'staf.surat.index' ? 'active' : '' }}">
                        Semua Surat
                    </a>
                    <a href="{{ route('staf.surat.index', ['jenis' => 'masuk']) }}" class="nav-link {{ $currentRoute == 'staf.surat.index' && request('jenis') == 'masuk' ? 'active' : '' }}">
                        Surat Masuk
                    </a>
                    <a href="{{ route('staf.surat.index', ['jenis' => 'keluar']) }}" class="nav-link {{ $currentRoute == 'staf.surat.index' && request('jenis') == 'keluar' ? 'active' : '' }}">
                        Surat Keluar
                    </a>
                    <a href="{{ route('staf.surat.create') }}" class="nav-link {{ $currentRoute == 'staf.surat.create' ? 'active' : '' }}">
                        Buat Surat
                    </a>
                </div>
            </div>
        </div>
        @endif

        {{-- Perpustakaan --}}
        @if($userRole === 'perpustakaan')
        <div class="nav-section">
            <div class="nav-section-title">Perpustakaan</div>
            <div class="nav-item {{ Str::startsWith($currentRoute, 'staf.dokumen') || Str::startsWith($currentRoute, 'staf.laporan') ? 'open' : '' }}">
                <a href="#" class="nav-link submenu-toggle">
                    <span class="nav-icon"><i class="fas fa-book"></i></span> Perpustakaan
                    <span class="nav-arrow"><i class="fas fa-chevron-right"></i></span>
                </a>
                <div class="nav-submenu">
                    <a href="{{ route('staf.dokumen.index') }}" class="nav-link {{ $currentRoute == 'staf.dokumen.index' ? 'active' : '' }}">
                        Koleksi Dokumen
                    </a>
                    <a href="{{ route('staf.laporan.index') }}" class="nav-link {{ $currentRoute == 'staf.laporan.index' ? 'active' : '' }}">
                        Laporan
                    </a>
                </div>
            </div>
        </div>
        @endif

        {{-- Inventaris --}}
        @if($userRole === 'inventaris')
        <div class="nav-section">
            <div class="nav-section-title">Inventaris</div>
            <div class="nav-item {{ Str::startsWith($currentRoute, 'staf.inventaris') ? 'open' : '' }}">
                <a href="#" class="nav-link submenu-toggle">
                    <span class="nav-icon"><i class="fas fa-boxes-stacked"></i></span> Inventaris
                    <span class="nav-arrow"><i class="fas fa-chevron-right"></i></span>
                </a>
                <div class="nav-submenu">
                    <a href="{{ route('staf.inventaris.index') }}" class="nav-link {{ $currentRoute == 'staf.inventaris.index' ? 'active' : '' }}">
                        Daftar Inventaris
                    </a>
                    <a href="{{ route('staf.inventaris.index', ['status' => 'rusak']) }}" class="nav-link {{ $currentRoute == 'staf.inventaris.index' && request('status') == 'rusak' ? 'active' : '' }}">
                        Laporan Kerusakan
                    </a>
                </div>
            </div>
        </div>
        @endif

        {{-- Kesiswaan & Kurikulum --}}
        @if($userRole === 'kesiswaan_kurikulum')
        <div class="nav-section">
            <div class="nav-section-title">Kesiswaan &amp; Kurikulum</div>
            <div class="nav-item {{ Str::startsWith($currentRoute, 'staf.kesiswaan') ? 'open' : '' }}">
                <a href="#" class="nav-link submenu-toggle">
                    <span class="nav-icon"><i class="fas fa-users"></i></span> Kesiswaan
                    <span class="nav-arrow"><i class="fas fa-chevron-right"></i></span>
                </a>
                <div class="nav-submenu">
                    <a href="{{ route('staf.kesiswaan.index') }}" class="nav-link {{ $currentRoute == 'staf.kesiswaan.index' ? 'active' : '' }}">
                        Data Siswa
                    </a>
                </div>
            </div>
            <div class="nav-item {{ Str::startsWith($currentRoute, 'staf.kurikulum') ? 'open' : '' }}">
                <a href="#" class="nav-link submenu-toggle">
                    <span class="nav-icon"><i class="fas fa-graduation-cap"></i></span> Kurikulum
                    <span class="nav-arrow"><i class="fas fa-chevron-right"></i></span>
                </a>
                <div class="nav-submenu">
                    <a href="{{ route('staf.kurikulum.index') }}" class="nav-link {{ $currentRoute == 'staf.kurikulum.index' ? 'active' : '' }}">
                        Dokumen Kurikulum
                    </a>
                </div>
            </div>
        </div>
        @endif

        {{-- Pramu Bakti --}}
        @if($userRole === 'pramu_bakti')
        <div class="nav-section">
            <div class="nav-section-title">Pramu Bakti</div>
            <div class="nav-item">
                <a href="{{ route('staf.laporan.index') }}" class="nav-link {{ $currentRoute == 'staf.laporan.index' ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fas fa-broom"></i></span> Laporan Kerja
                </a>
            </div>
        </div>
        @endif

        {{-- ═══════════════════════════════════════════
             COMMON BOTTOM MENUS — Semua Staff
        ═══════════════════════════════════════════ --}}

        {{-- Administrasi (not for persuratan) --}}
        @if($userRole !== 'persuratan')
        <div class="nav-section">
            <div class="nav-section-title">Administrasi</div>
            <div class="nav-item {{ Str::startsWith($currentRoute, 'staf.surat') && $userRole !== 'persuratan' ? 'open' : '' }}">
                <a href="#" class="nav-link submenu-toggle">
                    <span class="nav-icon"><i class="fas fa-envelope"></i></span> Surat
                    <span class="nav-arrow"><i class="fas fa-chevron-right"></i></span>
                </a>
                <div class="nav-submenu">
                    <a href="{{ route('staf.surat.index') }}" class="nav-link {{ $currentRoute == 'staf.surat.index' ? 'active' : '' }}">
                        Semua Surat
                    </a>
                    <a href="{{ route('staf.surat.create') }}" class="nav-link {{ $currentRoute == 'staf.surat.create' ? 'active' : '' }}">
                        Buat Surat
                    </a>
                </div>
            </div>

            {{-- Laporan (not for pramu_bakti) --}}
            @if($userRole !== 'pramu_bakti')
            <div class="nav-item {{ Str::startsWith($currentRoute, 'staf.laporan') ? 'open' : '' }}">
                <a href="#" class="nav-link submenu-toggle">
                    <span class="nav-icon"><i class="fas fa-chart-bar"></i></span> Laporan
                    <span class="nav-arrow"><i class="fas fa-chevron-right"></i></span>
                </a>
                <div class="nav-submenu">
                    <a href="{{ route('staf.laporan.index') }}" class="nav-link {{ $currentRoute == 'staf.laporan.index' ? 'active' : '' }}">
                        Semua Laporan
                    </a>
                    <a href="{{ route('staf.laporan.create') }}" class="nav-link {{ $currentRoute == 'staf.laporan.create' ? 'active' : '' }}">
                        Buat Laporan
                    </a>
                </div>
            </div>
            @endif

            {{-- Dokumen & Arsip (not for perpustakaan) --}}
            @if($userRole !== 'perpustakaan')
            <div class="nav-item {{ Str::startsWith($currentRoute, 'staf.dokumen') ? 'open' : '' }}">
                <a href="#" class="nav-link submenu-toggle">
                    <span class="nav-icon"><i class="fas fa-folder-open"></i></span> Dokumen & Arsip
                    <span class="nav-arrow"><i class="fas fa-chevron-right"></i></span>
                </a>
                <div class="nav-submenu">
                    <a href="{{ route('staf.dokumen.index') }}" class="nav-link {{ $currentRoute == 'staf.dokumen.index' ? 'active' : '' }}">
                        Daftar Dokumen
                    </a>
                    <a href="{{ route('staf.dokumen.index') }}" class="nav-link {{ $currentRoute == 'staf.dokumen.index' ? 'active' : '' }}">
                        Daftar Dokumen
                    </a>
                </div>
            </div>
            @endif
        </div>
        @endif

        {{-- ── Kinerja ── --}}
        <div class="nav-section">
            <div class="nav-section-title">Kinerja</div>

            {{-- SKP (for non-kepegawaian) --}}
            @if($userRole !== 'kepegawaian')
            <div class="nav-item {{ Str::startsWith($currentRoute, 'staf.skp') ? 'open' : '' }}">
                <a href="#" class="nav-link submenu-toggle">
                    <span class="nav-icon"><i class="fas fa-bullseye"></i></span> SKP
                    <span class="nav-arrow"><i class="fas fa-chevron-right"></i></span>
                </a>
                <div class="nav-submenu">
                    <a href="{{ route('staf.skp.index') }}" class="nav-link {{ $currentRoute == 'staf.skp.index' ? 'active' : '' }}">
                        Daftar SKP
                    </a>
                    <a href="{{ route('staf.skp.create') }}" class="nav-link {{ $currentRoute == 'staf.skp.create' ? 'active' : '' }}">
                        Buat SKP
                    </a>
                </div>
            </div>
            @endif

            <div class="nav-item {{ Str::startsWith($currentRoute, 'staf.evaluasi') ? 'open' : '' }}">
                <a href="#" class="nav-link submenu-toggle">
                    <span class="nav-icon"><i class="fas fa-clipboard-check"></i></span> Evaluasi
                    <span class="nav-arrow"><i class="fas fa-chevron-right"></i></span>
                </a>
                <div class="nav-submenu">
                    <a href="{{ route('staf.evaluasi.pkg') }}" class="nav-link {{ $currentRoute == 'staf.evaluasi.pkg' ? 'active' : '' }}">
                        PKG / BKD
                    </a>
                    <a href="{{ route('staf.evaluasi.p5') }}" class="nav-link {{ $currentRoute == 'staf.evaluasi.p5' ? 'active' : '' }}">
                        Asesmen P5
                    </a>
                    <a href="{{ route('staf.evaluasi.star') }}" class="nav-link {{ $currentRoute == 'staf.evaluasi.star' ? 'active' : '' }}">
                        Analisis STAR
                    </a>
                    <a href="{{ route('staf.evaluasi.bukti-fisik') }}" class="nav-link {{ $currentRoute == 'staf.evaluasi.bukti-fisik' ? 'active' : '' }}">
                        Bukti Fisik
                    </a>
                    <a href="{{ route('staf.evaluasi.pembelajaran') }}" class="nav-link {{ $currentRoute == 'staf.evaluasi.pembelajaran' ? 'active' : '' }}">
                        Metode Pembelajaran
                    </a>
                </div>
            </div>
        </div>

        {{-- ── Lainnya ── --}}
        <div class="nav-section">
            <div class="nav-section-title">Lainnya</div>

            <div class="nav-item {{ Str::startsWith($currentRoute, 'staf.word-ai') ? 'open' : '' }}">
                <a href="#" class="nav-link submenu-toggle">
                    <span class="nav-icon"><i class="fas fa-file-word"></i></span> Word & AI
                    <span class="nav-arrow"><i class="fas fa-chevron-right"></i></span>
                </a>
                <div class="nav-submenu">
                    <a href="{{ route('staf.word-ai.index') }}" class="nav-link {{ $currentRoute == 'staf.word-ai.index' ? 'active' : '' }}">
                        Daftar Dokumen
                    </a>
                    <a href="{{ route('staf.word-ai.create') }}" class="nav-link {{ $currentRoute == 'staf.word-ai.create' ? 'active' : '' }}">
                        Buat Dokumen
                    </a>
                </div>
            </div>

            <div class="nav-item">
                <a href="{{ route('staf.agenda.index') }}" class="nav-link {{ Str::startsWith($currentRoute, 'staf.agenda') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fas fa-calendar-alt"></i></span> Agenda
                </a>
            </div>

            <div class="nav-item">
                <a href="{{ route('staf.notifikasi.index') }}" class="nav-link {{ Str::startsWith($currentRoute, 'staf.notifikasi') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fas fa-bell"></i></span> Notifikasi
                    @if($unreadCount > 0)
                        <span class="nav-badge">{{ $unreadCount > 99 ? '99+' : $unreadCount }}</span>
                    @endif
                </a>
            </div>

            <div class="nav-item">
                <a href="{{ route('staf.panduan.index') }}" class="nav-link {{ $currentRoute == 'staf.panduan.index' ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fas fa-book-open"></i></span> Panduan
                </a>
            </div>

            <div class="nav-item">
                <a href="{{ route('staf.pengingat.index') }}" class="nav-link {{ Str::startsWith($currentRoute, 'staf.pengingat') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fas fa-bell"></i></span> Pengingat
                </a>
            </div>

            <div class="nav-item">
                <a href="{{ route('staf.ulang-tahun.index') }}" class="nav-link {{ Str::startsWith($currentRoute, 'staf.ulang-tahun') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fas fa-birthday-cake"></i></span> Ulang Tahun
                </a>
            </div>

            <div class="nav-item {{ Str::startsWith($currentRoute, 'staf.profil') ? 'open' : '' }}">
                <a href="#" class="nav-link submenu-toggle">
                    <span class="nav-icon"><i class="fas fa-user-cog"></i></span> Akun Saya
                    <span class="nav-arrow"><i class="fas fa-chevron-right"></i></span>
                </a>
                <div class="nav-submenu">
                    <a href="{{ route('staf.profil.edit') }}" class="nav-link {{ $currentRoute == 'staf.profil.edit' ? 'active' : '' }}">
                        Edit Profil
                    </a>
                    <a href="{{ route('staf.profil.edit') }}#ubah-password" class="nav-link">
                        Ubah Password
                    </a>
                </div>
            </div>
        </div>
    </nav>

    {{-- Sidebar Footer --}}
    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}" id="logoutFormSidebar">
            @csrf
            <a href="#" class="logout-link" onclick="event.preventDefault();
                Swal.fire({
                    title: 'Logout?',
                    text: 'Anda yakin ingin keluar dari sistem?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#10b981',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Ya, Keluar',
                    cancelButtonText: 'Batal'
                }).then((r) => { if(r.isConfirmed) document.getElementById('logoutFormSidebar').submit(); });">
                <i class="fas fa-sign-out-alt"></i> Keluar
            </a>
        </form>
    </div>
</aside>
