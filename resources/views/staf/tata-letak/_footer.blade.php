<footer style="padding:16px 28px;text-align:center;font-size:.75rem;color:var(--text-muted);border-top:1px solid var(--border-color);background:#fff;">
    &copy; {{ date('Y') }} TU Administrasi &mdash; SMA Negeri 2 Jember. All rights reserved.
</footer>

<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ═══════════════════════════════════════════
       Sidebar Toggle (localStorage key: sidebar-staf)
    ═══════════════════════════════════════════ */
    const body = document.body;
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('sidebarToggle');
    const overlay = document.getElementById('sidebarOverlay');
    const STORAGE_KEY = 'sidebar-staf';
    const isMobile = () => window.innerWidth <= 1024;

    // Restore state from localStorage
    if (!isMobile()) {
        const saved = localStorage.getItem(STORAGE_KEY);
        if (saved === 'collapsed') {
            body.classList.add('sidebar-collapsed');
        }
    }

    if (toggleBtn) {
        toggleBtn.addEventListener('click', function () {
            if (isMobile()) {
                body.classList.toggle('sidebar-open');
            } else {
                body.classList.toggle('sidebar-collapsed');
                localStorage.setItem(STORAGE_KEY,
                    body.classList.contains('sidebar-collapsed') ? 'collapsed' : 'expanded'
                );
            }
        });
    }

    if (overlay) {
        overlay.addEventListener('click', function () {
            body.classList.remove('sidebar-open');
        });
    }

    /* ═══════════════════════════════════════════
       Submenu Accordion
    ═══════════════════════════════════════════ */
    document.querySelectorAll('.submenu-toggle').forEach(function (toggle) {
        toggle.addEventListener('click', function (e) {
            e.preventDefault();
            const parent = this.closest('.nav-item');
            const wasOpen = parent.classList.contains('open');

            // Close siblings in same section
            const section = parent.closest('.nav-section');
            if (section) {
                section.querySelectorAll('.nav-item.open').forEach(function (item) {
                    if (item !== parent) item.classList.remove('open');
                });
            }

            parent.classList.toggle('open', !wasOpen);
        });
    });

    /* ═══════════════════════════════════════════
       SweetAlert Confirm (data-confirm)
    ═══════════════════════════════════════════ */
    document.querySelectorAll('[data-confirm]').forEach(function (el) {
        el.addEventListener('click', function (e) {
            e.preventDefault();
            const message = this.getAttribute('data-confirm') || 'Anda yakin ingin melanjutkan?';
            const form = this.closest('form');
            const href = this.getAttribute('href');

            Swal.fire({
                title: 'Konfirmasi',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Lanjutkan',
                cancelButtonText: 'Batal'
            }).then(function (result) {
                if (result.isConfirmed) {
                    if (form) {
                        form.submit();
                    } else if (href) {
                        window.location.href = href;
                    }
                }
            });
        });
    });

    /* ═══════════════════════════════════════════
       Auto-close Alerts
    ═══════════════════════════════════════════ */
    document.querySelectorAll('.auto-dismiss').forEach(function (alert) {
        setTimeout(function () {
            alert.style.transition = 'opacity .4s ease, transform .4s ease';
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-8px)';
            setTimeout(function () { alert.remove(); }, 400);
        }, 5000);
    });

    /* ═══════════════════════════════════════════
       Notification Dropdown Toggle
    ═══════════════════════════════════════════ */
    const notifDropdown = document.getElementById('notifDropdown');
    const notifToggle = document.getElementById('notifToggle');

    if (notifToggle) {
        notifToggle.addEventListener('click', function (e) {
            e.stopPropagation();
            notifDropdown.classList.toggle('open');
            // Close profile dropdown
            const profileDD = document.getElementById('profileDropdown');
            if (profileDD) profileDD.classList.remove('open');
        });
    }

    /* ═══════════════════════════════════════════
       Profile Dropdown Toggle
    ═══════════════════════════════════════════ */
    const profileDropdown = document.getElementById('profileDropdown');
    const profileToggle = document.getElementById('profileToggle');

    if (profileToggle) {
        profileToggle.addEventListener('click', function (e) {
            e.stopPropagation();
            profileDropdown.classList.toggle('open');
            // Close notif dropdown
            if (notifDropdown) notifDropdown.classList.remove('open');
        });
    }

    // Close dropdowns when clicking outside
    document.addEventListener('click', function (e) {
        if (notifDropdown && !notifDropdown.contains(e.target)) {
            notifDropdown.classList.remove('open');
        }
        if (profileDropdown && !profileDropdown.contains(e.target)) {
            profileDropdown.classList.remove('open');
        }
    });

    /* ═══════════════════════════════════════════
       Notification AJAX Poll (every 30s)
    ═══════════════════════════════════════════ */
    if (typeof loadNotifications === 'function') {
        setInterval(loadNotifications, 30000);
    }

});
</script>
