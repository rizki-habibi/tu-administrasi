{{-- Menu Khusus: Kesiswaan & Kurikulum --}}
<div class="nav-label">Kesiswaan</div>

{{-- Data Kesiswaan --}}
<div class="nav-item {{ request()->routeIs('staf.kesiswaan.*') ? 'open' : '' }}">
    <a class="nav-link {{ request()->routeIs('staf.kesiswaan.*') ? 'active' : '' }}" data-toggle="submenu">
        <i class="bi bi-mortarboard-fill icon"></i> <span>Data Siswa</span> <i class="bi bi-chevron-right arrow"></i>
    </a>
    <div class="submenu">
        <a href="{{ route('staf.kesiswaan.index') }}" class="sub-link {{ request()->routeIs('staf.kesiswaan.index') ? 'active' : '' }}">Daftar Siswa</a>
    </div>
</div>

<div class="nav-label">Kurikulum</div>

{{-- Kurikulum --}}
<div class="nav-item {{ request()->routeIs('staf.kurikulum.*') ? 'open' : '' }}">
    <a class="nav-link {{ request()->routeIs('staf.kurikulum.*') ? 'active' : '' }}" data-toggle="submenu">
        <i class="bi bi-book-half icon"></i> <span>Dokumen Kurikulum</span> <i class="bi bi-chevron-right arrow"></i>
    </a>
    <div class="submenu">
        <a href="{{ route('staf.kurikulum.index') }}" class="sub-link {{ request()->routeIs('staf.kurikulum.index') ? 'active' : '' }}">Daftar Kurikulum</a>
    </div>
</div>

{{-- Evaluasi --}}
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

{{-- Laporan --}}
<div class="nav-item {{ request()->routeIs('staf.laporan.*') ? 'open' : '' }}">
    <a class="nav-link {{ request()->routeIs('staf.laporan.*') ? 'active' : '' }}" data-toggle="submenu">
        <i class="bi bi-graph-up icon"></i> <span>Laporan</span> <i class="bi bi-chevron-right arrow"></i>
    </a>
    <div class="submenu">
        <a href="{{ route('staf.laporan.index') }}" class="sub-link {{ request()->routeIs('staf.laporan.index') ? 'active' : '' }}">Semua Laporan</a>
        <a href="{{ route('staf.laporan.create') }}" class="sub-link {{ request()->routeIs('staf.laporan.create') ? 'active' : '' }}">Buat Laporan</a>
    </div>
</div>
