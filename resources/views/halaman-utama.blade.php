<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SIMPEG-SMART - Sistem Informasi Administrasi Tata Usaha | SMA Negeri 2 Jember</title>
    <meta name="description" content="Sistem Informasi Administrasi Tata Usaha SMA Negeri 2 Jember - Layanan administrasi sekolah yang modern, efisien, dan terintegrasi.">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}">
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
        .hero {
            min-height: 100vh; display: flex; align-items: center; position: relative; overflow: hidden;
            background: var(--dark);
        }
        .hero-bg {
            position: absolute; inset: 0; z-index: 0;
            background:
                radial-gradient(ellipse 80% 50% at 20% 40%, rgba(79,70,229,.25), transparent),
                radial-gradient(ellipse 60% 60% at 80% 20%, rgba(5,150,105,.2), transparent),
                radial-gradient(ellipse 50% 40% at 60% 80%, rgba(217,119,6,.12), transparent),
                linear-gradient(180deg, #0f172a 0%, #1a1439 40%, #0f2027 100%);
        }
        .hero-mesh {
            position: absolute; inset: 0; z-index: 0;
            background-image:
                radial-gradient(at 40% 20%, rgba(99,102,241,.15) 0px, transparent 50%),
                radial-gradient(at 80% 0%, rgba(16,185,129,.1) 0px, transparent 50%),
                radial-gradient(at 0% 50%, rgba(251,191,36,.08) 0px, transparent 50%);
        }
        .grid-overlay {
            position: absolute; inset: 0; z-index: 0; opacity: .04;
            background-image: linear-gradient(rgba(255,255,255,.5) 1px, transparent 1px),
                              linear-gradient(90deg, rgba(255,255,255,.5) 1px, transparent 1px);
            background-size: 60px 60px;
        }
        .hero-glow-1 {
            position: absolute; width: 500px; height: 500px; border-radius: 50%;
            background: radial-gradient(circle, rgba(79,70,229,.3), transparent 70%);
            top: -150px; left: 10%; animation: float 10s ease-in-out infinite; z-index: 0;
        }
        .hero-glow-2 {
            position: absolute; width: 400px; height: 400px; border-radius: 50%;
            background: radial-gradient(circle, rgba(5,150,105,.2), transparent 70%);
            bottom: -100px; right: 5%; animation: float 12s ease-in-out infinite reverse; z-index: 0;
        }
        .hero-glow-3 {
            position: absolute; width: 300px; height: 300px; border-radius: 50%;
            background: radial-gradient(circle, rgba(251,191,36,.15), transparent 70%);
            top: 40%; right: 30%; animation: float 14s ease-in-out infinite 2s; z-index: 0;
        }
        @keyframes float {
            0%,100% { transform: translateY(0) scale(1); opacity:.6; }
            50% { transform: translateY(-30px) scale(1.1); opacity:1; }
        }
        .hero-content { position: relative; z-index: 2; padding-top: 80px; }
        .hero-pill {
            display: inline-flex; align-items: center; gap: 8px;
            background: rgba(255,255,255,.07); border: 1px solid rgba(255,255,255,.12);
            border-radius: 100px; padding: 6px 18px 6px 8px; margin-bottom: 28px;
            backdrop-filter: blur(10px);
        }
        .hero-pill .dot { width: 10px; height: 10px; border-radius: 50%; background: var(--emerald-light); animation: blink 2s ease infinite; }
        .hero-pill span { color: #c7d2fe; font-size: .72rem; font-weight: 500; }
        @keyframes blink { 0%,100% { opacity:1; } 50% { opacity:.3; } }
        .hero h1 {
            font-family: 'Space Grotesk', sans-serif; color: #fff; font-size: 3.5rem;
            font-weight: 800; line-height: 1.1; margin-bottom: 20px; letter-spacing: -1px;
        }
        .hero h1 .text-grad-1 {
            background: linear-gradient(135deg, var(--indigo-light), #c084fc);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        }
        .hero h1 .text-grad-2 {
            background: linear-gradient(135deg, var(--emerald-light), var(--cyan));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        }
        .hero h1 .text-grad-3 {
            background: linear-gradient(135deg, var(--amber-light), #fb923c);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        }
        .hero .lead { color: rgba(203,213,225,.9); font-size: 1.05rem; line-height: 1.8; margin-bottom: 36px; max-width: 520px; }
        .hero-btns { display: flex; gap: 14px; flex-wrap: wrap; }
        .btn-primary-glow {
            background: linear-gradient(135deg, var(--indigo), var(--violet));
            color: #fff; padding: 15px 34px; border-radius: 14px; text-decoration: none;
            font-weight: 700; font-size: .88rem; display: inline-flex; align-items: center; gap: 10px;
            box-shadow: 0 4px 25px rgba(79,70,229,.45); transition: all .3s; border: none;
        }
        .btn-primary-glow:hover { transform: translateY(-3px); box-shadow: 0 8px 35px rgba(79,70,229,.6); color: #fff; }
        .btn-outline-glow {
            background: rgba(255,255,255,.05); color: rgba(203,213,225,.9);
            border: 1.5px solid rgba(255,255,255,.15); padding: 15px 34px; border-radius: 14px;
            text-decoration: none; font-weight: 600; font-size: .88rem;
            display: inline-flex; align-items: center; gap: 10px; transition: all .3s;
        }
        .btn-outline-glow:hover { background: rgba(255,255,255,.1); color: #fff; border-color: rgba(255,255,255,.3); }
        .hero-metrics { display: flex; gap: 40px; margin-top: 50px; }
        .hero-metric .val { color: #fff; font-size: 2.2rem; font-weight: 900; font-family: 'Space Grotesk', sans-serif; line-height: 1; }
        .hero-metric .lbl { color: var(--gray-light); font-size: .72rem; margin-top: 4px; }
        .hero-visual { position: relative; z-index: 2; }
        .hero-glass {
            background: rgba(255,255,255,.04); border: 1px solid rgba(255,255,255,.08);
            border-radius: 24px; padding: 28px; backdrop-filter: blur(16px);
            box-shadow: 0 25px 60px rgba(0,0,0,.35);
        }
        .hero-glass .glass-head { display: flex; align-items: center; gap: 12px; margin-bottom: 22px; }
        .hero-glass .glass-icon {
            width: 50px; height: 50px; border-radius: 14px; display: flex;
            align-items: center; justify-content: center; font-size: 1.3rem; color: #fff;
        }
        .hero-glass .glass-head h5 { color: #fff; font-size: .92rem; font-weight: 700; margin: 0; }
        .hero-glass .glass-head small { color: var(--gray-light); font-size: .7rem; }
        .glass-list { list-style: none; padding: 0; margin: 0; }
        .glass-list li {
            display: flex; align-items: center; gap: 12px; padding: 11px 0;
            border-bottom: 1px solid rgba(255,255,255,.06); color: #e2e8f0; font-size: .82rem;
        }
        .glass-list li:last-child { border-bottom: none; }
        .glass-list li .icon-sm {
            width: 32px; height: 32px; border-radius: 10px; display: flex;
            align-items: center; justify-content: center; font-size: .85rem; flex-shrink: 0;
        }

        /* ═══════════════════ TRUST BAR ═══════════════════ */
        .trust-bar {
            background: var(--white); border-bottom: 1px solid #e2e8f0; padding: 24px 0; overflow: hidden;
        }
        .trust-bar p { color: var(--gray); font-size: .7rem; text-transform: uppercase; letter-spacing: 1.5px; font-weight: 600; text-align: center; margin-bottom: 12px; }
        .trust-logos { display: flex; align-items: center; gap: 50px; animation: marquee 30s linear infinite; }
        .trust-logos .trust-item { display: flex; align-items: center; gap: 10px; white-space: nowrap; flex-shrink: 0; }
        .trust-logos .trust-item i { font-size: 1.5rem; }
        .trust-logos .trust-item span { font-size: .82rem; font-weight: 600; color: var(--dark); }
        @keyframes marquee { 0% { transform: translateX(0); } 100% { transform: translateX(-50%); } }

        /* ═══════════════════ SECTIONS ═══════════════════ */
        .section { padding: 100px 0; }
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
            font-family: 'Space Grotesk', sans-serif; font-size: 2.4rem;
            font-weight: 800; color: var(--dark); line-height: 1.15; margin-bottom: 14px; letter-spacing: -.5px;
        }
        .section-heading-white { color: #fff; }
        .section-desc { color: var(--gray); font-size: .95rem; line-height: 1.7; max-width: 600px; }
        .section-desc-light { color: var(--gray-light); }
        .bg-light-custom { background: var(--light); }
        .bg-dark-custom { background: var(--dark); }
        .bg-gradient-mesh {
            background:
                radial-gradient(ellipse 60% 50% at 10% 90%, rgba(79,70,229,.06), transparent),
                radial-gradient(ellipse 50% 40% at 90% 10%, rgba(5,150,105,.05), transparent),
                var(--white);
        }

        /* ═══════════════════ FEATURE CARDS ═══════════════════ */
        .feature-card {
            background: var(--white); border: 1px solid #e5e7eb; border-radius: 20px;
            padding: 32px 28px; transition: all .35s; height: 100%; position: relative; overflow: hidden;
        }
        .feature-card::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; height: 4px;
            background: linear-gradient(90deg, var(--indigo), var(--emerald));
            opacity: 0; transition: opacity .3s;
        }
        .feature-card:hover { transform: translateY(-8px); box-shadow: 0 20px 50px rgba(0,0,0,.08); border-color: transparent; }
        .feature-card:hover::before { opacity: 1; }
        .feature-icon {
            width: 58px; height: 58px; border-radius: 16px; display: inline-flex;
            align-items: center; justify-content: center; font-size: 1.5rem; color: #fff; margin-bottom: 20px;
        }
        .feature-card h5 { font-size: .95rem; font-weight: 700; color: var(--dark); margin-bottom: 8px; }
        .feature-card p { color: var(--gray); font-size: .82rem; line-height: 1.65; margin: 0; }
        .feature-tag {
            position: absolute; top: 20px; right: 20px; font-size: .6rem;
            padding: 4px 10px; border-radius: 100px; font-weight: 700; letter-spacing: .3px;
        }
        .tag-indigo { background: var(--indigo-50); color: var(--indigo); }
        .tag-emerald { background: var(--emerald-50); color: var(--emerald); }
        .tag-amber { background: var(--amber-50); color: var(--amber); }

        /* ═══════════════════ BIG FEATURE HIGHLIGHT ═══════════════════ */
        .big-feature {
            background: linear-gradient(135deg, #0f172a, #1e1b4b); border-radius: 28px;
            padding: 60px 50px; position: relative; overflow: hidden;
        }
        .big-feature::before {
            content: ''; position: absolute; width: 400px; height: 400px; border-radius: 50%;
            background: radial-gradient(circle, rgba(99,102,241,.2), transparent 70%);
            top: -200px; right: -100px;
        }
        .big-feature::after {
            content: ''; position: absolute; width: 300px; height: 300px; border-radius: 50%;
            background: radial-gradient(circle, rgba(5,150,105,.12), transparent 70%);
            bottom: -150px; left: -100px;
        }
        .big-feature h3 { color: #fff; font-family: 'Space Grotesk', sans-serif; font-weight: 800; font-size: 1.8rem; position: relative; z-index: 1; }
        .big-feature p { color: var(--gray-light); position: relative; z-index: 1; }
        .big-feature .mini-card {
            background: rgba(255,255,255,.06); border: 1px solid rgba(255,255,255,.1);
            border-radius: 16px; padding: 20px; text-align: center; transition: all .3s;
        }
        .big-feature .mini-card:hover { background: rgba(255,255,255,.1); transform: translateY(-4px); }
        .big-feature .mini-card i { font-size: 2rem; margin-bottom: 10px; display: block; }
        .big-feature .mini-card h6 { color: #fff; font-size: .82rem; font-weight: 600; margin-bottom: 4px; }
        .big-feature .mini-card small { color: var(--gray-light); font-size: .72rem; }

        /* ═══════════════════ FLOW STEPS ═══════════════════ */
        .flow-card { text-align: center; position: relative; padding: 0 10px; }
        .flow-num {
            width: 68px; height: 68px; border-radius: 20px; display: flex;
            align-items: center; justify-content: center; font-size: 1.3rem;
            font-weight: 900; color: #fff; margin: 0 auto 18px;
            font-family: 'Space Grotesk', sans-serif;
        }
        .flow-card h6 { font-size: .88rem; font-weight: 700; color: var(--dark); margin-bottom: 6px; }
        .flow-card p { font-size: .78rem; color: var(--gray); line-height: 1.55; }
        .flow-connector {
            position: absolute; top: 34px; right: -20px; width: 40px; height: 3px; border-radius: 2px;
        }

        /* ═══════════════════ STATS SECTION ═══════════════════ */
        .stats-section {
            background: linear-gradient(135deg, #0f172a 0%, #172554 30%, #1e3a5f 60%, #0f172a 100%);
            position: relative; overflow: hidden;
        }
        .stats-section .mesh-top {
            position: absolute; inset: 0;
            background:
                radial-gradient(ellipse at 20% 50%, rgba(79,70,229,.15), transparent 50%),
                radial-gradient(ellipse at 80% 30%, rgba(5,150,105,.12), transparent 50%),
                radial-gradient(ellipse at 50% 90%, rgba(217,119,6,.08), transparent 50%);
        }
        .stat-card {
            background: rgba(255,255,255,.04); border: 1px solid rgba(255,255,255,.08);
            border-radius: 20px; padding: 30px 24px; text-align: center;
            backdrop-filter: blur(10px); transition: all .35s; position: relative; overflow: hidden;
        }
        .stat-card:hover { background: rgba(255,255,255,.08); border-color: rgba(255,255,255,.15); transform: translateY(-5px); }
        .stat-card .live-dot {
            position: absolute; top: 14px; right: 14px; width: 8px; height: 8px;
            border-radius: 50%; background: var(--emerald-light); animation: blink 2s ease infinite;
        }
        .stat-icon {
            width: 54px; height: 54px; border-radius: 16px; display: flex;
            align-items: center; justify-content: center; font-size: 1.3rem; color: #fff; margin: 0 auto 16px;
        }
        .stat-val {
            font-size: 2.2rem; font-weight: 900; color: #fff; line-height: 1;
            margin-bottom: 4px; font-family: 'Space Grotesk', sans-serif;
            font-variant-numeric: tabular-nums;
        }
        .stat-lbl { font-size: .75rem; color: var(--gray-light); font-weight: 500; }
        .school-data-panel {
            background: rgba(255,255,255,.04); border: 1px solid rgba(255,255,255,.08);
            border-radius: 20px; padding: 28px; backdrop-filter: blur(10px);
        }
        .school-data-panel h6 { color: #fff; font-size: .9rem; font-weight: 700; margin-bottom: 20px; }
        .sd-row {
            display: flex; justify-content: space-between; align-items: center;
            padding: 10px 0; border-bottom: 1px solid rgba(255,255,255,.06);
        }
        .sd-row:last-child { border-bottom: none; }
        .sd-row .sd-label { color: var(--gray-light); font-size: .8rem; display: flex; align-items: center; gap: 8px; }
        .sd-row .sd-value { color: #fff; font-weight: 800; font-size: 1rem; font-family: 'Space Grotesk', sans-serif; }
        .map-card {
            border-radius: 20px; overflow: hidden; border: 1px solid rgba(255,255,255,.08);
            height: 100%; min-height: 300px; position: relative;
        }
        .map-card iframe { width: 100%; height: 100%; min-height: 300px; border: none; }
        .map-overlay {
            position: absolute; top: 16px; left: 16px; background: rgba(15,23,42,.9);
            backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,.1);
            border-radius: 14px; padding: 14px 20px; z-index: 5;
        }
        .map-overlay h6 { color: #fff; font-size: .82rem; font-weight: 700; margin: 0 0 2px; }
        .map-overlay small { color: var(--gray-light); font-size: .68rem; }

        /* ═══════════════════ HIERARKI ═══════════════════ */
        .org-chart { display: flex; flex-direction: column; align-items: center; }
        .org-level { display: flex; justify-content: center; gap: 20px; flex-wrap: wrap; }
        .org-line-v { width: 3px; height: 40px; background: linear-gradient(180deg, var(--indigo-light), var(--emerald-light)); margin: 0 auto; border-radius: 2px; }
        .org-line-h { width: 100%; max-width: 550px; height: 3px; background: linear-gradient(90deg, transparent, var(--indigo-light), var(--emerald-light), transparent); margin: 0 auto; border-radius: 2px; }
        .org-box {
            background: var(--white); border: 2px solid #e5e7eb; border-radius: 16px;
            padding: 20px 28px; text-align: center; min-width: 170px; transition: all .3s;
        }
        .org-box:hover { border-color: var(--indigo-light); box-shadow: 0 8px 30px rgba(79,70,229,.1); transform: translateY(-3px); }
        .org-box.leader { background: linear-gradient(135deg, var(--indigo), var(--violet)); border-color: transparent; }
        .org-box.leader h6, .org-box.leader small { color: #fff; }
        .org-box.coordinator { border-color: var(--emerald-light); }
        .org-box .org-icon { font-size: 1.6rem; margin-bottom: 8px; display: block; }
        .org-box h6 { font-size: .82rem; font-weight: 700; color: var(--dark); margin: 0 0 2px; }
        .org-box small { font-size: .68rem; color: var(--gray); }

        /* ═══════════════════ CTA SECTION ═══════════════════ */
        .cta-section {
            background: linear-gradient(135deg, var(--indigo), #6d28d9, var(--violet));
            position: relative; overflow: hidden;
        }
        .cta-section::before {
            content: ''; position: absolute; inset: 0;
            background:
                radial-gradient(ellipse at 20% 50%, rgba(255,255,255,.08), transparent 50%),
                radial-gradient(ellipse at 80% 30%, rgba(5,150,105,.15), transparent 50%);
        }
        .cta-section h2 { color: #fff; font-family: 'Space Grotesk', sans-serif; font-weight: 800; font-size: 2.2rem; position: relative; z-index: 1; }
        .cta-section p { color: rgba(255,255,255,.8); position: relative; z-index: 1; }
        .btn-cta-white {
            background: #fff; color: var(--indigo); padding: 16px 36px; border-radius: 14px;
            font-weight: 700; font-size: .9rem; text-decoration: none; display: inline-flex;
            align-items: center; gap: 10px; transition: all .3s; border: none; position: relative; z-index: 1;
        }
        .btn-cta-white:hover { transform: translateY(-3px); box-shadow: 0 8px 30px rgba(0,0,0,.2); color: var(--indigo); }
        .btn-cta-outline {
            background: transparent; color: #fff; border: 2px solid rgba(255,255,255,.3);
            padding: 14px 34px; border-radius: 14px; font-weight: 600; font-size: .9rem;
            text-decoration: none; display: inline-flex; align-items: center; gap: 10px;
            transition: all .3s; position: relative; z-index: 1;
        }
        .btn-cta-outline:hover { background: rgba(255,255,255,.1); border-color: rgba(255,255,255,.5); color: #fff; }

        /* ═══════════════════ FOOTER ═══════════════════ */
        .footer {
            background: var(--dark); padding: 70px 0 0; position: relative; overflow: hidden;
        }
        .footer::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; height: 4px;
            background: linear-gradient(90deg, var(--indigo), var(--emerald), var(--amber), var(--indigo));
            background-size: 200% 100%; animation: gradient-shift 6s linear infinite;
        }
        @keyframes gradient-shift { 0% { background-position: 0% 0%; } 100% { background-position: 200% 0%; } }
        .footer h5 { color: #fff; font-size: .92rem; font-weight: 700; margin-bottom: 18px; font-family: 'Space Grotesk', sans-serif; }
        .footer p, .footer li, .footer a { color: var(--gray-light); font-size: .82rem; line-height: 1.8; }
        .footer a { text-decoration: none; transition: color .2s; }
        .footer a:hover { color: var(--indigo-light); }
        .footer ul { list-style: none; padding: 0; }
        .footer ul li { display: flex; align-items: flex-start; gap: 10px; }
        .footer ul li i { color: var(--emerald-light); margin-top: 5px; font-size: .8rem; }
        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,.06); margin-top: 50px;
            padding: 22px 0; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;
        }
        .footer-bottom p { color: var(--gray); font-size: .72rem; margin: 0; }
        .footer-socials { display: flex; gap: 10px; }
        .footer-socials a {
            width: 36px; height: 36px; border-radius: 10px; display: flex;
            align-items: center; justify-content: center; background: rgba(255,255,255,.06);
            border: 1px solid rgba(255,255,255,.08); color: var(--gray-light); font-size: .9rem;
            transition: all .2s;
        }
        .footer-socials a:hover { background: var(--indigo); color: #fff; border-color: var(--indigo); }
        .footer-map-mini { border-radius: 14px; overflow: hidden; border: 1px solid rgba(255,255,255,.08); height: 180px; }
        .footer-map-mini iframe { width: 100%; height: 100%; border: none; filter: grayscale(.3) brightness(.8); }

        /* ═══════════════════ HERO IMAGE ═══════════════════ */
        .hero-img-wrap {
            position: relative; border-radius: 24px; overflow: hidden;
            box-shadow: 0 25px 60px rgba(0,0,0,.35);
            border: 1px solid rgba(255,255,255,.08);
        }
        .hero-img-main {
            width: 100%; height: auto; display: block; border-radius: 24px;
            aspect-ratio: 16/10; object-fit: cover;
        }

        /* ═══════════════════ BERITA CAROUSEL ═══════════════════ */
        .berita-section { background: var(--light); }
        .berita-carousel { overflow: hidden; position: relative; border-radius: 16px; }
        .berita-track {
            display: flex; gap: 24px;
            animation: beritaScroll 40s linear infinite;
        }
        .berita-track:hover { animation-play-state: paused; }
        .berita-track.paused { animation-play-state: paused; }
        @keyframes beritaScroll {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
        .berita-card {
            min-width: 340px; max-width: 340px; background: var(--white);
            border-radius: 18px; overflow: hidden; flex-shrink: 0;
            border: 1px solid #e5e7eb; transition: all .35s;
            box-shadow: 0 2px 8px rgba(0,0,0,.04);
        }
        .berita-card:hover { transform: translateY(-6px); box-shadow: 0 16px 40px rgba(0,0,0,.1); border-color: transparent; }
        .berita-img { position: relative; height: 180px; overflow: hidden; }
        .berita-img img { width: 100%; height: 100%; object-fit: cover; }
        .berita-img-placeholder {
            width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;
            background: linear-gradient(135deg, #1e1b4b, #312e81); color: rgba(255,255,255,.25);
            font-size: 3rem;
        }
        .berita-badge {
            position: absolute; top: 12px; left: 12px;
            background: linear-gradient(135deg, var(--amber), #f97316); color: #fff;
            font-size: .65rem; font-weight: 700; padding: 4px 12px; border-radius: 100px;
            display: inline-flex; align-items: center; gap: 4px;
        }
        .berita-body { padding: 20px; }
        .berita-date { font-size: .7rem; color: var(--gray); margin-bottom: 8px; display: flex; align-items: center; gap: 6px; }
        .berita-title { font-size: .9rem; font-weight: 700; color: var(--dark); margin-bottom: 8px; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .berita-excerpt { font-size: .78rem; color: var(--gray); line-height: 1.6; margin: 0; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .berita-controls { display: inline-flex; gap: 8px; }
        .berita-nav-btn {
            width: 42px; height: 42px; border-radius: 12px; border: 1.5px solid #e5e7eb;
            background: var(--white); color: var(--dark); font-size: 1rem;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; transition: all .2s;
        }
        .berita-nav-btn:hover { background: var(--indigo); color: #fff; border-color: var(--indigo); }
        @media(max-width:767px) {
            .berita-card { min-width: 280px; max-width: 280px; }
            .berita-img { height: 140px; }
        }

        /* ═══════════════════ SCROLLBAR ═══════════════════ */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: var(--dark); }
        ::-webkit-scrollbar-thumb { background: var(--indigo); border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--indigo-light); }

        /* ═══════════════════ ANIMATIONS ═══════════════════ */
        .fade-up { opacity: 0; transform: translateY(30px); transition: all .7s cubic-bezier(.16,1,.3,1); }
        .fade-up.visible { opacity: 1; transform: translateY(0); }

        /* ═══════════════════ RESPONSIVE ═══════════════════ */
        @media(max-width:991px) {
            .hero h1 { font-size: 2.4rem; }
            .hero-metrics { gap: 24px; }
            .hero-visual { margin-top: 40px; }
            .section-heading { font-size: 1.9rem; }
            .flow-connector { display: none; }
            .big-feature { padding: 40px 28px; }
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
            .hero { min-height: auto; padding: 120px 0 60px; }
            .hero h1 { font-size: 1.8rem; }
            .hero .lead { font-size: .9rem; }
            .hero-metrics { flex-direction: column; gap: 12px; }
            .section { padding: 60px 0; }
            .section-heading { font-size: 1.5rem; }
            .org-level { flex-direction: column; align-items: center; }
            .cta-section h2 { font-size: 1.6rem; }
        }
    </style>
</head>
<body>

{{-- ═══════════════════ NAVBAR ═══════════════════ --}}
<nav class="navbar-main" id="navbar">
    <div class="container d-flex justify-content-between align-items-center">
        <a href="#" class="nav-brand">
            <img src="{{ asset('images/logo.png') }}" alt="Logo SMA Negeri 2 Jember" onerror="this.style.display='none'">
            <div class="txt">
                <h6>SIMPEG-SMART</h6>
                <small>SMA Negeri 2 Jember</small>
            </div>
        </a>
        <button class="mobile-toggle" onclick="document.querySelector('.nav-pills-custom').classList.toggle('show')">
            <i class="bi bi-list"></i>
        </button>
        <div class="nav-pills-custom">
            <a href="#beranda">Beranda</a>
            <a href="#berita">Berita</a>
            <a href="#fitur">Fitur</a>
            <a href="{{ route('kinerja') }}">Kinerja</a>
            <a href="#statistik">Statistik</a>
            <a href="#alur">Alur</a>
            <a href="#struktur">Struktur</a>
            <a href="#kontak">Kontak</a>
            <a href="{{ route('login') }}" class="btn-login"><i class="bi bi-box-arrow-in-right"></i> Masuk</a>
        </div>
    </div>
</nav>

{{-- ═══════════════════ HERO ═══════════════════ --}}
<section class="hero" id="beranda">
    <div class="hero-bg"></div>
    <div class="hero-mesh"></div>
    <div class="grid-overlay"></div>
    <div class="hero-glow-1"></div>
    <div class="hero-glow-2"></div>
    <div class="hero-glow-3"></div>
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 hero-content">
                <div class="hero-pill">
                    <span class="dot"></span>
                    <span>Platform Aktif &bull; Layani 9 Peran &bull; 24/7</span>
                </div>
                <h1>
                    Sistem <span class="text-grad-1">Informasi</span><br>
                    Administrasi <span class="text-grad-2">Tata Usaha</span><br>
                    <span class="text-grad-3">SMA Negeri 2 Jember</span>
                </h1>
                <p class="lead">
                    Platform digital terpadu untuk pengelolaan administrasi, kehadiran, keuangan, persuratan, inventaris, evaluasi kinerja, dan layanan AI &mdash; semua dalam satu ekosistem.
                </p>
                <div class="hero-btns">
                    <a href="{{ route('login') }}" class="btn-primary-glow">
                        <i class="bi bi-rocket-takeoff-fill"></i> Masuk Sistem
                    </a>
                    <a href="{{ route('kinerja') }}" class="btn-outline-glow">
                        <i class="bi bi-bar-chart-line-fill"></i> Lihat Kinerja
                    </a>
                </div>
                <div class="hero-metrics">
                    <div class="hero-metric"><div class="val">15+</div><div class="lbl">Modul Layanan</div></div>
                    <div class="hero-metric"><div class="val">9</div><div class="lbl">Peran Pengguna</div></div>
                    <div class="hero-metric"><div class="val">AI</div><div class="lbl">Powered Chatbot</div></div>
                </div>
            </div>
            <div class="col-lg-5 offset-lg-1 hero-visual">
                {{-- Hero Image Placeholder (ganti dengan gambar sekolah) --}}
                <div class="hero-img-wrap">
                    <img src="{{ asset('images/hero-dashboard.png') }}" alt="Dashboard SIMPEG-SMART" class="hero-img-main"
                         onerror="this.parentElement.innerHTML='<div class=\'hero-glass\'><div class=\'glass-head\'><div class=\'glass-icon\' style=\'background:linear-gradient(135deg,var(--emerald),var(--cyan));\'><i class=\'bi bi-shield-check\'></i></div><div><h5>Layanan Terintegrasi</h5><small>Fitur terpadu untuk setiap peran staf</small></div></div><ul class=\'glass-list\'><li><span class=\'icon-sm\' style=\'background:rgba(99,102,241,.15);color:var(--indigo-light);\'><i class=\'bi bi-fingerprint\'></i></span> Absensi GPS &amp; Selfie Realtime</li><li><span class=\'icon-sm\' style=\'background:rgba(5,150,105,.15);color:var(--emerald-light);\'><i class=\'bi bi-envelope-paper-fill\'></i></span> Surat Menyurat &amp; Disposisi Digital</li><li><span class=\'icon-sm\' style=\'background:rgba(217,119,6,.15);color:var(--amber-light);\'><i class=\'bi bi-cash-coin\'></i></span> Keuangan, RKAS &amp; Anggaran</li><li><span class=\'icon-sm\' style=\'background:rgba(236,72,153,.15);color:#f472b6;\'><i class=\'bi bi-box-seam-fill\'></i></span> Inventaris &amp; Sarana Prasarana</li><li><span class=\'icon-sm\' style=\'background:rgba(139,92,246,.15);color:#a78bfa;\'><i class=\'bi bi-clipboard2-data-fill\'></i></span> SKP, PKG, P5, STAR &amp; Evaluasi</li><li><span class=\'icon-sm\' style=\'background:rgba(6,182,212,.15);color:#22d3ee;\'><i class=\'bi bi-robot\'></i></span> SIMPEG-AI Assistant &amp; Word Generator</li></ul></div>';">
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════ TRUST BAR ═══════════════════ --}}
<div class="trust-bar">
    <div class="container">
        <p>Dibangun dengan teknologi terpercaya</p>
    </div>
    <div style="overflow:hidden;">
        <div class="trust-logos">
            @for($i = 0; $i < 2; $i++)
            <div class="trust-item"><i class="bi bi-filetype-php" style="color:#777BB4;"></i><span>Laravel 11</span></div>
            <div class="trust-item"><i class="bi bi-bootstrap-fill" style="color:#7952B3;"></i><span>Bootstrap 5</span></div>
            <div class="trust-item"><i class="bi bi-database-fill" style="color:#00758F;"></i><span>MySQL 8</span></div>
            <div class="trust-item"><i class="bi bi-filetype-js" style="color:#F7DF1E;"></i><span>JavaScript</span></div>
            <div class="trust-item"><i class="bi bi-google" style="color:#4285F4;"></i><span>Google Drive API</span></div>
            <div class="trust-item"><i class="bi bi-geo-alt-fill" style="color:#E74C3C;"></i><span>Leaflet Maps</span></div>
            <div class="trust-item"><i class="bi bi-robot" style="color:#10b981;"></i><span>OpenAI / Gemini</span></div>
            <div class="trust-item"><i class="bi bi-shield-check" style="color:#0891b2;"></i><span>CSRF &amp; Auth</span></div>
            @endfor
        </div>
    </div>
</div>

{{-- ═══════════════════ BERITA TERBARU (Auto-scroll Ticker) ═══════════════════ --}}
@if($beritaTerbaru->count() > 0)
<section class="section berita-section" id="berita" style="padding:80px 0;">
    <div class="container">
        <div class="text-center mb-5 fade-up">
            <div class="section-chip chip-amber"><i class="bi bi-newspaper"></i> Berita Terkini</div>
            <h2 class="section-heading">Berita &amp; Pengumuman<br>Terbaru</h2>
            <p class="section-desc mx-auto">Informasi terkini dari kegiatan administrasi, event sekolah, dan pembaruan sistem SIMPEG-SMART.</p>
        </div>
        <div class="berita-carousel fade-up">
            <div class="berita-track" id="beritaTrack">
                @foreach($beritaTerbaru as $berita)
                <div class="berita-card">
                    <div class="berita-img">
                        @if($berita->thumbnail)
                            <img src="{{ $berita->thumbnail_url }}" alt="{{ $berita->judul }}">
                        @else
                            <div class="berita-img-placeholder">
                                <i class="bi bi-newspaper"></i>
                            </div>
                        @endif
                        @if($berita->unggulan)
                            <span class="berita-badge"><i class="bi bi-star-fill"></i> Unggulan</span>
                        @endif
                    </div>
                    <div class="berita-body">
                        <div class="berita-date">
                            <i class="bi bi-calendar3"></i> {{ $berita->created_at->translatedFormat('d M Y') }}
                        </div>
                        <h5 class="berita-title">{{ $berita->judul }}</h5>
                        <p class="berita-excerpt">{{ Str::limit($berita->deskripsi, 100) }}</p>
                    </div>
                </div>
                @endforeach
                {{-- Duplicate for infinite scroll --}}
                @foreach($beritaTerbaru as $berita)
                <div class="berita-card">
                    <div class="berita-img">
                        @if($berita->thumbnail)
                            <img src="{{ $berita->thumbnail_url }}" alt="{{ $berita->judul }}">
                        @else
                            <div class="berita-img-placeholder">
                                <i class="bi bi-newspaper"></i>
                            </div>
                        @endif
                        @if($berita->unggulan)
                            <span class="berita-badge"><i class="bi bi-star-fill"></i> Unggulan</span>
                        @endif
                    </div>
                    <div class="berita-body">
                        <div class="berita-date">
                            <i class="bi bi-calendar3"></i> {{ $berita->created_at->translatedFormat('d M Y') }}
                        </div>
                        <h5 class="berita-title">{{ $berita->judul }}</h5>
                        <p class="berita-excerpt">{{ Str::limit($berita->deskripsi, 100) }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        <div class="text-center mt-4 fade-up">
            <div class="berita-controls">
                <button class="berita-nav-btn" id="beritaPrev" title="Sebelumnya"><i class="bi bi-chevron-left"></i></button>
                <button class="berita-nav-btn berita-pause-btn" id="beritaPause" title="Jeda/Lanjut"><i class="bi bi-pause-fill"></i></button>
                <button class="berita-nav-btn" id="beritaNext" title="Selanjutnya"><i class="bi bi-chevron-right"></i></button>
            </div>
        </div>
    </div>
</section>
@endif

{{-- ═══════════════════ TENTANG SEKOLAH & MEDIA ═══════════════════ --}}
<section class="section" id="tentang" style="padding:80px 0;">
    <div class="container">
        <div class="text-center mb-5 fade-up">
            <div class="section-chip chip-indigo"><i class="bi bi-camera-reels-fill"></i> Tentang Sekolah</div>
            <h2 class="section-heading">Mengenal Lebih Dekat<br>SMA Negeri 2 Jember</h2>
            <p class="section-desc mx-auto">Sejarah, profil, dan kegiatan terbaru SMA Negeri 2 Jember melalui video dan dokumentasi visual.</p>
        </div>

        {{-- Video Utama --}}
        <div class="row g-4 mb-5 fade-up">
            <div class="col-lg-8">
                <div style="position:relative;border-radius:18px;overflow:hidden;box-shadow:0 12px 40px rgba(0,0,0,.15);aspect-ratio:16/9;">
                    <iframe src="https://www.youtube.com/embed/lU4jiQrfOik" title="Kepala Sekolah SMAN 2 Jember" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen style="width:100%;height:100%;border:none;"></iframe>
                </div>
            </div>
            <div class="col-lg-4 d-flex flex-column gap-3">
                <div style="background:linear-gradient(135deg,#312e81,#6366f1);border-radius:18px;padding:28px;color:#fff;flex:1;">
                    <h5 style="font-family:'Space Grotesk',sans-serif;font-weight:700;margin-bottom:12px;">
                        <i class="bi bi-stars me-2"></i>Babak Baru SMAN 2 Jember
                    </h5>
                    <p style="font-size:.85rem;opacity:.9;line-height:1.7;">New leadership, new direction! SMA Negeri 2 Jember memulai babak baru bersama Kepala Sekolah yang siap mengangkat kualitas pendidikan, karakter, dan prestasi siswa ke level berikutnya.</p>
                    <a href="https://sman2jember.sch.id/about/" target="_blank" rel="noopener noreferrer" class="btn btn-sm mt-2" style="background:rgba(255,255,255,.2);color:#fff;border:1px solid rgba(255,255,255,.3);border-radius:10px;">
                        <i class="bi bi-box-arrow-up-right me-1"></i> Sejarah Sekolah
                    </a>
                </div>
                <div style="background:#fff;border:1px solid #e5e7eb;border-radius:18px;padding:20px;">
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <div style="width:44px;height:44px;border-radius:12px;background:linear-gradient(135deg,var(--emerald),var(--cyan));display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.1rem;">
                            <i class="bi bi-building"></i>
                        </div>
                        <div>
                            <h6 style="margin:0;font-weight:700;font-size:.88rem;">SMAN 2 Jember</h6>
                            <small style="color:var(--gray);font-size:.72rem;">Terakreditasi A | NPSN: 20523868</small>
                        </div>
                    </div>
                    <p style="font-size:.78rem;color:var(--gray);line-height:1.6;margin:0;">Sekolah unggulan di Kabupaten Jember dengan visi membentuk insan yang beriman, berilmu, berkarakter, dan berwawasan global.</p>
                </div>
            </div>
        </div>

        {{-- Video Grid --}}
        <div class="row g-4 fade-up">
            <div class="col-lg-4 col-md-6">
                <div style="border-radius:16px;overflow:hidden;box-shadow:0 6px 24px rgba(0,0,0,.08);border:1px solid #e5e7eb;background:#fff;">
                    <div style="aspect-ratio:16/9;">
                        <iframe src="https://www.youtube.com/embed/iLBBTDctlVU" title="Profil Guru SMAN 2 Jember" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen style="width:100%;height:100%;border:none;"></iframe>
                    </div>
                    <div style="padding:16px;">
                        <h6 style="font-size:.85rem;font-weight:700;margin-bottom:4px;"><i class="bi bi-people-fill me-1" style="color:var(--indigo);"></i>Profil Guru</h6>
                        <p style="font-size:.75rem;color:var(--gray);margin:0;line-height:1.5;">Mengenal lebih dekat para pendidik SMAN 2 Jember yang berdedikasi tinggi.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div style="border-radius:16px;overflow:hidden;box-shadow:0 6px 24px rgba(0,0,0,.08);border:1px solid #e5e7eb;background:#fff;">
                    <div style="aspect-ratio:16/9;">
                        <iframe src="https://www.youtube.com/embed/FjvxaQ_3Kbc" title="7 Kebiasaan Anak Indonesia Hebat" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen style="width:100%;height:100%;border:none;"></iframe>
                    </div>
                    <div style="padding:16px;">
                        <h6 style="font-size:.85rem;font-weight:700;margin-bottom:4px;"><i class="bi bi-award-fill me-1" style="color:var(--amber);"></i>7 Kebiasaan Anak Hebat</h6>
                        <p style="font-size:.75rem;color:var(--gray);margin:0;line-height:1.5;">Program karakter 7 Kebiasaan Anak Indonesia Hebat di SMAN 2 Jember.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div style="border-radius:16px;overflow:hidden;box-shadow:0 6px 24px rgba(0,0,0,.08);border:1px solid #e5e7eb;background:#fff;">
                    <div style="aspect-ratio:16/9;background:linear-gradient(135deg,#059669,#0891b2);display:flex;align-items:center;justify-content:center;flex-direction:column;color:#fff;">
                        <i class="bi bi-egg-fried" style="font-size:2.5rem;margin-bottom:8px;"></i>
                        <h6 style="font-size:.88rem;font-weight:700;margin:0;">Makan Bergizi Gratis</h6>
                    </div>
                    <div style="padding:16px;">
                        <h6 style="font-size:.85rem;font-weight:700;margin-bottom:4px;"><i class="bi bi-heart-pulse-fill me-1" style="color:var(--emerald);"></i>Program MBG</h6>
                        <p style="font-size:.75rem;color:var(--gray);margin:0;line-height:1.5;">SMAN 2 Jember melaksanakan Program Makan Bergizi Gratis dari Pemerintah.</p>
                        <a href="https://sman2jember.sch.id/elementor-2038/" target="_blank" rel="noopener noreferrer" style="font-size:.72rem;color:var(--indigo);font-weight:600;text-decoration:none;">Selengkapnya <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>

        {{-- 360 View --}}
        <div class="row g-4 mt-4 fade-up">
            <div class="col-lg-6">
                <div style="border-radius:18px;overflow:hidden;box-shadow:0 12px 40px rgba(0,0,0,.1);border:1px solid #e5e7eb;aspect-ratio:16/9;">
                    <iframe src="https://www.google.com/maps/embed?pb=!4v1709913600000!6m8!1m7!1sCfhQmJIB0YlnswGcDuLUcg!2m2!1d-8.1721234!2d113.7029!3f0!4f0!5f0.7820865974627469" allowfullscreen="" loading="lazy" style="width:100%;height:100%;border:none;" title="360° View SMAN 2 Jember"></iframe>
                </div>
            </div>
            <div class="col-lg-6">
                <div style="border-radius:18px;overflow:hidden;box-shadow:0 12px 40px rgba(0,0,0,.1);border:1px solid #e5e7eb;aspect-ratio:16/9;">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d987.3297!2d113.7029!3d-8.1721!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd695b617d6a4e3%3A0x2bce46e45b9f9c0e!2sSMAN%202%20Jember!5e1!3m2!1sid!2sid!4v1709913600000!5m2!1sid!2sid" allowfullscreen="" loading="lazy" style="width:100%;height:100%;border:none;" title="Satelit SMAN 2 Jember"></iframe>
                </div>
            </div>
            <div class="col-12 text-center mt-2">
                <div class="d-inline-flex gap-3 flex-wrap justify-content-center">
                    <a href="https://maps.app.goo.gl/AtSZWWQp57YcW9WV7" target="_blank" rel="noopener noreferrer" class="btn btn-sm" style="background:var(--indigo);color:#fff;border-radius:10px;font-size:.78rem;padding:8px 18px;">
                        <i class="bi bi-geo-alt-fill me-1"></i> Buka di Google Maps
                    </a>
                    <a href="https://maps.app.goo.gl/bqDDhppTZPak8FQo9" target="_blank" rel="noopener noreferrer" class="btn btn-sm" style="background:var(--emerald);color:#fff;border-radius:10px;font-size:.78rem;padding:8px 18px;">
                        <i class="bi bi-eye-fill me-1"></i> Lihat 360° Street View
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════ FITUR LAYANAN ═══════════════════ --}}
<section class="section bg-gradient-mesh" id="fitur">
    <div class="container">
        <div class="text-center mb-5 fade-up">
            <div class="section-chip chip-indigo"><i class="bi bi-grid-3x3-gap-fill"></i> Modul Layanan</div>
            <h2 class="section-heading">Layanan Administrasi<br>Berdasarkan Peran</h2>
            <p class="section-desc mx-auto">Setiap peran mendapat fitur khusus sesuai tupoksi &mdash; dari manajemen pegawai hingga layanan sarana prasarana.</p>
        </div>
        <div class="row g-4">
            <div class="col-lg-4 col-md-6 fade-up">
                <div class="feature-card">
                    <span class="feature-tag tag-indigo">Full Access</span>
                    <div class="feature-icon" style="background:linear-gradient(135deg,#6366f1,#8b5cf6);"><i class="bi bi-shield-lock-fill"></i></div>
                    <h5>Administrator</h5>
                    <p>Kontrol penuh: user management, data master, backup ke Google Drive, monitoring log, pengaturan sistem, dan export lengkap.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 fade-up">
                <div class="feature-card">
                    <span class="feature-tag tag-amber">Monitoring</span>
                    <div class="feature-icon" style="background:linear-gradient(135deg,#d97706,#f59e0b);"><i class="bi bi-person-workspace"></i></div>
                    <h5>Kepala Sekolah</h5>
                    <p>Dashboard eksekutif, persetujuan SKP, monitoring keuangan &amp; kehadiran, resolusi, rekap laporan, dan AI chatbot.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 fade-up">
                <div class="feature-card">
                    <span class="feature-tag tag-emerald">Kepegawaian</span>
                    <div class="feature-icon" style="background:linear-gradient(135deg,#059669,#10b981);"><i class="bi bi-people-fill"></i></div>
                    <h5>Kepegawaian</h5>
                    <p>Data pegawai &amp; guru, riwayat jabatan, pengajuan cuti, kenaikan pangkat, SKP, upload dokumen, dan sertifikasi.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 fade-up">
                <div class="feature-card">
                    <span class="feature-tag tag-indigo">Keuangan</span>
                    <div class="feature-icon" style="background:linear-gradient(135deg,#0891b2,#06b6d4);"><i class="bi bi-cash-coin"></i></div>
                    <h5>Keuangan</h5>
                    <p>Anggaran, pemasukan &amp; pengeluaran, laporan keuangan, pengelolaan BOS, bukti transaksi, dan rekap finansial.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 fade-up">
                <div class="feature-card">
                    <span class="feature-tag tag-amber">Persuratan</span>
                    <div class="feature-icon" style="background:linear-gradient(135deg,#e11d48,#f43f5e);"><i class="bi bi-envelope-paper-fill"></i></div>
                    <h5>Persuratan</h5>
                    <p>Surat masuk &amp; keluar, disposisi, template otomatis, arsip digital, tracking status, dan cetak surat langsung.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 fade-up">
                <div class="feature-card">
                    <span class="feature-tag tag-emerald">Sarpras</span>
                    <div class="feature-icon" style="background:linear-gradient(135deg,#7c3aed,#a78bfa);"><i class="bi bi-box-seam-fill"></i></div>
                    <h5>Inventaris / Sarpras</h5>
                    <p>Data inventaris, kategori &amp; lokasi, peminjaman, pengadaan, kondisi barang, dan laporan inventaris lengkap.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 fade-up">
                <div class="feature-card">
                    <span class="feature-tag tag-amber">Perpustakaan</span>
                    <div class="feature-icon" style="background:linear-gradient(135deg,#f97316,#fb923c);"><i class="bi bi-book-half"></i></div>
                    <h5>Perpustakaan</h5>
                    <p>Katalog buku, peminjaman &amp; pengembalian, denda, laporan statistik, dan kartu anggota siswa digital.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 fade-up">
                <div class="feature-card">
                    <span class="feature-tag tag-indigo">Akademik</span>
                    <div class="feature-icon" style="background:linear-gradient(135deg,#14b8a6,#2dd4bf);"><i class="bi bi-mortarboard-fill"></i></div>
                    <h5>Kesiswaan &amp; Kurikulum</h5>
                    <p>Data siswa, wali kelas, ekskul, pelanggaran, dokumen kurikulum, jadwal, dan distribusi agenda kegiatan.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 fade-up">
                <div class="feature-card">
                    <span class="feature-tag tag-emerald">Layanan</span>
                    <div class="feature-icon" style="background:linear-gradient(135deg,#64748b,#94a3b8);"><i class="bi bi-tools"></i></div>
                    <h5>Pramu Bakti</h5>
                    <p>Jadwal kebersihan &amp; pelayanan, laporan harian, permintaan bantuan kegiatan, dan checklist tugas harian.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════ FITUR UNGGULAN HIGHLIGHT ═══════════════════ --}}
<section class="section" style="padding-top:0;">
    <div class="container">
        <div class="big-feature fade-up">
            <div class="row align-items-center">
                <div class="col-lg-5 mb-4 mb-lg-0">
                    <div class="section-chip chip-white"><i class="bi bi-lightning-charge-fill"></i> Fitur Unggulan</div>
                    <h3>Teknologi Canggih<br>untuk Administrasi Cerdas</h3>
                    <p class="mt-3" style="font-size:.9rem;line-height:1.7;">
                        Dibekali kecerdasan buatan, integrasi cloud, pelacakan GPS, dan analisis data real-time untuk memodernisasi proses tata usaha.
                    </p>
                </div>
                <div class="col-lg-7">
                    <div class="row g-3">
                        <div class="col-sm-6 col-lg-4"><div class="mini-card"><i class="bi bi-robot" style="color:var(--emerald-light);"></i><h6>SIMPEG-AI</h6><small>Chatbot asisten cerdas</small></div></div>
                        <div class="col-sm-6 col-lg-4"><div class="mini-card"><i class="bi bi-file-earmark-word" style="color:#4ea8de;"></i><h6>Word AI</h6><small>Generate dokumen otomatis</small></div></div>
                        <div class="col-sm-6 col-lg-4"><div class="mini-card"><i class="bi bi-geo-alt-fill" style="color:#f472b6;"></i><h6>GPS Absensi</h6><small>Tracking lokasi realtime</small></div></div>
                        <div class="col-sm-6 col-lg-4"><div class="mini-card"><i class="bi bi-cloud-arrow-up-fill" style="color:var(--amber-light);"></i><h6>Cloud Backup</h6><small>Auto backup Google Drive</small></div></div>
                        <div class="col-sm-6 col-lg-4"><div class="mini-card"><i class="bi bi-chat-left-dots-fill" style="color:#a78bfa;"></i><h6>Live Chat</h6><small>Pesan antar staf realtime</small></div></div>
                        <div class="col-sm-6 col-lg-4"><div class="mini-card"><i class="bi bi-graph-up-arrow" style="color:#22d3ee;"></i><h6>Analitik</h6><small>Dashboard &amp; rekap data</small></div></div>
                        <div class="col-sm-6 col-lg-4"><div class="mini-card"><i class="bi bi-bell-fill" style="color:#fb923c;"></i><h6>Notifikasi</h6><small>Pengingat otomatis</small></div></div>
                        <div class="col-sm-6 col-lg-4"><div class="mini-card"><i class="bi bi-file-earmark-bar-graph-fill" style="color:var(--emerald-light);"></i><h6>SKP &amp; PKG</h6><small>Evaluasi kinerja digital</small></div></div>
                        <div class="col-sm-6 col-lg-4"><div class="mini-card"><i class="bi bi-download" style="color:#94a3b8;"></i><h6>Export</h6><small>PDF, Excel, Word</small></div></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════ ALUR LAYANAN ═══════════════════ --}}
<section class="section bg-light-custom" id="alur">
    <div class="container">
        <div class="text-center mb-5 fade-up">
            <div class="section-chip chip-emerald"><i class="bi bi-signpost-split-fill"></i> Alur Layanan</div>
            <h2 class="section-heading">Proses Administrasi<br>yang Terstruktur</h2>
            <p class="section-desc mx-auto">Alur kerja transparan dari login hingga arsip digital &mdash; setiap langkah terecord dan dapat diaudit.</p>
        </div>
        <div class="row g-4 justify-content-center fade-up">
            @php
                $steps = [
                    ['num' => 1, 'title' => 'Login Sistem', 'desc' => 'Masuk sesuai peran & autentikasi aman', 'color' => 'var(--indigo)'],
                    ['num' => 2, 'title' => 'Absensi GPS', 'desc' => 'Catat kehadiran via GPS & selfie', 'color' => 'var(--emerald)'],
                    ['num' => 3, 'title' => 'Tugas Harian', 'desc' => 'Kerjakan sesuai tupoksi masing-masing', 'color' => 'var(--amber)'],
                    ['num' => 4, 'title' => 'Persetujuan', 'desc' => 'Approval berjenjang dari atasan', 'color' => 'var(--indigo)'],
                    ['num' => 5, 'title' => 'Laporan', 'desc' => 'Generate & export data ke berbagai format', 'color' => 'var(--emerald)'],
                    ['num' => 6, 'title' => 'Arsip Cloud', 'desc' => 'Simpan & backup otomatis ke Google Drive', 'color' => 'var(--amber)'],
                ];
            @endphp
            @foreach($steps as $i => $step)
            <div class="col-lg-2 col-md-4 col-6">
                <div class="flow-card">
                    <div class="flow-num" style="background:linear-gradient(135deg,{{ $step['color'] }},{{ $step['color'] }}cc);">{{ $step['num'] }}</div>
                    <h6>{{ $step['title'] }}</h6>
                    <p>{{ $step['desc'] }}</p>
                    @if($i < count($steps) - 1)
                    <div class="flow-connector d-none d-lg-block" style="background:linear-gradient(90deg,{{ $step['color'] }},transparent);"></div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══════════════════ STATISTIK ═══════════════════ --}}
<section class="section stats-section" id="statistik">
    <div class="mesh-top"></div>
    <div class="container position-relative" style="z-index:2;">
        <div class="text-center mb-5 fade-up">
            <div class="section-chip chip-white"><i class="bi bi-graph-up-arrow"></i> Statistik</div>
            <h2 class="section-heading section-heading-white">Pengunjung &amp; Data Sekolah</h2>
            <p class="section-desc section-desc-light mx-auto">Data pengunjung website dan statistik layanan administrasi SMA Negeri 2 Jember secara real-time.</p>
        </div>
        <div class="row g-4 mb-5 fade-up">
            <div class="col-lg-3 col-md-6">
                <div class="stat-card">
                    <div class="live-dot"></div>
                    <div class="stat-icon" style="background:linear-gradient(135deg,var(--emerald),var(--cyan));"><i class="bi bi-person-check-fill"></i></div>
                    <div class="stat-val" data-count="{{ $statistikPengunjung['hari_ini'] }}">{{ number_format($statistikPengunjung['hari_ini']) }}</div>
                    <div class="stat-lbl">Pengunjung Hari Ini</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-icon" style="background:linear-gradient(135deg,var(--indigo),var(--violet));"><i class="bi bi-calendar-month"></i></div>
                    <div class="stat-val" data-count="{{ $statistikPengunjung['bulan_ini'] }}">{{ number_format($statistikPengunjung['bulan_ini']) }}</div>
                    <div class="stat-lbl">Pengunjung Bulan Ini</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-icon" style="background:linear-gradient(135deg,var(--amber),#f97316);"><i class="bi bi-people-fill"></i></div>
                    <div class="stat-val" data-count="{{ $statistikPengunjung['total_unik'] }}">{{ number_format($statistikPengunjung['total_unik']) }}</div>
                    <div class="stat-lbl">Total Pengunjung Unik</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-icon" style="background:linear-gradient(135deg,#e11d48,#f43f5e);"><i class="bi bi-eye-fill"></i></div>
                    <div class="stat-val" data-count="{{ $statistikPengunjung['total_kunjungan'] }}">{{ number_format($statistikPengunjung['total_kunjungan']) }}</div>
                    <div class="stat-lbl">Total Halaman Dilihat</div>
                </div>
            </div>
        </div>
        <div class="row g-4 fade-up">
            <div class="col-lg-7">
                <div class="map-card">
                    <div class="map-overlay">
                        <h6><i class="bi bi-geo-alt-fill me-1" style="color:var(--rose);"></i> SMA Negeri 2 Jember</h6>
                        <small>Jl. Jawa No. 16, Sumbersari, Jember</small>
                    </div>
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3949.3187417696467!2d113.70127867589513!3d-8.172088480405044!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd695b617d6a4e3%3A0x2bce46e45b9f9c0e!2sSMAN%202%20Jember!5e0!3m2!1sid!2sid!4v1709913600000!5m2!1sid!2sid" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Lokasi SMA Negeri 2 Jember"></iframe>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="school-data-panel mb-3">
                    <h6><i class="bi bi-building me-2" style="color:var(--indigo-light);"></i>Data Sekolah</h6>
                    <div class="sd-row"><span class="sd-label"><i class="bi bi-people-fill" style="color:var(--emerald-light);"></i> Pegawai Aktif</span><span class="sd-value">{{ number_format($statistikLayanan['total_pegawai']) }}</span></div>
                    <div class="sd-row"><span class="sd-label"><i class="bi bi-mortarboard-fill" style="color:var(--indigo-light);"></i> Siswa Aktif</span><span class="sd-value">{{ number_format($statistikLayanan['total_siswa']) }}</span></div>
                    <div class="sd-row"><span class="sd-label"><i class="bi bi-envelope-paper-fill" style="color:#f472b6;"></i> Total Surat</span><span class="sd-value">{{ number_format($statistikLayanan['total_surat']) }}</span></div>
                    <div class="sd-row"><span class="sd-label"><i class="bi bi-box-seam-fill" style="color:var(--amber-light);"></i> Inventaris</span><span class="sd-value">{{ number_format($statistikLayanan['total_inventaris']) }}</span></div>
                    <div class="sd-row"><span class="sd-label"><i class="bi bi-calendar-event-fill" style="color:#22d3ee;"></i> Agenda Mendatang</span><span class="sd-value">{{ number_format($statistikLayanan['total_acara']) }}</span></div>
                </div>
                <div class="school-data-panel">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0"><i class="bi bi-clock me-1"></i> Jam Layanan</h6>
                            <small style="color:var(--gray-light);font-size:.7rem;">Senin &ndash; Jumat</small>
                        </div>
                        <div style="text-align:right;">
                            <span style="color:var(--emerald-light);font-weight:800;font-size:1rem;font-family:'Space Grotesk',sans-serif;">07:00 - 15:00</span><br>
                            <small style="color:var(--gray);font-size:.6rem;">WIB</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════ HIERARKI ═══════════════════ --}}
<section class="section" id="struktur">
    <div class="container">
        <div class="text-center mb-5 fade-up">
            <div class="section-chip chip-amber"><i class="bi bi-diagram-3-fill"></i> Struktur Organisasi</div>
            <h2 class="section-heading">Hierarki Kepengurusan<br>Tata Usaha</h2>
            <p class="section-desc mx-auto">Pembagian peran, tugas, dan tanggung jawab yang jelas di lingkungan tata usaha SMA Negeri 2 Jember.</p>
        </div>
        <div class="org-chart fade-up">
            <div class="org-level">
                <div class="org-box leader">
                    <span class="org-icon"><i class="bi bi-person-workspace"></i></span>
                    <h6>Kepala Sekolah</h6>
                    <small>Penanggung Jawab &amp; Persetujuan</small>
                </div>
            </div>
            <div class="org-line-v"></div>
            <div class="org-level">
                <div class="org-box coordinator">
                    <span class="org-icon"><i class="bi bi-shield-lock" style="color:var(--emerald);"></i></span>
                    <h6>Administrator / KTU</h6>
                    <small>Koordinator &amp; Operator Sistem</small>
                </div>
            </div>
            <div class="org-line-v"></div>
            <div class="org-line-h"></div>
            <div class="org-level">
                <div class="org-box"><span class="org-icon"><i class="bi bi-people-fill" style="color:var(--emerald);"></i></span><h6>Kepegawaian</h6><small>IKI Pelaksana 1</small></div>
                <div class="org-box"><span class="org-icon"><i class="bi bi-cash-coin" style="color:var(--cyan);"></i></span><h6>Keuangan</h6><small>IKI Pelaksana 2</small></div>
                <div class="org-box"><span class="org-icon"><i class="bi bi-envelope-paper" style="color:var(--rose);"></i></span><h6>Persuratan</h6><small>IKI Pelaksana 3</small></div>
                <div class="org-box"><span class="org-icon"><i class="bi bi-mortarboard" style="color:#14b8a6;"></i></span><h6>Kesiswaan &amp; Kurikulum</h6><small>IKI Pelaksana 4</small></div>
            </div>
            <div class="org-line-h" style="margin-top:12px;"></div>
            <div class="org-level" style="margin-top:12px;">
                <div class="org-box"><span class="org-icon"><i class="bi bi-box-seam" style="color:var(--violet);"></i></span><h6>Inventaris / Sarpras</h6><small>IKI Pelaksana 5</small></div>
                <div class="org-box"><span class="org-icon"><i class="bi bi-book-half" style="color:#f97316;"></i></span><h6>Perpustakaan</h6><small>IKI Pelaksana 6</small></div>
                <div class="org-box"><span class="org-icon"><i class="bi bi-tools" style="color:var(--gray);"></i></span><h6>Pramu Bakti</h6><small>IKI Pelaksana 7</small></div>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════ CTA ═══════════════════ --}}
<section class="section cta-section">
    <div class="container text-center fade-up">
        <h2>Siap Mengelola Administrasi<br>Lebih Efisien?</h2>
        <p class="mx-auto mb-4" style="max-width:550px;font-size:.95rem;">
            Akses dashboard sesuai peran Anda dan mulai kelola administrasi sekolah secara digital, efisien, dan terintegrasi.
        </p>
        <div class="d-flex justify-content-center gap-3 flex-wrap">
            <a href="{{ route('login') }}" class="btn-cta-white"><i class="bi bi-rocket-takeoff-fill"></i> Masuk Sistem</a>
            <a href="{{ route('kinerja') }}" class="btn-cta-outline"><i class="bi bi-bar-chart-line-fill"></i> Lihat Kinerja Publik</a>
        </div>
    </div>
</section>

{{-- ═══════════════════ FOOTER ═══════════════════ --}}
<footer class="footer" id="kontak">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" style="width:48px;height:48px;border-radius:14px;object-fit:contain;border:2px solid rgba(255,255,255,.1);" onerror="this.style.display='none'">
                    <div>
                        <h5 class="mb-0" style="font-family:'Space Grotesk',sans-serif;">SIMPEG-SMART</h5>
                        <small style="color:var(--gray);font-size:.68rem;">Sistem Informasi Administrasi TU</small>
                    </div>
                </div>
                <p>Platform digital terpadu untuk pengelolaan administrasi tata usaha SMA Negeri 2 Jember. Modern, efisien, dan terintegrasi.</p>
                <div class="footer-socials mt-3">
                    <a href="https://www.youtube.com/@sman2jember878/featured" target="_blank" rel="noopener noreferrer" title="YouTube"><i class="bi bi-youtube"></i></a>
                    <a href="https://maps.app.goo.gl/eyfqRqGeS6xYWC318" target="_blank" rel="noopener noreferrer" title="Google Maps"><i class="bi bi-geo-alt-fill"></i></a>
                    <a href="https://www.instagram.com/sman2jember.official/" target="_blank" rel="noopener noreferrer" title="Instagram"><i class="bi bi-instagram"></i></a>
                    <a href="https://www.tiktok.com/@sman2jemberofficial_" target="_blank" rel="noopener noreferrer" title="TikTok"><i class="bi bi-tiktok"></i></a>
                    <a href="https://www.linkedin.com/school/sma-negeri-2-jember/" target="_blank" rel="noopener noreferrer" title="LinkedIn"><i class="bi bi-linkedin"></i></a>
                    <a href="https://sekolah.data.kemendikdasmen.go.id/profil-sekolah/57D32944-DF14-40BD-8868-C70A092F3496" target="_blank" rel="noopener noreferrer" title="Data Sekolah"><i class="bi bi-database-fill"></i></a>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <h5><i class="bi bi-geo-alt-fill me-2" style="color:var(--emerald-light);"></i>Kontak &amp; Lokasi</h5>
                <ul>
                    <li><i class="bi bi-building"></i><span>SMA Negeri 2 Jember</span></li>
                    <li><i class="bi bi-geo-alt-fill"></i><span>Jl. Jawa No. 16, Sumbersari, Kec. Sumbersari, Kabupaten Jember, Jawa Timur 68121</span></li>
                    <li><i class="bi bi-telephone-fill"></i><span><a href="tel:+62331321375">(0331) 321375</a></span></li>
                    <li><i class="bi bi-envelope-fill"></i><span><a href="mailto:info@sman2jember.sch.id">info@sman2jember.sch.id</a></span></li>
                    <li><i class="bi bi-globe2"></i><span><a href="https://sman2jember.sch.id" target="_blank" rel="noopener noreferrer">sman2jember.sch.id</a></span></li>
                </ul>
            </div>
            <div class="col-lg-4 col-md-12">
                <h5><i class="bi bi-globe2 me-2" style="color:var(--amber-light);"></i>Pengunjung</h5>
                <div class="d-flex flex-column gap-2">
                    @if(isset($lokasiPengunjung) && count($lokasiPengunjung) > 0)
                        @foreach($lokasiPengunjung->take(6) as $lokasi)
                        <div class="d-flex align-items-center justify-content-between" style="padding:6px 12px;background:rgba(255,255,255,.04);border-radius:8px;">
                            <span style="font-size:.78rem;color:var(--gray-light);">
                                <i class="bi bi-geo-alt me-1" style="color:var(--emerald-light);"></i>{{ $lokasi->negara ?? $lokasi->kota ?? 'Tidak diketahui' }}
                            </span>
                            <span class="badge" style="background:rgba(99,102,241,.2);color:var(--indigo-light);font-size:.68rem;">{{ $lokasi->total }} kunjungan</span>
                        </div>
                        @endforeach
                    @else
                        <div class="d-flex align-items-center justify-content-between" style="padding:6px 12px;background:rgba(255,255,255,.04);border-radius:8px;">
                            <span style="font-size:.78rem;color:var(--gray-light);"><i class="bi bi-geo-alt me-1" style="color:var(--emerald-light);"></i>Indonesia</span>
                            <span class="badge" style="background:rgba(99,102,241,.2);color:var(--indigo-light);font-size:.68rem;">{{ $statistikPengunjung['total_kunjungan'] ?? 0 }} kunjungan</span>
                        </div>
                    @endif
                </div>
                <div class="mt-3 d-flex align-items-center gap-3" style="font-size:.72rem;color:var(--gray);">
                    <span><i class="bi bi-people-fill me-1" style="color:var(--indigo-light);"></i>Total: {{ number_format($statistikPengunjung['total_kunjungan'] ?? 0) }}</span>
                    <span><i class="bi bi-calendar-check me-1" style="color:var(--emerald-light);"></i>Hari Ini: {{ $statistikPengunjung['hari_ini'] ?? 0 }}</span>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} SIMPEG-SMART &mdash; Sistem Informasi Administrasi Tata Usaha | SMA Negeri 2 Jember</p>
            <div class="footer-socials">
                <a href="#" title="Ke Atas" onclick="window.scrollTo({top:0,behavior:'smooth'});return false;"><i class="bi bi-arrow-up"></i></a>
            </div>
        </div>
    </div>
</footer>

{{-- ═══════════════════ SCRIPTS ═══════════════════ --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Navbar scroll
    window.addEventListener('scroll', function() {
        const nav = document.getElementById('navbar');
        nav.classList.toggle('scrolled', window.scrollY > 50);
        document.querySelectorAll('section[id]').forEach(sec => {
            const top = sec.offsetTop - 120, h = sec.offsetHeight, id = sec.id;
            const link = document.querySelector('.nav-pills-custom a[href="#' + id + '"]');
            if (link) link.classList.toggle('active', window.scrollY >= top && window.scrollY < top + h);
        });
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

    // Counter animation
    const counterObs = new IntersectionObserver(entries => {
        entries.forEach(e => {
            if (!e.isIntersecting) return;
            document.querySelectorAll('.stat-val[data-count]').forEach(el => {
                const target = parseInt(el.dataset.count) || 0;
                if (!target) return;
                let cur = 0;
                const step = Math.max(1, Math.ceil(target / 90));
                const t = setInterval(() => {
                    cur += step;
                    if (cur >= target) { cur = target; clearInterval(t); }
                    el.textContent = cur.toLocaleString('id-ID');
                }, 16);
            });
            counterObs.disconnect();
        });
    }, { threshold: 0.25 });
    const ss = document.getElementById('statistik');
    if (ss) counterObs.observe(ss);

    // Berita carousel controls
    (function() {
        const track = document.getElementById('beritaTrack');
        const pauseBtn = document.getElementById('beritaPause');
        const prevBtn = document.getElementById('beritaPrev');
        const nextBtn = document.getElementById('beritaNext');
        if (!track) return;

        let isPaused = false;
        if (pauseBtn) pauseBtn.addEventListener('click', function() {
            isPaused = !isPaused;
            track.classList.toggle('paused', isPaused);
            this.innerHTML = isPaused
                ? '<i class="bi bi-play-fill"></i>'
                : '<i class="bi bi-pause-fill"></i>';
        });

        function scrollByCard(dir) {
            const card = track.querySelector('.berita-card');
            if (!card) return;
            const w = card.offsetWidth + 24;
            track.style.animation = 'none';
            track.offsetHeight; // reflow
            const current = track.scrollLeft || 0;
            const matrix = getComputedStyle(track).transform;
            let tx = 0;
            if (matrix && matrix !== 'none') {
                tx = parseFloat(matrix.split(',')[4]) || 0;
            }
            track.style.transform = 'translateX(' + (tx + (dir * w)) + 'px)';
            track.style.transition = 'transform .4s ease';
            setTimeout(() => {
                track.style.transition = '';
                track.style.animation = '';
                if (!isPaused) track.classList.remove('paused');
            }, 500);
        }
        if (prevBtn) prevBtn.addEventListener('click', () => scrollByCard(1));
        if (nextBtn) nextBtn.addEventListener('click', () => scrollByCard(-1));
    })();
</script>
</body>
</html>
