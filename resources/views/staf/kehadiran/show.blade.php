@extends('peran.staf.app')
@section('judul', 'Detail Absensi')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>#map-in, #map-out { height: 220px; border-radius: 10px; }</style>
@endpush

@section('konten')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Detail Absensi - {{ $attendance->date->translatedFormat('l, d F Y') }}</h4>
    <a href="{{ route('staf.kehadiran.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

{{-- Info Ringkas --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <div class="row g-3" style="font-size:.85rem;">
            <div class="col-md-3"><strong class="text-muted d-block">Tanggal</strong>{{ $attendance->date->translatedFormat('l, d F Y') }}</div>
            <div class="col-md-2"><strong class="text-muted d-block">Status</strong>
                @php $colors = ['hadir'=>'success','terlambat'=>'warning','izin'=>'info','sakit'=>'secondary','alpha'=>'danger','cuti'=>'primary']; @endphp
                <span class="badge bg-{{ $colors[$attendance->status] ?? 'secondary' }}">{{ ucfirst($attendance->status) }}</span>
            </div>
            <div class="col-md-2"><strong class="text-muted d-block">Jam Masuk</strong>{{ $attendance->jam_masuk ?? '-' }}</div>
            <div class="col-md-2"><strong class="text-muted d-block">Jam Pulang</strong>{{ $attendance->jam_pulang ?? '-' }}</div>
            <div class="col-md-3"><strong class="text-muted d-block">Catatan</strong>{{ $attendance->catatan ?? '-' }}</div>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- Lokasi Masuk --}}
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white"><h6 class="mb-0"><i class="bi bi-box-arrow-in-right text-success me-2"></i>Lokasi Masuk</h6></div>
            <div class="card-body">
                @if($attendance->lat_masuk && $attendance->lng_masuk)
                    <div id="map-in" class="mb-3"></div>
                    <div class="mb-2" style="font-size:.82rem;">
                        <i class="bi bi-geo-alt-fill text-danger me-1"></i>
                        <strong>Alamat:</strong> {{ $attendance->alamat_masuk ?? 'Memuat alamat...' }}
                    </div>
                    <div style="font-size:.78rem;" class="text-muted">
                        <i class="bi bi-pin-map me-1"></i>Koordinat: {{ $attendance->lat_masuk }}, {{ $attendance->lng_masuk }}
                        <a href="https://maps.google.com/?q={{ $attendance->lat_masuk }},{{ $attendance->lng_masuk }}" target="_blank" class="ms-2 text-decoration-none"><i class="bi bi-box-arrow-up-right"></i> Google Maps</a>
                    </div>
                @else
                    <div class="text-center py-5 text-muted"><i class="bi bi-geo-alt" style="font-size:2rem;"></i><br>Lokasi tidak tersedia</div>
                @endif
                @if($attendance->foto_masuk)
                    <div class="text-center mt-3">
                        <img src="{{ asset('storage/' . $attendance->foto_masuk) }}" class="img-fluid rounded" style="max-height:200px;" alt="Foto Masuk">
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Lokasi Pulang --}}
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white"><h6 class="mb-0"><i class="bi bi-box-arrow-right text-warning me-2"></i>Lokasi Pulang</h6></div>
            <div class="card-body">
                @if($attendance->lat_pulang && $attendance->lng_pulang)
                    <div id="map-out" class="mb-3"></div>
                    <div class="mb-2" style="font-size:.82rem;">
                        <i class="bi bi-geo-alt-fill text-danger me-1"></i>
                        <strong>Alamat:</strong> {{ $attendance->alamat_pulang ?? 'Memuat alamat...' }}
                    </div>
                    <div style="font-size:.78rem;" class="text-muted">
                        <i class="bi bi-pin-map me-1"></i>Koordinat: {{ $attendance->lat_pulang }}, {{ $attendance->lng_pulang }}
                        <a href="https://maps.google.com/?q={{ $attendance->lat_pulang }},{{ $attendance->lng_pulang }}" target="_blank" class="ms-2 text-decoration-none"><i class="bi bi-box-arrow-up-right"></i> Google Maps</a>
                    </div>
                @else
                    <div class="text-center py-5 text-muted"><i class="bi bi-geo-alt" style="font-size:2rem;"></i><br>Lokasi tidak tersedia</div>
                @endif
                @if($attendance->foto_pulang)
                    <div class="text-center mt-3">
                        <img src="{{ asset('storage/' . $attendance->foto_pulang) }}" class="img-fluid rounded" style="max-height:200px;" alt="Foto Pulang">
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    function initMap(id, lat, lng, label) {
        if (!document.getElementById(id)) return;
        const map = L.map(id).setView([lat, lng], 17);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19, attribution: '&copy; OSM' }).addTo(map);
        L.marker([lat, lng]).addTo(map).bindPopup(label).openPopup();
    }
    @if($attendance->lat_masuk && $attendance->lng_masuk)
        initMap('map-in', {{ $attendance->lat_masuk }}, {{ $attendance->lng_masuk }}, 'Lokasi Masuk');
    @endif
    @if($attendance->lat_pulang && $attendance->lng_pulang)
        initMap('map-out', {{ $attendance->lat_pulang }}, {{ $attendance->lng_pulang }}, 'Lokasi Pulang');
    @endif
});
</script>
@endpush
@endif
@endsection
