<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('judul', 'Beranda') - TU Staff</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}">

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    {{-- SweetAlert2 --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        :root {
            --primary: #10b981;
            --primary-dark: #059669;
            --primary-light: #34d399;
            --secondary: #06b6d4;
            --sidebar-bg: linear-gradient(180deg, #0f172a 0%, #1e293b 100%);
            --sidebar-width: 270px;
            --body-bg: #f0f2f8;
            --card-radius: 14px;
            --text-dark: #1e293b;
            --text-muted: #64748b;
            --white: #ffffff;
            --border-color: #e2e8f0;
            --shadow-sm: 0 1px 3px rgba(0,0,0,.08);
            --shadow-md: 0 4px 12px rgba(0,0,0,.1);
            --shadow-lg: 0 8px 30px rgba(0,0,0,.12);
            --transition: .25s cubic-bezier(.4,0,.2,1);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--body-bg);
            color: var(--text-dark);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* ── Layout ── */
        .app-wrapper { display: flex; min-height: 100vh; }

        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            transition: margin-left var(--transition);
        }

        .main-body { flex: 1; padding: 24px 28px 32px; }

        /* ── Sidebar collapsed ── */
        body.sidebar-collapsed .sidebar { width: 0; overflow: hidden; opacity: 0; pointer-events: none; }
        body.sidebar-collapsed .main-content { margin-left: 0; }

        /* ── Cards ── */
        .card {
            background: var(--white);
            border-radius: var(--card-radius);
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-color);
            transition: box-shadow var(--transition);
        }
        .card:hover { box-shadow: var(--shadow-md); }
        .card-header {
            padding: 18px 22px;
            border-bottom: 1px solid var(--border-color);
            font-weight: 600;
            font-size: .95rem;
        }
        .card-body { padding: 22px; }

        /* ── Buttons ── */
        .btn-primary-custom {
            background: var(--primary);
            color: #fff;
            border: none;
            padding: 10px 22px;
            border-radius: 8px;
            font-weight: 600;
            font-size: .875rem;
            cursor: pointer;
            transition: all var(--transition);
        }
        .btn-primary-custom:hover { background: var(--primary-dark); transform: translateY(-1px); box-shadow: 0 4px 12px rgba(16,185,129,.35); }

        .btn-secondary-custom {
            background: var(--white);
            color: var(--text-dark);
            border: 1px solid var(--border-color);
            padding: 10px 22px;
            border-radius: 8px;
            font-weight: 500;
            font-size: .875rem;
            cursor: pointer;
            transition: all var(--transition);
        }
        .btn-secondary-custom:hover { border-color: var(--primary); color: var(--primary); }

        /* ── Focus rings ── */
        input:focus, select:focus, textarea:focus, button:focus-visible {
            outline: 3px solid rgba(16,185,129,.35);
            outline-offset: 2px;
        }

        /* ── Form controls ── */
        .form-control-custom {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: .875rem;
            transition: border-color var(--transition);
        }
        .form-control-custom:focus { border-color: var(--primary); }

        /* ── Stat cards ── */
        .stat-card {
            border-radius: var(--card-radius);
            padding: 22px;
            box-shadow: var(--shadow-sm);
            border: none;
            position: relative;
            overflow: hidden;
            color: #fff;
        }
        .stat-card p { margin: 0; font-size: .82rem; opacity: .9; }
        .stat-card h3 { margin: 4px 0; font-size: 1.6rem; font-weight: 700; }
        .stat-card .icon-box {
            width: 48px; height: 48px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            background: rgba(255,255,255,.2);
            color: #fff;
            font-size: 1.3rem;
        }
        }

        /* ── Table ── */
        .table-custom { width: 100%; border-collapse: collapse; }
        .table-custom th {
            background: #f8fafc;
            padding: 12px 16px;
            font-size: .8rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: .05em;
            text-align: left;
            border-bottom: 2px solid var(--border-color);
        }
        .table-custom td {
            padding: 12px 16px;
            font-size: .875rem;
            border-bottom: 1px solid var(--border-color);
            vertical-align: middle;
        }
        .table-custom tbody tr:hover { background: rgba(16,185,129,.04); }

        /* ── Badges ── */
        .badge-custom {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: .75rem;
            font-weight: 600;
        }
        .badge-success { background: rgba(16,185,129,.12); color: #059669; }
        .badge-warning { background: rgba(245,158,11,.12); color: #d97706; }
        .badge-danger  { background: rgba(239,68,68,.12); color: #dc2626; }
        .badge-info    { background: rgba(6,182,212,.12); color: #0891b2; }

        /* ── Pagination ── */
        .pagination { display: flex; gap: 4px; list-style: none; padding: 0; }
        .pagination .page-item .page-link {
            display: flex; align-items: center; justify-content: center;
            min-width: 36px; height: 36px;
            border-radius: 8px;
            font-size: .85rem;
            font-weight: 500;
            color: var(--text-muted);
            border: 1px solid var(--border-color);
            background: var(--white);
            text-decoration: none;
            transition: all var(--transition);
        }
        .pagination .page-item .page-link:hover { border-color: var(--primary); color: var(--primary); }
        .pagination .page-item.active .page-link { background: var(--primary); color: #fff; border-color: var(--primary); }

        /* ── Alerts ── */
        .alert-custom {
            padding: 14px 18px;
            border-radius: 10px;
            font-size: .875rem;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 18px;
            animation: slideIn .3s ease;
        }
        .alert-success { background: rgba(16,185,129,.1); border: 1px solid rgba(16,185,129,.25); color: #065f46; }
        .alert-danger  { background: rgba(239,68,68,.1); border: 1px solid rgba(239,68,68,.25); color: #991b1b; }
        .alert-warning { background: rgba(245,158,11,.1); border: 1px solid rgba(245,158,11,.25); color: #92400e; }
        .alert-info    { background: rgba(6,182,212,.1); border: 1px solid rgba(6,182,212,.25); color: #155e75; }

        @keyframes slideIn { from { opacity: 0; transform: translateY(-8px); } to { opacity: 1; transform: translateY(0); } }

        /* ── Responsive ── */
        @media (max-width: 1024px) {
            .sidebar { transform: translateX(-100%); position: fixed; z-index: 1050; }
            body.sidebar-open .sidebar { transform: translateX(0); opacity: 1; pointer-events: auto; width: var(--sidebar-width); }
            .main-content { margin-left: 0 !important; }
            .sidebar-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.4); z-index: 1040; }
            body.sidebar-open .sidebar-overlay { display: block; }
        }

        @media (max-width: 640px) {
            .main-body { padding: 16px 14px 24px; }
        }

        /* ── Scrollbar ── */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        @yield('styles')
    </style>
</head>
<body>
    <div class="app-wrapper">
        {{-- Sidebar --}}
        @include('staf.tata-letak._sidebar')

        {{-- Overlay mobile --}}
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        {{-- Main Content --}}
        <div class="main-content">
            @include('staf.tata-letak._header')

            <main class="main-body">
                {{-- Flash Messages --}}
                @if(session('success'))
                    <div class="alert-custom alert-success auto-dismiss">
                        <i class="fas fa-check-circle"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert-custom alert-danger auto-dismiss">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif
                @if(session('warning'))
                    <div class="alert-custom alert-warning auto-dismiss">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span>{{ session('warning') }}</span>
                    </div>
                @endif
                @if(session('info'))
                    <div class="alert-custom alert-info auto-dismiss">
                        <i class="fas fa-info-circle"></i>
                        <span>{{ session('info') }}</span>
                    </div>
                @endif

                @yield('konten')
            </main>

            @include('staf.tata-letak._footer')
        </div>
    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @yield('scripts')
</body>
</html>
