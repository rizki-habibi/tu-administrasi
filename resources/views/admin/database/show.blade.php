@extends('peran.admin.app')
@section('judul', 'Detail Tabel: ' . $table)

@section('konten')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
        <a href="{{ route('admin.database.index') }}" class="text-primary text-decoration-none" style="font-size:.82rem;">
            <i class="bi bi-arrow-left me-1"></i>Kembali ke Database Inspector
        </a>
        <h5 class="fw-bold mb-1 mt-2"><i class="bi bi-table text-primary me-2"></i>{{ $table }}</h5>
        <p class="text-muted mb-0" style="font-size:.82rem;">
            {{ $rowCount }} record &middot; {{ $tableInfo[0]->total_kb ?? 0 }} KB &middot; {{ $tableInfo[0]->engine ?? 'InnoDB' }}
        </p>
    </div>
</div>

{{-- Struktur Kolom --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white border-0 py-3">
        <h6 class="fw-bold mb-0"><i class="bi bi-columns-gap text-primary me-2"></i>Struktur Kolom ({{ count($columns) }})</h6>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" style="font-size:.85rem;">
            <thead class="table-light">
                <tr>
                    <th style="width:40px">No</th>
                    <th>Kolom</th>
                    <th>Tipe</th>
                    <th class="text-center">Nullable</th>
                    <th>Key</th>
                    <th>Default</th>
                    <th>Extra</th>
                </tr>
            </thead>
            <tbody>
                @foreach($columns as $i => $col)
                <tr>
                    <td class="text-muted">{{ $i + 1 }}</td>
                    <td>
                        <code class="text-primary">{{ $col->nama }}</code>
                        @if($col->kunci === 'PRI') <span class="badge bg-warning text-dark ms-1">PK</span> @endif
                        @if($col->kunci === 'MUL') <span class="badge bg-info text-dark ms-1">FK</span> @endif
                        @if($col->kunci === 'UNI') <span class="badge bg-success ms-1">UQ</span> @endif
                    </td>
                    <td><code class="text-muted">{{ $col->tipe }}</code></td>
                    <td class="text-center">
                        @if($col->nullable === 'YES')
                            <span class="badge bg-secondary bg-opacity-10 text-secondary">NULL</span>
                        @else
                            <span class="badge bg-danger bg-opacity-10 text-danger">NOT NULL</span>
                        @endif
                    </td>
                    <td>{{ $col->kunci ?: '-' }}</td>
                    <td><small class="text-muted">{{ $col->default_val ?? 'NULL' }}</small></td>
                    <td><small class="text-muted">{{ $col->extra ?: '-' }}</small></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Data Terbaru --}}
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 py-3">
        <h6 class="fw-bold mb-0"><i class="bi bi-list-ul text-primary me-2"></i>10 Data Terbaru</h6>
    </div>
    @if($recentRows->count() > 0)
    <div class="table-responsive">
        <table class="table table-hover table-sm mb-0" style="font-size:.78rem;">
            <thead class="table-light">
                <tr>
                    @foreach(array_keys((array) $recentRows->first()) as $col)
                        <th class="text-nowrap">{{ $col }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($recentRows as $row)
                <tr>
                    @foreach((array) $row as $val)
                        <td class="text-truncate" style="max-width:200px;" title="{{ $val }}">{{ Str::limit((string) $val, 50) }}</td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="card-body text-center text-muted py-4">
        <i class="bi bi-inbox" style="font-size:2rem;"></i>
        <p class="mb-0 mt-2">Tabel kosong</p>
    </div>
    @endif
</div>
@endsection
