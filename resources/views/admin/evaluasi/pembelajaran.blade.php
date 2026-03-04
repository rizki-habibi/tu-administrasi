@extends('admin.tata-letak.app')
@section('judul', 'Model Pembelajaran')

@section('konten')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color:#1e293b;">Model & Metode Pembelajaran</h4>
        <p class="text-muted mb-0" style="font-size:.85rem;">Model pembelajaran berbasis teknologi & inovasi</p>
    </div>
    <a href="{{ route('admin.evaluasi.pembelajaran.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Tambah Model</a>
</div>

<div class="row g-3">
    @forelse($methods as $m)
    <div class="col-md-6 col-lg-4">
        <div class="card h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:40px;height:40px;background:linear-gradient(135deg,#6366f1,#818cf8);color:#fff;font-size:1rem;flex-shrink:0;">
                        <i class="bi bi-lightbulb"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0">{{ $m->nama_metode }}</h6>
                        <small class="text-muted">{{ ucfirst(str_replace('_', ' ', $m->jenis)) }}</small>
                    </div>
                </div>
                <p class="text-muted mb-2" style="font-size:.82rem;">{{ Str::limit($m->deskripsi, 100) }}</p>
                @if($m->mata_pelajaran)<small class="badge bg-info bg-opacity-10 text-info me-1">{{ $m->mata_pelajaran }}</small>@endif
            </div>
            <div class="card-footer bg-transparent border-top-0 pt-0 px-4 pb-3">
                <small class="text-muted"><i class="bi bi-person me-1"></i>{{ $m->creator->nama ?? '-' }} &bull; {{ $m->created_at->format('d/m/Y') }}</small>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="card">
            <div class="card-body text-center py-5 text-muted">
                <i class="bi bi-inbox" style="font-size:3rem;"></i>
                <p class="mt-2">Belum ada model pembelajaran</p>
            </div>
        </div>
    </div>
    @endforelse
</div>
@if($methods->hasPages())
<div class="d-flex justify-content-center mt-4">{{ $methods->links() }}</div>
@endif
@endsection
