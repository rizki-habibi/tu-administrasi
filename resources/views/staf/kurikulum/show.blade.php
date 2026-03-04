@extends('staf.tata-letak.app')
@section('judul', 'Detail Dokumen Kurikulum')

@section('konten')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('staf.kurikulum.index') }}" class="btn btn-outline-secondary btn-sm me-3"><i class="bi bi-arrow-left"></i></a>
    <h4 class="fw-bold mb-0" style="color:#1e293b;">Detail Dokumen</h4>
</div>

<div class="row g-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body p-4">
                <div class="mb-3">
                    <span class="badge bg-light text-dark me-1">{{ ucfirst($document->jenis ?? '-') }}</span>
                    @if($document->status == 'active')
                    <span class="badge bg-success">Aktif</span>
                    @elseif($document->status == 'archived')
                    <span class="badge bg-warning text-dark">Diarsipkan</span>
                    @else
                    <span class="badge bg-secondary">Draft</span>
                    @endif
                </div>
                <h5 class="fw-bold mb-3">{{ $document->judul }}</h5>
                <table class="table table-borderless mb-0">
                    <tr><td class="text-muted" width="180">Mata Pelajaran</td><td>{{ $document->mata_pelajaran ?? '-' }}</td></tr>
                    <tr><td class="text-muted">Kelas</td><td>{{ $document->tingkat_kelas ?? '-' }}</td></tr>
                    <tr><td class="text-muted">Tahun Ajaran</td><td>{{ $document->tahun_ajaran ?? '-' }}</td></tr>
                    <tr><td class="text-muted">Semester</td><td>{{ $document->semester ?? '-' }}</td></tr>
                    <tr><td class="text-muted">Pengunggah</td><td>{{ $document->creator->nama ?? '-' }}</td></tr>
                    <tr><td class="text-muted">Tanggal</td><td>{{ $document->created_at->translatedFormat('d F Y, H:i') }}</td></tr>
                </table>
                @if($document->deskripsi)
                <hr>
                <h6 class="fw-semibold">Deskripsi</h6>
                <p class="mb-0">{{ $document->deskripsi }}</p>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-transparent"><h6 class="mb-0"><i class="bi bi-file-earmark me-1"></i> File</h6></div>
            <div class="card-body text-center">
                @if($document->path_file)
                <i class="bi bi-file-earmark-pdf" style="font-size:3rem; color:#dc3545;"></i>
                <p class="mt-2 mb-2 text-muted small">{{ $document->nama_file ?? basename($document->path_file) }}</p>
                <a href="{{ Storage::url($document->path_file) }}" class="btn btn-outline-primary w-100" target="_blank"><i class="bi bi-download me-1"></i> Unduh</a>
                @else
                <p class="text-muted mb-0">Tidak ada file</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
