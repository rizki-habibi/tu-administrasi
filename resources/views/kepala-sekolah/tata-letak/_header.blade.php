{{-- Top Header Bar for Kepala Sekolah --}}
@php
    $unreadNotif = \App\Models\Notification::where('pengguna_id', auth()->id())->where('sudah_dibaca', false)->count();
@endphp

<header class="top-header">
    {{-- Sidebar Toggle --}}
    <button class="sidebar-toggle" id="sidebarToggle"><i class="bi bi-list"></i></button>

    {{-- Page Title --}}
    <span class="header-title">@yield('judul', 'Beranda')</span>

    {{-- Right Side --}}
    <div class="header-right">
        {{-- Date --}}
        <span class="header-date d-none d-md-block">
            <i class="bi bi-calendar3"></i> {{ now()->translatedFormat('d F Y') }}
        </span>

        {{-- Notification Dropdown --}}
        <div class="dropdown" id="notifDropdown">
            <button class="notif-btn" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false" id="notifToggle">
                <i class="bi bi-bell"></i>
                @if($unreadNotif > 0)
                    <span class="notif-badge" id="notifBadge"></span>
                @endif
            </button>
            <div class="dropdown-menu dropdown-menu-end p-0" style="width:360px;max-height:440px;border-radius:14px!important;overflow:hidden;">
                {{-- Dropdown Header --}}
                <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom" style="background:#fffbeb;">
                    <h6 class="mb-0 fw-bold" style="font-size:.85rem;color:#92400e;">Notifikasi</h6>
                    <span class="badge" id="notifCount" style="font-size:.65rem;background:#d97706;color:#fff;">{{ $unreadNotif }}</span>
                </div>
                {{-- Notification List (loaded via AJAX) --}}
                <div id="notifList" style="max-height:320px;overflow-y:auto;">
                    <div class="text-center py-4 text-muted" style="font-size:.8rem;">
                        <div class="spinner-border spinner-border-sm me-1" style="color:#d97706;"></div> Memuat...
                    </div>
                </div>
                {{-- Footer Link --}}
                <div class="border-top text-center py-2" style="background:#fffbeb;">
                    <a href="{{ route('kepala-sekolah.notifikasi.index') }}" class="text-decoration-none fw-semibold" style="font-size:.78rem;color:#92400e;">
                        Lihat Semua Notifikasi <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- User Profile Dropdown --}}
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
                <i class="bi bi-chevron-down" style="font-size:.65rem;color:#a8a29e;"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <a class="dropdown-item" href="{{ route('kepala-sekolah.profil.edit') }}">
                        <i class="bi bi-person-circle me-2" style="color:#d97706;"></i>Profil Saya
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item text-danger" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form-header').submit();">
                        <i class="bi bi-box-arrow-left me-2"></i>Logout
                    </a>
                    <form id="logout-form-header" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                </li>
            </ul>
        </div>
    </div>
</header>

{{-- Notification AJAX Loading Script --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const notifToggle = document.getElementById('notifToggle');
        if (notifToggle) {
            notifToggle.addEventListener('click', function () {
                const dropdown = bootstrap.Dropdown.getOrCreateInstance(notifToggle);
                loadNotifications();
            });
        }

        function loadNotifications() {
            const list = document.getElementById('notifList');
            list.innerHTML = '<div class="text-center py-4 text-muted" style="font-size:.8rem;"><div class="spinner-border spinner-border-sm me-1" style="color:#d97706;"></div> Memuat...</div>';

            fetch('{{ route("kepala-sekolah.notifikasi.json") }}', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(r => r.json())
            .then(data => {
                const badge = document.getElementById('notifBadge');
                const count = document.getElementById('notifCount');
                if (count) count.textContent = data.unread_count;
                if (badge) badge.style.display = data.unread_count > 0 ? '' : 'none';

                if (data.notifications.length === 0) {
                    list.innerHTML = '<div class="text-center py-4 text-muted" style="font-size:.82rem;"><i class="bi bi-bell-slash" style="font-size:1.5rem;color:#d97706;"></i><p class="mb-0 mt-1">Tidak ada notifikasi baru</p></div>';
                    return;
                }

                const icons = {
                    info:       'bi-info-circle text-primary',
                    warning:    'bi-exclamation-triangle text-warning',
                    success:    'bi-check-circle text-success',
                    danger:     'bi-x-circle text-danger',
                    kehadiran:  'bi-fingerprint text-success',
                    izin:       'bi-calendar2-x text-warning',
                    event:      'bi-calendar-event text-info',
                    pengumuman: 'bi-megaphone text-primary',
                    sistem:     'bi-gear text-secondary'
                };

                list.innerHTML = data.notifications.map(n => `
                    <div class="d-flex gap-2 px-3 py-2 border-bottom notif-item" style="transition:.15s;${!n.sudah_dibaca ? 'background:#fffbeb;' : ''}">
                        <div class="flex-shrink-0 mt-1">
                            <i class="bi ${icons[n.jenis] || icons.info}" style="font-size:1rem;"></i>
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <div class="fw-semibold" style="font-size:.82rem;color:#92400e;">${n.judul}</div>
                            <div class="text-muted" style="font-size:.75rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${n.pesan}</div>
                            <small class="text-muted" style="font-size:.68rem;">${n.time}</small>
                        </div>
                    </div>
                `).join('');
            })
            .catch(() => {
                list.innerHTML = '<div class="text-center py-4 text-muted" style="font-size:.82rem;">Gagal memuat notifikasi</div>';
            });
        }
    });
</script>
