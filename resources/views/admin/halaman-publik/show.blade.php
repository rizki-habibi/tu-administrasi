@extends('peran.admin.app')
@section('judul', $kontenPublik->judul)

@push('styles')
<style>
    .detail-header {
        background: linear-gradient(135deg, var(--primary), #818cf8);
        border-radius: var(--card-radius);
        padding: 2rem;
        color: #fff;
        margin-bottom: 1.5rem;
    }
    .detail-header h2 { font-weight: 700; margin-bottom: .5rem; }
    .detail-meta { display: flex; gap: 1rem; flex-wrap: wrap; font-size: .85rem; opacity: .9; }
    .detail-meta span i { margin-right: .25rem; }
    .detail-card {
        background: #fff;
        border-radius: var(--card-radius);
        box-shadow: 0 2px 12px rgba(0,0,0,.06);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    .detail-card h5 { font-weight: 700; margin-bottom: 1rem; color: var(--primary); }
    .detail-card .konten-html img { max-width: 100%; border-radius: 8px; }
    .info-grid { display: grid; grid-template-columns: 140px 1fr; gap: .5rem 1rem; font-size: .9rem; }
    .info-grid dt { color: #64748b; font-weight: 600; }
    .info-grid dd { margin: 0; color: #1e293b; }
    .badge-status { padding: .35em .75em; border-radius: 6px; font-size: .8rem; font-weight: 600; }
    .badge-aktif { background: #dcfce7; color: #166534; }
    .badge-nonaktif { background: #fee2e2; color: #991b1b; }
    .file-preview { border: 2px dashed #e2e8f0; border-radius: 10px; padding: 1.5rem; text-align: center; }
    .file-preview img { max-height: 400px; max-width: 100%; border-radius: 8px; }
    .file-preview video { max-width: 100%; border-radius: 8px; }
    .btn-action { border-radius: 8px; padding: .5rem 1.25rem; font-weight: 600; font-size: .85rem; }
</style>
@endpush

@section('konten')
<div class="detail-header">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
        <div>
            <h2>{{ $kontenPublik->judul }}</h2>
            <div class="detail-meta">
                <span><i class="bi bi-folder"></i> {{ ucfirst(str_replace('_', ' ', $kontenPublik->kategori)) }}</span>
                <span><i class="bi bi-tag"></i> {{ ucfirst($kontenPublik->tipe) }}</span>
                <span><i class="bi bi-layout-text-sidebar"></i> {{ ucfirst(str_replace('_', ' ', $kontenPublik->bagian)) }}</span>
                <span><i class="bi bi-calendar3"></i> {{ $kontenPublik->created_at->translatedFormat('d F Y H:i') }}</span>
                @if($kontenPublik->pembuat)
                    <span><i class="bi bi-person"></i> {{ $kontenPublik->pembuat->nama }}</span>
                @endif
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.halaman-publik.edit', $kontenPublik) }}" class="btn btn-warning btn-action text-white">
                <i class="bi bi-pencil-square"></i> Edit
            </a>
            <a href="{{ route('admin.halaman-publik.index') }}" class="btn btn-light btn-action">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        {{-- Deskripsi --}}
        @if($kontenPublik->deskripsi)
        <div class="detail-card">
            <h5><i class="bi bi-text-paragraph"></i> Deskripsi</h5>
            <p class="mb-0" style="color:#475569;">{{ $kontenPublik->deskripsi }}</p>
        </div>
        @endif

        {{-- Konten --}}
        @if($kontenPublik->konten)
        <div class="detail-card">
            <h5><i class="bi bi-file-richtext"></i> Konten</h5>
            <div class="konten-html">{!! $kontenPublik->konten !!}</div>
        </div>
        @endif

        {{-- File Preview --}}
        @if($kontenPublik->path_file)
        <div class="detail-card">
            <h5><i class="bi bi-file-earmark"></i> File / Media</h5>
            <div class="file-preview">
                @if(in_array($kontenPublik->tipe, ['gambar']) || Str::startsWith($kontenPublik->tipe_file ?? '', 'image/'))
                    <img src="{{ $kontenPublik->file_url }}" alt="{{ $kontenPublik->judul }}">
                @elseif($kontenPublik->tipe === 'video')
                    <video controls>
                        <source src="{{ $kontenPublik->file_url }}" type="{{ $kontenPublik->tipe_file }}">
                    </video>
                @elseif($kontenPublik->tipe === 'dokumen')
                    <div class="py-3">
                        <i class="bi bi-file-earmark-pdf" style="font-size:3rem;color:var(--primary);"></i>
                        <p class="mt-2 mb-1 fw-semibold">{{ $kontenPublik->nama_file }}</p>
                        <p class="text-muted small">{{ $kontenPublik->ukuran_format }}</p>
                        <a href="{{ $kontenPublik->file_url }}" target="_blank" class="btn btn-sm btn-outline-primary mt-2">
                            <i class="bi bi-download"></i> Unduh File
                        </a>
                    </div>
                @else
                    <div class="py-3">
                        <i class="bi bi-file-earmark" style="font-size:3rem;color:var(--primary);"></i>
                        <p class="mt-2 mb-0">{{ $kontenPublik->nama_file }}</p>
                        <a href="{{ $kontenPublik->file_url }}" target="_blank" class="btn btn-sm btn-outline-primary mt-2">
                            <i class="bi bi-download"></i> Unduh
                        </a>
                    </div>
                @endif
            </div>
        </div>
        @endif

        {{-- External URL --}}
        @if($kontenPublik->url_external)
        <div class="detail-card">
            <h5><i class="bi bi-link-45deg"></i> URL Eksternal</h5>
            @if(Str::contains($kontenPublik->url_external, ['youtube.com', 'youtu.be']))
                @php
                    preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $kontenPublik->url_external, $m);
                    $ytId = $m[1] ?? null;
                @endphp
                @if($ytId)
                    <div class="ratio ratio-16x9" style="border-radius:10px;overflow:hidden;">
                        <iframe src="https://www.youtube.com/embed/{{ $ytId }}" allowfullscreen></iframe>
                    </div>
                @endif
            @else
                <a href="{{ $kontenPublik->url_external }}" target="_blank" rel="noopener" class="btn btn-outline-primary">
                    <i class="bi bi-box-arrow-up-right"></i> {{ $kontenPublik->url_external }}
                </a>
            @endif
        </div>
        @endif
    </div>

    <div class="col-lg-4">
        {{-- Info --}}
        <div class="detail-card">
            <h5><i class="bi bi-info-circle"></i> Informasi</h5>
            <dl class="info-grid">
                <dt>Status</dt>
                <dd>
                    <span class="badge-status {{ $kontenPublik->aktif ? 'badge-aktif' : 'badge-nonaktif' }}">
                        {{ $kontenPublik->aktif ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </dd>
                <dt>Unggulan</dt>
                <dd>{{ $kontenPublik->unggulan ? 'Ya' : 'Tidak' }}</dd>
                <dt>Urutan</dt>
                <dd>{{ $kontenPublik->urutan }}</dd>
                @if($kontenPublik->nama_file)
                <dt>Nama File</dt>
                <dd style="word-break:break-all;">{{ $kontenPublik->nama_file }}</dd>
                @endif
                @if($kontenPublik->ukuran_file)
                <dt>Ukuran</dt>
                <dd>{{ $kontenPublik->ukuran_format }}</dd>
                @endif
                <dt>Dibuat</dt>
                <dd>{{ $kontenPublik->created_at->translatedFormat('d M Y H:i') }}</dd>
                <dt>Diperbarui</dt>
                <dd>{{ $kontenPublik->updated_at->translatedFormat('d M Y H:i') }}</dd>
            </dl>
        </div>

        {{-- Thumbnail --}}
        @if($kontenPublik->thumbnail)
        <div class="detail-card">
            <h5><i class="bi bi-image"></i> Thumbnail</h5>
            <img src="{{ $kontenPublik->thumbnail_url }}" alt="Thumbnail" style="width:100%;border-radius:8px;">
        </div>
        @endif

        {{-- Actions --}}
        <div class="detail-card">
            <h5><i class="bi bi-gear"></i> Aksi</h5>
            <div class="d-grid gap-2">
                <form action="{{ route('admin.halaman-publik.toggle-aktif', $kontenPublik) }}" method="POST">
                    @csrf @method('PATCH')
                    <button class="btn w-100 {{ $kontenPublik->aktif ? 'btn-outline-warning' : 'btn-outline-success' }} btn-action">
                        <i class="bi {{ $kontenPublik->aktif ? 'bi-eye-slash' : 'bi-eye' }}"></i>
                        {{ $kontenPublik->aktif ? 'Nonaktifkan' : 'Aktifkan' }}
                    </button>
                </form>
                <form action="{{ route('admin.halaman-publik.destroy', $kontenPublik) }}" method="POST" data-confirm="Hapus konten ini?">
                    @csrf @method('DELETE')
                    <button class="btn btn-outline-danger w-100 btn-action">
                        <i class="bi bi-trash3"></i> Hapus Konten
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
