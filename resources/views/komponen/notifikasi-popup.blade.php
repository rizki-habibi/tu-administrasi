{{-- ═══ SISTEM NOTIFIKASI & PEMBERITAHUAN ═══ --}}
{{-- Include di semua layout: admin, staf, kepala-sekolah --}}

@auth
@php
    $routePrefix = auth()->user()->getRoutePrefix();
    $pengaturanNotif = \App\Models\PengaturanPengguna::semuaUntuk(auth()->id());
    $popupAktif = ($pengaturanNotif['notifikasi_popup'] ?? 'true') === 'true';
    $popupDelay = (int)($pengaturanNotif['notifikasi_popup_delay'] ?? 5); // menit
    $pushAktif = ($pengaturanNotif['notifikasi_push'] ?? 'false') === 'true';
@endphp

{{-- Popup Overlay --}}
<div id="notifPopupOverlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:9998;opacity:0;transition:opacity .3s;"></div>

{{-- Main Notification Popup --}}
<div id="notifPopup" style="display:none;position:fixed;top:50%;left:50%;transform:translate(-50%,-50%) scale(.9);z-index:9999;width:440px;max-width:92vw;max-height:80vh;background:#fff;border-radius:16px;box-shadow:0 25px 60px rgba(0,0,0,.25);overflow:hidden;opacity:0;transition:all .3s ease;">
    {{-- Header --}}
    <div style="background:linear-gradient(135deg,#6366f1,#8b5cf6);padding:18px 20px;color:#fff;display:flex;align-items:center;justify-content:space-between;">
        <div style="display:flex;align-items:center;gap:10px;">
            <div style="width:36px;height:36px;border-radius:10px;background:rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;">
                <i class="bi bi-bell-fill" style="font-size:1.1rem;"></i>
            </div>
            <div>
                <div style="font-weight:700;font-size:.92rem;">Pemberitahuan Penting</div>
                <div style="font-size:.7rem;opacity:.85;" id="notifPopupCount"></div>
            </div>
        </div>
        <button onclick="tutupNotifPopup()" style="background:rgba(255,255,255,.15);border:none;color:#fff;width:30px;height:30px;border-radius:8px;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:1rem;transition:background .2s;">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>

    {{-- Content --}}
    <div id="notifPopupBody" style="padding:16px 20px;overflow-y:auto;max-height:50vh;">
        <div style="text-align:center;padding:30px;color:#94a3b8;">
            <i class="bi bi-arrow-repeat" style="font-size:2rem;animation:spin 1s linear infinite;display:inline-block;"></i>
            <p style="margin:8px 0 0;font-size:.82rem;">Memuat pemberitahuan...</p>
        </div>
    </div>

    {{-- Footer --}}
    <div style="padding:12px 20px;background:#f8fafc;border-top:1px solid #e5e7eb;display:flex;justify-content:space-between;align-items:center;">
        <button onclick="tandaiSemuaDibaca()" style="background:none;border:none;color:#6366f1;font-size:.78rem;cursor:pointer;font-weight:500;">
            <i class="bi bi-check-all me-1"></i>Tandai Semua Dibaca
        </button>
        <a href="{{ route($routePrefix . '.notifikasi.index') }}" style="color:#6366f1;font-size:.78rem;text-decoration:none;font-weight:500;">
            Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
        </a>
    </div>
</div>

{{-- Storage Warning Popup --}}
<div id="storageWarningPopup" style="display:none;position:fixed;bottom:90px;right:20px;z-index:9990;width:340px;background:#fff;border-radius:14px;box-shadow:0 10px 40px rgba(0,0,0,.15);overflow:hidden;opacity:0;transition:all .3s ease;">
    <div style="padding:14px 16px;background:linear-gradient(135deg,#f59e0b,#f97316);color:#fff;display:flex;align-items:center;gap:10px;">
        <i class="bi bi-cloud-fill" style="font-size:1.2rem;"></i>
        <div>
            <div style="font-weight:700;font-size:.82rem;">Penyimpanan Hampir Penuh</div>
            <div style="font-size:.68rem;opacity:.85;" id="storageDesc"></div>
        </div>
        <button onclick="tutupStorageWarning()" style="margin-left:auto;background:rgba(255,255,255,.2);border:none;color:#fff;width:24px;height:24px;border-radius:6px;cursor:pointer;display:flex;align-items:center;justify-content:center;">
            <i class="bi bi-x"></i>
        </button>
    </div>
    <div style="padding:12px 16px;">
        <div style="background:#f1f5f9;border-radius:8px;height:8px;overflow:hidden;margin-bottom:8px;">
            <div id="storageBar" style="height:100%;border-radius:8px;transition:width .5s;"></div>
        </div>
        <div style="display:flex;justify-content:space-between;font-size:.72rem;color:#64748b;">
            <span id="storageUsed"></span>
            <span id="storageLimit"></span>
        </div>
        <p style="margin:10px 0 0;font-size:.72rem;color:#64748b;">
            <i class="bi bi-info-circle me-1"></i>Pertimbangkan untuk menghapus file lama atau menggunakan Google Drive untuk cadangan.
        </p>
    </div>
</div>

{{-- Toast Notification (pojok kanan atas) --}}
<div id="notifToastContainer" style="position:fixed;top:70px;right:20px;z-index:9995;display:flex;flex-direction:column;gap:8px;pointer-events:none;"></div>

<style>
@keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
@keyframes slideInRight { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
@keyframes slideOutRight { from { transform: translateX(0); opacity: 1; } to { transform: translateX(100%); opacity: 0; } }
.notif-toast {
    pointer-events: auto;
    background: #fff; border-radius: 12px; box-shadow: 0 8px 30px rgba(0,0,0,.12);
    padding: 12px 16px; display: flex; align-items: flex-start; gap: 10px;
    max-width: 360px; animation: slideInRight .3s ease forwards;
    border-left: 4px solid #6366f1; cursor: pointer;
}
.notif-toast.closing { animation: slideOutRight .3s ease forwards; }
.notif-toast .nt-icon { width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: .85rem; color: #fff; }
.notif-toast .nt-content { flex: 1; }
.notif-toast .nt-title { font-size: .78rem; font-weight: 600; color: #1e293b; margin-bottom: 2px; }
.notif-toast .nt-msg { font-size: .72rem; color: #64748b; line-height: 1.4; }
.notif-toast .nt-time { font-size: .65rem; color: #94a3b8; margin-top: 3px; }
.notif-item { display: flex; gap: 12px; padding: 12px; border-radius: 10px; margin-bottom: 8px; background: #f8fafc; transition: background .2s; cursor: pointer; }
.notif-item:hover { background: #eef2ff; }
.notif-item .ni-icon { width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; color: #fff; font-size: .9rem; }
.notif-item .ni-body { flex: 1; }
.notif-item .ni-title { font-size: .8rem; font-weight: 600; color: #1e293b; margin-bottom: 2px; }
.notif-item .ni-msg { font-size: .74rem; color: #64748b; line-height: 1.4; }
.notif-item .ni-time { font-size: .68rem; color: #94a3b8; margin-top: 4px; }
</style>

<script>
(function() {
    const NOTIF_PENTING_URL = '{{ route("api.notifikasi-penting") }}';
    const NOTIF_BACA_URL = '{{ route("api.notifikasi-baca") }}';
    const PUSH_SUBSCRIBE_URL = '{{ route("api.push-subscribe") }}';
    const PUSH_UNSUBSCRIBE_URL = '{{ route("api.push-unsubscribe") }}';
    const CEK_STORAGE_URL = '{{ route("api.cek-storage") }}';
    const CSRF = '{{ csrf_token() }}';
    const POPUP_AKTIF = {{ $popupAktif ? 'true' : 'false' }};
    const POPUP_DELAY_MENIT = {{ $popupDelay }};
    const PUSH_AKTIF = {{ $pushAktif ? 'true' : 'false' }};
    const NOTIF_INDEX_URL = '{{ route($routePrefix . ".notifikasi.index") }}';

    function jenisIcon(jenis) {
        const icons = { kehadiran: 'bi-fingerprint', izin: 'bi-calendar-check', event: 'bi-calendar-event', laporan: 'bi-file-text', sistem: 'bi-gear', pengumuman: 'bi-megaphone' };
        return icons[jenis] || 'bi-bell';
    }
    function jenisBg(jenis) {
        const colors = { kehadiran: '#10b981', izin: '#06b6d4', event: '#6366f1', laporan: '#f59e0b', sistem: '#ef4444', pengumuman: '#1e293b' };
        return colors[jenis] || '#6366f1';
    }

    // === TOAST NOTIFICATION ===
    window.tampilkanToast = function(judul, pesan, jenis, onClick) {
        const container = document.getElementById('notifToastContainer');
        const toast = document.createElement('div');
        toast.className = 'notif-toast';
        toast.style.borderLeftColor = jenisBg(jenis);
        toast.innerHTML = `
            <div class="nt-icon" style="background:${jenisBg(jenis)}"><i class="bi ${jenisIcon(jenis)}"></i></div>
            <div class="nt-content">
                <div class="nt-title">${judul}</div>
                <div class="nt-msg">${pesan}</div>
                <div class="nt-time">Baru saja</div>
            </div>
        `;
        if (onClick) toast.addEventListener('click', onClick);
        container.appendChild(toast);

        // Play sound if enabled
        const suaraAktif = localStorage.getItem('notifikasi_suara') !== 'false';
        if (suaraAktif && window.AudioContext) {
            try {
                const ctx = new AudioContext();
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.connect(gain); gain.connect(ctx.destination);
                osc.frequency.value = 880; osc.type = 'sine';
                gain.gain.value = 0.1;
                osc.start(); osc.stop(ctx.currentTime + 0.15);
            } catch(e) {}
        }

        setTimeout(() => { toast.classList.add('closing'); setTimeout(() => toast.remove(), 300); }, 8000);
    };

    // === NOTIFICATION POPUP ===
    window.bukaNotifPopup = function() {
        const overlay = document.getElementById('notifPopupOverlay');
        const popup = document.getElementById('notifPopup');
        overlay.style.display = 'block';
        popup.style.display = 'block';
        requestAnimationFrame(() => {
            overlay.style.opacity = '1';
            popup.style.opacity = '1';
            popup.style.transform = 'translate(-50%,-50%) scale(1)';
        });
        loadNotifPopupContent();
    };

    window.tutupNotifPopup = function() {
        const overlay = document.getElementById('notifPopupOverlay');
        const popup = document.getElementById('notifPopup');
        overlay.style.opacity = '0';
        popup.style.opacity = '0';
        popup.style.transform = 'translate(-50%,-50%) scale(.9)';
        setTimeout(() => { overlay.style.display = 'none'; popup.style.display = 'none'; }, 300);
    };

    document.getElementById('notifPopupOverlay')?.addEventListener('click', tutupNotifPopup);

    async function loadNotifPopupContent() {
        const body = document.getElementById('notifPopupBody');
        const countEl = document.getElementById('notifPopupCount');
        try {
            const res = await fetch(NOTIF_PENTING_URL, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }});
            const data = await res.json();
            countEl.textContent = `${data.total_belum_dibaca} pemberitahuan belum dibaca`;

            if (!data.notifikasi || data.notifikasi.length === 0) {
                body.innerHTML = `
                    <div style="text-align:center;padding:30px;color:#94a3b8;">
                        <i class="bi bi-check-circle" style="font-size:2.5rem;color:#10b981;"></i>
                        <p style="margin:10px 0 0;font-size:.85rem;color:#475569;">Tidak ada pemberitahuan penting</p>
                        <p style="margin:4px 0 0;font-size:.72rem;">Anda sudah membaca semuanya!</p>
                    </div>`;
                return;
            }

            body.innerHTML = data.notifikasi.map(n => `
                <div class="notif-item" onclick="bacaNotif(${n.id}, '${n.tautan || ''}')">
                    <div class="ni-icon" style="background:${jenisBg(n.jenis)}"><i class="bi ${jenisIcon(n.jenis)}"></i></div>
                    <div class="ni-body">
                        <div class="ni-title">${n.judul}</div>
                        <div class="ni-msg">${n.pesan}</div>
                        <div class="ni-time"><i class="bi bi-clock me-1"></i>${n.waktu}</div>
                    </div>
                </div>
            `).join('');
        } catch(e) {
            body.innerHTML = '<div style="text-align:center;padding:20px;color:#ef4444;font-size:.82rem;">Gagal memuat pemberitahuan</div>';
        }
    }

    window.bacaNotif = async function(id, tautan) {
        try {
            await fetch(NOTIF_BACA_URL, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
                body: JSON.stringify({ id })
            });
        } catch(e) {}
        if (tautan) window.location.href = tautan;
        else { tutupNotifPopup(); loadNotifPopupContent(); }
    };

    window.tandaiSemuaDibaca = async function() {
        const prefix = '{{ $routePrefix }}';
        try {
            await fetch('/' + prefix + '/notifikasi/baca-semua', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
            });
            loadNotifPopupContent();
            tampilkanToast('Berhasil', 'Semua notifikasi ditandai sudah dibaca', 'sistem');
        } catch(e) {}
    };

    // === AUTO POPUP (setelah delay menit) ===
    if (POPUP_AKTIF && POPUP_DELAY_MENIT > 0) {
        const popupKey = 'lastNotifPopup_' + Date.now().toString().substr(0, 8);
        const lastShown = sessionStorage.getItem('notifPopupShown');

        if (!lastShown) {
            setTimeout(async () => {
                try {
                    const res = await fetch(NOTIF_PENTING_URL, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }});
                    const data = await res.json();
                    if (data.notifikasi && data.notifikasi.length > 0) {
                        bukaNotifPopup();
                        sessionStorage.setItem('notifPopupShown', '1');
                    }
                } catch(e) {}
            }, POPUP_DELAY_MENIT * 60 * 1000);
        }
    }

    // === POLLING setiap 60 detik untuk toast notif baru ===
    let lastNotifId = 0;
    async function pollNotifikasi() {
        try {
            const res = await fetch(NOTIF_PENTING_URL, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }});
            const data = await res.json();

            // Update badge di header jika ada
            const badges = document.querySelectorAll('.notif-badge-count');
            badges.forEach(b => {
                b.textContent = data.total_belum_dibaca;
                b.style.display = data.total_belum_dibaca > 0 ? 'flex' : 'none';
            });

            // Show toast for new notifications
            if (data.notifikasi && data.notifikasi.length > 0) {
                const newestId = data.notifikasi[0].id;
                if (lastNotifId > 0 && newestId > lastNotifId) {
                    const newOnes = data.notifikasi.filter(n => n.id > lastNotifId);
                    newOnes.forEach(n => tampilkanToast(n.judul, n.pesan, n.jenis, () => bacaNotif(n.id, n.tautan)));
                }
                lastNotifId = newestId;
            }
        } catch(e) {}
    }

    // Poll pertama kali setelah 5 detik, lalu interval 60 detik
    setTimeout(pollNotifikasi, 5000);
    setInterval(pollNotifikasi, 60000);

    // === PUSH NOTIFICATION REGISTRATION ===
    if (PUSH_AKTIF && 'serviceWorker' in navigator && 'PushManager' in window) {
        navigator.serviceWorker.register('/sw.js').then(reg => {
            console.log('[SW] Service Worker registered');
        }).catch(err => console.warn('[SW] Registration failed:', err));
    }

    window.aktifkanPush = async function() {
        if (!('serviceWorker' in navigator) || !('PushManager' in window)) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({ icon: 'warning', title: 'Tidak Didukung', text: 'Browser Anda tidak mendukung notifikasi push.', confirmButtonColor: '#6366f1' });
            }
            return false;
        }

        try {
            const permission = await Notification.requestPermission();
            if (permission !== 'granted') {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({ icon: 'info', title: 'Izin Ditolak', text: 'Anda perlu mengizinkan notifikasi di browser untuk fitur ini.', confirmButtonColor: '#6366f1' });
                }
                return false;
            }

            const reg = await navigator.serviceWorker.register('/sw.js');
            await navigator.serviceWorker.ready;

            // Subscribe (tanpa VAPID key, fallback ke polling-based push simulation)
            // Untuk production, generate VAPID keys dan set applicationServerKey
            try {
                const sub = await reg.pushManager.subscribe({
                    userVisibleOnly: true,
                    applicationServerKey: null // Set VAPID public key di production
                });

                await fetch(PUSH_SUBSCRIBE_URL, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
                    body: JSON.stringify(sub.toJSON())
                });
            } catch(subErr) {
                // Fallback: simpan flag bahwa push diinginkan, gunakan polling
                await fetch(PUSH_SUBSCRIBE_URL, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
                    body: JSON.stringify({ endpoint: 'polling-fallback', keys: { p256dh: 'none', auth: 'none' }})
                });
            }

            return true;
        } catch(e) {
            console.warn('[Push] Error:', e);
            return false;
        }
    };

    window.nonaktifkanPush = async function() {
        try {
            if ('serviceWorker' in navigator) {
                const reg = await navigator.serviceWorker.getRegistration();
                if (reg) {
                    const sub = await reg.pushManager.getSubscription();
                    if (sub) await sub.unsubscribe();
                }
            }
            await fetch(PUSH_UNSUBSCRIBE_URL, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
            });
        } catch(e) {}
    };

    // === STORAGE MONITORING ===
    async function cekStorageUsage() {
        try {
            const res = await fetch(CEK_STORAGE_URL, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }});
            const data = await res.json();

            if (data.hampir_penuh || data.penuh) {
                const popup = document.getElementById('storageWarningPopup');
                const bar = document.getElementById('storageBar');
                const desc = document.getElementById('storageDesc');
                const used = document.getElementById('storageUsed');
                const limit = document.getElementById('storageLimit');

                const pct = Math.min(data.persentase, 100);
                bar.style.width = pct + '%';
                bar.style.background = data.penuh ? '#ef4444' : '#f59e0b';
                desc.textContent = `Terpakai ${data.persentase}% dari ${data.limit_format}`;
                used.textContent = data.digunakan_format + ' terpakai';
                limit.textContent = data.limit_format + ' total';

                // Hanya tampilkan jika belum ditutup di sesi ini
                const dismissKey = 'storageDismissed';
                if (!sessionStorage.getItem(dismissKey)) {
                    popup.style.display = 'block';
                    requestAnimationFrame(() => { popup.style.opacity = '1'; });
                }
            }
        } catch(e) {}
    }

    window.tutupStorageWarning = function() {
        const popup = document.getElementById('storageWarningPopup');
        popup.style.opacity = '0';
        setTimeout(() => popup.style.display = 'none', 300);
        sessionStorage.setItem('storageDismissed', '1');
    };

    // Cek storage setelah 15 detik
    setTimeout(cekStorageUsage, 15000);
})();
</script>
@endauth
