{{-- ═══ AI Chat Widget Component ═══ --}}
{{-- Shared floating AI chat widget for all roles --}}
@php
    $user = Auth::user();
    $peran = $user->peran ?? 'admin';
    $aiConfig = \App\Models\PengaturanAi::getActive();
    $ikonKelas = $aiConfig->ikon ?? 'bi-robot';
    $warnaTema = $aiConfig->warna_tema ?? '#6366f1';

    // Map role to route prefix
    $routeMap = [
        'admin' => 'admin.siatu-ai.kirim',
        'kepala_sekolah' => 'kepala-sekolah.siatu-ai.kirim',
        'magang' => 'magang.siatu-ai.kirim',
    ];

    // All staff roles use staf prefix
    $stafRoles = ['kepegawaian', 'pramu_bakti', 'keuangan', 'persuratan', 'perpustakaan', 'inventaris', 'kesiswaan_kurikulum', 'staff'];
    if (in_array($peran, $stafRoles)) {
        $aiRoute = 'staf.siatu-ai.kirim';
    } else {
        $aiRoute = $routeMap[$peran] ?? 'staf.siatu-ai.kirim';
    }
@endphp

<style>
    /* ── AI Chat Popup ── */
    .ai-popup {
        position: fixed; bottom: 20px; right: 20px; width: 400px; height: 560px;
        border-radius: 20px; z-index: 1060; display: none; flex-direction: column;
        box-shadow: 0 20px 60px rgba(0,0,0,.25); overflow: hidden;
        background: linear-gradient(180deg, #0f0a2e 0%, #1e1b4b 50%, #312e81 100%);
        animation: aiSlideUp .35s cubic-bezier(.4,0,.2,1);
    }
    .ai-popup.show { display: flex; }
    @keyframes aiSlideUp { from { opacity: 0; transform: translateY(24px) scale(.97); } to { opacity: 1; transform: translateY(0) scale(1); } }
    .ai-popup .fp-header {
        padding: 16px 20px; display: flex; align-items: center; justify-content: space-between; flex-shrink: 0;
        background: rgba(255,255,255,.05); border-bottom: 1px solid rgba(255,255,255,.1);
    }
    .ai-popup .fp-header h6 { color: #fff; margin: 0; font-size: .88rem; font-weight: 700; display: flex; align-items: center; gap: 10px; }
    .ai-popup .fp-close {
        width: 30px; height: 30px; border-radius: 8px; border: none; cursor: pointer;
        display: flex; align-items: center; justify-content: center; font-size: .9rem;
        background: rgba(255,255,255,.1); color: #a5b4fc; transition: .2s;
    }
    .ai-popup .fp-close:hover { background: rgba(255,255,255,.2); color: #fff; }
    .ai-popup .fp-body { flex: 1; overflow-y: hidden; padding: 0; display: flex; flex-direction: column; }
    .ai-messages { flex: 1; overflow-y: auto; padding: 16px 20px; }
    .ai-messages::-webkit-scrollbar { width: 3px; }
    .ai-messages::-webkit-scrollbar-thumb { background: rgba(255,255,255,.15); border-radius: 3px; }
    .ai-msg { margin-bottom: 14px; display: flex; gap: 10px; animation: fadeIn .3s ease; }
    .ai-msg.bot .ai-msg-avatar {
        width: 32px; height: 32px; border-radius: 10px; display: flex; align-items: center; justify-content: center;
        flex-shrink: 0; overflow: hidden;
    }
    .ai-msg.user { flex-direction: row-reverse; }
    .ai-msg-bubble { max-width: 82%; padding: 10px 14px; border-radius: 14px; font-size: .8rem; line-height: 1.6; }
    .ai-msg.bot .ai-msg-bubble { background: rgba(255,255,255,.08); color: #e0e7ff; border-bottom-left-radius: 4px; }
    .ai-msg.bot .ai-msg-bubble ul { margin: 6px 0 0; padding-left: 16px; font-size: .76rem; }
    .ai-msg.bot .ai-msg-bubble strong { color: #c7d2fe; }
    .ai-msg.user .ai-msg-bubble { background: linear-gradient(135deg, {{ $warnaTema }}, {{ $warnaTema }}cc); color: #fff; border-bottom-right-radius: 4px; }
    .ai-input-area {
        padding: 14px 16px; border-top: 1px solid rgba(255,255,255,.1);
        display: flex; gap: 8px; align-items: flex-end; flex-shrink: 0;
    }
    .ai-input-area textarea {
        flex: 1; background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.12);
        border-radius: 12px; padding: 10px 14px; color: #e0e7ff; font-size: .8rem;
        resize: none; outline: none; font-family: inherit; min-height: 42px; max-height: 100px;
    }
    .ai-input-area textarea::placeholder { color: rgba(255,255,255,.3); }
    .ai-input-area textarea:focus { border-color: rgba(99,102,241,.5); background: rgba(255,255,255,.12); }
    .ai-action-btn {
        width: 38px; height: 38px; border-radius: 10px; border: none; cursor: pointer;
        display: flex; align-items: center; justify-content: center; font-size: 1rem;
        transition: .2s; flex-shrink: 0;
    }
    .ai-send-btn { background: linear-gradient(135deg, {{ $warnaTema }}, {{ $warnaTema }}cc); color: #fff; }
    .ai-send-btn:hover { opacity: .85; }
    .ai-voice-btn { background: rgba(255,255,255,.08); color: #a5b4fc; }
    .ai-voice-btn:hover { background: rgba(255,255,255,.15); color: #fff; }
    .ai-voice-btn.recording { background: #ef4444; color: #fff; animation: pulse 1.2s infinite; }
    @keyframes pulse { 0%,100% { opacity: 1; } 50% { opacity: .6; } }
    .ai-quick-actions { padding: 8px 16px; display: flex; gap: 6px; flex-wrap: wrap; border-top: 1px solid rgba(255,255,255,.06); flex-shrink: 0; }
    .ai-quick-btn {
        padding: 5px 12px; border-radius: 20px; border: 1px solid rgba(255,255,255,.12);
        background: rgba(255,255,255,.05); color: #c7d2fe; font-size: .66rem;
        cursor: pointer; transition: .2s; white-space: nowrap;
    }
    .ai-quick-btn:hover { background: rgba(99,102,241,.25); border-color: rgba(99,102,241,.35); color: #fff; }

    /* ── 3D AI Avatar ── */
    .ai-3d-icon {
        width: 32px; height: 32px; border-radius: 10px; position: relative;
        background: linear-gradient(135deg, {{ $warnaTema }}, {{ $warnaTema }}cc);
        display: flex; align-items: center; justify-content: center;
        animation: ai3dFloat 3s ease-in-out infinite;
        box-shadow: 0 4px 12px {{ $warnaTema }}66;
    }
    .ai-3d-icon::before {
        content: ''; position: absolute; inset: -2px; border-radius: 12px;
        background: conic-gradient(from 0deg, {{ $warnaTema }}, {{ $warnaTema }}cc, #a78bfa, #c084fc, {{ $warnaTema }}cc, {{ $warnaTema }});
        z-index: -1; animation: ai3dSpin 4s linear infinite; opacity: .6;
    }
    .ai-3d-icon i { color: #fff; font-size: .85rem; filter: drop-shadow(0 0 4px rgba(255,255,255,.5)); }
    @keyframes ai3dFloat {
        0%,100% { transform: translateY(0) rotateY(0deg); }
        25% { transform: translateY(-2px) rotateY(5deg); }
        75% { transform: translateY(1px) rotateY(-3deg); }
    }
    @keyframes ai3dSpin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
    .ai-3d-header-icon {
        width: 28px; height: 28px; border-radius: 8px; position: relative;
        background: linear-gradient(135deg, {{ $warnaTema }}, #a78bfa);
        display: inline-flex; align-items: center; justify-content: center;
        animation: ai3dFloat 3s ease-in-out infinite;
    }
    .ai-3d-header-icon i { color: #fff; font-size: .75rem; }

    /* ── Floating AI FAB (3D) ── */
    .fab-ai {
        position: fixed; bottom: 20px; right: 20px; width: 54px; height: 54px;
        border-radius: 16px; border: none; cursor: pointer; z-index: 1055;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem; transition: all .3s;
        background: linear-gradient(135deg, {{ $warnaTema }}, #7c3aed);
        color: #fff; perspective: 200px;
        box-shadow: 0 4px 20px {{ $warnaTema }}80, 0 0 40px {{ $warnaTema }}26;
    }
    .fab-ai::before {
        content: ''; position: absolute; inset: -3px; border-radius: 19px;
        background: conic-gradient(from 0deg, {{ $warnaTema }}, {{ $warnaTema }}cc, #a78bfa, #c084fc, {{ $warnaTema }}cc, {{ $warnaTema }});
        z-index: -1; animation: ai3dSpin 3s linear infinite; opacity: .5;
    }
    .fab-ai::after {
        content: ''; position: absolute; inset: 0; border-radius: 16px;
        background: linear-gradient(135deg, {{ $warnaTema }}, #7c3aed); z-index: -1;
    }
    .fab-ai:hover { transform: scale(1.1) rotateY(10deg); box-shadow: 0 8px 30px {{ $warnaTema }}99; }
    .fab-ai.hidden { display: none; }

    .ai-typing { display: flex; gap: 4px; padding: 8px 12px; }
    .ai-typing span { width: 6px; height: 6px; border-radius: 50%; background: #a5b4fc; animation: typingBounce .6s infinite; }
    .ai-typing span:nth-child(2) { animation-delay: .15s; }
    .ai-typing span:nth-child(3) { animation-delay: .3s; }
    @keyframes typingBounce { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-6px); } }

    .ai-provider-badge {
        font-size: .6rem; padding: 2px 8px; border-radius: 10px;
        background: rgba(255,255,255,.1); color: #a5b4fc; margin-left: auto;
    }

    @media (max-width: 767px) {
        .ai-popup { width: calc(100vw - 24px); right: 12px !important; bottom: 12px; height: 70vh; }
        .fab-ai { right: 12px; bottom: 12px; width: 48px; height: 48px; border-radius: 14px; }
    }
</style>

{{-- AI Chat Popup --}}
<div class="ai-popup" id="aiPopup">
    <div class="fp-header">
        <h6>
            <span class="ai-3d-header-icon"><i class="bi {{ $ikonKelas }}"></i></span>
            SIMPEG-AI Assistant
            @if($aiConfig)
                <span class="ai-provider-badge">{{ $aiConfig->nama_tampilan ?? $aiConfig->provider }}</span>
            @endif
        </h6>
        <button class="fp-close" id="closeAi"><i class="bi bi-x-lg"></i></button>
    </div>
    <div class="fp-body">
        <div class="ai-messages" id="aiMessages">
            <div class="ai-msg bot">
                <div class="ai-msg-avatar">
                    <div class="ai-3d-icon"><i class="bi {{ $ikonKelas }}"></i></div>
                </div>
                <div class="ai-msg-bubble">
                    Halo <strong>{{ $user->nama }}</strong>! Saya <strong>SIMPEG-AI</strong> 🤖, asisten cerdas Anda.
                    @if($aiConfig)
                        <small style="display:block;margin-top:4px;opacity:.7;">Menggunakan {{ $aiConfig->nama_tampilan }} ({{ $aiConfig->model }})</small>
                    @endif
                    <ul>
                        <li>Panduan fitur & cara penggunaan</li>
                        <li>Membuat draft surat & dokumen</li>
                        <li>Analisis kehadiran & kinerja</li>
                        <li>Alur administrasi sekolah</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="ai-quick-actions" id="aiQuickActions">
            <button class="ai-quick-btn" data-prompt="Jelaskan fitur apa saja yang ada di SIMPEG-SMART"><i class="bi bi-grid me-1"></i>Fitur Sistem</button>
            <button class="ai-quick-btn" data-prompt="Bagaimana cara menggunakan fitur kehadiran?"><i class="bi bi-fingerprint me-1"></i>Kehadiran</button>
            <button class="ai-quick-btn" data-prompt="Panduan penggunaan lengkap"><i class="bi bi-book me-1"></i>Panduan</button>
            <button class="ai-quick-btn" data-prompt="Buatkan draft surat tugas"><i class="bi bi-envelope me-1"></i>Draft Surat</button>
            <button class="ai-quick-btn" data-prompt="Cara backup data ke Google Drive"><i class="bi bi-cloud me-1"></i>Backup</button>
            <button class="ai-quick-btn" data-prompt="Alur administrasi surat masuk dan keluar"><i class="bi bi-diagram-3 me-1"></i>Alur Surat</button>
        </div>
        <div class="ai-input-area">
            <button class="ai-action-btn ai-voice-btn" id="aiVoice" title="Bicara"><i class="bi bi-mic-fill"></i></button>
            <textarea id="aiInput" placeholder="Tanya apa saja tentang SIMPEG-SMART..." rows="1"></textarea>
            <button class="ai-action-btn ai-send-btn" id="aiSend" title="Kirim"><i class="bi bi-send-fill"></i></button>
        </div>
    </div>
</div>

{{-- Floating AI FAB --}}
<button class="fab-ai" id="fabAi" title="SIMPEG-AI Assistant">
    <i class="bi {{ $ikonKelas }}"></i>
</button>

<script>
(function() {
    const aiPopup = document.getElementById('aiPopup');
    const fabAi = document.getElementById('fabAi');
    const closeAi = document.getElementById('closeAi');
    const aiInput = document.getElementById('aiInput');
    const aiSend = document.getElementById('aiSend');
    const aiMessages = document.getElementById('aiMessages');
    const aiVoice = document.getElementById('aiVoice');
    const aiApiUrl = "{{ route($aiRoute) }}";
    const csrfToken = "{{ csrf_token() }}";
    const ikonKelas = "{{ $ikonKelas }}";

    // Toggle popup
    if (fabAi) fabAi.addEventListener('click', () => {
        aiPopup.classList.add('show');
        fabAi.classList.add('hidden');
        if (aiInput) aiInput.focus();
    });
    if (closeAi) closeAi.addEventListener('click', () => {
        aiPopup.classList.remove('show');
        fabAi.classList.remove('hidden');
    });

    // Header AI button (if exists)
    const headerAiBtn = document.getElementById('headerAiBtn');
    if (headerAiBtn) headerAiBtn.addEventListener('click', () => {
        if (aiPopup.classList.contains('show')) {
            aiPopup.classList.remove('show');
            fabAi.classList.remove('hidden');
        } else {
            aiPopup.classList.add('show');
            fabAi.classList.add('hidden');
            if (aiInput) aiInput.focus();
        }
    });

    function addMessage(text, isBot) {
        const div = document.createElement('div');
        div.className = 'ai-msg ' + (isBot ? 'bot' : 'user');
        if (isBot) {
            div.innerHTML = `<div class="ai-msg-avatar"><div class="ai-3d-icon"><i class="bi ${ikonKelas}"></i></div></div><div class="ai-msg-bubble">${text}</div>`;
        } else {
            div.innerHTML = `<div class="ai-msg-bubble">${escapeHtml(text)}</div>`;
        }
        aiMessages.appendChild(div);
        aiMessages.scrollTop = aiMessages.scrollHeight;
    }

    function showTyping() {
        const div = document.createElement('div');
        div.className = 'ai-msg bot';
        div.id = 'aiTyping';
        div.innerHTML = `<div class="ai-msg-avatar"><div class="ai-3d-icon"><i class="bi ${ikonKelas}"></i></div></div><div class="ai-msg-bubble"><div class="ai-typing"><span></span><span></span><span></span></div></div>`;
        aiMessages.appendChild(div);
        aiMessages.scrollTop = aiMessages.scrollHeight;
    }

    function removeTyping() {
        const el = document.getElementById('aiTyping');
        if (el) el.remove();
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    async function handleAiSend() {
        const text = aiInput.value.trim();
        if (!text) return;

        addMessage(text, false);
        aiInput.value = '';
        aiInput.style.height = 'auto';

        // Hide quick actions after first message
        const qa = document.getElementById('aiQuickActions');
        if (qa) qa.style.display = 'none';

        showTyping();

        try {
            const resp = await fetch(aiApiUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ pesan: text })
            });

            removeTyping();

            if (resp.ok) {
                const data = await resp.json();
                if (data.success && data.jawaban) {
                    addMessage(data.jawaban, true);
                } else {
                    addMessage('<span style="color:#fca5a5;">⚠️ ' + (data.pesan || 'Gagal mendapatkan respons dari AI.') + '</span>', true);
                }
            } else if (resp.status === 419) {
                addMessage('<span style="color:#fca5a5;">⚠️ Sesi telah berakhir. Silakan muat ulang halaman.</span>', true);
            } else if (resp.status === 422) {
                addMessage('<span style="color:#fca5a5;">⚠️ Pesan tidak valid. Pastikan pesan tidak kosong dan tidak lebih dari 2000 karakter.</span>', true);
            } else {
                addMessage('<span style="color:#fca5a5;">⚠️ Terjadi kesalahan server (Kode: ' + resp.status + '). Silakan coba lagi.</span>', true);
            }
        } catch (err) {
            removeTyping();
            addMessage('<span style="color:#fca5a5;">⚠️ Gagal terhubung ke server. Periksa koneksi internet Anda.</span>', true);
        }
    }

    if (aiSend) aiSend.addEventListener('click', handleAiSend);
    if (aiInput) {
        aiInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); handleAiSend(); }
        });
        // Auto resize textarea
        aiInput.addEventListener('input', () => {
            aiInput.style.height = 'auto';
            aiInput.style.height = Math.min(aiInput.scrollHeight, 100) + 'px';
        });
    }

    // Quick action buttons
    document.querySelectorAll('.ai-quick-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const prompt = btn.dataset.prompt;
            if (prompt && aiInput) { aiInput.value = prompt; handleAiSend(); }
        });
    });

    // Voice input
    if (aiVoice && ('webkitSpeechRecognition' in window || 'SpeechRecognition' in window)) {
        const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
        const recognition = new SpeechRecognition();
        recognition.lang = 'id-ID';
        recognition.continuous = false;
        recognition.interimResults = false;

        aiVoice.addEventListener('click', () => {
            if (aiVoice.classList.contains('recording')) {
                recognition.stop();
                aiVoice.classList.remove('recording');
            } else {
                recognition.start();
                aiVoice.classList.add('recording');
            }
        });

        recognition.onresult = (e) => {
            const transcript = e.results[0][0].transcript;
            if (aiInput) { aiInput.value = transcript; handleAiSend(); }
            aiVoice.classList.remove('recording');
        };
        recognition.onerror = () => aiVoice.classList.remove('recording');
        recognition.onend = () => aiVoice.classList.remove('recording');
    } else if (aiVoice) {
        aiVoice.style.display = 'none';
    }
})();
</script>
