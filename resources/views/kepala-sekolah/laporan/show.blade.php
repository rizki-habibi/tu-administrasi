@extends('kepala-sekolah.tata-letak.app')
@section('judul', 'Detail Laporan')

@section('konten')
<div class="mb-4">
    <a href="{{ route('kepala-sekolah.laporan.index') }}" class="text-decoration-none text-warning" style="font-size:.85rem;"><i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar</a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0" style="font-size:.9rem;"><i class="bi bi-file-text text-warning me-2"></i>Detail Laporan</h6>
                <span class="badge bg-{{ $report->status_badge }} bg-opacity-10 text-{{ $report->status_badge }}">{{ ucfirst($report->status) }}</span>
            </div>
            <div class="card-body">
                <h5 class="fw-bold mb-3">{{ $report->judul }}</h5>
                <div class="row g-3 mb-3" style="font-size:.85rem;">
                    <div class="col-md-4"><strong class="text-muted d-block">Kategori</strong><span class="badge bg-warning bg-opacity-10 text-warning">{{ $report->category_label }}</span></div>
                    <div class="col-md-4"><strong class="text-muted d-block">Prioritas</strong>
                        @php $prBadge = match($report->prioritas) { 'high' => 'danger', 'medium' => 'warning', default => 'secondary' }; @endphp
                        <span class="badge bg-{{ $prBadge }} bg-opacity-10 text-{{ $prBadge }}">{{ ucfirst($report->prioritas ?? '-') }}</span>
                    </div>
                    <div class="col-md-4"><strong class="text-muted d-block">Tanggal</strong>{{ $report->created_at->translatedFormat('d F Y H:i') }}</div>
                </div>
                <div style="font-size:.85rem;">
                    <strong class="text-muted d-block mb-1">Deskripsi</strong>
                    <div class="p-3 rounded-3" style="background:#faf5f0;">{!! nl2br(e($report->deskripsi ?? '-')) !!}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="fw-bold mb-0" style="font-size:.9rem;">Informasi</h6>
            </div>
            <div class="card-body" style="font-size:.85rem;">
                <div class="mb-3">
                    <strong class="text-muted d-block">Pembuat</strong>
                    <div class="d-flex align-items-center gap-2 mt-1">
                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold" style="width:28px;height:28px;font-size:.65rem;background:linear-gradient(135deg,#d97706,#ea580c);flex-shrink:0;">
                            {{ strtoupper(substr($report->user->nama ?? '-', 0, 2)) }}
                        </div>
                        <div>
                            <div class="fw-semibold">{{ $report->user->nama ?? '-' }}</div>
                            <small class="text-muted">{{ $report->user->role_label ?? '-' }}</small>
                        </div>
                    </div>
                </div>
                <div><strong class="text-muted d-block">Terakhir Diperbarui</strong>{{ $report->updated_at->translatedFormat('d F Y H:i') }}</div>
            </div>
        </div>

        @if($report->lampiran)
        <div class="card">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="fw-bold mb-0" style="font-size:.9rem;">Lampiran</h6>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center gap-2 p-2 rounded-3" style="background:#fef3c7;">
                    <i class="bi bi-file-earmark-text" style="font-size:1.5rem;color:#d97706;"></i>
                    <div class="flex-grow-1">
                        <div class="fw-semibold" style="font-size:.82rem;">Dokumen Lampiran</div>
                    </div>
                    <a href="{{ asset('storage/' . $report->lampiran) }}" target="_blank" class="btn btn-sm btn-outline-warning"><i class="bi bi-download"></i></a>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
