@extends('layouts.staff')
@section('title', 'Detail Dokumen Kurikulum')

@section('content')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('staff.kurikulum.index') }}" class="btn btn-outline-secondary btn-sm me-3"><i class="bi bi-arrow-left"></i></a>
    <h4 class="fw-bold mb-0" style="color:#1e293b;">Detail Dokumen</h4>
</div>

<div class="row g-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body p-4">
                <div class="mb-3">
                    <span class="badge bg-light text-dark me-1">{{ ucfirst($document->type ?? '-') }}</span>
                    @if($document->status == 'final')
                    <span class="badge bg-success">Final</span>
                    @elseif($document->status == 'review')
                    <span class="badge bg-warning text-dark">Review</span>
                    @else
                    <span class="badge bg-secondary">Draft</span>
                    @endif
                </div>
                <h5 class="fw-bold mb-3">{{ $document->title }}</h5>
                <table class="table table-borderless mb-0">
                    <tr><td class="text-muted" width="180">Mata Pelajaran</td><td>{{ $document->subject ?? '-' }}</td></tr>
                    <tr><td class="text-muted">Kelas</td><td>{{ $document->class_level ?? '-' }}</td></tr>
                    <tr><td class="text-muted">Tahun Ajaran</td><td>{{ $document->academic_year ?? '-' }}</td></tr>
                    <tr><td class="text-muted">Semester</td><td>{{ $document->semester ?? '-' }}</td></tr>
                    <tr><td class="text-muted">Pengunggah</td><td>{{ $document->creator->name ?? '-' }}</td></tr>
                    <tr><td class="text-muted">Tanggal</td><td>{{ $document->created_at->translatedFormat('d F Y, H:i') }}</td></tr>
                </table>
                @if($document->description)
                <hr>
                <h6 class="fw-semibold">Deskripsi</h6>
                <p class="mb-0">{{ $document->description }}</p>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-transparent"><h6 class="mb-0"><i class="bi bi-file-earmark me-1"></i> File</h6></div>
            <div class="card-body text-center">
                @if($document->file)
                <i class="bi bi-file-earmark-pdf" style="font-size:3rem; color:#dc3545;"></i>
                <p class="mt-2 mb-2 text-muted small">{{ basename($document->file) }}</p>
                <a href="{{ Storage::url($document->file) }}" class="btn btn-outline-primary w-100" target="_blank"><i class="bi bi-download me-1"></i> Unduh</a>
                @else
                <p class="text-muted mb-0">Tidak ada file</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
