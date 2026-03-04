<!DOCTYPE html>
<html><head>
    <meta charset="utf-8"><title>Daftar Dokumen - TU Administrasi</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; font-size: 12px; padding: 20px; }
        h2 { text-align: center; margin-bottom: 4px; }
        .subtitle { text-align: center; color: #666; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 6px 8px; text-align: left; }
        th { background: #6366f1; color: #fff; }
        tr:nth-child(even) { background: #f8f9fa; }
        .footer { text-align: center; margin-top: 20px; font-size: 10px; color: #999; }
        @media print { body { padding: 0; } }
    </style>
</head><body onload="window.print()">
    <h2>Daftar Dokumen</h2>
    <p class="subtitle">TU Administrasi - SMA Negeri 2 Jember | Dicetak: {{ now()->format('d/m/Y H:i') }}</p>
    <table>
        <thead><tr><th>No</th><th>Judul</th><th>Kategori</th><th>File</th><th>Ukuran</th><th>Diupload</th><th>Tanggal</th></tr></thead>
        <tbody>
            @foreach($documents as $i => $doc)
            <tr>
                <td>{{ $i + 1 }}</td><td>{{ $doc->judul }}</td><td>{{ ucfirst($doc->kategori) }}</td>
                <td>{{ $doc->nama_file }}</td><td>{{ $doc->file_size_formatted }}</td>
                <td>{{ $doc->uploader->nama ?? '-' }}</td><td>{{ $doc->created_at->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="footer">Dicetak dari Sistem TU Administrasi SMA Negeri 2 Jember</div>
</body></html>
