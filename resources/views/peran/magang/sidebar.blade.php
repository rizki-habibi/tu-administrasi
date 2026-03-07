{{-- Sidebar Navigation for Magang --}}
@php
    $user = Auth::user();
    $unreadCount = \App\Models\Notifikasi::where('pengguna_id', $user->id)->where('sudah_dibaca', false)->count();
@endphp

<aside class="sidebar" id="sidebar">
    {{-- Brand --}}
    <div class="sidebar-brand">
        <img src="{{ asset('images/logo.png') }}" alt="Logo SMA Negeri 2 Jember" style="width:42px;height:42px;object-fit:contain;" onerror="this.style.display='none'">
        <h6>TU Administrasi</h6>
        <small>SMA Negeri 2 Jember</small>
    </div>

    {{-- Profile --}}
    <div class="sidebar-profile">
        <div class="avatar">
            @if($user->foto)
                <img src="{{ asset('storage/' . $user->foto) }}" alt="{{ $user->nama }}">
            @else
                {{ strtoupper(substr($user->nama, 0, 2)) }}
            @endif
        </div>
        <div class="info">
            <div class="nama">{{ $user->nama }}</div>
            <div class="peran"><i class="bi bi-mortarboard-fill"></i> Staff Magang</div>
        </div>
        <div class="status" title="Online"></div>
    </div>

    {{-- Search --}}
    <div class="sidebar-search">
        <i class="bi bi-search"></i>
        <input type="text" id="sidebarSearchInput" placeholder="Cari menu..." autocomplete="off">
    </div>

    <nav class="sidebar-nav">
        {{-- Menu Utama --}}
        <div class="nav-group open" data-group="utama">
            <div class="nav-group-label"><span>Menu Utama</span> <i class="bi bi-chevron-down"></i></div>
            <div class="nav-group-items">
                <div class="nav-item">
                    <a href="{{ route('magang.beranda') }}" class="nav-link {{ request()->routeIs('magang.beranda') ? 'active' : '' }}">
                        <i class="bi bi-grid-1x2-fill icon"></i> <span>Beranda</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- Kehadiran --}}
        <div class="nav-group {{ request()->routeIs('magang.kehadiran.*', 'magang.izin.*') ? 'open' : '' }}" data-group="kehadiran">
            <div class="nav-group-label"><span>Kehadiran</span> <i class="bi bi-chevron-down"></i></div>
            <div class="nav-group-items">
                <div class="nav-item {{ request()->routeIs('magang.kehadiran.*') ? 'open' : '' }}">
                    <a class="nav-link {{ request()->routeIs('magang.kehadiran.*') ? 'active' : '' }}" data-toggle="submenu">
                        <i class="bi bi-fingerprint icon"></i> <span>Absensi</span> <i class="bi bi-chevron-right arrow"></i>
                    </a>
                    <div class="submenu">
                        <a href="{{ route('magang.kehadiran.index') }}" class="sub-link {{ request()->routeIs('magang.kehadiran.index') && !request('view') ? 'active' : '' }}">Absen Hari Ini</a>
                        <a href="{{ route('magang.kehadiran.index', ['view' => 'history']) }}" class="sub-link {{ request('view') == 'history' ? 'active' : '' }}">Riwayat Kehadiran</a>
                    </div>
                </div>
                <div class="nav-item {{ request()->routeIs('magang.izin.*') ? 'open' : '' }}">
                    <a class="nav-link {{ request()->routeIs('magang.izin.*') ? 'active' : '' }}" data-toggle="submenu">
                        <i class="bi bi-calendar2-check icon"></i> <span>Pengajuan Izin</span> <i class="bi bi-chevron-right arrow"></i>
                    </a>
                    <div class="submenu">
                        <a href="{{ route('magang.izin.index') }}" class="sub-link {{ request()->routeIs('magang.izin.index') ? 'active' : '' }}">Daftar Izin</a>
                        <a href="{{ route('magang.izin.create') }}" class="sub-link {{ request()->routeIs('magang.izin.create') ? 'active' : '' }}">Ajukan Izin</a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Logbook & Kegiatan --}}
        <div class="nav-group {{ request()->routeIs('magang.logbook.*', 'magang.kegiatan.*') ? 'open' : '' }}" data-group="aktivitas">
            <div class="nav-group-label"><span>Aktivitas Magang</span> <i class="bi bi-chevron-down"></i></div>
            <div class="nav-group-items">
                <div class="nav-item {{ request()->routeIs('magang.logbook.*') ? 'open' : '' }}">
                    <a class="nav-link {{ request()->routeIs('magang.logbook.*') ? 'active' : '' }}" data-toggle="submenu">
                        <i class="bi bi-journal-text icon"></i> <span>Logbook Harian</span> <i class="bi bi-chevron-right arrow"></i>
                    </a>
                    <div class="submenu">
                        <a href="{{ route('magang.logbook.index') }}" class="sub-link {{ request()->routeIs('magang.logbook.index') ? 'active' : '' }}">Semua Logbook</a>
                        <a href="{{ route('magang.logbook.create') }}" class="sub-link {{ request()->routeIs('magang.logbook.create') ? 'active' : '' }}">Tulis Logbook</a>
                    </div>
                </div>
                <div class="nav-item {{ request()->routeIs('magang.kegiatan.*') ? 'open' : '' }}">
                    <a class="nav-link {{ request()->routeIs('magang.kegiatan.*') ? 'active' : '' }}" data-toggle="submenu">
                        <i class="bi bi-clipboard2-check icon"></i> <span>Kegiatan</span> <i class="bi bi-chevron-right arrow"></i>
                    </a>
                    <div class="submenu">
                        <a href="{{ route('magang.kegiatan.index') }}" class="sub-link {{ request()->routeIs('magang.kegiatan.index') ? 'active' : '' }}">Semua Kegiatan</a>
                        <a href="{{ route('magang.kegiatan.create') }}" class="sub-link {{ request()->routeIs('magang.kegiatan.create') ? 'active' : '' }}">Tambah Kegiatan</a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Notifikasi --}}
        <div class="nav-group {{ request()->routeIs('magang.notifikasi.*') ? 'open' : '' }}" data-group="lainnya">
            <div class="nav-group-label"><span>Lainnya</span> <i class="bi bi-chevron-down"></i></div>
            <div class="nav-group-items">
                <div class="nav-item">
                    <a href="{{ route('magang.notifikasi.index') }}" class="nav-link {{ request()->routeIs('magang.notifikasi.*') ? 'active' : '' }}">
                        <i class="bi bi-bell-fill icon"></i> <span>Notifikasi</span>
                        @if($unreadCount > 0)
                            <span class="badge bg-danger">{{ $unreadCount }}</span>
                        @endif
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('magang.profil.edit') }}" class="nav-link {{ request()->routeIs('magang.profil.*') ? 'active' : '' }}">
                        <i class="bi bi-person-fill icon"></i> <span>Profil Saya</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    {{-- Sidebar Footer --}}
    <div class="sidebar-footer">
        <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form-s').submit();" title="Keluar">
            <i class="bi bi-box-arrow-left"></i> <span>Keluar</span>
        </a>
        <form id="logout-form-s" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
    </div>
</aside>
