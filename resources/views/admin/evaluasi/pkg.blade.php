@extends('peran.admin.app')
@section('judul', 'PKG / BKD / SKP')

@section('konten')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color:#1e293b;">Penilaian Kinerja Guru (PKG / BKD)</h4>
        <p class="text-muted mb-0" style="font-size:.85rem;">Kelola penilaian kinerja guru dan tenaga kependidikan</p>
    </div>
    <a href="{{ route('admin.evaluasi.pkg.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Tambah Penilaian</a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr><th>#</th><th>Guru / Staff</th><th>Jenis</th><th>Periode</th><th>Nilai</th><th>Predikat</th><th>Tanggal</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse($evaluations as $e)
                    <tr>
                        <td>{{ $loop->iteration + ($evaluations->currentPage()-1)*$evaluations->perPage() }}</td>
                        <td class="fw-semibold">{{ $e->user->nama ?? 'N/A' }}</td>
                        <td><span class="badge bg-primary bg-opacity-10 text-primary">{{ strtoupper($e->jenis) }}</span></td>
                        <td>{{ $e->periode ?? '-' }}</td>
                        <td class="fw-bold">{{ $e->nilai ?? '-' }}</td>
                        <td>
                            @if($e->predikat == 'amat_baik')<span class="badge bg-success">Amat Baik</span>
                            @elseif($e->predikat == 'baik')<span class="badge bg-info">Baik</span>
                            @elseif($e->predikat == 'cukup')<span class="badge bg-warning text-dark">Cukup</span>
                            @else<span class="badge bg-danger">{{ ucwords(str_replace('_', ' ', $e->predikat ?? '-')) }}</span>@endif
                        </td>
                        <td>{{ $e->created_at->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('admin.evaluasi.pkg') }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center py-4 text-muted"><i class="bi bi-inbox" style="font-size:2rem;"></i><br>Belum ada data penilaian</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($evaluations->hasPages())
    <div class="card-footer bg-transparent border-0 d-flex justify-content-center py-3">{{ $evaluations->withQueryString()->links() }}</div>
    @endif
</div>
@endsection
