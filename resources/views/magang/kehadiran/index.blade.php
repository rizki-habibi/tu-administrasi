@extends('peran.magang.app')
@section('judul', 'Absensi')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<style>
    .attendance-card { border-radius: 16px; overflow: hidden; }
    .clock-status-icon { font-size: 3.5rem; }
    .map-container { height: 260px; border-radius: 12px; overflow: hidden; border: 2px solid #e2e8f0; }
    .location-address { font-size: .82rem; background: #ecfeff; border-radius: 8px; padding: 10px 14px; border-left: 3px solid #0891b2; }
    .camera-wrapper { position: relative; border-radius: 12px; overflow: hidden; background: #000; min-height: 200px; }
    .capture-overlay { position: absolute; bottom: 10px; left: 50%; transform: translateX(-50%); }
    .shutter-btn { width: 56px; height: 56px; border-radius: 50%; background: #fff; border: 4px solid rgba(255,255,255,.6); cursor: pointer; transition: .2s; display:flex;align-items:center;justify-content:center; }
    .shutter-btn:hover { transform: scale(1.1); }
    .stat-mini { background: #fff; border-radius: 12px; padding: 14px 18px; border: 1px solid #e2e8f0; text-align: center; }
    .stat-mini .num { font-size: 1.4rem; font-weight: 700; color: #1e293b; }
    .stat-mini .lbl { font-size: .72rem; color: #64748b; }
</style>
@endpush

@section('konten')
@php
    $statusColors = ['hadir'=>'success','terlambat'=>'warning','izin'=>'info','sakit'=>'primary','alpha'=>'danger'];
    $monthStats = \App\Models\Kehadiran::where('pengguna_id', auth()->id())
        ->whereMonth('tanggal', now()->month)->whereYear('tanggal', now()->year)
        ->selectRaw('status, COUNT(*) as total')->groupBy('status')->pluck('total','status');
    $officeLat = $setting->lat_kantor ?? -8.1740;
    $officeLng = $setting->lng_kantor ?? 113.7169;
    $maxDist = $setting->jarak_maksimal_meter ?? 200;
@endphp

<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-mini"><div class="num text-success">{{ $monthStats['hadir'] ?? 0 }}</div><div class="lbl">Hadir</div></div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-mini"><div class="num text-warning">{{ $monthStats['terlambat'] ?? 0 }}</div><div class="lbl">Terlambat</div></div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-mini"><div class="num text-info">{{ ($monthStats['izin'] ?? 0) + ($monthStats['sakit'] ?? 0) }}</div><div class="lbl">Izin/Sakit</div></div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-mini"><div class="num text-danger">{{ $monthStats['alpha'] ?? 0 }}</div><div class="lbl">Alpha</div></div>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4 attendance-card">
    <div class="card-header bg-white border-0 py-3">
        <h6 class="mb-0 fw-bold"><i class="bi bi-calendar-check me-2" style="color:#0891b2;"></i>Absensi Hari Ini &mdash; {{ now()->translatedFormat('l, d F Y') }}</h6>
    </div>
    <div class="card-body">
        @if(!$todayAttendance || !$todayAttendance->jam_masuk)
            <div class="text-center py-3">
                <div class="clock-status-icon mb-2">🕐</div>
                <h5 class="fw-bold">Belum Absen Masuk</h5>
                <p class="text-muted mb-1">Jam masuk: <strong>{{ $setting->jam_masuk ?? '08:00' }}</strong></p>
                <button type="button" class="btn btn-primary btn-lg px-4 mt-2" data-bs-toggle="modal" data-bs-target="#clockInModal">
                    <i class="bi bi-fingerprint me-2"></i>Absen Masuk
                </button>
            </div>
        @elseif(!$todayAttendance->jam_pulang)
            <div class="text-center py-3">
                <div class="clock-status-icon mb-2">✅</div>
                <h5 class="fw-bold text-success">Sudah Absen Masuk</h5>
                <p class="text-muted">Jam Masuk: <strong>{{ $todayAttendance->jam_masuk }}</strong>
                    &bull; Status: <span class="badge bg-{{ $statusColors[$todayAttendance->status] ?? 'secondary' }}">{{ ucfirst($todayAttendance->status) }}</span>
                </p>
                <button type="button" class="btn btn-outline-danger btn-lg px-4" data-bs-toggle="modal" data-bs-target="#clockOutModal">
                    <i class="bi bi-box-arrow-right me-2"></i>Absen Pulang
                </button>
            </div>
        @else
            <div class="text-center py-3">
                <div class="clock-status-icon mb-2">🎉</div>
                <h5 class="fw-bold text-success">Absensi Lengkap</h5>
                <p class="text-muted">Masuk: <strong>{{ $todayAttendance->jam_masuk }}</strong> &bull; Pulang: <strong>{{ $todayAttendance->jam_pulang }}</strong></p>
            </div>
        @endif
    </div>
</div>

{{-- Clock In Modal --}}
<div class="modal fade" id="clockInModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title fw-bold"><i class="bi bi-fingerprint me-2"></i>Absen Masuk</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Foto Selfie</label>
                        <div class="camera-wrapper" id="cameraWrapperIn">
                            <video id="videoIn" autoplay playsinline style="width:100%;border-radius:12px;"></video>
                            <canvas id="canvasIn" style="display:none;"></canvas>
                            <div class="capture-overlay">
                                <button type="button" class="shutter-btn" id="captureIn"><i class="bi bi-camera-fill fs-4 text-dark"></i></button>
                            </div>
                        </div>
                        <img id="previewIn" style="display:none;width:100%;border-radius:12px;margin-top:8px;">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Lokasi Anda</label>
                        <div class="map-container" id="mapIn"></div>
                        <div class="location-address mt-2" id="addressIn"><i class="bi bi-geo-alt me-1"></i>Mendeteksi lokasi...</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="submitClockIn" disabled><i class="bi bi-check-circle me-1"></i>Konfirmasi Masuk</button>
            </div>
        </div>
    </div>
</div>

{{-- Clock Out Modal --}}
<div class="modal fade" id="clockOutModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title fw-bold"><i class="bi bi-box-arrow-right me-2"></i>Absen Pulang</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Foto Selfie</label>
                        <div class="camera-wrapper" id="cameraWrapperOut">
                            <video id="videoOut" autoplay playsinline style="width:100%;border-radius:12px;"></video>
                            <canvas id="canvasOut" style="display:none;"></canvas>
                            <div class="capture-overlay">
                                <button type="button" class="shutter-btn" id="captureOut"><i class="bi bi-camera-fill fs-4 text-dark"></i></button>
                            </div>
                        </div>
                        <img id="previewOut" style="display:none;width:100%;border-radius:12px;margin-top:8px;">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Lokasi Anda</label>
                        <div class="map-container" id="mapOut"></div>
                        <div class="location-address mt-2" id="addressOut"><i class="bi bi-geo-alt me-1"></i>Mendeteksi lokasi...</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="submitClockOut" disabled><i class="bi bi-check-circle me-1"></i>Konfirmasi Pulang</button>
            </div>
        </div>
    </div>
</div>

{{-- History --}}
@if(request('view') == 'history')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 py-3">
        <h6 class="mb-0 fw-bold"><i class="bi bi-clock-history me-2" style="color:#0891b2;"></i>Riwayat Kehadiran</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th>Tanggal</th><th>Masuk</th><th>Pulang</th><th>Status</th></tr>
                </thead>
                <tbody>
                    @forelse($attendances as $att)
                    <tr>
                        <td>{{ $att->tanggal->format('d/m/Y') }}</td>
                        <td>{{ $att->jam_masuk ?? '-' }}</td>
                        <td>{{ $att->jam_pulang ?? '-' }}</td>
                        <td><span class="badge bg-{{ $statusColors[$att->status] ?? 'secondary' }}">{{ ucfirst($att->status) }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center text-muted py-4">Belum ada riwayat kehadiran.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-3 py-2">{{ $attendances->links() }}</div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
let photoDataIn = null, photoDataOut = null;
let userLat = null, userLng = null, userAddress = '';

function initCamera(videoId, canvasId, captureId, previewId, type) {
    const video = document.getElementById(videoId);
    const canvas = document.getElementById(canvasId);
    const capture = document.getElementById(captureId);
    const preview = document.getElementById(previewId);

    const modal = (type === 'in') ? document.getElementById('clockInModal') : document.getElementById('clockOutModal');
    let stream = null;

    modal.addEventListener('shown.bs.modal', () => {
        navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' } }).then(s => {
            stream = s; video.srcObject = s;
        });
        getLocation(type);
    });
    modal.addEventListener('hidden.bs.modal', () => {
        if (stream) stream.getTracks().forEach(t => t.stop());
        video.srcObject = null;
        preview.style.display = 'none';
        video.style.display = '';
    });

    capture.addEventListener('click', () => {
        canvas.width = video.videoWidth; canvas.height = video.videoHeight;
        canvas.getContext('2d').drawImage(video, 0, 0);
        const data = canvas.toDataURL('image/jpeg', 0.8);
        if (type === 'in') photoDataIn = data; else photoDataOut = data;
        preview.src = data; preview.style.display = 'block'; video.style.display = 'none';
        checkReady(type);
    });
}

function getLocation(type) {
    if (!navigator.geolocation) return;
    navigator.geolocation.getCurrentPosition(pos => {
        userLat = pos.coords.latitude; userLng = pos.coords.longitude;
        const mapId = type === 'in' ? 'mapIn' : 'mapOut';
        const addrId = type === 'in' ? 'addressIn' : 'addressOut';
        const map = L.map(mapId).setView([userLat, userLng], 16);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(map);
        L.marker([userLat, userLng]).addTo(map);

        fetch(`https://nominatim.openstreetmap.org/reverse?lat=${userLat}&lon=${userLng}&format=json`)
            .then(r => r.json()).then(d => {
                userAddress = d.display_name || '';
                document.getElementById(addrId).innerHTML = '<i class="bi bi-geo-alt me-1"></i>' + (userAddress || 'Lokasi terdeteksi');
            });
        checkReady(type);
    });
}

function checkReady(type) {
    if (type === 'in') document.getElementById('submitClockIn').disabled = !(photoDataIn && userLat);
    else document.getElementById('submitClockOut').disabled = !(photoDataOut && userLat);
}

function submitAttendance(url, photoData) {
    fetch(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
        body: JSON.stringify({ foto: photoData, latitude: userLat, longitude: userLng, alamat: userAddress })
    }).then(r => { if (r.redirected) window.location.href = r.url; else location.reload(); });
}

document.addEventListener('DOMContentLoaded', () => {
    initCamera('videoIn', 'canvasIn', 'captureIn', 'previewIn', 'in');
    initCamera('videoOut', 'canvasOut', 'captureOut', 'previewOut', 'out');

    document.getElementById('submitClockIn')?.addEventListener('click', () => submitAttendance("{{ route('magang.kehadiran.masuk') }}", photoDataIn));
    document.getElementById('submitClockOut')?.addEventListener('click', () => submitAttendance("{{ route('magang.kehadiran.pulang') }}", photoDataOut));
});
</script>
@endpush
