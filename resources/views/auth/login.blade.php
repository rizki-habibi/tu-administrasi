<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - TU Administrasi</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Poppins', sans-serif; min-height: 100vh;
            background: linear-gradient(135deg, #1e1b4b 0%, #312e81 30%, #4338ca 60%, #6366f1 100%);
            display: flex; align-items: center; justify-content: center; padding: 20px;
            position: relative; overflow: hidden;
        }
        body::before {
            content: ''; position: absolute; top: -50%; left: -50%; width: 200%; height: 200%;
            background: radial-gradient(circle at 30% 40%, rgba(139,92,246,.15) 0%, transparent 50%),
                        radial-gradient(circle at 70% 60%, rgba(99,102,241,.1) 0%, transparent 50%);
            animation: float 15s ease-in-out infinite;
        }
        @keyframes float { 0%,100% { transform: translate(0,0); } 50% { transform: translate(-20px, -20px); } }

        .login-container {
            display: flex; max-width: 900px; width: 100%; border-radius: 20px; overflow: hidden;
            box-shadow: 0 25px 60px rgba(0,0,0,.3); position: relative; z-index: 1;
        }

        /* Left Panel */
        .login-left {
            flex: 1; background: linear-gradient(135deg, #1e1b4b, #312e81);
            padding: 50px 40px; display: flex; flex-direction: column; justify-content: center;
            position: relative; overflow: hidden;
        }
        .login-left::before {
            content: ''; position: absolute; top: -100px; right: -100px;
            width: 300px; height: 300px; border-radius: 50%;
            background: rgba(99,102,241,.2);
        }
        .login-left::after {
            content: ''; position: absolute; bottom: -60px; left: -60px;
            width: 200px; height: 200px; border-radius: 50%;
            background: rgba(139,92,246,.15);
        }
        .login-left .content { position: relative; z-index: 1; }
        .login-left img { width: 80px; height: 80px; border-radius: 16px; object-fit: cover; margin-bottom: 20px; border: 2px solid rgba(255,255,255,.15); }
        .login-left h2 { color: #fff; font-size: 1.6rem; font-weight: 700; margin-bottom: 6px; }
        .login-left h4 { color: #a5b4fc; font-size: 1rem; font-weight: 400; margin-bottom: 24px; }
        .login-left p { color: #c7d2fe; font-size: .85rem; line-height: 1.6; }
        .login-left .features { list-style: none; padding: 0; margin-top: 24px; }
        .login-left .features li { color: #c7d2fe; font-size: .82rem; padding: 6px 0; display: flex; align-items: center; gap: 10px; }
        .login-left .features li i { color: #818cf8; font-size: .9rem; }

        /* Right Panel */
        .login-right {
            flex: 1; background: #fff; padding: 50px 40px;
            display: flex; flex-direction: column; justify-content: center;
        }
        .login-right h3 { font-size: 1.4rem; font-weight: 700; color: #1e293b; margin-bottom: 6px; }
        .login-right p.subtitle { font-size: .85rem; color: #64748b; margin-bottom: 30px; }

        .form-group { margin-bottom: 20px; }
        .form-group label { font-size: .8rem; font-weight: 500; color: #475569; margin-bottom: 6px; display: block; }
        .input-group-custom {
            display: flex; align-items: center; background: #f8fafc; border: 1.5px solid #e2e8f0;
            border-radius: 10px; transition: all .2s; overflow: hidden;
        }
        .input-group-custom:focus-within { border-color: #818cf8; box-shadow: 0 0 0 3px rgba(99,102,241,.1); background: #fff; }
        .input-group-custom .icon { padding: 0 14px; color: #94a3b8; font-size: 1.1rem; }
        .input-group-custom input {
            flex: 1; border: none; background: transparent; padding: 12px 14px 12px 0;
            font-family: 'Poppins', sans-serif; font-size: .85rem; color: #1e293b; outline: none;
        }
        .input-group-custom input::placeholder { color: #94a3b8; }
        .input-group-custom .toggle-pw { background: none; border: none; padding: 0 14px; color: #94a3b8; cursor: pointer; font-size: 1rem; }
        .input-group-custom .toggle-pw:hover { color: #6366f1; }

        .form-check { margin-bottom: 20px; }
        .form-check label { font-size: .8rem; color: #64748b; }

        .btn-login {
            width: 100%; padding: 12px; border: none; border-radius: 10px; font-family: 'Poppins', sans-serif;
            font-size: .9rem; font-weight: 600; color: #fff; cursor: pointer;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            transition: all .3s; position: relative; overflow: hidden;
        }
        .btn-login:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(99,102,241,.4); }
        .btn-login:active { transform: translateY(0); }

        .error-msg { color: #ef4444; font-size: .78rem; margin-top: 4px; }

        .footer-text { text-align: center; margin-top: 24px; font-size: .75rem; color: #94a3b8; }

        @media (max-width: 768px) {
            .login-left { display: none; }
            .login-container { max-width: 440px; }
            .login-right { padding: 40px 28px; }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-left">
            <div class="content">
                <img src="{{ asset('images/logo.png') }}" alt="Logo SMA Negeri 2 Jember" style="width:80px;height:80px;object-fit:contain;" onerror="this.style.display='none'">
                <h2>TU Administrasi</h2>
                <h4>SMA Negeri 2 Jember</h4>
                <p>Sistem Informasi Tata Usaha terintegrasi untuk pengelolaan administrasi sekolah yang efisien dan modern.</p>
                <ul class="features">
                    <li><i class="bi bi-check-circle-fill"></i> Manajemen Kehadiran & GPS</li>
                    <li><i class="bi bi-check-circle-fill"></i> Pengajuan Izin & Cuti Online</li>
                    <li><i class="bi bi-check-circle-fill"></i> Manajemen Dokumen & Arsip</li>
                    <li><i class="bi bi-check-circle-fill"></i> Laporan & Export Data</li>
                    <li><i class="bi bi-check-circle-fill"></i> Agenda & Event Sekolah</li>
                </ul>
            </div>
        </div>

        <div class="login-right">
            <h3>Selamat Datang!</h3>
            <p class="subtitle">Masuk ke akun Anda untuk melanjutkan</p>

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <label>Email</label>
                    <div class="input-group-custom">
                        <span class="icon"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="Masukkan email" required autofocus>
                    </div>
                    @error('email') <div class="error-msg">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label>Kata Sandi</label>
                    <div class="input-group-custom">
                        <span class="icon"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" id="password" placeholder="Masukkan kata sandi" required>
                        <button type="button" class="toggle-pw" onclick="togglePassword()"><i class="bi bi-eye" id="eyeIcon"></i></button>
                    </div>
                    @error('password') <div class="error-msg">{{ $message }}</div> @enderror
                </div>

                <div class="d-flex justify-content-between align-items-center" style="margin-bottom:20px;">
                    <div class="form-check mb-0">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember" style="font-size:.8rem;color:#64748b;">Ingat saya</label>
                    </div>
                    @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" style="font-size:.8rem;color:#6366f1;text-decoration:none;font-weight:500;">Lupa kata sandi?</a>
                    @endif
                </div>

                <button type="submit" class="btn-login">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Masuk
                </button>
            </form>

            <div class="footer-text">
                &copy; {{ date('Y') }} TU Administrasi - SMA Negeri 2 Jember
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const pw = document.getElementById('password');
            const icon = document.getElementById('eyeIcon');
            if (pw.type === 'password') { pw.type = 'text'; icon.className = 'bi bi-eye-slash'; }
            else { pw.type = 'password'; icon.className = 'bi bi-eye'; }
        }
    </script>
</body>
</html>
