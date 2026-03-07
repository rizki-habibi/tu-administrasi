<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $panduan->judul }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/github-markdown-css@5/github-markdown-light.min.css">
    <style>
        @page { margin: 20mm 18mm 20mm 18mm; size: A4; }
        * { box-sizing: border-box; }
        body { font-family: 'Segoe UI', 'Poppins', -apple-system, sans-serif; margin: 0; padding: 0; color: #1e293b; font-size: 11pt; line-height: 1.6; }

        /* === COVER PAGE === */
        .cover-page {
            width: 100%; height: 100vh; display: flex; flex-direction: column;
            align-items: center; justify-content: center; text-align: center;
            page-break-after: always; position: relative; overflow: hidden;
        }
        .cover-border {
            position: absolute; inset: 12mm; border: 3px solid #312e81;
            border-radius: 8px; pointer-events: none;
        }
        .cover-border::before {
            content: ''; position: absolute; inset: 4px; border: 1px solid #6366f1;
            border-radius: 6px;
        }
        .cover-ornament-top, .cover-ornament-bottom {
            position: absolute; left: 50%; transform: translateX(-50%);
            width: 120px; height: 4px; background: linear-gradient(90deg, transparent, #6366f1, transparent);
        }
        .cover-ornament-top { top: 18mm; }
        .cover-ornament-bottom { bottom: 18mm; }

        .cover-logo { width: 80px; height: 80px; margin-bottom: 20px; }
        .cover-institution { font-size: 11pt; color: #64748b; text-transform: uppercase; letter-spacing: 3px; margin-bottom: 6px; }
        .cover-school { font-size: 14pt; font-weight: 700; color: #312e81; margin-bottom: 40px; }
        .cover-divider { width: 80px; height: 3px; background: #6366f1; margin: 0 auto 30px; border-radius: 2px; }
        .cover-title { font-size: 22pt; font-weight: 800; color: #1e293b; margin-bottom: 10px; line-height: 1.3; padding: 0 40px; }
        .cover-subtitle { font-size: 11pt; color: #64748b; margin-bottom: 40px; padding: 0 50px; line-height: 1.5; }
        .cover-version { display: inline-block; background: #eef2ff; color: #4338ca; padding: 6px 20px; border-radius: 20px; font-size: 10pt; font-weight: 600; margin-bottom: 50px; }
        .cover-meta-table { border-collapse: collapse; margin: 0 auto; font-size: 9.5pt; }
        .cover-meta-table td { padding: 6px 16px; color: #475569; }
        .cover-meta-table td:first-child { font-weight: 600; text-align: right; color: #1e293b; }
        .cover-meta-table td:last-child { text-align: left; }

        /* === CONTENT === */
        .content-page { padding: 0; }
        .markdown-body {
            font-family: 'Segoe UI', -apple-system, sans-serif;
            font-size: 10.5pt; line-height: 1.7; max-width: 100%; padding: 0;
        }
        .markdown-body h1 { font-size: 16pt; font-weight: 700; color: #1e293b; padding-bottom: 8px; border-bottom: 2px solid #e2e8f0; margin-top: 0; }
        .markdown-body h2 { font-size: 13pt; font-weight: 600; color: #312e81; margin-top: 24px; padding-bottom: 6px; border-bottom: 1px solid #e2e8f0; }
        .markdown-body h3 { font-size: 11.5pt; font-weight: 600; color: #4338ca; margin-top: 18px; }
        .markdown-body table { font-size: 9.5pt; border-collapse: collapse; width: 100%; margin: 10px 0; }
        .markdown-body table th { background: #f0f2f8; font-weight: 600; color: #475569; padding: 8px 12px; text-align: left; border: 1px solid #d1d5db; }
        .markdown-body table td { padding: 6px 12px; border: 1px solid #d1d5db; }
        .markdown-body table tr:nth-child(even) { background: #f8fafc; }
        .markdown-body code { background: #f1f5f9; color: #6366f1; padding: 1px 4px; border-radius: 3px; font-size: 9.5pt; }
        .markdown-body pre { background: #f8fafc; color: #1e293b; padding: 12px; border-radius: 6px; border: 1px solid #e2e8f0; overflow-x: auto; font-size: 9pt; }
        .markdown-body pre code { background: transparent; color: inherit; padding: 0; }
        .markdown-body blockquote { border-left: 3px solid #6366f1; background: #eef2ff; padding: 8px 12px; margin: 10px 0; color: #4338ca; font-size: 10pt; }
        .markdown-body ol, .markdown-body ul { padding-left: 20px; }
        .markdown-body li { margin-bottom: 3px; }
        .markdown-body a { color: #6366f1; text-decoration: underline; }
        .markdown-body strong { color: #1e293b; }
        .markdown-body img { max-width: 100%; }

        .doc-footer {
            margin-top: 40px; padding-top: 12px; border-top: 1px solid #e2e8f0;
            font-size: 8.5pt; color: #94a3b8; text-align: center;
        }
    </style>
</head>
<body>
    {{-- COVER PAGE --}}
    <div class="cover-page">
        <div class="cover-border"></div>
        <div class="cover-ornament-top"></div>
        <div class="cover-ornament-bottom"></div>

        @if($panduan->logo)
            <img src="{{ $panduan->logo_url }}" alt="Logo" class="cover-logo">
        @else
            <div style="width:80px;height:80px;border-radius:16px;background:linear-gradient(135deg,#312e81,#6366f1);display:flex;align-items:center;justify-content:center;margin-bottom:20px;">
                <span style="font-size:32pt;color:#fff;">&#9776;</span>
            </div>
        @endif

        <div class="cover-institution">Sistem TU Administrasi</div>
        <div class="cover-school">SMA Negeri 2 Jember</div>
        <div class="cover-divider"></div>
        <div class="cover-title">{{ $panduan->judul }}</div>
        <div class="cover-subtitle">{{ $panduan->deskripsi }}</div>

        @if($panduan->versi)
            <div class="cover-version">{{ $panduan->versi }}</div>
        @endif

        <table class="cover-meta-table">
            <tr><td>Kategori</td><td>: {{ ucfirst($panduan->kategori) }}</td></tr>
            <tr><td>Penulis</td><td>: {{ $panduan->pembuat->nama ?? 'Administrator' }}</td></tr>
            <tr><td>Tanggal</td><td>: {{ $panduan->created_at->translatedFormat('d F Y') }}</td></tr>
            @if($panduan->updated_at->ne($panduan->created_at))
            <tr><td>Diperbarui</td><td>: {{ $panduan->updated_at->translatedFormat('d F Y') }}</td></tr>
            @endif
        </table>
    </div>

    {{-- CONTENT --}}
    <div class="content-page">
        <div class="markdown-body">
            {!! \Illuminate\Support\Str::markdown($panduan->konten ?? '') !!}
        </div>
        <div class="doc-footer">
            Dokumen ini digenerate dari Sistem TU Administrasi &mdash; SMA Negeri 2 Jember &mdash; {{ now()->translatedFormat('d F Y, H:i') }} WIB
        </div>
    </div>
</body>
</html>
