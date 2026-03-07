@extends('peran.admin.app')
@section('judul', 'Detail Riwayat Pangkat')

@section('konten')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
        <h5 class="fw-bold mb-1">Detail Riwayat Pangkat</h5>
        <p class="text-muted mb-0" style="font-size:.82rem;">{{ $pangkat->pangkat }} ({{ $pangkat->golongan }}) — {{ $pangkat->pengguna->nama ?? '-' }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.kepegawaian.pangkat.edit', $pangkat) }}" class="btn btn-outline-warning btn-sm"><i class="bi bi-pencil me-1"></i>Edit</a>
        <a href="{{ route('admin.kepegawaian.pangkat.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="row g-3" style="font-size:.85rem;">
            <div class="col-md-6">
                <span class="text-muted d-block mb-1">Pegawai</span>
                <span class="fw-medium">{{ $pangkat->pengguna->nama ?? '-' }}</span>
            </div>
            <div class="col-md-3">
                <span class="text-muted d-block mb-1">Pangkat</span>
                <span class="fw-medium">{{ $pangkat->pangkat }}</span>
            </div>
            <div class="col-md-3">
                <span class="text-muted d-block mb-1">Golongan</span>
                <span class="badge bg-primary bg-opacity-10 text-primary">{{ $pangkat->golongan }}</span>
            </div>
            <div class="col-md-4">
                <span class="text-muted d-block mb-1">TMT Pangkat</span>
                <span class="fw-medium">{{ $pangkat->tmt_pangkat->format('d M Y') }}</span>
            </div>
            <div class="col-md-4">
                <span class="text-muted d-block mb-1">Jenis Kenaikan</span>
                <span class="fw-medium">{{ ucfirst($pangkat->jenis_kenaikan ?? '-') }}</span>
            </div>
            <div class="col-md-4">
                <span class="text-muted d-block mb-1">Nomor SK</span>
                <span class="fw-medium">{{ $pangkat->nomor_sk ?? '-' }}</span>
            </div>
            <div class="col-md-4">
                <span class="text-muted d-block mb-1">Tanggal SK</span>
                <span class="fw-medium">{{ $pangkat->tanggal_sk ? $pangkat->tanggal_sk->format('d M Y') : '-' }}</span>
            </div>
            <div class="col-md-4">
                <span class="text-muted d-block mb-1">Pejabat Penetap</span>
                <span class="fw-medium">{{ $pangkat->pejabat_penetap ?? '-' }}</span>
            </div>
            <div class="col-md-4">
                <span class="text-muted d-block mb-1">File SK</span>
                @if($pangkat->file_sk)
                    <a href="{{ Storage::url($pangkat->file_sk) }}" target="_blank" class="btn btn-outline-primary btn-sm"><i class="bi bi-download me-1"></i>Unduh / Lihat</a>
                @else
                    <span class="text-muted">Tidak ada file</span>
                @endif
            </div>
            <div class="col-12">
                <span class="text-muted d-block mb-1">Keterangan</span>
                <span class="fw-medium">{{ $pangkat->keterangan ?? '-' }}</span>
            </div>
        </div>
    </div>
</div>
@endsection
