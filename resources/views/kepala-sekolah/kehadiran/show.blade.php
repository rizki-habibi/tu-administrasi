@extends('peran.kepala-sekolah.app')
@section('judul', 'Detail Kehadiran')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>#map-in, #map-out { height: 260px; border-radius: 10px; }</style>
@endpush

@section('konten')
<div class="mb-4">
    <a href="{{ url()->previous() }}" class="text-decoration-none text-warning" style="font-size:.85rem;"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
</div>

<div class="card mb-4">
    <div class="card-header bg-white border-0 py-3">
        <h6 class="fw-bold mb-0" style="font-size:.9rem;"><i class="bi bi-calendar-check text-warning me-2"></i>Detail Kehadiran</h6>
    </div>
    <div class="card-body">
        <div class="row g-3 mb-3" style="font-size:.85rem;">
            <div class="col-md-3"><strong class="text-muted d-block">Pegawai</strong>{{ $attendance->user->nama ?? '-' }}</div>
            <div class="col-md-3"><strong class="text-muted d-block">Peran</strong>{{ $attendance->user->role_label ?? '-' }}</div>
            <div class="col-md-3"><strong class="text-muted d-block">Tanggal</strong>{{ $attendance->date->translatedFormat('l, d F Y') }}</div>
            <div class="col-md-3"><strong class="text-muted d-block">Status</strong><span class="badge bg-{{ $attendance->status_badge }} bg-opacity-10 text-{{ $attendance->status_badge }}">{{ ucfirst($attendance->status) }}</span></div>
        </div>
        <div class="row g-3" style="font-size:.85rem;">
            <div class="col-md-3"><strong class="text-muted d-block">Jam Masuk</strong>{{ $attendance->jam_masuk ?? '-' }}</div>
            <div class="col-md-3"><strong class="text-muted d-block">Jam Keluar</strong>{{ $attendance->jam_pulang ?? '-' }}</div>
            <div class="col-md-6"><strong class="text-muted d-block">Catatan</strong>{{ $attendance->catatan ?? '-' }}</div>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- Clock In Location --}}
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="fw-bold mb-0" style="font-size:.9rem;"><i class="bi bi-box-arrow-in-right text-success me-2"></i>Lokasi Masuk</h6>
            </div>
            <div class="card-body">
                @if($attendance->lat_masuk && $attendance->lng_masuk)
                    <div id="map-in" class="mb-3"></div>
                @else
                    <div class="text-center py-5 text-muted" style="font-size:.85rem;"><i class="bi bi-geo-alt" style="font-size:2rem;"></i><br>Lokasi tidak tersedia</div>
                @endif
                <div style="font-size:.82rem;">
                    <strong class="text-muted">Alamat:</strong><br>{{ $attendance->alamat_masuk ?? '-' }}
                </div>
                @if($attendance->foto_masuk)
                    <div class="mt-2"><img src="{{ asset('storage/' . $attendance->foto_masuk) }}" class="rounded-3 img-fluid" style="max-height:180px;" alt="Foto Masuk"></div>
                @endif
            </div>
        </div>
    </div>

    {{-- Clock Out Location --}}
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="fw-bold mb-0" style="font-size:.9rem;"><i class="bi bi-box-arrow-right text-danger me-2"></i>Lokasi Keluar</h6>
            </div>
            <div class="card-body">
                @if($attendance->lat_pulang && $attendance->lng_pulang)
                    <div id="map-out" class="mb-3"></div>
                @else
                    <div class="text-center py-5 text-muted" style="font-size:.85rem;"><i class="bi bi-geo-alt" style="font-size:2rem;"></i><br>Lokasi tidak tersedia</div>
                @endif
                <div style="font-size:.82rem;">
                    <strong class="text-muted">Alamat:</strong><br>{{ $attendance->alamat_pulang ?? '-' }}
                </div>
                @if($attendance->foto_pulang)
                    <div class="mt-2"><img src="{{ asset('storage/' . $attendance->foto_pulang) }}" class="rounded-3 img-fluid" style="max-height:180px;" alt="Foto Keluar"></div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    @if($attendance->lat_masuk && $attendance->lng_masuk)
    (function () {
        var map = L.map('map-in').setView([{{ $attendance->lat_masuk }}, {{ $attendance->lng_masuk }}], 16);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19, attribution: '&copy; OSM' }).addTo(map);
        L.marker([{{ $attendance->lat_masuk }}, {{ $attendance->lng_masuk }}]).addTo(map).bindPopup('<strong>Lokasi Masuk</strong><br>{{ $attendance->jam_masuk }}').openPopup();
    })();
    @endif

    @if($attendance->lat_pulang && $attendance->lng_pulang)
    (function () {
        var map = L.map('map-out').setView([{{ $attendance->lat_pulang }}, {{ $attendance->lng_pulang }}], 16);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19, attribution: '&copy; OSM' }).addTo(map);
        L.marker([{{ $attendance->lat_pulang }}, {{ $attendance->lng_pulang }}]).addTo(map).bindPopup('<strong>Lokasi Keluar</strong><br>{{ $attendance->jam_pulang }}').openPopup();
    })();
    @endif
});
</script>
@endpush
