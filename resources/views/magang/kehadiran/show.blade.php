@extends('peran.magang.app')
@section('judul', 'Detail Kehadiran')

@section('konten')
<div class="mb-4">
    <a href="{{ route('magang.kehadiran.index') }}" class="text-muted text-decoration-none"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 py-3">
        <h6 class="fw-bold mb-0"><i class="bi bi-clock-history me-2 text-primary"></i>Detail Kehadiran — {{ $kehadiran->tanggal?->format('l, d M Y') }}</h6>
    </div>
    <div class="card-body">
        <div class="row g-4">
            <div class="col-md-6">
                <div class="card border bg-light">
                    <div class="card-body text-center">
                        <i class="bi bi-box-arrow-in-right text-success" style="font-size:1.5rem;"></i>
                        <h6 class="fw-bold mt-2 mb-1">Jam Masuk</h6>
                        <p class="fs-4 fw-bold text-success mb-1">{{ $kehadiran->jam_masuk ? \Carbon\Carbon::parse($kehadiran->jam_masuk)->format('H:i') : '-' }}</p>
                        @if($kehadiran->foto_masuk)
                        <img src="{{ asset('storage/' . $kehadiran->foto_masuk) }}" class="rounded mt-2" style="max-width:150px;">
                        @endif
                        @if($kehadiran->lokasi_masuk)
                        <p class="mt-2 mb-0" style="font-size:.8rem;"><i class="bi bi-geo-alt"></i> {{ $kehadiran->lokasi_masuk }}</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border bg-light">
                    <div class="card-body text-center">
                        <i class="bi bi-box-arrow-right text-danger" style="font-size:1.5rem;"></i>
                        <h6 class="fw-bold mt-2 mb-1">Jam Pulang</h6>
                        <p class="fs-4 fw-bold text-danger mb-1">{{ $kehadiran->jam_pulang ? \Carbon\Carbon::parse($kehadiran->jam_pulang)->format('H:i') : '-' }}</p>
                        @if($kehadiran->foto_pulang)
                        <img src="{{ asset('storage/' . $kehadiran->foto_pulang) }}" class="rounded mt-2" style="max-width:150px;">
                        @endif
                        @if($kehadiran->lokasi_pulang)
                        <p class="mt-2 mb-0" style="font-size:.8rem;"><i class="bi bi-geo-alt"></i> {{ $kehadiran->lokasi_pulang }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <small class="text-muted fw-semibold">Status</small>
            <p>
                @php $ws = ['hadir'=>'success','izin'=>'info','sakit'=>'warning','alfa'=>'danger']; @endphp
                <span class="badge bg-{{ $ws[$kehadiran->status] ?? 'secondary' }}">{{ ucfirst($kehadiran->status) }}</span>
            </p>
        </div>

        @if($kehadiran->catatan)
        <div class="mt-2">
            <small class="text-muted fw-semibold">Catatan</small>
            <div class="bg-light p-3 rounded mt-1">{!! nl2br(e($kehadiran->catatan)) !!}</div>
        </div>
        @endif
    </div>
</div>
@endsection
