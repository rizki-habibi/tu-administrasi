@extends('layouts.dokumen')

@section('title', $item->judul . ' — Dokumen')

@section('content')
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb" style="font-size:.78rem;">
            <li class="breadcrumb-item"><a href="{{ route('dokumen.beranda') }}" style="color:var(--dk-primary);text-decoration:none;">Beranda</a></li>
            @if(isset($kategoriMenu[$item->kategori]))
                <li class="breadcrumb-item"><a href="{{ route('dokumen.kategori', $item->kategori) }}" style="color:var(--dk-primary);text-decoration:none;">{{ $kategoriMenu[$item->kategori]['label'] }}</a></li>
            @endif
            <li class="breadcrumb-item active">{{ Str::limit($item->judul, 40) }}</li>
        </ol>
    </nav>

    <div class="card" style="border:none;border-radius:16px;box-shadow:0 4px 20px rgba(0,0,0,.06);overflow:hidden;">
        @if($item->thumbnail)
            <img src="{{ asset('storage/' . $item->thumbnail) }}" class="card-img-top" style="max-height:320px;object-fit:cover;" alt="{{ $item->judul }}">
        @endif
        <div class="card-body" style="padding:28px;">
            <div class="d-flex align-items-start justify-content-between flex-wrap gap-3 mb-3">
                <div>
                    <span class="badge-kategori mb-2 d-inline-block">{{ ucfirst(str_replace('_', ' ', $item->kategori)) }}</span>
                    @if($item->unggulan)
                        <span class="badge bg-warning text-dark" style="font-size:.65rem;border-radius:20px;padding:3px 10px;">
                            <i class="bi bi-star-fill me-1"></i>Unggulan
                        </span>
                    @endif
                    <h3 style="font-weight:700;color:#1e1b4b;margin-top:8px;font-size:1.3rem;">{{ $item->judul }}</h3>
                </div>
                <div style="font-size:.75rem;color:#94a3b8;text-align:right;">
                    <div><i class="bi bi-calendar3 me-1"></i>{{ $item->created_at->translatedFormat('d F Y') }}</div>
                    @if($item->pembuat)
                        <div class="mt-1"><i class="bi bi-person me-1"></i>{{ $item->pembuat->nama }}</div>
                    @endif
                </div>
            </div>

            @if($item->deskripsi)
                <div style="font-size:.88rem;color:#475569;line-height:1.7;margin-bottom:18px;padding:14px 18px;background:#f8fafc;border-radius:10px;border-left:3px solid var(--dk-primary);">
                    {{ $item->deskripsi }}
                </div>
            @endif

            @if($item->konten)
                <div class="konten-body" style="font-size:.88rem;color:#334155;line-height:1.8;">
                    {!! $item->konten !!}
                </div>
            @endif

            @if($item->tipe === 'video' && $item->url_external)
                <div class="mt-3">
                    @if(str_contains($item->url_external, 'youtube.com') || str_contains($item->url_external, 'youtu.be'))
                        @php
                            preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([\w-]+)/', $item->url_external, $m);
                            $videoId = $m[1] ?? null;
                        @endphp
                        @if($videoId)
                            <div class="ratio ratio-16x9" style="border-radius:12px;overflow:hidden;">
                                <iframe src="https://www.youtube.com/embed/{{ $videoId }}" allowfullscreen loading="lazy"></iframe>
                            </div>
                        @endif
                    @else
                        <a href="{{ $item->url_external }}" target="_blank" rel="noopener noreferrer" class="btn btn-outline-primary" style="border-radius:10px;">
                            <i class="bi bi-play-circle me-2"></i>Tonton Video
                        </a>
                    @endif
                </div>
            @endif

            @if($item->tipe === 'gambar' && $item->path_file)
                <div class="mt-3">
                    <img src="{{ asset('storage/' . $item->path_file) }}" class="img-fluid rounded-3" alt="{{ $item->judul }}" style="max-height:500px;object-fit:contain;">
                </div>
            @endif

            {{-- Download / External Link --}}
            <div class="d-flex gap-2 mt-4 pt-3" style="border-top:1px solid #e2e8f0;">
                @if($item->path_file && in_array($item->tipe, ['dokumen', 'link']))
                    <a href="{{ asset('storage/' . $item->path_file) }}" class="btn btn-primary" style="border-radius:10px;font-size:.85rem;" target="_blank">
                        <i class="bi bi-download me-2"></i>Unduh File
                        @if($item->nama_file)
                            ({{ $item->nama_file }})
                        @endif
                    </a>
                @endif
                @if($item->url_external && $item->tipe !== 'video')
                    <a href="{{ $item->url_external }}" class="btn btn-outline-primary" style="border-radius:10px;font-size:.85rem;" target="_blank" rel="noopener noreferrer">
                        <i class="bi bi-box-arrow-up-right me-2"></i>Buka Link External
                    </a>
                @endif
                <a href="javascript:history.back()" class="btn btn-outline-secondary" style="border-radius:10px;font-size:.85rem;">
                    <i class="bi bi-arrow-left me-1"></i>Kembali
                </a>
            </div>
        </div>
    </div>
@endsection
