@extends('kepala-sekolah.tata-letak.app')
@section('judul', 'Notifikasi')

@section('konten')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-1">Notifikasi</h5>
        <p class="text-muted mb-0" style="font-size:.85rem;">Seluruh notifikasi Anda</p>
    </div>
    @if($notifications->where('sudah_dibaca', false)->count() > 0)
    <form action="{{ route('kepala-sekolah.notifikasi.baca-semua') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-sm btn-outline-warning"><i class="bi bi-check-all me-1"></i>Tandai Semua Dibaca</button>
    </form>
    @endif
</div>

<div class="card">
    <div class="card-body p-0">
        @forelse($notifications as $notif)
        <div class="d-flex align-items-start gap-3 px-4 py-3 border-bottom {{ !$notif->sudah_dibaca ? 'bg-warning bg-opacity-10' : '' }}">
            <div class="flex-shrink-0 mt-1">
                @php
                    $iconMap = [
                        'kehadiran' => ['bi-calendar-check', '#10b981'],
                        'izin' => ['bi-calendar2-x', '#3b82f6'],
                        'event' => ['bi-calendar-event', '#8b5cf6'],
                        'laporan' => ['bi-file-text', '#f59e0b'],
                        'sistem' => ['bi-gear', '#ef4444'],
                        'pengumuman' => ['bi-megaphone', '#1c1917'],
                    ];
                    $icon = $iconMap[$notif->jenis] ?? ['bi-bell', '#6b7280'];
                @endphp
                <div class="d-flex align-items-center justify-content-center rounded-3" style="width:38px;height:38px;background:{{ $icon[1] }}15;">
                    <i class="bi {{ $icon[0] }}" style="font-size:1.1rem;color:{{ $icon[1] }};"></i>
                </div>
            </div>
            <div class="flex-grow-1 overflow-hidden">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="fw-semibold" style="font-size:.85rem;">{{ $notif->judul }}</div>
                        <p class="text-muted mb-1" style="font-size:.82rem;">{{ $notif->pesan }}</p>
                    </div>
                    @if(!$notif->sudah_dibaca)
                        <span class="badge bg-warning rounded-pill" style="font-size:.6rem;">Baru</span>
                    @endif
                </div>
                <div class="d-flex align-items-center gap-3" style="font-size:.75rem;">
                    <span class="text-muted"><i class="bi bi-clock me-1"></i>{{ $notif->created_at->diffForHumans() }}</span>
                    <span class="badge bg-{{ $notif->type_badge }} bg-opacity-10 text-{{ $notif->type_badge }}">{{ ucfirst($notif->jenis) }}</span>
                    @if($notif->tautan)
                        <a href="{{ $notif->tautan }}" class="text-warning text-decoration-none"><i class="bi bi-box-arrow-up-right me-1"></i>Lihat</a>
                    @endif
                    @if(!$notif->sudah_dibaca)
                        <form action="{{ route('kepala-sekolah.notifikasi.baca', $notif) }}" method="POST" class="d-inline">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-link p-0 text-muted" style="font-size:.75rem;">Tandai Dibaca</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-5 text-muted">
            <i class="bi bi-bell-slash" style="font-size:2.5rem;"></i>
            <p class="mt-2 mb-0" style="font-size:.85rem;">Tidak ada notifikasi</p>
        </div>
        @endforelse
    </div>
</div>

@if($notifications instanceof \Illuminate\Pagination\LengthAwarePaginator)
<div class="mt-3 d-flex justify-content-center">{{ $notifications->withQueryString()->links() }}</div>
@endif
@endsection
