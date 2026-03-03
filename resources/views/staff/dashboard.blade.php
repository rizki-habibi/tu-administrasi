@extends('layouts.staff')
@section('title', 'Beranda')

@section('content')
@php
    $user = Auth::user();
    $today = $user->attendances()->whereDate('date', today())->first();
    $monthAttendances = $user->attendances()->whereMonth('date', now()->month)->whereYear('date', now()->year)->get();
    $hadir = $monthAttendances->where('status', 'hadir')->count();
    $terlambat = $monthAttendances->where('status', 'terlambat')->count();
    $izin = $monthAttendances->whereIn('status', ['izin','sakit','cuti'])->count();
    $alpha = $monthAttendances->where('status', 'alpha')->count();
    $pendingLeave = $user->leaveRequests()->where('status', 'pending')->count();
    $upcomingEvents = \App\Models\Event::where('event_date', '>=', today())->where('status', 'upcoming')->take(4)->get();
    $notifications = $user->notifications()->where('is_read', false)->latest()->take(5)->get();
@endphp

<!-- Greeting -->
<div class="mb-4">
    <h5 class="fw-bold mb-1">Halo, {{ $user->name }}! 👋</h5>
    <p class="text-muted mb-0" style="font-size:.85rem;">{{ now()->translatedFormat('l, d F Y') }} &middot; {{ $user->position ?? 'Staff TU' }}</p>
</div>

<!-- Quick Attendance -->
<div class="card mb-4" style="border-left: 4px solid {{ $today && $today->clock_in ? ($today->clock_out ? '#10b981' : '#f59e0b') : '#6366f1' }};">
    <div class="card-body py-3">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                @if(!$today || !$today->clock_in)
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2" style="font-size:.82rem;"><i class="bi bi-info-circle me-1"></i>Belum absen masuk</span>
                    </div>
                @elseif(!$today->clock_out)
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-success bg-opacity-10 text-success px-3 py-2" style="font-size:.82rem;"><i class="bi bi-check-circle me-1"></i>Masuk: {{ $today->clock_in }}</span>
                        @if($today->status == 'terlambat')<span class="badge bg-warning text-dark">Terlambat</span>@endif
                    </div>
                @else
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-success bg-opacity-10 text-success px-3 py-2" style="font-size:.82rem;"><i class="bi bi-check-circle-fill me-1"></i>Absensi lengkap</span>
                        <small class="text-muted">{{ $today->clock_in }} - {{ $today->clock_out }}</small>
                    </div>
                @endif
            </div>
            <a href="{{ route('staff.attendance.index') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-camera me-1"></i>{{ !$today || !$today->clock_in ? 'Absen Masuk' : (!$today->clock_out ? 'Absen Pulang' : 'Lihat Detail') }}
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
                <a href="{{ route('staff.event.index') }}" class="btn btn-sm btn-outline-primary">Semua</a>
            </div>
            <div class="card-body p-0">
                @forelse($upcomingEvents as $event)
                <a href="{{ route('staff.event.show', $event) }}" class="d-block px-3 py-2 border-bottom text-decoration-none">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-medium text-dark" style="font-size:.85rem;">{{ $event->title }}</div>
                            <small class="text-muted"><i class="bi bi-calendar3 me-1"></i>{{ $event->event_date->format('d M Y') }}</small>
                        </div>
                        <span class="badge bg-info bg-opacity-10 text-info">{{ ucfirst($event->type) }}</span>
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
                <a href="{{ route('staff.notification.index') }}" class="btn btn-sm btn-outline-primary">Semua</a>
            </div>
            <div class="card-body p-0">
                @forelse($notifications as $notif)
                <div class="px-3 py-2 border-bottom">
                    <div class="fw-medium text-dark" style="font-size:.85rem;">{{ $notif->title }}</div>
                    <small class="text-muted">{{ Str::limit($notif->message, 60) }}</small>
                    <div><small class="text-muted">{{ $notif->created_at->diffForHumans() }}</small></div>
                </div>
                @empty
                <div class="text-center text-muted py-4" style="font-size:.82rem;">Tidak ada notifikasi baru</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
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
</script>
@endpush
