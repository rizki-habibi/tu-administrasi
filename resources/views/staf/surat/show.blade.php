@extends('staf.tata-letak.app')
@section('judul', 'Detail Surat - ' . $surat->nomor_surat)

@section('konten')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
        <h5 class="fw-bold mb-1"><i class="bi bi-envelope-paper text-primary me-2"></i>Detail Surat</h5>
        <p class="text-muted mb-0" style="font-size:.82rem;">{{ $surat->nomor_surat }}</p>
    </div>
    <a href="{{ route('staf.surat.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-start gap-3 mb-4">
                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:50px;height:50px;background:{{ $surat->jenis == 'masuk' ? 'rgba(16,185,129,.1)' : 'rgba(99,102,241,.1)' }}">
                        <i class="bi bi-envelope-{{ $surat->jenis == 'masuk' ? 'arrow-down text-success' : 'arrow-up text-primary' }}" style="font-size:1.3rem;"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">{{ $surat->perihal }}</h5>
                        <div class="d-flex flex-wrap gap-2">
                            {!! $surat->status_badge !!}
                            {!! $surat->sifat_badge !!}
                            <span class="badge bg-light text-dark">{{ ucfirst($surat->kategori) }}</span>
                        </div>
                    </div>
                </div>

                <div class="border rounded p-4 mb-4" style="background:#fafbfc;">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <small class="text-muted d-block">Nomor Surat</small>
                            <code class="text-primary fw-bold">{{ $surat->nomor_surat }}</code>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Tanggal Surat</small>
                            <strong>{{ $surat->tanggal_surat->translatedFormat('d F Y') }}</strong>
                        </div>
                        @if($surat->jenis == 'masuk')
                        <div class="col-md-6">
                            <small class="text-muted d-block">Asal</small>
                            <strong>{{ $surat->asal ?? '-' }}</strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Tanggal Diterima</small>
                            <strong>{{ $surat->tanggal_terima ? $surat->tanggal_terima->translatedFormat('d F Y') : '-' }}</strong>
                        </div>
                        @else
                        <div class="col-md-12">
                            <small class="text-muted d-block">Tujuan</small>
                            <strong>{{ $surat->tujuan ?? '-' }}</strong>
                        </div>
                        @endif
                    </div>
                </div>

                @if($surat->isi)
                <div class="mb-4">
                    <h6 class="fw-bold mb-2"><i class="bi bi-text-paragraph me-1"></i>Isi Surat</h6>
                    <div class="border rounded p-3" style="white-space:pre-wrap;line-height:1.8;">{{ $surat->isi }}</div>
                </div>
                @endif

                @if($surat->catatan)
                <div class="alert alert-info border-0">
                    <h6 class="fw-bold mb-1"><i class="bi bi-sticky me-1"></i>Catatan</h6>
                    <p class="mb-0">{{ $surat->catatan }}</p>
                </div>
                @endif

                @if($surat->path_file)
                <div class="border rounded p-3 d-flex align-items-center gap-3">
                    <div class="rounded d-flex align-items-center justify-content-center" style="width:45px;height:45px;background:rgba(239,68,68,.1);">
                        <i class="bi bi-file-earmark text-danger" style="font-size:1.2rem;"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-semibold" style="font-size:.85rem;">{{ $surat->nama_file }}</div>
                        <small class="text-muted">Lampiran</small>
                    </div>
                    <a href="{{ asset('storage/' . $surat->path_file) }}" class="btn btn-sm btn-outline-primary" target="_blank"><i class="bi bi-download me-1"></i>Unduh</a>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 pt-3">
                <h6 class="mb-0 fw-bold"><i class="bi bi-info-circle text-info me-2"></i>Info</h6>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between py-3">
                        <span class="text-muted">Dibuat oleh</span>
                        <span class="fw-semibold">{{ $surat->creator->nama ?? '-' }}</span>
                    </li>
                    @if($surat->approver)
                    <li class="list-group-item d-flex justify-content-between py-3">
                        <span class="text-muted">Disetujui</span>
                        <span class="fw-semibold">{{ $surat->approver->nama }}</span>
                    </li>
                    @endif
                    <li class="list-group-item d-flex justify-content-between py-3">
                        <span class="text-muted">Tanggal Input</span>
                        <span class="fw-semibold">{{ $surat->created_at->translatedFormat('d M Y H:i') }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
