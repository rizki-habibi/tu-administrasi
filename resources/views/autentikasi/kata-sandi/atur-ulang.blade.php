<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Atur Ulang Kata Sandi - SIMPEG-SMART</title>
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
        .card-container {
            max-width: 480px; width: 100%; background: #fff; border-radius: 20px; padding: 50px 40px;
            box-shadow: 0 25px 60px rgba(0,0,0,.3); position: relative; z-index: 1;
        }
        .card-container h3 { font-size: 1.4rem; font-weight: 700; color: #1e293b; margin-bottom: 6px; }
        .card-container p.subtitle { font-size: .85rem; color: #64748b; margin-bottom: 30px; }
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
        .btn-submit {
            width: 100%; padding: 12px; border: none; border-radius: 10px; font-family: 'Poppins', sans-serif;
            font-size: .9rem; font-weight: 600; color: #fff; cursor: pointer;
            background: linear-gradient(135deg, #6366f1, #8b5cf6); transition: all .3s;
        }
        .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(99,102,241,.4); }
        .error-msg { color: #ef4444; font-size: .78rem; margin-top: 4px; }
        .footer-text { text-align: center; margin-top: 24px; font-size: .75rem; color: #94a3b8; }
    </style>
</head>
<body>
    <div class="card-container">
        <div style="text-align:center; margin-bottom: 20px;">
            <div style="width:60px;height:60px;border-radius:14px;background:linear-gradient(135deg,#6366f1,#8b5cf6);display:inline-flex;align-items:center;justify-content:center;margin-bottom:12px;">
                <i class="bi bi-shield-lock-fill" style="color:#fff;font-size:1.5rem;"></i>
            </div>
        </div>
        <h3 style="text-align:center;">Atur Ulang Kata Sandi</h3>
        <p class="subtitle" style="text-align:center;">Masukkan kata sandi baru untuk akun Anda</p>

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="form-group">
                <label>Alamat Email</label>
                <div class="input-group-custom">
                    <span class="icon"><i class="bi bi-envelope"></i></span>
                    <input type="email" name="email" value="{{ $email ?? old('email') }}" placeholder="Email terdaftar" required autofocus>
                </div>
                @error('email') <div class="error-msg">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label>Kata Sandi Baru</label>
                <div class="input-group-custom">
                    <span class="icon"><i class="bi bi-lock"></i></span>
                    <input type="password" name="password" placeholder="Minimal 8 karakter" required>
                </div>
                @error('password') <div class="error-msg">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label>Konfirmasi Kata Sandi</label>
                <div class="input-group-custom">
                    <span class="icon"><i class="bi bi-lock-fill"></i></span>
                    <input type="password" name="password_confirmation" placeholder="Ulangi kata sandi baru" required>
                </div>
            </div>

            <button type="submit" class="btn-submit">
                <i class="bi bi-check-lg me-1"></i> Simpan Kata Sandi Baru
            </button>
        </form>

        <div class="footer-text">
            &copy; {{ date('Y') }} SIMPEG-SMART - SMA Negeri 2 Jember
        </div>
    </div>
</body>
</html>
