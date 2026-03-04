{{-- Menu Khusus: Perpustakaan --}}
<div class="nav-label">Perpustakaan</div>

{{-- Koleksi & Dokumen --}}
<div class="nav-item {{ request()->routeIs('staf.dokumen.*') ? 'open' : '' }}">
    <a class="nav-link {{ request()->routeIs('staf.dokumen.*') ? 'active' : '' }}" data-toggle="submenu">
        <i class="bi bi-book-half icon"></i> <span>Koleksi & Dokumen</span> <i class="bi bi-chevron-right arrow"></i>
    </a>
    <div class="submenu">
        <a href="{{ route('staf.dokumen.index') }}" class="sub-link {{ request()->routeIs('staf.dokumen.index') ? 'active' : '' }}">Koleksi Dokumen</a>
    </div>
</div>

{{-- Laporan --}}
<div class="nav-item {{ request()->routeIs('staf.laporan.*') ? 'open' : '' }}">
    <a class="nav-link {{ request()->routeIs('staf.laporan.*') ? 'active' : '' }}" data-toggle="submenu">
        <i class="bi bi-journal-text icon"></i> <span>Laporan</span> <i class="bi bi-chevron-right arrow"></i>
    </a>
    <div class="submenu">
        <a href="{{ route('staf.laporan.index') }}" class="sub-link {{ request()->routeIs('staf.laporan.index') ? 'active' : '' }}">Semua Laporan</a>
        <a href="{{ route('staf.laporan.create') }}" class="sub-link {{ request()->routeIs('staf.laporan.create') ? 'active' : '' }}">Buat Laporan</a>
    </div>
</div>
