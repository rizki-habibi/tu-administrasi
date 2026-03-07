<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kinerja Publik - SIATU | SMA Negeri 2 Jember</title>
    <meta name="description" content="Halaman kinerja publik Sistem Informasi Administrasi Tata Usaha SMA Negeri 2 Jember.">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --indigo: #4f46e5; --indigo-dark: #3730a3; --indigo-light: #818cf8; --indigo-50: #eef2ff;
            --emerald: #059669; --emerald-dark: #047857; --emerald-light: #34d399; --emerald-50: #ecfdf5;
            --amber: #d97706; --amber-dark: #b45309; --amber-light: #fbbf24; --amber-50: #fffbeb;
            --rose: #e11d48; --cyan: #0891b2; --violet: #7c3aed;
            --dark: #0f172a; --dark-2: #1e293b; --gray: #64748b; --gray-light: #94a3b8;
            --light: #f8fafc; --white: #ffffff;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; color: #1e293b; overflow-x: hidden; }
        html { scroll-behavior: smooth; }

        /* ═══════════════════ NAVBAR ═══════════════════ */
        .navbar-main {
            position: fixed; top: 0; left: 0; right: 0; z-index: 1050;
            padding: 16px 0; transition: all .35s ease; background: transparent;
        }
        .navbar-main.scrolled {
            padding: 10px 0; background: rgba(15, 23, 42, .95);
            backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
            box-shadow: 0 4px 30px rgba(0,0,0,.2); border-bottom: 1px solid rgba(255,255,255,.06);
        }
        .nav-brand { display: flex; align-items: center; gap: 12px; text-decoration: none; }
        .nav-brand img { width: 42px; height: 42px; border-radius: 12px; object-fit: contain; border: 2px solid rgba(255,255,255,.15); }
        .nav-brand .txt h6 { color: #fff; font-weight: 800; font-size: .9rem; margin: 0; letter-spacing: -.3px; font-family: 'Space Grotesk', sans-serif; }
        .nav-brand .txt small { color: var(--gray-light); font-size: .65rem; }
        .nav-pills-custom { display: flex; align-items: center; gap: 4px; }
        .nav-pills-custom a {
            color: rgba(255,255,255,.7); font-size: .8rem; font-weight: 500; text-decoration: none;
            padding: 8px 14px; border-radius: 10px; transition: all .2s;
        }
        .nav-pills-custom a:hover { color: #fff; background: rgba(255,255,255,.1); }
        .nav-pills-custom a.active { color: var(--amber-light); }
        .btn-login {
            background: linear-gradient(135deg, var(--emerald), var(--cyan));
            color: #fff !important; padding: 10px 26px !important; border-radius: 12px !important;
            font-weight: 700 !important; border: none; font-size: .82rem;
            box-shadow: 0 4px 20px rgba(5,150,105,.4); transition: all .3s !important;
        }
        .btn-login:hover { transform: translateY(-2px); box-shadow: 0 8px 30px rgba(5,150,105,.55); }
        .mobile-toggle { display: none; background: none; border: none; color: #fff; font-size: 1.5rem; cursor: pointer; }

        /* ═══════════════════ HERO ═══════════════════ */
        .hero-kinerja {
            min-height: 50vh; display: flex; align-items: center; position: relative; overflow: hidden;
            background: var(--dark); padding-top: 100px; padding-bottom: 60px;
        }
        .hero-kinerja .hero-bg {
            position: absolute; inset: 0; z-index: 0;
            background:
                radial-gradient(ellipse 80% 50% at 20% 40%, rgba(5,150,105,.25), transparent),
                radial-gradient(ellipse 60% 60% at 80% 20%, rgba(79,70,229,.2), transparent),
                radial-gradient(ellipse 50% 40% at 60% 80%, rgba(217,119,6,.12), transparent),
                linear-gradient(180deg, #0f172a 0%, #0f2027 100%);
        }
        .hero-kinerja .grid-overlay {
            position: absolute; inset: 0; z-index: 0; opacity: .04;
            background-image: linear-gradient(rgba(255,255,255,.5) 1px, transparent 1px),
                              linear-gradient(90deg, rgba(255,255,255,.5) 1px, transparent 1px);
            background-size: 60px 60px;
        }
        .hero-kinerja .hero-glow {
            position: absolute; width: 400px; height: 400px; border-radius: 50%;
            background: radial-gradient(circle, rgba(5,150,105,.25), transparent 70%);
            top: -100px; right: 10%; animation: float 10s ease-in-out infinite; z-index: 0;
        }
        @keyframes float {
            0%,100% { transform: translateY(0) scale(1); opacity:.6; }
            50% { transform: translateY(-30px) scale(1.1); opacity:1; }
        }
        .hero-kinerja .hero-content { position: relative; z-index: 2; }
        .hero-kinerja h1 {
            font-family: 'Space Grotesk', sans-serif; color: #fff; font-size: 2.8rem;
            font-weight: 800; line-height: 1.1; margin-bottom: 16px; letter-spacing: -1px;
        }
        .hero-kinerja h1 .text-grad {
            background: linear-gradient(135deg, var(--emerald-light), var(--cyan));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        }
        .hero-kinerja .lead { color: rgba(203,213,225,.9); font-size: 1rem; line-height: 1.7; max-width: 600px; }
        .hero-pill {
            display: inline-flex; align-items: center; gap: 8px;
            background: rgba(255,255,255,.07); border: 1px solid rgba(255,255,255,.12);
            border-radius: 100px; padding: 6px 18px 6px 8px; margin-bottom: 20px;
            backdrop-filter: blur(10px);
        }
        .hero-pill .dot { width: 10px; height: 10px; border-radius: 50%; background: var(--emerald-light); animation: blink 2s ease infinite; }
        .hero-pill span { color: #c7d2fe; font-size: .72rem; font-weight: 500; }
        @keyframes blink { 0%,100% { opacity:1; } 50% { opacity:.3; } }

        /* ═══════════════════ SECTIONS ═══════════════════ */
        .section { padding: 80px 0; }
        .section-chip {
            display: inline-flex; align-items: center; gap: 8px; padding: 6px 16px;
            border-radius: 100px; font-size: .7rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: .8px; margin-bottom: 16px;
        }
        .chip-indigo { background: var(--indigo-50); color: var(--indigo); }
        .chip-emerald { background: var(--emerald-50); color: var(--emerald); }
        .chip-amber { background: var(--amber-50); color: var(--amber); }
        .chip-white { background: rgba(255,255,255,.1); color: rgba(255,255,255,.8); }
        .section-heading {
            font-family: 'Space Grotesk', sans-serif; font-size: 2.2rem;
            font-weight: 800; color: var(--dark); line-height: 1.15; margin-bottom: 14px; letter-spacing: -.5px;
        }
        .section-desc { color: var(--gray); font-size: .92rem; line-height: 1.7; max-width: 600px; }
        .bg-light-custom { background: var(--light); }

        /* ═══════════════════ UNGGULAN CARDS ═══════════════════ */
        .unggulan-card {
            background: var(--white); border: 1px solid #e5e7eb; border-radius: 20px;
            overflow: hidden; transition: all .35s; height: 100%;
        }
        .unggulan-card:hover { transform: translateY(-6px); box-shadow: 0 16px 40px rgba(0,0,0,.08); border-color: transparent; }
        .unggulan-card .card-thumb {
            height: 180px; background: linear-gradient(135deg, var(--indigo), var(--violet));
            display: flex; align-items: center; justify-content: center; position: relative; overflow: hidden;
        }
        .unggulan-card .card-thumb img { width: 100%; height: 100%; object-fit: cover; }
        .unggulan-card .card-thumb .thumb-icon { font-size: 3rem; color: rgba(255,255,255,.5); }
        .unggulan-card .card-thumb .badge-cat {
            position: absolute; top: 12px; left: 12px; font-size: .65rem; font-weight: 700;
            padding: 4px 12px; border-radius: 100px; background: rgba(0,0,0,.4);
            color: #fff; backdrop-filter: blur(8px);
        }
        .unggulan-card .card-body-custom { padding: 22px; }
        .unggulan-card .card-body-custom h5 { font-size: .92rem; font-weight: 700; color: var(--dark); margin-bottom: 6px; }
        .unggulan-card .card-body-custom p { font-size: .8rem; color: var(--gray); line-height: 1.6; margin: 0; }

        /* ═══════════════════ KONTEN CARDS ═══════════════════ */
        .konten-card {
            background: var(--white); border: 1px solid #e5e7eb; border-radius: 16px;
            padding: 24px; transition: all .3s; height: 100%;
        }
        .konten-card:hover { transform: translateY(-4px); box-shadow: 0 12px 30px rgba(0,0,0,.06); }
        .konten-card .konten-icon {
            width: 48px; height: 48px; border-radius: 14px; display: flex;
            align-items: center; justify-content: center; font-size: 1.2rem; color: #fff; margin-bottom: 14px;
        }
        .konten-card h6 { font-size: .88rem; font-weight: 700; color: var(--dark); margin-bottom: 6px; }
        .konten-card p { font-size: .78rem; color: var(--gray); line-height: 1.6; margin: 0; }
        .konten-card .konten-type {
            display: inline-flex; align-items: center; gap: 4px; font-size: .65rem;
            color: var(--gray-light); margin-top: 10px;
        }

        /* ═══════════════════ KATEGORI ═══════════════════ */
        .kategori-header {
            display: flex; align-items: center; gap: 12px; margin-bottom: 24px; padding-bottom: 12px;
            border-bottom: 2px solid #e5e7eb;
        }
        .kategori-header .kat-icon {
            width: 44px; height: 44px; border-radius: 12px; display: flex;
            align-items: center; justify-content: center; font-size: 1.1rem; color: #fff;
        }
        .kategori-header h3 { font-family: 'Space Grotesk', sans-serif; font-size: 1.3rem; font-weight: 700; color: var(--dark); margin: 0; }
        .kategori-header small { color: var(--gray); font-size: .78rem; }

        /* ═══════════════════ SARAN FORM ═══════════════════ */
        .saran-section {
            background: linear-gradient(135deg, #0f172a, #1e1b4b); position: relative; overflow: hidden;
        }
        .saran-section::before {
            content: ''; position: absolute; width: 400px; height: 400px; border-radius: 50%;
            background: radial-gradient(circle, rgba(99,102,241,.15), transparent 70%);
            top: -200px; right: -100px;
        }
        .saran-card {
            background: rgba(255,255,255,.04); border: 1px solid rgba(255,255,255,.1);
            border-radius: 20px; padding: 36px; backdrop-filter: blur(10px); position: relative; z-index: 1;
        }
        .saran-card h3 { color: #fff; font-family: 'Space Grotesk', sans-serif; font-weight: 800; font-size: 1.5rem; margin-bottom: 6px; }
        .saran-card p { color: var(--gray-light); font-size: .88rem; margin-bottom: 24px; }
        .saran-card .form-control, .saran-card .form-select {
            background: rgba(255,255,255,.06); border: 1px solid rgba(255,255,255,.12);
            color: #e2e8f0; border-radius: 12px; padding: 12px 16px; font-size: .85rem;
        }
        .saran-card .form-control::placeholder { color: rgba(148,163,184,.5); }
        .saran-card .form-control:focus {
            background: rgba(255,255,255,.08); border-color: var(--indigo-light);
            box-shadow: 0 0 0 3px rgba(99,102,241,.15); color: #fff;
        }
        .saran-card label { color: var(--gray-light); font-size: .78rem; font-weight: 600; margin-bottom: 6px; }
        .btn-saran {
            background: linear-gradient(135deg, var(--emerald), var(--cyan));
            color: #fff; padding: 14px 32px; border-radius: 14px; border: none;
            font-weight: 700; font-size: .88rem; transition: all .3s;
            box-shadow: 0 4px 20px rgba(5,150,105,.4);
        }
        .btn-saran:hover { transform: translateY(-2px); box-shadow: 0 8px 30px rgba(5,150,105,.55); color:#fff; }

        /* ═══════════════════ EMPTY STATE ═══════════════════ */
        .empty-state {
            text-align: center; padding: 60px 20px;
            background: var(--light); border-radius: 20px; border: 2px dashed #e5e7eb;
        }
        .empty-state i { font-size: 3rem; color: var(--gray-light); margin-bottom: 16px; display: block; }
        .empty-state h5 { color: var(--dark); font-weight: 700; font-size: 1.1rem; margin-bottom: 6px; }
        .empty-state p { color: var(--gray); font-size: .85rem; }

        /* ═══════════════════ FOOTER ═══════════════════ */
        .footer {
            background: var(--dark); padding: 50px 0 0; position: relative; overflow: hidden;
        }
        .footer::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; height: 4px;
            background: linear-gradient(90deg, var(--indigo), var(--emerald), var(--amber), var(--indigo));
            background-size: 200% 100%; animation: gradient-shift 6s linear infinite;
        }
        @keyframes gradient-shift { 0% { background-position: 0% 0%; } 100% { background-position: 200% 0%; } }
        .footer h5 { color: #fff; font-size: .88rem; font-weight: 700; margin-bottom: 14px; font-family: 'Space Grotesk', sans-serif; }
        .footer p, .footer a { color: var(--gray-light); font-size: .8rem; line-height: 1.7; }
        .footer a { text-decoration: none; transition: color .2s; }
        .footer a:hover { color: var(--indigo-light); }
        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,.06); margin-top: 30px;
            padding: 18px 0; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;
        }
        .footer-bottom p { color: var(--gray); font-size: .72rem; margin: 0; }
        .footer-socials { display: flex; gap: 8px; }
        .footer-socials a {
            width: 34px; height: 34px; border-radius: 10px; display: flex;
            align-items: center; justify-content: center; background: rgba(255,255,255,.06);
            border: 1px solid rgba(255,255,255,.08); color: var(--gray-light); font-size: .85rem; transition: all .2s;
        }
        .footer-socials a:hover { background: var(--indigo); color: #fff; border-color: var(--indigo); }

        /* ═══════════════════ SCROLL TO TOP ═══════════════════ */
        .scroll-top {
            position: fixed; bottom: 30px; right: 30px; z-index: 999;
            width: 48px; height: 48px; border-radius: 14px;
            background: linear-gradient(135deg, var(--indigo), var(--violet));
            color: #fff; border: none; font-size: 1.2rem; cursor: pointer;
            box-shadow: 0 4px 20px rgba(79,70,229,.4); transition: all .3s;
            opacity: 0; visibility: hidden; transform: translateY(10px);
        }
        .scroll-top.visible { opacity: 1; visibility: visible; transform: translateY(0); }
        .scroll-top:hover { transform: translateY(-3px); box-shadow: 0 8px 30px rgba(79,70,229,.6); }

        /* ═══════════════════ ANIMATIONS ═══════════════════ */
        .fade-up { opacity: 0; transform: translateY(30px); transition: all .7s cubic-bezier(.16,1,.3,1); }
        .fade-up.visible { opacity: 1; transform: translateY(0); }

        /* ═══════════════════ SCROLLBAR ═══════════════════ */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: var(--dark); }
        ::-webkit-scrollbar-thumb { background: var(--indigo); border-radius: 4px; }

        /* ═══════════════════ RESPONSIVE ═══════════════════ */
        @media(max-width:991px) {
            .hero-kinerja h1 { font-size: 2rem; }
            .section-heading { font-size: 1.7rem; }
        }
        @media(max-width:767px) {
            .mobile-toggle { display: block; }
            .nav-pills-custom {
                display: none; flex-direction: column; position: absolute; top: 100%; left: 0; right: 0;
                background: rgba(15,23,42,.98); padding: 16px; gap: 4px;
                border-bottom: 1px solid rgba(255,255,255,.08);
            }
            .nav-pills-custom.show { display: flex; }
            .nav-pills-custom a { padding: 12px 16px; width: 100%; }
            .hero-kinerja { min-height: auto; padding-top: 100px; padding-bottom: 40px; }
            .hero-kinerja h1 { font-size: 1.6rem; }
            .section { padding: 50px 0; }
            .section-heading { font-size: 1.4rem; }
            .saran-card { padding: 24px 18px; }
        }
    </style>
</head>
<body>

{{-- ═══════════════════ NAVBAR ═══════════════════ --}}
<nav class="navbar-main scrolled" id="navbar">
    <div class="container d-flex justify-content-between align-items-center">
        <a href="{{ url('/') }}" class="nav-brand">
            <img src="{{ asset('images/logo.png') }}" alt="Logo SMA Negeri 2 Jember" onerror="this.style.display='none'">
            <div class="txt">
                <h6>SIATU</h6>
                <small>SMA Negeri 2 Jember</small>
            </div>
        </a>
        <button class="mobile-toggle" onclick="document.querySelector('.nav-pills-custom').classList.toggle('show')">
            <i class="bi bi-list"></i>
        </button>
        <div class="nav-pills-custom">
            <a href="{{ url('/') }}">Beranda</a>
            <a href="{{ route('kinerja') }}" class="active">Kinerja</a>
            <a href="#konten">Konten</a>
            <a href="#saran">Saran</a>
            <a href="{{ route('login') }}" class="btn-login"><i class="bi bi-box-arrow-in-right"></i> Masuk</a>
        </div>
    </div>
</nav>

{{-- ═══════════════════ HERO ═══════════════════ --}}
<section class="hero-kinerja">
    <div class="hero-bg"></div>
    <div class="grid-overlay"></div>
    <div class="hero-glow"></div>
    <div class="container">
        <div class="hero-content">
            <div class="hero-pill">
                <span class="dot"></span>
                <span>Halaman Publik &bull; Kinerja &amp; Informasi</span>
            </div>
            <h1>Kinerja &amp; Informasi<br><span class="text-grad">Publik Sekolah</span></h1>
            <p class="lead">
                Transparansi informasi dan kinerja administrasi SMA Negeri 2 Jember.
                Lihat dokumen, galeri, prestasi, dan informasi publik lainnya.
            </p>
        </div>
    </div>
</section>

{{-- ═══════════════════ FLASH MESSAGE ═══════════════════ --}}
@if(session('sukses'))
<div class="container mt-4">
    <div class="alert alert-success alert-dismissible fade show" style="border-radius:14px;border:none;background:var(--emerald-50);color:var(--emerald-dark);font-size:.88rem;font-weight:500;">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('sukses') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
</div>
@endif

{{-- ═══════════════════ UNGGULAN ═══════════════════ --}}
@if($unggulan->isNotEmpty())
<section class="section bg-light-custom">
    <div class="container">
        <div class="text-center mb-5 fade-up">
            <div class="section-chip chip-emerald"><i class="bi bi-star-fill"></i> Unggulan</div>
            <h2 class="section-heading">Konten Unggulan</h2>
            <p class="section-desc mx-auto">Informasi dan dokumen yang disorot untuk publik.</p>
        </div>
        <div class="row g-4">
            @foreach($unggulan as $item)
            <div class="col-lg-4 col-md-6 fade-up">
                <div class="unggulan-card">
                    <div class="card-thumb" style="background:linear-gradient(135deg, {{ ['profil'=>'#6366f1,#8b5cf6','visi_misi'=>'#059669,#10b981','pengurus'=>'#d97706,#f59e0b','dokumen'=>'#0891b2,#06b6d4','galeri'=>'#e11d48,#f43f5e','video'=>'#7c3aed,#a78bfa','kerjasama'=>'#14b8a6,#2dd4bf','prestasi'=>'#f97316,#fb923c','pengumuman'=>'#64748b,#94a3b8','saran'=>'#059669,#34d399'][$item->kategori] ?? '#6366f1,#8b5cf6' }});">
                        @if($item->thumbnail)
                            <img src="{{ $item->thumbnail_url }}" alt="{{ $item->judul }}">
                        @else
                            <i class="bi {{ ['profil'=>'bi-building','visi_misi'=>'bi-bullseye','pengurus'=>'bi-people-fill','dokumen'=>'bi-file-earmark-text-fill','galeri'=>'bi-images','video'=>'bi-play-circle-fill','kerjasama'=>'bi-handshake','prestasi'=>'bi-trophy-fill','pengumuman'=>'bi-megaphone-fill','saran'=>'bi-chat-dots-fill'][$item->kategori] ?? 'bi-file-earmark-text-fill' }} thumb-icon"></i>
                        @endif
                        <span class="badge-cat">{{ ucfirst(str_replace('_', ' ', $item->kategori)) }}</span>
                    </div>
                    <div class="card-body-custom">
                        <h5>{{ $item->judul }}</h5>
                        <p>{{ Str::limit($item->deskripsi, 100) }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ═══════════════════ KONTEN PER KATEGORI ═══════════════════ --}}
<section class="section" id="konten">
    <div class="container">
        @if($konten->isNotEmpty())
            @php
                $kategoriMeta = [
                    'profil'      => ['ikon' => 'bi-building',              'warna' => '#6366f1', 'label' => 'Profil Sekolah'],
                    'visi_misi'   => ['ikon' => 'bi-bullseye',              'warna' => '#059669', 'label' => 'Visi & Misi'],
                    'pengurus'    => ['ikon' => 'bi-people-fill',           'warna' => '#d97706', 'label' => 'Pengurus / Struktur'],
                    'dokumen'     => ['ikon' => 'bi-file-earmark-text-fill','warna' => '#0891b2', 'label' => 'Dokumen'],
                    'galeri'      => ['ikon' => 'bi-images',                'warna' => '#e11d48', 'label' => 'Galeri'],
                    'video'       => ['ikon' => 'bi-play-circle-fill',      'warna' => '#7c3aed', 'label' => 'Video'],
                    'kerjasama'   => ['ikon' => 'bi-handshake',             'warna' => '#14b8a6', 'label' => 'Kerjasama'],
                    'prestasi'    => ['ikon' => 'bi-trophy-fill',           'warna' => '#f97316', 'label' => 'Prestasi'],
                    'pengumuman'  => ['ikon' => 'bi-megaphone-fill',        'warna' => '#64748b', 'label' => 'Pengumuman'],
                    'saran'       => ['ikon' => 'bi-chat-dots-fill',        'warna' => '#059669', 'label' => 'Saran & Masukan'],
                ];
            @endphp

            @foreach($konten as $kategori => $items)
                @php $meta = $kategoriMeta[$kategori] ?? ['ikon' => 'bi-folder-fill', 'warna' => '#64748b', 'label' => ucfirst(str_replace('_',' ',$kategori))]; @endphp
                <div class="mb-5 fade-up">
                    <div class="kategori-header">
                        <div class="kat-icon" style="background:linear-gradient(135deg, {{ $meta['warna'] }}, {{ $meta['warna'] }}cc);">
                            <i class="bi {{ $meta['ikon'] }}"></i>
                        </div>
                        <div>
                            <h3>{{ $meta['label'] }}</h3>
                            <small>{{ $items->count() }} konten</small>
                        </div>
                    </div>
                    <div class="row g-3">
                        @foreach($items as $item)
                        <div class="col-lg-4 col-md-6">
                            <div class="konten-card">
                                <div class="konten-icon" style="background:linear-gradient(135deg, {{ $meta['warna'] }}, {{ $meta['warna'] }}bb);">
                                    <i class="bi {{ ['teks'=>'bi-file-text-fill','gambar'=>'bi-image-fill','video'=>'bi-play-btn-fill','dokumen'=>'bi-file-earmark-pdf-fill','link'=>'bi-link-45deg'][$item->tipe] ?? 'bi-file-text-fill' }}"></i>
                                </div>
                                <h6>{{ $item->judul }}</h6>
                                @if($item->deskripsi)
                                    <p>{{ Str::limit($item->deskripsi, 120) }}</p>
                                @endif

                                @if($item->tipe === 'gambar' && $item->path_file)
                                    <div class="mt-2" style="border-radius:10px;overflow:hidden;max-height:200px;">
                                        <img src="{{ $item->file_url }}" alt="{{ $item->judul }}" class="w-100" style="object-fit:cover;">
                                    </div>
                                @endif

                                @if($item->tipe === 'video' && $item->url_external)
                                    <div class="mt-2" style="border-radius:10px;overflow:hidden;">
                                        <div style="position:relative;padding-bottom:56.25%;height:0;">
                                            <iframe src="{{ $item->url_external }}" style="position:absolute;top:0;left:0;width:100%;height:100%;border:none;border-radius:10px;" allowfullscreen loading="lazy"></iframe>
                                        </div>
                                    </div>
                                @endif

                                @if($item->konten && $item->tipe === 'teks')
                                    <div class="mt-2" style="font-size:.78rem;color:var(--gray);line-height:1.6;">
                                        {!! Str::limit(strip_tags($item->konten), 200) !!}
                                    </div>
                                @endif

                                @if($item->path_file && in_array($item->tipe, ['dokumen']))
                                    <a href="{{ $item->file_url }}" target="_blank" class="btn btn-sm mt-2" style="background:var(--indigo-50);color:var(--indigo);font-size:.75rem;font-weight:600;border-radius:10px;padding:6px 14px;">
                                        <i class="bi bi-download me-1"></i> Unduh {{ $item->ukuran_format }}
                                    </a>
                                @endif

                                @if($item->url_external && $item->tipe === 'link')
                                    <a href="{{ $item->url_external }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm mt-2" style="background:var(--emerald-50);color:var(--emerald);font-size:.75rem;font-weight:600;border-radius:10px;padding:6px 14px;">
                                        <i class="bi bi-box-arrow-up-right me-1"></i> Buka Link
                                    </a>
                                @endif

                                <div class="konten-type">
                                    <i class="bi bi-clock"></i> {{ $item->created_at->diffForHumans() }}
                                    &bull;
                                    <i class="bi bi-tag"></i> {{ ucfirst($item->tipe) }}
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        @else
            <div class="empty-state fade-up">
                <i class="bi bi-inbox"></i>
                <h5>Belum Ada Konten</h5>
                <p>Konten publik belum tersedia saat ini. Silakan kembali lagi nanti.</p>
            </div>
        @endif
    </div>
</section>

{{-- ═══════════════════ SARAN ═══════════════════ --}}
<section class="section saran-section" id="saran">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="saran-card fade-up">
                    <div class="text-center mb-4">
                        <div class="section-chip chip-white"><i class="bi bi-chat-heart-fill"></i> Saran &amp; Masukan</div>
                        <h3>Sampaikan Saran Anda</h3>
                        <p>Bantu kami meningkatkan layanan administrasi SMA Negeri 2 Jember.</p>
                    </div>
                    <form action="{{ route('saran.store') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="nama">Nama Lengkap <span style="color:var(--rose);">*</span></label>
                                <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama') }}" placeholder="Masukkan nama Anda" required maxlength="100">
                                @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="email">Email (opsional)</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="email@contoh.com" maxlength="150">
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-12">
                                <label for="subjek">Subjek <span style="color:var(--rose);">*</span></label>
                                <input type="text" class="form-control @error('subjek') is-invalid @enderror" id="subjek" name="subjek" value="{{ old('subjek') }}" placeholder="Tentang apa saran Anda?" required maxlength="200">
                                @error('subjek') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-12">
                                <label for="pesan">Pesan <span style="color:var(--rose);">*</span></label>
                                <textarea class="form-control @error('pesan') is-invalid @enderror" id="pesan" name="pesan" rows="5" placeholder="Tulis saran, pertanyaan, atau masukan Anda di sini..." required maxlength="2000">{{ old('pesan') }}</textarea>
                                @error('pesan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-12 text-center mt-2">
                                <button type="submit" class="btn-saran"><i class="bi bi-send-fill me-2"></i> Kirim Saran</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════ FOOTER ═══════════════════ --}}
<footer class="footer">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" style="width:42px;height:42px;border-radius:12px;object-fit:contain;border:2px solid rgba(255,255,255,.1);" onerror="this.style.display='none'">
                    <div>
                        <h5 class="mb-0">SIATU</h5>
                        <small style="color:var(--gray);font-size:.68rem;">Sistem Informasi Administrasi TU</small>
                    </div>
                </div>
                <p>Platform digital untuk pengelolaan administrasi tata usaha SMA Negeri 2 Jember.</p>
            </div>
            <div class="col-lg-6 text-lg-end">
                <h5>Kontak</h5>
                <p>Jl. Jawa No. 16, Sumbersari, Jember<br>
                <a href="tel:+62331321375">(0331) 321375</a> &bull; <a href="mailto:info@sman2jember.sch.id">info@sman2jember.sch.id</a></p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} SIATU &mdash; SMA Negeri 2 Jember</p>
            <div class="footer-socials">
                <a href="{{ url('/') }}" title="Beranda"><i class="bi bi-house-fill"></i></a>
                <a href="#" title="Ke Atas" onclick="window.scrollTo({top:0,behavior:'smooth'});return false;"><i class="bi bi-arrow-up"></i></a>
            </div>
        </div>
    </div>
</footer>

{{-- ═══════════════════ SCROLL TO TOP ═══════════════════ --}}
<button class="scroll-top" id="scrollTopBtn" onclick="window.scrollTo({top:0,behavior:'smooth'});">
    <i class="bi bi-chevron-up"></i>
</button>

{{-- ═══════════════════ SCRIPTS ═══════════════════ --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Navbar scroll
    window.addEventListener('scroll', function() {
        const nav = document.getElementById('navbar');
        nav.classList.toggle('scrolled', window.scrollY > 50);
        // Scroll to top button
        const btn = document.getElementById('scrollTopBtn');
        btn.classList.toggle('visible', window.scrollY > 300);
    });

    // Scroll animations
    const anim = new IntersectionObserver(entries => {
        entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
    }, { threshold: 0.08 });
    document.querySelectorAll('.fade-up').forEach(el => anim.observe(el));

    // Mobile menu close
    document.querySelectorAll('.nav-pills-custom a').forEach(a => {
        a.addEventListener('click', () => document.querySelector('.nav-pills-custom').classList.remove('show'));
    });
</script>
</body>
</html>
