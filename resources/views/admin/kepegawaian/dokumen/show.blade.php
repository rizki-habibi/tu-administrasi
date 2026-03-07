@extends('peran.admin.app')
@section('judul', 'Detail Dokumen Kepegawaian')

@section('konten')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
        <h5 class="fw-bold mb-1">Detail Dokumen</h5>
        <p class="text-muted mb-0" style="font-size:.82rem;">{{ $dokumen->judul }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ Storage::url($dokumen->file_path) }}" target="_blank" class="btn btn-outline-success btn-sm"><i class="bi bi-download me-1"></i>Unduh</a>
        <a href="{{ route('admin.kepegawaian.dokumen.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="row g-3" style="font-size:.85rem;">
            <div class="col-md-6">
                <span class="text-muted d-block mb-1">Pegawai</span>
                <span class="fw-medium">{{ $dokumen->pengguna->nama ?? '-' }}</span>
            </div>
            <div class="col-md-6">
                <span class="text-muted d-block mb-1">Kategori</span>
                <span class="badge bg-info bg-opacity-10 text-info">{{ \App\Models\DokumenKepegawaian::KATEGORI[$dokumen->kategori] ?? $dokumen->kategori }}</span>
            </div>
            <div class="col-md-6">
                <span class="text-muted d-block mb-1">Judul Dokumen</span>
                <span class="fw-medium">{{ $dokumen->judul }}</span>
            </div>
            <div class="col-md-3">
                <span class="text-muted d-block mb-1">Nomor Dokumen</span>
                <span class="fw-medium">{{ $dokumen->nomor_dokumen ?? '-' }}</span>
            </div>
            <div class="col-md-3">
                <span class="text-muted d-block mb-1">Tanggal Dokumen</span>
                <span class="fw-medium">{{ $dokumen->tanggal_dokumen ? $dokumen->tanggal_dokumen->format('d M Y') : '-' }}</span>
            </div>
            <div class="col-md-4">
                <span class="text-muted d-block mb-1">Tipe File</span>
                <span class="fw-medium">{{ strtoupper($dokumen->file_type) }}</span>
            </div>
            <div class="col-md-4">
                <span class="text-muted d-block mb-1">Ukuran File</span>
                <span class="fw-medium">{{ number_format($dokumen->file_size / 1024, 1) }} KB</span>
            </div>
            <div class="col-md-4">
                <span class="text-muted d-block mb-1">Diunggah</span>
                <span class="fw-medium">{{ $dokumen->created_at->format('d M Y H:i') }}</span>
            </div>
            <div class="col-12">
                <span class="text-muted d-block mb-1">Keterangan</span>
                <span class="fw-medium">{{ $dokumen->keterangan ?? '-' }}</span>
            </div>
        </div>
    </div>
</div>

@if(in_array($dokumen->file_type, ['pdf', 'jpg', 'jpeg', 'png']))
<div class="card mt-3">
    <div class="card-header bg-white">
        <h6 class="mb-0 fw-bold" style="font-size:.85rem;">Pratinjau Dokumen</h6>
    </div>
    <div class="card-body text-center">
        @if($dokumen->file_type == 'pdf')
            <iframe src="{{ Storage::url($dokumen->file_path) }}" width="100%" height="600" style="border:1px solid #e2e8f0;border-radius:8px;"></iframe>
        @else
            <img src="{{ Storage::url($dokumen->file_path) }}" alt="{{ $dokumen->judul }}" class="img-fluid rounded" style="max-height:600px;">
        @endif
    </div>
</div>
@endif
@endsection
