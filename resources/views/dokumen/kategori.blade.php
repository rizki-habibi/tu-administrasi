@extends('layouts.dokumen')

@section('title', ($info['label'] ?? 'Kategori') . ' — Dokumen')

@section('content')
    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
        <div>
            <h4 style="font-weight:700;color:#1e1b4b;margin-bottom:4px;">
                <i class="bi {{ $info['icon'] }} me-2" style="color:var(--dk-primary);"></i>{{ $info['label'] }}
            </h4>
            <p style="font-size:.82rem;color:#64748b;margin:0;">
                Menampilkan {{ $items->total() }} konten dalam kategori ini.
            </p>
        </div>
        <form action="{{ route('dokumen.kategori', $kategori) }}" method="GET" class="d-flex gap-2" style="max-width:300px;">
            <div class="input-group input-group-sm">
                <input type="text" name="cari" class="form-control" placeholder="Cari..." value="{{ $cari }}" style="border-radius:8px 0 0 8px;font-size:.8rem;">
                <button class="btn btn-primary" style="border-radius:0 8px 8px 0;font-size:.8rem;"><i class="bi bi-search"></i></button>
            </div>
        </form>
    </div>

    {{-- Items Grid --}}
    @if($items->isNotEmpty())
        <div class="row g-3">
            @foreach($items as $item)
                <div class="col-md-6 col-lg-4">
                    <div class="card card-dokumen h-100">
                        @if($item->thumbnail)
                            <img src="{{ asset('storage/' . $item->thumbnail) }}" class="card-img-top" style="height:160px;object-fit:cover;" alt="{{ $item->judul }}">
                        @endif
                        <div class="card-body d-flex flex-column">
                            <h6 class="card-title">{{ $item->judul }}</h6>
                            <p class="card-text flex-grow-1">{{ Str::limit($item->deskripsi, 100) }}</p>

                            @if($item->tipe === 'dokumen' && $item->nama_file)
                                <div class="mb-2" style="font-size:.7rem;color:#94a3b8;">
                                    <i class="bi bi-paperclip me-1"></i>{{ $item->nama_file }}
                                    @if($item->ukuran_file)
                                        ({{ number_format($item->ukuran_file / 1024, 0) }} KB)
                                    @endif
                                </div>
                            @endif

                            <div class="d-flex gap-2 mt-auto">
                                <a href="{{ route('dokumen.show', $item->id) }}" class="btn btn-sm btn-outline-primary" style="border-radius:8px;font-size:.72rem;">
                                    <i class="bi bi-eye me-1"></i>Detail
                                </a>
                                @if($item->path_file)
                                    <a href="{{ asset('storage/' . $item->path_file) }}" class="btn btn-sm btn-outline-secondary" style="border-radius:8px;font-size:.72rem;" target="_blank">
                                        <i class="bi bi-download me-1"></i>Unduh
                                    </a>
                                @endif
                                @if($item->url_external)
                                    <a href="{{ $item->url_external }}" class="btn btn-sm btn-outline-info" style="border-radius:8px;font-size:.72rem;" target="_blank" rel="noopener noreferrer">
                                        <i class="bi bi-box-arrow-up-right me-1"></i>Link
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="d-flex justify-content-center mt-4">
            {{ $items->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <i class="bi bi-folder2-open" style="font-size:3rem;color:#cbd5e1;"></i>
            <h5 style="color:#94a3b8;margin-top:12px;font-size:1rem;">Belum ada konten di kategori ini</h5>
            <p style="font-size:.82rem;color:#94a3b8;">
                @if($cari)
                    Tidak ditemukan hasil untuk "{{ $cari }}".
                    <a href="{{ route('dokumen.kategori', $kategori) }}">Reset pencarian</a>
                @else
                    Konten akan muncul setelah admin menambahkan data.
                @endif
            </p>
        </div>
    @endif
@endsection
