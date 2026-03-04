{{-- Menu Khusus: Persuratan --}}
<div class="nav-label">Persuratan</div>

{{-- Surat Menyurat --}}
<div class="nav-item {{ request()->routeIs('staf.surat.*') ? 'open' : '' }}">
    <a class="nav-link {{ request()->routeIs('staf.surat.*') ? 'active' : '' }}" data-toggle="submenu">
        <i class="bi bi-envelope-paper-fill icon"></i> <span>Surat Menyurat</span> <i class="bi bi-chevron-right arrow"></i>
    </a>
    <div class="submenu">
        <a href="{{ route('staf.surat.index') }}" class="sub-link {{ request()->routeIs('staf.surat.index') && !request('jenis') ? 'active' : '' }}">Semua Surat</a>
        <a href="{{ route('staf.surat.index', ['jenis' => 'masuk']) }}" class="sub-link {{ request('jenis') == 'masuk' ? 'active' : '' }}">Surat Masuk</a>
        <a href="{{ route('staf.surat.index', ['jenis' => 'keluar']) }}" class="sub-link {{ request('jenis') == 'keluar' ? 'active' : '' }}">Surat Keluar</a>
        <a href="{{ route('staf.surat.create') }}" class="sub-link {{ request()->routeIs('staf.surat.create') ? 'active' : '' }}">Buat Surat</a>
    </div>
</div>

{{-- Arsip Dokumen --}}
<div class="nav-item {{ request()->routeIs('staf.dokumen.*') ? 'open' : '' }}">
    <a class="nav-link {{ request()->routeIs('staf.dokumen.*') ? 'active' : '' }}" data-toggle="submenu">
        <i class="bi bi-archive-fill icon"></i> <span>Arsip Dokumen</span> <i class="bi bi-chevron-right arrow"></i>
    </a>
    <div class="submenu">
        <a href="{{ route('staf.dokumen.index', ['kategori' => 'surat']) }}" class="sub-link {{ request('kategori') == 'surat' ? 'active' : '' }}">Arsip Surat</a>
        <a href="{{ route('staf.dokumen.index') }}" class="sub-link {{ request()->routeIs('staf.dokumen.index') && !request('kategori') ? 'active' : '' }}">Semua Dokumen</a>
    </div>
</div>
