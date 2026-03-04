@extends('staf.tata-letak.app')
@section('judul', 'Beranda')

@section('konten')
@php
    $user = Auth::user();
    $today = $user->attendances()->whereDate('date', today())->first();
    $monthAttendances = $user->attendances()->whereMonth('date', now()->month)->whereYear('date', now()->year)->get();
    $hadir = $monthAttendances->where('status', 'hadir')->count();
    $terlambat = $monthAttendances->where('status', 'terlambat')->count();
    $izin = $monthAttendances->whereIn('status', ['izin','sakit','cuti'])->count();
    $alpha = $monthAttendances->where('status', 'alpha')->count();
    $pendingLeave = $user->leaveRequests()->where('status', 'pending')->count();
    $upcomingEvents = \App\Models\Event::where('tanggal_acara', '>=', today())->where('status', 'upcoming')->take(4)->get();
    $notifications = $user->notifications()->where('sudah_dibaca', false)->latest()->take(5)->get();
@endphp

<!-- Greeting -->
<div class="mb-4">
    <h5 class="fw-bold mb-1">Halo, {{ $user->nama }}! 👋</h5>
    <p class="text-muted mb-0" style="font-size:.85rem;">{{ now()->translatedFormat('l, d F Y') }} &middot; {{ $user->jabatan ?? 'Staff TU' }}</p>
</div>

<!-- Quick Attendance -->
<div class="card mb-4" style="border-left: 4px solid {{ $today && $today->jam_masuk ? ($today->jam_pulang ? '#10b981' : '#f59e0b') : '#6366f1' }};">
    <div class="card-body py-3">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                @if(!$today || !$today->jam_masuk)
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2" style="font-size:.82rem;"><i class="bi bi-info-circle me-1"></i>Belum absen masuk</span>
                    </div>
                @elseif(!$today->jam_pulang)
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-success bg-opacity-10 text-success px-3 py-2" style="font-size:.82rem;"><i class="bi bi-check-circle me-1"></i>Masuk: {{ $today->jam_masuk }}</span>
                        @if($today->status == 'terlambat')<span class="badge bg-warning text-dark">Terlambat</span>@endif
                    </div>
                @else
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-success bg-opacity-10 text-success px-3 py-2" style="font-size:.82rem;"><i class="bi bi-check-circle-fill me-1"></i>Absensi lengkap</span>
                        <small class="text-muted">{{ $today->jam_masuk }} - {{ $today->jam_pulang }}</small>
                    </div>
                @endif
            </div>
            <a href="{{ route('staf.kehadiran.index') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-camera me-1"></i>{{ !$today || !$today->jam_masuk ? 'Absen Masuk' : (!$today->jam_pulang ? 'Absen Pulang' : 'Lihat Detail') }}
            </a>
        </div>
    </div>
</div>

<!-- Stats -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #10b981, #34d399);">
            <div class="d-flex justify-content-between align-items-start">
                <div><p>Hadir</p><h3>{{ $hadir }}</h3><p>bulan ini</p></div>
                <div class="icon-box"><i class="bi bi-check-lg"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #f59e0b, #fbbf24);">
            <div class="d-flex justify-content-between align-items-start">
                <div><p>Terlambat</p><h3>{{ $terlambat }}</h3><p>bulan ini</p></div>
                <div class="icon-box"><i class="bi bi-clock"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #6366f1, #818cf8);">
            <div class="d-flex justify-content-between align-items-start">
                <div><p>Izin/Sakit</p><h3>{{ $izin }}</h3><p>bulan ini</p></div>
                <div class="icon-box"><i class="bi bi-calendar-x"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #ef4444, #f87171);">
            <div class="d-flex justify-content-between align-items-start">
                <div><p>Alpha</p><h3>{{ $alpha }}</h3><p>bulan ini</p></div>
                <div class="icon-box"><i class="bi bi-x-lg"></i></div>
            </div>
        </div>
    </div>
</div>

<!-- Chart: Kehadiran Bulanan -->
<div class="row mb-4">
    <div class="col-lg-7">
        <div class="card h-100">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold" style="font-size:.9rem;"><i class="bi bi-bar-chart text-primary me-2"></i>Ringkasan Kehadiran Bulan Ini</h6>
            </div>
            <div class="card-body">
                <canvas id="myAttendanceBar" height="220"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card h-100">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold" style="font-size:.9rem;"><i class="bi bi-pie-chart text-info me-2"></i>Distribusi Kehadiran</h6>
            </div>
            <div class="card-body d-flex align-items-center justify-content-center">
                <div style="max-width:260px;max-height:260px;width:100%;">
                    <canvas id="myAttendanceChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <!-- Events -->
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h6 class="mb-0 fw-bold" style="font-size:.9rem;"><i class="bi bi-calendar-event text-primary me-2"></i>Agenda Mendatang</h6>
                <a href="{{ route('staf.agenda.index') }}" class="btn btn-sm btn-outline-primary">Semua</a>
            </div>
            <div class="card-body p-0">
                @forelse($upcomingEvents as $event)
                <a href="{{ route('staf.agenda.show', $event) }}" class="d-block px-3 py-2 border-bottom text-decoration-none">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-medium text-dark" style="font-size:.85rem;">{{ $event->judul }}</div>
                            <small class="text-muted"><i class="bi bi-calendar3 me-1"></i>{{ $event->tanggal_acara->format('d M Y') }}</small>
                        </div>
                        <span class="badge bg-info bg-opacity-10 text-info">{{ ucfirst($event->jenis) }}</span>
                    </div>
                </a>
                @empty
                <div class="text-center text-muted py-4" style="font-size:.82rem;">Tidak ada agenda mendatang</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Notifications -->
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h6 class="mb-0 fw-bold" style="font-size:.9rem;"><i class="bi bi-bell text-warning me-2"></i>Notifikasi Terbaru</h6>
                <a href="{{ route('staf.notifikasi.index') }}" class="btn btn-sm btn-outline-primary">Semua</a>
            </div>
            <div class="card-body p-0">
                @forelse($notifications as $notif)
                <div class="px-3 py-2 border-bottom">
                    <div class="fw-medium text-dark" style="font-size:.85rem;">{{ $notif->judul }}</div>
                    <small class="text-muted">{{ Str::limit($notif->pesan, 60) }}</small>
                    <div><small class="text-muted">{{ $notif->created_at->diffForHumans() }}</small></div>
                </div>
                @empty
                <div class="text-center text-muted py-4" style="font-size:.82rem;">Tidak ada notifikasi baru</div>
                @endforelse
            </div>
        </div>
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
                    <button class="btn btn-sm btn-success" onclick="tambahCatatan()"><i class="bi bi-plus-lg"></i> Tambah</button>
                </div>
                <div class="row g-2" id="catatan-container">
                    @forelse($catatanList as $cat)
                    <div class="col-md-4 col-lg-3" id="catatan-{{ $cat->id }}">
                        <div class="card h-100 border-start border-4" style="border-color: {{ $cat->warna ?? '#10b981' }} !important;">
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
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Bar chart
new Chart(document.getElementById('myAttendanceBar'), {
    type: 'bar',
    data: {
        labels: ['Hadir', 'Terlambat', 'Izin/Sakit', 'Alpha'],
        datasets: [{
            label: 'Jumlah',
            data: [{{ $hadir }}, {{ $terlambat }}, {{ $izin }}, {{ $alpha }}],
            backgroundColor: ['#10b981', '#f59e0b', '#6366f1', '#ef4444'],
            borderRadius: 6, barThickness: 40
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } }, x: { grid: { display: false } } }
    }
});

// Doughnut chart (properly sized)
new Chart(document.getElementById('myAttendanceChart'), {
    type: 'doughnut',
    data: {
        labels: ['Hadir', 'Terlambat', 'Izin/Sakit', 'Alpha'],
        datasets: [{
            data: [{{ $hadir }}, {{ $terlambat }}, {{ $izin }}, {{ $alpha }}],
            backgroundColor: ['#10b981', '#f59e0b', '#6366f1', '#ef4444'],
            borderWidth: 0, hoverOffset: 8
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: true,
        plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, padding: 12, font: { size: 11 } } } },
        cutout: '55%'
    }
});

// Catatan CRUD Functions
function tambahCatatan() {
    Swal.fire({
        title: 'Tambah Catatan',
        html: `<input id="swal-judul" class="swal2-input" placeholder="Judul catatan">
               <textarea id="swal-isi" class="swal2-textarea" placeholder="Isi catatan..."></textarea>
               <select id="swal-warna" class="swal2-select">
                   <option value="#10b981">🟢 Hijau</option>
                   <option value="#6366f1">🟣 Ungu</option>
                   <option value="#f59e0b">🟡 Kuning</option>
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
            fetch("{{ route('staf.catatan.store') }}", {
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
            fetch(`/staf/catatan/${id}`, {
                method: 'PUT', headers: {'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
                body: JSON.stringify(result.value)
            }).then(r => r.json()).then(d => { if(d.success) location.reload(); });
        }
    });
}

function hapusCatatan(id) {
    Swal.fire({ title: 'Hapus catatan?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#ef4444', confirmButtonText: 'Ya, hapus', cancelButtonText: 'Batal' }).then(result => {
        if (result.isConfirmed) {
            fetch(`/staf/catatan/${id}`, { method: 'DELETE', headers: {'X-CSRF-TOKEN':'{{ csrf_token() }}'} })
            .then(r => r.json()).then(d => { if(d.success) document.getElementById(`catatan-${id}`)?.remove(); });
        }
    });
}
</script>
@endpush
