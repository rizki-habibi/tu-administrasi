{{-- Menu Khusus: Pramu Bakti --}}
<div class="nav-group {{ request()->routeIs('staf.pemeliharaan.*') ? 'open' : '' }}">
    <div class="nav-group-label" data-toggle="nav-group"><span>Pemeliharaan</span><i class="bi bi-chevron-down"></i></div>
    <div class="nav-group-items">
        <div class="nav-item">
            <a class="nav-link {{ request()->routeIs('staf.pemeliharaan.index') ? 'active' : '' }}" href="{{ route('staf.pemeliharaan.index') }}">
                <i class="bi bi-tools icon"></i> <span>Daftar Pemeliharaan</span>
            </a>
        </div>
        <div class="nav-item">
            <a class="nav-link {{ request()->routeIs('staf.pemeliharaan.create') ? 'active' : '' }}" href="{{ route('staf.pemeliharaan.create') }}">
                <i class="bi bi-plus-circle icon"></i> <span>Buat Laporan</span>
            </a>
        </div>
    </div>
</div>

<div class="nav-group {{ request()->routeIs('staf.laporan.*') || request()->routeIs('staf.inventaris.*') ? 'open' : '' }}">
    <div class="nav-group-label" data-toggle="nav-group"><span>Laporan & Inventaris</span><i class="bi bi-chevron-down"></i></div>
    <div class="nav-group-items">
        <div class="nav-item {{ request()->routeIs('staf.laporan.*') ? 'open' : '' }}">
            <a class="nav-link {{ request()->routeIs('staf.laporan.*') ? 'active' : '' }}" data-toggle="submenu">
                <i class="bi bi-journal-text icon"></i> <span>Laporan Kerja</span> <i class="bi bi-chevron-right arrow"></i>
            </a>
            <div class="submenu">
                <a href="{{ route('staf.laporan.index') }}" class="sub-link {{ request()->routeIs('staf.laporan.index') ? 'active' : '' }}">Semua Laporan</a>
                <a href="{{ route('staf.laporan.create') }}" class="sub-link {{ request()->routeIs('staf.laporan.create') ? 'active' : '' }}">Buat Laporan</a>
            </div>
        </div>
        <div class="nav-item {{ request()->routeIs('staf.inventaris.*') ? 'open' : '' }}">
            <a class="nav-link {{ request()->routeIs('staf.inventaris.*') ? 'active' : '' }}" data-toggle="submenu">
                <i class="bi bi-box-seam icon"></i> <span>Kerusakan & Perbaikan</span> <i class="bi bi-chevron-right arrow"></i>
            </a>
            <div class="submenu">
                <a href="{{ route('staf.inventaris.index', ['status' => 'rusak']) }}" class="sub-link {{ request('status') == 'rusak' ? 'active' : '' }}">Laporan Kerusakan</a>
            </div>
        </div>
    </div>
</div>
