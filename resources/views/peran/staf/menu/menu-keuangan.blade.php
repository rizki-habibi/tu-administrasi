{{-- Menu Khusus: Keuangan --}}
<div class="nav-group {{ request()->routeIs('staf.keuangan.*') ? 'open' : '' }}">
    <div class="nav-group-label" data-toggle="nav-group"><span>Catatan Keuangan</span><i class="bi bi-chevron-down"></i></div>
    <div class="nav-group-items">
        <div class="nav-item">
            <a class="nav-link {{ request()->routeIs('staf.keuangan.index') ? 'active' : '' }}" href="{{ route('staf.keuangan.index') }}">
                <i class="bi bi-cash-coin icon"></i> <span>Daftar Transaksi</span>
            </a>
        </div>
        <div class="nav-item">
            <a class="nav-link {{ request()->routeIs('staf.keuangan.create') ? 'active' : '' }}" href="{{ route('staf.keuangan.create') }}">
                <i class="bi bi-plus-circle icon"></i> <span>Tambah Transaksi</span>
            </a>
        </div>
    </div>
</div>

<div class="nav-group {{ request()->routeIs('staf.laporan.*') || request()->routeIs('staf.dokumen.*') ? 'open' : '' }}">
    <div class="nav-group-label" data-toggle="nav-group"><span>Laporan & Dokumen</span><i class="bi bi-chevron-down"></i></div>
    <div class="nav-group-items">
        <div class="nav-item {{ request()->routeIs('staf.laporan.*') ? 'open' : '' }}">
            <a class="nav-link {{ request()->routeIs('staf.laporan.*') ? 'active' : '' }}" data-toggle="submenu">
                <i class="bi bi-journal-text icon"></i> <span>Laporan</span> <i class="bi bi-chevron-right arrow"></i>
            </a>
            <div class="submenu">
                <a href="{{ route('staf.laporan.index', ['kategori' => 'keuangan']) }}" class="sub-link {{ request()->routeIs('staf.laporan.index') && request('kategori') == 'keuangan' ? 'active' : '' }}">Lap. Keuangan</a>
                <a href="{{ route('staf.laporan.index') }}" class="sub-link {{ request()->routeIs('staf.laporan.index') && !request('kategori') ? 'active' : '' }}">Semua Laporan</a>
                <a href="{{ route('staf.laporan.create') }}" class="sub-link {{ request()->routeIs('staf.laporan.create') ? 'active' : '' }}">Buat Laporan</a>
            </div>
        </div>
        <div class="nav-item {{ request()->routeIs('staf.dokumen.*') ? 'open' : '' }}">
            <a class="nav-link {{ request()->routeIs('staf.dokumen.*') ? 'active' : '' }}" data-toggle="submenu">
                <i class="bi bi-file-earmark-text-fill icon"></i> <span>Dokumen</span> <i class="bi bi-chevron-right arrow"></i>
            </a>
            <div class="submenu">
                <a href="{{ route('staf.dokumen.index', ['kategori' => 'keuangan']) }}" class="sub-link {{ request('kategori') == 'keuangan' ? 'active' : '' }}">Dok. Keuangan</a>
                <a href="{{ route('staf.dokumen.index') }}" class="sub-link {{ request()->routeIs('staf.dokumen.index') && !request('kategori') ? 'active' : '' }}">Semua Dokumen</a>
            </div>
        </div>
    </div>
</div>
