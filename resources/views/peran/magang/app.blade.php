<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('judul', 'Beranda') - TU Magang</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        :root {
            --sidebar-w: 268px;
            --header-h: 62px;
            --primary: #0891b2;
            --primary-dark: #0e7490;
            --primary-light: #22d3ee;
            --secondary: #06b6d4;
            --sidebar-bg: linear-gradient(180deg, #0f172a 0%, #1e293b 100%);
            --body-bg: #f0f2f8;
            --card-radius: 14px;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { overflow-x: hidden; }
        body { font-family: 'Poppins', sans-serif; background: var(--body-bg); overflow-x: hidden; }

        /* ── Sidebar ── */
        .sidebar {
            position: fixed; top: 0; left: 0; width: var(--sidebar-w); height: 100vh;
            background: var(--sidebar-bg); color: #94a3b8; z-index: 1040;
            transition: transform .3s cubic-bezier(.4,0,.2,1);
            display: flex; flex-direction: column; overflow: hidden;
        }
        .sidebar-brand { padding: 20px 20px 12px; text-align: center; border-bottom: 1px solid rgba(255,255,255,.08); }
        .sidebar-brand img { width: 52px; height: 52px; border-radius: 12px; object-fit: cover; border: 2px solid rgba(255,255,255,.15); }
        .sidebar-brand h6 { color: #fff; font-size: .85rem; font-weight: 600; margin: 8px 0 2px; }
        .sidebar-brand small { font-size: .68rem; color: #64748b; }

        .sidebar-profile { padding: 16px 20px; border-bottom: 1px solid rgba(255,255,255,.08); display: flex; align-items: center; gap: 10px; }
        .sidebar-profile .avatar {
            width: 40px; height: 40px; border-radius: 10px; background: linear-gradient(135deg, var(--primary), var(--secondary));
            display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 600; font-size: .85rem; flex-shrink: 0;
            overflow: hidden;
        }
        .sidebar-profile .avatar img { width: 100%; height: 100%; object-fit: cover; }
        .sidebar-profile .info { overflow: hidden; }
        .sidebar-profile .info .nama { color: #fff; font-size: .8rem; font-weight: 600; white-space: nowrap; text-overflow: ellipsis; overflow: hidden; }
        .sidebar-profile .info .peran { font-size: .68rem; color: #64748b; }
        .sidebar-profile .status { width: 8px; height: 8px; border-radius: 50%; background: #22d3ee; margin-left: auto; flex-shrink: 0; }

        .sidebar-nav { flex: 1; overflow-y: auto; padding: 12px 0; }
        .sidebar-nav::-webkit-scrollbar { width: 4px; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,.15); border-radius: 4px; }
        .nav-item { position: relative; }
        .nav-link {
            display: flex; align-items: center; padding: 9px 20px; color: #94a3b8; text-decoration: none;
            font-size: .82rem; font-weight: 400; transition: all .2s; gap: 12px; cursor: pointer; border-left: 3px solid transparent;
        }
        .nav-link:hover { background: rgba(8,145,178,.12); color: #e2e8f0; }
        .nav-link.active, .nav-link.active:hover { background: rgba(8,145,178,.18); color: #fff; border-left-color: var(--primary-light); font-weight: 500; }
        .nav-link i.icon { font-size: 1.1rem; width: 22px; text-align: center; }
        .nav-link .arrow { margin-left: auto; font-size: .7rem; transition: transform .25s; }
        .nav-item.open > .nav-link .arrow { transform: rotate(90deg); }
        .nav-link .badge { font-size: .6rem; margin-left: auto; }

        .submenu { max-height: 0; overflow: hidden; transition: max-height .35s cubic-bezier(.4,0,.2,1); background: rgba(0,0,0,.12); }
        .nav-item.open > .submenu { max-height: 500px; }
        .submenu .sub-link {
            display: flex; align-items: center; padding: 7px 20px 7px 54px; color: #64748b; font-size: .78rem;
            text-decoration: none; transition: all .2s; gap: 8px;
        }
        .submenu .sub-link:hover { color: #fff; background: rgba(8,145,178,.1); }
        .submenu .sub-link.active { color: #fff; font-weight: 500; }

        .sidebar-footer { padding: 14px 20px; border-top: 1px solid rgba(255,255,255,.08); }
        .sidebar-footer a { color: #ef4444; font-size: .8rem; text-decoration: none; display: flex; align-items: center; gap: 8px; }
        .sidebar-footer a:hover { color: #fca5a5; }

        /* ── Sidebar Search ── */
        .sidebar-search { padding: 12px 16px; border-bottom: 1px solid rgba(255,255,255,.08); position: relative; }
        .sidebar-search i { position: absolute; left: 28px; top: 50%; transform: translateY(-50%); color: #475569; font-size: .82rem; pointer-events: none; }
        .sidebar-search input {
            width: 100%; background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.1);
            border-radius: 8px; padding: 8px 12px 8px 34px; color: #e2e8f0;
            font-size: .78rem; outline: none; transition: all .2s; font-family: inherit;
        }
        .sidebar-search input::placeholder { color: #475569; }
        .sidebar-search input:focus { background: rgba(255,255,255,.12); border-color: rgba(8,145,178,.4); }

        /* ── Nav Groups ── */
        .nav-group { border-bottom: 1px solid rgba(255,255,255,.04); }
        .nav-group-label {
            display: flex; align-items: center; padding: 10px 20px 6px; cursor: pointer; user-select: none;
        }
        .nav-group-label span { font-size: .65rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1.2px; color: #0891b2; }
        .nav-group-label i { font-size: .6rem; color: #0891b2; margin-left: auto; transition: transform .25s; }
        .nav-group.open > .nav-group-label i { transform: rotate(180deg); }
        .nav-group-items { max-height: 0; overflow: hidden; transition: max-height .35s cubic-bezier(.4,0,.2,1); }
        .nav-group.open > .nav-group-items { max-height: 2000px; }
        .nav-group.search-match > .nav-group-items { max-height: 2000px; }

        /* ── Main Content ── */
        .main-content { margin-left: var(--sidebar-w); transition: margin .3s cubic-bezier(.4,0,.2,1); min-height: 100vh; overflow-x: hidden; max-width: 100vw; }

        /* ── Top Header ── */
        .top-header {
            position: sticky; top: 0; z-index: 1030; height: var(--header-h);
            background: linear-gradient(90deg, #0f172a, #1e293b);
            display: flex; align-items: center; padding: 0 24px; gap: 16px;
            box-shadow: 0 2px 8px rgba(0,0,0,.15);
        }
        .sidebar-toggle { background: none; border: none; font-size: 1.3rem; color: #94a3b8; cursor: pointer; padding: 6px; border-radius: 8px; transition: .2s; }
        .sidebar-toggle:hover { background: rgba(8,145,178,.2); color: #fff; }
        .header-title { font-size: .9rem; font-weight: 600; color: #e2e8f0; }
        .header-right { margin-left: auto; display: flex; align-items: center; gap: 12px; }
        .header-date { font-size: .78rem; color: #64748b; }
        .notif-btn { position: relative; background: rgba(8,145,178,.2); border: none; width: 38px; height: 38px; border-radius: 10px; font-size: 1.1rem; color: #67e8f9; cursor: pointer; transition: .2s; }
        .notif-btn:hover { background: rgba(8,145,178,.35); color: #fff; }
        .notif-badge { position: absolute; top: 4px; right: 4px; width: 8px; height: 8px; background: #ef4444; border-radius: 50%; }
        .header-profile { display: flex; align-items: center; gap: 8px; cursor: pointer; padding: 4px 10px; border-radius: 10px; transition: .2s; border: none; background: none; }
        .header-profile:hover { background: rgba(8,145,178,.2); }
        .header-profile .avatar-sm { width: 34px; height: 34px; border-radius: 8px; background: linear-gradient(135deg, var(--primary), var(--secondary)); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: .75rem; overflow: hidden; }
        .header-profile .avatar-sm img { width: 100%; height: 100%; object-fit: cover; }
        .header-profile .name { font-size: .8rem; font-weight: 500; color: #e2e8f0; }

        /* ── Page Content ── */
        .page-content { padding: 24px; overflow-x: hidden; }

        /* ── Dropdown ── */
        .dropdown-menu { min-width: 200px; padding: 6px; border-radius: 12px !important; border: 1px solid #e2e8f0; box-shadow: 0 8px 24px rgba(0,0,0,.1) !important; }
        .dropdown-item { border-radius: 8px; font-size: .82rem; padding: 9px 14px; color: #374151; font-weight: 500; transition: all .15s; }
        .dropdown-item:hover { background: #ecfeff; color: var(--primary); }
        .dropdown-item.text-danger:hover { background: #fef2f2; color: #ef4444; }
        .dropdown-divider { margin: 4px 0; border-color: #f1f5f9; }

        /* ── Pagination ── */
        .pagination { gap: 4px; flex-wrap: wrap; justify-content: center; }
        .page-link { border-radius: 8px !important; border: 1px solid #e2e8f0; color: #155e75; font-size: .82rem; font-weight: 500; padding: 8px 14px; transition: all .2s; }
        .page-link:hover { background: linear-gradient(135deg, #0891b2, #22d3ee); color: #fff; border-color: transparent; box-shadow: 0 2px 8px rgba(8,145,178,.3); }
        .page-item.active .page-link { background: linear-gradient(135deg, #0e7490, #0891b2); border-color: transparent; color: #fff; box-shadow: 0 2px 8px rgba(8,145,178,.3); }
        .page-item.disabled .page-link { background: #f8fafc; color: #94a3b8; border-color: #e2e8f0; }

        /* ── Cards ── */
        .card { border: none; border-radius: var(--card-radius); box-shadow: 0 1px 3px rgba(0,0,0,.06); transition: box-shadow .2s; }
        .card:hover { box-shadow: 0 4px 12px rgba(0,0,0,.08); }
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
        .btn-primary:hover { background: linear-gradient(135deg, var(--primary-dark), #0891b2); transform: translateY(-1px); box-shadow: 0 4px 12px rgba(8,145,178,.3); }

        /* ── Forms ── */
        .form-control, .form-select { font-size: .82rem; border-radius: 8px; border-color: #e2e8f0; padding: 9px 14px; }
        .form-control:focus, .form-select:focus { border-color: var(--primary-light); box-shadow: 0 0 0 3px rgba(8,145,178,.12); }
        .form-label { font-size: .8rem; font-weight: 500; color: #475569; }

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
        .modal-footer { border-top: 1px solid #f1f5f9; padding: 12px 20px; }

        /* ── Scrollbar ── */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 6px; }

        /* ── Animation ── */
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
    @include('peran.magang.sidebar')

    <div class="main-content" id="mainContent">
        @include('peran.magang.header')

        <div class="page-content fade-in">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert" style="border-radius:10px; border-left: 4px solid #0891b2;">
                    <i class="bi bi-check-circle-fill me-2 text-info"></i>
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
            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show d-flex align-items-center" role="alert" style="border-radius:10px; border-left: 4px solid #0891b2;">
                    <i class="bi bi-info-circle-fill me-2 text-info"></i>
                    {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('konten')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Sidebar toggle
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', () => {
                if (window.innerWidth <= 991) {
                    document.body.classList.toggle('sidebar-open');
                } else {
                    document.body.classList.toggle('sidebar-collapsed');
                }
            });
        }
        document.addEventListener('click', (e) => {
            if (window.innerWidth <= 991 && document.body.classList.contains('sidebar-open')) {
                if (!sidebar.contains(e.target) && e.target !== sidebarToggle) {
                    document.body.classList.remove('sidebar-open');
                }
            }
        });

        // Submenu toggle
        document.querySelectorAll('[data-toggle="submenu"]').forEach(el => {
            el.addEventListener('click', (e) => {
                e.preventDefault();
                el.closest('.nav-item').classList.toggle('open');
            });
        });

        // Nav group toggle
        document.querySelectorAll('.nav-group-label').forEach(el => {
            el.addEventListener('click', () => {
                el.closest('.nav-group').classList.toggle('open');
            });
        });

        // Sidebar search
        const searchInput = document.getElementById('sidebarSearchInput');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const q = this.value.toLowerCase().trim();
                document.querySelectorAll('.nav-group').forEach(g => {
                    if (!q) { g.classList.remove('search-match'); g.style.display = ''; return; }
                    const links = g.querySelectorAll('.nav-link span, .sub-link');
                    let found = false;
                    links.forEach(l => {
                        if (l.textContent.toLowerCase().includes(q)) found = true;
                    });
                    g.style.display = found ? '' : 'none';
                    if (found) g.classList.add('search-match');
                    else g.classList.remove('search-match');
                });
            });
        }

        // Notification AJAX
        const notifToggle = document.getElementById('notifToggle');
        if (notifToggle) {
            let notifLoaded = false;
            notifToggle.addEventListener('click', function () {
                if (notifLoaded) return;
                notifLoaded = true;
                fetch("{{ route('magang.notifikasi.json') }}")
                    .then(r => r.json())
                    .then(data => {
                        const list = document.getElementById('notifList');
                        if (!data.length) {
                            list.innerHTML = '<div class="text-center py-4 text-muted" style="font-size:.82rem;">Tidak ada notifikasi</div>';
                            return;
                        }
                        list.innerHTML = data.map(n => `
                            <a href="/magang/notifikasi/${n.id}/baca" class="d-flex align-items-start gap-2 px-3 py-2 text-decoration-none border-bottom ${!n.sudah_dibaca ? 'bg-light' : ''}"
                               style="transition:.15s;" onmouseover="this.style.background='#ecfeff'" onmouseout="this.style.background='${!n.sudah_dibaca ? '#f8fafc' : ''}'">
                                <div class="flex-shrink-0 mt-1"><i class="bi bi-bell-fill" style="color:#0891b2;font-size:.9rem;"></i></div>
                                <div class="flex-grow-1">
                                    <div style="font-size:.8rem;font-weight:${!n.sudah_dibaca ? '600' : '400'};color:#1e293b;">${n.judul}</div>
                                    <div style="font-size:.72rem;color:#64748b;" class="text-truncate">${n.pesan || ''}</div>
                                </div>
                            </a>
                        `).join('');
                    });
            });
        }

        // SweetAlert for delete confirms
        document.querySelectorAll('.btn-delete').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('form') || document.getElementById(this.dataset.form);
                Swal.fire({
                    title: 'Yakin hapus?',
                    text: 'Data yang dihapus tidak bisa dikembalikan.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal'
                }).then(r => { if (r.isConfirmed && form) form.submit(); });
            });
        });
    </script>
    @include('komponen.notifikasi-popup')
    @include('komponen.ai-widget')
    @stack('scripts')
</body>
</html>
