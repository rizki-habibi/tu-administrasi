@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
<!-- Greeting -->
<div class="mb-4">
    <h5 class="fw-bold mb-1">Selamat {{ now()->hour < 12 ? 'Pagi' : (now()->hour < 15 ? 'Siang' : (now()->hour < 18 ? 'Sore' : 'Malam')) }}, {{ Auth::user()->name }}! 👋</h5>
    <p class="text-muted mb-0" style="font-size:.85rem;">Berikut ringkasan data hari ini, {{ now()->translatedFormat('l, d F Y') }}</p>
</div>

<!-- Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #6366f1, #818cf8);">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p>Total Staff</p>
                    <h3>{{ $totalStaff }}</h3>
                    <p>{{ $activeStaff }} aktif</p>
                </div>
                <div class="icon-box"><i class="bi bi-people-fill"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #10b981, #34d399);">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p>Hadir Hari Ini</p>
                    <h3>{{ $todayPresent }}</h3>
                    <p>{{ $todayLate }} terlambat</p>
                </div>
                <div class="icon-box"><i class="bi bi-check-circle-fill"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #f59e0b, #fbbf24);">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p>Izin Pending</p>
                    <h3>{{ $pendingLeave }}</h3>
                    <p>menunggu approval</p>
                </div>
                <div class="icon-box"><i class="bi bi-clock-fill"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #8b5cf6, #a78bfa);">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p>Dokumen</p>
                    <h3>{{ $totalDocs }}</h3>
                    <p>{{ $monthReports }} laporan bulan ini</p>
                </div>
                <div class="icon-box"><i class="bi bi-folder-fill"></i></div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <!-- Chart: Kehadiran 7 Hari Terakhir -->
    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold" style="font-size:.9rem;"><i class="bi bi-bar-chart text-primary me-2"></i>Grafik Kehadiran 7 Hari Terakhir</h6>
            </div>
            <div class="card-body">
                <canvas id="weeklyChart" height="200"></canvas>
            </div>
        </div>
    </div>

    <!-- Chart: Distribusi Status Bulan Ini -->
    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold" style="font-size:.9rem;"><i class="bi bi-pie-chart text-info me-2"></i>Status Kehadiran Bulan Ini</h6>
            </div>
            <div class="card-body d-flex align-items-center justify-content-center">
                <canvas id="statusChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <!-- Kehadiran Hari Ini -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h6 class="mb-0 fw-bold" style="font-size:.9rem;"><i class="bi bi-clock-history text-primary me-2"></i>Kehadiran Hari Ini</h6>
                <a href="{{ route('admin.attendance.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light"><tr><th>Staff</th><th>Masuk</th><th>Pulang</th><th>Status</th></tr></thead>
                    <tbody>
                        @forelse($recentAttendances as $att)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#6366f1,#8b5cf6);color:#fff;display:flex;align-items:center;justify-content:center;font-size:.7rem;font-weight:600;">{{ strtoupper(substr($att->user->name ?? '?', 0, 2)) }}</div>
                                    <span>{{ $att->user->name ?? '-' }}</span>
                                </div>
                            </td>
                            <td>{{ $att->clock_in ?? '-' }}</td>
                            <td>{{ $att->clock_out ?? '-' }}</td>
                            <td>
                                @php $sc = ['hadir'=>'success','terlambat'=>'warning','izin'=>'info','sakit'=>'secondary','alpha'=>'danger']; @endphp
                                <span class="badge bg-{{ $sc[$att->status] ?? 'secondary' }}">{{ ucfirst($att->status) }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted py-4">Belum ada data kehadiran hari ini</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Sidebar Widgets -->
    <div class="col-lg-4">
        <!-- Pending Leave -->
        <div class="card mb-3">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold" style="font-size:.9rem;"><i class="bi bi-calendar2-x text-warning me-2"></i>Izin Pending</h6>
            </div>
            <div class="card-body p-0">
                @forelse($recentLeaves as $leave)
                <a href="{{ route('admin.leave.show', $leave) }}" class="d-block px-3 py-2 text-decoration-none border-bottom" style="font-size:.82rem;">
                    <div class="d-flex justify-content-between">
                        <strong class="text-dark">{{ $leave->user->name ?? '-' }}</strong>
                        <span class="badge bg-warning">{{ ucfirst(str_replace('_',' ',$leave->type)) }}</span>
                    </div>
                    <small class="text-muted">{{ $leave->start_date->format('d/m') }} - {{ $leave->end_date->format('d/m/Y') }}</small>
                </a>
                @empty
                <div class="text-center text-muted py-4" style="font-size:.82rem;">Tidak ada izin pending</div>
                @endforelse
            </div>
        </div>

        <!-- Upcoming Events -->
        <div class="card">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold" style="font-size:.9rem;"><i class="bi bi-calendar-event text-info me-2"></i>Agenda Mendatang</h6>
            </div>
            <div class="card-body p-0">
                @forelse($upcomingEvents as $event)
                <div class="px-3 py-2 border-bottom" style="font-size:.82rem;">
                    <div class="fw-medium text-dark">{{ $event->title }}</div>
                    <small class="text-muted"><i class="bi bi-calendar3 me-1"></i>{{ $event->event_date->format('d M Y') }} &middot; {{ $event->start_time }}</small>
                </div>
                @empty
                <div class="text-center text-muted py-4" style="font-size:.82rem;">Tidak ada agenda</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Weekly Attendance Chart
new Chart(document.getElementById('weeklyChart'), {
    type: 'bar',
    data: {
        labels: @json($weeklyLabels),
        datasets: [
            { label: 'Hadir', data: @json($weeklyHadir), backgroundColor: '#10b981', borderRadius: 4 },
            { label: 'Terlambat', data: @json($weeklyTerlambat), backgroundColor: '#f59e0b', borderRadius: 4 },
            { label: 'Alpha', data: @json($weeklyAlpha), backgroundColor: '#ef4444', borderRadius: 4 }
        ]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, padding: 15 } } },
        scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } }, x: { grid: { display: false } } }
    }
});

// Status Distribution Doughnut
new Chart(document.getElementById('statusChart'), {
    type: 'doughnut',
    data: {
        labels: @json(array_keys($statusDistribution)),
        datasets: [{
            data: @json(array_values($statusDistribution)),
            backgroundColor: ['#10b981', '#f59e0b', '#3b82f6', '#8b5cf6', '#ef4444'],
            borderWidth: 0, hoverOffset: 8
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, padding: 12, font: { size: 11 } } } },
        cutout: '60%'
    }
});
</script>
@endpush
