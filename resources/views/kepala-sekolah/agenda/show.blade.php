@extends('kepala-sekolah.tata-letak.app')
@section('judul', 'Detail Kegiatan')

@section('konten')
<div class="mb-4">
    <a href="{{ route('kepala-sekolah.agenda.index') }}" class="text-decoration-none text-warning" style="font-size:.85rem;"><i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar</a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0" style="font-size:.9rem;"><i class="bi bi-calendar-event text-warning me-2"></i>Detail Kegiatan</h6>
                <span class="badge bg-{{ $event->status_badge }} bg-opacity-10 text-{{ $event->status_badge }}">{{ ucfirst($event->status) }}</span>
            </div>
            <div class="card-body">
                <h5 class="fw-bold mb-3">{{ $event->judul }}</h5>
                <div class="row g-3 mb-3" style="font-size:.85rem;">
                    <div class="col-md-6"><strong class="text-muted d-block">Tipe</strong><span class="badge bg-warning bg-opacity-10 text-warning">{{ $event->type_label }}</span></div>
                    <div class="col-md-6"><strong class="text-muted d-block">Status</strong><span class="badge bg-{{ $event->status_badge }} bg-opacity-10 text-{{ $event->status_badge }}">{{ ucfirst($event->status) }}</span></div>
                    <div class="col-md-6"><strong class="text-muted d-block">Tanggal</strong>{{ $event->tanggal_acara->translatedFormat('l, d F Y') }}</div>
                    <div class="col-md-6"><strong class="text-muted d-block">Waktu</strong>{{ $event->waktu_mulai ?? '-' }} {{ $event->waktu_selesai ? '- ' . $event->waktu_selesai : '' }}</div>
                    <div class="col-md-12"><strong class="text-muted d-block">Lokasi</strong>{{ $event->lokasi ?? '-' }}</div>
                </div>
                <div style="font-size:.85rem;">
                    <strong class="text-muted d-block mb-1">Deskripsi</strong>
                    <div class="p-3 rounded-3" style="background:#faf5f0;">{!! nl2br(e($event->deskripsi ?? 'Tidak ada deskripsi')) !!}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="fw-bold mb-0" style="font-size:.9rem;">Informasi</h6>
            </div>
            <div class="card-body" style="font-size:.85rem;">
                <div class="mb-3">
                    <strong class="text-muted d-block">Dibuat oleh</strong>
                    <div class="d-flex align-items-center gap-2 mt-1">
                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold" style="width:28px;height:28px;font-size:.65rem;background:linear-gradient(135deg,#d97706,#ea580c);flex-shrink:0;">
                            {{ strtoupper(substr($event->creator->nama ?? '-', 0, 2)) }}
                        </div>
                        <span>{{ $event->creator->nama ?? '-' }}</span>
                    </div>
                </div>
                <div class="mb-3"><strong class="text-muted d-block">Dibuat pada</strong>{{ $event->created_at->translatedFormat('d F Y H:i') }}</div>

                {{-- Countdown / Date info --}}
                @if($event->tanggal_acara->isFuture())
                <div class="p-3 rounded-3 text-center" style="background:#fef3c7;">
                    <i class="bi bi-hourglass-split text-warning" style="font-size:1.5rem;"></i>
                    <div class="fw-bold mt-1" style="color:#d97706;">{{ $event->tanggal_acara->diffForHumans() }}</div>
                    <small class="text-muted">Menuju hari kegiatan</small>
                </div>
                @elseif($event->tanggal_acara->isToday())
                <div class="p-3 rounded-3 text-center" style="background:#d1fae5;">
                    <i class="bi bi-lightning-charge-fill text-success" style="font-size:1.5rem;"></i>
                    <div class="fw-bold mt-1 text-success">Hari Ini!</div>
                </div>
                @else
                <div class="p-3 rounded-3 text-center" style="background:#f3f4f6;">
                    <i class="bi bi-check-circle text-secondary" style="font-size:1.5rem;"></i>
                    <div class="fw-bold mt-1 text-secondary">Sudah Berlalu</div>
                    <small class="text-muted">{{ $event->tanggal_acara->diffForHumans() }}</small>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
