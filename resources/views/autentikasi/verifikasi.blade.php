<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Verifikasi Email - SIMPEG-SMART</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Poppins', sans-serif; min-height: 100vh;
            background: linear-gradient(135deg, #1e1b4b 0%, #312e81 30%, #4338ca 60%, #6366f1 100%);
            display: flex; align-items: center; justify-content: center; padding: 20px;
        }
        .card-container {
            max-width: 520px; width: 100%; background: #fff; border-radius: 20px; padding: 50px 40px;
            box-shadow: 0 25px 60px rgba(0,0,0,.3); position: relative; z-index: 1; text-align: center;
        }
        .card-container h3 { font-size: 1.4rem; font-weight: 700; color: #1e293b; margin-bottom: 6px; }
        .card-container p { font-size: .85rem; color: #64748b; line-height: 1.7; }
        .success-msg { background: #ecfdf5; border: 1px solid #6ee7b7; border-radius: 10px; padding: 12px 16px; color: #065f46; font-size: .82rem; margin-bottom: 20px; }
        .btn-submit {
            display: inline-block; padding: 10px 24px; border: none; border-radius: 10px; font-family: 'Poppins', sans-serif;
            font-size: .85rem; font-weight: 600; color: #6366f1; cursor: pointer; background: none; text-decoration: underline;
        }
        .footer-text { text-align: center; margin-top: 24px; font-size: .75rem; color: #94a3b8; }
    </style>
</head>
<body>
    <div class="card-container">
        <div style="margin-bottom: 20px;">
            <div style="width:60px;height:60px;border-radius:14px;background:linear-gradient(135deg,#6366f1,#8b5cf6);display:inline-flex;align-items:center;justify-content:center;margin-bottom:12px;">
                <i class="bi bi-envelope-check-fill" style="color:#fff;font-size:1.5rem;"></i>
            </div>
        </div>
        <h3>Verifikasi Alamat Email</h3>

        @if (session('resent'))
            <div class="success-msg" style="margin-top:16px;">
                <i class="bi bi-check-circle-fill me-1"></i> Tautan verifikasi baru telah dikirim ke alamat email Anda.
            </div>
        @endif

        <p style="margin-top:16px;">
            Sebelum melanjutkan, silakan periksa email Anda untuk tautan verifikasi.<br>
            Jika Anda tidak menerima email,
        </p>

        <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
            @csrf
            <button type="submit" class="btn-submit">klik di sini untuk mengirim ulang</button>.
        </form>

        <div class="footer-text">
            &copy; {{ date('Y') }} SIMPEG-SMART - SMA Negeri 2 Jember
        </div>
    </div>
</body>
</html>
