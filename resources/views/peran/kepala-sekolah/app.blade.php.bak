<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('judul', 'Beranda') - Kepala Sekolah</title>
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

        /* ── Sidebar ── */
        .sidebar {
            position: fixed; top: 0; left: 0; width: var(--sidebar-w); height: 100vh;
            background: var(--sidebar-bg); color: #d6d3d1; z-index: 1040;
            transition: transform .3s cubic-bezier(.4,0,.2,1);
            display: flex; flex-direction: column; overflow: hidden;
        }
        .sidebar-brand { padding: 20px 20px 12px; text-align: center; border-bottom: 1px solid rgba(255,255,255,.08); }
        .sidebar-brand img { width: 52px; height: 52px; border-radius: 12px; object-fit: cover; border: 2px solid rgba(255,255,255,.15); }
        .sidebar-brand h6 { color: #fff; font-size: .85rem; font-weight: 600; margin: 8px 0 2px; }
        .sidebar-brand small { font-size: .68rem; color: #a8a29e; }

        .sidebar-profile { padding: 16px 20px; border-bottom: 1px solid rgba(255,255,255,.08); display: flex; align-items: center; gap: 10px; }
        .sidebar-profile .avatar {
            width: 40px; height: 40px; border-radius: 10px; background: linear-gradient(135deg, var(--primary), var(--secondary));
            display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 600; font-size: .85rem; flex-shrink: 0;
            overflow: hidden;
        }
        .sidebar-profile .avatar img { width: 100%; height: 100%; object-fit: cover; }
        .sidebar-profile .info { overflow: hidden; }
        .sidebar-profile .info .name { color: #fff; font-size: .8rem; font-weight: 600; white-space: nowrap; text-overflow: ellipsis; overflow: hidden; }
        .sidebar-profile .info .role { font-size: .68rem; color: #a8a29e; }
        .sidebar-profile .status { width: 8px; height: 8px; border-radius: 50%; background: #34d399; margin-left: auto; flex-shrink: 0; }

        .sidebar-nav { flex: 1; overflow-y: auto; padding: 12px 0; }
        .sidebar-nav::-webkit-scrollbar { width: 4px; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,.15); border-radius: 4px; }
        .nav-label { font-size: .65rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1.2px; color: #d97706; padding: 12px 20px 6px; }
        .nav-item { position: relative; }
        .nav-link {
            display: flex; align-items: center; padding: 9px 20px; color: #d6d3d1; text-decoration: none;
            font-size: .82rem; font-weight: 400; transition: all .2s; gap: 12px; cursor: pointer; border-left: 3px solid transparent;
        }
        .nav-link:hover { background: rgba(217,119,6,.12); color: #fef3c7; }
        .nav-link.active, .nav-link.active:hover { background: rgba(217,119,6,.18); color: #fff; border-left-color: var(--primary-light); font-weight: 500; }
        .nav-link i.icon { font-size: 1.1rem; width: 22px; text-align: center; }
        .nav-link .arrow { margin-left: auto; font-size: .7rem; transition: transform .25s; }
        .nav-item.open > .nav-link .arrow { transform: rotate(90deg); }
        .nav-link .badge { font-size: .6rem; margin-left: auto; }

        .submenu { max-height: 0; overflow: hidden; transition: max-height .35s cubic-bezier(.4,0,.2,1); background: rgba(0,0,0,.12); }
        .nav-item.open > .submenu { max-height: 400px; }
        .submenu .sub-link {
            display: flex; align-items: center; padding: 7px 20px 7px 54px; color: #a8a29e; font-size: .78rem;
            text-decoration: none; transition: all .2s; gap: 8px;
        }
        .submenu .sub-link:hover { color: #fff; background: rgba(217,119,6,.1); }
        .submenu .sub-link.active { color: #fff; font-weight: 500; }

        .sidebar-footer { padding: 14px 20px; border-top: 1px solid rgba(255,255,255,.08); }
        .sidebar-footer a { color: #ef4444; font-size: .8rem; text-decoration: none; display: flex; align-items: center; gap: 8px; }
        .sidebar-footer a:hover { color: #fca5a5; }

        /* ── Main Content ── */
        .main-content { margin-left: var(--sidebar-w); transition: margin .3s cubic-bezier(.4,0,.2,1); min-height: 100vh; overflow-x: hidden; max-width: 100vw; }

        /* ── Top Header (Dark Unified) ── */
        .top-header {
            position: sticky; top: 0; z-index: 1030; height: var(--header-h);
            background: linear-gradient(90deg, #1c1917, #292524);
            display: flex; align-items: center; padding: 0 24px; gap: 16px;
            box-shadow: 0 2px 8px rgba(0,0,0,.15);
        }
        .sidebar-toggle { background: none; border: none; font-size: 1.3rem; color: #d6d3d1; cursor: pointer; padding: 6px; border-radius: 8px; transition: .2s; }
        .sidebar-toggle:hover { background: rgba(217,119,6,.2); color: #fff; }
        .header-title { font-size: .9rem; font-weight: 600; color: #fef3c7; }
        .header-right { margin-left: auto; display: flex; align-items: center; gap: 12px; }
        .header-date { font-size: .78rem; color: #a8a29e; }
        .header-tool-btn { position: relative; background: rgba(217,119,6,.2); border: none; width: 38px; height: 38px; border-radius: 10px; font-size: 1.1rem; color: #fbbf24; cursor: pointer; transition: .2s; display: flex; align-items: center; justify-content: center; }
        .header-tool-btn:hover { background: rgba(217,119,6,.35); color: #fff; }
        .notif-btn { position: relative; background: rgba(217,119,6,.2); border: none; width: 38px; height: 38px; border-radius: 10px; font-size: 1.1rem; color: #fbbf24; cursor: pointer; transition: .2s; }
        .notif-btn:hover { background: rgba(217,119,6,.35); color: #fff; }
        .notif-badge { position: absolute; top: 4px; right: 4px; width: 8px; height: 8px; background: #ef4444; border-radius: 50%; }
        .header-profile { display: flex; align-items: center; gap: 8px; cursor: pointer; padding: 4px 10px; border-radius: 10px; transition: .2s; border: none; background: none; }
        .header-profile:hover { background: rgba(217,119,6,.2); }
        .header-profile .avatar-sm { width: 34px; height: 34px; border-radius: 8px; background: linear-gradient(135deg, var(--primary), var(--secondary)); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: .75rem; overflow: hidden; }
        .header-profile .avatar-sm img { width: 100%; height: 100%; object-fit: cover; }
        .header-profile .name { font-size: .8rem; font-weight: 500; color: #fef3c7; }
        .header-profile .role-tag { font-size: .6rem; color: #fbbf24; display: block; font-weight: 400; }

        /* ── Page Content ── */
        .page-content { padding: 24px; overflow-x: hidden; }

        /* ── Header Dropdown ── */
        .dropdown-menu { min-width: 200px; padding: 6px; border-radius: 12px !important; border: 1px solid #e7e5e4; box-shadow: 0 8px 24px rgba(0,0,0,.1) !important; }
        .dropdown-item { border-radius: 8px; font-size: .82rem; padding: 9px 14px; color: #44403c; font-weight: 500; transition: all .15s; }
        .dropdown-item:hover { background: #fef3c7; color: var(--primary-dark); }
        .dropdown-item.text-danger:hover { background: #fef2f2; color: #ef4444; }
        .dropdown-divider { margin: 4px 0; border-color: #f5f5f4; }

        /* ── Pagination ── */
        .pagination { gap: 4px; flex-wrap: wrap; justify-content: center; }
        .page-link { border-radius: 8px !important; border: 1px solid #e7e5e4; color: #92400e; font-size: .82rem; font-weight: 500; padding: 8px 14px; transition: all .2s; }
        .page-link:hover { background: linear-gradient(135deg, #d97706, #f59e0b); color: #fff; border-color: transparent; box-shadow: 0 2px 8px rgba(217,119,6,.3); }
        .page-item.active .page-link { background: linear-gradient(135deg, #b45309, #d97706); border-color: transparent; color: #fff; box-shadow: 0 2px 8px rgba(217,119,6,.3); }
        .page-item.disabled .page-link { background: #fafaf9; color: #a8a29e; border-color: #e7e5e4; }

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
        .table thead th { font-weight: 600; color: #57534e; text-transform: uppercase; font-size: .72rem; letter-spacing: .5px; border-bottom-width: 1px; }
        .badge { font-weight: 500; font-size: .72rem; padding: 4px 10px; border-radius: 6px; }

        /* ── Buttons ── */
        .btn { font-size: .82rem; font-weight: 500; border-radius: 8px; padding: 8px 16px; transition: all .2s; }
        .btn-primary { background: linear-gradient(135deg, var(--primary), var(--primary-light)); border: none; color: #fff; }
        .btn-primary:hover { background: linear-gradient(135deg, var(--primary-dark), #d97706); transform: translateY(-1px); box-shadow: 0 4px 12px rgba(217,119,6,.3); }

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
        .modal-header { border-bottom: 1px solid #f5f5f4; padding: 16px 20px; }
        .modal-header .modal-title { font-size: .95rem; font-weight: 600; }
        .modal-footer { border-top: 1px solid #f5f5f4; padding: 12px 20px; }

        /* ── Forms ── */
        .form-control, .form-select { font-size: .82rem; border-radius: 8px; border-color: #e7e5e4; padding: 9px 14px; }
        .form-control:focus, .form-select:focus { border-color: var(--primary-light); box-shadow: 0 0 0 3px rgba(217,119,6,.12); }
        .form-label { font-size: .8rem; font-weight: 500; color: #57534e; }

        /* ── Scrollbar ── */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-thumb { background: #d6d3d1; border-radius: 6px; }

        /* ── Animations ── */
        .fade-in { animation: fadeIn .4s ease; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }

        /* ── Print ── */
        @media print {
            .sidebar, .top-header { display: none !important; }
            .main-content { margin-left: 0 !important; }
            .page-content { padding: 0 !important; }
        }

        /* ── Sidebar Search ── */
        .sidebar-search { padding: 12px 16px; border-bottom: 1px solid rgba(255,255,255,.08); position: relative; }
        .sidebar-search i { position: absolute; left: 28px; top: 50%; transform: translateY(-50%); color: #78716c; font-size: .82rem; pointer-events: none; }
        .sidebar-search input {
            width: 100%; background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.1);
            border-radius: 8px; padding: 8px 12px 8px 34px; color: #fef3c7;
            font-size: .78rem; outline: none; transition: all .2s; font-family: inherit;
        }
        .sidebar-search input::placeholder { color: #78716c; }
        .sidebar-search input:focus { background: rgba(255,255,255,.12); border-color: rgba(217,119,6,.4); }

        /* ── Nav Groups (Collapsible) ── */
        .nav-group { border-bottom: 1px solid rgba(255,255,255,.04); }
        .nav-group-label {
            display: flex; align-items: center; padding: 10px 20px 6px; cursor: pointer; user-select: none;
        }
        .nav-group-label span { font-size: .65rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1.2px; color: #d97706; }
        .nav-group-label i { font-size: .6rem; color: #d97706; margin-left: auto; transition: transform .25s; }
        .nav-group.open > .nav-group-label i { transform: rotate(180deg); }
        .nav-group-items { max-height: 0; overflow: hidden; transition: max-height .35s cubic-bezier(.4,0,.2,1); }
        .nav-group.open > .nav-group-items { max-height: 2000px; }
        .nav-group.search-match > .nav-group-items { max-height: 2000px; }

        /* ── Right Panel ── */
        .right-panel {
            position: fixed; top: 0; right: 0; width: 300px; height: 100vh;
            background: linear-gradient(180deg, #1c1917 0%, #292524 100%);
            z-index: 1050; transform: translateX(100%);
            transition: transform .3s cubic-bezier(.4,0,.2,1);
            display: flex; flex-direction: column;
            box-shadow: -4px 0 20px rgba(0,0,0,.2);
        }
        .right-panel.open { transform: translateX(0); }
        .right-panel-overlay {
            position: fixed; inset: 0; background: rgba(0,0,0,.4);
            z-index: 1049; opacity: 0; pointer-events: none; transition: opacity .3s;
        }
        .right-panel-overlay.open { opacity: 1; pointer-events: auto; }
        .right-panel-header {
            padding: 20px; border-bottom: 1px solid rgba(255,255,255,.1);
            display: flex; justify-content: space-between; align-items: center;
        }
        .right-panel-header h6 { color: #fff; font-size: .9rem; margin: 0; font-weight: 600; }
        .right-panel-close { background: none; border: none; color: #fbbf24; font-size: 1.1rem; cursor: pointer; padding: 4px; }
        .right-panel-close:hover { color: #fff; }
        .right-panel-body { flex: 1; overflow-y: auto; padding: 16px 20px; }
        .rp-section-label { font-size: .65rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; color: #d97706; margin-bottom: 8px; margin-top: 16px; padding: 0 14px; }
        .rp-section-label:first-child { margin-top: 0; }
        .rp-item {
            display: flex; align-items: center; gap: 12px; padding: 10px 14px;
            border-radius: 10px; color: #d6d3d1; text-decoration: none;
            font-size: .82rem; transition: all .2s; margin-bottom: 4px;
        }
        .rp-item:hover { background: rgba(217,119,6,.15); color: #fff; }
        .rp-item i { font-size: 1.1rem; width: 22px; text-align: center; }

        @stack('styles')
    </style>
</head>
<body>
    {{-- Sidebar --}}
    @include('peran.kepala-sekolah.sidebar')

    {{-- Right Panel (Fitur Khusus) --}}
    <div class="right-panel-overlay" id="rightPanelOverlay"></div>
    <div class="right-panel" id="rightPanel">
        <div class="right-panel-header">
            <h6><i class="bi bi-tools me-2"></i>Alat & Fitur Khusus</h6>
            <button class="right-panel-close" id="closeRightPanel"><i class="bi bi-x-lg"></i></button>
        </div>
        <div class="right-panel-body">
            <div class="rp-section-label">Fitur Khusus Kepsek</div>
            <a href="{{ route('kepala-sekolah.resolusi.index') }}" class="rp-item">
                <i class="bi bi-stamp"></i> <span>Resolusi</span>
            </a>
            <a href="{{ route('kepala-sekolah.rekap-eksekutif.index') }}" class="rp-item">
                <i class="bi bi-bar-chart-line-fill"></i> <span>Rekap Eksekutif</span>
            </a>
            <a href="{{ route('kepala-sekolah.siatu-ai.index') }}" class="rp-item">
                <i class="bi bi-robot"></i> <span>SIMPEG-AI</span>
            </a>

            <div class="rp-section-label">Pengaturan</div>
            <a href="{{ route('kepala-sekolah.profil.edit') }}" class="rp-item">
                <i class="bi bi-person-fill"></i> <span>Edit Profil</span>
            </a>
            <a href="{{ route('kepala-sekolah.pengaturan.index') }}" class="rp-item">
                <i class="bi bi-gear-fill"></i> <span>Pengaturan</span>
            </a>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="main-content" id="mainContent">
        {{-- Top Header --}}
        @include('peran.kepala-sekolah.header')

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
        @include('peran.kepala-sekolah.footer')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // ── Sidebar Toggle ──
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', function() {
                if (window.innerWidth > 991) {
                    document.body.classList.toggle('sidebar-collapsed');
                } else {
                    document.body.classList.toggle('sidebar-open');
                }
            });
            document.addEventListener('click', function(e) {
                if (window.innerWidth <= 991 && document.body.classList.contains('sidebar-open')) {
                    if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                        document.body.classList.remove('sidebar-open');
                    }
                }
            });
        }

        // ── Nav Group Toggle ──
        document.querySelectorAll('[data-toggle="nav-group"]').forEach(label => {
            label.addEventListener('click', function() {
                this.closest('.nav-group').classList.toggle('open');
            });
        });

        // ── Submenu Toggle ──
        document.querySelectorAll('[data-toggle="submenu"]').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                this.closest('.nav-item').classList.toggle('open');
            });
        });

        // ── Sidebar Search ──
        const searchInput = document.getElementById('sidebarSearch');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const query = this.value.toLowerCase().trim();
                const groups = document.querySelectorAll('.nav-group');
                groups.forEach(group => {
                    const items = group.querySelectorAll('.nav-item');
                    let groupMatch = false;
                    items.forEach(item => {
                        const text = item.textContent.toLowerCase();
                        const match = !query || text.includes(query);
                        item.style.display = match ? '' : 'none';
                        if (match) groupMatch = true;
                    });
                    group.style.display = groupMatch || !query ? '' : 'none';
                    if (query && groupMatch) {
                        group.classList.add('search-match');
                    } else {
                        group.classList.remove('search-match');
                    }
                });
            });
        }

        // ── Right Panel Toggle ──
        const rpToggle = document.getElementById('rightPanelToggle');
        const rpPanel = document.getElementById('rightPanel');
        const rpOverlay = document.getElementById('rightPanelOverlay');
        const rpClose = document.getElementById('closeRightPanel');
        function openRightPanel() { rpPanel.classList.add('open'); rpOverlay.classList.add('open'); }
        function closeRightPanel() { rpPanel.classList.remove('open'); rpOverlay.classList.remove('open'); }
        if (rpToggle) rpToggle.addEventListener('click', openRightPanel);
        if (rpClose) rpClose.addEventListener('click', closeRightPanel);
        if (rpOverlay) rpOverlay.addEventListener('click', closeRightPanel);
    });
    </script>
    @include('komponen.notifikasi-popup')
    @stack('scripts')
</body>
</html>
