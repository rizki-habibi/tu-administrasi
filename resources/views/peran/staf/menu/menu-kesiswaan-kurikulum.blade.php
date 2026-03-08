{{-- Menu Khusus: Kesiswaan & Kurikulum --}}
<div class="nav-group {{ request()->routeIs('staf.kesiswaan.*') || request()->routeIs('staf.kesiswaan-kelola.*') || request()->routeIs('staf.pelanggaran.*') || request()->routeIs('staf.prestasi.*') ? 'open' : '' }}">
    <div class="nav-group-label" data-toggle="nav-group"><span>Kesiswaan</span><i class="bi bi-chevron-down"></i></div>
    <div class="nav-group-items">
        <div class="nav-item {{ request()->routeIs('staf.kesiswaan.*') || request()->routeIs('staf.kesiswaan-kelola.*') ? 'open' : '' }}">
            <a class="nav-link {{ request()->routeIs('staf.kesiswaan.*') || request()->routeIs('staf.kesiswaan-kelola.*') ? 'active' : '' }}" data-toggle="submenu">
                <i class="bi bi-mortarboard-fill icon"></i> <span>Data Siswa</span> <i class="bi bi-chevron-right arrow"></i>
            </a>
            <div class="submenu">
                <a href="{{ route('staf.kesiswaan.index') }}" class="sub-link {{ request()->routeIs('staf.kesiswaan.index') ? 'active' : '' }}">Daftar Siswa</a>
                <a href="{{ route('staf.kesiswaan-kelola.create') }}" class="sub-link {{ request()->routeIs('staf.kesiswaan-kelola.create') ? 'active' : '' }}">Tambah Siswa</a>
                <a href="{{ route('staf.kesiswaan-kelola.ekspor') }}" class="sub-link {{ request()->routeIs('staf.kesiswaan-kelola.ekspor') ? 'active' : '' }}">Ekspor CSV</a>
            </div>
        </div>
        <div class="nav-item {{ request()->routeIs('staf.pelanggaran.*') ? 'open' : '' }}">
            <a class="nav-link {{ request()->routeIs('staf.pelanggaran.*') ? 'active' : '' }}" data-toggle="submenu">
                <i class="bi bi-exclamation-triangle-fill icon"></i> <span>Pelanggaran</span> <i class="bi bi-chevron-right arrow"></i>
            </a>
            <div class="submenu">
                <a href="{{ route('staf.pelanggaran.index') }}" class="sub-link {{ request()->routeIs('staf.pelanggaran.index') ? 'active' : '' }}">Daftar Pelanggaran</a>
                <a href="{{ route('staf.pelanggaran.create') }}" class="sub-link {{ request()->routeIs('staf.pelanggaran.create') ? 'active' : '' }}">Catat Pelanggaran</a>
            </div>
        </div>
        <div class="nav-item {{ request()->routeIs('staf.prestasi.*') ? 'open' : '' }}">
            <a class="nav-link {{ request()->routeIs('staf.prestasi.*') ? 'active' : '' }}" data-toggle="submenu">
                <i class="bi bi-trophy-fill icon"></i> <span>Prestasi</span> <i class="bi bi-chevron-right arrow"></i>
            </a>
            <div class="submenu">
                <a href="{{ route('staf.prestasi.index') }}" class="sub-link {{ request()->routeIs('staf.prestasi.index') ? 'active' : '' }}">Daftar Prestasi</a>
                <a href="{{ route('staf.prestasi.create') }}" class="sub-link {{ request()->routeIs('staf.prestasi.create') ? 'active' : '' }}">Catat Prestasi</a>
            </div>
        </div>
    </div>
</div>

<div class="nav-group {{ request()->routeIs('staf.kurikulum.*') || request()->routeIs('staf.kurikulum-kelola.*') || request()->routeIs('staf.evaluasi.*') || request()->routeIs('staf.laporan.*') ? 'open' : '' }}">
    <div class="nav-group-label" data-toggle="nav-group"><span>Kurikulum</span><i class="bi bi-chevron-down"></i></div>
    <div class="nav-group-items">
        <div class="nav-item {{ request()->routeIs('staf.kurikulum.*') || request()->routeIs('staf.kurikulum-kelola.*') ? 'open' : '' }}">
            <a class="nav-link {{ request()->routeIs('staf.kurikulum.*') || request()->routeIs('staf.kurikulum-kelola.*') ? 'active' : '' }}" data-toggle="submenu">
                <i class="bi bi-book-half icon"></i> <span>Dokumen Kurikulum</span> <i class="bi bi-chevron-right arrow"></i>
            </a>
            <div class="submenu">
                <a href="{{ route('staf.kurikulum.index') }}" class="sub-link {{ request()->routeIs('staf.kurikulum.index') ? 'active' : '' }}">Daftar Kurikulum</a>
                <a href="{{ route('staf.kurikulum-kelola.create') }}" class="sub-link {{ request()->routeIs('staf.kurikulum-kelola.create') ? 'active' : '' }}">Unggah Dokumen</a>
            </div>
        </div>
        <div class="nav-item {{ request()->routeIs('staf.evaluasi.*') ? 'open' : '' }}">
            <a class="nav-link {{ request()->routeIs('staf.evaluasi.*') ? 'active' : '' }}" data-toggle="submenu">
                <i class="bi bi-clipboard2-data-fill icon"></i> <span>Evaluasi</span> <i class="bi bi-chevron-right arrow"></i>
            </a>
            <div class="submenu">
                <a href="{{ route('staf.evaluasi.pkg') }}" class="sub-link {{ request()->routeIs('staf.evaluasi.pkg') ? 'active' : '' }}">Penilaian Kinerja Guru</a>
                <a href="{{ route('staf.evaluasi.p5') }}" class="sub-link {{ request()->routeIs('staf.evaluasi.p5') ? 'active' : '' }}">Penilaian P5</a>
                <a href="{{ route('staf.evaluasi.star') }}" class="sub-link {{ request()->routeIs('staf.evaluasi.star') ? 'active' : '' }}">Analisis STAR</a>
                <a href="{{ route('staf.evaluasi.bukti-fisik') }}" class="sub-link {{ request()->routeIs('staf.evaluasi.bukti-fisik') ? 'active' : '' }}">Bukti Fisik</a>
                <a href="{{ route('staf.evaluasi.pembelajaran') }}" class="sub-link {{ request()->routeIs('staf.evaluasi.pembelajaran') ? 'active' : '' }}">Evaluasi Pembelajaran</a>
            </div>
        </div>
        <div class="nav-item {{ request()->routeIs('staf.laporan.*') ? 'open' : '' }}">
            <a class="nav-link {{ request()->routeIs('staf.laporan.*') ? 'active' : '' }}" data-toggle="submenu">
                <i class="bi bi-graph-up icon"></i> <span>Laporan</span> <i class="bi bi-chevron-right arrow"></i>
            </a>
            <div class="submenu">
                <a href="{{ route('staf.laporan.index') }}" class="sub-link {{ request()->routeIs('staf.laporan.index') ? 'active' : '' }}">Semua Laporan</a>
                <a href="{{ route('staf.laporan.create') }}" class="sub-link {{ request()->routeIs('staf.laporan.create') ? 'active' : '' }}">Buat Laporan</a>
            </div>
        </div>
    </div>
</div>
