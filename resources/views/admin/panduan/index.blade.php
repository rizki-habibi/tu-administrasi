@extends('peran.admin.app')

@section('judul', 'Pusat Panduan')

@push('styles')
<style>
    .panduan-hub-header { background: linear-gradient(135deg, #312e81 0%, #6366f1 100%); color: #fff; border-radius: 14px; padding: 28px 32px; margin-bottom: 24px; }
    .panduan-hub-header h1 { margin: 0; font-size: 1.4rem; font-weight: 700; }
    .panduan-hub-header p { margin: 6px 0 0; opacity: .85; font-size: .85rem; }

    .stat-mini { background: #fff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px 18px; text-align: center; }
    .stat-mini .val { font-size: 1.4rem; font-weight: 800; color: #1e293b; }
    .stat-mini .lbl { font-size: .7rem; color: #94a3b8; margin-top: 2px; }

    .doc-card { border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; transition: all .2s; height: 100%; position: relative; overflow: hidden; background: #fff; }
    .doc-card:hover { border-color: #6366f1; box-shadow: 0 4px 20px rgba(99,102,241,.12); transform: translateY(-2px); }
    .doc-card .doc-icon { width: 44px; height: 44px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; color: #fff; margin-bottom: 12px; }
    .doc-card .doc-logo { width: 44px; height: 44px; border-radius: 10px; object-fit: cover; margin-bottom: 12px; border: 1px solid #e2e8f0; }
    .doc-card .doc-title { font-size: .92rem; font-weight: 600; color: #1e293b; margin-bottom: 4px; }
    .doc-card .doc-desc { font-size: .78rem; color: #64748b; line-height: 1.5; margin-bottom: 12px; }
    .doc-card .doc-meta { font-size: .72rem; color: #94a3b8; display: flex; gap: 12px; align-items: center; flex-wrap: wrap; }
    .doc-card .doc-badge { position: absolute; top: 12px; right: 12px; font-size: .65rem; padding: 2px 8px; border-radius: 20px; font-weight: 600; }
    .doc-card .doc-actions { display: flex; gap: 6px; margin-top: 12px; position: relative; z-index: 2; }
    .doc-card .doc-actions .btn { font-size: .72rem; padding: 4px 10px; border-radius: 8px; }

    .filter-bar { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px 20px; margin-bottom: 20px; }
    .filter-bar .form-control, .filter-bar .form-select { font-size: .82rem; border-radius: 8px; }

    .kat-badge { font-size: .65rem; padding: 2px 8px; border-radius: 20px; font-weight: 600; }

    .empty-box { text-align: center; padding: 50px 20px; color: #94a3b8; }
    .empty-box i { font-size: 2.5rem; margin-bottom: 10px; display: block; }
</style>
@endpush

@section('konten')
<div class="panduan-hub-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h1><i class="bi bi-book me-2"></i>Pusat Panduan</h1>
            <p>Kelola dokumentasi & panduan sistem untuk semua pengguna</p>
        </div>
        <a href="{{ route('admin.panduan.create') }}" class="btn btn-sm" style="background:rgba(255,255,255,.2);color:#fff;border:1px solid rgba(255,255,255,.3);">
            <i class="bi bi-plus-lg me-1"></i> Tambah Panduan
        </a>
    </div>
</div>

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-mini">
            <div class="val">{{ $stats['total'] }}</div>
            <div class="lbl">Total Panduan</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-mini">
            <div class="val" style="color:#10b981;">{{ $stats['aktif'] }}</div>
            <div class="lbl">Aktif</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-mini">
            <div class="val" style="color:#6366f1;">{{ $stats['panduan'] }}</div>
            <div class="lbl">Panduan</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-mini">
            <div class="val" style="color:#f59e0b;">{{ $stats['dokumentasi'] }}</div>
            <div class="lbl">Dokumentasi</div>
        </div>
    </div>
</div>

{{-- Flash --}}
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" style="font-size:.85rem;border-radius:10px;">
    <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
    <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- Filter Bar --}}
<div class="filter-bar">
    <form method="GET" action="{{ route('admin.panduan.index') }}">
        <div class="row g-2 align-items-end">
            <div class="col-md-5">
                <label class="form-label" style="font-size:.75rem;font-weight:600;color:#64748b;">Pencarian</label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control" name="cari" value="{{ request('cari') }}" placeholder="Cari judul, deskripsi, konten...">
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label" style="font-size:.75rem;font-weight:600;color:#64748b;">Kategori</label>
                <select class="form-select form-select-sm" name="kategori">
                    <option value="">Semua Kategori</option>
                    <option value="panduan" {{ request('kategori') == 'panduan' ? 'selected' : '' }}>Panduan</option>
                    <option value="dokumentasi" {{ request('kategori') == 'dokumentasi' ? 'selected' : '' }}>Dokumentasi</option>
                    <option value="changelog" {{ request('kategori') == 'changelog' ? 'selected' : '' }}>Changelog</option>
                    <option value="referensi" {{ request('kategori') == 'referensi' ? 'selected' : '' }}>Referensi</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label" style="font-size:.75rem;font-weight:600;color:#64748b;">Visibilitas</label>
                <select class="form-select form-select-sm" name="visibilitas">
                    <option value="">Semua</option>
                    <option value="semua" {{ request('visibilitas') == 'semua' ? 'selected' : '' }}>Publik</option>
                    <option value="admin" {{ request('visibilitas') == 'admin' ? 'selected' : '' }}>Admin Only</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-sm btn-primary w-100"><i class="bi bi-funnel me-1"></i> Filter</button>
                <a href="{{ route('admin.panduan.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
            </div>
        </div>
    </form>
</div>

{{-- Document Cards --}}
@if($panduan->isNotEmpty())
<div class="row g-3">
    @foreach($panduan as $dok)
    <div class="col-md-6 col-xl-4">
        <div class="doc-card">
            @if($dok->versi)
            <span class="doc-badge" style="background: {{ $dok->warna }}20; color: {{ $dok->warna }};">{{ $dok->versi }}</span>
            @endif

            @if($dok->logo)
                <img src="{{ $dok->logo_url }}" alt="{{ $dok->judul }}" class="doc-logo">
            @else
                <div class="doc-icon" style="background: {{ $dok->warna }};">
                    <i class="bi {{ $dok->ikon }}"></i>
                </div>
            @endif

            <div class="doc-title">{{ $dok->judul }}</div>
            <div class="doc-desc">{{ Str::limit($dok->deskripsi, 100) }}</div>

            <div class="doc-meta">
                <span><i class="bi bi-calendar3 me-1"></i>{{ $dok->created_at->translatedFormat('d M Y') }}</span>
                <span class="kat-badge" style="background: {{ ['panduan'=>'#eef2ff','dokumentasi'=>'#fef3c7','changelog'=>'#ecfdf5','referensi'=>'#fce7f3'][$dok->kategori] ?? '#f1f5f9' }}; color: {{ ['panduan'=>'#6366f1','dokumentasi'=>'#d97706','changelog'=>'#059669','referensi'=>'#ec4899'][$dok->kategori] ?? '#64748b' }};">{{ ucfirst($dok->kategori) }}</span>
                @if($dok->visibilitas === 'admin')
                <span class="kat-badge" style="background:#fef2f2;color:#ef4444;"><i class="bi bi-lock-fill me-1"></i>Admin</span>
                @endif
                @if(!$dok->aktif)
                <span class="kat-badge" style="background:#f1f5f9;color:#94a3b8;">Nonaktif</span>
                @endif
            </div>

            <div class="doc-actions">
                <a href="{{ route('admin.panduan.show', $dok) }}" class="btn btn-outline-primary"><i class="bi bi-eye me-1"></i>Baca</a>
                <a href="{{ route('admin.panduan.edit', $dok) }}" class="btn btn-outline-warning"><i class="bi bi-pencil me-1"></i>Edit</a>
                <form action="{{ route('admin.panduan.destroy', $dok) }}" method="POST" onsubmit="return confirm('Yakin hapus panduan ini?');">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger"><i class="bi bi-trash"></i></button>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="mt-4">{{ $panduan->links() }}</div>
@else
<div class="empty-box">
    <i class="bi bi-journal-x"></i>
    <h6 class="fw-bold" style="color:#475569;">Belum ada panduan</h6>
    <p style="font-size:.82rem;">Mulai tambahkan panduan untuk membantu pengguna memahami fitur sistem.</p>
    <a href="{{ route('admin.panduan.create') }}" class="btn btn-sm btn-primary mt-2"><i class="bi bi-plus-lg me-1"></i> Tambah Panduan Pertama</a>
</div>
@endif
@endsection
