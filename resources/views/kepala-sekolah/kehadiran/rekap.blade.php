@extends('peran.kepala-sekolah.app')
@section('judul', 'Rekap Kehadiran Bulanan')

@section('konten')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-1">Rekap Kehadiran Bulanan</h5>
        <p class="text-muted mb-0" style="font-size:.85rem;">Laporan kehadiran per bulan</p>
    </div>
    <a href="{{ route('kepala-sekolah.kehadiran.index') }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-calendar-day me-1"></i>Hari Ini</a>
</div>

{{-- Filter --}}
<div class="card mb-3">
    <div class="card-body py-2">
        <form class="d-flex gap-2 align-items-center flex-wrap" method="GET">
            <select name="month" class="form-select form-select-sm" style="width:auto;">
                @for($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}</option>
                @endfor
            </select>
            <select name="year" class="form-select form-select-sm" style="width:auto;">
                @for($y = now()->year; $y >= now()->year - 3; $y--)
                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
            <select name="pengguna_id" class="form-select form-select-sm" style="width:auto;">
                <option value="">Semua Staff</option>
                @foreach($staffs as $s)
                    <option value="{{ $s->id }}" {{ request('pengguna_id') == $s->id ? 'selected' : '' }}>{{ $s->nama }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-sm btn-outline-warning"><i class="bi bi-funnel"></i> Filter</button>
        </form>
    </div>
</div>

{{-- Summary --}}
@php
    $totalHadir = $attendances->where('status','hadir')->count();
    $totalTerlambat = $attendances->where('status','terlambat')->count();
    $totalIzin = $attendances->where('status','izin')->count();
    $totalSakit = $attendances->where('status','sakit')->count();
    $totalAlpha = $attendances->where('status','alpha')->count();
@endphp
<div class="row g-3 mb-4">
    @foreach([
        ['Hadir', $totalHadir, '#10b981', 'bi-check-circle'],
        ['Terlambat', $totalTerlambat, '#f59e0b', 'bi-clock-history'],
        ['Izin', $totalIzin, '#3b82f6', 'bi-calendar-x'],
        ['Sakit', $totalSakit, '#ef4444', 'bi-heart-pulse'],
        ['Alpha', $totalAlpha, '#6b7280', 'bi-x-circle'],
    ] as $item)
    <div class="col">
        <div class="p-3 rounded-3 text-center" style="background:{{ $item[2] }}10;">
            <i class="bi {{ $item[3] }}" style="font-size:1.3rem;color:{{ $item[2] }};"></i>
            <h4 class="fw-bold mb-0 mt-1" style="color:{{ $item[2] }};">{{ $item[1] }}</h4>
            <small class="text-muted">{{ $item[0] }}</small>
        </div>
    </div>
    @endforeach
</div>

{{-- Table --}}
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr><th>#</th><th>Tanggal</th><th>Pegawai</th><th>Masuk</th><th>Keluar</th><th>Status</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                @forelse($attendances as $i => $att)
                    <tr>
                        <td>{{ $attendances instanceof \Illuminate\Pagination\LengthAwarePaginator ? $attendances->firstItem() + $i : $i + 1 }}</td>
                        <td>{{ $att->tanggal->translatedFormat('d M Y') }}</td>
                        <td>
                            <div class="fw-semibold">{{ $att->user->nama ?? '-' }}</div>
                            <small class="text-muted">{{ $att->user->role_label ?? '-' }}</small>
                        </td>
                        <td>{{ $att->jam_masuk ?? '-' }}</td>
                        <td>{{ $att->jam_pulang ?? '-' }}</td>
                        <td><span class="badge bg-{{ $att->status_badge }} bg-opacity-10 text-{{ $att->status_badge }}">{{ ucfirst($att->status) }}</span></td>
                        <td><a href="{{ route('kepala-sekolah.kehadiran.show', $att) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-eye"></i></a></td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center py-4 text-muted">Tidak ada data kehadiran</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@if($attendances instanceof \Illuminate\Pagination\LengthAwarePaginator)
<div class="mt-3 d-flex justify-content-center">{{ $attendances->withQueryString()->links() }}</div>
@endif
@endsection
