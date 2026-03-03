@extends('layouts.admin')
@section('title', 'Metode STAR')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color:#1e293b;">Analisis Metode STAR</h4>
        <p class="text-muted mb-0" style="font-size:.85rem;">Situation - Task - Action - Result</p>
    </div>
    <a href="{{ route('admin.evaluasi.star.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Tambah Analisis</a>
</div>

<!-- STAR Explanation Cards -->
<div class="row g-3 mb-4">
    @php $starCards = [
        ['S - Situation', 'Menjelaskan situasi atau konteks yang dihadapi', 'bi-geo-alt', '#6366f1'],
        ['T - Task', 'Tugas atau tantangan yang harus diselesaikan', 'bi-list-task', '#f59e0b'],
        ['A - Action', 'Langkah-langkah atau tindakan yang diambil', 'bi-lightning', '#10b981'],
        ['R - Result', 'Hasil atau dampak dari tindakan yang diambil', 'bi-trophy', '#ec4899'],
    ]; @endphp
    @foreach($starCards as $sc)
    <div class="col-md-3">
        <div class="card text-center py-3 px-2">
            <i class="bi {{ $sc[2] }}" style="font-size:1.8rem;color:{{ $sc[3] }}"></i>
            <h6 class="fw-bold mt-2 mb-1" style="font-size:.85rem;">{{ $sc[0] }}</h6>
            <small class="text-muted" style="font-size:.72rem;">{{ $sc[1] }}</small>
        </div>
    </div>
    @endforeach
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead><tr><th>#</th><th>Judul</th><th>Guru</th><th>Periode</th><th>Tanggal</th><th>Aksi</th></tr></thead>
                <tbody>
                    @forelse($analyses as $a)
                    <tr>
                        <td>{{ $loop->iteration + ($analyses->currentPage()-1)*$analyses->perPage() }}</td>
                        <td class="fw-semibold">{{ $a->title }}</td>
                        <td>{{ $a->user->name ?? 'N/A' }}</td>
                        <td>{{ $a->period ?? '-' }}</td>
                        <td>{{ $a->created_at->format('d/m/Y') }}</td>
                        <td><a href="{{ route('admin.evaluasi.star') }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a></td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-4 text-muted"><i class="bi bi-inbox" style="font-size:2rem;"></i><br>Belum ada analisis STAR</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($analyses->hasPages())
    <div class="card-footer bg-transparent border-0 d-flex justify-content-center py-3">{{ $analyses->withQueryString()->links() }}</div>
    @endif
</div>
@endsection
