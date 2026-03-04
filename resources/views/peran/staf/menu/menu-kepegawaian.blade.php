{{-- Menu Khusus: Kepegawaian --}}
<div class="nav-label">Kepegawaian</div>

{{-- SKP --}}
<div class="nav-item {{ request()->routeIs('staf.skp.*') ? 'open' : '' }}">
    <a class="nav-link {{ request()->routeIs('staf.skp.*') ? 'active' : '' }}" data-toggle="submenu">
        <i class="bi bi-person-vcard-fill icon"></i> <span>SKP</span> <i class="bi bi-chevron-right arrow"></i>
    </a>
    <div class="submenu">
        <a href="{{ route('staf.skp.index') }}" class="sub-link {{ request()->routeIs('staf.skp.index') ? 'active' : '' }}">Daftar SKP</a>
        <a href="{{ route('staf.skp.create') }}" class="sub-link {{ request()->routeIs('staf.skp.create') ? 'active' : '' }}">Buat SKP</a>
    </div>
</div>

{{-- Evaluasi --}}
<div class="nav-item {{ request()->routeIs('staf.evaluasi.*') ? 'open' : '' }}">
    <a class="nav-link {{ request()->routeIs('staf.evaluasi.*') ? 'active' : '' }}" data-toggle="submenu">
        <i class="bi bi-clipboard2-data-fill icon"></i> <span>Evaluasi</span> <i class="bi bi-chevron-right arrow"></i>
    </a>
    <div class="submenu">
        <a href="{{ route('staf.evaluasi.pkg') }}" class="sub-link {{ request()->routeIs('staf.evaluasi.pkg*') ? 'active' : '' }}">PKG / BKD</a>
        <a href="{{ route('staf.evaluasi.star') }}" class="sub-link {{ request()->routeIs('staf.evaluasi.star*') ? 'active' : '' }}">Analisis STAR</a>
        <a href="{{ route('staf.evaluasi.bukti-fisik') }}" class="sub-link {{ request()->routeIs('staf.evaluasi.bukti-fisik*') ? 'active' : '' }}">Bukti Fisik</a>
    </div>
</div>
