{{-- Menu Khusus: Perpustakaan --}}
<div class="nav-group {{ request()->routeIs('staf.buku.*') || request()->routeIs('staf.peminjaman-buku.*') ? 'open' : '' }}">
    <div class="nav-group-label" data-toggle="nav-group"><span>Manajemen Buku</span><i class="bi bi-chevron-down"></i></div>
    <div class="nav-group-items">
        <div class="nav-item {{ request()->routeIs('staf.buku.*') ? 'open' : '' }}">
            <a class="nav-link {{ request()->routeIs('staf.buku.*') ? 'active' : '' }}" data-toggle="submenu">
                <i class="bi bi-book-half icon"></i> <span>Koleksi Buku</span> <i class="bi bi-chevron-right arrow"></i>
            </a>
            <div class="submenu">
                <a href="{{ route('staf.buku.index') }}" class="sub-link {{ request()->routeIs('staf.buku.index') ? 'active' : '' }}">Daftar Buku</a>
                <a href="{{ route('staf.buku.create') }}" class="sub-link {{ request()->routeIs('staf.buku.create') ? 'active' : '' }}">Tambah Buku</a>
                <a href="{{ route('staf.buku.ekspor') }}" class="sub-link {{ request()->routeIs('staf.buku.ekspor') ? 'active' : '' }}">Ekspor CSV</a>
            </div>
        </div>
        <div class="nav-item {{ request()->routeIs('staf.peminjaman-buku.*') ? 'open' : '' }}">
            <a class="nav-link {{ request()->routeIs('staf.peminjaman-buku.*') ? 'active' : '' }}" data-toggle="submenu">
                <i class="bi bi-arrow-left-right icon"></i> <span>Peminjaman</span> <i class="bi bi-chevron-right arrow"></i>
            </a>
            <div class="submenu">
                <a href="{{ route('staf.peminjaman-buku.index') }}" class="sub-link {{ request()->routeIs('staf.peminjaman-buku.index') ? 'active' : '' }}">Daftar Peminjaman</a>
                <a href="{{ route('staf.peminjaman-buku.create') }}" class="sub-link {{ request()->routeIs('staf.peminjaman-buku.create') ? 'active' : '' }}">Pinjam Buku</a>
            </div>
        </div>
    </div>
</div>

<div class="nav-group {{ request()->routeIs('staf.dokumen.*') || request()->routeIs('staf.laporan.*') ? 'open' : '' }}">
    <div class="nav-group-label" data-toggle="nav-group"><span>Laporan & Dokumen</span><i class="bi bi-chevron-down"></i></div>
    <div class="nav-group-items">
        <div class="nav-item {{ request()->routeIs('staf.dokumen.*') ? 'open' : '' }}">
            <a class="nav-link {{ request()->routeIs('staf.dokumen.*') ? 'active' : '' }}" data-toggle="submenu">
                <i class="bi bi-folder2-open icon"></i> <span>Koleksi Dokumen</span> <i class="bi bi-chevron-right arrow"></i>
            </a>
            <div class="submenu">
                <a href="{{ route('staf.dokumen.index') }}" class="sub-link {{ request()->routeIs('staf.dokumen.index') ? 'active' : '' }}">Koleksi Dokumen</a>
            </div>
        </div>
        <div class="nav-item {{ request()->routeIs('staf.laporan.*') ? 'open' : '' }}">
            <a class="nav-link {{ request()->routeIs('staf.laporan.*') ? 'active' : '' }}" data-toggle="submenu">
                <i class="bi bi-journal-text icon"></i> <span>Laporan</span> <i class="bi bi-chevron-right arrow"></i>
            </a>
            <div class="submenu">
                <a href="{{ route('staf.laporan.index') }}" class="sub-link {{ request()->routeIs('staf.laporan.index') ? 'active' : '' }}">Semua Laporan</a>
                <a href="{{ route('staf.laporan.create') }}" class="sub-link {{ request()->routeIs('staf.laporan.create') ? 'active' : '' }}">Buat Laporan</a>
            </div>
        </div>
    </div>
</div>
