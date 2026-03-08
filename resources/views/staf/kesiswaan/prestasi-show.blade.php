@extends('peran.staf.app')
@section('judul', 'Detail Prestasi')

@section('konten')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-trophy"></i> Detail Prestasi</h4>
    <a href="{{ route('staf.prestasi.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="row mb-3"><div class="col-md-3 fw-bold">Siswa</div><div class="col-md-9">{{ $prestasi->student?->nama ?? '-' }} ({{ $prestasi->student?->kelas ?? '-' }})</div></div>
        <div class="row mb-3"><div class="col-md-3 fw-bold">Judul</div><div class="col-md-9 fw-bold">{{ $prestasi->judul }}</div></div>
        <div class="row mb-3"><div class="col-md-3 fw-bold">Tingkat</div><div class="col-md-9"><span class="badge bg-info">{{ ucfirst($prestasi->tingkat) }}</span></div></div>
        <div class="row mb-3"><div class="col-md-3 fw-bold">Jenis</div><div class="col-md-9">{{ ucfirst(str_replace('_',' ',$prestasi->jenis)) }}</div></div>
        <div class="row mb-3"><div class="col-md-3 fw-bold">Tanggal</div><div class="col-md-9">{{ $prestasi->tanggal?->format('d F Y') }}</div></div>
        <div class="row mb-3"><div class="col-md-3 fw-bold">Penyelenggara</div><div class="col-md-9">{{ $prestasi->penyelenggara ?? '-' }}</div></div>
        <div class="row mb-3"><div class="col-md-3 fw-bold">Hasil</div><div class="col-md-9">{{ $prestasi->hasil ?? '-' }}</div></div>
        @if($prestasi->path_file)
        <div class="row mb-3"><div class="col-md-3 fw-bold">Bukti</div><div class="col-md-9"><a href="{{ asset('storage/' . $prestasi->path_file) }}" target="_blank" class="btn btn-sm btn-outline-primary"><i class="bi bi-download"></i> Lihat Bukti</a></div></div>
        @endif
    </div>
</div>
@endsection
