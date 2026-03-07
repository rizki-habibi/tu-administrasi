{{-- Sidebar Navigation for Kepala Sekolah --}}
@php
    $pendingLeave = \App\Models\PengajuanIzin::where('status', 'pending')->count();
    $pendingSkp   = \App\Models\Skp::where('status', 'diajukan')->count();
    $unreadNotif  = \App\Models\Notifikasi::where('sudah_dibaca', false)->count();
@endphp

<aside class="sidebar" id="sidebar">
    {{-- Brand --}}
    <div class="sidebar-brand">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" style="width:42px;height:42px;object-fit:contain;" onerror="this.style.display='none'">
        <div>
            <h6>Kepala Sekolah</h6>
            <small>SMA Negeri 2 Jember</small>
        </div>
    </div>

    {{-- Search --}}
    <div class="sidebar-search">
        <i class="bi bi-search"></i>
        <input type="text" id="sidebarSearch" placeholder="Cari menu..." autocomplete="off">
    </div>

    <nav class="sidebar-nav" id="sidebarNav">

        {{-- -- Menu Utama -- --}}
        <div class="nav-group {{ request()->routeIs('kepala-sekolah.beranda') ? 'open' : 'open' }}">
            <div class="nav-group-label" data-toggle="nav-group"><span>Menu Utama</span><i class="bi bi-chevron-down"></i></div>
            <div class="nav-group-items">
                <div class="nav-item">
                    <a href="{{ route('kepala-sekolah.beranda') }}" class="nav-link {{ request()->routeIs('kepala-sekolah.beranda') ? 'active' : '' }}">
                        <i class="bi bi-grid-1x2-fill icon"></i> <span>Beranda</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- -- Monitoring Staff -- --}}
        <div class="nav-group {{ request()->routeIs('kepala-sekolah.pegawai.*') || request()->routeIs('kepala-sekolah.kehadiran.*') || request()->routeIs('kepala-sekolah.izin.*') ? 'open' : '' }}">
            <div class="nav-group-label" data-toggle="nav-group"><span>Monitoring Staff</span><i class="bi bi-chevron-down"></i></div>
            <div class="nav-group-items">
                <div class="nav-item">
                    <a href="{{ route('kepala-sekolah.pegawai.index') }}" class="nav-link {{ request()->routeIs('kepala-sekolah.pegawai.*') ? 'active' : '' }}">
                        <i class="bi bi-people-fill icon"></i> <span>Data Staff</span>
                    </a>
                </div>
                <div class="nav-item {{ request()->routeIs('kepala-sekolah.kehadiran.*') ? 'open' : '' }}">
                    <a class="nav-link {{ request()->routeIs('kepala-sekolah.kehadiran.*') ? 'active' : '' }}" data-toggle="submenu">
                        <i class="bi bi-fingerprint icon"></i> <span>Kehadiran</span> <i class="bi bi-chevron-right arrow"></i>
                    </a>
                    <div class="submenu">
                        <a href="{{ route('kepala-sekolah.kehadiran.index') }}" class="sub-link {{ request()->routeIs('kepala-sekolah.kehadiran.index') ? 'active' : '' }}">Absensi Hari Ini</a>
                        <a href="{{ route('kepala-sekolah.kehadiran.laporan') }}" class="sub-link {{ request()->routeIs('kepala-sekolah.kehadiran.laporan') ? 'active' : '' }}">Rekap Kehadiran</a>
                    </div>
                </div>
                <div class="nav-item {{ request()->routeIs('kepala-sekolah.izin.*') ? 'open' : '' }}">
                    <a class="nav-link {{ request()->routeIs('kepala-sekolah.izin.*') ? 'active' : '' }}" data-toggle="submenu">
                        <i class="bi bi-calendar2-check icon"></i> <span>Pengajuan Izin</span>
                        @if($pendingLeave > 0)<span class="badge bg-danger" style="font-size:.6rem;">{{ $pendingLeave }}</span>@endif
                        <i class="bi bi-chevron-right arrow"></i>
                    </a>
                    <div class="submenu">
                        <a href="{{ route('kepala-sekolah.izin.index') }}" class="sub-link {{ request()->routeIs('kepala-sekolah.izin.index') && !request('status') ? 'active' : '' }}">Semua Pengajuan</a>
                        <a href="{{ route('kepala-sekolah.izin.index', ['status' => 'pending']) }}" class="sub-link {{ request('status') === 'pending' ? 'active' : '' }}">Menunggu Persetujuan</a>
                    </div>
                </div>
            </div>
        </div>

        {{-- -- Kinerja Pegawai -- --}}
        <div class="nav-group {{ request()->routeIs('kepala-sekolah.skp.*') || request()->routeIs('kepala-sekolah.evaluasi.*') ? 'open' : '' }}">
            <div class="nav-group-label" data-toggle="nav-group"><span>Kinerja Pegawai</span><i class="bi bi-chevron-down"></i></div>
            <div class="nav-group-items">
                <div class="nav-item {{ request()->routeIs('kepala-sekolah.skp.*') ? 'open' : '' }}">
                    <a class="nav-link {{ request()->routeIs('kepala-sekolah.skp.*') ? 'active' : '' }}" data-toggle="submenu">
                        <i class="bi bi-file-earmark-bar-graph-fill icon"></i> <span>SKP</span>
                        @if($pendingSkp > 0)<span class="badge bg-warning text-dark" style="font-size:.6rem;">{{ $pendingSkp }}</span>@endif
                        <i class="bi bi-chevron-right arrow"></i>
                    </a>
                    <div class="submenu">
                        <a href="{{ route('kepala-sekolah.skp.index') }}" class="sub-link {{ request()->routeIs('kepala-sekolah.skp.index') && !request('status') ? 'active' : '' }}">Semua SKP</a>
                        <a href="{{ route('kepala-sekolah.skp.index', ['status' => 'diajukan']) }}" class="sub-link {{ request('status') === 'diajukan' ? 'active' : '' }}">Menunggu Penilaian</a>
                    </div>
                </div>
                <div class="nav-item {{ request()->routeIs('kepala-sekolah.evaluasi.*') ? 'open' : '' }}">
                    <a class="nav-link {{ request()->routeIs('kepala-sekolah.evaluasi.*') ? 'active' : '' }}" data-toggle="submenu">
                        <i class="bi bi-clipboard2-data-fill icon"></i> <span>Evaluasi Kinerja</span> <i class="bi bi-chevron-right arrow"></i>
                    </a>
                    <div class="submenu">
                        <a href="{{ route('kepala-sekolah.evaluasi.pkg') }}" class="sub-link {{ request()->routeIs('kepala-sekolah.evaluasi.pkg*') ? 'active' : '' }}">PKG / BKD</a>
                        <a href="{{ route('kepala-sekolah.evaluasi.star') }}" class="sub-link {{ request()->routeIs('kepala-sekolah.evaluasi.star*') ? 'active' : '' }}">Metode STAR</a>
                        <a href="{{ route('kepala-sekolah.evaluasi.bukti-fisik') }}" class="sub-link {{ request()->routeIs('kepala-sekolah.evaluasi.bukti-fisik*') ? 'active' : '' }}">Bukti Fisik</a>
                    </div>
                </div>
            </div>
        </div>

        {{-- -- Administrasi -- --}}
        <div class="nav-group {{ request()->routeIs('kepala-sekolah.surat.*') || request()->routeIs('kepala-sekolah.laporan.*') || request()->routeIs('kepala-sekolah.keuangan.*') ? 'open' : '' }}">
            <div class="nav-group-label" data-toggle="nav-group"><span>Administrasi</span><i class="bi bi-chevron-down"></i></div>
            <div class="nav-group-items">
                <div class="nav-item">
                    <a href="{{ route('kepala-sekolah.surat.index') }}" class="nav-link {{ request()->routeIs('kepala-sekolah.surat.*') ? 'active' : '' }}">
                        <i class="bi bi-envelope-paper-fill icon"></i> <span>Surat Menyurat</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('kepala-sekolah.laporan.index') }}" class="nav-link {{ request()->routeIs('kepala-sekolah.laporan.*') ? 'active' : '' }}">
                        <i class="bi bi-journal-text icon"></i> <span>Laporan</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('kepala-sekolah.keuangan.index') }}" class="nav-link {{ request()->routeIs('kepala-sekolah.keuangan.*') ? 'active' : '' }}">
                        <i class="bi bi-cash-coin icon"></i> <span>Keuangan</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- -- Lainnya -- --}}
        <div class="nav-group {{ request()->routeIs('kepala-sekolah.agenda.*') || request()->routeIs('kepala-sekolah.notifikasi.*') || request()->routeIs('kepala-sekolah.chat.*') || request()->routeIs('kepala-sekolah.ulang-tahun.*') || request()->routeIs('kepala-sekolah.panduan.*') || request()->routeIs('kepala-sekolah.pengaturan.*') ? 'open' : '' }}">
            <div class="nav-group-label" data-toggle="nav-group"><span>Lainnya</span><i class="bi bi-chevron-down"></i></div>
            <div class="nav-group-items">
                <div class="nav-item">
                    <a href="{{ route('kepala-sekolah.agenda.index') }}" class="nav-link {{ request()->routeIs('kepala-sekolah.agenda.*') ? 'active' : '' }}">
                        <i class="bi bi-calendar-event-fill icon"></i> <span>Agenda</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('kepala-sekolah.notifikasi.index') }}" class="nav-link {{ request()->routeIs('kepala-sekolah.notifikasi.*') ? 'active' : '' }}">
                        <i class="bi bi-megaphone-fill icon"></i> <span>Notifikasi</span>
                        @if($unreadNotif > 0)<span class="badge bg-info" style="font-size:.6rem;">{{ $unreadNotif }}</span>@endif
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('kepala-sekolah.chat.index') }}" class="nav-link {{ request()->routeIs('kepala-sekolah.chat.*') ? 'active' : '' }}">
                        <i class="bi bi-chat-left-text-fill icon"></i> <span>Chat</span>
                        <span class="badge bg-primary" id="chat-badge-sidebar" style="font-size:.6rem;display:none;">0</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('kepala-sekolah.ulang-tahun.index') }}" class="nav-link {{ request()->routeIs('kepala-sekolah.ulang-tahun.*') ? 'active' : '' }}">
                        <i class="bi bi-gift-fill icon"></i> <span>Ulang Tahun</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('kepala-sekolah.panduan.index') }}" class="nav-link {{ request()->routeIs('kepala-sekolah.panduan.*') ? 'active' : '' }}">
                        <i class="bi bi-book icon"></i> <span>Panduan</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('kepala-sekolah.pengaturan.index') }}" class="nav-link {{ request()->routeIs('kepala-sekolah.pengaturan.*') ? 'active' : '' }}">
                        <i class="bi bi-gear-fill icon"></i> <span>Pengaturan</span>
                    </a>
                </div>
            </div>
        </div>

    </nav>

    {{-- Sidebar Footer --}}
    <div class="sidebar-footer">
        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="bi bi-box-arrow-left"></i> <span>Keluar</span>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
    </div>
</aside>
