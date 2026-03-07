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
        .sidebar-brand {
            padding: 0 16px; height: var(--header-h); border-bottom: 1px solid rgba(255,255,255,.08);
            display: flex; align-items: center; gap: 10px; flex-shrink: 0;
        }
        .sidebar-brand img { width: 40px; height: 40px; border-radius: 10px; object-fit: cover; border: 2px solid rgba(255,255,255,.15); flex-shrink: 0; }
        .sidebar-brand h6 { color: #fff; font-size: .82rem; font-weight: 600; margin: 0; line-height: 1.3; }
        .sidebar-brand small { font-size: .65rem; color: #a8a29e; }

        .sidebar-profile {
            padding: 14px 16px; border-bottom: 1px solid rgba(255,255,255,.08);
            display: flex; align-items: center; gap: 10px;
        }
        .sidebar-profile .avatar {
            width: 38px; height: 38px; border-radius: 10px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-weight: 600; font-size: .8rem; flex-shrink: 0; overflow: hidden;
        }
        .sidebar-profile .avatar img { width: 100%; height: 100%; object-fit: cover; }
        .sidebar-profile .info { overflow: hidden; flex: 1; }
        .sidebar-profile .info .name { color: #fff; font-size: .78rem; font-weight: 600; white-space: nowrap; text-overflow: ellipsis; overflow: hidden; }
        .sidebar-profile .info .role { font-size: .65rem; color: #a8a29e; }
        .sidebar-profile .status { width: 8px; height: 8px; border-radius: 50%; background: #34d399; flex-shrink: 0; }

        .sidebar-nav { flex: 1; overflow-y: auto; padding: 8px 0; scroll-behavior: smooth; }
        .sidebar-nav::-webkit-scrollbar { width: 3px; }
        .sidebar-nav::-webkit-scrollbar-track { background: transparent; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,.12); border-radius: 3px; }
        .nav-item { position: relative; }
        .nav-link {
            display: flex; align-items: center; padding: 8px 16px; color: #d6d3d1; text-decoration: none;
            font-size: .8rem; font-weight: 400; transition: all .2s; gap: 10px; cursor: pointer;
            border-left: 3px solid transparent; margin: 1px 0;
        }
        .nav-link:hover { background: rgba(217,119,6,.12); color: #fef3c7; }
        .nav-link.active, .nav-link.active:hover { background: rgba(217,119,6,.18); color: #fff; border-left-color: var(--primary-light); font-weight: 500; }
        .nav-link i.icon { font-size: 1rem; width: 20px; text-align: center; flex-shrink: 0; }
        .nav-link .arrow { margin-left: auto; font-size: .65rem; transition: transform .25s; flex-shrink: 0; }
        .nav-item.open > .nav-link .arrow { transform: rotate(90deg); }
        .nav-link .badge { font-size: .58rem; padding: 2px 6px; border-radius: 4px; }

        .submenu { max-height: 0; overflow: hidden; transition: max-height .35s cubic-bezier(.4,0,.2,1); background: rgba(0,0,0,.1); }
        .nav-item.open > .submenu { max-height: 500px; }
        .submenu .sub-link {
            display: flex; align-items: center; padding: 6px 16px 6px 46px; color: #a8a29e; font-size: .76rem;
            text-decoration: none; transition: all .2s; gap: 8px;
        }
        .submenu .sub-link:hover { color: #fff; background: rgba(217,119,6,.1); }
        .submenu .sub-link.active { color: #fff; font-weight: 500; }
        .submenu .sub-link .badge { font-size: .55rem; padding: 1px 5px; }

        .sidebar-footer { padding: 0 16px; height: var(--header-h); border-top: 1px solid rgba(255,255,255,.08); display: flex; align-items: center; flex-shrink: 0; }
        .sidebar-footer a { color: #ef4444; font-size: .78rem; text-decoration: none; display: flex; align-items: center; gap: 8px; }
        .sidebar-footer a:hover { color: #fca5a5; }

        /* ── Main Content ── */
        .main-content { margin-left: var(--sidebar-w); transition: margin .3s cubic-bezier(.4,0,.2,1); min-height: 100vh; overflow-x: hidden; max-width: 100vw; }

        /* ── Top Header ── */
        .top-header {
            position: sticky; top: 0; z-index: 1030; height: var(--header-h);
            background: #fff;
            display: flex; align-items: center; padding: 0 24px; gap: 16px;
            box-shadow: 0 1px 3px rgba(0,0,0,.08); border-bottom: 1px solid #e7e5e4;
        }
        .sidebar-toggle { background: none; border: none; font-size: 1.3rem; color: #57534e; cursor: pointer; padding: 6px; border-radius: 8px; transition: .2s; }
        .sidebar-toggle:hover { background: #fef3c7; color: var(--primary); }
        .header-title { font-size: .9rem; font-weight: 600; color: #1c1917; }
        .header-right { margin-left: auto; display: flex; align-items: center; gap: 8px; }
        .header-date { font-size: .78rem; color: #78716c; display: flex; align-items: center; gap: 6px; }
        .header-tool-btn { position: relative; background: #fef3c7; border: 1px solid #fde68a; width: 38px; height: 38px; border-radius: 10px; font-size: 1.1rem; color: #92400e; cursor: pointer; transition: .2s; display: flex; align-items: center; justify-content: center; }
        .header-tool-btn:hover { background: #fde68a; color: var(--primary-dark); border-color: #fbbf24; }
        .notif-btn { position: relative; background: #fef3c7; border: 1px solid #fde68a; width: 38px; height: 38px; border-radius: 10px; font-size: 1.1rem; color: #92400e; cursor: pointer; transition: .2s; display: flex; align-items: center; justify-content: center; }
        .notif-btn:hover { background: #fde68a; color: var(--primary-dark); border-color: #fbbf24; }
        .notif-badge { position: absolute; top: 4px; right: 4px; width: 8px; height: 8px; background: #ef4444; border-radius: 50%; border: 2px solid #fff; }
        .header-profile { display: flex; align-items: center; gap: 8px; cursor: pointer; padding: 4px 10px; border-radius: 10px; transition: .2s; border: 1px solid transparent; background: none; }
        .header-profile:hover { background: #fef3c7; border-color: #fde68a; }
        .header-profile .avatar-sm { width: 34px; height: 34px; border-radius: 8px; background: linear-gradient(135deg, var(--primary), var(--secondary)); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: .75rem; overflow: hidden; }
        .header-profile .avatar-sm img { width: 100%; height: 100%; object-fit: cover; }
        .header-profile .name { font-size: .8rem; font-weight: 500; color: #1c1917; }
        .header-profile .role-tag { font-size: .6rem; color: #78716c; display: block; font-weight: 400; }

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

        /* ── Nav Groups ── */
        .nav-group { border-bottom: 1px solid rgba(255,255,255,.04); }
        .nav-group-label {
            display: flex; align-items: center; padding: 10px 16px 4px; cursor: pointer; user-select: none; gap: 6px;
        }
        .nav-group-label span:first-child { font-size: .63rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1.2px; color: #d97706; }
        .nav-group-label .group-badge {
            font-size: .55rem; font-weight: 600; padding: 1px 6px; border-radius: 8px;
            background: rgba(217,119,6,.25); color: #fbbf24; line-height: 1.5;
        }
        .nav-group-label i { font-size: .55rem; color: #d97706; margin-left: auto; transition: transform .25s; flex-shrink: 0; }
        .nav-group.open > .nav-group-label i { transform: rotate(180deg); }
        .nav-group-items { max-height: 0; overflow: hidden; transition: max-height .35s cubic-bezier(.4,0,.2,1); }
        .nav-group.open > .nav-group-items { max-height: 2000px; }
        .nav-group.search-match > .nav-group-items { max-height: 2000px; }

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
        .page-link:hover { background: linear-gradient(135deg, #d97706, #f59e0b); color: #fff; border-color: transparent; }
        .page-item.active .page-link { background: linear-gradient(135deg, #b45309, #d97706); border-color: transparent; color: #fff; }
        .page-item.disabled .page-link { background: #fafaf9; color: #a8a29e; border-color: #e7e5e4; }

        /* ── Cards ── */
        .card { border: none; border-radius: var(--card-radius); box-shadow: 0 1px 3px rgba(0,0,0,.06); transition: box-shadow .2s; }
        .card:hover { box-shadow: 0 4px 12px rgba(0,0,0,.08); }
        .stat-card { border-radius: var(--card-radius); padding: 20px; color: #fff; position: relative; overflow: hidden; }
        .stat-card::after { content: ''; position: absolute; top: -20px; right: -20px; width: 80px; height: 80px; border-radius: 50%; background: rgba(255,255,255,.1); }

        /* ── Tables ── */
        .table { font-size: .82rem; }
        .table thead th { font-weight: 600; color: #57534e; text-transform: uppercase; font-size: .72rem; letter-spacing: .5px; }
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

        /* ── Modal & Forms ── */
        .modal-content { border: none; border-radius: var(--card-radius); }
        .form-control, .form-select { font-size: .82rem; border-radius: 8px; border-color: #e7e5e4; padding: 9px 14px; }
        .form-control:focus, .form-select:focus { border-color: var(--primary-light); box-shadow: 0 0 0 3px rgba(217,119,6,.12); }
        .form-label { font-size: .8rem; font-weight: 500; color: #57534e; }

        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-thumb { background: #d6d3d1; border-radius: 6px; }

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
            padding: 18px 20px; border-bottom: 1px solid #e7e5e4;
            display: flex; justify-content: space-between; align-items: center; flex-shrink: 0;
            background: linear-gradient(135deg, #d97706, #f59e0b);
        }
        .sd-header h6 { color: #fff; font-size: .88rem; margin: 0; font-weight: 700; }
        .sd-close { background: rgba(255,255,255,.2); border: none; color: #fff; width: 30px; height: 30px; border-radius: 8px; cursor: pointer; font-size: .9rem; display: flex; align-items: center; justify-content: center; transition: .2s; }
        .sd-close:hover { background: rgba(255,255,255,.35); }
        .sd-body { flex: 1; overflow-y: auto; padding: 0; }
        .sd-section { padding: 14px 20px; border-bottom: 1px solid #f5f5f4; }
        .sd-section-title { font-size: .65rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #d97706; margin-bottom: 10px; }
        .sd-widget {
            display: flex; align-items: center; gap: 12px; padding: 10px 14px;
            border-radius: 12px; cursor: pointer; transition: .15s; text-decoration: none; color: #44403c; font-size: .82rem;
        }
        .sd-widget:hover { background: #fef3c7; color: #b45309; }
        .sd-widget i { font-size: 1.1rem; width: 22px; text-align: center; }
        .sd-widget .sd-desc { font-size: .68rem; color: #a8a29e; margin-top: 1px; }
        .sd-toggle { margin-left: auto; }
        .sd-toggle input[type="checkbox"] { display: none; }
        .sd-toggle label {
            width: 38px; height: 22px; background: #d6d3d1; border-radius: 20px;
            display: block; position: relative; cursor: pointer; transition: .2s;
        }
        .sd-toggle label::after {
            content: ''; width: 18px; height: 18px; background: #fff; border-radius: 50%;
            position: absolute; top: 2px; left: 2px; transition: .2s; box-shadow: 0 1px 3px rgba(0,0,0,.15);
        }
        .sd-toggle input:checked + label { background: #d97706; }
        .sd-toggle input:checked + label::after { left: 18px; }
        .sd-stat-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-top: 8px; }
        .sd-stat-card {
            padding: 12px; border-radius: 10px; text-align: center; text-decoration: none;
            transition: .15s; border: 1px solid #f5f5f4;
        }
        .sd-stat-card:hover { border-color: #fde68a; background: #fffbeb; transform: translateY(-1px); }
        .sd-stat-card .sd-stat-num { font-size: 1.2rem; font-weight: 700; color: #1c1917; }
        .sd-stat-card .sd-stat-label { font-size: .65rem; color: #78716c; margin-top: 2px; }
        .sd-stat-card i { font-size: 1.3rem; margin-bottom: 4px; }

        /* ── AI Chat Popup (Bottom-Right) ── */
        .ai-popup {
            position: fixed; bottom: 20px; right: 20px; width: 400px; height: 560px;
            border-radius: 20px; z-index: 1060; display: none; flex-direction: column;
            box-shadow: 0 20px 60px rgba(0,0,0,.25); overflow: hidden;
            background: linear-gradient(180deg, #1c1917 0%, #292524 50%, #3f3b37 100%);
            animation: aiSlideUp .35s cubic-bezier(.4,0,.2,1);
        }
        .ai-popup.show { display: flex; }
        @keyframes aiSlideUp { from { opacity: 0; transform: translateY(24px) scale(.97); } to { opacity: 1; transform: translateY(0) scale(1); } }
        .ai-popup .fp-header {
            padding: 16px 20px; display: flex; align-items: center; justify-content: space-between; flex-shrink: 0;
            background: rgba(255,255,255,.05); border-bottom: 1px solid rgba(255,255,255,.1);
        }
        .ai-popup .fp-header h6 { color: #fff; margin: 0; font-size: .88rem; font-weight: 700; display: flex; align-items: center; gap: 10px; }
        .ai-popup .fp-close {
            width: 30px; height: 30px; border-radius: 8px; border: none; cursor: pointer;
            display: flex; align-items: center; justify-content: center; font-size: .9rem;
            background: rgba(255,255,255,.1); color: #fbbf24; transition: .2s;
        }
        .ai-popup .fp-close:hover { background: rgba(255,255,255,.2); color: #fff; }
        .ai-popup .fp-body { flex: 1; overflow-y: hidden; padding: 0; display: flex; flex-direction: column; }
        .ai-messages { flex: 1; overflow-y: auto; padding: 16px 20px; }
        .ai-messages::-webkit-scrollbar { width: 3px; }
        .ai-messages::-webkit-scrollbar-thumb { background: rgba(255,255,255,.15); border-radius: 3px; }
        .ai-msg { margin-bottom: 14px; display: flex; gap: 10px; animation: fadeIn .3s ease; }
        .ai-msg.bot .ai-msg-avatar { width: 32px; height: 32px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; overflow: hidden; }
        .ai-msg.user { flex-direction: row-reverse; }
        .ai-msg-bubble { max-width: 82%; padding: 10px 14px; border-radius: 14px; font-size: .8rem; line-height: 1.6; }
        .ai-msg.bot .ai-msg-bubble { background: rgba(255,255,255,.08); color: #e7e5e4; border-bottom-left-radius: 4px; }
        .ai-msg.bot .ai-msg-bubble ul { margin: 6px 0 0; padding-left: 16px; font-size: .76rem; }
        .ai-msg.bot .ai-msg-bubble strong { color: #fbbf24; }
        .ai-msg.user .ai-msg-bubble { background: linear-gradient(135deg, #d97706, #f59e0b); color: #fff; border-bottom-right-radius: 4px; }
        .ai-input-area {
            padding: 14px 16px; border-top: 1px solid rgba(255,255,255,.1);
            display: flex; gap: 8px; align-items: flex-end; flex-shrink: 0;
        }
        .ai-input-area textarea {
            flex: 1; background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.12);
            border-radius: 12px; padding: 10px 14px; color: #e7e5e4; font-size: .8rem;
            resize: none; outline: none; font-family: inherit; min-height: 42px; max-height: 100px;
        }
        .ai-input-area textarea::placeholder { color: rgba(255,255,255,.3); }
        .ai-input-area textarea:focus { border-color: rgba(217,119,6,.5); background: rgba(255,255,255,.12); }
        .ai-action-btn {
            width: 38px; height: 38px; border-radius: 10px; border: none; cursor: pointer;
            display: flex; align-items: center; justify-content: center; font-size: 1rem;
            transition: .2s; flex-shrink: 0;
        }
        .ai-send-btn { background: linear-gradient(135deg, #d97706, #f59e0b); color: #fff; }
        .ai-send-btn:hover { background: linear-gradient(135deg, #b45309, #d97706); }
        .ai-voice-btn { background: rgba(255,255,255,.08); color: #fbbf24; }
        .ai-voice-btn:hover { background: rgba(255,255,255,.15); color: #fff; }
        .ai-voice-btn.recording { background: #ef4444; color: #fff; animation: pulse 1.2s infinite; }
        @keyframes pulse { 0%,100% { opacity: 1; } 50% { opacity: .6; } }
        .ai-quick-actions { padding: 8px 16px; display: flex; gap: 6px; flex-wrap: wrap; border-top: 1px solid rgba(255,255,255,.06); flex-shrink: 0; }
        .ai-quick-btn {
            padding: 5px 12px; border-radius: 20px; border: 1px solid rgba(255,255,255,.12);
            background: rgba(255,255,255,.05); color: #d6d3d1; font-size: .66rem;
            cursor: pointer; transition: .2s; white-space: nowrap;
        }
        .ai-quick-btn:hover { background: rgba(217,119,6,.25); border-color: rgba(217,119,6,.35); color: #fff; }

        /* ── 3D AI Avatar ── */
        .ai-3d-icon {
            width: 32px; height: 32px; border-radius: 10px; position: relative;
            background: linear-gradient(135deg, #d97706, #f59e0b);
            display: flex; align-items: center; justify-content: center;
            animation: ai3dFloat 3s ease-in-out infinite;
            box-shadow: 0 4px 12px rgba(217,119,6,.4);
        }
        .ai-3d-icon::before {
            content: ''; position: absolute; inset: -2px; border-radius: 12px;
            background: conic-gradient(from 0deg, #d97706, #f59e0b, #fbbf24, #fcd34d, #f59e0b, #d97706);
            z-index: -1; animation: ai3dSpin 4s linear infinite; opacity: .6;
        }
        .ai-3d-icon i { color: #fff; font-size: .85rem; filter: drop-shadow(0 0 4px rgba(255,255,255,.5)); }
        @keyframes ai3dFloat {
            0%,100% { transform: translateY(0) rotateY(0deg); }
            25% { transform: translateY(-2px) rotateY(5deg); }
            75% { transform: translateY(1px) rotateY(-3deg); }
        }
        @keyframes ai3dSpin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
        .ai-3d-header-icon {
            width: 28px; height: 28px; border-radius: 8px; position: relative;
            background: linear-gradient(135deg, #d97706, #fbbf24);
            display: inline-flex; align-items: center; justify-content: center;
            animation: ai3dFloat 3s ease-in-out infinite;
        }
        .ai-3d-header-icon i { color: #fff; font-size: .75rem; }

        /* ── Floating AI FAB (3D) ── */
        .fab-ai {
            position: fixed; bottom: 20px; right: 20px; width: 54px; height: 54px;
            border-radius: 16px; border: none; cursor: pointer; z-index: 1055;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.4rem; transition: all .3s;
            background: linear-gradient(135deg, #b45309, #d97706);
            color: #fff; perspective: 200px;
            box-shadow: 0 4px 20px rgba(217,119,6,.5), 0 0 40px rgba(217,119,6,.15);
        }
        .fab-ai::before {
            content: ''; position: absolute; inset: -3px; border-radius: 19px;
            background: conic-gradient(from 0deg, #d97706, #f59e0b, #fbbf24, #fcd34d, #f59e0b, #d97706);
            z-index: -1; animation: ai3dSpin 3s linear infinite; opacity: .5;
        }
        .fab-ai::after {
            content: ''; position: absolute; inset: 0; border-radius: 16px;
            background: linear-gradient(135deg, #b45309, #d97706); z-index: -1;
        }
        .fab-ai:hover { transform: scale(1.1) rotateY(10deg); box-shadow: 0 8px 30px rgba(217,119,6,.6); }
        .fab-ai.hidden { display: none; }

        @media (max-width: 767px) {
            .ai-popup { width: calc(100vw - 24px); right: 12px !important; bottom: 12px; height: 70vh; }
            .settings-drawer { width: 100vw; }
            .fab-ai { right: 12px; bottom: 12px; width: 48px; height: 48px; border-radius: 14px; }
        }

        @media print {
            .sidebar, .top-header { display: none !important; }
            .main-content { margin-left: 0 !important; }
            .page-content { padding: 0 !important; }
        }

        @stack('styles')
    </style>
</head>
<body>
    @include('peran.kepala-sekolah.sidebar')

    {{-- ═══ Settings Right Drawer ═══ --}}
    <div class="settings-drawer-overlay" id="settingsOverlay"></div>
    <div class="settings-drawer" id="settingsDrawer">
        <div class="sd-header">
            <h6><i class="bi bi-lightning-charge-fill me-2"></i>Pengaturan & Alat Cepat</h6>
            <button class="sd-close" id="closeSettingsDrawer"><i class="bi bi-x-lg"></i></button>
        </div>
        <div class="sd-body">
            <div class="sd-section">
                <div class="sd-section-title">Ringkasan Cepat</div>
                <div class="sd-stat-grid">
                    <a href="{{ route('kepala-sekolah.pegawai.index') }}" class="sd-stat-card" style="text-decoration:none;">
                        <i class="bi bi-people-fill" style="color:#d97706;"></i>
                        <div class="sd-stat-num">{{ \App\Models\Pengguna::whereIn('peran', \App\Models\Pengguna::STAFF_ROLES)->where('aktif', true)->count() }}</div>
                        <div class="sd-stat-label">Staf Aktif</div>
                    </a>
                    <a href="{{ route('kepala-sekolah.izin.index', ['status' => 'pending']) }}" class="sd-stat-card" style="text-decoration:none;">
                        <i class="bi bi-hourglass-split" style="color:#f59e0b;"></i>
                        <div class="sd-stat-num">{{ \App\Models\PengajuanIzin::where('status', 'pending')->count() }}</div>
                        <div class="sd-stat-label">Izin Pending</div>
                    </a>
                    <a href="{{ route('kepala-sekolah.notifikasi.index') }}" class="sd-stat-card" style="text-decoration:none;">
                        <i class="bi bi-bell-fill" style="color:#ef4444;"></i>
                        <div class="sd-stat-num">{{ \App\Models\Notifikasi::where('sudah_dibaca', false)->count() }}</div>
                        <div class="sd-stat-label">Notifikasi Baru</div>
                    </a>
                    <a href="{{ route('kepala-sekolah.skp.index', ['status' => 'diajukan']) }}" class="sd-stat-card" style="text-decoration:none;">
                        <i class="bi bi-file-earmark-bar-graph-fill" style="color:#10b981;"></i>
                        <div class="sd-stat-num">{{ \App\Models\Skp::where('status', 'diajukan')->count() }}</div>
                        <div class="sd-stat-label">SKP Pending</div>
                    </a>
                </div>
            </div>
            <div class="sd-section">
                <div class="sd-section-title">Tampilan</div>
                <div class="sd-widget">
                    <i class="bi bi-moon-stars" style="color:#d97706;"></i>
                    <div><div>Mode Gelap</div><div class="sd-desc">Tema gelap untuk kenyamanan mata</div></div>
                    <div class="sd-toggle ms-auto">
                        <input type="checkbox" id="darkModeToggle"><label for="darkModeToggle"></label>
                    </div>
                </div>
            </div>
            <div class="sd-section">
                <div class="sd-section-title">Navigasi Cepat</div>
                <a href="{{ route('kepala-sekolah.resolusi.index') }}" class="sd-widget">
                    <i class="bi bi-stamp" style="color:#d97706;"></i>
                    <div><div>Resolusi</div><div class="sd-desc">Keputusan & resolusi kepala sekolah</div></div>
                </a>
                <a href="{{ route('kepala-sekolah.rekap-eksekutif.index') }}" class="sd-widget">
                    <i class="bi bi-bar-chart-line-fill" style="color:#10b981;"></i>
                    <div><div>Rekap Eksekutif</div><div class="sd-desc">Ringkasan data sekolah</div></div>
                </a>
                <a href="{{ route('kepala-sekolah.siatu-ai.index') }}" class="sd-widget">
                    <i class="bi bi-robot" style="color:#0ea5e9;"></i>
                    <div><div>SIMPEG-AI</div><div class="sd-desc">Analisis data & laporan AI</div></div>
                </a>
                <a href="{{ route('kepala-sekolah.pengaturan.index') }}" class="sd-widget">
                    <i class="bi bi-gear-fill" style="color:#f59e0b;"></i>
                    <div><div>Pengaturan</div><div class="sd-desc">Profil, password, tampilan</div></div>
                </a>
            </div>
            <div class="sd-section" style="border-bottom:none;">
                <div class="sd-section-title">Fitur Khusus</div>
                <a href="{{ route('kepala-sekolah.profil.edit') }}" class="sd-widget">
                    <i class="bi bi-person-fill" style="color:#8b5cf6;"></i>
                    <div><div>Edit Profil</div><div class="sd-desc">Ubah data profil Anda</div></div>
                </a>
                <a href="{{ route('kepala-sekolah.panduan.index') }}" class="sd-widget">
                    <i class="bi bi-book" style="color:#2563eb;"></i>
                    <div><div>Panduan</div><div class="sd-desc">Bantuan penggunaan sistem</div></div>
                </a>
            </div>
        </div>
    </div>

    <div class="main-content" id="mainContent">
        @include('peran.kepala-sekolah.header')

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

        @include('peran.kepala-sekolah.footer')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('[data-toggle="nav-group"]').forEach(label => {
            label.addEventListener('click', function() {
                const group = this.closest('.nav-group');
                const isOpening = !group.classList.contains('open');

                if (isOpening) {
                    group.classList.add('open');
                    const items = group.querySelector('.nav-group-items');
                    if (items) items.style.maxHeight = items.scrollHeight + 'px';
                } else {
                    group.querySelectorAll('.nav-item.open').forEach(ni => {
                        ni.classList.remove('open');
                        const sub = ni.querySelector('.submenu');
                        if (sub) sub.style.maxHeight = '';
                    });
                    group.classList.remove('open');
                    const items = group.querySelector('.nav-group-items');
                    if (items) items.style.maxHeight = '';
                }
            });
        });
    });
    </script>

    {{-- ═══ AI Chat Popup (Bottom-Right) with 3D Icon ═══ --}}
    <div class="ai-popup" id="aiPopup">
        <div class="fp-header">
            <h6>
                <span class="ai-3d-header-icon"><i class="bi bi-robot"></i></span>
                SIMPEG-AI Assistant
            </h6>
            <button class="fp-close" id="closeAi"><i class="bi bi-x-lg"></i></button>
        </div>
        <div class="fp-body">
            <div class="ai-messages" id="aiMessages">
                <div class="ai-msg bot">
                    <div class="ai-msg-avatar">
                        <div class="ai-3d-icon"><i class="bi bi-robot"></i></div>
                    </div>
                    <div class="ai-msg-bubble">
                        Halo! Saya <strong>SIMPEG-AI</strong>, asisten cerdas Anda. Tanyakan apa saja:
                        <ul>
                            <li>Panduan fitur & cara penggunaan</li>
                            <li>Monitoring kehadiran & kinerja</li>
                            <li>Persetujuan izin & SKP</li>
                            <li>Alur administrasi sekolah</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="ai-quick-actions" id="aiQuickActions">
                <button class="ai-quick-btn" data-prompt="Jelaskan fitur apa saja yang ada di SIMPEG-SMART"><i class="bi bi-grid me-1"></i>Fitur</button>
                <button class="ai-quick-btn" data-prompt="Bagaimana cara melihat rekap kehadiran?"><i class="bi bi-fingerprint me-1"></i>Kehadiran</button>
                <button class="ai-quick-btn" data-prompt="Bagaimana cara approve SKP pegawai?"><i class="bi bi-file-earmark-check me-1"></i>SKP</button>
                <button class="ai-quick-btn" data-prompt="Bagaimana proses persetujuan izin?"><i class="bi bi-calendar2-check me-1"></i>Izin</button>
                <button class="ai-quick-btn" data-prompt="Panduan penggunaan lengkap"><i class="bi bi-book me-1"></i>Panduan</button>
            </div>
            <div class="ai-input-area">
                <button class="ai-action-btn ai-voice-btn" id="aiVoice" title="Bicara"><i class="bi bi-mic-fill"></i></button>
                <textarea id="aiInput" placeholder="Tanya apa saja tentang SIMPEG-SMART..." rows="1"></textarea>
                <button class="ai-action-btn ai-send-btn" id="aiSend" title="Kirim"><i class="bi bi-send-fill"></i></button>
            </div>
        </div>
    </div>

    <button class="fab-ai" id="fabAi" title="SIMPEG-AI Assistant"><i class="bi bi-robot"></i></button>

    @include('komponen.notifikasi-popup')
    @stack('scripts')
</body>
</html>
