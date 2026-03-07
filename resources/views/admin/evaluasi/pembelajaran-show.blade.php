@extends('peran.admin.app')
@section('judul', 'Detail Metode Pembelajaran')

@section('konten')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('admin.evaluasi.pembelajaran') }}" class="btn btn-outline-secondary btn-sm me-3"><i class="bi bi-arrow-left"></i></a>
    <div>
        <h4 class="fw-bold mb-0" style="color:#1e293b;">{{ $method->nama_metode }}</h4>
        <small class="text-muted">{{ $method->jenis_label }} &middot; {{ $method->creator->nama ?? '-' }}</small>
    </div>
    <div class="ms-auto d-flex gap-2">
        <a href="{{ route('admin.evaluasi.pembelajaran.edit', $method) }}" class="btn btn-outline-warning btn-sm"><i class="bi bi-pencil me-1"></i> Edit</a>
        <form action="{{ route('admin.evaluasi.pembelajaran.destroy', $method) }}" method="POST" class="d-inline">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-outline-danger btn-sm" data-confirm="Hapus metode ini?"><i class="bi bi-trash me-1"></i> Hapus</button>
        </form>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-header bg-white"><h6 class="fw-bold mb-0"><i class="bi bi-file-text me-2 text-primary"></i>Deskripsi</h6></div>
            <div class="card-body"><p style="white-space:pre-line;font-size:.85rem;">{{ $method->deskripsi ?? '-' }}</p></div>
        </div>
        @if($method->langkah_pelaksanaan)
        <div class="card mb-3">
            <div class="card-header bg-white"><h6 class="fw-bold mb-0"><i class="bi bi-list-ol me-2 text-success"></i>Langkah Pelaksanaan</h6></div>
            <div class="card-body"><p style="white-space:pre-line;font-size:.85rem;">{{ $method->langkah_pelaksanaan }}</p></div>
        </div>
        @endif
        @if($method->hasil)
        <div class="card mb-3">
            <div class="card-header bg-white"><h6 class="fw-bold mb-0"><i class="bi bi-trophy me-2 text-warning"></i>Hasil</h6></div>
            <div class="card-body"><p style="white-space:pre-line;font-size:.85rem;">{{ $method->hasil }}</p></div>
        </div>
        @endif
    </div>
    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-header bg-white"><h6 class="fw-bold mb-0">Informasi</h6></div>
            <div class="card-body" style="font-size:.85rem;">
                <p><strong>Mata Pelajaran:</strong><br>{{ $method->mata_pelajaran ?? '-' }}</p>
                <p><strong>Jenis:</strong><br>{{ $method->jenis_label }}</p>
                <p><strong>Dibuat:</strong><br>{{ $method->created_at->translatedFormat('d F Y H:i') }}</p>
            </div>
        </div>
        @if($method->kelebihan || $method->kekurangan)
        <div class="card">
            <div class="card-body" style="font-size:.85rem;">
                @if($method->kelebihan)
                <p><strong class="text-success"><i class="bi bi-plus-circle me-1"></i>Kelebihan:</strong><br>{{ $method->kelebihan }}</p>
                @endif
                @if($method->kekurangan)
                <p class="mb-0"><strong class="text-danger"><i class="bi bi-dash-circle me-1"></i>Kekurangan:</strong><br>{{ $method->kekurangan }}</p>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
