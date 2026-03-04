@php
    $headerUser = Auth::user();
@endphp

<header class="top-header" id="topHeader">
    <style>
        .top-header {
            background: #f8fafc;
            border-bottom: 1px solid var(--border-color);
            padding: 0 28px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 1020;
            box-shadow: 0 1px 3px rgba(0,0,0,.04);
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .sidebar-toggle {
            width: 38px; height: 38px;
            border: 1px solid var(--border-color);
            background: var(--white);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            color: var(--text-muted);
            transition: all var(--transition);
            font-size: .9rem;
        }
        .sidebar-toggle:hover {
            border-color: var(--primary);
            color: var(--primary);
            background: rgba(16,185,129,.06);
        }

        .header-page-title {
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--text-dark);
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .header-date {
            font-size: .8rem;
            color: var(--text-muted);
            font-weight: 500;
            padding: 6px 14px;
            background: var(--white);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* ── Notification Dropdown ── */
        .notif-dropdown {
            position: relative;
        }
        .notif-toggle {
            width: 40px; height: 40px;
            border: 1px solid var(--border-color);
            background: var(--white);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            color: var(--text-muted);
            transition: all var(--transition);
            font-size: .95rem;
            position: relative;
        }
        .notif-toggle:hover {
            border-color: var(--primary);
            color: var(--primary);
        }
        .notif-toggle .notif-badge {
            position: absolute;
            top: -4px; right: -4px;
            background: #ef4444;
            color: #fff;
            font-size: .6rem;
            font-weight: 700;
            min-width: 18px;
            height: 18px;
            border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            padding: 0 4px;
            border: 2px solid #f8fafc;
        }
        .notif-panel {
            position: absolute;
            top: 50px; right: 0;
            width: 360px;
            background: var(--white);
            border-radius: var(--card-radius);
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--border-color);
            display: none;
            z-index: 1100;
            overflow: hidden;
        }
        .notif-dropdown.open .notif-panel { display: block; }

        .notif-panel-header {
            padding: 14px 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--border-color);
            background: #f8fafc;
        }
        .notif-panel-header h4 {
            font-size: .9rem;
            font-weight: 700;
            color: var(--text-dark);
        }
        .notif-mark-all {
            font-size: .75rem;
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            cursor: pointer;
            border: none;
            background: none;
            padding: 0;
        }
        .notif-mark-all:hover { color: var(--primary-dark); }

        .notif-panel-body {
            max-height: 320px;
            overflow-y: auto;
        }
        .notif-item {
            display: flex;
            gap: 12px;
            padding: 12px 18px;
            border-bottom: 1px solid #f1f5f9;
            transition: background .15s;
            cursor: pointer;
            text-decoration: none;
            color: inherit;
        }
        .notif-item:hover { background: rgba(16,185,129,.04); }
        .notif-item.unread { background: rgba(16,185,129,.06); }
        .notif-item-icon {
            width: 36px; height: 36px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            background: linear-gradient(135deg, rgba(16,185,129,.12), rgba(5,150,105,.08));
            color: var(--primary);
            font-size: .8rem;
            flex-shrink: 0;
        }
        .notif-item-content { flex: 1; min-width: 0; }
        .notif-item-text {
            font-size: .82rem;
            color: var(--text-dark);
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .notif-item-time {
            font-size: .7rem;
            color: var(--text-muted);
            margin-top: 3px;
        }
        .notif-empty {
            padding: 32px 18px;
            text-align: center;
            color: var(--text-muted);
            font-size: .85rem;
        }
        .notif-panel-footer {
            padding: 10px 18px;
            text-align: center;
            border-top: 1px solid var(--border-color);
        }
        .notif-panel-footer a {
            font-size: .8rem;
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }
        .notif-panel-footer a:hover { color: var(--primary-dark); }

        /* ── Profile Dropdown ── */
        .profile-dropdown {
            position: relative;
        }
        .profile-toggle {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 5px 12px 5px 5px;
            border: 1px solid var(--border-color);
            background: var(--white);
            border-radius: 10px;
            cursor: pointer;
            transition: all var(--transition);
        }
        .profile-toggle:hover { border-color: var(--primary); }
        .profile-toggle-avatar {
            width: 32px; height: 32px;
            border-radius: 8px;
            overflow: hidden;
        }
        .profile-toggle-avatar img {
            width: 100%; height: 100%;
            object-fit: cover;
        }
        .profile-toggle-avatar-placeholder {
            width: 100%; height: 100%;
            background: linear-gradient(135deg, #10b981, #059669);
            display: flex; align-items: center; justify-content: center;
            font-weight: 700;
            color: #fff;
            font-size: .75rem;
        }
        .profile-toggle-name {
            font-size: .82rem;
            font-weight: 600;
            color: var(--text-dark);
        }
        .profile-toggle .chevron {
            font-size: .6rem;
            color: var(--text-muted);
            transition: transform var(--transition);
        }
        .profile-dropdown.open .profile-toggle .chevron { transform: rotate(180deg); }

        .profile-panel {
            position: absolute;
            top: 50px; right: 0;
            width: 220px;
            background: var(--white);
            border-radius: 12px;
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--border-color);
            display: none;
            z-index: 1100;
            overflow: hidden;
            padding: 6px;
        }
        .profile-dropdown.open .profile-panel { display: block; }

        .profile-panel a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            font-size: .84rem;
            color: var(--text-dark);
            text-decoration: none;
            border-radius: 8px;
            transition: all .15s;
            font-weight: 500;
        }
        .profile-panel a:hover { background: rgba(16,185,129,.06); color: var(--primary); }
        .profile-panel a i { width: 18px; text-align: center; font-size: .85rem; color: var(--text-muted); }
        .profile-panel a:hover i { color: var(--primary); }
        .profile-panel .divider {
            height: 1px;
            background: var(--border-color);
            margin: 4px 8px;
        }
        .profile-panel .logout-item { color: #ef4444; }
        .profile-panel .logout-item i { color: #ef4444; }
        .profile-panel .logout-item:hover { background: rgba(239,68,68,.08); color: #dc2626; }

        @media (max-width: 640px) {
            .top-header { padding: 0 14px; }
            .header-date { display: none; }
            .profile-toggle-name { display: none; }
            .notif-panel { width: 300px; right: -40px; }
        }
    </style>

    {{-- Left --}}
    <div class="header-left">
        <button class="sidebar-toggle" id="sidebarToggle" title="Toggle Sidebar">
            <i class="fas fa-bars"></i>
        </button>
        <div class="header-page-title">@yield('judul', 'Beranda')</div>
    </div>

    {{-- Right --}}
    <div class="header-right">
        {{-- Date --}}
        <div class="header-date">
            <i class="far fa-calendar"></i>
            <span>{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</span>
        </div>

        {{-- Notification --}}
        <div class="notif-dropdown" id="notifDropdown">
            <button class="notif-toggle" id="notifToggle" title="Notifikasi">
                <i class="fas fa-bell"></i>
                <span class="notif-badge" id="notifBadge" style="display:none;">0</span>
            </button>
            <div class="notif-panel">
                <div class="notif-panel-header">
                    <h4>Notifikasi</h4>
                    <a href="{{ route('staf.notifikasi.baca-semua') }}" class="notif-mark-all"
                       onclick="event.preventDefault(); fetch('{{ route('staf.notifikasi.baca-semua') }}', {method:'POST', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}}).then(()=>loadNotifications());">
                        Tandai semua
                    </a>
                </div>
                <div class="notif-panel-body" id="notifPanelBody">
                    <div class="notif-empty"><i class="far fa-bell-slash"></i><br>Belum ada notifikasi</div>
                </div>
                <div class="notif-panel-footer">
                    <a href="{{ route('staf.notifikasi.index') }}">Lihat Semua Notifikasi</a>
                </div>
            </div>
        </div>

        {{-- Profile --}}
        <div class="profile-dropdown" id="profileDropdown">
            <button class="profile-toggle" id="profileToggle">
                <div class="profile-toggle-avatar">
                    @if($headerUser->foto)
                        <img src="{{ asset('storage/' . $headerUser->foto) }}" alt="{{ $headerUser->nama }}">
                    @else
                        <div class="profile-toggle-avatar-placeholder">{{ strtoupper(substr($headerUser->nama, 0, 1)) }}</div>
                    @endif
                </div>
                <span class="profile-toggle-name">{{ Str::limit($headerUser->nama, 15) }}</span>
                <i class="fas fa-chevron-down chevron"></i>
            </button>
            <div class="profile-panel">
                <a href="{{ route('staf.profil.edit') }}">
                    <i class="fas fa-user"></i> Profil Saya
                </a>
                <a href="{{ route('staf.profil.password') }}">
                    <i class="fas fa-key"></i> Ubah Password
                </a>
                <div class="divider"></div>
                <form method="POST" action="{{ route('logout') }}" id="logoutFormHeader">
                    @csrf
                </form>
                <a href="#" class="logout-item" onclick="event.preventDefault();
                    Swal.fire({
                        title: 'Logout?',
                        text: 'Anda yakin ingin keluar dari sistem?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#10b981',
                        cancelButtonColor: '#64748b',
                        confirmButtonText: 'Ya, Keluar',
                        cancelButtonText: 'Batal'
                    }).then((r) => { if(r.isConfirmed) document.getElementById('logoutFormHeader').submit(); });">
                    <i class="fas fa-sign-out-alt"></i> Keluar
                </a>
            </div>
        </div>
    </div>
</header>

<script>
    {{-- Notification AJAX load --}}
    function loadNotifications() {
        fetch('{{ route("staf.notifikasi.json") }}')
            .then(r => r.json())
            .then(data => {
                const badge = document.getElementById('notifBadge');
                const body = document.getElementById('notifPanelBody');

                if (data.unread_count > 0) {
                    badge.style.display = 'flex';
                    badge.textContent = data.unread_count > 99 ? '99+' : data.unread_count;
                } else {
                    badge.style.display = 'none';
                }

                if (data.notifications && data.notifications.length > 0) {
                    let html = '';
                    data.notifications.forEach(n => {
                        const unreadClass = !n.read_at ? ' unread' : '';
                        const readUrl = '{{ url("staf/notifikasi") }}/' + n.id + '/baca';
                        html += `<a href="${readUrl}" class="notif-item${unreadClass}">
                            <div class="notif-item-icon"><i class="fas fa-bell"></i></div>
                            <div class="notif-item-content">
                                <div class="notif-item-text">${n.title || n.message || 'Notifikasi baru'}</div>
                                <div class="notif-item-time">${n.time_ago || ''}</div>
                            </div>
                        </a>`;
                    });
                    body.innerHTML = html;
                } else {
                    body.innerHTML = '<div class="notif-empty"><i class="far fa-bell-slash"></i><br>Belum ada notifikasi</div>';
                }
            })
            .catch(() => {});
    }

    document.addEventListener('DOMContentLoaded', loadNotifications);
</script>
