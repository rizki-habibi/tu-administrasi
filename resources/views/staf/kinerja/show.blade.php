@extends('peran.staf.app')
@section('judul', 'Detail Kinerja')

@section('konten')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h4 class="fw-bold mb-1">Detail Kinerja</h4>
        <p class="text-muted mb-0" style="font-size:.85rem;">Konten bersifat baca-saja untuk staff.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('staf.kinerja.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
        @if($item->path_file || $item->url_external)
            <a href="{{ route('staf.kinerja.download', $item) }}" class="btn btn-success btn-sm">
                <i class="bi bi-download me-1"></i>Unduh
            </a>
        @endif
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <div class="d-flex flex-wrap gap-2 mb-3">
            <span class="badge bg-info-subtle text-info-emphasis">{{ ucfirst(str_replace('_', ' ', $item->kategori)) }}</span>
            <span class="badge bg-secondary-subtle text-secondary-emphasis">{{ ucfirst($item->tipe) }}</span>
            @if($item->unggulan)
                <span class="badge bg-warning text-dark"><i class="bi bi-star-fill"></i> Unggulan</span>
            @endif
        </div>

        <h5 class="fw-bold mb-2">{{ $item->judul }}</h5>
        @if($item->deskripsi)
            <p class="text-muted">{{ $item->deskripsi }}</p>
        @endif

        @if($item->thumbnail)
            <img src="{{ $item->thumbnail_url }}" alt="Thumbnail" class="img-fluid rounded mb-3" style="max-height:280px;object-fit:cover;">
        @endif

        @if($item->tipe === 'video' && $item->url_external)
            <div class="ratio ratio-16x9 rounded overflow-hidden mb-3">
                <iframe src="{{ $item->url_external }}" allowfullscreen></iframe>
            </div>
        @endif

        @if($item->konten)
            <div class="border rounded p-3" style="background:#f8fafc;">
                {!! $item->konten !!}
            </div>
        @endif

        <div class="mt-3 small text-muted">
            @if($item->nama_file)
                <div><i class="bi bi-paperclip me-1"></i>File: {{ $item->nama_file }} @if($item->ukuran_file) ({{ $item->ukuran_format }}) @endif</div>
            @endif
            <div><i class="bi bi-clock-history me-1"></i>Terakhir diperbarui: {{ $item->updated_at->translatedFormat('d F Y H:i') }}</div>
        </div>
    </div>
</div>
@endsection
