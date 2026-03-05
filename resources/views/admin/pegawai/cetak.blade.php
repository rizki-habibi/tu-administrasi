<!DOCTYPE html>
<html><head>
    <meta charset="utf-8"><title>Data Staf - TU Administrasi</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; font-size: 12px; padding: 20px; }
        h2 { text-align: center; margin-bottom: 4px; }
        .subtitle { text-align: center; color: #666; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 6px 8px; text-align: left; }
        th { background: #6366f1; color: #fff; }
        tr:nth-child(even) { background: #f8f9fa; }
        .footer { text-align: center; margin-top: 20px; font-size: 10px; color: #999; }
    </style>
</head><body onload="window.print()">
    <h2>Data Staf</h2>
    <p class="subtitle">TU Administrasi - SMA Negeri 2 Jember | {{ now()->format('d/m/Y H:i') }}</p>
    <table>
        <thead><tr><th>No</th><th>Nama</th><th>Email</th><th>Jabatan</th><th>Telepon</th><th>Status</th></tr></thead>
        <tbody>
            @foreach($staffs as $i => $s)
            <tr><td>{{ $i+1 }}</td><td>{{ $s->nama }}</td><td>{{ $s->email }}</td><td>{{ $s->jabatan ?? '-' }}</td><td>{{ $s->telepon ?? '-' }}</td><td>{{ $s->aktif ? 'Aktif' : 'Nonaktif' }}</td></tr>
            @endforeach
        </tbody>
    </table>
    <div class="footer">Dicetak dari Sistem TU Administrasi SMA Negeri 2 Jember</div>
</body></html>
