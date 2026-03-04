{{-- Chat Index: Daftar Percakapan --}}
@php
    $routePrefix = auth()->user()->getRoutePrefix();
@endphp

<div class="chat-container" style="display:flex;height:calc(100vh - var(--header-h) - 40px);background:#fff;border-radius:14px;overflow:hidden;box-shadow:0 2px 12px rgba(0,0,0,.08);">
    {{-- Sidebar Chat --}}
    <div class="chat-sidebar" style="width:340px;border-right:1px solid #e5e7eb;display:flex;flex-direction:column;background:#fafbfc;">
        {{-- Header --}}
        <div style="padding:16px 18px;border-bottom:1px solid #e5e7eb;">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="fw-bold mb-0" style="font-size:.95rem;"><i class="bi bi-chat-dots-fill text-primary me-2"></i>Pesan</h6>
                <div class="d-flex gap-1">
                    <button class="btn btn-sm btn-outline-primary" onclick="pesanBaru()" title="Pesan Baru">
                        <i class="bi bi-pencil-square"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-success" onclick="buatGrup()" title="Buat Grup">
                        <i class="bi bi-people-fill"></i>
                    </button>
                </div>
            </div>
            <div class="position-relative">
                <i class="bi bi-search position-absolute" style="left:12px;top:50%;transform:translateY(-50%);color:#94a3b8;"></i>
                <input type="text" id="searchChat" class="form-control form-control-sm" placeholder="Cari percakapan..." style="padding-left:35px;border-radius:20px;background:#f1f5f9;border:1px solid #e5e7eb;font-size:.8rem;" onkeyup="filterChat()">
            </div>
        </div>

        {{-- List Percakapan --}}
        <div style="flex:1;overflow-y:auto;" id="chatList">
            @forelse($percakapan as $p)
                @php
                    $namaChat = $p->getNamaUntuk(auth()->id());
                    $lastMsg = $p->pesanTerakhir;
                    $unread = $p->pesanBelumDibaca(auth()->id());
                    $initials = strtoupper(substr($namaChat, 0, 2));
                    $isGrup = $p->tipe === 'grup';
                @endphp
                <a href="{{ route($routePrefix . '.chat.show', $p) }}" class="chat-item d-block text-decoration-none {{ isset($percakapan_aktif) && $percakapan_aktif == $p->id ? 'active' : '' }}" data-nama="{{ strtolower($namaChat) }}" style="padding:12px 18px;border-bottom:1px solid #f1f5f9;transition:background .15s;{{ isset($percakapan_aktif) && $percakapan_aktif == $p->id ? 'background:#eef2ff;' : '' }}">
                    <div class="d-flex align-items-center gap-10" style="gap:10px;">
                        <div style="width:44px;height:44px;border-radius:50%;background:{{ $isGrup ? 'linear-gradient(135deg,#10b981,#34d399)' : 'linear-gradient(135deg,#6366f1,#818cf8)' }};color:#fff;display:flex;align-items:center;justify-content:center;font-size:.75rem;font-weight:600;flex-shrink:0;">
                            @if($isGrup) <i class="bi bi-people-fill"></i> @else {{ $initials }} @endif
                        </div>
                        <div style="flex:1;min-width:0;">
                            <div class="d-flex justify-content-between align-items-center">
                                <strong style="font-size:.83rem;color:#1e293b;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:160px;display:block;">{{ $namaChat }}</strong>
                                @if($lastMsg)
                                    <small style="color:#94a3b8;font-size:.68rem;flex-shrink:0;">{{ $lastMsg->created_at->format('H:i') }}</small>
                                @endif
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <small style="color:#64748b;font-size:.75rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:180px;display:block;">
                                    @if($lastMsg)
                                        @if($lastMsg->pengirim_id === auth()->id()) <span style="color:#6366f1;">Anda:</span> @endif
                                        {{ \Str::limit($lastMsg->isi, 40) }}
                                    @else
                                        <em>Belum ada pesan</em>
                                    @endif
                                </small>
                                @if($unread > 0)
                                    <span class="badge rounded-pill bg-primary" style="font-size:.6rem;padding:3px 7px;">{{ $unread }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="text-center py-5">
                    <i class="bi bi-chat-square-text" style="font-size:3rem;color:#e5e7eb;"></i>
                    <p class="text-muted mt-2" style="font-size:.82rem;">Belum ada percakapan.<br>Klik <i class="bi bi-pencil-square"></i> untuk memulai.</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Main Area --}}
    <div style="flex:1;display:flex;align-items:center;justify-content:center;background:#f8fafc;">
        <div class="text-center">
            <i class="bi bi-chat-left-text" style="font-size:4rem;color:#e5e7eb;"></i>
            <h5 class="text-muted mt-3" style="font-size:1rem;">Pilih percakapan untuk mulai chat</h5>
            <p class="text-muted" style="font-size:.8rem;">atau buat percakapan baru</p>
        </div>
    </div>
</div>

@push('scripts')
<script>
const csrfToken = '{{ csrf_token() }}';
const buatUrl = '{{ route($routePrefix . ".chat.buat") }}';
const semuaUser = @json($semuaUser->map(fn($u) => ['id' => $u->id, 'nama' => $u->nama, 'peran' => $u->role_label]));

function filterChat() {
    const q = document.getElementById('searchChat').value.toLowerCase();
    document.querySelectorAll('.chat-item').forEach(el => {
        el.style.display = el.dataset.nama.includes(q) ? 'block' : 'none';
    });
}

function pesanBaru() {
    let optHtml = semuaUser.map(u => `<option value="${u.id}">${u.nama} (${u.peran})</option>`).join('');
    Swal.fire({
        title: 'Pesan Baru',
        html: `<select id="swal-target" class="swal2-select" style="width:100%;padding:8px;border-radius:8px;border:1px solid #ddd;">${optHtml}</select>`,
        showCancelButton: true, confirmButtonText: 'Mulai Chat', cancelButtonText: 'Batal',
        confirmButtonColor: '#6366f1',
        preConfirm: () => document.getElementById('swal-target').value
    }).then(result => {
        if (result.isConfirmed) {
            fetch(buatUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                body: JSON.stringify({ tipe: 'pribadi', anggota_ids: [parseInt(result.value)] })
            }).then(r => r.json()).then(d => { if (d.redirect) window.location.href = d.redirect; });
        }
    });
}

function buatGrup() {
    let checkboxHtml = semuaUser.map(u =>
        `<label class="d-flex align-items-center gap-2 py-1" style="font-size:.85rem;cursor:pointer;">
            <input type="checkbox" value="${u.id}" class="grup-anggota"> ${u.nama} <small class="text-muted">(${u.peran})</small>
        </label>`
    ).join('');

    Swal.fire({
        title: 'Buat Grup Baru',
        html: `
            <input id="swal-nama-grup" class="swal2-input" placeholder="Nama grup (cth: Tim Keuangan)" style="margin-bottom:10px;">
            <div style="max-height:250px;overflow-y:auto;text-align:left;padding:0 10px;">${checkboxHtml}</div>
        `,
        showCancelButton: true, confirmButtonText: 'Buat Grup', cancelButtonText: 'Batal',
        confirmButtonColor: '#10b981',
        preConfirm: () => {
            const nama = document.getElementById('swal-nama-grup').value;
            const ids = [...document.querySelectorAll('.grup-anggota:checked')].map(el => parseInt(el.value));
            if (!nama) { Swal.showValidationMessage('Nama grup wajib diisi'); return false; }
            if (ids.length === 0) { Swal.showValidationMessage('Pilih minimal 1 anggota'); return false; }
            return { nama, ids };
        }
    }).then(result => {
        if (result.isConfirmed) {
            fetch(buatUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                body: JSON.stringify({ tipe: 'grup', nama: result.value.nama, anggota_ids: result.value.ids })
            }).then(r => r.json()).then(d => { if (d.redirect) window.location.href = d.redirect; });
        }
    });
}

// Polling badge update
setInterval(() => {
    fetch('{{ route($routePrefix . ".chat.belum-dibaca") }}')
        .then(r => r.json())
        .then(d => {
            const badge = document.getElementById('chat-badge-sidebar');
            if (badge) { badge.textContent = d.total; badge.style.display = d.total > 0 ? 'inline-flex' : 'none'; }
        }).catch(() => {});
}, 15000);
</script>
@endpush
