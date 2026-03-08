@extends('peran.staf.app')
@section('judul', 'Detail Peminjaman Fasilitas')

@section('konten')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-building"></i> Detail Peminjaman</h4>
    <a href="{{ route('staf.peminjaman.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="row mb-3"><div class="col-md-3 fw-bold">Fasilitas</div><div class="col-md-9">{{ $peminjaman->nama_fasilitas }}</div></div>
        <div class="row mb-3"><div class="col-md-3 fw-bold">Jenis</div><div class="col-md-9">{{ \App\Models\PeminjamanFasilitas::JENIS[$peminjaman->jenis] ?? $peminjaman->jenis }}</div></div>
        <div class="row mb-3"><div class="col-md-3 fw-bold">Peminjam</div><div class="col-md-9">{{ $peminjaman->peminjam_nama }}</div></div>
        <div class="row mb-3"><div class="col-md-3 fw-bold">Tanggal</div><div class="col-md-9">{{ $peminjaman->tanggal?->format('d F Y') }}</div></div>
        <div class="row mb-3"><div class="col-md-3 fw-bold">Waktu</div><div class="col-md-9">{{ $peminjaman->jam_mulai }} - {{ $peminjaman->jam_selesai }}</div></div>
        <div class="row mb-3"><div class="col-md-3 fw-bold">Keperluan</div><div class="col-md-9">{{ $peminjaman->keperluan }}</div></div>
        @if($peminjaman->penanggung_jawab)
        <div class="row mb-3"><div class="col-md-3 fw-bold">Penanggung Jawab</div><div class="col-md-9">{{ $peminjaman->penanggung_jawab }}</div></div>
        @endif
        @if($peminjaman->catatan)
        <div class="row mb-3"><div class="col-md-3 fw-bold">Catatan</div><div class="col-md-9">{{ $peminjaman->catatan }}</div></div>
        @endif
        <div class="row mb-3"><div class="col-md-3 fw-bold">Status</div><div class="col-md-9">{!! $peminjaman->status_badge !!}</div></div>
        @if($peminjaman->alasan_tolak)
        <div class="row mb-3"><div class="col-md-3 fw-bold">Alasan Ditolak</div><div class="col-md-9 text-danger">{{ $peminjaman->alasan_tolak }}</div></div>
        @endif
        @if($peminjaman->approver)
        <div class="row mb-3"><div class="col-md-3 fw-bold">Diproses oleh</div><div class="col-md-9">{{ $peminjaman->approver->nama }} ({{ $peminjaman->disetujui_pada?->format('d/m/Y H:i') }})</div></div>
        @endif
    </div>
</div>

@if($peminjaman->status === 'pending')
<div class="card border-0 shadow-sm mt-4">
    <div class="card-header bg-transparent fw-bold">Tindakan</div>
    <div class="card-body d-flex gap-2">
        <form action="{{ route('staf.peminjaman.setujui', $peminjaman) }}" method="POST" onsubmit="return confirm('Setujui peminjaman ini?')">
            @csrf @method('PATCH')
            <button class="btn btn-success"><i class="bi bi-check-lg"></i> Setujui</button>
        </form>
        <form action="{{ route('staf.peminjaman.tolak', $peminjaman) }}" method="POST" id="formTolak">
            @csrf @method('PATCH')
            <div class="input-group">
                <input type="text" name="alasan_tolak" class="form-control" placeholder="Alasan penolakan..." required>
                <button class="btn btn-danger" onclick="return confirm('Tolak peminjaman ini?')"><i class="bi bi-x-lg"></i> Tolak</button>
            </div>
        </form>
    </div>
</div>
@elseif($peminjaman->status === 'disetujui')
<div class="card border-0 shadow-sm mt-4">
    <div class="card-body">
        <form action="{{ route('staf.peminjaman.selesai', $peminjaman) }}" method="POST" onsubmit="return confirm('Tandai peminjaman ini selesai?')">
            @csrf @method('PATCH')
            <button class="btn btn-primary"><i class="bi bi-check-circle"></i> Tandai Selesai</button>
        </form>
    </div>
</div>
@endif
@endsection
