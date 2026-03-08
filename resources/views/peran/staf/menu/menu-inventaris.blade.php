{{-- Menu Khusus: Inventaris --}}
<div class="nav-group {{ request()->routeIs('staf.inventaris.*') || request()->routeIs('staf.kerusakan.*') || request()->routeIs('staf.peminjaman.*') || request()->routeIs('staf.dokumen.*') ? 'open' : '' }}">
    <div class="nav-group-label" data-toggle="nav-group"><span>Inventaris</span><i class="bi bi-chevron-down"></i></div>
    <div class="nav-group-items">
        <div class="nav-item {{ request()->routeIs('staf.inventaris.*') ? 'open' : '' }}">
            <a class="nav-link {{ request()->routeIs('staf.inventaris.*') ? 'active' : '' }}" data-toggle="submenu">
                <i class="bi bi-box-seam-fill icon"></i> <span>Data Inventaris</span> <i class="bi bi-chevron-right arrow"></i>
            </a>
            <div class="submenu">
                <a href="{{ route('staf.inventaris.index') }}" class="sub-link {{ request()->routeIs('staf.inventaris.index') && !request('kondisi') ? 'active' : '' }}">Semua Barang</a>
                <a href="{{ route('staf.inventaris.index', ['kondisi' => 'rusak']) }}" class="sub-link {{ request('kondisi') == 'rusak' ? 'active' : '' }}">Barang Rusak</a>
                <a href="{{ route('staf.inventaris.create') }}" class="sub-link {{ request()->routeIs('staf.inventaris.create') ? 'active' : '' }}">Tambah Barang</a>
            </div>
        </div>
        <div class="nav-item {{ request()->routeIs('staf.peminjaman.*') ? 'open' : '' }}">
            <a class="nav-link {{ request()->routeIs('staf.peminjaman.*') ? 'active' : '' }}" data-toggle="submenu">
                <i class="bi bi-calendar2-check icon"></i> <span>Peminjaman Fasilitas</span> <i class="bi bi-chevron-right arrow"></i>
            </a>
            <div class="submenu">
                <a href="{{ route('staf.peminjaman.index') }}" class="sub-link {{ request()->routeIs('staf.peminjaman.index') ? 'active' : '' }}">Daftar Peminjaman</a>
                <a href="{{ route('staf.peminjaman.create') }}" class="sub-link {{ request()->routeIs('staf.peminjaman.create') ? 'active' : '' }}">Ajukan Peminjaman</a>
            </div>
        </div>
        <div class="nav-item {{ request()->routeIs('staf.kerusakan.*') ? 'open' : '' }}">
            <a class="nav-link {{ request()->routeIs('staf.kerusakan.*') ? 'active' : '' }}" data-toggle="submenu">
                <i class="bi bi-tools icon"></i> <span>Laporan Kerusakan</span> <i class="bi bi-chevron-right arrow"></i>
            </a>
            <div class="submenu">
                <a href="{{ route('staf.kerusakan.index') }}" class="sub-link {{ request()->routeIs('staf.kerusakan.index') ? 'active' : '' }}">Daftar Kerusakan</a>
                <a href="{{ route('staf.kerusakan.create') }}" class="sub-link {{ request()->routeIs('staf.kerusakan.create') ? 'active' : '' }}">Buat Laporan</a>
            </div>
        </div>
        <div class="nav-item {{ request()->routeIs('staf.dokumen.*') ? 'open' : '' }}">
            <a class="nav-link {{ request()->routeIs('staf.dokumen.*') ? 'active' : '' }}" data-toggle="submenu">
                <i class="bi bi-folder2-open icon"></i> <span>Dokumen</span> <i class="bi bi-chevron-right arrow"></i>
            </a>
            <div class="submenu">
                <a href="{{ route('staf.dokumen.index') }}" class="sub-link {{ request()->routeIs('staf.dokumen.index') ? 'active' : '' }}">Arsip Dokumen</a>
            </div>
        </div>
    </div>
</div>
