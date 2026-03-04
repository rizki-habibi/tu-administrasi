@extends('kepala-sekolah.tata-letak.app')
@section('judul', 'Kehadiran Hari Ini')

@section('konten')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-1">Kehadiran Hari Ini</h5>
        <p class="text-muted mb-0" style="font-size:.85rem;">{{ now()->translatedFormat('l, d F Y') }}</p>
    </div>
    <a href="{{ route('kepala-sekolah.kehadiran.laporan') }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-graph-up me-1"></i>Rekap Bulanan</a>
</div>

{{-- Summary Cards --}}
@php
    $hadir = $todayAttendances->where('status', 'hadir')->count();
    $terlambat = $todayAttendances->where('status', 'terlambat')->count();
    $izin = $todayAttendances->whereIn('status', ['izin','sakit','cuti'])->count();
    $belum = $allStaff->count() - $todayAttendances->count();
@endphp
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#10b981,#34d399);">
            <div class="d-flex justify-content-between align-items-start">
                <div><p>Hadir</p><h3>{{ $hadir }}</h3></div>
                <div class="icon-box"><i class="bi bi-check-circle-fill"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#f59e0b,#fbbf24);">
            <div class="d-flex justify-content-between align-items-start">
                <div><p>Terlambat</p><h3>{{ $terlambat }}</h3></div>
                <div class="icon-box"><i class="bi bi-clock-history"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#3b82f6,#60a5fa);">
            <div class="d-flex justify-content-between align-items-start">
                <div><p>Izin/Sakit/Cuti</p><h3>{{ $izin }}</h3></div>
                <div class="icon-box"><i class="bi bi-calendar-x"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#6b7280,#9ca3af);">
            <div class="d-flex justify-content-between align-items-start">
                <div><p>Belum Absen</p><h3>{{ $belum }}</h3></div>
                <div class="icon-box"><i class="bi bi-person-x"></i></div>
            </div>
        </div>
    </div>
</div>

{{-- Attendance Table --}}
<div class="card">
    <div class="card-header bg-white border-0 py-3">
        <h6 class="fw-bold mb-0" style="font-size:.9rem;"><i class="bi bi-list-check text-warning me-2"></i>Daftar Kehadiran Staff</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr><th>#</th><th>Pegawai</th><th>Masuk</th><th>Keluar</th><th>Status</th><th>Catatan</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                @php $no = 1; @endphp
                @foreach($allStaff as $s)
                    @php $att = $todayAttendances->firstWhere('pengguna_id', $s->id); @endphp
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>
                            <div class="fw-semibold">{{ $s->nama }}</div>
                            <small class="text-muted">{{ $s->role_label }}</small>
                        </td>
                        <td>{{ $att->jam_masuk ?? '-' }}</td>
                        <td>{{ $att->jam_pulang ?? '-' }}</td>
                        <td>
                            @if($att)
                                <span class="badge bg-{{ $att->status_badge }} bg-opacity-10 text-{{ $att->status_badge }}">{{ ucfirst($att->status) }}</span>
                            @else
                                <span class="badge bg-secondary bg-opacity-10 text-secondary">Belum Absen</span>
                            @endif
                        </td>
                        <td style="max-width:180px;font-size:.8rem;">{{ $att->catatan ?? '-' }}</td>
                        <td>
                            @if($att)
                                <a href="{{ route('kepala-sekolah.kehadiran.show', $att) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-eye"></i></a>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
