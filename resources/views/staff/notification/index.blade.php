@extends('layouts.staff')
@section('title', 'Notifikasi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-bell"></i> Notifikasi</h4>
    @if($notifications->where('is_read', false)->count() > 0)
    <form action="{{ route('staff.notification.read-all') }}" method="POST">
        @csrf @method('PATCH')
        <button class="btn btn-outline-primary"><i class="bi bi-check-all"></i> Tandai Semua Dibaca</button>
    </form>
    @endif
</div>

<div class="card border-0 shadow-sm">
    <div class="list-group list-group-flush">
        @forelse($notifications as $notif)
        <div class="list-group-item {{ !$notif->is_read ? 'bg-light' : '' }}">
            <div class="d-flex justify-content-between align-items-start">
                <div class="flex-grow-1">
                    <div class="d-flex align-items-center mb-1">
                        @if(!$notif->is_read) <span class="badge bg-primary me-2">Baru</span> @endif
                        @php
                            $icons = ['kehadiran'=>'bi-clock','izin'=>'bi-calendar-x','event'=>'bi-calendar-event','laporan'=>'bi-file-text','sistem'=>'bi-gear','pengumuman'=>'bi-megaphone'];
                            $colors = ['kehadiran'=>'text-success','izin'=>'text-warning','event'=>'text-info','laporan'=>'text-primary','sistem'=>'text-secondary','pengumuman'=>'text-danger'];
                        @endphp
                        <i class="bi {{ $icons[$notif->type] ?? 'bi-bell' }} {{ $colors[$notif->type] ?? '' }} me-2"></i>
                        <strong>{{ $notif->title }}</strong>
                    </div>
                    <p class="mb-1 text-muted">{{ $notif->message }}</p>
                    <small class="text-muted">{{ $notif->created_at->diffForHumans() }}</small>
                </div>
                <div class="ms-3">
                    @if(!$notif->is_read)
                    <form action="{{ route('staff.notification.read', $notif) }}" method="POST" class="d-inline">
                        @csrf @method('PATCH')
                        <button class="btn btn-sm btn-outline-success" title="Tandai Dibaca"><i class="bi bi-check"></i></button>
                    </form>
                    @endif
                    @if($notif->link)
                        <a href="{{ $notif->link }}" class="btn btn-sm btn-outline-info" title="Lihat"><i class="bi bi-arrow-right"></i></a>
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
