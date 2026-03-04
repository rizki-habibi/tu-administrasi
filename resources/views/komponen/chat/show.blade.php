{{-- Chat Show: Percakapan + Pesan --}}
@php
    $routePrefix = auth()->user()->getRoutePrefix();
    $currentUserId = auth()->id();
    $chatNama = $percakapan->getNamaUntuk($currentUserId);
    $isGrup = $percakapan->tipe === 'grup';
    $anggotaCount = $percakapan->anggota->count();
@endphp

<div class="chat-container" style="display:flex;height:calc(100vh - var(--header-h) - 40px);background:#fff;border-radius:14px;overflow:hidden;box-shadow:0 2px 12px rgba(0,0,0,.08);">
    {{-- Sidebar Chat --}}
    <div class="chat-sidebar d-none d-lg-flex" style="width:300px;border-right:1px solid #e5e7eb;flex-direction:column;background:#fafbfc;">
        <div style="padding:14px 16px;border-bottom:1px solid #e5e7eb;">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="fw-bold mb-0" style="font-size:.85rem;"><i class="bi bi-chat-dots-fill text-primary me-1"></i>Pesan</h6>
                <div class="d-flex gap-1">
                    <button class="btn btn-sm btn-outline-primary" onclick="pesanBaru()" style="padding:2px 6px;"><i class="bi bi-pencil-square" style="font-size:.7rem;"></i></button>
                    <button class="btn btn-sm btn-outline-success" onclick="buatGrup()" style="padding:2px 6px;"><i class="bi bi-people-fill" style="font-size:.7rem;"></i></button>
                </div>
            </div>
            <input type="text" id="searchChat" class="form-control form-control-sm" placeholder="Cari..." style="border-radius:20px;background:#f1f5f9;font-size:.75rem;" onkeyup="filterChat()">
        </div>
        <div style="flex:1;overflow-y:auto;">
            @foreach($semuaPercakapan as $p)
                @php
                    $nm = $p->getNamaUntuk($currentUserId);
                    $lm = $p->pesanTerakhir;
                    $ur = $p->pesanBelumDibaca($currentUserId);
                    $ig = $p->tipe === 'grup';
                @endphp
                <a href="{{ route($routePrefix . '.chat.show', $p) }}" class="chat-item d-block text-decoration-none {{ $p->id === $percakapan->id ? 'active' : '' }}" data-nama="{{ strtolower($nm) }}" style="padding:10px 16px;border-bottom:1px solid #f1f5f9;{{ $p->id === $percakapan->id ? 'background:#eef2ff;border-left:3px solid #6366f1;' : '' }}">
                    <div class="d-flex align-items-center" style="gap:8px;">
                        <div style="width:36px;height:36px;border-radius:50%;background:{{ $ig ? 'linear-gradient(135deg,#10b981,#34d399)' : 'linear-gradient(135deg,#6366f1,#818cf8)' }};color:#fff;display:flex;align-items:center;justify-content:center;font-size:.65rem;font-weight:600;flex-shrink:0;">
                            @if($ig) <i class="bi bi-people-fill"></i> @else {{ strtoupper(substr($nm, 0, 2)) }} @endif
                        </div>
                        <div style="flex:1;min-width:0;">
                            <div class="d-flex justify-content-between">
                                <strong style="font-size:.75rem;color:#1e293b;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:130px;">{{ $nm }}</strong>
                                @if($lm) <small style="color:#94a3b8;font-size:.6rem;">{{ $lm->created_at->format('H:i') }}</small> @endif
                            </div>
                            <div class="d-flex justify-content-between">
                                <small style="color:#64748b;font-size:.68rem;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:150px;">{{ $lm ? \Str::limit($lm->isi, 30) : 'Belum ada pesan' }}</small>
                                @if($ur > 0) <span class="badge rounded-pill bg-primary" style="font-size:.55rem;padding:2px 5px;">{{ $ur }}</span> @endif
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

    {{-- Main Chat Area --}}
    <div style="flex:1;display:flex;flex-direction:column;">
        {{-- Chat Header --}}
        <div style="padding:12px 20px;border-bottom:1px solid #e5e7eb;background:#fff;display:flex;align-items:center;gap:12px;">
            <a href="{{ route($routePrefix . '.chat.index') }}" class="d-lg-none btn btn-sm btn-light"><i class="bi bi-arrow-left"></i></a>
            <div style="width:40px;height:40px;border-radius:50%;background:{{ $isGrup ? 'linear-gradient(135deg,#10b981,#34d399)' : 'linear-gradient(135deg,#6366f1,#818cf8)' }};color:#fff;display:flex;align-items:center;justify-content:center;font-weight:600;font-size:.75rem;">
                @if($isGrup) <i class="bi bi-people-fill"></i> @else {{ strtoupper(substr($chatNama, 0, 2)) }} @endif
            </div>
            <div>
                <strong style="font-size:.9rem;color:#1e293b;">{{ $chatNama }}</strong>
                <br><small style="color:#64748b;font-size:.72rem;">
                    @if($isGrup) {{ $anggotaCount }} anggota @else Online @endif
                </small>
            </div>
            @if($isGrup)
            <div class="ms-auto">
                <button class="btn btn-sm btn-light" onclick="lihatAnggota()" title="Lihat Anggota"><i class="bi bi-info-circle"></i></button>
            </div>
            @endif
        </div>

        {{-- Messages Area --}}
        <div id="messageArea" style="flex:1;overflow-y:auto;padding:16px 20px;background:#f8fafc;display:flex;flex-direction:column;gap:4px;">
            @php $lastDate = ''; @endphp
            @foreach($pesan as $msg)
                @php
                    $msgDate = $msg->created_at->format('d M Y');
                    $isMine = $msg->pengirim_id === $currentUserId;
                    $isSistem = $msg->tipe === 'sistem';
                @endphp

                @if($msgDate !== $lastDate)
                    <div class="text-center my-2">
                        <span style="background:#e5e7eb;color:#64748b;font-size:.68rem;padding:3px 12px;border-radius:10px;">{{ $msgDate }}</span>
                    </div>
                    @php $lastDate = $msgDate; @endphp
                @endif

                @if($isSistem)
                    <div class="text-center my-1">
                        <small style="color:#94a3b8;font-size:.72rem;font-style:italic;">{{ $msg->isi }}</small>
                    </div>
                @else
                    <div class="d-flex {{ $isMine ? 'justify-content-end' : 'justify-content-start' }}" style="margin-bottom:2px;">
                        <div style="max-width:75%;min-width:80px;">
                            @if(!$isMine && $isGrup)
                                <small style="color:#6366f1;font-size:.68rem;font-weight:600;margin-left:8px;">{{ $msg->pengirim->nama }}</small>
                            @endif
                            @if($msg->balasan)
                                <div style="background:{{ $isMine ? 'rgba(99,102,241,0.15)' : 'rgba(0,0,0,0.06)' }};border-radius:8px 8px 0 0;padding:6px 12px;border-left:3px solid #6366f1;">
                                    <small style="color:#6366f1;font-weight:600;font-size:.68rem;">{{ $msg->balasan->pengirim->nama }}</small>
                                    <br><small style="color:#64748b;font-size:.68rem;">{{ \Str::limit($msg->balasan->isi, 50) }}</small>
                                </div>
                            @endif
                            <div style="background:{{ $isMine ? 'linear-gradient(135deg,#6366f1,#818cf8)' : '#fff' }};color:{{ $isMine ? '#fff' : '#1e293b' }};padding:8px 14px;border-radius:{{ $msg->balasan ? '0 0 16px 16px' : ($isMine ? '16px 16px 4px 16px' : '16px 16px 16px 4px') }};box-shadow:0 1px 3px rgba(0,0,0,.06);font-size:.82rem;line-height:1.5;position:relative;cursor:pointer;" onclick="setBalasan({{ $msg->id }}, '{{ addslashes($msg->pengirim->nama) }}', '{{ addslashes(\Str::limit($msg->isi, 40)) }}')" data-msg-id="{{ $msg->id }}">
                                {{ $msg->isi }}
                                <div style="text-align:right;margin-top:2px;">
                                    <small style="font-size:.6rem;opacity:.7;">{{ $msg->created_at->format('H:i') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        {{-- Reply Preview --}}
        <div id="replyPreview" style="display:none;padding:8px 20px;background:#eef2ff;border-top:1px solid #e5e7eb;font-size:.78rem;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <i class="bi bi-reply-fill text-primary me-1"></i>
                    <strong id="replyNama" class="text-primary"></strong>
                    <span id="replyIsi" class="text-muted ms-1"></span>
                </div>
                <button type="button" class="btn-close" style="font-size:.6rem;" onclick="clearBalasan()"></button>
            </div>
        </div>

        {{-- Input Area --}}
        <div style="padding:12px 20px;border-top:1px solid #e5e7eb;background:#fff;">
            <form id="chatForm" class="d-flex gap-2 align-items-end">
                <input type="hidden" id="balasanId" value="">
                <div style="flex:1;position:relative;">
                    <textarea id="pesanInput" rows="1" class="form-control" placeholder="Tulis pesan..." style="border-radius:20px;padding:10px 18px;font-size:.82rem;resize:none;max-height:120px;overflow-y:auto;border:2px solid #e5e7eb;transition:border-color .2s;" onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='#e5e7eb'"></textarea>
                </div>
                <button type="submit" class="btn btn-primary" style="border-radius:50%;width:42px;height:42px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="bi bi-send-fill"></i>
                </button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
const csrfToken = '{{ csrf_token() }}';
const kirimUrl = '{{ route($routePrefix . ".chat.kirim", $percakapan) }}';
const pollUrl = '{{ route($routePrefix . ".chat.pesan-baru", $percakapan) }}';
const buatUrl = '{{ route($routePrefix . ".chat.buat") }}';
const currentUserId = {{ $currentUserId }};
const isGrup = {{ $isGrup ? 'true' : 'false' }};
const semuaUser = @json($semuaUser->map(fn($u) => ['id' => $u->id, 'nama' => $u->nama, 'peran' => $u->role_label]));
let lastMsgId = {{ $pesan->last()?->id ?? 0 }};

// Auto scroll ke bawah
const msgArea = document.getElementById('messageArea');
msgArea.scrollTop = msgArea.scrollHeight;

// Auto-resize textarea
const textarea = document.getElementById('pesanInput');
textarea.addEventListener('input', function() {
    this.style.height = 'auto';
    this.style.height = Math.min(this.scrollHeight, 120) + 'px';
});

// Enter = kirim, Shift+Enter = newline
textarea.addEventListener('keydown', function(e) {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        document.getElementById('chatForm').dispatchEvent(new Event('submit'));
    }
});

// Kirim pesan
document.getElementById('chatForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const isi = textarea.value.trim();
    if (!isi) return;

    const balasanId = document.getElementById('balasanId').value || null;
    textarea.value = '';
    textarea.style.height = 'auto';
    clearBalasan();

    try {
        const res = await fetch(kirimUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
            body: JSON.stringify({ isi, balasan_id: balasanId })
        });
        const data = await res.json();
        if (data.success) {
            appendMessage(data.data);
            lastMsgId = data.data.id;
        }
    } catch (err) {}
});

function appendMessage(msg) {
    const isMine = msg.pengirim_id === currentUserId;
    // Date separator
    const lastDateEl = msgArea.querySelector('.date-sep:last-of-type');
    const lastDateText = lastDateEl ? lastDateEl.textContent.trim() : '';
    if (msg.tanggal !== lastDateText) {
        const dateSep = document.createElement('div');
        dateSep.className = 'text-center my-2 date-sep';
        dateSep.textContent = msg.tanggal;
        dateSep.innerHTML = `<span style="background:#e5e7eb;color:#64748b;font-size:.68rem;padding:3px 12px;border-radius:10px;">${msg.tanggal}</span>`;
        msgArea.appendChild(dateSep);
    }

    let replyHtml = '';
    if (msg.balasan) {
        replyHtml = `<div style="background:${isMine ? 'rgba(99,102,241,0.15)' : 'rgba(0,0,0,0.06)'};border-radius:8px 8px 0 0;padding:6px 12px;border-left:3px solid #6366f1;">
            <small style="color:#6366f1;font-weight:600;font-size:.68rem;">${msg.balasan.pengirim}</small>
            <br><small style="color:#64748b;font-size:.68rem;">${msg.balasan.isi}</small>
        </div>`;
    }

    let senderHtml = '';
    if (!isMine && isGrup) {
        senderHtml = `<small style="color:#6366f1;font-size:.68rem;font-weight:600;margin-left:8px;">${msg.pengirim_nama}</small>`;
    }

    const div = document.createElement('div');
    div.className = `d-flex ${isMine ? 'justify-content-end' : 'justify-content-start'}`;
    div.style.marginBottom = '2px';
    div.innerHTML = `
        <div style="max-width:75%;min-width:80px;">
            ${senderHtml}
            ${replyHtml}
            <div style="background:${isMine ? 'linear-gradient(135deg,#6366f1,#818cf8)' : '#fff'};color:${isMine ? '#fff' : '#1e293b'};padding:8px 14px;border-radius:${msg.balasan ? '0 0 16px 16px' : (isMine ? '16px 16px 4px 16px' : '16px 16px 16px 4px')};box-shadow:0 1px 3px rgba(0,0,0,.06);font-size:.82rem;line-height:1.5;cursor:pointer;" onclick="setBalasan(${msg.id}, '${msg.pengirim_nama.replace(/'/g, "\\'")}', '${msg.isi.substring(0,40).replace(/'/g, "\\'")}')" data-msg-id="${msg.id}">
                ${msg.isi.replace(/\n/g, '<br>')}
                <div style="text-align:right;margin-top:2px;">
                    <small style="font-size:.6rem;opacity:.7;">${msg.waktu}</small>
                </div>
            </div>
        </div>
    `;
    msgArea.appendChild(div);
    msgArea.scrollTop = msgArea.scrollHeight;
}

// Reply functions
function setBalasan(id, nama, isi) {
    document.getElementById('balasanId').value = id;
    document.getElementById('replyNama').textContent = nama;
    document.getElementById('replyIsi').textContent = isi;
    document.getElementById('replyPreview').style.display = 'block';
    textarea.focus();
}

function clearBalasan() {
    document.getElementById('balasanId').value = '';
    document.getElementById('replyPreview').style.display = 'none';
}

// Polling 3 detik
setInterval(async () => {
    try {
        const res = await fetch(`${pollUrl}?after_id=${lastMsgId}`, {
            headers: { 'Accept': 'application/json' }
        });
        const data = await res.json();
        if (data.data && data.data.length > 0) {
            data.data.forEach(msg => {
                if (msg.pengirim_id !== currentUserId) {
                    appendMessage(msg);
                }
                lastMsgId = Math.max(lastMsgId, msg.id);
            });
        }
    } catch (err) {}
}, 3000);

function filterChat() {
    const q = document.getElementById('searchChat').value.toLowerCase();
    document.querySelectorAll('.chat-item').forEach(el => {
        el.style.display = el.dataset.nama.includes(q) ? 'block' : 'none';
    });
}

function pesanBaru() {
    let optHtml = semuaUser.map(u => `<option value="${u.id}">${u.nama} (${u.peran})</option>`).join('');
    Swal.fire({
        title: 'Pesan Baru', html: `<select id="swal-target" class="swal2-select" style="width:100%;padding:8px;border-radius:8px;border:1px solid #ddd;">${optHtml}</select>`,
        showCancelButton: true, confirmButtonText: 'Mulai Chat', cancelButtonText: 'Batal', confirmButtonColor: '#6366f1',
        preConfirm: () => document.getElementById('swal-target').value
    }).then(r => {
        if (r.isConfirmed) {
            fetch(buatUrl, { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }, body: JSON.stringify({ tipe: 'pribadi', anggota_ids: [parseInt(r.value)] }) })
            .then(r => r.json()).then(d => { if (d.redirect) window.location.href = d.redirect; });
        }
    });
}

function buatGrup() {
    let ch = semuaUser.map(u => `<label class="d-flex align-items-center gap-2 py-1" style="font-size:.85rem;cursor:pointer;"><input type="checkbox" value="${u.id}" class="grup-anggota"> ${u.nama} <small class="text-muted">(${u.peran})</small></label>`).join('');
    Swal.fire({
        title: 'Buat Grup Baru',
        html: `<input id="swal-nama-grup" class="swal2-input" placeholder="Nama grup"><div style="max-height:250px;overflow-y:auto;text-align:left;padding:0 10px;">${ch}</div>`,
        showCancelButton: true, confirmButtonText: 'Buat Grup', cancelButtonText: 'Batal', confirmButtonColor: '#10b981',
        preConfirm: () => {
            const n = document.getElementById('swal-nama-grup').value;
            const ids = [...document.querySelectorAll('.grup-anggota:checked')].map(el => parseInt(el.value));
            if (!n) { Swal.showValidationMessage('Nama grup wajib diisi'); return false; }
            if (ids.length === 0) { Swal.showValidationMessage('Pilih minimal 1 anggota'); return false; }
            return { nama: n, ids };
        }
    }).then(r => {
        if (r.isConfirmed) {
            fetch(buatUrl, { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }, body: JSON.stringify({ tipe: 'grup', nama: r.value.nama, anggota_ids: r.value.ids }) })
            .then(r => r.json()).then(d => { if (d.redirect) window.location.href = d.redirect; });
        }
    });
}

@if($isGrup)
function lihatAnggota() {
    const anggota = @json($percakapan->anggota->map(fn($u) => ['nama' => $u->nama, 'peran' => $u->role_label]));
    let html = anggota.map(a => `<div class="d-flex align-items-center gap-2 py-2 border-bottom"><i class="bi bi-person-circle text-primary"></i> <span style="font-size:.85rem;">${a.nama}</span> <small class="text-muted">(${a.peran})</small></div>`).join('');
    Swal.fire({ title: 'Anggota Grup', html: `<div style="text-align:left;">${html}</div>`, confirmButtonColor: '#6366f1' });
}
@endif
</script>
@endpush
