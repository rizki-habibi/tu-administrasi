@extends('peran.kepala-sekolah.app')

@section('judul', 'Pusat Panduan')

@push('styles')
<style>
    .panduan-hub-header { background: linear-gradient(135deg, #065f46 0%, #10b981 100%); color: #fff; border-radius: 14px; padding: 28px 32px; margin-bottom: 24px; }
    .panduan-hub-header h1 { margin: 0; font-size: 1.4rem; font-weight: 700; }
    .panduan-hub-header p { margin: 6px 0 0; opacity: .85; font-size: .85rem; }

    .search-filter-bar { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px 20px; margin-bottom: 20px; }
    .filter-input { border: 1px solid #e2e8f0; border-radius: 8px; font-size: .82rem; padding: 8px 12px; }
    .filter-input:focus { border-color: #10b981; box-shadow: 0 0 0 3px rgba(16,185,129,.12); outline: none; }

    .doc-card { border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; transition: all .2s; cursor: pointer; height: 100%; position: relative; overflow: hidden; background: #fff; }
    .doc-card:hover { border-color: #10b981; box-shadow: 0 4px 20px rgba(16,185,129,.12); transform: translateY(-2px); }
    .doc-card .doc-icon { width: 44px; height: 44px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; color: #fff; margin-bottom: 12px; }
    .doc-card .doc-logo { width: 44px; height: 44px; border-radius: 10px; object-fit: cover; margin-bottom: 12px; }
    .doc-card .doc-title { font-size: .92rem; font-weight: 600; color: #1e293b; margin-bottom: 4px; }
    .doc-card .doc-desc { font-size: .78rem; color: #64748b; line-height: 1.5; margin-bottom: 12px; }
    .doc-card .doc-meta { font-size: .72rem; color: #94a3b8; display: flex; gap: 12px; align-items: center; }
    .doc-card .doc-badge { position: absolute; top: 12px; right: 12px; font-size: .65rem; padding: 2px 8px; border-radius: 20px; font-weight: 600; }
</style>
@endpush

@section('konten')
<div class="panduan-hub-header">
    <h1><i class="bi bi-book me-2"></i>Pusat Panduan</h1>
    <p>Dokumentasi lengkap & panduan penggunaan Sistem TU Administrasi</p>
</div>

{{-- Search & Filter --}}
<div class="search-filter-bar">
    <form method="GET" action="{{ route('kepala-sekolah.panduan.index') }}" class="row g-2 align-items-end">
        <div class="col-md-6">
            <input type="text" name="cari" class="form-control filter-input" placeholder="Cari panduan..." value="{{ request('cari') }}">
        </div>
        <div class="col-md-3">
            <select name="kategori" class="form-select filter-input">
                <option value="">Semua Kategori</option>
                @foreach(['panduan'=>'Panduan','dokumentasi'=>'Dokumentasi','changelog'=>'Changelog','referensi'=>'Referensi'] as $val => $label)
                    <option value="{{ $val }}" {{ request('kategori') === $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-sm text-white flex-fill" style="background:#10b981;"><i class="bi bi-search me-1"></i> Cari</button>
            <a href="{{ route('kepala-sekolah.panduan.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
        </div>
    </form>
</div>

{{-- Document Cards --}}
<div class="row g-3">
    @forelse($panduan as $p)
    <div class="col-md-6 col-xl-4">
        <div class="doc-card">
            <a href="{{ route('kepala-sekolah.panduan.show', $p) }}" class="stretched-link" style="text-decoration:none;color:inherit;"></a>
            @if($p->versi)<span class="doc-badge" style="background:{{ $p->warna ?? '#10b981' }}20;color:{{ $p->warna ?? '#10b981' }};">{{ $p->versi }}</span>@endif
            @if($p->logo)
                <img src="{{ $p->logo_url }}" alt="{{ $p->judul }}" class="doc-logo">
            @else
                <div class="doc-icon" style="background:{{ $p->warna ?? '#10b981' }};"><i class="bi {{ $p->ikon }}"></i></div>
            @endif
            <div class="doc-title">{{ $p->judul }}</div>
            <div class="doc-desc">{{ Str::limit($p->deskripsi, 100) }}</div>
            <div class="doc-meta">
                <span><i class="bi bi-calendar3 me-1"></i>{{ $p->created_at->translatedFormat('d M Y') }}</span>
                <span><i class="bi bi-tag me-1"></i>{{ ucfirst($p->kategori) }}</span>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="text-center py-5">
            <i class="bi bi-journal-x" style="font-size:3rem;color:#cbd5e1;"></i>
            <p class="text-muted mt-2">Belum ada panduan tersedia.</p>
        </div>
    </div>
    @endforelse
</div>

@if($panduan->hasPages())
<div class="mt-4">{{ $panduan->withQueryString()->links() }}</div>
@endif
@endsection
