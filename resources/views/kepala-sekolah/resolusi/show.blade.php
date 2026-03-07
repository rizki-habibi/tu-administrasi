@extends('peran.kepala-sekolah.app')
@section('judul', 'Detail Resolusi')

@section('konten')
<div class="mb-4">
    <a href="{{ route('kepala-sekolah.resolusi.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
    <a href="{{ route('kepala-sekolah.resolusi.edit', $resolusi) }}" class="btn btn-sm btn-outline-warning ms-1"><i class="bi bi-pencil me-1"></i>Edit</a>
</div>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <code class="text-primary d-block mb-1">{{ $resolusi->nomor_resolusi }}</code>
                        <h5 class="fw-bold mb-0">{{ $resolusi->judul }}</h5>
                    </div>
                    @php $sc = ['draft'=>'warning','berlaku'=>'success','dicabut'=>'danger']; @endphp
                    <span class="badge bg-{{ $sc[$resolusi->status] ?? 'secondary' }} fs-6">{{ ucfirst($resolusi->status) }}</span>
                </div>
            </div>
            <div class="card-body">
                <h6 class="fw-bold text-muted mb-2"><i class="bi bi-info-circle me-1"></i>Latar Belakang</h6>
                <p class="bg-light rounded p-3">{{ $resolusi->latar_belakang }}</p>

                <h6 class="fw-bold text-muted mb-2 mt-4"><i class="bi bi-check2-square me-1"></i>Isi Keputusan</h6>
                <div class="bg-light rounded p-3">{!! nl2br(e($resolusi->isi_keputusan)) !!}</div>

                @if($resolusi->tindak_lanjut)
                <h6 class="fw-bold text-muted mb-2 mt-4"><i class="bi bi-arrow-right-circle me-1"></i>Tindak Lanjut</h6>
                <p class="bg-light rounded p-3">{{ $resolusi->tindak_lanjut }}</p>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Info Resolusi</h6>
                <div class="mb-2"><small class="text-muted">Kategori</small><br><span class="badge bg-light text-dark">{{ ucfirst($resolusi->kategori) }}</span></div>
                <div class="mb-2"><small class="text-muted">Tanggal Berlaku</small><br>{{ $resolusi->tanggal_berlaku->format('d/m/Y') }}</div>
                <div class="mb-2"><small class="text-muted">Tanggal Berakhir</small><br>{{ $resolusi->tanggal_berakhir?->format('d/m/Y') ?? 'Tidak terbatas' }}</div>
                <div class="mb-2"><small class="text-muted">Dibuat Oleh</small><br>{{ $resolusi->pembuat->nama ?? '-' }}</div>
                <div><small class="text-muted">Dibuat Pada</small><br>{{ $resolusi->created_at->format('d/m/Y H:i') }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
