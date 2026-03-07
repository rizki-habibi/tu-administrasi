{{-- Header for Kepala Sekolah - Dark unified with sidebar --}}
<header class="top-header">
    <button class="sidebar-toggle" id="sidebarToggle"><i class="bi bi-list"></i></button>
    <div class="header-title">@yield('judul', 'Dashboard')</div>
    <span class="header-date">{{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}</span>

    <div class="header-right">
        {{-- Right Panel Toggle --}}
        <button class="header-tool-btn" id="rightPanelToggle" title="Alat & Fitur Khusus">
            <i class="bi bi-tools"></i>
        </button>

        {{-- Notification --}}
        <div class="dropdown">
            <button class="notif-btn" data-bs-toggle="dropdown" aria-expanded="false" id="notifToggle">
                <i class="bi bi-bell-fill"></i>
                <span class="notif-badge d-none" id="notifBadge"></span>
            </button>
            <div class="dropdown-menu dropdown-menu-end notif-dropdown" style="width:340px;max-height:420px;overflow-y:auto;border:none;border-radius:14px;box-shadow:0 8px 30px rgba(0,0,0,.15);padding:0;">
                <div style="padding:14px 16px;border-bottom:1px solid #e7e5e4;"><h6 style="font-size:.85rem;font-weight:600;margin:0;">Notifikasi</h6></div>
                <div id="notifList" style="padding:8px;"><div class="text-center text-muted py-3" style="font-size:.8rem;">Memuat...</div></div>
                <div style="padding:10px 16px;border-top:1px solid #e7e5e4;text-align:center;">
                    <a href="{{ route('kepala-sekolah.notifikasi.index') }}" style="font-size:.78rem;color:var(--primary);font-weight:500;text-decoration:none;">Lihat Semua Notifikasi</a>
                </div>
            </div>
        </div>

        {{-- Profile --}}
        <div class="dropdown">
            <button class="header-profile" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="avatar-sm">
                    @if(Auth::user()->foto)
                        <img src="{{ asset('storage/' . Auth::user()->foto) }}" alt="Foto">
                    @else
                        {{ strtoupper(substr(Auth::user()->nama ?? 'K', 0, 1)) }}
                    @endif
                </div>
                <div class="text-start d-none d-md-block">
                    <span class="name">{{ Auth::user()->nama ?? 'Kepala Sekolah' }}</span>
                    <span class="role-tag">Kepala Sekolah</span>
                </div>
                <i class="bi bi-chevron-down" style="font-size:.6rem;color:#fbbf24;"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-end" style="min-width:180px;border:none;border-radius:12px;box-shadow:0 8px 30px rgba(0,0,0,.12);padding:8px;">
                <a class="dropdown-item" href="{{ route('kepala-sekolah.profil.edit') }}" style="border-radius:8px;font-size:.82rem;padding:8px 12px;"><i class="bi bi-person me-2"></i>Profil</a>
                <a class="dropdown-item" href="{{ route('kepala-sekolah.pengaturan.index') }}" style="border-radius:8px;font-size:.82rem;padding:8px 12px;"><i class="bi bi-gear me-2"></i>Pengaturan</a>
                <hr class="dropdown-divider my-1">
                <a class="dropdown-item text-danger" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form-h').submit();" style="border-radius:8px;font-size:.82rem;padding:8px 12px;">
                    <i class="bi bi-box-arrow-left me-2"></i>Keluar
                </a>
                <form id="logout-form-h" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
            </div>
        </div>
    </div>
</header>

{{-- Notification AJAX Script --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const notifToggle = document.getElementById('notifToggle');
    let notifLoaded = false;
    if (notifToggle) {
        notifToggle.addEventListener('click', function() {
            if (notifLoaded) return;
            notifLoaded = true;
            fetch("{{ route('kepala-sekolah.notifikasi.json') }}")
                .then(r => r.json())
                .then(data => {
                    const list = document.getElementById('notifList');
                    if (!data.length) {
                        list.innerHTML = '<div class="text-center text-muted py-3" style="font-size:.8rem;">Tidak ada notifikasi baru</div>';
                        return;
                    }
                    let html = '';
                    data.forEach(n => {
                        html += '<a href="' + (n.url || '#') + '" class="d-flex align-items-start gap-2 p-2 text-decoration-none rounded-3" style="transition:.15s;"' +
                            ' onmouseenter="this.style.background=\'#f5f5f4\'" onmouseleave="this.style.background=\'transparent\'">' +
                            '<div style="width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,var(--primary),var(--secondary));color:#fff;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:.85rem;">' +
                            '<i class="bi bi-bell"></i></div>' +
                            '<div><div style="font-size:.8rem;font-weight:500;color:#1c1917;">' + (n.judul || 'Notifikasi') + '</div>' +
                            '<div style="font-size:.7rem;color:#78716c;margin-top:2px;">' + (n.pesan || '').substring(0, 60) + '</div></div></a>';
                    });
                    list.innerHTML = html;
                    if (data.length > 0) {
                        const badge = document.getElementById('notifBadge');
                        if (badge) badge.classList.remove('d-none');
                    }
                })
                .catch(() => {
                    document.getElementById('notifList').innerHTML = '<div class="text-center text-muted py-3" style="font-size:.8rem;">Gagal memuat</div>';
                });
        });
    }
});
</script>
