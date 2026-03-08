<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('judul', 'Beranda') - TU Admin</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}">
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
        .sidebar-brand {
            padding: 0 16px; height: var(--header-h); border-bottom: 1px solid rgba(255,255,255,.08);
            display: flex; align-items: center; justify-content: space-between; flex-shrink: 0;
        }
        .sidebar-brand .d-flex { min-width: 0; }
        .sidebar-brand img { width: 40px; height: 40px; border-radius: 10px; object-fit: cover; border: 2px solid rgba(255,255,255,.15); flex-shrink: 0; }
        .sidebar-brand h6 { color: #fff; font-size: .82rem; font-weight: 600; margin: 0; line-height: 1.3; }
        .sidebar-brand small { font-size: .65rem; color: #a5b4fc; }

        .sidebar-profile {
            padding: 14px 16px; border-bottom: 1px solid rgba(255,255,255,.08);
            display: flex; align-items: center; gap: 10px;
        }
        .sidebar-profile .avatar {
            width: 38px; height: 38px; border-radius: 10px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-weight: 600; font-size: .8rem; flex-shrink: 0;
        }
        .sidebar-profile .info { overflow: hidden; flex: 1; }
        .sidebar-profile .info .name { color: #fff; font-size: .78rem; font-weight: 600; white-space: nowrap; text-overflow: ellipsis; overflow: hidden; }
        .sidebar-profile .info .role { font-size: .65rem; color: #a5b4fc; }
        .sidebar-profile .status { width: 8px; height: 8px; border-radius: 50%; background: #34d399; flex-shrink: 0; }

        .sidebar-nav { flex: 1; overflow-y: overlay; overflow-y: auto; padding: 8px 0; scroll-behavior: smooth; }
        .sidebar-nav::-webkit-scrollbar { width: 3px; }
        .sidebar-nav::-webkit-scrollbar-track { background: transparent; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,.12); border-radius: 3px; }
        .sidebar-nav:hover::-webkit-scrollbar-thumb { background: rgba(255,255,255,.22); }
        .nav-item { position: relative; }
        .nav-link {
            display: flex; align-items: center; padding: 8px 16px; color: #c7d2fe; text-decoration: none;
            font-size: .8rem; font-weight: 400; transition: all .2s; gap: 10px; cursor: pointer;
            border-left: 3px solid transparent; margin: 1px 0;
        }
        .nav-link:hover { background: rgba(99,102,241,.12); color: #e0e7ff; }
        .nav-link.active, .nav-link.active:hover { background: rgba(99,102,241,.18); color: #fff; border-left-color: var(--primary-light); font-weight: 500; }
        .nav-link i.icon { font-size: 1rem; width: 20px; text-align: center; flex-shrink: 0; }
        .nav-link .arrow { margin-left: auto; font-size: .65rem; transition: transform .25s; flex-shrink: 0; }
        .nav-item.open > .nav-link .arrow { transform: rotate(90deg); }
        .nav-link .badge { font-size: .58rem; padding: 2px 6px; border-radius: 4px; }
        .nav-link .count-pill {
            margin-left: auto; font-size: .62rem; font-weight: 600; padding: 1px 7px;
            border-radius: 10px; background: rgba(99,102,241,.25); color: #c7d2fe; line-height: 1.5;
        }

        .submenu { max-height: 0; overflow: hidden; transition: max-height .35s cubic-bezier(.4,0,.2,1); background: rgba(0,0,0,.1); }
        .nav-item.open > .submenu { max-height: 500px; }
        .submenu .sub-link {
            display: flex; align-items: center; padding: 6px 16px 6px 46px; color: #a5b4fc; font-size: .76rem;
            text-decoration: none; transition: all .2s; gap: 8px;
        }
        .submenu .sub-link .sub-icon { font-size: .72rem; width: 16px; text-align: center; flex-shrink: 0; opacity: .7; }
        .submenu .sub-link:hover { color: #fff; background: rgba(99,102,241,.1); }
        .submenu .sub-link:hover .sub-icon { opacity: 1; }
        .submenu .sub-link.active { color: #fff; font-weight: 500; }
        .submenu .sub-link.active .sub-icon { opacity: 1; }
        .submenu .sub-link .badge { font-size: .55rem; padding: 1px 5px; }

        .sidebar-footer { padding: 0 16px; height: var(--header-h); border-top: 1px solid rgba(255,255,255,.08); display: flex; align-items: center; flex-shrink: 0; }
        .sidebar-footer a { color: #ef4444; font-size: .78rem; text-decoration: none; display: flex; align-items: center; gap: 8px; }
        .sidebar-footer a:hover { color: #fca5a5; }

        /* ── Main Content ── */
        .main-content { margin-left: var(--sidebar-w); transition: margin .3s cubic-bezier(.4,0,.2,1); min-height: 100vh; overflow-x: hidden; max-width: 100vw; }

        /* ── Top Header (Clean White) ── */
        .top-header {
            position: sticky; top: 0; z-index: 1030; height: var(--header-h);
            background: #fff;
            display: flex; align-items: center; padding: 0 24px; gap: 16px;
            box-shadow: 0 1px 3px rgba(0,0,0,.08); border-bottom: 1px solid #e5e7eb;
        }
        .sidebar-toggle { background: none; border: none; font-size: 1.3rem; color: #4b5563; cursor: pointer; padding: 6px; border-radius: 8px; transition: .2s; }
        .sidebar-toggle:hover { background: #f3f4f6; color: var(--primary); }
        .header-title { font-size: .9rem; font-weight: 600; color: #1f2937; }
        .header-right { margin-left: auto; display: flex; align-items: center; gap: 8px; }
        .header-date { font-size: .78rem; color: #6b7280; display: flex; align-items: center; gap: 6px; }
        .header-date:hover { background: #ede9fe; color: var(--primary); border-color: #c4b5fd; }
        .header-tool-btn, .header-toggle-btn { position: relative; background: #f3f4f6; border: 1px solid #e5e7eb; width: 38px; height: 38px; border-radius: 10px; font-size: 1.1rem; color: #4b5563; cursor: pointer; transition: .2s; display: flex; align-items: center; justify-content: center; }
        .header-tool-btn:hover, .header-toggle-btn:hover { background: #ede9fe; color: var(--primary); border-color: #c4b5fd; }
        .header-toggle-btn.active { background: #ede9fe; color: var(--primary); border-color: #c4b5fd; }
        .notif-btn { position: relative; background: #f3f4f6; border: 1px solid #e5e7eb; width: 38px; height: 38px; border-radius: 10px; font-size: 1.1rem; color: #4b5563; cursor: pointer; transition: .2s; display: flex; align-items: center; justify-content: center; }
        .notif-btn:hover { background: #ede9fe; color: var(--primary); border-color: #c4b5fd; }
        .notif-badge { position: absolute; top: 4px; right: 4px; width: 8px; height: 8px; background: #ef4444; border-radius: 50%; border: 2px solid #fff; }
        .header-profile { display: flex; align-items: center; gap: 8px; cursor: pointer; padding: 4px 10px; border-radius: 10px; transition: .2s; border: 1px solid transparent; background: none; }
        .header-profile:hover { background: #f3f4f6; border-color: #e5e7eb; }
        .header-profile .avatar-sm { width: 34px; height: 34px; border-radius: 8px; background: linear-gradient(135deg, var(--primary), var(--secondary)); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: .75rem; }
        .header-profile .name { font-size: .8rem; font-weight: 500; color: #1f2937; }
        .header-profile .role-tag { font-size: .6rem; color: #6b7280; display: block; font-weight: 400; }

        /* ── Sidebar Search ── */
        .sidebar-search { padding: 12px 16px; border-bottom: 1px solid rgba(255,255,255,.08); position: relative; }
        .sidebar-search i { position: absolute; left: 28px; top: 50%; transform: translateY(-50%); color: #64748b; font-size: .82rem; pointer-events: none; }
        .sidebar-search input {
            width: 100%; background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.1);
            border-radius: 8px; padding: 8px 12px 8px 34px; color: #e0e7ff;
            font-size: .78rem; outline: none; transition: all .2s; font-family: inherit;
        }
        .sidebar-search input::placeholder { color: #64748b; }
        .sidebar-search input:focus { background: rgba(255,255,255,.12); border-color: rgba(99,102,241,.4); }

        /* ── Nav Groups (Collapsible) ── */
        .nav-group { border-bottom: 1px solid rgba(255,255,255,.04); }
        .nav-group-label {
            display: flex; align-items: center; padding: 10px 16px 4px; cursor: pointer; user-select: none; gap: 6px;
        }
        .nav-group-label span:first-child { font-size: .63rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1.2px; color: #6366f1; }
        .nav-group-label .group-badge {
            font-size: .55rem; font-weight: 600; padding: 1px 6px; border-radius: 8px;
            background: rgba(99,102,241,.25); color: #a5b4fc; line-height: 1.5;
        }
        .nav-group-label .group-badge.bg-info { background: rgba(56,189,248,.25); color: #7dd3fc; }
        .nav-group-label .group-badge.bg-warning { background: rgba(251,191,36,.25); color: #fcd34d; }
        .nav-group-label i { font-size: .55rem; color: #6366f1; margin-left: auto; transition: transform .25s; flex-shrink: 0; }
        .nav-group.open > .nav-group-label i { transform: rotate(180deg); }
        .nav-group-desc {
            padding: 0 16px 6px; font-size: .62rem; color: #7c7fb8; line-height: 1.4;
            max-height: 0; overflow: hidden; transition: max-height .3s ease, opacity .3s ease; opacity: 0;
        }
        .nav-group.open > .nav-group-desc { max-height: 40px; opacity: 1; }
        .nav-group-items { max-height: 0; overflow: hidden; transition: max-height .35s cubic-bezier(.4,0,.2,1); }
        .nav-group.open > .nav-group-items { max-height: 2000px; }
        .nav-group.search-match > .nav-group-items { max-height: 2000px; }

        /* ── Page Content ── */
        .page-content { padding: 24px; overflow-x: hidden; }

        /* ── Header Dropdown ── */
        .dropdown-menu { min-width: 200px; padding: 6px; border-radius: 12px !important; border: 1px solid #e2e8f0; box-shadow: 0 8px 24px rgba(0,0,0,.1) !important; }
        .dropdown-item { border-radius: 8px; font-size: .82rem; padding: 9px 14px; color: #374151; font-weight: 500; transition: all .15s; }
        .dropdown-item:hover { background: #f0f2f8; color: var(--primary); }
        .dropdown-item.text-danger:hover { background: #fef2f2; color: #ef4444; }
        .dropdown-divider { margin: 4px 0; border-color: #f1f5f9; }

        /* ── Pagination ── */
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

        /* ── Settings Right Drawer ── */
        .settings-drawer {
            position: fixed; top: 0; right: 0; width: 340px; height: 100vh;
            background: #fff; z-index: 1060; transform: translateX(100%);
            transition: transform .3s cubic-bezier(.4,0,.2,1);
            display: flex; flex-direction: column;
            box-shadow: -4px 0 24px rgba(0,0,0,.12);
        }
        .settings-drawer.open { transform: translateX(0); }
        .settings-drawer-overlay {
            position: fixed; inset: 0; background: rgba(0,0,0,.35);
            z-index: 1059; opacity: 0; pointer-events: none; transition: opacity .3s;
        }
        .settings-drawer-overlay.open { opacity: 1; pointer-events: auto; }
        .sd-header {
            padding: 18px 20px; border-bottom: 1px solid #e5e7eb;
            display: flex; justify-content: space-between; align-items: center; flex-shrink: 0;
            background: linear-gradient(135deg, #6366f1, #818cf8);
        }
        .sd-header h6 { color: #fff; font-size: .88rem; margin: 0; font-weight: 700; }
        .sd-close { background: rgba(255,255,255,.2); border: none; color: #fff; width: 30px; height: 30px; border-radius: 8px; cursor: pointer; font-size: .9rem; display: flex; align-items: center; justify-content: center; transition: .2s; }
        .sd-close:hover { background: rgba(255,255,255,.35); }
        .sd-body { flex: 1; overflow-y: auto; padding: 0; }
        .sd-section { padding: 14px 20px; border-bottom: 1px solid #f1f5f9; }
        .sd-section-title { font-size: .65rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #6366f1; margin-bottom: 10px; }
        .sd-widget {
            display: flex; align-items: center; gap: 12px; padding: 10px 14px;
            border-radius: 12px; cursor: pointer; transition: .15s; text-decoration: none; color: #374151; font-size: .82rem;
        }
        .sd-widget:hover { background: #f0f2ff; color: #4f46e5; }
        .sd-widget i { font-size: 1.1rem; width: 22px; text-align: center; }
        .sd-widget .sd-desc { font-size: .68rem; color: #9ca3af; margin-top: 1px; }
        .sd-widget .sd-badge { margin-left: auto; font-size: .6rem; padding: 2px 8px; border-radius: 10px; font-weight: 600; }
        .sd-toggle { margin-left: auto; }
        .sd-toggle input[type="checkbox"] { display: none; }
        .sd-toggle label {
            width: 38px; height: 22px; background: #d1d5db; border-radius: 20px;
            display: block; position: relative; cursor: pointer; transition: .2s;
        }
        .sd-toggle label::after {
            content: ''; width: 18px; height: 18px; background: #fff; border-radius: 50%;
            position: absolute; top: 2px; left: 2px; transition: .2s; box-shadow: 0 1px 3px rgba(0,0,0,.15);
        }
        .sd-toggle input:checked + label { background: #6366f1; }
        .sd-toggle input:checked + label::after { left: 18px; }
        .sd-stat-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-top: 8px; }
        .sd-stat-card {
            padding: 12px; border-radius: 10px; text-align: center; text-decoration: none;
            transition: .15s; border: 1px solid #f1f5f9;
        }
        .sd-stat-card:hover { border-color: #c7d2fe; background: #faf5ff; transform: translateY(-1px); }
        .sd-stat-card .sd-stat-num { font-size: 1.2rem; font-weight: 700; color: #1f2937; }
        .sd-stat-card .sd-stat-label { font-size: .65rem; color: #6b7280; margin-top: 2px; }
        .sd-stat-card i { font-size: 1.3rem; margin-bottom: 4px; }

        @media (max-width: 767px) {
            .settings-drawer { width: 100vw; }
        }

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
    {{-- Sidebar --}}
    @include('peran.admin.sidebar')

    {{-- ═══ Settings Right Drawer ═══ --}}
    <div class="settings-drawer-overlay" id="settingsOverlay"></div>
    <div class="settings-drawer" id="settingsDrawer">
        <div class="sd-header">
            <h6><i class="bi bi-lightning-charge-fill me-2"></i>Pengaturan & Alat Cepat</h6>
            <button class="sd-close" id="closeSettingsDrawer"><i class="bi bi-x-lg"></i></button>
        </div>
        <div class="sd-body">
            {{-- Quick Stats --}}
            <div class="sd-section">
                <div class="sd-section-title">Ringkasan Cepat</div>
                <div class="sd-stat-grid">
                    <a href="{{ route('admin.pegawai.index') }}" class="sd-stat-card" style="text-decoration:none;">
                        <i class="bi bi-people-fill" style="color:#6366f1;"></i>
                        <div class="sd-stat-num">{{ \App\Models\Pengguna::whereIn('peran', \App\Models\Pengguna::STAFF_ROLES)->where('aktif', true)->count() }}</div>
                        <div class="sd-stat-label">Staf Aktif</div>
                    </a>
                    <a href="{{ route('admin.izin.index', ['status' => 'pending']) }}" class="sd-stat-card" style="text-decoration:none;">
                        <i class="bi bi-hourglass-split" style="color:#f59e0b;"></i>
                        <div class="sd-stat-num">{{ \App\Models\PengajuanIzin::where('status', 'pending')->count() }}</div>
                        <div class="sd-stat-label">Izin Pending</div>
                    </a>
                    <a href="{{ route('admin.notifikasi.index') }}" class="sd-stat-card" style="text-decoration:none;">
                        <i class="bi bi-bell-fill" style="color:#ef4444;"></i>
                        <div class="sd-stat-num">{{ \App\Models\Notifikasi::where('sudah_dibaca', false)->count() }}</div>
                        <div class="sd-stat-label">Notifikasi Baru</div>
                    </a>
                    <a href="{{ route('admin.inventaris.index') }}" class="sd-stat-card" style="text-decoration:none;">
                        <i class="bi bi-box-seam-fill" style="color:#10b981;"></i>
                        <div class="sd-stat-num">{{ \App\Models\Inventaris::count() }}</div>
                        <div class="sd-stat-label">Inventaris</div>
                    </a>
                </div>
            </div>

            {{-- Tampilan --}}
            <div class="sd-section">
                <div class="sd-section-title">Tampilan</div>
                <div class="sd-widget">
                    <i class="bi bi-moon-stars" style="color:#6366f1;"></i>
                    <div><div>Mode Gelap</div><div class="sd-desc">Tema gelap untuk kenyamanan mata</div></div>
                    <div class="sd-toggle ms-auto">
                        <input type="checkbox" id="darkModeToggle"><label for="darkModeToggle"></label>
                    </div>
                </div>
            </div>

            {{-- Navigasi Cepat --}}
            <div class="sd-section">
                <div class="sd-section-title">Navigasi Cepat</div>
                <a href="{{ route('admin.pengaturan.index') }}" class="sd-widget">
                    <i class="bi bi-sliders" style="color:#6366f1;"></i>
                    <div><div>Pengaturan Sistem</div><div class="sd-desc">Profil, password, tampilan, notifikasi</div></div>
                </a>
                <a href="{{ route('admin.kehadiran.pengaturan') }}" class="sd-widget">
                    <i class="bi bi-fingerprint" style="color:#10b981;"></i>
                    <div><div>Pengaturan Absensi</div><div class="sd-desc">Radius GPS, jam kerja, toleransi</div></div>
                </a>
                <a href="{{ route('admin.ekspor.index') }}" class="sd-widget">
                    <i class="bi bi-cloud-download" style="color:#0ea5e9;"></i>
                    <div><div>Ekspor & Backup</div><div class="sd-desc">Download data, backup Google Drive</div></div>
                </a>
                <a href="{{ route('admin.log-aktivitas.index') }}" class="sd-widget">
                    <i class="bi bi-clock-history" style="color:#f59e0b;"></i>
                    <div><div>Log Aktivitas</div><div class="sd-desc">Riwayat aksi seluruh pengguna</div></div>
                </a>
            </div>

            {{-- Alat Khusus --}}
            <div class="sd-section">
                <div class="sd-section-title">Alat Khusus</div>
                <a href="{{ route('admin.disposisi.index') }}" class="sd-widget">
                    <i class="bi bi-reply-all-fill" style="color:#8b5cf6;"></i>
                    <div><div>Disposisi Surat</div><div class="sd-desc">Tindak lanjut surat masuk</div></div>
                </a>
                <a href="{{ route('admin.word-ai.create') }}" class="sd-widget">
                    <i class="bi bi-file-earmark-word-fill" style="color:#2563eb;"></i>
                    <div><div>Buat Dokumen AI</div><div class="sd-desc">Generate dokumen otomatis</div></div>
                </a>
                <a href="{{ route('admin.siatu-ai.index') }}" class="sd-widget">
                    <i class="bi bi-robot" style="color:#6366f1;"></i>
                    <div><div>SIMPEG-AI (Full)</div><div class="sd-desc">Analisis data, laporan, rekomendasi</div></div>
                </a>
            </div>

            {{-- Storage Monitor --}}
            <div class="sd-section" style="border-bottom:none;">
                <div class="sd-section-title">Penyimpanan</div>
                <div style="padding:0 14px;">
                    <div style="background:#f1f5f9;border-radius:8px;height:8px;overflow:hidden;margin-bottom:6px;">
                        <div id="drawerStorageBar" style="height:100%;border-radius:8px;background:#6366f1;width:0%;transition:width .6s;"></div>
                    </div>
                    <div class="d-flex justify-content-between" style="font-size:.68rem;color:#6b7280;">
                        <span id="drawerStorageText">Memuat...</span>
                        <span id="drawerStoragePct"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="main-content" id="mainContent">
        {{-- Top Header --}}
        @include('peran.admin.header')

        {{-- Page Content --}}
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

            @yield('konten')
        </div>

        {{-- Page Footer --}}
        <footer style="padding:20px 28px;border-top:1px solid #e5e7eb;background:#fff;margin-top:auto;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:8px;">
            <small style="color:#94a3b8;font-size:.72rem;">&copy; {{ date('Y') }} SIMPEG-SMART &mdash; SMA Negeri 2 Jember</small>
            <small style="color:#94a3b8;font-size:.72rem;">v2.0 &middot; Laravel {{ app()->version() }}</small>
        </footer>

        {{-- Footer Scripts --}}
        @include('peran.admin.footer')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // ── Nav Group Toggle ──
        document.querySelectorAll('[data-toggle="nav-group"]').forEach(label => {
            label.addEventListener('click', function() {
                const group = this.closest('.nav-group');
                const items = group.querySelector('.nav-group-items');
                const isOpening = !group.classList.contains('open');

                if (isOpening) {
                    group.classList.add('open');
                    if (items) items.style.maxHeight = items.scrollHeight + 'px';
                } else {
                    // Close all open submenus inside this group first
                    group.querySelectorAll('.nav-item.open').forEach(ni => {
                        ni.classList.remove('open');
                        const sub = ni.querySelector('.submenu');
                        if (sub) sub.style.maxHeight = '';
                    });
                    group.classList.remove('open');
                    if (items) items.style.maxHeight = '';
                }

                // Keep clicked group visible in sidebar
                setTimeout(() => {
                    this.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }, 100);
            });
        });
    });
    </script>

    {{-- ═══ AI Chat Widget (Shared Component) ═══ --}}
    @include('komponen.ai-widget')

    @include('komponen.notifikasi-popup')
    @stack('scripts')
</body>
</html>
