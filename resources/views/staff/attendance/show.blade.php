@extends('layouts.staff')
@section('title', 'Detail Absensi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Detail Absensi - {{ $attendance->date->format('d F Y') }}</h4>
    <a href="{{ route('staff.attendance.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><h6 class="mb-0"><i class="bi bi-box-arrow-in-right text-success"></i> Absen Masuk</h6></div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr><td class="fw-bold" width="140">Jam Masuk</td><td>{{ $attendance->clock_in ?? '-' }}</td></tr>
                    <tr><td class="fw-bold">Status</td><td>
                        @php $colors = ['hadir'=>'success','terlambat'=>'warning','izin'=>'info','sakit'=>'secondary','alpha'=>'danger','cuti'=>'primary']; @endphp
                        <span class="badge bg-{{ $colors[$attendance->status] ?? 'secondary' }}">{{ ucfirst($attendance->status) }}</span>
                    </td></tr>
                    <tr><td class="fw-bold">Lokasi</td><td>
                        @if($attendance->latitude_in)
                            <a href="https://maps.google.com/?q={{ $attendance->latitude_in }},{{ $attendance->longitude_in }}" target="_blank" class="text-decoration-none">
                                <i class="bi bi-geo-alt"></i> {{ $attendance->latitude_in }}, {{ $attendance->longitude_in }}
                            </a>
                        @else - @endif
                    </td></tr>
                </table>
                @if($attendance->photo_in)
                    <div class="text-center mt-3">
                        <img src="{{ asset('storage/' . $attendance->photo_in) }}" class="img-fluid rounded" style="max-height:250px;" alt="Foto Masuk">
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><h6 class="mb-0"><i class="bi bi-box-arrow-right text-warning"></i> Absen Pulang</h6></div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr><td class="fw-bold" width="140">Jam Pulang</td><td>{{ $attendance->clock_out ?? '-' }}</td></tr>
                    <tr><td class="fw-bold">Lokasi</td><td>
                        @if($attendance->latitude_out)
                            <a href="https://maps.google.com/?q={{ $attendance->latitude_out }},{{ $attendance->longitude_out }}" target="_blank" class="text-decoration-none">
                                <i class="bi bi-geo-alt"></i> {{ $attendance->latitude_out }}, {{ $attendance->longitude_out }}
                            </a>
                        @else - @endif
                    </td></tr>
                </table>
                @if($attendance->photo_out)
                    <div class="text-center mt-3">
                        <img src="{{ asset('storage/' . $attendance->photo_out) }}" class="img-fluid rounded" style="max-height:250px;" alt="Foto Pulang">
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@if($attendance->note)
<div class="card border-0 shadow-sm mt-4">
    <div class="card-body">
        <h6><i class="bi bi-chat-left-text"></i> Catatan</h6>
        <p class="mb-0">{{ $attendance->note }}</p>
    </div>
</div>
@endif
@endsection
