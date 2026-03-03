@extends('layouts.admin')
@section('title', 'Detail Dokumen Akreditasi')

@section('content')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('admin.akreditasi.index') }}" class="btn btn-outline-secondary btn-sm me-3"><i class="bi bi-arrow-left"></i></a>
    <h4 class="fw-bold mb-0" style="color:#1e293b;">Detail Dokumen Akreditasi</h4>
    <div class="ms-auto">
        <form action="{{ route('admin.akreditasi.destroy', $document->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus dokumen ini?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-outline-danger btn-sm"><i class="bi bi-trash me-1"></i> Hapus</button>
        </form>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body p-4">
                @php
                $standarLabels = [
                    1 => 'Standar Kompetensi Lulusan',
                    2 => 'Standar Isi',
                    3 => 'Standar Proses',
                    4 => 'Standar Penilaian',
                    5 => 'Standar PTK',
                    6 => 'Standar Sarana & Prasarana',
                    7 => 'Standar Pengelolaan',
                    8 => 'Standar Pembiayaan',
                ];
                @endphp
                <div class="mb-3">
                    <span class="badge bg-primary" style="font-size:0.85rem;">Standar {{ $document->standar }}</span>
                    <span class="text-muted ms-2">{{ $standarLabels[$document->standar] ?? '' }}</span>
                </div>
                <h5 class="fw-bold mb-3">{{ $document->title }}</h5>
                <table class="table table-borderless mb-0">
                    <tr><td class="text-muted" width="200">Tahun Akreditasi</td><td>{{ $document->year ?? '-' }}</td></tr>
                    <tr><td class="text-muted">Status Kelengkapan</td><td>
                        @if($document->is_complete)
                        <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> Lengkap</span>
                        @else
                        <span class="badge bg-warning text-dark"><i class="bi bi-clock me-1"></i> Belum Lengkap</span>
                        @endif
                    </td></tr>
                    <tr><td class="text-muted">Diunggah Oleh</td><td>{{ $document->uploader->name ?? '-' }}</td></tr>
                    <tr><td class="text-muted">Tanggal Unggah</td><td>{{ $document->created_at->translatedFormat('d F Y, H:i') }}</td></tr>
                </table>
                @if($document->description)
                <hr>
                <h6 class="fw-semibold">Deskripsi</h6>
                <p>{{ $document->description }}</p>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-transparent">
                <h6 class="mb-0"><i class="bi bi-file-earmark me-1"></i> File Dokumen</h6>
            </div>
            <div class="card-body">
                @if($document->file)
                <div class="text-center">
                    <i class="bi bi-file-earmark-pdf" style="font-size:3rem; color:#dc3545;"></i>
                    <p class="mt-2 mb-2 text-muted small">{{ basename($document->file) }}</p>
                    <a href="{{ Storage::url($document->file) }}" class="btn btn-outline-primary w-100" target="_blank">
                        <i class="bi bi-download me-1"></i> Unduh
                    </a>
                </div>
                @else
                <p class="text-muted text-center mb-0">Tidak ada file</p>
                @endif
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header bg-transparent">
                <h6 class="mb-0"><i class="bi bi-info-circle me-1"></i> Info Standar</h6>
            </div>
            <div class="card-body">
                <p class="mb-0 small text-muted">{{ $standarLabels[$document->standar] ?? 'Standar '.$document->standar }} merupakan salah satu dari 8 Standar Nasional Pendidikan yang dinilai dalam proses akreditasi sekolah.</p>
            </div>
        </div>
    </div>
</div>
@endsection
