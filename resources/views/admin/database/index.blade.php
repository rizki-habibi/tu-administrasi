@extends('peran.admin.app')
@section('judul', 'Database Inspector')

@section('konten')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
        <h5 class="fw-bold mb-1"><i class="bi bi-database-fill-gear text-primary me-2"></i>Database Inspector</h5>
        <p class="text-muted mb-0" style="font-size:.82rem;">Pantau semua tabel database <code>{{ $dbName }}</code> — {{ count($tables) }} tabel</p>
    </div>
    <div class="d-flex gap-2">
        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2">
            <i class="bi bi-hdd-stack me-1"></i>{{ number_format($totalSize, 0) }} KB Total
        </span>
        <span class="badge bg-success bg-opacity-10 text-success px-3 py-2">
            <i class="bi bi-list-ol me-1"></i>{{ number_format($totalRows) }} Total Record
        </span>
    </div>
</div>

{{-- Ringkasan Cepat --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center py-3">
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width:48px;height:48px;background:linear-gradient(135deg,#6366f1,#818cf8);">
                    <i class="bi bi-table text-white"></i>
                </div>
                <h4 class="fw-bold mb-0">{{ count($tables) }}</h4>
                <small class="text-muted">Tabel</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center py-3">
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width:48px;height:48px;background:linear-gradient(135deg,#10b981,#34d399);">
                    <i class="bi bi-list-ol text-white"></i>
                </div>
                <h4 class="fw-bold mb-0">{{ number_format($totalRows) }}</h4>
                <small class="text-muted">Total Record</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center py-3">
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width:48px;height:48px;background:linear-gradient(135deg,#f59e0b,#fbbf24);">
                    <i class="bi bi-hdd-fill text-white"></i>
                </div>
                <h4 class="fw-bold mb-0">{{ number_format($totalSize / 1024, 2) }} MB</h4>
                <small class="text-muted">Ukuran DB</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center py-3">
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width:48px;height:48px;background:linear-gradient(135deg,#ef4444,#f87171);">
                    <i class="bi bi-shield-check text-white"></i>
                </div>
                <h4 class="fw-bold mb-0 text-success"><i class="bi bi-check-circle"></i></h4>
                <small class="text-muted">Status OK</small>
            </div>
        </div>
    </div>
</div>

{{-- Pencarian --}}
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body py-3">
        <div class="input-group">
            <span class="input-group-text border-0 bg-light"><i class="bi bi-search"></i></span>
            <input type="text" id="searchTable" class="form-control form-control-sm border-0 bg-light" placeholder="Cari tabel...">
        </div>
    </div>
</div>

{{-- Daftar Tabel --}}
<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" style="font-size:.85rem;" id="tableList">
            <thead class="table-light">
                <tr>
                    <th style="width:40px">No</th>
                    <th>Nama Tabel</th>
                    <th class="text-end">Record</th>
                    <th class="text-end">Ukuran Data</th>
                    <th class="text-end">Ukuran Index</th>
                    <th class="text-end">Total</th>
                    <th>Engine</th>
                    <th>Terakhir Update</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tables as $i => $t)
                <tr class="table-row">
                    <td class="text-muted">{{ $i + 1 }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-table text-primary"></i>
                            <strong>{{ $t->nama }}</strong>
                        </div>
                    </td>
                    <td class="text-end">
                        <span class="badge {{ $t->jumlah_baris > 100 ? 'bg-primary' : ($t->jumlah_baris > 0 ? 'bg-info' : 'bg-secondary') }} bg-opacity-10 text-{{ $t->jumlah_baris > 100 ? 'primary' : ($t->jumlah_baris > 0 ? 'info' : 'secondary') }}">
                            {{ number_format($t->jumlah_baris ?? 0) }}
                        </span>
                    </td>
                    <td class="text-end">{{ $t->ukuran_data_kb }} KB</td>
                    <td class="text-end">{{ $t->ukuran_index_kb }} KB</td>
                    <td class="text-end fw-semibold">{{ $t->total_kb }} KB</td>
                    <td><span class="badge bg-light text-dark">{{ $t->engine }}</span></td>
                    <td class="text-muted" style="font-size:.8rem;">{{ $t->diperbarui ?? '-' }}</td>
                    <td class="text-center">
                        <a href="{{ route('admin.database.show', $t->nama) }}" class="btn btn-sm btn-outline-primary" title="Lihat Detail">
                            <i class="bi bi-eye"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('searchTable').addEventListener('input', function() {
    const q = this.value.toLowerCase();
    document.querySelectorAll('#tableList .table-row').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
});
</script>
@endpush
