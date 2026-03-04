<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dev System Scan — {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --bg-primary: #0d1117;
            --bg-secondary: #161b22;
            --bg-card: #1c2128;
            --border-color: #30363d;
            --text-primary: #e6edf3;
            --text-secondary: #8b949e;
            --accent-green: #3fb950;
            --accent-red: #f85149;
            --accent-yellow: #d29922;
            --accent-blue: #58a6ff;
            --accent-purple: #bc8cff;
        }
        * { box-sizing: border-box; }
        body {
            background: var(--bg-primary);
            color: var(--text-primary);
            font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, sans-serif;
            margin: 0;
            padding: 0;
        }
        .top-bar {
            background: var(--bg-secondary);
            border-bottom: 1px solid var(--border-color);
            padding: 16px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .top-bar h1 {
            font-size: 1.25rem;
            font-weight: 600;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .top-bar .actions { display: flex; gap: 10px; }
        .btn-dev {
            padding: 8px 16px;
            border-radius: 6px;
            border: 1px solid var(--border-color);
            background: var(--bg-card);
            color: var(--text-primary);
            cursor: pointer;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: all 0.15s;
        }
        .btn-dev:hover { border-color: var(--accent-blue); color: var(--accent-blue); }
        .btn-dev.danger:hover { border-color: var(--accent-red); color: var(--accent-red); }
        .btn-dev.primary { background: var(--accent-blue); color: #fff; border-color: var(--accent-blue); }
        .btn-dev.primary:hover { opacity: 0.85; }
        .container-scan { max-width: 1200px; margin: 0 auto; padding: 24px; }
        .status-banner {
            padding: 20px 24px;
            border-radius: 10px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .status-banner.safe {
            background: linear-gradient(135deg, rgba(63,185,80,0.15), rgba(63,185,80,0.05));
            border: 1px solid rgba(63,185,80,0.3);
        }
        .status-banner.error {
            background: linear-gradient(135deg, rgba(248,81,73,0.15), rgba(248,81,73,0.05));
            border: 1px solid rgba(248,81,73,0.3);
        }
        .status-banner .status-text { font-size: 1.4rem; font-weight: 700; }
        .status-banner.safe .status-text { color: var(--accent-green); }
        .status-banner.error .status-text { color: var(--accent-red); }
        .status-meta { font-size: 0.85rem; color: var(--text-secondary); text-align: right; }
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }
        .stat-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            padding: 16px;
            text-align: center;
        }
        .stat-card .stat-value { font-size: 2rem; font-weight: 700; }
        .stat-card .stat-label { font-size: 0.8rem; color: var(--text-secondary); margin-top: 4px; }
        .stat-card.green .stat-value { color: var(--accent-green); }
        .stat-card.red .stat-value { color: var(--accent-red); }
        .stat-card.yellow .stat-value { color: var(--accent-yellow); }
        .stat-card.blue .stat-value { color: var(--accent-blue); }
        .stat-card.purple .stat-value { color: var(--accent-purple); }
        .panel {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            margin-bottom: 24px;
            overflow: hidden;
        }
        .panel-header {
            padding: 14px 20px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-weight: 600;
            font-size: 0.95rem;
        }
        .panel-header .badge-count {
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 2px 10px;
            font-size: 0.75rem;
            color: var(--text-secondary);
        }
        .panel-body { padding: 0; }
        .panel-body.padded { padding: 16px 20px; }
        table.scan-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.85rem;
        }
        table.scan-table th {
            text-align: left;
            padding: 10px 16px;
            background: var(--bg-secondary);
            color: var(--text-secondary);
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 1px solid var(--border-color);
        }
        table.scan-table td {
            padding: 10px 16px;
            border-bottom: 1px solid var(--border-color);
            vertical-align: top;
        }
        table.scan-table tr:last-child td { border-bottom: none; }
        table.scan-table tr:hover { background: rgba(255,255,255,0.02); }
        .badge-level {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.05em;
        }
        .badge-level.error, .badge-level.critical, .badge-level.emergency {
            background: rgba(248,81,73,0.15);
            color: var(--accent-red);
        }
        .badge-level.warning {
            background: rgba(210,153,34,0.15);
            color: var(--accent-yellow);
        }
        .badge-level.info, .badge-level.notice, .badge-level.debug {
            background: rgba(88,166,255,0.15);
            color: var(--accent-blue);
        }
        .badge-ok {
            color: var(--accent-green);
            font-weight: 600;
        }
        .badge-err {
            color: var(--accent-red);
            font-weight: 600;
        }
        .suggestion {
            margin-top: 6px;
            padding: 6px 10px;
            background: rgba(210,153,34,0.1);
            border-left: 3px solid var(--accent-yellow);
            border-radius: 0 4px 4px 0;
            font-size: 0.8rem;
            color: var(--accent-yellow);
        }
        .mono {
            font-family: 'Cascadia Code', 'Fira Code', 'Consolas', monospace;
            font-size: 0.8rem;
        }
        .env-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 12px;
        }
        .env-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid var(--border-color);
        }
        .env-item:last-child { border-bottom: none; }
        .env-key { color: var(--text-secondary); font-size: 0.85rem; }
        .env-val { font-weight: 600; font-size: 0.85rem; }
        .spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid var(--border-color);
            border-top-color: var(--accent-blue);
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
        .empty-state {
            text-align: center;
            padding: 32px;
            color: var(--text-secondary);
        }
        .empty-state i { font-size: 2rem; display: block; margin-bottom: 8px; }
        .scrollable { max-height: 500px; overflow-y: auto; }
        .scrollable::-webkit-scrollbar { width: 6px; }
        .scrollable::-webkit-scrollbar-track { background: var(--bg-card); }
        .scrollable::-webkit-scrollbar-thumb { background: var(--border-color); border-radius: 3px; }
        .file-link { color: var(--accent-blue); text-decoration: none; }
        .file-link:hover { text-decoration: underline; }
        @media (max-width: 768px) {
            .stat-grid { grid-template-columns: repeat(2, 1fr); }
            .top-bar { flex-direction: column; gap: 10px; }
        }
    </style>
</head>
<body>

<div class="top-bar">
    <h1>
        <i class="bi bi-shield-check"></i>
        Dev System Scan
    </h1>
    <div class="actions">
        <button class="btn-dev" onclick="window.location.href='/'">
            <i class="bi bi-house"></i> Beranda
        </button>
        <button class="btn-dev danger" id="btnClearLog">
            <i class="bi bi-trash3"></i> Clear Log
        </button>
        <button class="btn-dev primary" id="btnRefresh">
            <i class="bi bi-arrow-clockwise"></i> Refresh Scan
        </button>
    </div>
</div>

<div class="container-scan" id="scanContent">
    @php $r = $results; @endphp

    {{-- STATUS BANNER --}}
    <div class="status-banner {{ $r['status'] === 'SAFE' ? 'safe' : 'error' }}">
        <div>
            <div class="status-text">
                @if($r['status'] === 'SAFE')
                    <i class="bi bi-check-circle-fill"></i> SAFE — Tidak Ada Error
                @else
                    <i class="bi bi-exclamation-triangle-fill"></i> {{ $r['total_errors'] }} ERROR TERDETEKSI
                @endif
            </div>
        </div>
        <div class="status-meta">
            <div>{{ $r['timestamp'] }}</div>
            <div>Scan selesai dalam {{ $r['elapsed_ms'] }}ms</div>
        </div>
    </div>

    {{-- STAT CARDS --}}
    <div class="stat-grid">
        <div class="stat-card {{ count($r['syntax']['errors']) === 0 ? 'green' : 'red' }}">
            <div class="stat-value">{{ count($r['syntax']['errors']) }}</div>
            <div class="stat-label">Syntax Error</div>
        </div>
        <div class="stat-card blue">
            <div class="stat-value">{{ $r['syntax']['scanned'] }}</div>
            <div class="stat-label">File Di-scan</div>
        </div>
        <div class="stat-card {{ $r['log']['total_error_today'] === 0 ? 'green' : 'red' }}">
            <div class="stat-value">{{ $r['log']['total_error_today'] }}</div>
            <div class="stat-label">Log Error Hari Ini</div>
        </div>
        <div class="stat-card {{ $r['blade']['success'] ? 'green' : 'red' }}">
            <div class="stat-value">
                <i class="bi bi-{{ $r['blade']['success'] ? 'check-lg' : 'x-lg' }}"></i>
            </div>
            <div class="stat-label">Blade Compile</div>
        </div>
        <div class="stat-card {{ $r['route']['success'] ? 'green' : 'red' }}">
            <div class="stat-value">{{ $r['route']['total_routes'] ?? '!' }}</div>
            <div class="stat-label">Routes</div>
        </div>
        <div class="stat-card purple">
            <div class="stat-value">{{ count($r['model']['models']) }}</div>
            <div class="stat-label">Models</div>
        </div>
    </div>

    {{-- SYNTAX ERRORS --}}
    <div class="panel">
        <div class="panel-header">
            <span><i class="bi bi-code-slash"></i> PHP Syntax Scan</span>
            <span class="badge-count">{{ $r['syntax']['scanned'] }} file</span>
        </div>
        <div class="panel-body">
            @if(empty($r['syntax']['errors']))
                <div class="empty-state">
                    <i class="bi bi-check-circle text-success"></i>
                    Tidak ada syntax error. Semua {{ $r['syntax']['scanned'] }} file bersih.
                </div>
            @else
                <div class="scrollable">
                    <table class="scan-table">
                        <thead>
                            <tr>
                                <th style="width:30%">File</th>
                                <th>Error</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($r['syntax']['errors'] as $err)
                                <tr>
                                    <td class="mono">{{ $err['file'] }}</td>
                                    <td>
                                        <div>{{ $err['message'] }}</div>
                                        @if($err['suggestion'])
                                            <div class="suggestion">{{ $err['suggestion'] }}</div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    {{-- BLADE COMPILE --}}
    <div class="panel">
        <div class="panel-header">
            <span><i class="bi bi-filetype-html"></i> Blade Compile Check</span>
            <span class="badge-count">{{ $r['blade']['success'] ? 'OK' : 'GAGAL' }}</span>
        </div>
        <div class="panel-body padded">
            @if($r['blade']['success'])
                <span class="badge-ok"><i class="bi bi-check-circle"></i> {{ $r['blade']['message'] }}</span>
            @else
                <div>
                    <span class="badge-err"><i class="bi bi-x-circle"></i> {{ $r['blade']['message'] }}</span>
                    @if(!empty($r['blade']['file']))
                        <div class="mono mt-2">{{ $r['blade']['file'] }}:{{ $r['blade']['line'] }}</div>
                    @endif
                    @if(!empty($r['blade']['suggestion']))
                        <div class="suggestion">{{ $r['blade']['suggestion'] }}</div>
                    @endif
                </div>
            @endif
        </div>
    </div>

    {{-- ROUTE CHECK --}}
    <div class="panel">
        <div class="panel-header">
            <span><i class="bi bi-signpost-split"></i> Route Check</span>
            <span class="badge-count">{{ $r['route']['total_routes'] ?? 0 }} routes</span>
        </div>
        <div class="panel-body padded">
            @if($r['route']['success'])
                <span class="badge-ok"><i class="bi bi-check-circle"></i> {{ $r['route']['message'] }}</span>
            @else
                <span class="badge-err"><i class="bi bi-x-circle"></i> {{ $r['route']['message'] }}</span>
            @endif
        </div>
    </div>

    {{-- LOG ERRORS --}}
    <div class="panel">
        <div class="panel-header">
            <span><i class="bi bi-journal-text"></i> Laravel Log</span>
            <span class="badge-count">{{ $r['log']['file_size'] }} — {{ count($r['log']['entries']) }} entri terbaru</span>
        </div>
        <div class="panel-body">
            @if(empty($r['log']['entries']))
                <div class="empty-state">
                    <i class="bi bi-journal-check"></i>
                    Log bersih. Tidak ada entri error.
                </div>
            @else
                <div class="scrollable">
                    <table class="scan-table">
                        <thead>
                            <tr>
                                <th style="width:140px">Waktu</th>
                                <th style="width:80px">Level</th>
                                <th>Pesan</th>
                                <th style="width:180px">File</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($r['log']['entries'] as $entry)
                                <tr>
                                    <td class="mono">{{ $entry['date'] }}<br>{{ $entry['time'] }}</td>
                                    <td>
                                        <span class="badge-level {{ strtolower($entry['level']) }}">
                                            {{ $entry['level'] }}
                                        </span>
                                    </td>
                                    <td>
                                        <div style="word-break:break-all;">{{ $entry['message'] }}</div>
                                        @if($entry['suggestion'])
                                            <div class="suggestion">{{ $entry['suggestion'] }}</div>
                                        @endif
                                    </td>
                                    <td class="mono">
                                        @if($entry['file'])
                                            {{ $entry['file'] }}<br>Line {{ $entry['line'] }}
                                        @else
                                            <span style="color:var(--text-secondary)">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    {{-- MODEL CHECK --}}
    <div class="panel">
        <div class="panel-header">
            <span><i class="bi bi-database"></i> Model Check</span>
            <span class="badge-count">{{ count($r['model']['models']) }} model</span>
        </div>
        <div class="panel-body">
            @if(empty($r['model']['models']))
                <div class="empty-state">
                    <i class="bi bi-database-slash"></i>
                    Tidak ada model ditemukan.
                </div>
            @else
                <div class="scrollable">
                    <table class="scan-table">
                        <thead>
                            <tr>
                                <th>Model</th>
                                <th>Tabel</th>
                                <th>Fillable</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($r['model']['models'] as $model)
                                <tr>
                                    <td class="mono">{{ class_basename($model['class']) }}</td>
                                    <td class="mono">{{ $model['table'] ?? '—' }}</td>
                                    <td>{{ $model['fillable'] ?? '—' }}</td>
                                    <td>
                                        @if($model['status'] === 'OK')
                                            <span class="badge-ok"><i class="bi bi-check"></i> OK</span>
                                        @else
                                            <span class="badge-err"><i class="bi bi-x"></i> ERROR</span>
                                            <div class="suggestion mt-1">{{ $model['message'] ?? '' }}</div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    {{-- ENVIRONMENT --}}
    <div class="panel">
        <div class="panel-header">
            <span><i class="bi bi-gear"></i> Environment</span>
        </div>
        <div class="panel-body padded">
            <div class="env-grid">
                <div>
                    <div class="env-item">
                        <span class="env-key">PHP</span>
                        <span class="env-val">{{ $r['environment']['php_version'] }}</span>
                    </div>
                    <div class="env-item">
                        <span class="env-key">Laravel</span>
                        <span class="env-val">{{ $r['environment']['laravel_version'] }}</span>
                    </div>
                    <div class="env-item">
                        <span class="env-key">APP_ENV</span>
                        <span class="env-val">{{ $r['environment']['app_env'] }}</span>
                    </div>
                    <div class="env-item">
                        <span class="env-key">APP_DEBUG</span>
                        <span class="env-val">{{ $r['environment']['app_debug'] ? 'TRUE' : 'FALSE' }}</span>
                    </div>
                </div>
                <div>
                    <div class="env-item">
                        <span class="env-key">Database</span>
                        <span class="env-val">{{ $r['environment']['db_connection'] }}</span>
                    </div>
                    <div class="env-item">
                        <span class="env-key">Cache</span>
                        <span class="env-val">{{ $r['environment']['cache_driver'] }}</span>
                    </div>
                    <div class="env-item">
                        <span class="env-key">Session</span>
                        <span class="env-val">{{ $r['environment']['session_driver'] }}</span>
                    </div>
                    <div class="env-item">
                        <span class="env-key">Disk Free</span>
                        <span class="env-val">{{ $r['environment']['disk_free'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- FOOTER --}}
    <div class="text-center py-4" style="color:var(--text-secondary); font-size:0.8rem;">
        Dev System Scan v1.0 — {{ config('app.name') }} — Laravel {{ app()->version() }}
        <br>
        <code style="color:var(--accent-purple)">php artisan dev:scan-all</code> untuk scan via terminal
    </div>
</div>

<script>
document.getElementById('btnRefresh').addEventListener('click', async function() {
    const btn = this;
    btn.innerHTML = '<span class="spinner"></span> Scanning...';
    btn.disabled = true;

    try {
        const res = await fetch('{{ url("/dev/system-scan/refresh") }}', {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        });
        // Reload page to show fresh data
        window.location.reload();
    } catch (err) {
        alert('Scan gagal: ' + err.message);
        btn.innerHTML = '<i class="bi bi-arrow-clockwise"></i> Refresh Scan';
        btn.disabled = false;
    }
});

document.getElementById('btnClearLog').addEventListener('click', async function() {
    if (!confirm('Yakin ingin menghapus semua isi laravel.log?')) return;

    const btn = this;
    btn.innerHTML = '<span class="spinner"></span> Clearing...';
    btn.disabled = true;

    try {
        const res = await fetch('{{ url("/dev/system-scan/clear-log") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        });
        const data = await res.json();
        if (data.success) {
            alert(data.pesan);
            window.location.reload();
        }
    } catch (err) {
        alert('Gagal clear log: ' + err.message);
    } finally {
        btn.innerHTML = '<i class="bi bi-trash3"></i> Clear Log';
        btn.disabled = false;
    }
});
</script>

</body>
</html>
