{{-- Footer Scripts for Staff Layout --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Sidebar Toggle ──────────────────────────────────────────────
    const sidebar = document.getElementById('sidebar');
    const toggle  = document.getElementById('sidebarToggle');
    const body    = document.body;
    const isMobile = () => window.innerWidth <= 991;

    if (toggle) {
        toggle.addEventListener('click', () => {
            if (isMobile()) {
                body.classList.toggle('sidebar-open');
            } else {
                body.classList.toggle('sidebar-collapsed');
                localStorage.setItem('sidebar-staf', body.classList.contains('sidebar-collapsed') ? 'collapsed' : 'expanded');
            }
        });
    }

    // Restore sidebar state on desktop
    if (!isMobile() && localStorage.getItem('sidebar-staf') === 'collapsed') {
        body.classList.add('sidebar-collapsed');
    }

    // Close sidebar on mobile when clicking outside
    document.addEventListener('click', e => {
        if (isMobile() && body.classList.contains('sidebar-open') && !sidebar.contains(e.target) && e.target !== toggle) {
            body.classList.remove('sidebar-open');
        }
    });

    // ── SweetAlert Confirm (data-confirm) ───────────────────────────
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('[data-confirm]');
        if (!btn) return;
        e.preventDefault();

        Swal.fire({
            title: 'Konfirmasi',
            text: btn.dataset.confirm || 'Yakin ingin melanjutkan?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#94a3b8',
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

    // ── Auto-close Alerts after 4 seconds ───────────────────────────
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(a => {
            try { new bootstrap.Alert(a).close(); } catch (err) {}
        });
    }, 4000);

    // ── Notification Count Polling (every 30s) ──────────────────────
    setInterval(() => {
        fetch('{{ route("staf.notifikasi.json") }}', {
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
        })
        .catch(() => {});
    }, 30000);

});
</script>
