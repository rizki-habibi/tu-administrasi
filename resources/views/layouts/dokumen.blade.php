<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dokumen & Kinerja') — SMA Negeri 2 Jember</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --dk-primary: #6366f1;
            --dk-primary-light: #818cf8;
            --dk-sidebar-bg: #1e1b4b;
            --dk-sidebar-hover: rgba(99,102,241,.15);
            --dk-sidebar-active: rgba(99,102,241,.25);
            --dk-text: #e0e7ff;
            --dk-text-muted: #a5b4fc;
            --dk-sidebar-w: 260px;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; background: #f8fafc; color: #1e293b; min-height: 100vh; display: flex; flex-direction: column; }

        /* ── Top Navbar ── */
        .dk-topbar {
            background: linear-gradient(135deg, #1e1b4b, #312e81);
            padding: 0 24px;
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 1050;
        }
        .dk-topbar .brand { display: flex; align-items: center; gap: 10px; text-decoration: none; }
        .dk-topbar .brand img { width: 32px; height: 32px; object-fit: contain; }
        .dk-topbar .brand span { color: #fff; font-weight: 600; font-size: .95rem; }
        .dk-topbar .nav-links { display: flex; gap: 6px; align-items: center; }
        .dk-topbar .nav-links a {
            color: var(--dk-text-muted);
            text-decoration: none;
            font-size: .82rem;
            font-weight: 500;
            padding: 6px 14px;
            border-radius: 8px;
            transition: .2s;
        }
        .dk-topbar .nav-links a:hover, .dk-topbar .nav-links a.active { color: #fff; background: rgba(255,255,255,.1); }
        .dk-topbar .nav-links a.btn-login {
            background: var(--dk-primary);
            color: #fff;
            padding: 6px 18px;
        }
        .dk-topbar .nav-links a.btn-login:hover { background: var(--dk-primary-light); }
        .dk-sidebar-toggle {
            display: none;
            background: none;
            border: none;
            color: #fff;
            font-size: 1.3rem;
            cursor: pointer;
            padding: 4px;
        }

        /* ── Layout Container ── */
        .dk-wrapper { display: flex; flex: 1; min-height: calc(100vh - 56px); }

        /* ── Left Sidebar ── */
        .dk-sidebar {
            width: var(--dk-sidebar-w);
            background: var(--dk-sidebar-bg);
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            position: sticky;
            top: 56px;
            height: calc(100vh - 56px);
            transition: transform .3s ease;
        }
        .dk-sidebar-header {
            padding: 20px 18px 12px;
            border-bottom: 1px solid rgba(255,255,255,.08);
        }
        .dk-sidebar-header h5 {
            color: #fff;
            font-size: .9rem;
            font-weight: 600;
            margin-bottom: 4px;
        }
        .dk-sidebar-header small {
            color: var(--dk-text-muted);
            font-size: .72rem;
        }
        .dk-sidebar-search {
            padding: 12px 14px 8px;
        }
        .dk-sidebar-search input {
            width: 100%;
            background: rgba(255,255,255,.08);
            border: 1px solid rgba(255,255,255,.1);
            border-radius: 8px;
            padding: 7px 12px 7px 34px;
            color: #fff;
            font-size: .78rem;
            outline: none;
            transition: .2s;
        }
        .dk-sidebar-search input::placeholder { color: var(--dk-text-muted); }
        .dk-sidebar-search input:focus { border-color: var(--dk-primary-light); background: rgba(255,255,255,.12); }
        .dk-sidebar-search { position: relative; }
        .dk-sidebar-search i {
            position: absolute;
            left: 26px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--dk-text-muted);
            font-size: .8rem;
        }

        /* ── Sidebar Nav Items ── */
        .dk-nav { flex: 1; padding: 8px 10px; overflow-y: auto; }
        .dk-nav-label {
            font-size: .65rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--dk-text-muted);
            padding: 14px 10px 6px;
            font-weight: 600;
        }
        .dk-nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            border-radius: 8px;
            color: var(--dk-text);
            text-decoration: none;
            font-size: .82rem;
            font-weight: 400;
            transition: .15s;
            margin-bottom: 2px;
        }
        .dk-nav-item:hover { background: var(--dk-sidebar-hover); color: #fff; }
        .dk-nav-item.active {
            background: var(--dk-sidebar-active);
            color: #fff;
            font-weight: 500;
        }
        .dk-nav-item.active::before {
            content: '';
            position: absolute;
            left: 0;
            width: 3px;
            height: 28px;
            background: var(--dk-primary-light);
            border-radius: 0 3px 3px 0;
        }
        .dk-nav-item { position: relative; }
        .dk-nav-item i { font-size: .95rem; width: 20px; text-align: center; }
        .dk-nav-item .badge {
            margin-left: auto;
            font-size: .6rem;
            padding: 3px 7px;
            border-radius: 10px;
        }

        .dk-sidebar-footer {
            padding: 14px 18px;
            border-top: 1px solid rgba(255,255,255,.08);
        }
        .dk-sidebar-footer a {
            color: var(--dk-text-muted);
            font-size: .75rem;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: .15s;
        }
        .dk-sidebar-footer a:hover { color: #fff; }

        /* ── Main Content ── */
        .dk-content {
            flex: 1;
            padding: 28px 32px;
            min-width: 0;
            overflow-x: hidden;
        }

        /* ── Footer ── */
        .dk-footer {
            background: #1e1b4b;
            color: var(--dk-text-muted);
            text-align: center;
            padding: 18px;
            font-size: .75rem;
        }
        .dk-footer a { color: var(--dk-primary-light); text-decoration: none; }

        /* ── Mobile Overlay ── */
        .dk-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.5);
            z-index: 1039;
        }

        /* ── Responsive ── */
        @media (max-width: 991.98px) {
            .dk-sidebar-toggle { display: block; }
            .dk-sidebar {
                position: fixed;
                top: 56px;
                left: 0;
                z-index: 1040;
                transform: translateX(-100%);
            }
            .dk-sidebar.open { transform: translateX(0); }
            .dk-overlay.show { display: block; }
            .dk-content { padding: 20px 16px; }
        }

        /* ── Utility ── */
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
            font-size: .65rem;
            font-weight: 500;
            padding: 3px 10px;
            border-radius: 20px;
            background: rgba(99,102,241,.1);
            color: var(--dk-primary);
        }
        .stat-card {
            background: #fff;
            border-radius: 14px;
            padding: 20px;
            box-shadow: 0 2px 12px rgba(0,0,0,.05);
            text-align: center;
        }
        .stat-card .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            font-size: 1.2rem;
        }
        .stat-card .stat-value { font-size: 1.4rem; font-weight: 700; color: #1e293b; }
        .stat-card .stat-label { font-size: .72rem; color: #64748b; margin-top: 2px; }

        @yield('extra-css')
    </style>
</head>
<body>

    {{-- Top Navbar --}}
    <nav class="dk-topbar">
        <div class="d-flex align-items-center gap-3">
            <button class="dk-sidebar-toggle" id="dkSidebarToggle"><i class="bi bi-list"></i></button>
            <a href="{{ route('halaman-utama') }}" class="brand">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" onerror="this.style.display='none'">
                <span>SMA Negeri 2 Jember</span>
            </a>
        </div>
        <div class="nav-links d-none d-md-flex">
            <a href="{{ route('halaman-utama') }}">Beranda</a>
            <a href="{{ route('dokumen.beranda') }}" class="{{ request()->routeIs('dokumen.*') ? 'active' : '' }}">Dokumen</a>
            @auth
                @php $peran = auth()->user()->peran; @endphp
                @if($peran === 'admin')
                    <a href="{{ route('admin.beranda') }}">Dashboard</a>
                @elseif($peran === 'kepala_sekolah')
                    <a href="{{ route('kepala-sekolah.beranda') }}">Dashboard</a>
                @else
                    <a href="{{ route('staf.beranda') }}">Dashboard</a>
                @endif
            @else
                <a href="{{ route('login') }}" class="btn-login"><i class="bi bi-box-arrow-in-right me-1"></i>Login</a>
            @endauth
        </div>
    </nav>

    {{-- Sidebar Overlay (mobile) --}}
    <div class="dk-overlay" id="dkOverlay"></div>

    <div class="dk-wrapper">
        {{-- Left Sidebar --}}
        <aside class="dk-sidebar" id="dkSidebar">
            <div class="dk-sidebar-header">
                <h5><i class="bi bi-journal-richtext me-2"></i>Dokumen & Kinerja</h5>
                <small>Portal Informasi Publik Sekolah</small>
            </div>

            <div class="dk-sidebar-search">
                <i class="bi bi-search"></i>
                <input type="text" id="dkSidebarSearch" placeholder="Cari menu...">
            </div>

            <nav class="dk-nav" id="dkNav">
                <div class="dk-nav-label">Navigasi</div>
                <a href="{{ route('dokumen.beranda') }}" class="dk-nav-item {{ ($aktifMenu ?? '') === 'beranda' ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2-fill"></i> <span>Beranda</span>
                </a>

                <div class="dk-nav-label">Kategori</div>
                @foreach($kategoriMenu as $key => $info)
                    <a href="{{ route('dokumen.kategori', $key) }}" class="dk-nav-item {{ ($aktifMenu ?? '') === $key ? 'active' : '' }}">
                        <i class="bi {{ $info['icon'] }}"></i>
                        <span>{{ $info['label'] }}</span>
                        @if(isset($statistik[$key]) && $statistik[$key] > 0)
                            <span class="badge bg-primary bg-opacity-25 text-primary">{{ $statistik[$key] }}</span>
                        @endif
                    </a>
                @endforeach

                <div class="dk-nav-label">Lainnya</div>
                <a href="{{ route('dokumen.arsip') }}" class="dk-nav-item {{ ($aktifMenu ?? '') === 'arsip' ? 'active' : '' }}">
                    <i class="bi bi-archive-fill"></i> <span>Arsip & Unduhan</span>
                </a>
                <a href="{{ route('dokumen.saran') }}" class="dk-nav-item {{ ($aktifMenu ?? '') === 'saran' ? 'active' : '' }}">
                    <i class="bi bi-chat-left-heart-fill"></i> <span>Saran & Masukan</span>
                </a>
            </nav>

            <div class="dk-sidebar-footer">
                <a href="{{ route('halaman-utama') }}"><i class="bi bi-arrow-left"></i> Kembali ke Halaman Utama</a>
            </div>
        </aside>

        {{-- Main Content --}}
        <main class="dk-content">
            @if(session('sukses'))
                <div class="alert alert-success alert-dismissible fade show" style="border-radius:12px;font-size:.85rem;" role="alert">
                    <i class="bi bi-check-circle me-1"></i> {{ session('sukses') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    {{-- Footer --}}
    <footer class="dk-footer">
        &copy; {{ date('Y') }} SMA Negeri 2 Jember — Portal Dokumen & Kinerja Publik.
        <a href="{{ route('halaman-utama') }}">Halaman Utama</a>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mobile sidebar toggle
        const toggle = document.getElementById('dkSidebarToggle');
        const sidebar = document.getElementById('dkSidebar');
        const overlay = document.getElementById('dkOverlay');
        if (toggle) {
            toggle.addEventListener('click', () => {
                sidebar.classList.toggle('open');
                overlay.classList.toggle('show');
            });
        }
        if (overlay) {
            overlay.addEventListener('click', () => {
                sidebar.classList.remove('open');
                overlay.classList.remove('show');
            });
        }

        // Sidebar search filter
        const search = document.getElementById('dkSidebarSearch');
        if (search) {
            search.addEventListener('input', function() {
                const q = this.value.toLowerCase();
                document.querySelectorAll('.dk-nav-item').forEach(item => {
                    const text = item.textContent.toLowerCase();
                    item.style.display = text.includes(q) ? '' : 'none';
                });
            });
        }
    });
    </script>
    @yield('scripts')
</body>
</html>
