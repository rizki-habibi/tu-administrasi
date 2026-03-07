@extends('peran.kepala-sekolah.app')
@section('judul', 'Dashboard Kepala Sekolah')

@section('konten')
<div class="mb-4">
    <h5 class="fw-bold mb-1">Selamat Datang, {{ Auth::user()->nama }}! 👋</h5>
    <p class="text-muted mb-0" style="font-size:.85rem;">{{ now()->translatedFormat('l, d F Y') }} &middot; Kepala Sekolah SMA Negeri 2 Jember</p>
</div>

<!-- Stats Overview -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #d97706, #f59e0b);">
            <div class="d-flex justify-content-between align-items-start">
                <div><p>Total Staff</p><h3>{{ $totalStaff }}</h3><p>staff aktif</p></div>
                <div class="icon-box"><i class="bi bi-people-fill"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #10b981, #34d399);">
            <div class="d-flex justify-content-between align-items-start">
                <div><p>Hadir Hari Ini</p><h3>{{ $todayPresent }}</h3><p>dari {{ $totalStaff }} staff</p></div>
                <div class="icon-box"><i class="bi bi-check-circle-fill"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #ef4444, #f87171);">
            <div class="d-flex justify-content-between align-items-start">
                <div><p>Izin Menunggu</p><h3>{{ $pendingLeave }}</h3><p>menunggu persetujuan</p></div>
                <div class="icon-box"><i class="bi bi-hourglass-split"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #6366f1, #818cf8);">
            <div class="d-flex justify-content-between align-items-start">
                <div><p>SKP Diajukan</p><h3>{{ $pendingSkp }}</h3><p>perlu penilaian</p></div>
                <div class="icon-box"><i class="bi bi-person-lines-fill"></i></div>
            </div>
        </div>
    </div>
</div>

<!-- Monthly Attendance Chart -->
<div class="row g-3 mb-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="fw-bold mb-0" style="font-size:.9rem;"><i class="bi bi-bar-chart-fill text-warning me-2"></i>Rekap Kehadiran Bulan {{ now()->translatedFormat('F Y') }}</h6>
            </div>
            <div class="card-body">
                <div class="row text-center g-2">
                    @php
                        $statItems = [
                            ['label' => 'Hadir', 'count' => $monthlyAttendance['hadir'], 'color' => '#10b981', 'icon' => 'bi-check-circle'],
                            ['label' => 'Terlambat', 'count' => $monthlyAttendance['terlambat'], 'color' => '#f59e0b', 'icon' => 'bi-clock-history'],
                            ['label' => 'Izin', 'count' => $monthlyAttendance['izin'], 'color' => '#3b82f6', 'icon' => 'bi-calendar-x'],
                            ['label' => 'Sakit', 'count' => $monthlyAttendance['sakit'], 'color' => '#ef4444', 'icon' => 'bi-heart-pulse'],
                            ['label' => 'Alpha', 'count' => $monthlyAttendance['alpha'], 'color' => '#6b7280', 'icon' => 'bi-x-circle'],
                        ];
                    @endphp
                    @foreach($statItems as $item)
                    <div class="col">
                        <div class="p-3 rounded-3" style="background: {{ $item['color'] }}10;">
                            <i class="bi {{ $item['icon'] }}" style="font-size:1.5rem; color:{{ $item['color'] }};"></i>
                            <h4 class="fw-bold mb-0 mt-1" style="color:{{ $item['color'] }};">{{ $item['count'] }}</h4>
                            <small class="text-muted">{{ $item['label'] }}</small>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="fw-bold mb-0" style="font-size:.9rem;"><i class="bi bi-calendar-event text-warning me-2"></i>Agenda Terdekat</h6>
            </div>
            <div class="card-body p-0">
                @forelse($upcomingEvents as $event)
                <a href="{{ route('kepala-sekolah.agenda.show', $event) }}" class="d-flex gap-3 px-3 py-2 border-bottom text-decoration-none">
                    <div class="text-center flex-shrink-0" style="width:42px;">
                        <div class="fw-bold" style="font-size:1.1rem;color:#d97706;">{{ $event->tanggal_acara->format('d') }}</div>
                        <div style="font-size:.65rem;color:#78716c;">{{ $event->tanggal_acara->translatedFormat('M') }}</div>
                    </div>
                    <div class="overflow-hidden">
                        <div class="fw-semibold text-dark" style="font-size:.82rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $event->judul }}</div>
                        <small class="text-muted">{{ $event->lokasi ?? '-' }}</small>
                    </div>
                </a>
                @empty
                <div class="text-center py-4 text-muted" style="font-size:.82rem;">Tidak ada agenda terdekat</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Pending Leave Requests -->
@if($recentLeaves->count() > 0)
<div class="card mb-4">
    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
        <h6 class="fw-bold mb-0" style="font-size:.9rem;"><i class="bi bi-calendar2-check text-danger me-2"></i>Izin Menunggu Persetujuan</h6>
        <a href="{{ route('kepala-sekolah.izin.index', ['status'=>'pending']) }}" class="btn btn-sm btn-outline-warning">Lihat Semua</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead><tr><th>Pegawai</th><th>Jenis</th><th>Tanggal</th><th>Alasan</th><th>Aksi</th></tr></thead>
                <tbody>
                @foreach($recentLeaves as $leave)
                <tr>
                    <td>
                        <div class="fw-semibold">{{ $leave->user->nama }}</div>
                        <small class="text-muted">{{ $leave->user->role_label }}</small>
                    </td>
                    <td><span class="badge bg-info bg-opacity-10 text-info">{{ ucfirst($leave->jenis) }}</span></td>
                    <td style="font-size:.8rem;">{{ $leave->tanggal_mulai->format('d/m/Y') }} - {{ $leave->tanggal_selesai->format('d/m/Y') }}</td>
                    <td style="font-size:.8rem;">{{ \Str::limit($leave->reason, 40) }}</td>
                    <td>
                        <a href="{{ route('kepala-sekolah.izin.show', $leave) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

<!-- Quick Links -->
<div class="row g-3">
    <div class="col-md-4">
        <a href="{{ route('kepala-sekolah.skp.index', ['status'=>'diajukan']) }}" class="card text-decoration-none h-100">
            <div class="card-body text-center py-4">
                <div class="mb-2"><i class="bi bi-person-lines-fill" style="font-size:2rem;color:#d97706;"></i></div>
                <h6 class="fw-bold">Penilaian SKP</h6>
                <p class="text-muted mb-0" style="font-size:.8rem;">{{ $pendingSkp }} SKP menunggu penilaian</p>
            </div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="{{ route('kepala-sekolah.kehadiran.laporan') }}" class="card text-decoration-none h-100">
            <div class="card-body text-center py-4">
                <div class="mb-2"><i class="bi bi-graph-up-arrow" style="font-size:2rem;color:#10b981;"></i></div>
                <h6 class="fw-bold">Rekap Kehadiran</h6>
                <p class="text-muted mb-0" style="font-size:.8rem;">Monitoring kehadiran staff</p>
            </div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="{{ route('kepala-sekolah.keuangan.index') }}" class="card text-decoration-none h-100">
            <div class="card-body text-center py-4">
                <div class="mb-2"><i class="bi bi-cash-coin" style="font-size:2rem;color:#6366f1;"></i></div>
                <h6 class="fw-bold">Rekapitulasi Keuangan</h6>
                <p class="text-muted mb-0" style="font-size:.8rem;">Overview keuangan sekolah</p>
            </div>
        </a>
    </div>
</div>

{{-- Ulang Tahun Hari Ini --}}
@if(isset($birthdayUsers) && $birthdayUsers->count() > 0)
<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="fw-bold mb-3"><i class="bi bi-cake2-fill text-warning me-2"></i>Ulang Tahun Hari Ini 🎉</h6>
                <div class="d-flex flex-wrap gap-3">
                    @foreach($birthdayUsers as $bu)
                    <div class="d-flex align-items-center gap-2 bg-light rounded-pill px-3 py-2">
                        <div class="rounded-circle bg-warning d-flex align-items-center justify-content-center" style="width:36px;height:36px;">
                            <i class="bi bi-person-fill text-white"></i>
                        </div>
                        <div>
                            <strong style="font-size:.85rem;">{{ $bu->nama }}</strong>
                            <br><small class="text-muted">{{ $bu->jabatan ?? $bu->peran }}</small>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endif

{{-- Catatan Beranda --}}
@if(isset($catatanList))
<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0"><i class="bi bi-sticky-fill text-info me-2"></i>Catatan / Notula</h6>
                    <button class="btn btn-sm btn-warning" onclick="tambahCatatan()"><i class="bi bi-plus-lg"></i> Tambah</button>
                </div>
                <div class="row g-2" id="catatan-container">
                    @forelse($catatanList as $cat)
                    <div class="col-md-4 col-lg-3" id="catatan-{{ $cat->id }}">
                        <div class="card h-100 border-start border-4" style="border-color: {{ $cat->warna ?? '#d97706' }} !important;">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between">
                                    <strong style="font-size:.85rem;">{{ $cat->judul }}</strong>
                                    <div class="dropdown">
                                        <i class="bi bi-three-dots-vertical" data-bs-toggle="dropdown" style="cursor:pointer;"></i>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item" href="#" onclick="editCatatan({{ $cat->id }})"><i class="bi bi-pencil me-1"></i>Edit</a></li>
                                            <li><a class="dropdown-item text-danger" href="#" onclick="hapusCatatan({{ $cat->id }})"><i class="bi bi-trash me-1"></i>Hapus</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <p class="text-muted mb-1" style="font-size:.8rem;">{{ Str::limit($cat->isi, 100) }}</p>
                                <small class="text-muted"><i class="bi bi-calendar3 me-1"></i>{{ $cat->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12">
                        <p class="text-muted text-center mb-0" style="font-size:.85rem;">Belum ada catatan. Klik "Tambah" untuk membuat.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@include('komponen.ulang-tahun-popup')

{{-- AI Assistant Widget (floating) --}}
<div id="ai-assistant" style="position:fixed;bottom:24px;right:24px;z-index:1050;">
    {{-- Toggle Button --}}
    <button id="ai-toggle-btn" class="btn btn-primary rounded-circle shadow-lg d-flex align-items-center justify-content-center"
        style="width:56px;height:56px;background:linear-gradient(135deg,#6366f1,#8b5cf6);border:none;" title="AI Assistant">
        <i class="bi bi-stars" style="font-size:1.5rem;"></i>
    </button>

    {{-- Chat Panel --}}
    <div id="ai-panel" class="card border-0 shadow-lg d-none" style="position:absolute;bottom:70px;right:0;width:380px;max-height:520px;border-radius:16px;overflow:hidden;">
        <div class="card-header text-white py-3 border-0" style="background:linear-gradient(135deg,#6366f1,#8b5cf6);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fw-bold mb-0"><i class="bi bi-stars me-2"></i>AI Assistant</h6>
                    <small class="opacity-75">Tanya apa saja tentang data sekolah</small>
                </div>
                <button class="btn btn-sm btn-light btn-close-ai" style="opacity:.8;" onclick="document.getElementById('ai-panel').classList.add('d-none')">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        </div>
        <div id="ai-messages" class="card-body p-3" style="max-height:340px;overflow-y:auto;background:#f8f9fa;">
            <div class="ai-msg ai-msg-bot mb-3">
                <div class="d-flex gap-2">
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center flex-shrink-0"
                         style="width:32px;height:32px;font-size:.8rem;">AI</div>
                    <div class="bg-white rounded-3 p-2 px-3 shadow-sm" style="font-size:.85rem;">
                        Halo Kepala Sekolah! Saya asisten AI Anda. Tanyakan tentang data kehadiran, kinerja staff, SKP, atau apa saja tentang sekolah. 🎓
                    </div>
                </div>
            </div>
            {{-- AI Ringkasan --}}
            <div id="ai-ringkasan" class="mb-3 d-none">
                <div class="d-flex gap-2">
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center flex-shrink-0"
                         style="width:32px;height:32px;font-size:.8rem;">AI</div>
                    <div class="bg-white rounded-3 p-2 px-3 shadow-sm" style="font-size:.85rem;">
                        <strong class="text-primary"><i class="bi bi-graph-up me-1"></i>Ringkasan Hari Ini:</strong>
                        <div id="ai-ringkasan-text" class="mt-1"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer bg-white border-top p-3">
            <div class="d-flex gap-2">
                <input type="text" id="ai-input" class="form-control form-control-sm rounded-pill"
                    placeholder="Tanya AI..." style="font-size:.85rem;"
                    onkeydown="if(event.key==='Enter')kirimPertanyaanAI()">
                <button class="btn btn-primary btn-sm rounded-pill px-3" onclick="kirimPertanyaanAI()" id="ai-send-btn">
                    <i class="bi bi-send-fill"></i>
                </button>
            </div>
            <div class="d-flex gap-1 mt-2 flex-wrap">
                <button class="btn btn-outline-secondary rounded-pill px-2 py-0" style="font-size:.7rem;" onclick="kirimCepat('Berapa kehadiran hari ini?')">
                    <i class="bi bi-people me-1"></i>Kehadiran
                </button>
                <button class="btn btn-outline-secondary rounded-pill px-2 py-0" style="font-size:.7rem;" onclick="kirimCepat('Bagaimana kinerja staff bulan ini?')">
                    <i class="bi bi-bar-chart me-1"></i>Kinerja
                </button>
                <button class="btn btn-outline-secondary rounded-pill px-2 py-0" style="font-size:.7rem;" onclick="kirimCepat('Ada berapa izin yang menunggu persetujuan?')">
                    <i class="bi bi-envelope me-1"></i>Izin
                </button>
                <button class="btn btn-outline-secondary rounded-pill px-2 py-0" style="font-size:.7rem;" onclick="kirimCepat('Berapa SKP yang perlu dinilai?')">
                    <i class="bi bi-file-earmark-check me-1"></i>SKP
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// AI Assistant Toggle
document.getElementById('ai-toggle-btn').addEventListener('click', function() {
    const panel = document.getElementById('ai-panel');
    panel.classList.toggle('d-none');
    if (!panel.classList.contains('d-none')) {
        document.getElementById('ai-input').focus();
        loadAiRingkasan();
    }
});

let ringkasanLoaded = false;
function loadAiRingkasan() {
    if (ringkasanLoaded) return;
    ringkasanLoaded = true;

    fetch('{{ route("kepala-sekolah.ai.ringkasan") }}', {
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success && data.ringkasan) {
            document.getElementById('ai-ringkasan-text').textContent = data.ringkasan;
            document.getElementById('ai-ringkasan').classList.remove('d-none');
            scrollAiMessages();
        }
    })
    .catch(() => {});
}

function kirimPertanyaanAI() {
    const input = document.getElementById('ai-input');
    const pertanyaan = input.value.trim();
    if (!pertanyaan) return;

    appendAiMessage(pertanyaan, 'user');
    input.value = '';

    const loadingId = 'ai-loading-' + Date.now();
    appendAiLoading(loadingId);

    fetch('{{ route("kepala-sekolah.ai.assistant") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ pertanyaan })
    })
    .then(r => r.json())
    .then(data => {
        removeElement(loadingId);
        appendAiMessage(data.jawaban, 'bot', true);
    })
    .catch(() => {
        removeElement(loadingId);
        appendAiMessage('Maaf, terjadi gangguan koneksi. Coba lagi nanti.', 'bot');
    });
}

function kirimCepat(text) {
    document.getElementById('ai-input').value = text;
    kirimPertanyaanAI();
}

function appendAiMessage(text, type, isHtml = false) {
    const container = document.getElementById('ai-messages');
    const div = document.createElement('div');
    div.className = 'ai-msg mb-3';

    if (type === 'user') {
        div.innerHTML = `
            <div class="d-flex gap-2 justify-content-end">
                <div class="bg-primary text-white rounded-3 p-2 px-3 shadow-sm" style="font-size:.85rem;max-width:85%;">
                    ${escapeHtml(text)}
                </div>
            </div>`;
    } else {
        const content = isHtml ? text : escapeHtml(text);
        div.innerHTML = `
            <div class="d-flex gap-2">
                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center flex-shrink-0"
                     style="width:32px;height:32px;font-size:.8rem;">AI</div>
                <div class="bg-white rounded-3 p-2 px-3 shadow-sm" style="font-size:.85rem;max-width:85%;">
                    ${content}
                </div>
            </div>`;
    }
    container.appendChild(div);
    scrollAiMessages();
}

function appendAiLoading(id) {
    const container = document.getElementById('ai-messages');
    const div = document.createElement('div');
    div.id = id;
    div.className = 'ai-msg mb-3';
    div.innerHTML = `
        <div class="d-flex gap-2">
            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center flex-shrink-0"
                 style="width:32px;height:32px;font-size:.8rem;">AI</div>
            <div class="bg-white rounded-3 p-2 px-3 shadow-sm" style="font-size:.85rem;">
                <div class="spinner-grow spinner-grow-sm text-primary me-1"></div>
                <span class="text-muted">Sedang berpikir...</span>
            </div>
        </div>`;
    container.appendChild(div);
    scrollAiMessages();
}

function scrollAiMessages() {
    const c = document.getElementById('ai-messages');
    c.scrollTop = c.scrollHeight;
}

function removeElement(id) {
    document.getElementById(id)?.remove();
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
</script>
@endpush

@push('scripts')
<script>
// Catatan CRUD Functions
function tambahCatatan() {
    Swal.fire({
        title: 'Tambah Catatan',
        html: `<input id="swal-judul" class="swal2-input" placeholder="Judul catatan">
               <textarea id="swal-isi" class="swal2-textarea" placeholder="Isi catatan..."></textarea>
               <select id="swal-warna" class="swal2-select">
                   <option value="#d97706">🟡 Emas</option>
                   <option value="#10b981">🟢 Hijau</option>
                   <option value="#6366f1">🟣 Ungu</option>
                   <option value="#ef4444">🔴 Merah</option>
                   <option value="#3b82f6">🔵 Biru</option>
               </select>`,
        showCancelButton: true, confirmButtonText: 'Simpan', cancelButtonText: 'Batal',
        preConfirm: () => {
            const judul = document.getElementById('swal-judul').value;
            const isi = document.getElementById('swal-isi').value;
            if (!judul || !isi) { Swal.showValidationMessage('Judul dan isi wajib diisi'); return false; }
            return { judul, isi, warna: document.getElementById('swal-warna').value };
        }
    }).then(result => {
        if (result.isConfirmed) {
            fetch("{{ route('kepala-sekolah.catatan.store') }}", {
                method: 'POST', headers: {'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
                body: JSON.stringify(result.value)
            }).then(r => r.json()).then(d => { if(d.success) location.reload(); else Swal.fire('Error', d.message, 'error'); });
        }
    });
}

function editCatatan(id) {
    const card = document.querySelector(`#catatan-${id}`);
    const judul = card?.querySelector('strong')?.textContent || '';
    Swal.fire({
        title: 'Edit Catatan',
        html: `<input id="swal-judul" class="swal2-input" value="${judul}" placeholder="Judul">
               <textarea id="swal-isi" class="swal2-textarea" placeholder="Isi catatan..."></textarea>`,
        showCancelButton: true, confirmButtonText: 'Simpan', cancelButtonText: 'Batal',
        preConfirm: () => ({ judul: document.getElementById('swal-judul').value, isi: document.getElementById('swal-isi').value })
    }).then(result => {
        if (result.isConfirmed) {
            fetch(`/kepala-sekolah/catatan/${id}`, {
                method: 'PUT', headers: {'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
                body: JSON.stringify(result.value)
            }).then(r => r.json()).then(d => { if(d.success) location.reload(); });
        }
    });
}

function hapusCatatan(id) {
    Swal.fire({ title: 'Hapus catatan?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#ef4444', confirmButtonText: 'Ya, hapus', cancelButtonText: 'Batal' }).then(result => {
        if (result.isConfirmed) {
            fetch(`/kepala-sekolah/catatan/${id}`, { method: 'DELETE', headers: {'X-CSRF-TOKEN':'{{ csrf_token() }}'} })
            .then(r => r.json()).then(d => { if(d.success) document.getElementById(`catatan-${id}`)?.remove(); });
        }
    });
}
</script>
@endpush
