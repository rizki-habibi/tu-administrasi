<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - TU Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        :root {
            --sidebar-w: 268px;
            --header-h: 62px;
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --primary-light: #818cf8;
            --secondary: #8b5cf6;
            --sidebar-bg: linear-gradient(180deg, #1e1b4b 0%, #312e81 100%);
            --body-bg: #f0f2f8;
            --card-radius: 14px;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { overflow-x: hidden; }
        body { font-family: 'Poppins', sans-serif; background: var(--body-bg); overflow-x: hidden; }

        /* ── Sidebar ── */
        .sidebar {
            position: fixed; top: 0; left: 0; width: var(--sidebar-w); height: 100vh;
            background: var(--sidebar-bg); color: #c7d2fe; z-index: 1040;
            transition: transform .3s cubic-bezier(.4,0,.2,1);
            display: flex; flex-direction: column; overflow: hidden;
        }
        .sidebar-brand { padding: 20px 20px 12px; text-align: center; border-bottom: 1px solid rgba(255,255,255,.08); }
        .sidebar-brand img { width: 52px; height: 52px; border-radius: 12px; object-fit: cover; border: 2px solid rgba(255,255,255,.15); }
        .sidebar-brand h6 { color: #fff; font-size: .85rem; font-weight: 600; margin: 8px 0 2px; }
        .sidebar-brand small { font-size: .68rem; color: #a5b4fc; }

        .sidebar-profile { padding: 16px 20px; border-bottom: 1px solid rgba(255,255,255,.08); display: flex; align-items: center; gap: 10px; }
        .sidebar-profile .avatar {
            width: 40px; height: 40px; border-radius: 10px; background: linear-gradient(135deg, var(--primary), var(--secondary));
            display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 600; font-size: .85rem; flex-shrink: 0;
        }
        .sidebar-profile .info { overflow: hidden; }
        .sidebar-profile .info .name { color: #fff; font-size: .8rem; font-weight: 600; white-space: nowrap; text-overflow: ellipsis; overflow: hidden; }
        .sidebar-profile .info .role { font-size: .68rem; color: #a5b4fc; }
        .sidebar-profile .status { width: 8px; height: 8px; border-radius: 50%; background: #34d399; margin-left: auto; flex-shrink: 0; }

        .sidebar-nav { flex: 1; overflow-y: auto; padding: 12px 0; }
        .sidebar-nav::-webkit-scrollbar { width: 4px; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,.15); border-radius: 4px; }
        .nav-label { font-size: .65rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1.2px; color: #6366f1; padding: 12px 20px 6px; }
        .nav-item { position: relative; }
        .nav-link {
            display: flex; align-items: center; padding: 9px 20px; color: #c7d2fe; text-decoration: none;
            font-size: .82rem; font-weight: 400; transition: all .2s; gap: 12px; cursor: pointer; border-left: 3px solid transparent;
        }
        .nav-link:hover { background: rgba(99,102,241,.12); color: #e0e7ff; }
        .nav-link.active, .nav-link.active:hover { background: rgba(99,102,241,.18); color: #fff; border-left-color: var(--primary-light); font-weight: 500; }
        .nav-link i.icon { font-size: 1.1rem; width: 22px; text-align: center; }
        .nav-link .arrow { margin-left: auto; font-size: .7rem; transition: transform .25s; }
        .nav-item.open > .nav-link .arrow { transform: rotate(90deg); }

        .submenu { max-height: 0; overflow: hidden; transition: max-height .35s cubic-bezier(.4,0,.2,1); background: rgba(0,0,0,.12); }
        .nav-item.open > .submenu { max-height: 400px; }
        .submenu .sub-link {
            display: flex; align-items: center; padding: 7px 20px 7px 54px; color: #a5b4fc; font-size: .78rem;
            text-decoration: none; transition: all .2s; gap: 8px;
        }
        .submenu .sub-link::before { content: ''; width: 16px; height: 2px; border-radius: 2px; background: #6366f1; flex-shrink: 0; }
        .submenu .sub-link:hover { color: #fff; background: rgba(99,102,241,.1); }
        .submenu .sub-link.active { color: #fff; font-weight: 500; }
        .submenu .sub-link.active::before { background: var(--primary-light); box-shadow: 0 0 6px var(--primary-light); }

        .sidebar-footer { padding: 14px 20px; border-top: 1px solid rgba(255,255,255,.08); }
        .sidebar-footer a { color: #ef4444; font-size: .8rem; text-decoration: none; display: flex; align-items: center; gap: 8px; }
        .sidebar-footer a:hover { color: #fca5a5; }

        /* ── Main Content ── */
        .main-content { margin-left: var(--sidebar-w); transition: margin .3s cubic-bezier(.4,0,.2,1); min-height: 100vh; overflow-x: hidden; max-width: 100vw; }

        /* ── Top Header ── */
        .top-header {
            position: sticky; top: 0; z-index: 1030; height: var(--header-h);
            background: #fff; border-bottom: 1px solid #e2e8f0;
            display: flex; align-items: center; padding: 0 24px; gap: 16px;
            box-shadow: 0 1px 3px rgba(0,0,0,.04);
        }
        .sidebar-toggle { background: none; border: none; font-size: 1.3rem; color: #475569; cursor: pointer; padding: 6px; border-radius: 8px; transition: .2s; }
        .sidebar-toggle:hover { background: #f1f5f9; color: var(--primary); }
        .header-title { font-size: .9rem; font-weight: 600; color: #1e293b; }
        .header-right { margin-left: auto; display: flex; align-items: center; gap: 12px; }
        .header-date { font-size: .78rem; color: #64748b; }
        .notif-btn { position: relative; background: #f1f5f9; border: none; width: 38px; height: 38px; border-radius: 10px; font-size: 1.1rem; color: #475569; cursor: pointer; transition: .2s; }
        .notif-btn:hover { background: #e2e8f0; color: var(--primary); }
        .notif-badge { position: absolute; top: 4px; right: 4px; width: 8px; height: 8px; background: #ef4444; border-radius: 50%; }
        .header-profile { display: flex; align-items: center; gap: 8px; cursor: pointer; padding: 4px 10px; border-radius: 10px; transition: .2s; border: none; background: none; }
        .header-profile:hover { background: #f1f5f9; }
        .header-profile .avatar-sm { width: 34px; height: 34px; border-radius: 8px; background: linear-gradient(135deg, var(--primary), var(--secondary)); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: .75rem; }
        .header-profile .name { font-size: .8rem; font-weight: 500; color: #1e293b; }

        /* ── Page Content ── */
        .page-content { padding: 24px; overflow-x: hidden; }

        /* ── Header Dropdown ── */
        .dropdown-menu { min-width: 200px; padding: 6px; border-radius: 12px !important; border: 1px solid #e2e8f0; box-shadow: 0 8px 24px rgba(0,0,0,.1) !important; }
        .dropdown-item { border-radius: 8px; font-size: .82rem; padding: 9px 14px; color: #374151; font-weight: 500; transition: all .15s; }
        .dropdown-item:hover { background: #f0f2f8; color: var(--primary); }
        .dropdown-item.text-danger:hover { background: #fef2f2; color: #ef4444; }
        .dropdown-divider { margin: 4px 0; border-color: #f1f5f9; }
        .pagination { gap: 4px; flex-wrap: wrap; justify-content: center; }
        .page-link { border-radius: 8px !important; border: 1px solid #e2e8f0; color: #4338ca; font-size: .82rem; font-weight: 500; padding: 8px 14px; transition: all .2s; }
        .page-link:hover { background: linear-gradient(135deg, #6366f1, #818cf8); color: #fff; border-color: transparent; box-shadow: 0 2px 8px rgba(99,102,241,.3); }
        .page-item.active .page-link { background: linear-gradient(135deg, #4338ca, #6366f1); border-color: transparent; color: #fff; box-shadow: 0 2px 8px rgba(99,102,241,.3); }
        .page-item.disabled .page-link { background: #f8fafc; color: #94a3b8; border-color: #e2e8f0; }

        /* ── Cards ── */
        .card { border: none; border-radius: var(--card-radius); box-shadow: 0 1px 3px rgba(0,0,0,.06); transition: box-shadow .2s; }
        .card:hover { box-shadow: 0 4px 12px rgba(0,0,0,.08); }

        /* Stat Cards */
        .stat-card { border-radius: var(--card-radius); padding: 20px; color: #fff; position: relative; overflow: hidden; }
        .stat-card::after { content: ''; position: absolute; top: -20px; right: -20px; width: 80px; height: 80px; border-radius: 50%; background: rgba(255,255,255,.1); }
        .stat-card .icon-box { width: 48px; height: 48px; border-radius: 12px; background: rgba(255,255,255,.2); display: flex; align-items: center; justify-content: center; font-size: 1.3rem; }
        .stat-card h3 { font-size: 1.6rem; font-weight: 700; margin: 8px 0 2px; }
        .stat-card p { font-size: .78rem; opacity: .85; margin: 0; }

        /* ── Tables ── */
        .table { font-size: .82rem; }
        .table thead th { font-weight: 600; color: #475569; text-transform: uppercase; font-size: .72rem; letter-spacing: .5px; border-bottom-width: 1px; }
        .badge { font-weight: 500; font-size: .72rem; padding: 4px 10px; border-radius: 6px; }

        /* ── Buttons ── */
        .btn { font-size: .82rem; font-weight: 500; border-radius: 8px; padding: 8px 16px; transition: all .2s; }
        .btn-primary { background: linear-gradient(135deg, var(--primary), var(--secondary)); border: none; }
        .btn-primary:hover { background: linear-gradient(135deg, var(--primary-dark), #7c3aed); transform: translateY(-1px); box-shadow: 0 4px 12px rgba(99,102,241,.3); }

        /* ── Responsive ── */
        body.sidebar-collapsed .sidebar { transform: translateX(-100%); }
        body.sidebar-collapsed .main-content { margin-left: 0; }
        @media (max-width: 991px) {
            .sidebar { transform: translateX(-100%); }
            .main-content { margin-left: 0; }
            body.sidebar-open .sidebar { transform: translateX(0); }
            body.sidebar-open::after { content: ''; position: fixed; inset: 0; background: rgba(0,0,0,.4); z-index: 1039; }
        }

        /* ── Modal ── */
        .modal-content { border: none; border-radius: var(--card-radius); }
        .modal-header { border-bottom: 1px solid #f1f5f9; padding: 16px 20px; }
        .modal-header .modal-title { font-size: .95rem; font-weight: 600; }
        .modal-footer { border-top: 1px solid #f1f5f9; padding: 12px 20px; }

        /* ── Forms ── */
        .form-control, .form-select { font-size: .82rem; border-radius: 8px; border-color: #e2e8f0; padding: 9px 14px; }
        .form-control:focus, .form-select:focus { border-color: var(--primary-light); box-shadow: 0 0 0 3px rgba(99,102,241,.12); }
        .form-label { font-size: .8rem; font-weight: 500; color: #475569; }

        /* ── Scrollbar ── */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 6px; }

        /* ── Animations ── */
        .fade-in { animation: fadeIn .4s ease; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }

        /* ── Print ── */
        @media print {
            .sidebar, .top-header { display: none !important; }
            .main-content { margin-left: 0 !important; }
            .page-content { padding: 0 !important; }
        }

        @stack('styles')
    </style>
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <img src="{{ asset('storage/gambar/logo.png') }}" alt="Logo" onerror="this.style.display='none'">
            <h6>TU Administrasi</h6>
            <small>SMA Negeri 2 Jember</small>
        </div>

        <div class="sidebar-profile">
            <div class="avatar">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
            <div class="info">
                <div class="name">{{ Auth::user()->name }}</div>
                <div class="role"><i class="bi bi-shield-check"></i> Administrator</div>
            </div>
            <div class="status" title="Online"></div>
        </div>

        @php $pendingLeave = \App\Models\LeaveRequest::where('status','pending')->count(); @endphp
        <nav class="sidebar-nav">
            <div class="nav-label">Menu Utama</div>
            <div class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2-fill icon"></i> <span>Dashboard</span>
                </a>
            </div>

            <div class="nav-label">Manajemen Pegawai</div>
            <div class="nav-item {{ request()->routeIs('admin.staff.*') ? 'open' : '' }}">
                <a class="nav-link {{ request()->routeIs('admin.staff.*') ? 'active' : '' }}" data-toggle="submenu">
                    <i class="bi bi-people-fill icon"></i> <span>Data Staff</span> <i class="bi bi-chevron-right arrow"></i>
                </a>
                <div class="submenu">
                    <a href="{{ route('admin.staff.index') }}" class="sub-link {{ request()->routeIs('admin.staff.index') ? 'active' : '' }}">Semua Staff</a>
                    <a href="{{ route('admin.staff.create') }}" class="sub-link {{ request()->routeIs('admin.staff.create') ? 'active' : '' }}">Tambah Staff Baru</a>
                    <a href="{{ route('admin.staff.export', ['format'=>'pdf']) }}" class="sub-link" target="_blank">Cetak Data Staff</a>
                </div>
            </div>
            <div class="nav-item {{ request()->routeIs('admin.attendance.*') ? 'open' : '' }}">
                <a class="nav-link {{ request()->routeIs('admin.attendance.*') ? 'active' : '' }}" data-toggle="submenu">
                    <i class="bi bi-fingerprint icon"></i> <span>Kehadiran</span> <i class="bi bi-chevron-right arrow"></i>
                </a>
                <div class="submenu">
                    <a href="{{ route('admin.attendance.index') }}" class="sub-link {{ request()->routeIs('admin.attendance.index') ? 'active' : '' }}">Absensi Hari Ini</a>
                    <a href="{{ route('admin.attendance.report') }}" class="sub-link {{ request()->routeIs('admin.attendance.report') ? 'active' : '' }}">Rekap Kehadiran</a>
                    <a href="{{ route('admin.attendance.settings') }}" class="sub-link {{ request()->routeIs('admin.attendance.settings') ? 'active' : '' }}">Pengaturan Absensi</a>
                </div>
            </div>
            <div class="nav-item {{ request()->routeIs('admin.leave.*') ? 'open' : '' }}">
                <a class="nav-link {{ request()->routeIs('admin.leave.*') ? 'active' : '' }}" data-toggle="submenu">
                    <i class="bi bi-calendar2-check icon"></i> <span>Pengajuan Izin</span>
                    @if($pendingLeave > 0)<span class="badge bg-danger" style="font-size:.6rem;">{{ $pendingLeave }}</span>@endif
                    <i class="bi bi-chevron-right arrow"></i>
                </a>
                <div class="submenu">
                    <a href="{{ route('admin.leave.index') }}" class="sub-link {{ request()->routeIs('admin.leave.index') ? 'active' : '' }}">Semua Pengajuan</a>
                    <a href="{{ route('admin.leave.index', ['status'=>'pending']) }}" class="sub-link">Menunggu Persetujuan</a>
                    <a href="{{ route('admin.leave.index', ['status'=>'approved']) }}" class="sub-link">Disetujui</a>
                </div>
            </div>

            <div class="nav-label">Administrasi Dokumen</div>
            <div class="nav-item {{ request()->routeIs('admin.surat.*') ? 'open' : '' }}">
                <a class="nav-link {{ request()->routeIs('admin.surat.*') ? 'active' : '' }}" data-toggle="submenu">
                    <i class="bi bi-envelope-paper-fill icon"></i> <span>Surat Menyurat</span> <i class="bi bi-chevron-right arrow"></i>
                </a>
                <div class="submenu">
                    <a href="{{ route('admin.surat.index') }}" class="sub-link {{ request()->routeIs('admin.surat.index') ? 'active' : '' }}">Semua Surat</a>
                    <a href="{{ route('admin.surat.index', ['jenis'=>'masuk']) }}" class="sub-link">Surat Masuk</a>
                    <a href="{{ route('admin.surat.index', ['jenis'=>'keluar']) }}" class="sub-link">Surat Keluar</a>
                    <a href="{{ route('admin.surat.create', ['jenis'=>'keluar']) }}" class="sub-link {{ request()->routeIs('admin.surat.create') ? 'active' : '' }}">Buat Surat Baru</a>
                </div>
            </div>
            <div class="nav-item {{ request()->routeIs('admin.document.*') ? 'open' : '' }}">
                <a class="nav-link {{ request()->routeIs('admin.document.*') ? 'active' : '' }}" data-toggle="submenu">
                    <i class="bi bi-archive-fill icon"></i> <span>Dokumen & Arsip</span> <i class="bi bi-chevron-right arrow"></i>
                </a>
                <div class="submenu">
                    <a href="{{ route('admin.document.index') }}" class="sub-link {{ request()->routeIs('admin.document.index') ? 'active' : '' }}">Semua Dokumen</a>
                    <a href="{{ route('admin.document.create') }}" class="sub-link {{ request()->routeIs('admin.document.create') ? 'active' : '' }}">Upload Dokumen</a>
                    <a href="{{ route('admin.document.index', ['category'=>'surat']) }}" class="sub-link">Surat Menyurat</a>
                    <a href="{{ route('admin.document.index', ['category'=>'keuangan']) }}" class="sub-link">Keuangan</a>
                    <a href="{{ route('admin.document.index', ['category'=>'kepegawaian']) }}" class="sub-link">Kepegawaian</a>
                </div>
            </div>
            <div class="nav-item {{ request()->routeIs('admin.report.*') ? 'open' : '' }}">
                <a class="nav-link {{ request()->routeIs('admin.report.*') ? 'active' : '' }}" data-toggle="submenu">
                    <i class="bi bi-journal-text icon"></i> <span>Laporan</span> <i class="bi bi-chevron-right arrow"></i>
                </a>
                <div class="submenu">
                    <a href="{{ route('admin.report.index') }}" class="sub-link {{ request()->routeIs('admin.report.index') ? 'active' : '' }}">Semua Laporan</a>
                    <a href="{{ route('admin.report.index', ['category'=>'keuangan']) }}" class="sub-link">Laporan Keuangan</a>
                    <a href="{{ route('admin.report.index', ['category'=>'inventaris']) }}" class="sub-link">Laporan Inventaris</a>
                </div>
            </div>

            <div class="nav-label">Kegiatan & Komunikasi</div>
            <div class="nav-item {{ request()->routeIs('admin.event.*') ? 'open' : '' }}">
                <a class="nav-link {{ request()->routeIs('admin.event.*') ? 'active' : '' }}" data-toggle="submenu">
                    <i class="bi bi-calendar-event-fill icon"></i> <span>Agenda & Event</span> <i class="bi bi-chevron-right arrow"></i>
                </a>
                <div class="submenu">
                    <a href="{{ route('admin.event.index') }}" class="sub-link {{ request()->routeIs('admin.event.index') ? 'active' : '' }}">Semua Event</a>
                    <a href="{{ route('admin.event.create') }}" class="sub-link {{ request()->routeIs('admin.event.create') ? 'active' : '' }}">Buat Event Baru</a>
                </div>
            </div>
            <div class="nav-item {{ request()->routeIs('admin.notification.*') ? 'open' : '' }}">
                <a class="nav-link {{ request()->routeIs('admin.notification.*') ? 'active' : '' }}" data-toggle="submenu">
                    <i class="bi bi-megaphone-fill icon"></i> <span>Notifikasi</span> <i class="bi bi-chevron-right arrow"></i>
                </a>
                <div class="submenu">
                    <a href="{{ route('admin.notification.index') }}" class="sub-link {{ request()->routeIs('admin.notification.index') ? 'active' : '' }}">Semua Notifikasi</a>
                    <a href="{{ route('admin.notification.create') }}" class="sub-link {{ request()->routeIs('admin.notification.create') ? 'active' : '' }}">Kirim Pengumuman</a>
                </div>
            </div>

            <div class="nav-label">Sistem</div>
            <div class="nav-item {{ request()->routeIs('admin.attendance.settings') ? 'open' : '' }}">
                <a class="nav-link" data-toggle="submenu">
                    <i class="bi bi-gear-fill icon"></i> <span>Pengaturan</span> <i class="bi bi-chevron-right arrow"></i>
                </a>
                <div class="submenu">
                    <a href="{{ route('admin.attendance.settings') }}" class="sub-link {{ request()->routeIs('admin.attendance.settings') ? 'active' : '' }}">Pengaturan Absensi</a>
                    <a href="{{ route('admin.staff.export', ['format'=>'csv']) }}" class="sub-link">Export Data Staff</a>
                    <a href="{{ route('admin.attendance.export', ['format'=>'csv']) }}" class="sub-link">Export Kehadiran</a>
                    <a href="{{ route('admin.document.export', ['format'=>'csv']) }}" class="sub-link">Export Dokumen</a>
                </div>
            </div>
        </nav>

        <div class="sidebar-footer">
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="bi bi-box-arrow-left"></i> <span>Logout</span>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <header class="top-header">
            <button class="sidebar-toggle" id="sidebarToggle"><i class="bi bi-list"></i></button>
            <span class="header-title">@yield('title', 'Dashboard')</span>
            <div class="header-right">
                <span class="header-date d-none d-md-block"><i class="bi bi-calendar3"></i> {{ now()->translatedFormat('d F Y') }}</span>
                <button class="notif-btn" onclick="location.href='{{ route('admin.notification.index') }}'">
                    <i class="bi bi-bell"></i>
                    @php $unreadNotif = \App\Models\Notification::where('is_read', false)->count(); @endphp
                    @if($unreadNotif > 0)<span class="notif-badge"></span>@endif
                </button>
                <div class="dropdown">
                    <button type="button" class="header-profile" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="avatar-sm">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
                        <span class="name d-none d-md-block">{{ Auth::user()->name }}</span>
                        <i class="bi bi-chevron-down" style="font-size:.65rem;color:#94a3b8;"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('admin.attendance.settings') }}"><i class="bi bi-gear me-2 text-primary"></i>Pengaturan</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="bi bi-box-arrow-left me-2"></i>Logout</a></li>
                    </ul>
                </div>
            </div>
        </header>

        <div class="page-content fade-in">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert" style="border-radius:10px; border-left: 4px solid #10b981;">
                    <i class="bi bi-check-circle-fill me-2 text-success"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert" style="border-radius:10px; border-left: 4px solid #ef4444;">
                    <i class="bi bi-exclamation-triangle-fill me-2 text-danger"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Sidebar toggle
        const sidebar = document.getElementById('sidebar');
        const toggle = document.getElementById('sidebarToggle');
        const body = document.body;
        const isMobile = () => window.innerWidth <= 991;

        toggle.addEventListener('click', () => {
            if (isMobile()) { body.classList.toggle('sidebar-open'); }
            else { body.classList.toggle('sidebar-collapsed'); localStorage.setItem('sidebar', body.classList.contains('sidebar-collapsed') ? 'collapsed' : 'expanded'); }
        });
        if (!isMobile() && localStorage.getItem('sidebar') === 'collapsed') body.classList.add('sidebar-collapsed');
        document.addEventListener('click', e => { if (isMobile() && body.classList.contains('sidebar-open') && !sidebar.contains(e.target) && e.target !== toggle) body.classList.remove('sidebar-open'); });

        // Submenu toggle — accordion: close others when opening
        document.querySelectorAll('[data-toggle="submenu"]').forEach(el => {
            el.addEventListener('click', e => {
                e.preventDefault();
                const item = el.closest('.nav-item');
                const isOpen = item.classList.contains('open');
                // close all open submenus
                document.querySelectorAll('.nav-item.open').forEach(i => i.classList.remove('open'));
                // if it was closed, open it
                if (!isOpen) item.classList.add('open');
            });
        });

        // SweetAlert for delete confirmations
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('[data-confirm]');
            if (!btn) return;
            e.preventDefault();
            Swal.fire({
                title: 'Konfirmasi', text: btn.dataset.confirm || 'Yakin ingin melanjutkan?',
                icon: 'warning', showCancelButton: true, confirmButtonColor: '#6366f1', cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Ya', cancelButtonText: 'Batal', reverseButtons: true,
                customClass: { popup: 'rounded-4' }
            }).then(r => { if (r.isConfirmed) { const form = btn.closest('form'); if (form) form.submit(); else location.href = btn.href; } });
        });

        // Auto close alerts
        setTimeout(() => { document.querySelectorAll('.alert').forEach(a => { new bootstrap.Alert(a).close(); }); }, 4000);
    </script>
    @stack('scripts')
</body>
</html>
