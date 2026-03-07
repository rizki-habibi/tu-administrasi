{{-- Top Header Bar for Admin --}}
@php
    $unreadNotif = \App\Models\Notifikasi::where('sudah_dibaca', false)->count();
@endphp

<header class="top-header">
    {{-- Sidebar Toggle --}}
    <button class="sidebar-toggle" id="sidebarToggle"><i class="bi bi-list"></i></button>

    {{-- Page Title --}}
    <span class="header-title">@yield('judul', 'Beranda')</span>

    {{-- Right Side --}}
    <div class="header-right">
        {{-- Date (clickable → notule popup) --}}
        <button class="header-date d-none d-md-flex" id="headerDateBtn" style="background:none;border:1px solid #e5e7eb;border-radius:10px;padding:6px 14px;cursor:pointer;transition:.2s;gap:6px;align-items:center;" title="Klik untuk catatan kegiatan hari ini">
            <i class="bi bi-calendar3"></i> {{ now()->translatedFormat('d F Y') }}
        </button>

        {{-- Notule Popup --}}
        <div id="notulePopup" class="card border-0 shadow-lg d-none" style="position:fixed;top:64px;right:200px;width:440px;max-height:560px;border-radius:16px;z-index:1060;overflow:hidden;">
            <div class="card-header py-3 border-0 text-white" style="background:linear-gradient(135deg,#6366f1,#8b5cf6);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fw-bold mb-0"><i class="bi bi-journal-text me-2"></i>Notule Kegiatan</h6>
                        <small class="opacity-75" id="notuleTanggalLabel">{{ now()->translatedFormat('l, d F Y') }}</small>
                    </div>
                    <button class="btn btn-sm btn-light" style="opacity:.8;" onclick="document.getElementById('notulePopup').classList.add('d-none')">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            </div>
            <div class="card-body p-3" style="max-height:340px;overflow-y:auto;" id="notuleList">
                <div class="text-center py-4 text-muted" style="font-size:.82rem;">
                    <div class="spinner-border spinner-border-sm text-primary me-1"></div> Memuat...
                </div>
            </div>
            <div class="card-footer bg-white border-top p-3">
                <form id="notuleForm">
                    <input type="hidden" name="tanggal" value="{{ now()->toDateString() }}">
                    <textarea name="kegiatan" class="form-control form-control-sm mb-2" rows="2" placeholder="Tulis kegiatan hari ini..." style="font-size:.82rem;resize:none;" required></textarea>
                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <input type="text" name="hasil" class="form-control form-control-sm" placeholder="Hasil..." style="font-size:.78rem;">
                        </div>
                        <div class="col-6">
                            <input type="text" name="kendala" class="form-control form-control-sm" placeholder="Kendala..." style="font-size:.78rem;">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm w-100"><i class="bi bi-save me-1"></i>Simpan Notule</button>
                </form>
            </div>
        </div>

        {{-- AI Button → opens AI popup --}}
        <button class="header-tool-btn" id="headerAiBtn" title="SIMPEG-AI Assistant">
            <i class="bi bi-robot"></i>
        </button>

        {{-- Settings Button → opens right drawer --}}
        <button class="header-tool-btn" id="headerSettingsBtn" title="Pengaturan & Alat Cepat">
            <i class="bi bi-gear-fill"></i>
        </button>

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
                <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom" style="background:#f8fafc;">
                    <h6 class="mb-0 fw-bold" style="font-size:.85rem;">Notifikasi</h6>
                    <span class="badge bg-danger" id="notifCount" style="font-size:.65rem;">{{ $unreadNotif }}</span>
                </div>
                {{-- Notification List (loaded via AJAX) --}}
                <div id="notifList" style="max-height:320px;overflow-y:auto;">
                    <div class="text-center py-4 text-muted" style="font-size:.8rem;">
                        <div class="spinner-border spinner-border-sm text-primary me-1"></div> Memuat...
                    </div>
                </div>
                {{-- Footer Link --}}
                <div class="border-top text-center py-2" style="background:#f8fafc;">
                    <a href="{{ route('admin.notifikasi.index') }}" class="text-primary text-decoration-none fw-semibold" style="font-size:.78rem;">
                        Lihat Semua Notifikasi <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- User Profile Dropdown --}}
        <div class="dropdown">
            <button type="button" class="header-profile" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="avatar-sm">{{ strtoupper(substr(Auth::user()->nama, 0, 2)) }}</div>
                <span class="name d-none d-md-block">{{ Auth::user()->nama }}</span>
                <i class="bi bi-chevron-down" style="font-size:.65rem;color:#94a3b8;"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li class="px-3 py-2">
                    <div class="fw-semibold" style="font-size:.82rem;">{{ Auth::user()->nama }}</div>
                    <div class="text-muted" style="font-size:.68rem;">{{ Auth::user()->email }}</div>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item" href="{{ route('admin.pengaturan.index') }}">
                        <i class="bi bi-gear me-2 text-primary"></i>Pengaturan Sistem
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="{{ route('admin.kehadiran.pengaturan') }}">
                        <i class="bi bi-fingerprint me-2 text-success"></i>Pengaturan Absensi
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="{{ route('admin.log-aktivitas.index') }}">
                        <i class="bi bi-clock-history me-2 text-info"></i>Log Aktivitas
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item text-danger" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="bi bi-box-arrow-left me-2"></i>Keluar
                    </a>
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
                // Only load when dropdown is opening
                const dropdown = bootstrap.Dropdown.getOrCreateInstance(notifToggle);
                loadNotifications();
            });
        }

        function loadNotifications() {
            const list = document.getElementById('notifList');
            list.innerHTML = '<div class="text-center py-4 text-muted" style="font-size:.8rem;"><div class="spinner-border spinner-border-sm text-primary me-1"></div> Memuat...</div>';

            fetch('{{ route("admin.notifikasi.json") }}', {
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
                    list.innerHTML = '<div class="text-center py-4 text-muted" style="font-size:.82rem;"><i class="bi bi-bell-slash" style="font-size:1.5rem;"></i><p class="mb-0 mt-1">Tidak ada notifikasi baru</p></div>';
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
                    <div class="d-flex gap-2 px-3 py-2 border-bottom notif-item" style="transition:.15s;background:#f0f4ff;">
                        <div class="flex-shrink-0 mt-1">
                            <i class="bi ${icons[n.jenis] || icons.info}" style="font-size:1rem;"></i>
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <div class="fw-semibold text-dark" style="font-size:.82rem;">${n.judul}</div>
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

        // ── Notule Kegiatan (Header Date Popup) ──
        const dateBtn = document.getElementById('headerDateBtn');
        const notulePopup = document.getElementById('notulePopup');
        if (dateBtn && notulePopup) {
            dateBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                notulePopup.classList.toggle('d-none');
                if (!notulePopup.classList.contains('d-none')) {
                    loadNotule();
                }
            });

            // Close on outside click
            document.addEventListener('click', function(e) {
                if (!notulePopup.contains(e.target) && e.target !== dateBtn && !dateBtn.contains(e.target)) {
                    notulePopup.classList.add('d-none');
                }
            });
        }

        function loadNotule() {
            const list = document.getElementById('notuleList');
            list.innerHTML = '<div class="text-center py-3 text-muted"><div class="spinner-border spinner-border-sm text-primary me-1"></div> Memuat...</div>';

            fetch('{{ route("admin.notule.index") }}?tanggal={{ now()->toDateString() }}', {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => r.json())
            .then(data => {
                if (data.catatan.length === 0) {
                    list.innerHTML = '<div class="text-center py-4 text-muted" style="font-size:.82rem;"><i class="bi bi-journal-x" style="font-size:1.8rem;"></i><p class="mt-2 mb-0">Belum ada catatan kegiatan hari ini</p><small>Tulis notule di form bawah</small></div>';
                    return;
                }
                list.innerHTML = data.catatan.map(c => `
                    <div class="border rounded-3 p-2 mb-2" style="font-size:.82rem;">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <span class="fw-semibold text-primary"><i class="bi bi-person-circle me-1"></i>${c.pengguna ? c.pengguna.nama : 'Admin'}</span>
                            <span class="badge bg-${c.status === 'final' ? 'success' : 'warning'}-subtle text-${c.status === 'final' ? 'success' : 'warning'}" style="font-size:.65rem;">${c.status === 'final' ? 'Final' : 'Draf'}</span>
                        </div>
                        <div class="mb-1"><i class="bi bi-activity me-1 text-muted"></i>${c.kegiatan}</div>
                        ${c.hasil ? '<div class="text-success" style="font-size:.78rem;"><i class="bi bi-check-circle me-1"></i><strong>Hasil:</strong> ' + c.hasil + '</div>' : ''}
                        ${c.kendala ? '<div class="text-danger" style="font-size:.78rem;"><i class="bi bi-exclamation-circle me-1"></i><strong>Kendala:</strong> ' + c.kendala + '</div>' : ''}
                        ${c.rencana_besok ? '<div class="text-info" style="font-size:.78rem;"><i class="bi bi-arrow-right-circle me-1"></i><strong>Rencana:</strong> ' + c.rencana_besok + '</div>' : ''}
                    </div>
                `).join('');
            })
            .catch(() => {
                list.innerHTML = '<div class="text-center py-4 text-muted">Gagal memuat data</div>';
            });
        }

        // Notule form submit
        const notuleForm = document.getElementById('notuleForm');
        if (notuleForm) {
            notuleForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const btn = this.querySelector('button[type="submit"]');
                btn.disabled = true;
                btn.innerHTML = '<div class="spinner-border spinner-border-sm me-1"></div>Menyimpan...';

                fetch('{{ route("admin.notule.store") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(Object.fromEntries(formData))
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        notuleForm.reset();
                        notuleForm.querySelector('[name="tanggal"]').value = '{{ now()->toDateString() }}';
                        loadNotule();
                        // Update notification badge
                        const badge = document.getElementById('notifBadge');
                        if (badge) badge.style.display = '';
                    }
                    btn.disabled = false;
                    btn.innerHTML = '<i class="bi bi-save me-1"></i>Simpan Notule';
                })
                .catch(() => {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="bi bi-save me-1"></i>Simpan Notule';
                });
            });
        }
    });
</script>
