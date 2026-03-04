{{-- Menu Khusus: Keuangan --}}
<div class="nav-label">Keuangan</div>

{{-- Laporan Keuangan --}}
<div class="nav-item {{ request()->routeIs('staf.laporan.*') ? 'open' : '' }}">
    <a class="nav-link {{ request()->routeIs('staf.laporan.*') ? 'active' : '' }}" data-toggle="submenu">
        <i class="bi bi-cash-coin icon"></i> <span>Laporan Keuangan</span> <i class="bi bi-chevron-right arrow"></i>
    </a>
    <div class="submenu">
        <a href="{{ route('staf.laporan.index', ['kategori' => 'keuangan']) }}" class="sub-link {{ request()->routeIs('staf.laporan.index') && request('kategori') == 'keuangan' ? 'active' : '' }}">Lap. Keuangan</a>
        <a href="{{ route('staf.laporan.index') }}" class="sub-link {{ request()->routeIs('staf.laporan.index') && !request('kategori') ? 'active' : '' }}">Semua Laporan</a>
        <a href="{{ route('staf.laporan.create') }}" class="sub-link {{ request()->routeIs('staf.laporan.create') ? 'active' : '' }}">Buat Laporan</a>
    </div>
</div>

{{-- Dokumen Keuangan --}}
<div class="nav-item {{ request()->routeIs('staf.dokumen.*') ? 'open' : '' }}">
    <a class="nav-link {{ request()->routeIs('staf.dokumen.*') ? 'active' : '' }}" data-toggle="submenu">
        <i class="bi bi-file-earmark-text-fill icon"></i> <span>Dokumen Keuangan</span> <i class="bi bi-chevron-right arrow"></i>
    </a>
    <div class="submenu">
        <a href="{{ route('staf.dokumen.index', ['kategori' => 'keuangan']) }}" class="sub-link {{ request('kategori') == 'keuangan' ? 'active' : '' }}">Dok. Keuangan</a>
        <a href="{{ route('staf.dokumen.index') }}" class="sub-link {{ request()->routeIs('staf.dokumen.index') && !request('kategori') ? 'active' : '' }}">Semua Dokumen</a>
    </div>
</div>
