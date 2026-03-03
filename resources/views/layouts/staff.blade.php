<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Beranda') - TU Staff</title>
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
            --sidebar-bg: linear-gradient(180deg, #0f172a 0%, #1e293b 100%);
            --body-bg: #f0f2f8;
            --card-radius: 14px;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { overflow-x: hidden; }
        body { font-family: 'Poppins', sans-serif; background: var(--body-bg); overflow-x: hidden; }

        .sidebar {
            position: fixed; top: 0; left: 0; width: var(--sidebar-w); height: 100vh;
            background: var(--sidebar-bg); color: #94a3b8; z-index: 1040;
            transition: transform .3s cubic-bezier(.4,0,.2,1);
            display: flex; flex-direction: column; overflow: hidden;
        }
        .sidebar-brand { padding: 20px 20px 12px; text-align: center; border-bottom: 1px solid rgba(255,255,255,.06); }
        .sidebar-brand img { width: 52px; height: 52px; border-radius: 12px; object-fit: cover; border: 2px solid rgba(255,255,255,.1); }
        .sidebar-brand h6 { color: #fff; font-size: .85rem; font-weight: 600; margin: 8px 0 2px; }
        .sidebar-brand small { font-size: .68rem; color: #64748b; }

        .sidebar-profile { padding: 16px 20px; border-bottom: 1px solid rgba(255,255,255,.06); display: flex; align-items: center; gap: 10px; }
        .sidebar-profile .avatar {
            width: 40px; height: 40px; border-radius: 10px;
            background: linear-gradient(135deg, #10b981, #059669);
            display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 600; font-size: .85rem; flex-shrink: 0;
        }
        .sidebar-profile .avatar img { width: 100%; height: 100%; border-radius: 10px; object-fit: cover; }
        .sidebar-profile .info { overflow: hidden; }
        .sidebar-profile .info .name { color: #f1f5f9; font-size: .8rem; font-weight: 600; white-space: nowrap; text-overflow: ellipsis; overflow: hidden; }
        .sidebar-profile .info .role { font-size: .68rem; color: #64748b; }
        .sidebar-profile .status { width: 8px; height: 8px; border-radius: 50%; background: #34d399; margin-left: auto; flex-shrink: 0; }

        .sidebar-nav { flex: 1; overflow-y: auto; padding: 12px 0; }
        .sidebar-nav::-webkit-scrollbar { width: 4px; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,.1); border-radius: 4px; }
        .nav-label { font-size: .65rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1.2px; color: #475569; padding: 12px 20px 6px; }
        .nav-item { position: relative; }
        .nav-link {
            display: flex; align-items: center; padding: 9px 20px; color: #94a3b8; text-decoration: none;
            font-size: .82rem; font-weight: 400; transition: all .2s; gap: 12px; cursor: pointer; border-left: 3px solid transparent;
        }
        .nav-link:hover { background: rgba(99,102,241,.1); color: #e2e8f0; }
        .nav-link.active, .nav-link.active:hover { background: rgba(99,102,241,.15); color: #fff; border-left-color: var(--primary-light); font-weight: 500; }
        .nav-link i.icon { font-size: 1.1rem; width: 22px; text-align: center; }
        .nav-link .arrow { margin-left: auto; font-size: .7rem; transition: transform .25s; }
        .nav-item.open > .nav-link .arrow { transform: rotate(90deg); }
        .nav-link .badge { font-size: .6rem; margin-left: auto; }

        .submenu { max-height: 0; overflow: hidden; transition: max-height .35s cubic-bezier(.4,0,.2,1); background: rgba(0,0,0,.15); }
        .nav-item.open > .submenu { max-height: 400px; }
        .submenu .sub-link {
            display: flex; align-items: center; padding: 7px 20px 7px 54px; color: #64748b; font-size: .78rem;
            text-decoration: none; transition: all .2s; gap: 8px;
        }
        .submenu .sub-link::before { content: ''; width: 16px; height: 2px; border-radius: 2px; background: #475569; flex-shrink: 0; }
        .submenu .sub-link:hover { color: #e2e8f0; background: rgba(99,102,241,.08); }
        .submenu .sub-link.active { color: #fff; font-weight: 500; }
        .submenu .sub-link.active::before { background: var(--primary-light); box-shadow: 0 0 6px var(--primary-light); }

        .sidebar-footer { padding: 14px 20px; border-top: 1px solid rgba(255,255,255,.06); }
        .sidebar-footer a { color: #ef4444; font-size: .8rem; text-decoration: none; display: flex; align-items: center; gap: 8px; }
        .sidebar-footer a:hover { color: #fca5a5; }

        .main-content { margin-left: var(--sidebar-w); transition: margin .3s cubic-bezier(.4,0,.2,1); min-height: 100vh; overflow-x: hidden; max-width: 100vw; }
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
        .header-profile .avatar-sm { width: 34px; height: 34px; border-radius: 8px; background: linear-gradient(135deg, #10b981, #059669); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: .75rem; }
        .header-profile .name { font-size: .8rem; font-weight: 500; color: #1e293b; }

        .page-content { padding: 24px; overflow-x: hidden; }

        /* ── Header Dropdown ── */
        .dropdown-menu { min-width: 200px; padding: 6px; border-radius: 12px !important; border: 1px solid #e2e8f0; box-shadow: 0 8px 24px rgba(0,0,0,.1) !important; }
        .dropdown-item { border-radius: 8px; font-size: .82rem; padding: 9px 14px; color: #374151; font-weight: 500; transition: all .15s; }
        .dropdown-item:hover { background: #f0fdf4; color: #059669; }
        .dropdown-item.text-danger:hover { background: #fef2f2; color: #ef4444; }
        .dropdown-divider { margin: 4px 0; border-color: #f1f5f9; }

        /* ── Pagination ── */
        .pagination { gap: 4px; flex-wrap: wrap; justify-content: center; }
        .page-link { border-radius: 8px !important; border: 1px solid #e2e8f0; color: #0f766e; font-size: .82rem; font-weight: 500; padding: 8px 14px; transition: all .2s; }
        .page-link:hover { background: linear-gradient(135deg, #10b981, #059669); color: #fff; border-color: transparent; box-shadow: 0 2px 8px rgba(16,185,129,.3); }
        .page-item.active .page-link { background: linear-gradient(135deg, #0f766e, #10b981); border-color: transparent; color: #fff; box-shadow: 0 2px 8px rgba(16,185,129,.3); }
        .page-item.disabled .page-link { background: #f8fafc; color: #94a3b8; border-color: #e2e8f0; }

        .card { border: none; border-radius: var(--card-radius); box-shadow: 0 1px 3px rgba(0,0,0,.06); transition: box-shadow .2s; }
        .card:hover { box-shadow: 0 4px 12px rgba(0,0,0,.08); }
        .stat-card { border-radius: var(--card-radius); padding: 20px; color: #fff; position: relative; overflow: hidden; }
        .stat-card::after { content: ''; position: absolute; top: -20px; right: -20px; width: 80px; height: 80px; border-radius: 50%; background: rgba(255,255,255,.1); }
        .stat-card .icon-box { width: 48px; height: 48px; border-radius: 12px; background: rgba(255,255,255,.2); display: flex; align-items: center; justify-content: center; font-size: 1.3rem; }
        .stat-card h3 { font-size: 1.6rem; font-weight: 700; margin: 8px 0 2px; }
        .stat-card p { font-size: .78rem; opacity: .85; margin: 0; }

        .table { font-size: .82rem; }
        .table thead th { font-weight: 600; color: #475569; text-transform: uppercase; font-size: .72rem; letter-spacing: .5px; border-bottom-width: 1px; }
        .badge { font-weight: 500; font-size: .72rem; padding: 4px 10px; border-radius: 6px; }
        .btn { font-size: .82rem; font-weight: 500; border-radius: 8px; padding: 8px 16px; transition: all .2s; }
        .btn-primary { background: linear-gradient(135deg, var(--primary), var(--secondary)); border: none; }
        .btn-primary:hover { background: linear-gradient(135deg, var(--primary-dark), #7c3aed); transform: translateY(-1px); box-shadow: 0 4px 12px rgba(99,102,241,.3); }

        body.sidebar-collapsed .sidebar { transform: translateX(-100%); }
        body.sidebar-collapsed .main-content { margin-left: 0; }
        @media (max-width: 991px) {
            .sidebar { transform: translateX(-100%); }
            .main-content { margin-left: 0; }
            body.sidebar-open .sidebar { transform: translateX(0); }
            body.sidebar-open::after { content: ''; position: fixed; inset: 0; background: rgba(0,0,0,.4); z-index: 1039; }
        }
        .modal-content { border: none; border-radius: var(--card-radius); }
        .modal-header { border-bottom: 1px solid #f1f5f9; padding: 16px 20px; }
        .modal-header .modal-title { font-size: .95rem; font-weight: 600; }
        .modal-footer { border-top: 1px solid #f1f5f9; padding: 12px 20px; }
        .form-control, .form-select { font-size: .82rem; border-radius: 8px; border-color: #e2e8f0; padding: 9px 14px; }
        .form-control:focus, .form-select:focus { border-color: var(--primary-light); box-shadow: 0 0 0 3px rgba(99,102,241,.12); }
        .form-label { font-size: .8rem; font-weight: 500; color: #475569; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 6px; }
        .fade-in { animation: fadeIn .4s ease; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
        @media print { .sidebar, .top-header { display: none !important; } .main-content { margin-left: 0 !important; } .page-content { padding: 0 !important; } }
        @stack('styles')
    </style>
</head>
<body>
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <img src="{{ asset('storage/gambar/logo.png') }}" alt="Logo" onerror="this.style.display='none'">
            <h6>TU Administrasi</h6>
            <small>SMA Negeri 2 Jember</small>
        </div>
        <div class="sidebar-profile">
            <div class="avatar">
                @if(Auth::user()->photo)
                    <img src="{{ asset('storage/' . Auth::user()->photo) }}" alt="">
                @else
                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                @endif
            </div>
            <div class="info">
                <div class="name">{{ Auth::user()->name }}</div>
                <div class="role"><i class="bi bi-person-badge"></i> {{ Auth::user()->position ?? 'Staff TU' }}</div>
            </div>
            <div class="status" title="Online"></div>
        </div>

        @php $unread = Auth::user()->notifications()->where('is_read', false)->count(); @endphp
        <nav class="sidebar-nav">
            <div class="nav-label">Menu Utama</div>
            <div class="nav-item">
                <a href="{{ route('staff.dashboard') }}" class="nav-link {{ request()->routeIs('staff.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2-fill icon"></i> <span>Beranda</span>
                </a>
            </div>

            <div class="nav-label">Kehadiran</div>
            <div class="nav-item {{ request()->routeIs('staff.attendance.*') ? 'open' : '' }}">
                <a class="nav-link {{ request()->routeIs('staff.attendance.*') ? 'active' : '' }}" data-toggle="submenu">
                    <i class="bi bi-fingerprint icon"></i> <span>Absensi</span> <i class="bi bi-chevron-right arrow"></i>
                </a>
                <div class="submenu">
                    <a href="{{ route('staff.attendance.index') }}" class="sub-link {{ request()->routeIs('staff.attendance.index') ? 'active' : '' }}">Absen Hari Ini</a>
                    <a href="{{ route('staff.attendance.index', ['view' => 'history']) }}" class="sub-link">Riwayat Kehadiran</a>
                </div>
            </div>
            <div class="nav-item {{ request()->routeIs('staff.leave.*') ? 'open' : '' }}">
                <a class="nav-link {{ request()->routeIs('staff.leave.*') ? 'active' : '' }}" data-toggle="submenu">
                    <i class="bi bi-calendar2-check icon"></i> <span>Pengajuan Izin</span> <i class="bi bi-chevron-right arrow"></i>
                </a>
                <div class="submenu">
                    <a href="{{ route('staff.leave.index') }}" class="sub-link {{ request()->routeIs('staff.leave.index') ? 'active' : '' }}">Daftar Izin</a>
                    <a href="{{ route('staff.leave.create') }}" class="sub-link {{ request()->routeIs('staff.leave.create') ? 'active' : '' }}">Ajukan Izin Baru</a>
                </div>
            </div>

            <div class="nav-label">Administrasi</div>
            <div class="nav-item {{ request()->routeIs('staff.surat.*') ? 'open' : '' }}">
                <a class="nav-link {{ request()->routeIs('staff.surat.*') ? 'active' : '' }}" data-toggle="submenu">
                    <i class="bi bi-envelope-paper-fill icon"></i> <span>Surat Menyurat</span> <i class="bi bi-chevron-right arrow"></i>
                </a>
                <div class="submenu">
                    <a href="{{ route('staff.surat.index') }}" class="sub-link {{ request()->routeIs('staff.surat.index') ? 'active' : '' }}">Semua Surat</a>
                    <a href="{{ route('staff.surat.create', ['jenis'=>'keluar']) }}" class="sub-link {{ request()->routeIs('staff.surat.create') ? 'active' : '' }}">Buat Surat</a>
                </div>
            </div>
            <div class="nav-item {{ request()->routeIs('staff.report.*') ? 'open' : '' }}">
                <a class="nav-link {{ request()->routeIs('staff.report.*') ? 'active' : '' }}" data-toggle="submenu">
                    <i class="bi bi-journal-text icon"></i> <span>Laporan</span> <i class="bi bi-chevron-right arrow"></i>
                </a>
                <div class="submenu">
                    <a href="{{ route('staff.report.index') }}" class="sub-link {{ request()->routeIs('staff.report.index') ? 'active' : '' }}">Semua Laporan</a>
                    <a href="{{ route('staff.report.create') }}" class="sub-link {{ request()->routeIs('staff.report.create') ? 'active' : '' }}">Buat Laporan</a>
                </div>
            </div>
            <div class="nav-item {{ request()->routeIs('staff.document.*') ? 'open' : '' }}">
                <a class="nav-link {{ request()->routeIs('staff.document.*') ? 'active' : '' }}" data-toggle="submenu">
                    <i class="bi bi-archive-fill icon"></i> <span>Dokumen & Arsip</span> <i class="bi bi-chevron-right arrow"></i>
                </a>
                <div class="submenu">
                    <a href="{{ route('staff.document.index') }}" class="sub-link {{ request()->routeIs('staff.document.index') ? 'active' : '' }}">Semua Dokumen</a>
                    <a href="{{ route('staff.document.index', ['category' => 'surat']) }}" class="sub-link">Surat Menyurat</a>
                    <a href="{{ route('staff.document.index', ['category' => 'administrasi']) }}" class="sub-link">Administrasi</a>
                </div>
            </div>

            <div class="nav-label">Akademik & Data</div>
            <div class="nav-item {{ request()->routeIs('staff.kurikulum.*') ? 'open' : '' }}">
                <a class="nav-link {{ request()->routeIs('staff.kurikulum.*') ? 'active' : '' }}" data-toggle="submenu">
                    <i class="bi bi-book-half icon"></i> <span>Kurikulum</span> <i class="bi bi-chevron-right arrow"></i>
                </a>
                <div class="submenu">
                    <a href="{{ route('staff.kurikulum.index') }}" class="sub-link {{ request()->routeIs('staff.kurikulum.index') ? 'active' : '' }}">Dokumen Kurikulum</a>
                </div>
            </div>
            <div class="nav-item {{ request()->routeIs('staff.kesiswaan.*') ? 'open' : '' }}">
                <a class="nav-link {{ request()->routeIs('staff.kesiswaan.*') ? 'active' : '' }}" data-toggle="submenu">
                    <i class="bi bi-mortarboard-fill icon"></i> <span>Kesiswaan</span> <i class="bi bi-chevron-right arrow"></i>
                </a>
                <div class="submenu">
                    <a href="{{ route('staff.kesiswaan.index') }}" class="sub-link {{ request()->routeIs('staff.kesiswaan.index') ? 'active' : '' }}">Data Siswa</a>
                </div>
            </div>
            <div class="nav-item {{ request()->routeIs('staff.inventaris.*') ? 'open' : '' }}">
                <a class="nav-link {{ request()->routeIs('staff.inventaris.*') ? 'active' : '' }}" data-toggle="submenu">
                    <i class="bi bi-box-seam-fill icon"></i> <span>Inventaris</span> <i class="bi bi-chevron-right arrow"></i>
                </a>
                <div class="submenu">
                    <a href="{{ route('staff.inventaris.index') }}" class="sub-link {{ request()->routeIs('staff.inventaris.index') ? 'active' : '' }}">Daftar Inventaris</a>
                </div>
            </div>

            <div class="nav-label">Evaluasi & Penilaian</div>
            <div class="nav-item {{ request()->routeIs('staff.evaluasi.*') ? 'open' : '' }}">
                <a class="nav-link {{ request()->routeIs('staff.evaluasi.*') ? 'active' : '' }}" data-toggle="submenu">
                    <i class="bi bi-clipboard2-data-fill icon"></i> <span>Evaluasi</span> <i class="bi bi-chevron-right arrow"></i>
                </a>
                <div class="submenu">
                    <a href="{{ route('staff.evaluasi.pkg') }}" class="sub-link {{ request()->routeIs('staff.evaluasi.pkg*') ? 'active' : '' }}">PKG / BKD Saya</a>
                    <a href="{{ route('staff.evaluasi.p5') }}" class="sub-link {{ request()->routeIs('staff.evaluasi.p5*') ? 'active' : '' }}">Asesmen P5</a>
                    <a href="{{ route('staff.evaluasi.star') }}" class="sub-link {{ request()->routeIs('staff.evaluasi.star*') ? 'active' : '' }}">Metode STAR</a>
                    <a href="{{ route('staff.evaluasi.bukti-fisik') }}" class="sub-link {{ request()->routeIs('staff.evaluasi.bukti-fisik*') ? 'active' : '' }}">Bukti Fisik</a>
                    <a href="{{ route('staff.evaluasi.learning') }}" class="sub-link {{ request()->routeIs('staff.evaluasi.learning*') ? 'active' : '' }}">Model Pembelajaran</a>
                </div>
            </div>

            <div class="nav-label">Kegiatan</div>
            <div class="nav-item {{ request()->routeIs('staff.event.*') ? 'open' : '' }}">
                <a class="nav-link {{ request()->routeIs('staff.event.*') ? 'active' : '' }}" data-toggle="submenu">
                    <i class="bi bi-calendar-event-fill icon"></i> <span>Agenda & Event</span> <i class="bi bi-chevron-right arrow"></i>
                </a>
                <div class="submenu">
                    <a href="{{ route('staff.event.index') }}" class="sub-link {{ request()->routeIs('staff.event.index') ? 'active' : '' }}">Semua Event</a>
                    <a href="{{ route('staff.event.index', ['type' => 'rapat']) }}" class="sub-link">Rapat</a>
                    <a href="{{ route('staff.event.index', ['type' => 'kegiatan']) }}" class="sub-link">Kegiatan</a>
                </div>
            </div>

            <div class="nav-label">Informasi</div>
            <div class="nav-item {{ request()->routeIs('staff.reminder.*') ? 'open' : '' }}">
                <a class="nav-link {{ request()->routeIs('staff.reminder.*') ? 'active' : '' }}" data-toggle="submenu">
                    <i class="bi bi-alarm-fill icon"></i> <span>Pengingat</span>
                    @php $myReminders = \App\Models\Reminder::where('user_id', Auth::id())->where('is_completed', false)->where('due_date', '<', now())->count(); @endphp
                    @if($myReminders > 0)<span class="badge bg-warning text-dark">{{ $myReminders }}</span>@endif
                    <i class="bi bi-chevron-right arrow"></i>
                </a>
                <div class="submenu">
                    <a href="{{ route('staff.reminder.index') }}" class="sub-link {{ request()->routeIs('staff.reminder.index') ? 'active' : '' }}">Pengingat Saya</a>
                </div>
            </div>
            <div class="nav-item {{ request()->routeIs('staff.notification.*') ? 'open' : '' }}">
                <a class="nav-link {{ request()->routeIs('staff.notification.*') ? 'active' : '' }}" data-toggle="submenu">
                    <i class="bi bi-bell-fill icon"></i> <span>Notifikasi</span>
                    @if($unread > 0)<span class="badge bg-danger">{{ $unread }}</span>@endif
                    <i class="bi bi-chevron-right arrow"></i>
                </a>
                <div class="submenu">
                    <a href="{{ route('staff.notification.index') }}" class="sub-link {{ request()->routeIs('staff.notification.index') ? 'active' : '' }}">Semua Notifikasi</a>
                    <a href="{{ route('staff.notification.index', ['filter' => 'unread']) }}" class="sub-link">Belum Dibaca</a>
                </div>
            </div>
            <div class="nav-item {{ request()->routeIs('staff.profile.*') ? 'open' : '' }}">
                <a class="nav-link {{ request()->routeIs('staff.profile.*') ? 'active' : '' }}" data-toggle="submenu">
                    <i class="bi bi-person-gear icon"></i> <span>Akun Saya</span> <i class="bi bi-chevron-right arrow"></i>
                </a>
                <div class="submenu">
                    <a href="{{ route('staff.profile.edit') }}" class="sub-link {{ request()->routeIs('staff.profile.edit') ? 'active' : '' }}">Edit Profil</a>
                    <a href="{{ route('staff.profile.edit') }}#ubah-password" class="sub-link">Ubah Password</a>
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
            <span class="header-title">@yield('title', 'Beranda')</span>
            <div class="header-right">
                <span class="header-date d-none d-md-block"><i class="bi bi-calendar3"></i> {{ now()->translatedFormat('d F Y') }}</span>
                <div class="dropdown" id="notifDropdown">
                    <button class="notif-btn" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false" id="notifToggle">
                        <i class="bi bi-bell"></i>
                        @if($unread > 0)<span class="notif-badge" id="notifBadge"></span>@endif
                    </button>
                    <div class="dropdown-menu dropdown-menu-end p-0" style="width:360px;max-height:440px;border-radius:14px!important;overflow:hidden;">
                        <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom" style="background:#f8fafc;">
                            <h6 class="mb-0 fw-bold" style="font-size:.85rem;">Notifikasi</h6>
                            <div class="d-flex gap-2">
                                <span class="badge bg-danger" id="notifCount" style="font-size:.65rem;">{{ $unread }}</span>
                                <form action="{{ route('staff.notification.read-all') }}" method="POST" class="d-inline">@csrf @method('PATCH')
                                    <button type="submit" class="btn btn-link btn-sm p-0 text-primary text-decoration-none" style="font-size:.72rem;">Tandai semua</button>
                                </form>
                            </div>
                        </div>
                        <div id="notifList" style="max-height:320px;overflow-y:auto;">
                            <div class="text-center py-4 text-muted" style="font-size:.8rem;" id="notifLoading">
                                <div class="spinner-border spinner-border-sm text-primary me-1"></div> Memuat...
                            </div>
                        </div>
                        <div class="border-top text-center py-2" style="background:#f8fafc;">
                            <a href="{{ route('staff.notification.index') }}" class="text-primary text-decoration-none fw-semibold" style="font-size:.78rem;">Lihat Semua Notifikasi <i class="bi bi-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
                <div class="dropdown">
                    <button type="button" class="header-profile" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="avatar-sm">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
                        <span class="name d-none d-md-block">{{ Auth::user()->name }}</span>
                        <i class="bi bi-chevron-down" style="font-size:.65rem;color:#94a3b8;"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('staff.profile.edit') }}"><i class="bi bi-person me-2 text-success"></i>Profil Saya</a></li>
                        <li><a class="dropdown-item" href="{{ route('staff.profile.edit') }}#ubah-password"><i class="bi bi-key me-2 text-warning"></i>Ubah Password</a></li>
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
            @yield('content')
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
            else { body.classList.toggle('sidebar-collapsed'); localStorage.setItem('sidebar-staff', body.classList.contains('sidebar-collapsed') ? 'c' : 'e'); }
        });
        if (!isMobile() && localStorage.getItem('sidebar-staff') === 'c') body.classList.add('sidebar-collapsed');
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
            Swal.fire({ title: 'Konfirmasi', text: btn.dataset.confirm || 'Yakin?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#6366f1', cancelButtonColor: '#94a3b8', confirmButtonText: 'Ya', cancelButtonText: 'Batal', reverseButtons: true })
            .then(r => { if (r.isConfirmed) { const f = btn.closest('form'); if (f) f.submit(); else location.href = btn.href; } });
        });
        setTimeout(() => { document.querySelectorAll('.alert').forEach(a => { try { new bootstrap.Alert(a).close(); } catch(e){} }); }, 4000);

        // Notification dropdown AJAX
        const notifToggle = document.getElementById('notifToggle');
        if (notifToggle) {
            notifToggle.addEventListener('show.bs.dropdown', loadNotifications);
        }
        function loadNotifications() {
            const list = document.getElementById('notifList');
            list.innerHTML = '<div class="text-center py-4 text-muted" style="font-size:.8rem;"><div class="spinner-border spinner-border-sm text-primary me-1"></div> Memuat...</div>';
            fetch('{{ route("staff.notification.json") }}', { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
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
        // Auto-refresh notification count every 30s
        setInterval(() => {
            fetch('{{ route("staff.notification.json") }}', { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
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
