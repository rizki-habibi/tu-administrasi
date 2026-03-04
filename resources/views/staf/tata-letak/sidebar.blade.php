{{-- Sidebar Navigation for Staff --}}
@php
    $user = Auth::user();
    $userRole = $user->peran;
    $unreadCount = \App\Models\Notification::where('pengguna_id', $user->id)->where('sudah_dibaca', false)->count();
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
            <div class="peran"><i class="bi bi-person-badge"></i> {{ $user->role_label ?? ucfirst(str_replace('_', ' ', $userRole)) }}</div>
        </div>
        <div class="status" title="Online"></div>
    </div>

    <nav class="sidebar-nav">
        {{-- ═══════════════════════════════════════════
             Menu Utama
        ═══════════════════════════════════════════ --}}
        <div class="nav-label">Menu Utama</div>
        <div class="nav-item">
            <a href="{{ route('staf.beranda') }}" class="nav-link {{ request()->routeIs('staf.beranda') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill icon"></i> <span>Beranda</span>
            </a>
        </div>

        {{-- ═══════════════════════════════════════════
             Kehadiran
        ═══════════════════════════════════════════ --}}
        <div class="nav-label">Kehadiran</div>

        {{-- Absensi --}}
        <div class="nav-item {{ request()->routeIs('staf.kehadiran.*') ? 'open' : '' }}">
            <a class="nav-link {{ request()->routeIs('staf.kehadiran.*') ? 'active' : '' }}" data-toggle="submenu">
                <i class="bi bi-fingerprint icon"></i> <span>Absensi</span> <i class="bi bi-chevron-right arrow"></i>
            </a>
            <div class="submenu">
                <a href="{{ route('staf.kehadiran.index') }}" class="sub-link {{ request()->routeIs('staf.kehadiran.index') && !request('view') ? 'active' : '' }}">Absen Hari Ini</a>
                <a href="{{ route('staf.kehadiran.index', ['view' => 'history']) }}" class="sub-link {{ request('view') == 'history' ? 'active' : '' }}">Riwayat Kehadiran</a>
            </div>
        </div>

        {{-- Pengajuan Izin --}}
        <div class="nav-item {{ request()->routeIs('staf.izin.*') ? 'open' : '' }}">
            <a class="nav-link {{ request()->routeIs('staf.izin.*') ? 'active' : '' }}" data-toggle="submenu">
                <i class="bi bi-calendar2-check icon"></i> <span>Pengajuan Izin</span> <i class="bi bi-chevron-right arrow"></i>
            </a>
            <div class="submenu">
                <a href="{{ route('staf.izin.index') }}" class="sub-link {{ request()->routeIs('staf.izin.index') ? 'active' : '' }}">Daftar Izin</a>
                <a href="{{ route('staf.izin.create') }}" class="sub-link {{ request()->routeIs('staf.izin.create') ? 'active' : '' }}">Ajukan Izin</a>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════
             Menu Khusus Per Peran
        ═══════════════════════════════════════════ --}}
        @if($userRole === 'kepegawaian')
            @include('staf.tata-letak.sidebar.menu-kepegawaian')
        @elseif($userRole === 'pramu_bakti')
            @include('staf.tata-letak.sidebar.menu-pramu-bakti')
        @elseif($userRole === 'keuangan')
            @include('staf.tata-letak.sidebar.menu-keuangan')
        @elseif($userRole === 'persuratan')
            @include('staf.tata-letak.sidebar.menu-persuratan')
        @elseif($userRole === 'perpustakaan')
            @include('staf.tata-letak.sidebar.menu-perpustakaan')
        @elseif($userRole === 'inventaris')
            @include('staf.tata-letak.sidebar.menu-inventaris')
        @elseif($userRole === 'kesiswaan_kurikulum')
            @include('staf.tata-letak.sidebar.menu-kesiswaan-kurikulum')
        @endif

        {{-- ═══════════════════════════════════════════
             Administrasi Umum
        ═══════════════════════════════════════════ --}}
        @if(!in_array($userRole, ['persuratan']))
        <div class="nav-label">Administrasi</div>
        <div class="nav-item {{ request()->routeIs('staf.surat.*') ? 'open' : '' }}">
            <a class="nav-link {{ request()->routeIs('staf.surat.*') ? 'active' : '' }}" data-toggle="submenu">
                <i class="bi bi-envelope-paper-fill icon"></i> <span>Surat</span> <i class="bi bi-chevron-right arrow"></i>
            </a>
            <div class="submenu">
                <a href="{{ route('staf.surat.index') }}" class="sub-link {{ request()->routeIs('staf.surat.index') ? 'active' : '' }}">Semua Surat</a>
                <a href="{{ route('staf.surat.create') }}" class="sub-link {{ request()->routeIs('staf.surat.create') ? 'active' : '' }}">Buat Surat</a>
            </div>
        </div>
        @endif

        {{-- ═══════════════════════════════════════════
             Kinerja
        ═══════════════════════════════════════════ --}}
        <div class="nav-label">Kinerja</div>

        @if(!in_array($userRole, ['kepegawaian']))
        {{-- SKP --}}
        <div class="nav-item {{ request()->routeIs('staf.skp.*') ? 'open' : '' }}">
            <a class="nav-link {{ request()->routeIs('staf.skp.*') ? 'active' : '' }}" data-toggle="submenu">
                <i class="bi bi-file-earmark-bar-graph-fill icon"></i> <span>SKP</span> <i class="bi bi-chevron-right arrow"></i>
            </a>
            <div class="submenu">
                <a href="{{ route('staf.skp.index') }}" class="sub-link {{ request()->routeIs('staf.skp.index') ? 'active' : '' }}">Daftar SKP</a>
                <a href="{{ route('staf.skp.create') }}" class="sub-link {{ request()->routeIs('staf.skp.create') ? 'active' : '' }}">Buat SKP</a>
            </div>
        </div>
        @endif

        @if(!in_array($userRole, ['kepegawaian', 'kesiswaan_kurikulum']))
        {{-- Evaluasi --}}
        <div class="nav-item {{ request()->routeIs('staf.evaluasi.*') ? 'open' : '' }}">
            <a class="nav-link {{ request()->routeIs('staf.evaluasi.*') ? 'active' : '' }}" data-toggle="submenu">
                <i class="bi bi-clipboard2-data-fill icon"></i> <span>Evaluasi</span> <i class="bi bi-chevron-right arrow"></i>
            </a>
            <div class="submenu">
                <a href="{{ route('staf.evaluasi.pkg') }}" class="sub-link {{ request()->routeIs('staf.evaluasi.pkg*') ? 'active' : '' }}">PKG / BKD</a>
                <a href="{{ route('staf.evaluasi.p5') }}" class="sub-link {{ request()->routeIs('staf.evaluasi.p5*') ? 'active' : '' }}">Asesmen P5</a>
                <a href="{{ route('staf.evaluasi.star') }}" class="sub-link {{ request()->routeIs('staf.evaluasi.star*') ? 'active' : '' }}">Analisis STAR</a>
                <a href="{{ route('staf.evaluasi.bukti-fisik') }}" class="sub-link {{ request()->routeIs('staf.evaluasi.bukti-fisik*') ? 'active' : '' }}">Bukti Fisik</a>
                <a href="{{ route('staf.evaluasi.pembelajaran') }}" class="sub-link {{ request()->routeIs('staf.evaluasi.pembelajaran*') ? 'active' : '' }}">Metode Pembelajaran</a>
            </div>
        </div>
        @endif

        {{-- ═══════════════════════════════════════════
             Lainnya
        ═══════════════════════════════════════════ --}}
        <div class="nav-label">Lainnya</div>

        {{-- Word & AI --}}
        <div class="nav-item {{ request()->routeIs('staf.word-ai.*') ? 'open' : '' }}">
            <a class="nav-link {{ request()->routeIs('staf.word-ai.*') ? 'active' : '' }}" data-toggle="submenu">
                <i class="bi bi-file-earmark-word-fill icon"></i> <span>Word & AI</span> <i class="bi bi-chevron-right arrow"></i>
            </a>
            <div class="submenu">
                <a href="{{ route('staf.word-ai.index') }}" class="sub-link {{ request()->routeIs('staf.word-ai.index') ? 'active' : '' }}">Daftar Dokumen</a>
                <a href="{{ route('staf.word-ai.create') }}" class="sub-link {{ request()->routeIs('staf.word-ai.create') ? 'active' : '' }}">Buat Dokumen</a>
            </div>
        </div>

        {{-- Agenda --}}
        <div class="nav-item">
            <a href="{{ route('staf.agenda.index') }}" class="nav-link {{ request()->routeIs('staf.agenda.*') ? 'active' : '' }}">
                <i class="bi bi-calendar-event-fill icon"></i> <span>Agenda</span>
            </a>
        </div>

        {{-- Notifikasi --}}
        <div class="nav-item">
            <a href="{{ route('staf.notifikasi.index') }}" class="nav-link {{ request()->routeIs('staf.notifikasi.*') ? 'active' : '' }}">
                <i class="bi bi-megaphone-fill icon"></i> <span>Notifikasi</span>
                @if($unreadCount > 0)
                    <span class="badge bg-info" style="font-size:.6rem;">{{ $unreadCount }}</span>
                @endif
            </a>
        </div>

        {{-- Pengingat --}}
        <div class="nav-item">
            <a href="{{ route('staf.pengingat.index') }}" class="nav-link {{ request()->routeIs('staf.pengingat.*') ? 'active' : '' }}">
                <i class="bi bi-bell-fill icon"></i> <span>Pengingat</span>
            </a>
        </div>

        {{-- Panduan --}}
        <div class="nav-item">
            <a href="{{ route('staf.panduan.index') }}" class="nav-link {{ request()->routeIs('staf.panduan.*') ? 'active' : '' }}">
                <i class="bi bi-book icon"></i> <span>Panduan</span>
            </a>
        </div>

        {{-- Ulang Tahun --}}
        <div class="nav-item">
            <a href="{{ route('staf.ulang-tahun.index') }}" class="nav-link {{ request()->routeIs('staf.ulang-tahun.*') ? 'active' : '' }}">
                <i class="bi bi-gift-fill icon"></i> <span>Ulang Tahun</span>
            </a>
        </div>

        {{-- Akun Saya --}}
        <div class="nav-item {{ request()->routeIs('staf.profil.*') ? 'open' : '' }}">
            <a class="nav-link {{ request()->routeIs('staf.profil.*') ? 'active' : '' }}" data-toggle="submenu">
                <i class="bi bi-person-circle icon"></i> <span>Akun Saya</span> <i class="bi bi-chevron-right arrow"></i>
            </a>
            <div class="submenu">
                <a href="{{ route('staf.profil.edit') }}" class="sub-link {{ request()->routeIs('staf.profil.edit') ? 'active' : '' }}">Ubah Profil</a>
                <a href="{{ route('staf.profil.edit') }}#ubah-password" class="sub-link">Ubah Kata Sandi</a>
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
