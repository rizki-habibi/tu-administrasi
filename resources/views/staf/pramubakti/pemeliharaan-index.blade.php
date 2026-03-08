@extends('peran.staf.app')
@section('judul', 'Laporan Pemeliharaan')

@section('konten')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-tools"></i> Laporan Pemeliharaan</h4>
    <a href="{{ route('staf.pemeliharaan.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Buat Laporan</a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle"></i> {{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center">
            <div class="card-body">
                <h3 class="text-success mb-1">{{ $totalSelesai }}</h3>
                <small class="text-muted">Selesai</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center">
            <div class="card-body">
                <h3 class="text-warning mb-1">{{ $totalProses }}</h3>
                <small class="text-muted">Dalam Proses</small>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Cari</label>
                <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Judul laporan...">
            </div>
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua</option>
                    <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Diajukan</option>
                    <option value="reviewed" {{ request('status') == 'reviewed' ? 'selected' : '' }}>Ditinjau</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search"></i> Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th width="40">No</th>
                    <th>Judul</th>
                    <th>Prioritas</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th width="140">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($laporan as $i => $l)
                <tr>
                    <td>{{ $laporan->firstItem() + $i }}</td>
                    <td>{{ $l->judul }}</td>
                    <td>
                        @php
                            $pBadge = match($l->prioritas) { 'tinggi' => 'danger', 'sedang' => 'warning', default => 'secondary' };
                        @endphp
                        <span class="badge bg-{{ $pBadge }}">{{ ucfirst($l->prioritas) }}</span>
                    </td>
                    <td>
                        <span class="badge bg-{{ $l->status_badge }}">
                            {{ match($l->status) { 'submitted' => 'Diajukan', 'reviewed' => 'Ditinjau', 'completed' => 'Selesai', default => ucfirst($l->status) } }}
                        </span>
                    </td>
                    <td>{{ $l->created_at->format('d/m/Y') }}</td>
                    <td>
                        <a href="{{ route('staf.pemeliharaan.show', $l) }}" class="btn btn-sm btn-outline-info" title="Lihat"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('staf.pemeliharaan.edit', $l) }}" class="btn btn-sm btn-outline-warning" title="Edit"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('staf.pemeliharaan.destroy', $l) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus laporan ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-4">Belum ada laporan pemeliharaan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($laporan->hasPages())
        <div class="card-footer">{{ $laporan->links() }}</div>
    @endif
</div>
@endsection
