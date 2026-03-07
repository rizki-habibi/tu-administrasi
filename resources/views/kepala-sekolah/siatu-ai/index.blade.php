@extends('peran.kepala-sekolah.app')
@section('judul', 'SIATU-AI - Asisten AI')

@section('konten')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card border-0 shadow-sm" style="height: calc(100vh - 160px); display:flex; flex-direction:column;">
            <div class="card-header bg-white border-0 py-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="d-flex align-items-center justify-content-center rounded-circle" style="width:48px;height:48px;background:linear-gradient(135deg,#d97706,#f59e0b);">
                        <i class="bi bi-robot text-white" style="font-size:1.5rem;"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0">SIATU-AI <span class="badge bg-success" style="font-size:.6rem;">Online</span></h5>
                        <p class="text-muted mb-0" style="font-size:.8rem;">Asisten AI Kepala Sekolah SMA Negeri 2 Jember</p>
                    </div>
                </div>
            </div>

            <div class="card-body p-4" id="chat-messages" style="flex:1; overflow-y:auto; background:#f8f9fa;">
                <div class="d-flex gap-3 mb-4">
                    <div class="d-flex align-items-start justify-content-center rounded-circle flex-shrink-0" style="width:36px;height:36px;background:linear-gradient(135deg,#d97706,#f59e0b);">
                        <i class="bi bi-robot text-white" style="font-size:1rem;margin-top:7px;"></i>
                    </div>
                    <div class="bg-white rounded-3 p-3 shadow-sm" style="max-width:80%;font-size:.9rem;">
                        <p class="mb-2">Selamat datang, <strong>{{ Auth::user()->nama }}</strong>! 👋</p>
                        <p class="mb-2">Saya <strong>SIATU-AI</strong>, asisten AI strategis untuk Kepala Sekolah SMA Negeri 2 Jember. Saya siap membantu:</p>
                        <ul class="mb-2">
                            <li>📊 Analisis kinerja & rekap eksekutif</li>
                            <li>🎯 Strategi peningkatan mutu sekolah</li>
                            <li>📝 Membuat resolusi & keputusan</li>
                            <li>👥 Evaluasi & supervisi pegawai</li>
                            <li>💡 Rekomendasi kebijakan pendidikan</li>
                            <li>🏫 Perencanaan strategis jangka panjang</li>
                        </ul>
                        <p class="mb-0 text-muted" style="font-size:.8rem;">Silakan ketik pertanyaan Anda...</p>
                    </div>
                </div>
            </div>

            <div class="card-footer bg-white border-0 p-3">
                <form id="chat-form" class="d-flex gap-2">
                    @csrf
                    <div class="position-relative flex-grow-1">
                        <textarea id="pesan-input" class="form-control" rows="1" placeholder="Ketik pesan Anda..." style="resize:none;padding-right:48px;" maxlength="2000"></textarea>
                        <small class="position-absolute text-muted" style="bottom:4px;right:56px;font-size:.7rem;" id="char-count">0/2000</small>
                    </div>
                    <button type="submit" class="btn btn-warning text-white align-self-end" id="btn-kirim" style="height:40px;width:48px;">
                        <i class="bi bi-send-fill"></i>
                    </button>
                </form>
                <div class="text-center mt-2">
                    <small class="text-muted" style="font-size:.7rem;"><i class="bi bi-shield-check me-1"></i>SIATU-AI menggunakan teknologi Gemini AI. Harap verifikasi informasi penting.</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const chatMessages = document.getElementById('chat-messages');
const chatForm = document.getElementById('chat-form');
const pesanInput = document.getElementById('pesan-input');
const btnKirim = document.getElementById('btn-kirim');
const charCount = document.getElementById('char-count');
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

pesanInput.addEventListener('input', function() {
    this.style.height = 'auto';
    this.style.height = Math.min(this.scrollHeight, 120) + 'px';
    charCount.textContent = this.value.length + '/2000';
});
pesanInput.addEventListener('keydown', function(e) {
    if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); chatForm.dispatchEvent(new Event('submit')); }
});

chatForm.addEventListener('submit', function(e) {
    e.preventDefault();
    const pesan = pesanInput.value.trim();
    if (!pesan) return;
    appendMessage('user', pesan);
    pesanInput.value = ''; pesanInput.style.height = 'auto'; charCount.textContent = '0/2000';
    const typingId = showTyping();
    btnKirim.disabled = true; btnKirim.innerHTML = '<div class="spinner-border spinner-border-sm"></div>';

    fetch('{{ route("kepala-sekolah.siatu-ai.kirim") }}', {
        method: 'POST', headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
        body: JSON.stringify({ pesan })
    }).then(r => r.json()).then(data => {
        removeTyping(typingId);
        appendMessage('ai', data.success ? data.jawaban : '<p class="text-danger">⚠️ ' + (data.pesan || 'Gagal.') + '</p>');
    }).catch(() => { removeTyping(typingId); appendMessage('ai', '<p class="text-danger">⚠️ Koneksi gagal.</p>'); })
    .finally(() => { btnKirim.disabled = false; btnKirim.innerHTML = '<i class="bi bi-send-fill"></i>'; pesanInput.focus(); });
});

function appendMessage(type, content) {
    const div = document.createElement('div');
    div.className = 'd-flex gap-3 mb-4 ' + (type === 'user' ? 'justify-content-end' : '');
    const grad = 'linear-gradient(135deg,#d97706,#f59e0b)';
    if (type === 'ai') {
        div.innerHTML = `<div class="d-flex align-items-start justify-content-center rounded-circle flex-shrink-0" style="width:36px;height:36px;background:${grad};"><i class="bi bi-robot text-white" style="font-size:1rem;margin-top:7px;"></i></div><div class="bg-white rounded-3 p-3 shadow-sm" style="max-width:80%;font-size:.9rem;">${content}</div>`;
    } else {
        div.innerHTML = `<div class="rounded-3 p-3 text-white" style="max-width:80%;font-size:.9rem;background:${grad};">${content.replace(/\n/g,'<br>')}</div><div class="d-flex align-items-start justify-content-center rounded-circle flex-shrink-0 bg-secondary text-white" style="width:36px;height:36px;"><span style="margin-top:7px;font-size:.75rem;">{{ strtoupper(substr(Auth::user()->nama, 0, 2)) }}</span></div>`;
    }
    chatMessages.appendChild(div); chatMessages.scrollTop = chatMessages.scrollHeight;
}
function showTyping() {
    const id = 'typing-' + Date.now();
    const div = document.createElement('div'); div.id = id; div.className = 'd-flex gap-3 mb-4';
    div.innerHTML = `<div class="d-flex align-items-start justify-content-center rounded-circle flex-shrink-0" style="width:36px;height:36px;background:linear-gradient(135deg,#d97706,#f59e0b);"><i class="bi bi-robot text-white" style="font-size:1rem;margin-top:7px;"></i></div><div class="bg-white rounded-3 p-3 shadow-sm"><div class="d-flex gap-1"><div class="typing-dot"></div><div class="typing-dot"></div><div class="typing-dot"></div></div></div>`;
    chatMessages.appendChild(div); chatMessages.scrollTop = chatMessages.scrollHeight; return id;
}
function removeTyping(id) { document.getElementById(id)?.remove(); }
</script>
<style>
.typing-dot { width:8px;height:8px;background:#d97706;border-radius:50%;animation:typing 1.4s infinite ease-in-out; }
.typing-dot:nth-child(2) { animation-delay:0.2s; }
.typing-dot:nth-child(3) { animation-delay:0.4s; }
@keyframes typing { 0%,80%,100%{transform:scale(0);} 40%{transform:scale(1);} }
</style>
@endpush
