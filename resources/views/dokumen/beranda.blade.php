@extends('layouts.dokumen')

@section('title', 'Dokumen & Kinerja — Beranda')

@section('content')
    {{-- Hero --}}
    <div class="mb-4">
        <h4 style="font-weight:700;color:#1e1b4b;">Dokumen & Kinerja Publik</h4>
        <p style="font-size:.85rem;color:#64748b;margin-bottom:0;">Portal informasi publik SMA Negeri 2 Jember. Telusuri dokumen, kinerja, dan informasi sekolah.</p>
    </div>

    {{-- Statistik Ringkasan --}}
    <div class="row g-3 mb-4">
        @php
            $totalKonten = collect($statistik)->sum();
            $totalUnggulan = $unggulan->count();
            $totalKategori = collect($statistik)->filter(fn($v) => $v > 0)->count();
        @endphp
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:rgba(99,102,241,.1);color:var(--dk-primary);"><i class="bi bi-file-earmark-text"></i></div>
                <div class="stat-value">{{ $totalKonten }}</div>
                <div class="stat-label">Total Konten</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:rgba(245,158,11,.1);color:#f59e0b;"><i class="bi bi-star-fill"></i></div>
                <div class="stat-value">{{ $totalUnggulan }}</div>
                <div class="stat-label">Konten Unggulan</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:rgba(16,185,129,.1);color:#10b981;"><i class="bi bi-grid-3x3-gap-fill"></i></div>
                <div class="stat-value">{{ $totalKategori }}</div>
                <div class="stat-label">Kategori Aktif</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:rgba(239,68,68,.1);color:#ef4444;"><i class="bi bi-archive-fill"></i></div>
                <div class="stat-value">{{ collect($statistik)->get('dokumen', 0) }}</div>
                <div class="stat-label">File Dokumen</div>
            </div>
        </div>
    </div>

    {{-- Konten Unggulan --}}
    @if($unggulan->isNotEmpty())
        <h5 style="font-weight:600;font-size:.95rem;margin-bottom:14px;"><i class="bi bi-star-fill text-warning me-2"></i>Konten Unggulan</h5>
        <div class="row g-3 mb-4">
            @foreach($unggulan as $item)
                <div class="col-md-6 col-lg-4">
                    <div class="card card-dokumen h-100">
                        @if($item->thumbnail)
                            <img src="{{ asset('storage/' . $item->thumbnail) }}" class="card-img-top" style="height:160px;object-fit:cover;" alt="{{ $item->judul }}">
                        @endif
                        <div class="card-body">
                            <span class="badge-kategori mb-2 d-inline-block">{{ ucfirst(str_replace('_', ' ', $item->kategori)) }}</span>
                            <h6 class="card-title">{{ $item->judul }}</h6>
                            <p class="card-text">{{ Str::limit($item->deskripsi, 100) }}</p>
                            <a href="{{ route('dokumen.show', $item->id) }}" class="btn btn-sm btn-outline-primary" style="border-radius:8px;font-size:.75rem;">
                                <i class="bi bi-eye me-1"></i>Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- Konten Per Kategori --}}
    @foreach($konten as $kategori => $items)
        @if($items->isNotEmpty())
            <div class="d-flex align-items-center justify-content-between mb-3 mt-4">
                <h5 style="font-weight:600;font-size:.95rem;margin:0;">
                    @if(isset($kategoriMenu[$kategori]))
                        <i class="bi {{ $kategoriMenu[$kategori]['icon'] }} me-2" style="color:var(--dk-primary);"></i>{{ $kategoriMenu[$kategori]['label'] }}
                    @else
                        {{ ucfirst(str_replace('_', ' ', $kategori)) }}
                    @endif
                </h5>
                <a href="{{ route('dokumen.kategori', $kategori) }}" style="font-size:.78rem;color:var(--dk-primary);text-decoration:none;font-weight:500;">
                    Lihat Semua <i class="bi bi-chevron-right" style="font-size:.65rem;"></i>
                </a>
            </div>
            <div class="row g-3 mb-2">
                @foreach($items->take(3) as $item)
                    <div class="col-md-6 col-lg-4">
                        <div class="card card-dokumen h-100">
                            @if($item->thumbnail)
                                <img src="{{ asset('storage/' . $item->thumbnail) }}" class="card-img-top" style="height:140px;object-fit:cover;" alt="{{ $item->judul }}">
                            @endif
                            <div class="card-body">
                                <h6 class="card-title">{{ $item->judul }}</h6>
                                <p class="card-text">{{ Str::limit($item->deskripsi, 80) }}</p>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('dokumen.show', $item->id) }}" class="btn btn-sm btn-outline-primary" style="border-radius:8px;font-size:.72rem;">
                                        <i class="bi bi-eye me-1"></i>Detail
                                    </a>
                                    @if($item->path_file)
                                        <a href="{{ asset('storage/' . $item->path_file) }}" class="btn btn-sm btn-outline-secondary" style="border-radius:8px;font-size:.72rem;" target="_blank">
                                            <i class="bi bi-download me-1"></i>Unduh
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    @endforeach

    @if($konten->isEmpty() && $unggulan->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-journal-x" style="font-size:3rem;color:#cbd5e1;"></i>
            <h5 style="color:#94a3b8;margin-top:12px;font-size:1rem;">Belum ada konten tersedia</h5>
            <p style="font-size:.82rem;color:#94a3b8;">Konten akan ditampilkan setelah admin menambahkan data.</p>
        </div>
    @endif
@endsection
