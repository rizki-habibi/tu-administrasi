@extends('peran.magang.app')
@section('judul', 'Detail Izin')

@section('konten')
<div class="mb-4">
    <a href="{{ route('magang.izin.index') }}" class="text-muted text-decoration-none"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
        <h6 class="fw-bold mb-0"><i class="bi bi-envelope-open me-2 text-primary"></i>Detail Pengajuan Izin</h6>
        @php $ws = ['approved'=>'success','rejected'=>'danger','pending'=>'warning']; @endphp
        <span class="badge bg-{{ $ws[$izin->status] ?? 'secondary' }}">{{ ucfirst($izin->status) }}</span>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-4">
                <small class="text-muted fw-semibold">Jenis</small>
                <p><span class="badge bg-{{ $izin->jenis === 'sakit' ? 'danger' : 'info' }}">{{ ucfirst($izin->jenis) }}</span></p>
            </div>
            <div class="col-md-4">
                <small class="text-muted fw-semibold">Tanggal Mulai</small>
                <p>{{ $izin->tanggal_mulai?->format('d M Y') }}</p>
            </div>
            <div class="col-md-4">
                <small class="text-muted fw-semibold">Tanggal Selesai</small>
                <p>{{ $izin->tanggal_selesai?->format('d M Y') }}</p>
            </div>
        </div>

        <div class="mb-3">
            <small class="text-muted fw-semibold">Alasan</small>
            <div class="bg-light p-3 rounded mt-1">{!! nl2br(e($izin->alasan)) !!}</div>
        </div>

        @if($izin->lampiran)
        <div class="mb-3">
            <small class="text-muted fw-semibold">Lampiran</small>
            <div class="mt-1">
                <a href="{{ asset('storage/' . $izin->lampiran) }}" target="_blank" class="btn btn-sm btn-outline-primary"><i class="bi bi-paperclip me-1"></i>Lihat Lampiran</a>
            </div>
        </div>
        @endif

        @if($izin->catatan_admin)
        <div class="alert alert-light border mt-3" style="border-left:4px solid #0891b2 !important;">
            <strong><i class="bi bi-chat-quote me-1"></i>Catatan Admin:</strong>
            <p class="mb-0 mt-1">{!! nl2br(e($izin->catatan_admin)) !!}</p>
        </div>
        @endif
    </div>
    @if($izin->status === 'pending')
    <div class="card-footer bg-white border-0">
        <form action="{{ route('magang.izin.destroy', $izin) }}" method="POST" class="form-hapus">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-x-circle me-1"></i>Batalkan Pengajuan</button>
        </form>
    </div>
    @endif
</div>
@endsection
