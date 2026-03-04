@extends('staf.tata-letak.app')
@section('judul', 'Notifikasi')

@section('konten')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-bell"></i> Notifikasi</h4>
    @if($notifications->where('sudah_dibaca', false)->count() > 0)
    <form action="{{ route('staf.notifikasi.baca-semua') }}" method="POST">
        @csrf
        <button class="btn btn-outline-primary"><i class="bi bi-check-all"></i> Tandai Semua Dibaca</button>
    </form>
    @endif
</div>

<div class="card border-0 shadow-sm">
    <div class="list-group list-group-flush">
        @forelse($notifications as $notif)
        <div class="list-group-item {{ !$notif->sudah_dibaca ? 'bg-light' : '' }}">
            <div class="d-flex justify-content-between align-items-start">
                <div class="flex-grow-1">
                    <div class="d-flex align-items-center mb-1">
                        @if(!$notif->sudah_dibaca) <span class="badge bg-primary me-2">Baru</span> @endif
                        @php
                            $icons = ['kehadiran'=>'bi-clock','izin'=>'bi-calendar-x','event'=>'bi-calendar-event','laporan'=>'bi-file-text','sistem'=>'bi-gear','pengumuman'=>'bi-megaphone'];
                            $colors = ['kehadiran'=>'text-success','izin'=>'text-warning','event'=>'text-info','laporan'=>'text-primary','sistem'=>'text-secondary','pengumuman'=>'text-danger'];
                        @endphp
                        <i class="bi {{ $icons[$notif->jenis] ?? 'bi-bell' }} {{ $colors[$notif->jenis] ?? '' }} me-2"></i>
                        <strong>{{ $notif->judul }}</strong>
                    </div>
                    <p class="mb-1 text-muted">{{ $notif->pesan }}</p>
                    <small class="text-muted">{{ $notif->created_at->diffForHumans() }}</small>
                </div>
                <div class="ms-3">
                    @if(!$notif->sudah_dibaca)
                    <form action="{{ route('staf.notifikasi.baca', $notif) }}" method="POST" class="d-inline">
                        @csrf @method('PATCH')
                        <button class="btn btn-sm btn-outline-success" title="Tandai Dibaca"><i class="bi bi-check"></i></button>
                    </form>
                    @endif
                    @if($notif->tautan)
                        <a href="{{ $notif->tautan }}" class="btn btn-sm btn-outline-info" title="Lihat"><i class="bi bi-arrow-right"></i></a>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-5 text-muted">
            <i class="bi bi-bell-slash" style="font-size:3rem;"></i>
            <p class="mt-2">Belum ada notifikasi</p>
        </div>
        @endforelse
    </div>
</div>
<div class="mt-3">{{ $notifications->links() }}</div>
@endsection
