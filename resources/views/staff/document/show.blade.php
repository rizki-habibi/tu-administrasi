@extends('layouts.staff')
@section('title', 'Detail Dokumen')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-bold mb-0">Detail Dokumen</h5>
    <a href="{{ route('staff.document.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
</div>
<div class="card">
    <div class="card-body">
        <div class="d-flex align-items-center gap-3 mb-4">
            <i class="bi {{ $document->file_icon }}" style="font-size:3rem;"></i>
            <div>
                <h5 class="mb-1">{{ $document->title }}</h5>
                <span class="badge bg-primary">{{ ucfirst($document->category) }}</span>
            </div>
        </div>
        <div class="row g-3">
            <div class="col-md-6"><strong>Nama File:</strong><br>{{ $document->file_name }}</div>
            <div class="col-md-3"><strong>Ukuran:</strong><br>{{ $document->file_size_formatted }}</div>
            <div class="col-md-3"><strong>Tanggal:</strong><br>{{ $document->created_at->format('d F Y H:i') }}</div>
            @if($document->description)
                <div class="col-12"><strong>Deskripsi:</strong><div class="bg-light p-3 rounded mt-1">{!! nl2br(e($document->description)) !!}</div></div>
            @endif
        </div>
        <div class="mt-4">
            <a href="{{ asset('storage/' . $document->file_path) }}" class="btn btn-success" target="_blank"><i class="bi bi-download me-1"></i>Download</a>
        </div>
    </div>
</div>
@endsection
