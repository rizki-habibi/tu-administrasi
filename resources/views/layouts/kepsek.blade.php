<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('judul', 'Beranda') - Kepala Sekolah</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        :root {
            --sidebar-w: 268px;
            --header-h: 62px;
            --primary: #d97706;
            --primary-dark: #b45309;
            --primary-light: #f59e0b;
            --secondary: #ea580c;
            --sidebar-bg: linear-gradient(180deg, #1c1917 0%, #292524 100%);
            --body-bg: #faf5f0;
            --card-radius: 14px;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { overflow-x: hidden; }
        body { font-family: 'Poppins', sans-serif; background: var(--body-bg); overflow-x: hidden; }

        .sidebar {
            position: fixed; top: 0; left: 0; width: var(--sidebar-w); height: 100vh;
            background: var(--sidebar-bg); color: #a8a29e; z-index: 1040;
            transition: transform .3s cubic-bezier(.4,0,.2,1);
            display: flex; flex-direction: column; overflow: hidden;
        }
        .sidebar-brand { padding: 20px 20px 12px; text-align: center; border-bottom: 1px solid rgba(255,255,255,.06); }
        .sidebar-brand img { width: 52px; height: 52px; border-radius: 12px; object-fit: cover; border: 2px solid rgba(217,119,6,.3); }
        .sidebar-brand h6 { color: #fbbf24; font-size: .85rem; font-weight: 600; margin: 8px 0 2px; }
        .sidebar-brand small { font-size: .68rem; color: #78716c; }

        .sidebar-profile { padding: 16px 20px; border-bottom: 1px solid rgba(255,255,255,.06); display: flex; align-items: center; gap: 10px; }
        .sidebar-profile .avatar {
            width: 40px; height: 40px; border-radius: 10px;
            background: linear-gradient(135deg, #d97706, #ea580c);
            display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 600; font-size: .85rem; flex-shrink: 0;
        }
        .sidebar-profile .avatar img { width: 100%; height: 100%; border-radius: 10px; object-fit: cover; }
        .sidebar-profile .info { overflow: hidden; }
        .sidebar-profile .info .name { color: #fef3c7; font-size: .8rem; font-weight: 600; white-space: nowrap; text-overflow: ellipsis; overflow: hidden; }
        .sidebar-profile .info .role { font-size: .68rem; color: #d97706; }
        .sidebar-profile .status { width: 8px; height: 8px; border-radius: 50%; background: #34d399; margin-left: auto; flex-shrink: 0; }

        .sidebar-nav { flex: 1; overflow-y: auto; padding: 12px 0; }
        .sidebar-nav::-webkit-scrollbar { width: 4px; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,.1); border-radius: 4px; }
        .nav-label { font-size: .65rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1.2px; color: #92400e; padding: 12px 20px 6px; }
        .nav-item { position: relative; }
        .nav-link {
            display: flex; align-items: center; padding: 9px 20px; color: #a8a29e; text-decoration: none;
            font-size: .82rem; font-weight: 400; transition: all .2s; gap: 12px; cursor: pointer; border-left: 3px solid transparent;
        }
        .nav-link:hover { background: rgba(217,119,6,.12); color: #fef3c7; }
        .nav-link.active, .nav-link.active:hover { background: rgba(217,119,6,.18); color: #fff; border-left-color: var(--primary-light); font-weight: 500; }
        .nav-link i.icon { font-size: 1.1rem; width: 22px; text-align: center; }
        .nav-link .arrow { margin-left: auto; font-size: .7rem; transition: transform .25s; }
        .nav-item.open > .nav-link .arrow { transform: rotate(90deg); }
        .nav-link .badge { font-size: .6rem; margin-left: auto; }

        .submenu { max-height: 0; overflow: hidden; transition: max-height .35s cubic-bezier(.4,0,.2,1); background: rgba(0,0,0,.15); }
        .nav-item.open > .submenu { max-height: 400px; }
        .submenu .sub-link {
            display: flex; align-items: center; padding: 7px 20px 7px 54px; color: #78716c; font-size: .78rem;
            text-decoration: none; transition: all .2s; gap: 8px;
        }
        .submenu .sub-link:hover { color: #fef3c7; background: rgba(217,119,6,.08); }
        .submenu .sub-link.active { color: #fff; font-weight: 500; }

        .sidebar-footer { padding: 14px 20px; border-top: 1px solid rgba(255,255,255,.06); }
        .sidebar-footer a { color: #ef4444; font-size: .8rem; text-decoration: none; display: flex; align-items: center; gap: 8px; }
        .sidebar-footer a:hover { color: #fca5a5; }

        .main-content { margin-left: var(--sidebar-w); transition: margin .3s cubic-bezier(.4,0,.2,1); min-height: 100vh; overflow-x: hidden; max-width: 100vw; }
        .top-header {
            position: sticky; top: 0; z-index: 1030; height: var(--header-h);
            background: #fff; border-bottom: 1px solid #e7e5e4;
            display: flex; align-items: center; padding: 0 24px; gap: 16px;
            box-shadow: 0 1px 3px rgba(0,0,0,.04);
        }
        .sidebar-toggle { background: none; border: none; font-size: 1.3rem; color: #57534e; cursor: pointer; padding: 6px; border-radius: 8px; transition: .2s; }
        .sidebar-toggle:hover { background: #fef3c7; color: var(--primary); }
        .header-title { font-size: .9rem; font-weight: 600; color: #1c1917; }
        .header-right { margin-left: auto; display: flex; align-items: center; gap: 12px; }
        .header-date { font-size: .78rem; color: #78716c; }
        .notif-btn { position: relative; background: #fef3c7; border: none; width: 38px; height: 38px; border-radius: 10px; font-size: 1.1rem; color: #92400e; cursor: pointer; transition: .2s; }
        .notif-btn:hover { background: #fde68a; color: var(--primary); }
        .notif-badge { position: absolute; top: 4px; right: 4px; width: 8px; height: 8px; background: #ef4444; border-radius: 50%; }
        .header-profile { display: flex; align-items: center; gap: 8px; cursor: pointer; padding: 4px 10px; border-radius: 10px; transition: .2s; border: none; background: none; }
        .header-profile:hover { background: #fef3c7; }
        .header-profile .avatar-sm { width: 34px; height: 34px; border-radius: 8px; background: linear-gradient(135deg, #d97706, #ea580c); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: .75rem; }
        .header-profile .name { font-size: .8rem; font-weight: 500; color: #1c1917; }

        .page-content { padding: 24px; overflow-x: hidden; }

        .dropdown-menu { min-width: 200px; padding: 6px; border-radius: 12px !important; border: 1px solid #e7e5e4; box-shadow: 0 8px 24px rgba(0,0,0,.1) !important; }
        .dropdown-item { border-radius: 8px; font-size: .82rem; padding: 9px 14px; color: #374151; font-weight: 500; transition: all .15s; }
        .dropdown-item:hover { background: #fef3c7; color: var(--primary); }
        .dropdown-item.text-danger:hover { background: #fef2f2; color: #ef4444; }
        .dropdown-divider { margin: 4px 0; border-color: #f5f5f4; }

        .card { border: none; border-radius: var(--card-radius); box-shadow: 0 1px 3px rgba(0,0,0,.06); transition: box-shadow .2s; }
        .card:hover { box-shadow: 0 4px 12px rgba(0,0,0,.08); }
        .stat-card { border-radius: var(--card-radius); padding: 20px; color: #fff; position: relative; overflow: hidden; }
        .stat-card::after { content: ''; position: absolute; top: -20px; right: -20px; width: 80px; height: 80px; border-radius: 50%; background: rgba(255,255,255,.1); }
        .stat-card .icon-box { width: 48px; height: 48px; border-radius: 12px; background: rgba(255,255,255,.2); display: flex; align-items: center; justify-content: center; font-size: 1.3rem; }
        .stat-card h3 { font-size: 1.6rem; font-weight: 700; margin: 8px 0 2px; }
        .stat-card p { font-size: .78rem; opacity: .85; margin: 0; }

        .table { font-size: .82rem; }
        .table thead th { font-weight: 600; color: #57534e; text-transform: uppercase; font-size: .72rem; letter-spacing: .5px; border-bottom-width: 1px; }
        .badge { font-weight: 500; font-size: .72rem; padding: 4px 10px; border-radius: 6px; }
        .btn { font-size: .82rem; font-weight: 500; border-radius: 8px; padding: 8px 16px; transition: all .2s; }
        .btn-primary { background: linear-gradient(135deg, var(--primary), var(--secondary)); border: none; }
        .btn-primary:hover { background: linear-gradient(135deg, var(--primary-dark), #c2410c); transform: translateY(-1px); box-shadow: 0 4px 12px rgba(217,119,6,.3); }

        .pagination { gap: 4px; flex-wrap: wrap; justify-content: center; }
        .page-link { border-radius: 8px !important; border: 1px solid #e7e5e4; color: #92400e; font-size: .82rem; font-weight: 500; padding: 8px 14px; transition: all .2s; }
        .page-link:hover { background: linear-gradient(135deg, #d97706, #f59e0b); color: #fff; border-color: transparent; }
        .page-item.active .page-link { background: linear-gradient(135deg, #b45309, #d97706); border-color: transparent; color: #fff; }

        .form-control, .form-select { font-size: .82rem; border-radius: 8px; border-color: #e7e5e4; padding: 9px 14px; }
        .form-control:focus, .form-select:focus { border-color: var(--primary-light); box-shadow: 0 0 0 3px rgba(217,119,6,.12); }
        .form-label { font-size: .8rem; font-weight: 500; color: #57534e; }

        body.sidebar-collapsed .sidebar { transform: translateX(-100%); }
        body.sidebar-collapsed .main-content { margin-left: 0; }
        @media (max-width: 991px) {
            .sidebar { transform: translateX(-100%); }
            .main-content { margin-left: 0; }
            body.sidebar-open .sidebar { transform: translateX(0); }
            body.sidebar-open::after { content: ''; position: fixed; inset: 0; background: rgba(0,0,0,.4); z-index: 1039; }
        }
        .modal-content { border: none; border-radius: var(--card-radius); }
        .modal-header { border-bottom: 1px solid #f5f5f4; padding: 16px 20px; }
        .modal-header .modal-title { font-size: .95rem; font-weight: 600; }
        .modal-footer { border-top: 1px solid #f5f5f4; padding: 12px 20px; }

        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-thumb { background: #d6d3d1; border-radius: 6px; }
        .fade-in { animation: fadeIn .4s ease; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
        @media print {
            .sidebar, .top-header { display: none !important; }
            .main-content { margin-left: 0 !important; }
            .page-content { padding: 0 !important; }
        }
        @stack('styles')
    </style>
</head>
<body>
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <img src="{{ asset('storage/gambar/logo.png') }}" alt="Logo" onerror="this.style.display='none'">
            <h6>Kepala Sekolah</h6>
            <small>SMA Negeri 2 Jember</small>
        </div>

        <div class="sidebar-profile">
            <div class="avatar">
                @if(Auth::user()->foto)
                    <img src="{{ asset('storage/' . Auth::user()->foto) }}" alt="">
                @else
                    {{ strtoupper(substr(Auth::user()->nama, 0, 2)) }}
                @endif
            </div>
            <div class="info">
                <div class="nama">{{ Auth::user()->nama }}</div>
                <div class="peran"><i class="bi bi-shield-check"></i> Kepala Sekolah</div>
            </div>
            <div class="status" title="Online"></div>
        </div>

        @php
            $pendingLeave = \App\Models\LeaveRequest::where('status','pending')->count();
            $pendingSkp = \App\Models\Skp::where('status','diajukan')->count();
            $unread = Auth::user()->notifications()->where('sudah_dibaca', false)->count();
        @endphp
        <nav class="sidebar-nav">
            <div class="nav-label">Menu Utama</div>
            <div class="nav-item">
                <a href="{{ route('kepsek.dashboard') }}" class="nav-link {{ request()->routeIs('kepsek.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2-fill icon"></i> <span>Beranda</span>
                </a>
            </div>

            <div class="nav-label">Monitoring Staff</div>
            <div class="nav-item {{ request()->routeIs('kepsek.staff.*') ? 'open' : '' }}">
                <a class="nav-link {{ request()->routeIs('kepsek.staff.*') ? 'active' : '' }}" data-toggle="submenu">
                    <i class="bi bi-people-fill icon"></i> <span>Data Staff</span> <i class="bi bi-chevron-right arrow"></i>
                </a>
                <div class="submenu">
                    <a href="{{ route('kepsek.staff.index') }}" class="sub-link {{ request()->routeIs('kepsek.staff.index') ? 'active' : '' }}">Semua Staff</a>
                </div>
            </div>
            <div class="nav-item {{ request()->routeIs('kepsek.attendance.*') ? 'open' : '' }}">
                <a class="nav-link {{ request()->routeIs('kepsek.attendance.*') ? 'active' : '' }}" data-toggle="submenu">
                    <i class="bi bi-fingerprint icon"></i> <span>Kehadiran</span> <i class="bi bi-chevron-right arrow"></i>
                </a>
                <div class="submenu">
                    <a href="{{ route('kepsek.attendance.index') }}" class="sub-link {{ request()->routeIs('kepsek.attendance.index') ? 'active' : '' }}">Absensi Hari Ini</a>
                    <a href="{{ route('kepsek.attendance.report') }}" class="sub-link {{ request()->routeIs('kepsek.attendance.report') ? 'active' : '' }}">Rekap Kehadiran</a>
                </div>
            </div>
            <div class="nav-item {{ request()->routeIs('kepsek.leave.*') ? 'open' : '' }}">
                <a class="nav-link {{ request()->routeIs('kepsek.leave.*') ? 'active' : '' }}" data-toggle="submenu">
                    <i class="bi bi-calendar2-check icon"></i> <span>Pengajuan Izin</span>
                    @if($pendingLeave > 0)<span class="badge bg-danger" style="font-size:.6rem;">{{ $pendingLeave }}</span>@endif
                    <i class="bi bi-chevron-right arrow"></i>
                </a>
                <div class="submenu">
                    <a href="{{ route('kepsek.leave.index') }}" class="sub-link {{ request()->routeIs('kepsek.leave.index') ? 'active' : '' }}">Semua Pengajuan</a>
                    <a href="{{ route('kepsek.leave.index', ['status'=>'pending']) }}" class="sub-link">Menunggu Persetujuan</a>
                </div>
            </div>

            <div class="nav-label">Kinerja Pegawai</div>
            <div class="nav-item {{ request()->routeIs('kepsek.skp.*') ? 'open' : '' }}">
                <a class="nav-link {{ request()->routeIs('kepsek.skp.*') ? 'active' : '' }}" data-toggle="submenu">
                    <i class="bi bi-person-lines-fill icon"></i> <span>SKP</span>
                    @if($pendingSkp > 0)<span class="badge bg-warning text-dark" style="font-size:.6rem;">{{ $pendingSkp }}</span>@endif
                    <i class="bi bi-chevron-right arrow"></i>
                </a>
                <div class="submenu">
                    <a href="{{ route('kepsek.skp.index') }}" class="sub-link {{ request()->routeIs('kepsek.skp.index') ? 'active' : '' }}">Semua SKP</a>
                    <a href="{{ route('kepsek.skp.index', ['status'=>'diajukan']) }}" class="sub-link">Menunggu Penilaian</a>
                </div>
            </div>
            <div class="nav-item {{ request()->routeIs('kepsek.evaluasi.*') ? 'open' : '' }}">
                <a class="nav-link {{ request()->routeIs('kepsek.evaluasi.*') ? 'active' : '' }}" data-toggle="submenu">
                    <i class="bi bi-clipboard2-data-fill icon"></i> <span>Evaluasi Kinerja</span> <i class="bi bi-chevron-right arrow"></i>
                </a>
                <div class="submenu">
                    <a href="{{ route('kepsek.evaluasi.pkg') }}" class="sub-link {{ request()->routeIs('kepsek.evaluasi.pkg*') ? 'active' : '' }}">PKG / BKD</a>
                    <a href="{{ route('kepsek.evaluasi.star') }}" class="sub-link {{ request()->routeIs('kepsek.evaluasi.star*') ? 'active' : '' }}">Metode STAR</a>
                    <a href="{{ route('kepsek.evaluasi.bukti-fisik') }}" class="sub-link {{ request()->routeIs('kepsek.evaluasi.bukti-fisik*') ? 'active' : '' }}">Bukti Fisik</a>
                </div>
            </div>

            <div class="nav-label">Administrasi</div>
            <div class="nav-item {{ request()->routeIs('kepsek.surat.*') ? 'open' : '' }}">
                <a class="nav-link {{ request()->routeIs('kepsek.surat.*') ? 'active' : '' }}" data-toggle="submenu">
                    <i class="bi bi-envelope-paper-fill icon"></i> <span>Surat Menyurat</span> <i class="bi bi-chevron-right arrow"></i>
                </a>
                <div class="submenu">
                    <a href="{{ route('kepsek.surat.index') }}" class="sub-link {{ request()->routeIs('kepsek.surat.index') ? 'active' : '' }}">Semua Surat</a>
                </div>
            </div>
            <div class="nav-item {{ request()->routeIs('kepsek.report.*') ? 'open' : '' }}">
                <a class="nav-link {{ request()->routeIs('kepsek.report.*') ? 'active' : '' }}" data-toggle="submenu">
                    <i class="bi bi-journal-text icon"></i> <span>Laporan</span> <i class="bi bi-chevron-right arrow"></i>
                </a>
                <div class="submenu">
                    <a href="{{ route('kepsek.report.index') }}" class="sub-link {{ request()->routeIs('kepsek.report.index') ? 'active' : '' }}">Semua Laporan</a>
                </div>
            </div>
            <div class="nav-item {{ request()->routeIs('kepsek.keuangan.*') ? 'open' : '' }}">
                <a class="nav-link {{ request()->routeIs('kepsek.keuangan.*') ? 'active' : '' }}" data-toggle="submenu">
                    <i class="bi bi-cash-coin icon"></i> <span>Keuangan</span> <i class="bi bi-chevron-right arrow"></i>
                </a>
                <div class="submenu">
                    <a href="{{ route('kepsek.keuangan.index') }}" class="sub-link {{ request()->routeIs('kepsek.keuangan.index') ? 'active' : '' }}">Rekapitulasi</a>
                </div>
            </div>

            <div class="nav-label">Lainnya</div>
            <div class="nav-item {{ request()->routeIs('kepsek.event.*') ? 'open' : '' }}">
                <a class="nav-link {{ request()->routeIs('kepsek.event.*') ? 'active' : '' }}" data-toggle="submenu">
                    <i class="bi bi-calendar-event-fill icon"></i> <span>Agenda</span> <i class="bi bi-chevron-right arrow"></i>
                </a>
                <div class="submenu">
                    <a href="{{ route('kepsek.event.index') }}" class="sub-link {{ request()->routeIs('kepsek.event.index') ? 'active' : '' }}">Semua Event</a>
                </div>
            </div>
            <div class="nav-item">
                <a href="{{ route('kepsek.notification.index') }}" class="nav-link {{ request()->routeIs('kepsek.notification.*') ? 'active' : '' }}">
                    <i class="bi bi-bell-fill icon"></i> <span>Notifikasi</span>
                    @if($unread > 0)<span class="badge bg-danger">{{ $unread }}</span>@endif
                </a>
            </div>
            <div class="nav-item {{ request()->routeIs('kepsek.profile.*') ? 'open' : '' }}">
                <a class="nav-link {{ request()->routeIs('kepsek.profile.*') ? 'active' : '' }}" data-toggle="submenu">
                    <i class="bi bi-person-gear icon"></i> <span>Akun Saya</span> <i class="bi bi-chevron-right arrow"></i>
                </a>
                <div class="submenu">
                    <a href="{{ route('kepsek.profile.edit') }}" class="sub-link {{ request()->routeIs('kepsek.profile.edit') ? 'active' : '' }}">Edit Profil</a>
                    <a href="{{ route('kepsek.profile.edit') }}#ubah-password" class="sub-link">Ubah Password</a>
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

    <div class="main-content" id="mainContent">
        <header class="top-header">
            <button class="sidebar-toggle" id="sidebarToggle"><i class="bi bi-list"></i></button>
            <span class="header-title">@yield('judul', 'Beranda')</span>
            <div class="header-right">
                <span class="header-date d-none d-md-block"><i class="bi bi-calendar3"></i> {{ now()->translatedFormat('d F Y') }}</span>
                <div class="dropdown" id="notifDropdown">
                    <button class="notif-btn" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false" id="notifToggle">
                        <i class="bi bi-bell"></i>
                        @if($unread > 0)<span class="notif-badge" id="notifBadge"></span>@endif
                    </button>
                    <div class="dropdown-menu dropdown-menu-end p-0" style="width:360px;max-height:440px;border-radius:14px!important;overflow:hidden;">
                        <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom" style="background:#fffbeb;">
                            <h6 class="mb-0 fw-bold" style="font-size:.85rem;">Notifikasi</h6>
                            <span class="badge bg-danger" id="notifCount" style="font-size:.65rem;">{{ $unread }}</span>
                        </div>
                        <div id="notifList" style="max-height:320px;overflow-y:auto;">
                            <div class="text-center py-4 text-muted" style="font-size:.8rem;" id="notifLoading">
                                <div class="spinner-border spinner-border-sm text-warning me-1"></div> Memuat...
                            </div>
                        </div>
                        <div class="border-top text-center py-2" style="background:#fffbeb;">
                            <a href="{{ route('kepsek.notification.index') }}" class="text-decoration-none fw-semibold" style="font-size:.78rem;color:#92400e;">Lihat Semua Notifikasi <i class="bi bi-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
                <div class="dropdown">
                    <button type="button" class="header-profile" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="avatar-sm">{{ strtoupper(substr(Auth::user()->nama, 0, 2)) }}</div>
                        <span class="name d-none d-md-block">{{ Auth::user()->nama }}</span>
                        <i class="bi bi-chevron-down" style="font-size:.65rem;color:#a8a29e;"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('kepsek.profile.edit') }}"><i class="bi bi-person me-2 text-warning"></i>Profil Saya</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="bi bi-box-arrow-left me-2"></i>Logout</a></li>
                    </ul>
                </div>
            </div>
        </header>

        <div class="page-content fade-in">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert" style="border-radius:10px; border-left: 4px solid #10b981;">
                    <i class="bi bi-check-circle-fill me-2 text-success"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert" style="border-radius:10px; border-left: 4px solid #ef4444;">
                    <i class="bi bi-exclamation-triangle-fill me-2 text-danger"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @yield('konten')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const sidebar = document.getElementById('sidebar');
        const toggle = document.getElementById('sidebarToggle');
        const body = document.body;
        const isMobile = () => window.innerWidth <= 991;
        toggle.addEventListener('click', () => {
            if (isMobile()) body.classList.toggle('sidebar-open');
            else { body.classList.toggle('sidebar-collapsed'); localStorage.setItem('sidebar-kepsek', body.classList.contains('sidebar-collapsed') ? 'c' : 'e'); }
        });
        if (!isMobile() && localStorage.getItem('sidebar-kepsek') === 'c') body.classList.add('sidebar-collapsed');
        document.addEventListener('click', e => { if (isMobile() && body.classList.contains('sidebar-open') && !sidebar.contains(e.target) && e.target !== toggle) body.classList.remove('sidebar-open'); });
        document.querySelectorAll('[data-toggle="submenu"]').forEach(el => {
            el.addEventListener('click', e => {
                e.preventDefault();
                const item = el.closest('.nav-item');
                const isOpen = item.classList.contains('open');
                document.querySelectorAll('.nav-item.open').forEach(i => i.classList.remove('open'));
                if (!isOpen) item.classList.add('open');
            });
        });
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('[data-confirm]');
            if (!btn) return; e.preventDefault();
            Swal.fire({ title: 'Konfirmasi', text: btn.dataset.confirm || 'Yakin?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#d97706', cancelButtonColor: '#a8a29e', confirmButtonText: 'Ya', cancelButtonText: 'Batal', reverseButtons: true })
            .then(r => { if (r.isConfirmed) { const f = btn.closest('form'); if (f) f.submit(); else location.href = btn.href; } });
        });
        setTimeout(() => { document.querySelectorAll('.alert').forEach(a => { try { new bootstrap.Alert(a).close(); } catch(e){} }); }, 4000);

        const notifToggle = document.getElementById('notifToggle');
        if (notifToggle) { notifToggle.addEventListener('show.bs.dropdown', loadNotifications); }
        function loadNotifications() {
            const list = document.getElementById('notifList');
            list.innerHTML = '<div class="text-center py-4 text-muted" style="font-size:.8rem;"><div class="spinner-border spinner-border-sm text-warning me-1"></div> Memuat...</div>';
            fetch('{{ route("kepsek.notification.json") }}', { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
                .then(r => r.json())
                .then(data => {
                    const badge = document.getElementById('notifBadge');
                    const count = document.getElementById('notifCount');
                    if (count) count.textContent = data.unread_count;
                    if (badge) badge.style.display = data.unread_count > 0 ? '' : 'none';
                    if (data.notifications.length === 0) {
                        list.innerHTML = '<div class="text-center py-4 text-muted" style="font-size:.82rem;"><i class="bi bi-bell-slash" style="font-size:1.5rem;"></i><p class="mb-0 mt-1">Tidak ada notifikasi baru</p></div>';
                        return;
                    }
                    const icons = { info: 'bi-info-circle text-primary', warning: 'bi-exclamation-triangle text-warning', success: 'bi-check-circle text-success', danger: 'bi-x-circle text-danger' };
                    list.innerHTML = data.notifications.map(n => `
                        <a href="${n.read_url}" class="d-flex gap-2 px-3 py-2 text-decoration-none border-bottom notif-item" style="transition:.15s;cursor:pointer;">
                            <div class="flex-shrink-0 mt-1"><i class="bi ${icons[n.type] || icons.info}" style="font-size:1rem;"></i></div>
                            <div class="flex-grow-1 overflow-hidden">
                                <div class="fw-semibold text-dark" style="font-size:.82rem;">${n.title}</div>
                                <div class="text-muted" style="font-size:.75rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${n.message}</div>
                                <small class="text-muted" style="font-size:.68rem;">${n.time}</small>
                            </div>
                        </a>
                    `).join('');
                })
                .catch(() => { list.innerHTML = '<div class="text-center py-4 text-muted" style="font-size:.82rem;">Gagal memuat notifikasi</div>'; });
        }
        setInterval(() => {
            fetch('{{ route("kepsek.notification.json") }}', { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
                .then(r => r.json()).then(data => {
                    const badge = document.getElementById('notifBadge');
                    const count = document.getElementById('notifCount');
                    if (count) count.textContent = data.unread_count;
                    if (badge) badge.style.display = data.unread_count > 0 ? '' : 'none';
                }).catch(() => {});
        }, 30000);
    </script>
    @stack('scripts')
</body>
</html>
