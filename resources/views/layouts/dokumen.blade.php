<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dokumen & Kinerja') — SMA Negeri 2 Jember</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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

        .sidebar-info {
            padding: 14px 16px; border-bottom: 1px solid rgba(255,255,255,.08);
            display: flex; align-items: center; gap: 10px;
        }
        .sidebar-info .info-icon {
            width: 38px; height: 38px; border-radius: 10px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 1rem; flex-shrink: 0;
        }
        .sidebar-info .info-text { overflow: hidden; flex: 1; }
        .sidebar-info .info-text .title { color: #fff; font-size: .78rem; font-weight: 600; white-space: nowrap; text-overflow: ellipsis; overflow: hidden; }
        .sidebar-info .info-text .subtitle { font-size: .65rem; color: #a5b4fc; }

        .sidebar-search { padding: 12px 16px; border-bottom: 1px solid rgba(255,255,255,.08); position: relative; }
        .sidebar-search i { position: absolute; left: 28px; top: 50%; transform: translateY(-50%); color: #64748b; font-size: .82rem; pointer-events: none; }
        .sidebar-search input {
            width: 100%; background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.1);
            border-radius: 8px; padding: 8px 12px 8px 34px; color: #e0e7ff;
            font-size: .78rem; outline: none; transition: all .2s; font-family: inherit;
        }
        .sidebar-search input::placeholder { color: #64748b; }
        .sidebar-search input:focus { background: rgba(255,255,255,.12); border-color: rgba(99,102,241,.4); }

        .sidebar-nav { flex: 1; overflow-y: auto; padding: 8px 0; scroll-behavior: smooth; }
        .sidebar-nav::-webkit-scrollbar { width: 3px; }
        .sidebar-nav::-webkit-scrollbar-track { background: transparent; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,.12); border-radius: 3px; }

        .nav-group { border-bottom: 1px solid rgba(255,255,255,.04); }
        .nav-group-label {
            display: flex; align-items: center; padding: 10px 16px 4px; cursor: pointer; user-select: none; gap: 6px;
        }
        .nav-group-label span:first-child { font-size: .63rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1.2px; color: #6366f1; }
        .nav-group-label i { font-size: .55rem; color: #6366f1; margin-left: auto; transition: transform .25s; flex-shrink: 0; }
        .nav-group.open > .nav-group-label i { transform: rotate(180deg); }
        .nav-group-items { max-height: 0; overflow: hidden; transition: max-height .35s cubic-bezier(.4,0,.2,1); }
        .nav-group.open > .nav-group-items { max-height: 2000px; }

        .nav-link {
            display: flex; align-items: center; padding: 8px 16px; color: #c7d2fe; text-decoration: none;
            font-size: .8rem; font-weight: 400; transition: all .2s; gap: 10px; cursor: pointer;
            border-left: 3px solid transparent; margin: 1px 0;
        }
        .nav-link:hover { background: rgba(99,102,241,.12); color: #e0e7ff; }
        .nav-link.active, .nav-link.active:hover { background: rgba(99,102,241,.18); color: #fff; border-left-color: var(--primary-light); font-weight: 500; }
        .nav-link i.icon { font-size: 1rem; width: 20px; text-align: center; flex-shrink: 0; }
        .nav-link .badge { margin-left: auto; font-size: .58rem; padding: 2px 6px; border-radius: 4px; }
        .nav-link .count-pill {
            margin-left: auto; font-size: .62rem; font-weight: 600; padding: 1px 7px;
            border-radius: 10px; background: rgba(99,102,241,.25); color: #c7d2fe; line-height: 1.5;
        }

        .sidebar-footer { padding: 0 16px; height: var(--header-h); border-top: 1px solid rgba(255,255,255,.08); display: flex; align-items: center; flex-shrink: 0; }
        .sidebar-footer a { color: #a5b4fc; font-size: .78rem; text-decoration: none; display: flex; align-items: center; gap: 8px; transition: .15s; }
        .sidebar-footer a:hover { color: #fff; }

        /* ── Main Content ── */
        .main-content { margin-left: var(--sidebar-w); transition: margin .3s cubic-bezier(.4,0,.2,1); min-height: 100vh; overflow-x: hidden; max-width: 100vw; }

        /* ── Top Header (Clean White - matches admin) ── */
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
        .header-nav-btn {
            background: #f3f4f6; border: 1px solid #e5e7eb; height: 38px; border-radius: 10px;
            font-size: .8rem; color: #4b5563; cursor: pointer; transition: .2s;
            display: flex; align-items: center; justify-content: center; gap: 6px;
            padding: 0 14px; text-decoration: none;
        }
        .header-nav-btn:hover { background: #ede9fe; color: var(--primary); border-color: #c4b5fd; }
        .header-nav-btn.active { background: #ede9fe; color: var(--primary); border-color: #c4b5fd; }
        .header-nav-btn i { font-size: 1rem; }

        /* ── Page Content ── */
        .page-content { padding: 24px; overflow-x: hidden; }

        /* ── Footer ── */
        .dk-footer {
            background: #fff; border-top: 1px solid #e5e7eb; text-align: center;
            padding: 16px 24px; font-size: .75rem; color: #6b7280;
        }
        .dk-footer a { color: var(--primary); text-decoration: none; font-weight: 500; }
        .dk-footer a:hover { text-decoration: underline; }

        /* ── Responsive ── */
        body.sidebar-collapsed .sidebar { transform: translateX(-100%); }
        body.sidebar-collapsed .main-content { margin-left: 0; }
        @media (max-width: 991px) {
            .sidebar { transform: translateX(-100%); }
            .main-content { margin-left: 0; }
            body.sidebar-open .sidebar { transform: translateX(0); }
            body.sidebar-open::after { content: ''; position: fixed; inset: 0; background: rgba(0,0,0,.4); z-index: 1039; }
            .page-content { padding: 20px 16px; }
        }

        /* ── Cards ── */
        .card { border: none; border-radius: var(--card-radius); box-shadow: 0 1px 3px rgba(0,0,0,.06); transition: box-shadow .2s; }
        .card:hover { box-shadow: 0 4px 12px rgba(0,0,0,.08); }

        /* ── Dokumen Utility Cards ── */
        .card-dokumen {
            border: none;
            border-radius: 14px;
            box-shadow: 0 2px 12px rgba(0,0,0,.06);
            transition: .2s;
            overflow: hidden;
        }
        .card-dokumen:hover { box-shadow: 0 6px 24px rgba(0,0,0,.1); transform: translateY(-2px); }
        .card-dokumen .card-body { padding: 18px; }
        .card-dokumen .card-title { font-size: .9rem; font-weight: 600; margin-bottom: 6px; }
        .card-dokumen .card-text { font-size: .78rem; color: #64748b; }
        .badge-kategori {
            font-size: .65rem; font-weight: 500; padding: 3px 10px; border-radius: 20px;
            background: rgba(99,102,241,.1); color: var(--primary);
        }
        .stat-card {
            background: #fff; border-radius: 14px; padding: 20px;
            box-shadow: 0 2px 12px rgba(0,0,0,.05); text-align: center;
        }
        .stat-card .stat-icon {
            width: 48px; height: 48px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 10px; font-size: 1.2rem;
        }
        .stat-card .stat-value { font-size: 1.4rem; font-weight: 700; color: #1e293b; }
        .stat-card .stat-label { font-size: .72rem; color: #64748b; margin-top: 2px; }

        /* ── Buttons ── */
        .btn { font-size: .82rem; font-weight: 500; border-radius: 8px; padding: 8px 16px; transition: all .2s; }
        .btn-primary { background: linear-gradient(135deg, var(--primary), var(--secondary)); border: none; }
        .btn-primary:hover { background: linear-gradient(135deg, var(--primary-dark), #7c3aed); transform: translateY(-1px); box-shadow: 0 4px 12px rgba(99,102,241,.3); }

        /* ── Tables ── */
        .table { font-size: .82rem; }
        .table thead th { font-weight: 600; color: #475569; text-transform: uppercase; font-size: .72rem; letter-spacing: .5px; border-bottom-width: 1px; }
        .badge { font-weight: 500; font-size: .72rem; padding: 4px 10px; border-radius: 6px; }

        /* ── Forms ── */
        .form-control, .form-select { font-size: .82rem; border-radius: 8px; border-color: #e2e8f0; padding: 9px 14px; }
        .form-control:focus, .form-select:focus { border-color: var(--primary-light); box-shadow: 0 0 0 3px rgba(99,102,241,.12); }
        .form-label { font-size: .8rem; font-weight: 500; color: #475569; }

        /* ── Pagination ── */
        .pagination { gap: 4px; flex-wrap: wrap; justify-content: center; }
        .page-link { border-radius: 8px !important; border: 1px solid #e2e8f0; color: #4338ca; font-size: .82rem; font-weight: 500; padding: 8px 14px; transition: all .2s; }
        .page-link:hover { background: linear-gradient(135deg, #6366f1, #818cf8); color: #fff; border-color: transparent; box-shadow: 0 2px 8px rgba(99,102,241,.3); }
        .page-item.active .page-link { background: linear-gradient(135deg, #4338ca, #6366f1); border-color: transparent; color: #fff; box-shadow: 0 2px 8px rgba(99,102,241,.3); }
        .page-item.disabled .page-link { background: #f8fafc; color: #94a3b8; border-color: #e2e8f0; }

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

        @yield('extra-css')
    </style>
</head>
<body>

    {{-- ═══ Sidebar ═══ --}}
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

        {{-- Info Section --}}
        <div class="sidebar-info">
            <div class="info-icon"><i class="bi bi-journal-richtext"></i></div>
            <div class="info-text">
                <div class="title">Dokumen & Kinerja</div>
                <div class="subtitle">Portal Informasi Publik</div>
            </div>
        </div>

        {{-- Search --}}
        <div class="sidebar-search">
            <i class="bi bi-search"></i>
            <input type="text" id="sidebarSearch" placeholder="Cari menu..." autocomplete="off">
        </div>

        <nav class="sidebar-nav" id="sidebarNav">
            {{-- ▸ Navigasi Utama --}}
            <div class="nav-group open">
                <div class="nav-group-label" data-toggle="nav-group">
                    <span>Navigasi</span>
                    <i class="bi bi-chevron-down"></i>
                </div>
                <div class="nav-group-items">
                    <div class="nav-item">
                        <a href="{{ route('dokumen.beranda') }}" class="nav-link {{ ($aktifMenu ?? '') === 'beranda' ? 'active' : '' }}">
                            <i class="bi bi-grid-1x2-fill icon"></i> <span>Beranda Dokumen</span>
                        </a>
                    </div>
                </div>
            </div>

            {{-- ▸ Kategori Dokumen --}}
            <div class="nav-group open">
                <div class="nav-group-label" data-toggle="nav-group">
                    <span>Kategori</span>
                    <i class="bi bi-chevron-down"></i>
                </div>
                <div class="nav-group-items">
                    @foreach($kategoriMenu as $key => $info)
                        <div class="nav-item">
                            <a href="{{ route('dokumen.kategori', $key) }}" class="nav-link {{ ($aktifMenu ?? '') === $key ? 'active' : '' }}">
                                <i class="bi {{ $info['icon'] }} icon"></i>
                                <span>{{ $info['label'] }}</span>
                                @if(isset($statistik[$key]) && $statistik[$key] > 0)
                                    <span class="count-pill">{{ $statistik[$key] }}</span>
                                @endif
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- ▸ Lainnya --}}
            <div class="nav-group open">
                <div class="nav-group-label" data-toggle="nav-group">
                    <span>Lainnya</span>
                    <i class="bi bi-chevron-down"></i>
                </div>
                <div class="nav-group-items">
                    <div class="nav-item">
                        <a href="{{ route('dokumen.arsip') }}" class="nav-link {{ ($aktifMenu ?? '') === 'arsip' ? 'active' : '' }}">
                            <i class="bi bi-archive-fill icon"></i> <span>Arsip & Unduhan</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('dokumen.saran') }}" class="nav-link {{ ($aktifMenu ?? '') === 'saran' ? 'active' : '' }}">
                            <i class="bi bi-chat-left-heart-fill icon"></i> <span>Saran & Masukan</span>
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <div class="sidebar-footer">
            <a href="{{ route('halaman-utama') }}"><i class="bi bi-arrow-left"></i> Kembali ke Halaman Utama</a>
        </div>
    </aside>

    {{-- ═══ Main Content Area ═══ --}}
    <div class="main-content">
        {{-- Top Header --}}
        <header class="top-header">
            <button class="sidebar-toggle" id="sidebarToggle"><i class="bi bi-list"></i></button>
            <span class="header-title">@yield('title', 'Dokumen & Kinerja')</span>

            <div class="header-right">
                <a href="{{ route('halaman-utama') }}" class="header-nav-btn d-none d-md-flex">
                    <i class="bi bi-house"></i> <span>Beranda</span>
                </a>
                <a href="{{ route('dokumen.beranda') }}" class="header-nav-btn d-none d-md-flex {{ request()->routeIs('dokumen.*') ? 'active' : '' }}">
                    <i class="bi bi-journal-richtext"></i> <span>Dokumen</span>
                </a>
                @auth
                    @php $peran = auth()->user()->peran; @endphp
                    @if($peran === 'admin')
                        <a href="{{ route('admin.beranda') }}" class="header-nav-btn d-none d-md-flex">
                            <i class="bi bi-speedometer2"></i> <span>Dashboard</span>
                        </a>
                    @elseif($peran === 'kepala_sekolah')
                        <a href="{{ route('kepala-sekolah.beranda') }}" class="header-nav-btn d-none d-md-flex">
                            <i class="bi bi-speedometer2"></i> <span>Dashboard</span>
                        </a>
                    @else
                        <a href="{{ route('staf.beranda') }}" class="header-nav-btn d-none d-md-flex">
                            <i class="bi bi-speedometer2"></i> <span>Dashboard</span>
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="header-nav-btn">
                        <i class="bi bi-box-arrow-in-right"></i> <span>Login</span>
                    </a>
                @endauth
            </div>
        </header>

        {{-- Page Content --}}
        <div class="page-content fade-in">
            @if(session('sukses'))
                <div class="alert alert-success alert-dismissible fade show" style="border-radius:12px;font-size:.85rem;" role="alert">
                    <i class="bi bi-check-circle me-1"></i> {{ session('sukses') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>

        {{-- Footer --}}
        <footer class="dk-footer">
            &copy; {{ date('Y') }} <a href="{{ route('halaman-utama') }}">SMA Negeri 2 Jember</a> — Portal Dokumen & Kinerja Publik SIMPEG-SMART.
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        // ── Sidebar Toggle ────────────────────────────────
        const sidebar = document.getElementById('sidebar');
        const toggle  = document.getElementById('sidebarToggle');
        const body    = document.body;
        const isMobile = () => window.innerWidth <= 991;

        function doToggle() {
            if (isMobile()) {
                body.classList.toggle('sidebar-open');
            } else {
                body.classList.toggle('sidebar-collapsed');
                localStorage.setItem('dk-sidebar', body.classList.contains('sidebar-collapsed') ? 'collapsed' : 'expanded');
            }
        }

        if (toggle) toggle.addEventListener('click', doToggle);

        if (!isMobile() && localStorage.getItem('dk-sidebar') === 'collapsed') {
            body.classList.add('sidebar-collapsed');
        }

        document.addEventListener('click', e => {
            if (isMobile() && body.classList.contains('sidebar-open') && !sidebar.contains(e.target) && e.target !== toggle && (!toggle || !toggle.contains(e.target))) {
                body.classList.remove('sidebar-open');
            }
        });

        // ── Nav Group Toggle ────────────────────────────────
        document.querySelectorAll('[data-toggle="nav-group"]').forEach(el => {
            el.addEventListener('click', () => {
                el.closest('.nav-group').classList.toggle('open');
            });
        });

        // ── Sidebar Search Filter ────────────────────────────────
        const search = document.getElementById('sidebarSearch');
        if (search) {
            search.addEventListener('input', function() {
                const q = this.value.toLowerCase();
                document.querySelectorAll('.nav-link').forEach(item => {
                    const text = item.textContent.toLowerCase();
                    item.closest('.nav-item').style.display = text.includes(q) ? '' : 'none';
                });
                // Open all groups when searching
                if (q.length > 0) {
                    document.querySelectorAll('.nav-group').forEach(g => g.classList.add('open'));
                }
            });
        }

        // ── Alert Auto-close ────────────────────────────────
        setTimeout(() => {
            document.querySelectorAll('.alert-dismissible').forEach(el => {
                const bsAlert = bootstrap.Alert.getOrCreateInstance(el);
                bsAlert.close();
            });
        }, 4000);
    });
    </script>
    @yield('scripts')
</body>
</html>
