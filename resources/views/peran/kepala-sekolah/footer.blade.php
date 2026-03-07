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

    // ═══ AI CHAT POPUP ═══
    const fabAi   = document.getElementById('fabAi');
    const aiPopup = document.getElementById('aiPopup');
    const closeAi = document.getElementById('closeAi');
    const headerAiBtn = document.getElementById('headerAiBtn');

    function showAi() { if (aiPopup) { aiPopup.classList.add('show'); if (fabAi) fabAi.classList.add('hidden'); } }
    function hideAi() { if (aiPopup) { aiPopup.classList.remove('show'); if (fabAi) fabAi.classList.remove('hidden'); } }

    if (fabAi)       fabAi.addEventListener('click', showAi);
    if (closeAi)     closeAi.addEventListener('click', hideAi);
    if (headerAiBtn) headerAiBtn.addEventListener('click', function() { aiPopup && aiPopup.classList.contains('show') ? hideAi() : showAi(); });

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

    // ═══ AI CHAT — Knowledge Base ═══
    const aiMessages    = document.getElementById('aiMessages');
    const aiInput       = document.getElementById('aiInput');
    const aiSend        = document.getElementById('aiSend');
    const aiVoice       = document.getElementById('aiVoice');
    const aiQuickActions = document.getElementById('aiQuickActions');

    function addAiMessage(text, isUser) {
        const div = document.createElement('div');
        div.className = 'ai-msg ' + (isUser ? 'user' : 'bot');
        div.innerHTML = isUser
            ? '<div class="ai-msg-bubble">' + escapeHtml(text) + '</div>'
            : '<div class="ai-msg-avatar"><div class="ai-3d-icon"><i class="bi bi-robot"></i></div></div><div class="ai-msg-bubble">' + text + '</div>';
        aiMessages.appendChild(div);
        aiMessages.scrollTop = aiMessages.scrollHeight;
    }

    function escapeHtml(str) {
        const d = document.createElement('div');
        d.textContent = str;
        return d.innerHTML;
    }

    function simulateAiResponse(userText) {
        const lower = userText.toLowerCase();
        let response = '';

        if (lower.includes('surat') && (lower.includes('alur') || lower.includes('proses'))) {
            response = 'Alur surat di SIMPEG-SMART:<br><br><strong>Status:</strong> Draft → Diproses → Dikirim → Diterima → Diarsipkan<br><br>Sebagai Kepala Sekolah, Anda dapat melihat semua surat di menu <strong>Surat Menyurat</strong>.';
        } else if (lower.includes('kehadiran') || lower.includes('absen')) {
            response = 'Fitur <strong>Kehadiran</strong> untuk Kepala Sekolah:<br><br>📊 <strong>Lihat Absensi:</strong> Sidebar → Kehadiran → Absensi Hari Ini<br>📋 <strong>Rekap:</strong> Sidebar → Kehadiran → Rekap Kehadiran<br><br>Anda dapat memonitor kehadiran seluruh staff.';
        } else if (lower.includes('izin') || lower.includes('cuti')) {
            response = 'Fitur <strong>Pengajuan Izin</strong>:<br><br>✅ <strong>Approve/Reject:</strong> Sidebar → Pengajuan Izin → Menunggu Persetujuan<br>📋 <strong>Semua Pengajuan:</strong> Sidebar → Pengajuan Izin → Semua<br><br>Staff akan mendapat notifikasi saat izin disetujui/ditolak.';
        } else if (lower.includes('skp') || lower.includes('kinerja') || lower.includes('evaluasi')) {
            response = 'Modul <strong>Kinerja Pegawai</strong>:<br><br>📋 <strong>SKP:</strong> Sidebar → SKP → Menunggu Penilaian<br>• Status: Menunggu → Disetujui / Revisi / Ditolak<br>• Anda dapat memberikan stamp persetujuan<br><br>⭐ <strong>Evaluasi:</strong> PKG/BKD, Metode STAR, Bukti Fisik<br><br>Buka menu <strong>Kinerja Pegawai</strong> untuk detail.';
        } else if (lower.includes('resolusi') || lower.includes('keputusan')) {
            response = 'Fitur <strong>Resolusi</strong> khusus Kepala Sekolah:<br><br>📜 <strong>Buat Resolusi:</strong> Sidebar → Resolusi → Tambah Baru<br>• Keputusan penting & kebijakan sekolah<br>• Lengkap dengan timestamp dan status';
        } else if (lower.includes('rekap') || lower.includes('eksekutif') || lower.includes('laporan')) {
            response = 'Fitur <strong>Rekap Eksekutif</strong>:<br><br>📊 <strong>Dashboard:</strong> Ringkasan data sekolah<br>🤖 <strong>AI Analisis:</strong> Analisis otomatis data kinerja<br><br>Buka: Sidebar → Rekap Eksekutif';
        } else if (lower.includes('fitur') || lower.includes('sistem') || lower.includes('apa saja')) {
            response = 'Fitur <strong>SIMPEG-SMART</strong> untuk Kepala Sekolah:<br><br>' +
                '👥 <strong>Monitoring Staff:</strong> Data Pegawai, Kehadiran, Izin<br>' +
                '📋 <strong>Kinerja:</strong> SKP (approve/reject), Evaluasi PKG/STAR<br>' +
                '📧 <strong>Administrasi:</strong> Surat, Laporan, Keuangan<br>' +
                '📊 <strong>Khusus:</strong> Resolusi, Rekap Eksekutif, AI Assistant<br>' +
                '📅 <strong>Lainnya:</strong> Agenda, Chat, Notifikasi, Panduan';
        } else if (lower.includes('panduan') || lower.includes('bantuan') || lower.includes('cara')) {
            response = 'Saya bisa membantu tentang:<br><br>' +
                '• <strong>Kehadiran</strong> — monitoring absensi staff<br>' +
                '• <strong>Izin/Cuti</strong> — approve/reject pengajuan<br>' +
                '• <strong>SKP</strong> — penilaian & persetujuan kinerja<br>' +
                '• <strong>Evaluasi</strong> — PKG, STAR, Bukti Fisik<br>' +
                '• <strong>Surat</strong> — monitoring surat masuk/keluar<br>' +
                '• <strong>Resolusi</strong> — keputusan kepala sekolah<br><br>' +
                'Ketik topik spesifik untuk jawaban detail!';
        } else {
            response = 'Terima kasih atas pertanyaan Anda: <em>"' + escapeHtml(userText) + '"</em><br><br>' +
                'Saya bisa menjelaskan tentang:<br>' +
                '• <strong>Kehadiran</strong>, <strong>SKP</strong>, <strong>Izin</strong><br>' +
                '• <strong>Evaluasi</strong>, <strong>Surat</strong>, <strong>Laporan</strong><br>' +
                '• <strong>Resolusi</strong>, <strong>Rekap Eksekutif</strong><br><br>' +
                'Ketik kata kunci untuk jawaban detail!';
        }

        setTimeout(() => addAiMessage(response, false), 800 + Math.random() * 500);
    }

    function handleAiSend() {
        if (!aiInput) return;
        const text = aiInput.value.trim();
        if (!text) return;
        addAiMessage(text, true);
        aiInput.value = '';
        aiInput.style.height = 'auto';

        const typing = document.createElement('div');
        typing.className = 'ai-msg bot';
        typing.id = 'aiTyping';
        typing.innerHTML = '<div class="ai-msg-avatar"><div class="ai-3d-icon"><i class="bi bi-robot"></i></div></div><div class="ai-msg-bubble"><em style="opacity:.6;">Mengetik...</em></div>';
        aiMessages.appendChild(typing);
        aiMessages.scrollTop = aiMessages.scrollHeight;

        setTimeout(() => {
            const t = document.getElementById('aiTyping');
            if (t) t.remove();
            simulateAiResponse(text);
        }, 600);
    }

    if (aiSend) aiSend.addEventListener('click', handleAiSend);
    if (aiInput) {
        aiInput.addEventListener('keydown', e => {
            if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); handleAiSend(); }
        });
        aiInput.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 100) + 'px';
        });
    }

    if (aiQuickActions) {
        aiQuickActions.querySelectorAll('.ai-quick-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const prompt = this.dataset.prompt;
                if (prompt && aiInput) { aiInput.value = prompt; handleAiSend(); }
            });
        });
    }

    // ── Voice Input ──
    if (aiVoice && ('webkitSpeechRecognition' in window || 'SpeechRecognition' in window)) {
        const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
        const recognition = new SpeechRecognition();
        recognition.lang = 'id-ID';
        recognition.continuous = false;
        recognition.interimResults = false;
        let isRecording = false;

        aiVoice.addEventListener('click', function() {
            if (isRecording) { recognition.stop(); }
            else { recognition.start(); this.classList.add('recording'); isRecording = true; }
        });
        recognition.onresult = function(event) {
            const transcript = event.results[0][0].transcript;
            if (aiInput) { aiInput.value = transcript; handleAiSend(); }
        };
        recognition.onend = function() { aiVoice.classList.remove('recording'); isRecording = false; };
        recognition.onerror = function() { aiVoice.classList.remove('recording'); isRecording = false; };
    } else if (aiVoice) {
        aiVoice.title = 'Speech recognition tidak didukung browser ini';
        aiVoice.style.opacity = '.4';
        aiVoice.style.cursor = 'not-allowed';
    }

    // ── Close on Escape ──
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') { hideAi(); closeDrawer(); }
    });

});
</script>
