{{-- Sidebar Navigation for Kepala Sekolah --}}
<aside class="sidebar" id="sidebar">
    {{-- Brand --}}
    <div class="sidebar-brand">
        <img src="{{ asset('images/logo.png') }}" alt="Logo SMA Negeri 2 Jember" style="width:42px;height:42px;object-fit:contain;" onerror="this.style.display='none'">
        <h6>Kepala Sekolah</h6>
        <small>SMA Negeri 2 Jember</small>
    </div>

    {{-- Profile --}}
    <div class="sidebar-profile">
        <div class="avatar">
            @if(Auth::user()->foto)
                <img src="{{ asset('storage/' . Auth::user()->foto) }}" alt="Foto">
            @else
                {{ strtoupper(substr(Auth::user()->nama, 0, 2)) }}
            @endif
        </div>
        <div class="info">
            <div class="nama">{{ Auth::user()->nama }}</div>
            <div class="peran"><i class="bi bi-shield-check"></i> Kepala Sekolah</div>
        </div>
        <div class="status" title="Online"></div>
    </div>

    @php
        $pendingLeave = \App\Models\LeaveRequest::where('status', 'pending')->count();
        $pendingSkp   = \App\Models\Skp::where('status', 'diajukan')->count();
        $unreadNotif  = \App\Models\Notification::where('sudah_dibaca', false)->count();
    @endphp

    <nav class="sidebar-nav">
        {{-- ═══════════════════════════════════════════
             Menu Utama
        ═══════════════════════════════════════════ --}}
        <div class="nav-label">Menu Utama</div>
        <div class="nav-item">
            <a href="{{ route('kepala-sekolah.beranda') }}" class="nav-link {{ request()->routeIs('kepala-sekolah.beranda') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill icon"></i> <span>Beranda</span>
            </a>
        </div>

        {{-- ═══════════════════════════════════════════
             Monitoring Staff
        ═══════════════════════════════════════════ --}}
        <div class="nav-label">Monitoring Staff</div>

        {{-- Data Staff --}}
        <div class="nav-item">
            <a href="{{ route('kepala-sekolah.pegawai.index') }}" class="nav-link {{ request()->routeIs('kepala-sekolah.pegawai.*') ? 'active' : '' }}">
                <i class="bi bi-people-fill icon"></i> <span>Data Staff</span>
            </a>
        </div>

        {{-- Kehadiran --}}
        <div class="nav-item {{ request()->routeIs('kepala-sekolah.kehadiran.*') ? 'open' : '' }}">
            <a class="nav-link {{ request()->routeIs('kepala-sekolah.kehadiran.*') ? 'active' : '' }}" data-toggle="submenu">
                <i class="bi bi-fingerprint icon"></i> <span>Kehadiran</span> <i class="bi bi-chevron-right arrow"></i>
            </a>
            <div class="submenu">
                <a href="{{ route('kepala-sekolah.kehadiran.index') }}" class="sub-link {{ request()->routeIs('kepala-sekolah.kehadiran.index') ? 'active' : '' }}">Absensi Hari Ini</a>
                <a href="{{ route('kepala-sekolah.kehadiran.laporan') }}" class="sub-link {{ request()->routeIs('kepala-sekolah.kehadiran.laporan') ? 'active' : '' }}">Rekap Kehadiran</a>
            </div>
        </div>

        {{-- Pengajuan Izin --}}
        <div class="nav-item {{ request()->routeIs('kepala-sekolah.izin.*') ? 'open' : '' }}">
            <a class="nav-link {{ request()->routeIs('kepala-sekolah.izin.*') ? 'active' : '' }}" data-toggle="submenu">
                <i class="bi bi-calendar2-check icon"></i> <span>Pengajuan Izin</span>
                @if($pendingLeave > 0)
                    <span class="badge bg-danger" style="font-size:.6rem;">{{ $pendingLeave }}</span>
                @endif
                <i class="bi bi-chevron-right arrow"></i>
            </a>
            <div class="submenu">
                <a href="{{ route('kepala-sekolah.izin.index') }}" class="sub-link {{ request()->routeIs('kepala-sekolah.izin.index') && !request('status') ? 'active' : '' }}">Semua Pengajuan</a>
                <a href="{{ route('kepala-sekolah.izin.index', ['status' => 'pending']) }}" class="sub-link {{ request('status') === 'pending' ? 'active' : '' }}">Menunggu Persetujuan</a>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════
             Kinerja Pegawai
        ═══════════════════════════════════════════ --}}
        <div class="nav-label">Kinerja Pegawai</div>

        {{-- SKP --}}
        <div class="nav-item {{ request()->routeIs('kepala-sekolah.skp.*') ? 'open' : '' }}">
            <a class="nav-link {{ request()->routeIs('kepala-sekolah.skp.*') ? 'active' : '' }}" data-toggle="submenu">
                <i class="bi bi-file-earmark-bar-graph-fill icon"></i> <span>SKP</span>
                @if($pendingSkp > 0)
                    <span class="badge bg-warning text-dark" style="font-size:.6rem;">{{ $pendingSkp }}</span>
                @endif
                <i class="bi bi-chevron-right arrow"></i>
            </a>
            <div class="submenu">
                <a href="{{ route('kepala-sekolah.skp.index') }}" class="sub-link {{ request()->routeIs('kepala-sekolah.skp.index') && !request('status') ? 'active' : '' }}">Semua SKP</a>
                <a href="{{ route('kepala-sekolah.skp.index', ['status' => 'diajukan']) }}" class="sub-link {{ request('status') === 'diajukan' ? 'active' : '' }}">Menunggu Penilaian</a>
            </div>
        </div>

        {{-- Evaluasi Kinerja --}}
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

        {{-- ═══════════════════════════════════════════
             Administrasi
        ═══════════════════════════════════════════ --}}
        <div class="nav-label">Administrasi</div>

        {{-- Surat Menyurat --}}
        <div class="nav-item">
            <a href="{{ route('kepala-sekolah.surat.index') }}" class="nav-link {{ request()->routeIs('kepala-sekolah.surat.*') ? 'active' : '' }}">
                <i class="bi bi-envelope-paper-fill icon"></i> <span>Surat Menyurat</span>
            </a>
        </div>

        {{-- Laporan --}}
        <div class="nav-item">
            <a href="{{ route('kepala-sekolah.laporan.index') }}" class="nav-link {{ request()->routeIs('kepala-sekolah.laporan.*') ? 'active' : '' }}">
                <i class="bi bi-journal-text icon"></i> <span>Laporan</span>
            </a>
        </div>

        {{-- Keuangan --}}
        <div class="nav-item">
            <a href="{{ route('kepala-sekolah.keuangan.index') }}" class="nav-link {{ request()->routeIs('kepala-sekolah.keuangan.*') ? 'active' : '' }}">
                <i class="bi bi-cash-coin icon"></i> <span>Keuangan</span>
            </a>
        </div>

        {{-- ═══════════════════════════════════════════
             Lainnya
        ═══════════════════════════════════════════ --}}
        <div class="nav-label">Lainnya</div>

        {{-- Agenda --}}
        <div class="nav-item">
            <a href="{{ route('kepala-sekolah.agenda.index') }}" class="nav-link {{ request()->routeIs('kepala-sekolah.agenda.*') ? 'active' : '' }}">
                <i class="bi bi-calendar-event-fill icon"></i> <span>Agenda</span>
            </a>
        </div>

        {{-- Notifikasi --}}
        <div class="nav-item">
            <a href="{{ route('kepala-sekolah.notifikasi.index') }}" class="nav-link {{ request()->routeIs('kepala-sekolah.notifikasi.*') ? 'active' : '' }}">
                <i class="bi bi-megaphone-fill icon"></i> <span>Notifikasi</span>
                @if($unreadNotif > 0)
                    <span class="badge bg-info" style="font-size:.6rem;">{{ $unreadNotif }}</span>
                @endif
            </a>
        </div>

        {{-- Ulang Tahun --}}
        <div class="nav-item">
            <a href="{{ route('kepala-sekolah.ulang-tahun.index') }}" class="nav-link {{ request()->routeIs('kepala-sekolah.ulang-tahun.*') ? 'active' : '' }}">
                <i class="bi bi-gift-fill icon"></i> <span>Ulang Tahun</span>
            </a>
        </div>

        {{-- Panduan --}}
        <div class="nav-item">
            <a href="{{ route('kepala-sekolah.panduan.index') }}" class="nav-link {{ request()->routeIs('kepala-sekolah.panduan.*') ? 'active' : '' }}">
                <i class="bi bi-book icon"></i> <span>Panduan</span>
            </a>
        </div>

        {{-- Akun Saya --}}
        <div class="nav-item {{ request()->routeIs('kepala-sekolah.profil.*') ? 'open' : '' }}">
            <a class="nav-link {{ request()->routeIs('kepala-sekolah.profil.*') ? 'active' : '' }}" data-toggle="submenu">
                <i class="bi bi-person-circle icon"></i> <span>Akun Saya</span> <i class="bi bi-chevron-right arrow"></i>
            </a>
            <div class="submenu">
                <a href="{{ route('kepala-sekolah.profil.edit') }}" class="sub-link {{ request()->routeIs('kepala-sekolah.profil.edit') ? 'active' : '' }}">Ubah Profil</a>
                <a href="{{ route('kepala-sekolah.profil.edit') }}#ubah-password" class="sub-link">Ubah Kata Sandi</a>
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
