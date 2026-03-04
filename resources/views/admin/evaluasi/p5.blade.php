@extends('peran.admin.app')
@section('judul', 'Asesmen P5')

@section('konten')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color:#1e293b;">Asesmen P5</h4>
        <p class="text-muted mb-0" style="font-size:.85rem;">Projek Penguatan Profil Pelajar Pancasila</p>
    </div>
    <a href="{{ route('admin.evaluasi.p5.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Tambah Asesmen</a>
</div>

<!-- Dimension Stats -->
<div class="row g-3 mb-4">
    @php
    $dimensions = [
        ['Beriman & Bertaqwa', 'bi-moon-stars', '#6366f1'],
        ['Berkebhinekaan Global', 'bi-globe2', '#8b5cf6'],
        ['Bergotong Royong', 'bi-people', '#10b981'],
        ['Mandiri', 'bi-lightning', '#f59e0b'],
        ['Bernalar Kritis', 'bi-lightbulb', '#06b6d4'],
        ['Kreatif', 'bi-palette', '#ec4899'],
    ];
    @endphp
    @foreach($dimensions as $dim)
    <div class="col-6 col-lg-2">
        <div class="card text-center py-3">
            <i class="bi {{ $dim[1] }}" style="font-size:1.5rem;color:{{ $dim[2] }};"></i>
            <small class="fw-semibold mt-1" style="font-size:.72rem;">{{ $dim[0] }}</small>
        </div>
    </div>
    @endforeach
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr><th>#</th><th>Judul Projek</th><th>Dimensi</th><th>Kelas</th><th>Tema</th><th>Tanggal</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse($assessments as $a)
                    <tr>
                        <td>{{ $loop->iteration + ($assessments->currentPage()-1)*$assessments->perPage() }}</td>
                        <td class="fw-semibold">{{ $a->judul_projek }}</td>
                        <td><span class="badge bg-info bg-opacity-10 text-info">{{ ucwords(str_replace('_', ' ', $a->dimensi ?? '-')) }}</span></td>
                        <td>{{ $a->kelas ?? '-' }}</td>
                        <td>{{ $a->tema ?? '-' }}</td>
                        <td>{{ $a->created_at->format('d/m/Y') }}</td>
                        <td><a href="{{ route('admin.evaluasi.p5') }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a></td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-4 text-muted"><i class="bi bi-inbox" style="font-size:2rem;"></i><br>Belum ada asesmen P5</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($assessments->hasPages())
    <div class="card-footer bg-transparent border-0 d-flex justify-content-center py-3">{{ $assessments->withQueryString()->links() }}</div>
    @endif
</div>
@endsection
