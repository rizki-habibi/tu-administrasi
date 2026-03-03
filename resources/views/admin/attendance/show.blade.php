@extends('layouts.admin')
@section('title', 'Detail Kehadiran')

@section('content')
<div class="row g-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><h6 class="mb-0">Info Kehadiran</h6></div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr><th width="150">Nama</th><td>{{ $attendance->user->name ?? '-' }}</td></tr>
                    <tr><th>Tanggal</th><td>{{ $attendance->date->format('d M Y') }}</td></tr>
                    <tr><th>Jam Masuk</th><td>{{ $attendance->clock_in ?? '-' }}</td></tr>
                    <tr><th>Jam Pulang</th><td>{{ $attendance->clock_out ?? '-' }}</td></tr>
                    <tr><th>Status</th><td>
                        @php $colors = ['hadir'=>'success','terlambat'=>'warning','izin'=>'info','sakit'=>'primary','alpha'=>'danger']; @endphp
                        <span class="badge bg-{{ $colors[$attendance->status] ?? 'secondary' }}">{{ ucfirst($attendance->status) }}</span>
                    </td></tr>
                    <tr><th>Catatan</th><td>{{ $attendance->note ?? '-' }}</td></tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white"><h6 class="mb-0">Foto Kehadiran</h6></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-6 text-center">
                        <p class="fw-bold mb-2">Masuk</p>
                        @if($attendance->photo_in)
                            <img src="{{ asset('storage/' . $attendance->photo_in) }}" class="img-fluid rounded" style="max-height:200px;">
                        @else
                            <div class="bg-light rounded p-4 text-muted">Tidak ada foto</div>
                        @endif
                    </div>
                    <div class="col-6 text-center">
                        <p class="fw-bold mb-2">Pulang</p>
                        @if($attendance->photo_out)
                            <img src="{{ asset('storage/' . $attendance->photo_out) }}" class="img-fluid rounded" style="max-height:200px;">
                        @else
                            <div class="bg-light rounded p-4 text-muted">Tidak ada foto</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><h6 class="mb-0">Lokasi</h6></div>
            <div class="card-body">
                <p><strong>Masuk:</strong> {{ $attendance->latitude_in && $attendance->longitude_in ? $attendance->latitude_in . ', ' . $attendance->longitude_in : 'Tidak tersedia' }}</p>
                <p><strong>Pulang:</strong> {{ $attendance->latitude_out && $attendance->longitude_out ? $attendance->latitude_out . ', ' . $attendance->longitude_out : 'Tidak tersedia' }}</p>
                @if($attendance->latitude_in && $attendance->longitude_in)
                    <a href="https://www.google.com/maps?q={{ $attendance->latitude_in }},{{ $attendance->longitude_in }}" target="_blank" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-geo-alt"></i> Lihat di Google Maps
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
<div class="mt-3">
    <a href="{{ route('admin.attendance.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>
@endsection
