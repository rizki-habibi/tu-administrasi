@extends('peran.staf.app')
@section('judul', 'Detail Catatan')

@section('konten')
<div class="d-flex align-items-center mb-4 gap-2">
    <a href="{{ route('staf.catatan-harian.index') }}" class="btn btn-light btn-sm"><i class="bi bi-arrow-left"></i></a>
    <div>
        <h5 class="fw-bold mb-0"><i class="bi bi-journal-text text-primary me-2"></i>Catatan {{ $catatan->tanggal->translatedFormat('d F Y') }}</h5>
        <p class="text-muted mb-0" style="font-size:.82rem;">{{ $catatan->tanggal->translatedFormat('l') }}</p>
    </div>
    <div class="ms-auto">
        <a href="{{ route('staf.catatan-harian.edit', $catatan) }}" class="btn btn-warning btn-sm"><i class="bi bi-pencil me-1"></i>Edit</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-2"><i class="bi bi-list-task me-2"></i>Kegiatan</h6>
                <p style="font-size:.9rem; white-space:pre-line;">{{ $catatan->kegiatan }}</p>
            </div>
        </div>

        @if($catatan->hasil)
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-2"><i class="bi bi-trophy me-2 text-success"></i>Hasil / Capaian</h6>
                <p style="font-size:.9rem; white-space:pre-line;">{{ $catatan->hasil }}</p>
            </div>
        </div>
        @endif

        @if($catatan->kendala)
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-2"><i class="bi bi-exclamation-triangle me-2 text-warning"></i>Kendala / Hambatan</h6>
                <p style="font-size:.9rem; white-space:pre-line;">{{ $catatan->kendala }}</p>
            </div>
        </div>
        @endif

        @if($catatan->rencana_besok)
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-2"><i class="bi bi-calendar-check me-2 text-info"></i>Rencana Besok</h6>
                <p style="font-size:.9rem; white-space:pre-line;">{{ $catatan->rencana_besok }}</p>
            </div>
        </div>
        @endif
    </div>
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3">Info</h6>
                <ul class="list-unstyled mb-0" style="font-size:.85rem;">
                    <li class="d-flex justify-content-between py-2 border-bottom"><span class="text-muted">Status</span>
                        @php $st = ['draft' => 'secondary', 'selesai' => 'success']; @endphp
                        <span class="badge bg-{{ $st[$catatan->status] ?? 'secondary' }}">{{ ucfirst($catatan->status) }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom"><span class="text-muted">Tanggal</span><strong>{{ $catatan->tanggal->format('d/m/Y') }}</strong></li>
                    <li class="d-flex justify-content-between py-2 border-bottom"><span class="text-muted">Dibuat</span><strong>{{ $catatan->created_at->diffForHumans() }}</strong></li>
                    <li class="d-flex justify-content-between py-2"><span class="text-muted">Diupdate</span><strong>{{ $catatan->updated_at->diffForHumans() }}</strong></li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
