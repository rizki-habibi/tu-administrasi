@extends('peran.staf.app')
@section('judul', 'Detail Pelanggaran')

@section('konten')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-exclamation-triangle"></i> Detail Pelanggaran</h4>
    <a href="{{ route('staf.pelanggaran.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="row mb-3"><div class="col-md-3 fw-bold">Siswa</div><div class="col-md-9">{{ $pelanggaran->student?->nama ?? '-' }} ({{ $pelanggaran->student?->kelas ?? '-' }})</div></div>
        <div class="row mb-3"><div class="col-md-3 fw-bold">Tanggal</div><div class="col-md-9">{{ $pelanggaran->tanggal?->format('d F Y') }}</div></div>
        <div class="row mb-3"><div class="col-md-3 fw-bold">Jenis</div><div class="col-md-9">
            @php $jc = ['ringan'=>'warning','sedang'=>'orange','berat'=>'danger']; @endphp
            <span class="badge bg-{{ $jc[$pelanggaran->jenis] ?? 'secondary' }}">{{ ucfirst($pelanggaran->jenis) }}</span>
        </div></div>
        <div class="row mb-3"><div class="col-md-3 fw-bold">Dilaporkan oleh</div><div class="col-md-9">{{ $pelanggaran->reporter?->nama ?? '-' }}</div></div>
        <hr>
        <h6 class="fw-bold mb-2">Deskripsi</h6>
        <div class="bg-light p-3 rounded mb-3">{!! nl2br(e($pelanggaran->deskripsi)) !!}</div>
        @if($pelanggaran->tindakan)
        <h6 class="fw-bold mb-2">Tindakan/Sanksi</h6>
        <div class="bg-light p-3 rounded">{!! nl2br(e($pelanggaran->tindakan)) !!}</div>
        @endif
    </div>
</div>
@endsection
