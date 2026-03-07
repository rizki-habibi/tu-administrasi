@extends('peran.magang.app')
@section('judul', 'Notifikasi')

@section('konten')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-bold mb-0">Notifikasi</h5>
    @if($notifications->where('sudah_dibaca', false)->count() > 0)
    <form action="{{ route('magang.notifikasi.baca-semua') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-sm btn-outline-primary"><i class="bi bi-check-all me-1"></i>Tandai Semua Dibaca</button>
    </form>
    @endif
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        @forelse($notifications as $item)
        <div class="d-flex align-items-start px-3 py-3 border-bottom {{ !$item->sudah_dibaca ? 'bg-light' : '' }}">
            <div class="me-3 mt-1">
                <i class="bi bi-{{ $item->ikon ?? 'bell' }} text-primary" style="font-size:1.2rem;"></i>
            </div>
            <div class="flex-grow-1">
                <p class="mb-1 {{ !$item->sudah_dibaca ? 'fw-semibold' : '' }}">{{ $item->pesan }}</p>
                <small class="text-muted">{{ $item->created_at->diffForHumans() }}</small>
            </div>
            @if(!$item->sudah_dibaca)
            <form action="{{ route('magang.notifikasi.baca', $item) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-sm btn-link text-muted p-0" title="Tandai dibaca"><i class="bi bi-check2"></i></button>
            </form>
            @endif
        </div>
        @empty
        <div class="text-center text-muted py-5">
            <i class="bi bi-bell-slash" style="font-size:2rem;"></i>
            <p class="mt-2 mb-0">Tidak ada notifikasi.</p>
        </div>
        @endforelse
        <div class="px-3 py-2">{{ $notifications->links() }}</div>
    </div>
</div>
@endsection
