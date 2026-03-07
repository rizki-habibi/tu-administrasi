<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { font-family: 'Segoe UI', -apple-system, sans-serif; margin: 0; padding: 0; background: #f8fafc; }
        .container { max-width: 600px; margin: 0 auto; padding: 30px 20px; }
        .card { background: #fff; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,.08); overflow: hidden; }
        .header { background: linear-gradient(135deg, #6366f1, #8b5cf6); padding: 24px; text-align: center; color: #fff; }
        .header h1 { margin: 0; font-size: 20px; font-weight: 700; }
        .header p { margin: 6px 0 0; font-size: 13px; opacity: .85; }
        .body { padding: 24px; }
        .body h2 { margin: 0 0 8px; font-size: 17px; color: #1e293b; }
        .body p { margin: 0 0 16px; font-size: 14px; color: #475569; line-height: 1.6; }
        .btn { display: inline-block; padding: 10px 24px; background: linear-gradient(135deg, #6366f1, #8b5cf6); color: #fff; text-decoration: none; border-radius: 8px; font-size: 14px; font-weight: 600; }
        .footer { padding: 16px 24px; background: #f1f5f9; text-align: center; font-size: 12px; color: #94a3b8; }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="header">
                <h1>SIMPEG-SMART</h1>
                <p>Sistem Administrasi Tata Usaha</p>
            </div>
            <div class="body">
                <p>Halo <strong>{{ $nama }}</strong>,</p>
                <h2>{{ $judul }}</h2>
                <p>{!! nl2br(e($pesan)) !!}</p>
                @if($tautan)
                <p><a href="{{ $tautan }}" class="btn">Lihat Detail</a></p>
                @endif
            </div>
            <div class="footer">
                &copy; {{ date('Y') }} SIMPEG-SMART - SMA Negeri 2 Jember<br>
                Email ini dikirim secara otomatis, tidak perlu dibalas.
            </div>
        </div>
    </div>
</body>
</html>
