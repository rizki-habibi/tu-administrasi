{{-- Footer Scripts for Kepala Sekolah Layout --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Sidebar Toggle ──
    const sidebar = document.getElementById('sidebar');
    const toggle  = document.getElementById('sidebarToggle');
    const body    = document.body;
    const isMobile = () => window.innerWidth <= 991;

    function doToggle() {
        if (isMobile()) {
            body.classList.toggle('sidebar-open');
        } else {
            body.classList.toggle('sidebar-collapsed');
            localStorage.setItem('sidebar-kepsek', body.classList.contains('sidebar-collapsed') ? 'collapsed' : 'expanded');
        }
    }

    if (toggle) toggle.addEventListener('click', doToggle);

    if (!isMobile() && localStorage.getItem('sidebar-kepsek') === 'collapsed') {
        body.classList.add('sidebar-collapsed');
    }

    document.addEventListener('click', e => {
        if (isMobile() && body.classList.contains('sidebar-open') && !sidebar.contains(e.target) && e.target !== toggle && (!toggle || !toggle.contains(e.target))) {
            body.classList.remove('sidebar-open');
        }
    });

    // ── Submenu Toggle (supports nested) ──
    document.querySelectorAll('[data-toggle="submenu"]').forEach(el => {
        el.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const navItem = this.closest('.nav-item');
            if (!navItem) return;
            const isOpening = !navItem.classList.contains('open');
            navItem.classList.toggle('open');

            if (isOpening) {
                let parent = navItem.parentElement;
                while (parent) {
                    if (parent.classList.contains('submenu') || parent.classList.contains('nav-group-items')) {
                        parent.style.maxHeight = parent.scrollHeight + 500 + 'px';
                    }
                    parent = parent.parentElement;
                }
            } else {
                const sub = navItem.querySelector('.submenu');
                if (sub) sub.style.maxHeight = '';
                setTimeout(() => {
                    let parent = navItem.parentElement;
                    while (parent) {
                        if (parent.classList.contains('submenu') || parent.classList.contains('nav-group-items')) {
                            parent.style.maxHeight = parent.scrollHeight + 'px';
                        }
                        parent = parent.parentElement;
                    }
                }, 50);
            }
        });
    });

    // ── Sidebar Search ──
    const searchInput = document.getElementById('sidebarSearch');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            document.querySelectorAll('.nav-group').forEach(group => {
                const items = group.querySelectorAll('.nav-item');
                let groupMatch = false;
                items.forEach(item => {
                    const text = item.textContent.toLowerCase();
                    const match = !query || text.includes(query);
                    item.style.display = match ? '' : 'none';
                    if (match) groupMatch = true;
                });
                group.style.display = groupMatch || !query ? '' : 'none';
                group.classList.toggle('search-match', !!(query && groupMatch));
            });
        });
    }

    // ═══ SETTINGS RIGHT DRAWER ═══
    const settingsDrawer  = document.getElementById('settingsDrawer');
    const settingsOverlay = document.getElementById('settingsOverlay');
    const closeSettingsDrawer = document.getElementById('closeSettingsDrawer');
    const headerSettingsBtn = document.getElementById('headerSettingsBtn');

    function openDrawer() {
        if (settingsDrawer)  settingsDrawer.classList.add('open');
        if (settingsOverlay) settingsOverlay.classList.add('open');
    }
    function closeDrawer() {
        if (settingsDrawer)  settingsDrawer.classList.remove('open');
        if (settingsOverlay) settingsOverlay.classList.remove('open');
    }

    if (headerSettingsBtn) headerSettingsBtn.addEventListener('click', openDrawer);
    if (closeSettingsDrawer) closeSettingsDrawer.addEventListener('click', closeDrawer);
    if (settingsOverlay) settingsOverlay.addEventListener('click', closeDrawer);

    // ── Dark Mode Toggle ──
    const darkToggle = document.getElementById('darkModeToggle');
    if (darkToggle) {
        darkToggle.checked = localStorage.getItem('darkMode') === '1';
        if (darkToggle.checked) body.classList.add('dark-mode');
        darkToggle.addEventListener('change', function() {
            body.classList.toggle('dark-mode', this.checked);
            localStorage.setItem('darkMode', this.checked ? '1' : '0');
        });
    }

    // ── SweetAlert Confirm ──
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('[data-confirm]');
        if (!btn) return;
        e.preventDefault();
        Swal.fire({
            title: 'Konfirmasi',
            text: btn.dataset.confirm || 'Yakin ingin melanjutkan?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d97706',
            cancelButtonColor: '#a8a29e',
            confirmButtonText: 'Ya',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: { popup: 'rounded-4' }
        }).then(r => {
            if (r.isConfirmed) {
                const form = btn.closest('form');
                if (form) form.submit();
                else location.href = btn.href;
            }
        });
    });

    // ── Auto-close Alerts ──
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(a => {
            try { new bootstrap.Alert(a).close(); } catch (err) {}
        });
    }, 4000);

    // ── Notification Polling (30s) ──
    setInterval(() => {
        fetch('{{ route("kepala-sekolah.notifikasi.json") }}', {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(data => {
            const badge = document.getElementById('notifBadge');
            const count = document.getElementById('notifCount');
            if (count) count.textContent = data.unread_count;
            if (badge) badge.style.display = data.unread_count > 0 ? '' : 'none';
        })
        .catch(() => {});
    }, 30000);

    // ── Close on Escape ──
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') { closeDrawer(); }
    });

});
</script>
