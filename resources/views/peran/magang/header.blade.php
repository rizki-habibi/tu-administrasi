{{-- Top Header Bar for Magang --}}
@php
    $unreadNotif = \App\Models\Notifikasi::where('pengguna_id', auth()->id())->where('sudah_dibaca', false)->count();
@endphp

<header class="top-header">
    <button class="sidebar-toggle" id="sidebarToggle"><i class="bi bi-list"></i></button>
    <span class="header-title">@yield('judul', 'Beranda')</span>

    <div class="header-right">
        <span class="header-date d-none d-md-block">
            <i class="bi bi-calendar3"></i> {{ now()->translatedFormat('d F Y') }}
        </span>

        {{-- Notification --}}
        <div class="dropdown" id="notifDropdown">
            <button class="notif-btn" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false" id="notifToggle">
                <i class="bi bi-bell"></i>
                @if($unreadNotif > 0)
                    <span class="notif-badge" id="notifBadge"></span>
                @endif
            </button>
            <div class="dropdown-menu dropdown-menu-end p-0" style="width:360px;max-height:440px;border-radius:14px!important;overflow:hidden;">
                <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom" style="background:#ecfeff;">
                    <h6 class="mb-0 fw-bold" style="font-size:.85rem;color:#155e75;">Notifikasi</h6>
                    <span class="badge" id="notifCount" style="font-size:.65rem;background:#0891b2;color:#fff;">{{ $unreadNotif }}</span>
                </div>
                <div id="notifList" style="max-height:320px;overflow-y:auto;">
                    <div class="text-center py-4 text-muted" style="font-size:.8rem;">
                        <div class="spinner-border spinner-border-sm text-info me-1"></div> Memuat...
                    </div>
                </div>
                <div class="border-top text-center py-2" style="background:#ecfeff;">
                    <a href="{{ route('magang.notifikasi.index') }}" class="text-decoration-none fw-semibold" style="font-size:.78rem;color:#155e75;">
                        Lihat Semua Notifikasi <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- User Profile --}}
        <div class="dropdown">
            <button type="button" class="header-profile" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="avatar-sm">
                    @if(Auth::user()->foto)
                        <img src="{{ asset('storage/' . Auth::user()->foto) }}" alt="Foto">
                    @else
                        {{ strtoupper(substr(Auth::user()->nama, 0, 2)) }}
                    @endif
                </div>
                <span class="name d-none d-md-block">{{ Auth::user()->nama }}</span>
                <i class="bi bi-chevron-down" style="font-size:.65rem;color:#94a3b8;"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="{{ route('magang.profil.edit') }}"><i class="bi bi-person me-2"></i>Profil</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                       onclick="event.preventDefault();document.getElementById('logout-form-h').submit();">
                        <i class="bi bi-box-arrow-right me-2"></i>Keluar
                    </a>
                    <form id="logout-form-h" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                </li>
            </ul>
        </div>
    </div>
</header>
