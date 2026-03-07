@extends('peran.magang.app')
@section('judul', 'Detail Logbook')

@section('konten')
<div class="mb-4">
    <a href="{{ route('magang.logbook.index') }}" class="text-muted text-decoration-none"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
        <h6 class="fw-bold mb-0"><i class="bi bi-journal-text me-2 text-primary"></i>Logbook — {{ $logbook->tanggal->format('l, d M Y') }}</h6>
        <span class="badge bg-{{ $logbook->status === 'final' ? 'success' : 'warning' }}">{{ ucfirst($logbook->status) }}</span>
    </div>
    <div class="card-body">
        @if($logbook->jam_mulai || $logbook->jam_selesai)
        <div class="mb-3">
            <small class="text-muted fw-semibold">Jam Kegiatan</small>
            <p>{{ $logbook->jam_mulai ? substr($logbook->jam_mulai,0,5) : '-' }} s.d {{ $logbook->jam_selesai ? substr($logbook->jam_selesai,0,5) : '-' }}</p>
        </div>
        @endif

        <div class="mb-3">
            <small class="text-muted fw-semibold">Kegiatan</small>
            <div class="bg-light p-3 rounded mt-1">{!! nl2br(e($logbook->kegiatan)) !!}</div>
        </div>

        @if($logbook->hasil)
        <div class="mb-3">
            <small class="text-muted fw-semibold">Hasil</small>
            <div class="bg-light p-3 rounded mt-1">{!! nl2br(e($logbook->hasil)) !!}</div>
        </div>
        @endif

        @if($logbook->kendala)
        <div class="mb-3">
            <small class="text-muted fw-semibold">Kendala</small>
            <div class="bg-light p-3 rounded mt-1">{!! nl2br(e($logbook->kendala)) !!}</div>
        </div>
        @endif

        @if($logbook->rencana_besok)
        <div class="mb-3">
            <small class="text-muted fw-semibold">Rencana Besok</small>
            <div class="bg-light p-3 rounded mt-1">{!! nl2br(e($logbook->rencana_besok)) !!}</div>
        </div>
        @endif

        @if($logbook->catatan_pembimbing)
        <div class="alert alert-light border mt-3" style="border-left:4px solid #0891b2 !important;">
            <strong><i class="bi bi-chat-quote me-1"></i>Catatan Pembimbing:</strong>
            <p class="mb-0 mt-1">{!! nl2br(e($logbook->catatan_pembimbing)) !!}</p>
        </div>
        @endif
    </div>
    <div class="card-footer bg-white border-0 d-flex gap-2">
        @if($logbook->status !== 'final')
            <a href="{{ route('magang.logbook.edit', $logbook) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil me-1"></i>Edit</a>
            <form action="{{ route('magang.logbook.destroy', $logbook) }}" method="POST" class="form-hapus">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash me-1"></i>Hapus</button>
            </form>
        @endif
    </div>
</div>
@endsection
