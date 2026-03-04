{{-- Menu Khusus: Pramu Bakti --}}
<div class="nav-label">Pramu Bakti</div>

{{-- Laporan Kerja --}}
<div class="nav-item {{ request()->routeIs('staf.laporan.*') ? 'open' : '' }}">
    <a class="nav-link {{ request()->routeIs('staf.laporan.*') ? 'active' : '' }}" data-toggle="submenu">
        <i class="bi bi-journal-text icon"></i> <span>Laporan Kerja</span> <i class="bi bi-chevron-right arrow"></i>
    </a>
    <div class="submenu">
        <a href="{{ route('staf.laporan.index') }}" class="sub-link {{ request()->routeIs('staf.laporan.index') ? 'active' : '' }}">Semua Laporan</a>
        <a href="{{ route('staf.laporan.create') }}" class="sub-link {{ request()->routeIs('staf.laporan.create') ? 'active' : '' }}">Buat Laporan</a>
    </div>
</div>

{{-- Kerusakan & Perbaikan --}}
<div class="nav-item {{ request()->routeIs('staf.inventaris.*') ? 'open' : '' }}">
    <a class="nav-link {{ request()->routeIs('staf.inventaris.*') ? 'active' : '' }}" data-toggle="submenu">
        <i class="bi bi-tools icon"></i> <span>Kerusakan & Perbaikan</span> <i class="bi bi-chevron-right arrow"></i>
    </a>
    <div class="submenu">
        <a href="{{ route('staf.inventaris.index', ['status' => 'rusak']) }}" class="sub-link {{ request('status') == 'rusak' ? 'active' : '' }}">Laporan Kerusakan</a>
    </div>
</div>
