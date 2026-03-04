@extends('kepala-sekolah.tata-letak.app')
@section('judul', 'Evaluasi PKG')

@section('konten')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-1">Penilaian Kinerja Guru (PKG)</h5>
        <p class="text-muted mb-0" style="font-size:.85rem;">Data evaluasi PKG seluruh staff</p>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body py-2">
        <form class="d-flex gap-2 align-items-center flex-wrap" method="GET">
            <input type="text" name="search" class="form-control form-control-sm" style="width:200px;" placeholder="Cari pegawai..." value="{{ request('search') }}">
            <select name="status" class="form-select form-select-sm" style="width:auto;">
                <option value="">Semua Status</option>
                @foreach(['draft','submitted','reviewed','final'] as $s)
                    <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-sm btn-outline-warning"><i class="bi bi-funnel"></i> Filter</button>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr><th>#</th><th>Pegawai</th><th>Periode</th><th>Jenis</th><th>Nilai</th><th>Predikat</th><th>Status</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                @forelse($evaluations as $i => $eval)
                    <tr>
                        <td>{{ $evaluations instanceof \Illuminate\Pagination\LengthAwarePaginator ? $evaluations->firstItem() + $i : $i + 1 }}</td>
                        <td>
                            <div class="fw-semibold">{{ $eval->user->nama ?? '-' }}</div>
                            <small class="text-muted">{{ $eval->user->role_label ?? '-' }}</small>
                        </td>
                        <td>{{ $eval->periode ?? '-' }}</td>
                        <td><span class="badge bg-warning bg-opacity-10 text-warning">{{ $eval->jenis_label }}</span></td>
                        <td class="fw-bold">{{ $eval->nilai ? number_format($eval->nilai, 2) : '-' }}</td>
                        <td>{{ $eval->predikat ?? '-' }}</td>
                        <td><span class="badge bg-secondary">{{ ucfirst($eval->status ?? '-') }}</span></td>
                        <td>
                            <div class="d-flex gap-1">
                                @if($eval->path_file)
                                    <a href="{{ asset('storage/' . $eval->path_file) }}" target="_blank" class="btn btn-sm btn-outline-secondary" title="Unduh"><i class="bi bi-download"></i></a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center py-4 text-muted">Belum ada data evaluasi PKG</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@if($evaluations instanceof \Illuminate\Pagination\LengthAwarePaginator)
<div class="mt-3 d-flex justify-content-center">{{ $evaluations->withQueryString()->links() }}</div>
@endif
@endsection
