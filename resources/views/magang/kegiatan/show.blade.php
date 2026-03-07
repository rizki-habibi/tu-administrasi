@extends('peran.magang.app')
@section('judul', 'Detail Kegiatan')

@section('konten')
<div class="mb-4">
    <a href="{{ route('magang.kegiatan.index') }}" class="text-muted text-decoration-none"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
        <h6 class="fw-bold mb-0"><i class="bi bi-clipboard-data me-2 text-primary"></i>{{ $kegiatanMagang->judul }}</h6>
        <div class="d-flex gap-2">
            @php $ws = ['selesai'=>'success','berlangsung'=>'primary','belum_mulai'=>'secondary']; @endphp
            <span class="badge bg-{{ $ws[$kegiatanMagang->status] ?? 'secondary' }}">{{ str_replace('_',' ',ucfirst($kegiatanMagang->status)) }}</span>
            @php $wp = ['tinggi'=>'danger','sedang'=>'warning','rendah'=>'info']; @endphp
            <span class="badge bg-{{ $wp[$kegiatanMagang->prioritas] ?? 'secondary' }}">{{ ucfirst($kegiatanMagang->prioritas) }}</span>
        </div>
    </div>
    <div class="card-body">
        @if($kegiatanMagang->tanggal_mulai || $kegiatanMagang->tanggal_selesai)
        <div class="mb-3">
            <small class="text-muted fw-semibold">Periode</small>
            <p>{{ $kegiatanMagang->tanggal_mulai?->format('d M Y') ?? '-' }} s.d {{ $kegiatanMagang->tanggal_selesai?->format('d M Y') ?? '-' }}</p>
        </div>
        @endif

        @if($kegiatanMagang->deskripsi)
        <div class="mb-3">
            <small class="text-muted fw-semibold">Deskripsi</small>
            <div class="bg-light p-3 rounded mt-1">{!! nl2br(e($kegiatanMagang->deskripsi)) !!}</div>
        </div>
        @endif

        @if($kegiatanMagang->catatan)
        <div class="mb-3">
            <small class="text-muted fw-semibold">Catatan</small>
            <div class="bg-light p-3 rounded mt-1">{!! nl2br(e($kegiatanMagang->catatan)) !!}</div>
        </div>
        @endif
    </div>
    <div class="card-footer bg-white border-0 d-flex gap-2">
        <a href="{{ route('magang.kegiatan.edit', $kegiatanMagang) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil me-1"></i>Edit</a>
        <form action="{{ route('magang.kegiatan.destroy', $kegiatanMagang) }}" method="POST" class="form-hapus">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash me-1"></i>Hapus</button>
        </form>
    </div>
</div>
@endsection
