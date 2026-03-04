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
        .nav-link .badge { font-size: .6rem; margin-left: auto; }

        .submenu { max-height: 0; overflow: hidden; transition: max-height .35s cubic-bezier(.4,0,.2,1); background: rgba(0,0,0,.12); }
        .nav-item.open > .submenu { max-height: 400px; }
        .submenu .sub-link {
            display: flex; align-items: center; padding: 7px 20px 7px 54px; color: #a5b4fc; font-size: .78rem;
            text-decoration: none; transition: all .2s; gap: 8px;
        }
        .submenu .sub-link:hover { color: #fff; background: rgba(99,102,241,.1); }
        .submenu .sub-link.active { color: #fff; font-weight: 500; }

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
    @include('admin.tata-letak.sidebar')

    {{-- Main Content --}}
    <div class="main-content" id="mainContent">
        {{-- Top Header --}}
        @include('admin.tata-letak.header')

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

        {{-- Footer Scripts --}}
        @include('admin.tata-letak.footer')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('scripts')
</body>
</html>
