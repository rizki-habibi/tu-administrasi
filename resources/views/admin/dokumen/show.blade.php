@extends('admin.tata-letak.app')
@section('judul', 'Detail Dokumen')

@section('konten')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-bold mb-0">Detail Dokumen</h5>
    <a href="{{ route('admin.dokumen.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
</div>

<div class="card">
    <div class="card-body">
        <div class="d-flex align-items-center gap-3 mb-4">
            <i class="bi {{ $document->file_icon }}" style="font-size:3rem;"></i>
            <div>
                <h5 class="mb-1">{{ $document->judul }}</h5>
                <span class="badge bg-primary">{{ ucfirst($document->kategori) }}</span>
            </div>
        </div>
        <div class="row g-3">
            <div class="col-md-6"><strong>Nama File:</strong><br>{{ $document->nama_file }}</div>
            <div class="col-md-3"><strong>Ukuran:</strong><br>{{ $document->file_size_formatted }}</div>
            <div class="col-md-3"><strong>Tanggal Upload:</strong><br>{{ $document->created_at->format('d F Y H:i') }}</div>
            <div class="col-md-6"><strong>Diupload Oleh:</strong><br>{{ $document->uploader->nama ?? '-' }}</div>
            @if($document->deskripsi)
                <div class="col-12"><strong>Deskripsi:</strong><div class="bg-light p-3 rounded mt-1">{!! nl2br(e($document->deskripsi)) !!}</div></div>
            @endif
        </div>
        <div class="mt-4 d-flex gap-2">
            <a href="{{ asset('storage/' . $document->path_file) }}" class="btn btn-success" target="_blank"><i class="bi bi-download me-1"></i>Unduh</a>
            <a href="{{ route('admin.dokumen.edit', $document) }}" class="btn btn-warning"><i class="bi bi-pencil me-1"></i>Edit</a>
        </div>
    </div>
</div>
@endsection
