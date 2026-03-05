@extends('peran.staf.app')
@section('judul', 'Absensi')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<style>
    .attendance-card { border-radius: 16px; overflow: hidden; }
    .clock-status-icon { font-size: 3.5rem; }
    .map-container { height: 260px; border-radius: 12px; overflow: hidden; border: 2px solid #e2e8f0; }
    .location-address { font-size: .82rem; background: #f0fdf4; border-radius: 8px; padding: 10px 14px; border-left: 3px solid #10b981; }
    .distance-badge { display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; border-radius: 20px; font-size: .8rem; font-weight: 600; }
    .distance-ok { background: #d1fae5; color: #065f46; }
    .distance-far { background: #fee2e2; color: #991b1b; }
    .distance-unknown { background: #f1f5f9; color: #475569; }
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
    $statusColors = ['hadir'=>'success','terlambat'=>'warning','izin'=>'info','sakit'=>'primary','alpha'=>'danger','cuti'=>'secondary'];
    $monthStats = \App\Models\Attendance::where('pengguna_id', auth()->id())
        ->whereMonth('tanggal', now()->month)->whereYear('tanggal', now()->year)
        ->selectRaw('status, COUNT(*) as total')->groupBy('status')->pluck('total','status');
    $officeLat = $setting->lat_kantor ?? -8.1740;
    $officeLng = $setting->lng_kantor ?? 113.7169;
    $maxDist   = $setting->jarak_maksimal_meter ?? 200;
@endphp

<!-- Header Stats -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-mini">
            <div class="num text-success">{{ $monthStats['hadir'] ?? 0 }}</div>
            <div class="lbl">Hadir Bulan Ini</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-mini">
            <div class="num text-warning">{{ $monthStats['terlambat'] ?? 0 }}</div>
            <div class="lbl">Terlambat</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-mini">
            <div class="num text-info">{{ ($monthStats['izin'] ?? 0) + ($monthStats['sakit'] ?? 0) }}</div>
            <div class="lbl">Izin/Sakit</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-mini">
            <div class="num text-danger">{{ $monthStats['alpha'] ?? 0 }}</div>
            <div class="lbl">Alpha</div>
        </div>
    </div>
</div>

<!-- Today's Attendance Card -->
<div class="card border-0 shadow-sm mb-4 attendance-card">
    <div class="card-header bg-white border-0 py-3">
        <h6 class="mb-0 fw-bold"><i class="bi bi-calendar-check text-primary me-2"></i>Absensi Hari Ini � {{ now()->translatedFormat('l, d F Y') }}</h6>
    </div>
    <div class="card-body">
        @if(!$todayAttendance || !$todayAttendance->jam_masuk)
            <div class="text-center py-3">
                <div class="clock-status-icon mb-2">??</div>
                <h5 class="fw-bold">Belum Absen Masuk</h5>
                <p class="text-muted mb-1">Jam masuk: <strong>{{ $setting->jam_masuk ?? '08:00' }}</strong></p>
                @if($setting && $setting->toleransi_terlambat_menit)
                    <p class="text-muted" style="font-size:.82rem;">Toleransi: {{ $setting->toleransi_terlambat_menit }} menit</p>
                @endif
                <button type="button" class="btn btn-success btn-lg px-4 mt-2" data-bs-toggle="modal" data-bs-target="#clockInModal">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Absen Masuk Sekarang
                </button>
            </div>
        @elseif(!$todayAttendance->jam_pulang)
            <div class="row g-3 align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <div class="clock-status-icon mb-2">?</div>
                    <h5 class="fw-bold">Sudah Absen Masuk</h5>
                    <p class="mb-2 text-muted">Jam Masuk: <strong class="text-dark">{{ $todayAttendance->jam_masuk }}</strong>
                        @if($todayAttendance->status == 'terlambat') <span class="badge bg-warning ms-1">Terlambat</span> @endif
                    </p>
                    <div class="d-flex gap-2 flex-wrap justify-content-center justify-content-md-start">
                        <button type="button" class="btn btn-warning btn-lg px-4" data-bs-toggle="modal" data-bs-target="#clockOutModal">
                            <i class="bi bi-box-arrow-right me-2"></i>Absen Pulang
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-sm ubah-masuk-btn"
                                data-modal="#clockInModal" title="Ubah data absen masuk hari ini">
                            <i class="bi bi-pencil-square me-1"></i>Ubah Absen Masuk
                        </button>
                    </div>
                </div>
                @if($todayAttendance->lat_masuk)
                <div class="col-md-6">
                    <p class="fw-bold mb-1" style="font-size:.82rem;"><i class="bi bi-geo-alt text-success me-1"></i>Lokasi Absen Masuk</p>
                    <div id="miniMapIn" style="height:160px; border-radius:10px; overflow:hidden; border:1px solid #e2e8f0;"></div>
                </div>
                @endif
            </div>
        @else
            <div class="row g-3 align-items-center">
                <div class="col-md-5 text-center text-md-start">
                    <div class="clock-status-icon mb-2">??</div>
                    <h5 class="fw-bold">Absensi Lengkap!</h5>
                    <div class="d-flex gap-3 mt-2 justify-content-center justify-content-md-start flex-wrap">
                        <div><small class="text-muted d-block">Masuk</small><strong>{{ $todayAttendance->jam_masuk }}</strong></div>
                        <div><small class="text-muted d-block">Pulang</small><strong>{{ $todayAttendance->jam_pulang }}</strong></div>
                        <div><small class="text-muted d-block">Status</small>
                            <span class="badge bg-{{ $statusColors[$todayAttendance->status] ?? 'secondary' }}">{{ ucfirst($todayAttendance->status) }}</span>
                        </div>
                    </div>
                    <div class="d-flex gap-2 mt-3 flex-wrap justify-content-center justify-content-md-start">
                        <button type="button" class="btn btn-outline-success btn-sm ubah-masuk-btn" data-modal="#clockInModal">
                            <i class="bi bi-pencil-square me-1"></i>Ubah Absen Masuk
                        </button>
                        <button type="button" class="btn btn-outline-warning btn-sm ubah-masuk-btn" data-modal="#clockOutModal">
                            <i class="bi bi-pencil-square me-1"></i>Ubah Absen Pulang
                        </button>
                    </div>
                </div>
                @if($todayAttendance->lat_masuk || $todayAttendance->lat_pulang)
                <div class="col-md-7">
                    <p class="fw-bold mb-1" style="font-size:.82rem;"><i class="bi bi-map text-primary me-1"></i>Peta Kehadiran Hari Ini</p>
                    <div id="miniMapBoth" style="height:180px; border-radius:10px; overflow:hidden; border:1px solid #e2e8f0;"></div>
                </div>
                @endif
            </div>
        @endif
    </div>
</div>

<!-- --- CLOCK-IN MODAL --- -->
<div class="modal fade" id="clockInModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius:16px;overflow:hidden;">
            <div class="modal-header border-0 py-3" style="background:linear-gradient(135deg,#10b981,#059669);">
                <div>
                    <h5 class="modal-title text-white fw-bold mb-0"><i class="bi bi-box-arrow-in-right me-2"></i>Absen Masuk</h5>
                    <small class="text-white opacity-75" id="clockInTime">{{ now()->format('H:i:s') }}</small>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="clockInForm" action="{{ route('staf.kehadiran.masuk') }}" method="POST">
                @csrf
                <input type="hidden" name="photo" id="photoIn">
                <input type="hidden" name="latitude" id="latIn">
                <input type="hidden" name="longitude" id="lngIn">
                <input type="hidden" name="alamat" id="addrIn">
                <div class="modal-body p-0">
                    <div class="row g-0">
                        <div class="col-lg-6 p-3 border-end">
                            <p class="fw-bold mb-2" style="font-size:.85rem;"><i class="bi bi-map-fill text-success me-1"></i>Lokasi GPS Anda (Live)</p>
                            <div id="mapIn" class="map-container mb-2"></div>
                            <div id="locInfoIn" class="text-muted mb-2" style="font-size:.8rem;">
                                <div class="d-flex align-items-center gap-2"><div class="spinner-border spinner-border-sm"></div> Mendeteksi GPS...</div>
                            </div>
                            <div id="addressIn" class="location-address d-none mb-2"></div>
                            <div id="distanceIn"></div>
                        </div>
                        <div class="col-lg-6 p-3">
                            <p class="fw-bold mb-2" style="font-size:.85rem;"><i class="bi bi-camera-fill text-primary me-1"></i>Foto Selfie</p>
                            <div class="camera-wrapper mb-2" id="cameraWrapIn">
                                <video id="videoIn" width="100%" autoplay playsinline style="display:block;min-height:200px;object-fit:cover;"></video>
                                <div class="capture-overlay">
                                    <button type="button" class="shutter-btn captureBtn" data-target="in" title="Ambil Foto">
                                        <i class="bi bi-camera-fill" style="font-size:1.4rem;color:#1e293b;"></i>
                                    </button>
                                </div>
                            </div>
                            <canvas id="canvasIn" width="480" height="360" style="display:none;"></canvas>
                            <img id="previewIn" class="rounded d-none mb-2" style="width:100%;max-height:260px;object-fit:cover;">
                            <button type="button" class="btn btn-sm btn-outline-secondary retakeBtn d-none w-100" data-target="in">
                                <i class="bi bi-arrow-counterclockwise me-1"></i>Ulangi Foto
                            </button>
                            <div class="alert alert-light border-0 bg-light mt-2 mb-0 p-2" style="font-size:.75rem;border-radius:8px;">
                                <i class="bi bi-info-circle-fill text-primary me-1"></i>Pastikan wajah terlihat jelas. Foto & GPS diperlukan.
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success px-4" id="submitIn" disabled>
                        <i class="bi bi-check-circle me-1"></i>Konfirmasi Absen Masuk
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- --- CLOCK-OUT MODAL --- -->
<div class="modal fade" id="clockOutModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius:16px;overflow:hidden;">
            <div class="modal-header border-0 py-3" style="background:linear-gradient(135deg,#f59e0b,#d97706);">
                <div>
                    <h5 class="modal-title text-white fw-bold mb-0"><i class="bi bi-box-arrow-right me-2"></i>Absen Pulang</h5>
                    <small class="text-white opacity-75" id="clockOutTime">{{ now()->format('H:i:s') }}</small>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="clockOutForm" action="{{ route('staf.kehadiran.pulang') }}" method="POST">
                @csrf
                <input type="hidden" name="photo" id="photoOut">
                <input type="hidden" name="latitude" id="latOut">
                <input type="hidden" name="longitude" id="lngOut">
                <input type="hidden" name="alamat" id="addrOut">
                <div class="modal-body p-0">
                    <div class="row g-0">
                        <div class="col-lg-6 p-3 border-end">
                            <p class="fw-bold mb-2" style="font-size:.85rem;"><i class="bi bi-map-fill text-warning me-1"></i>Lokasi GPS Anda (Live)</p>
                            <div id="mapOut" class="map-container mb-2"></div>
                            <div id="locInfoOut" class="text-muted mb-2" style="font-size:.8rem;">
                                <div class="d-flex align-items-center gap-2"><div class="spinner-border spinner-border-sm"></div> Mendeteksi GPS...</div>
                            </div>
                            <div id="addressOut" class="location-address d-none mb-2"></div>
                            <div id="distanceOut"></div>
                        </div>
                        <div class="col-lg-6 p-3">
                            <p class="fw-bold mb-2" style="font-size:.85rem;"><i class="bi bi-camera-fill text-primary me-1"></i>Foto Selfie</p>
                            <div class="camera-wrapper mb-2" id="cameraWrapOut">
                                <video id="videoOut" width="100%" autoplay playsinline style="display:block;min-height:200px;object-fit:cover;"></video>
                                <div class="capture-overlay">
                                    <button type="button" class="shutter-btn captureBtn" data-target="out" title="Ambil Foto">
                                        <i class="bi bi-camera-fill" style="font-size:1.4rem;color:#1e293b;"></i>
                                    </button>
                                </div>
                            </div>
                            <canvas id="canvasOut" width="480" height="360" style="display:none;"></canvas>
                            <img id="previewOut" class="rounded d-none mb-2" style="width:100%;max-height:260px;object-fit:cover;">
                            <button type="button" class="btn btn-sm btn-outline-secondary retakeBtn d-none w-100" data-target="out">
                                <i class="bi bi-arrow-counterclockwise me-1"></i>Ulangi Foto
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning px-4" id="submitOut" disabled>
                        <i class="bi bi-check-circle me-1"></i>Konfirmasi Absen Pulang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Filter & History -->
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-6 col-md-3">
                <label class="form-label mb-1" style="font-size:.78rem;">Bulan</label>
                <select name="month" class="form-select form-select-sm">
                    <option value="">Semua Bulan</option>
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ request('month', now()->month) == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label mb-1" style="font-size:.78rem;">Tahun</label>
                <input type="number" name="year" class="form-control form-control-sm" value="{{ request('year', now()->year) }}">
            </div>
            <div class="col-6 col-md-3">
                <label class="form-label mb-1" style="font-size:.78rem;">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">Semua Status</option>
                    @foreach(['hadir','terlambat','izin','sakit','alpha','cuti'] as $s)
                        <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-2">
                <button class="btn btn-primary btn-sm w-100"><i class="bi bi-search me-1"></i>Saring</button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Tanggal</th><th>Masuk</th><th>Pulang</th><th>Status</th><th>Lokasi</th><th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attendances as $att)
                <tr>
                    <td>
                        <strong>{{ $att->tanggal->format('d/m/Y') }}</strong><br>
                        <small class="text-muted">{{ $att->tanggal->translatedFormat('l') }}</small>
                    </td>
                    <td>{{ $att->jam_masuk ?? '-' }}</td>
                    <td>{{ $att->jam_pulang ?? '-' }}</td>
                    <td><span class="badge bg-{{ $statusColors[$att->status] ?? 'secondary' }}">{{ ucfirst($att->status) }}</span></td>
                    <td>
                        @if($att->lat_masuk)
                            <a href="https://maps.google.com/?q={{ $att->lat_masuk }},{{ $att->lng_masuk }}" target="_blank" class="badge bg-light text-primary border text-decoration-none">
                                <i class="bi bi-geo-alt-fill me-1"></i>Lihat Map
                            </a>
                        @else <span class="text-muted" style="font-size:.75rem;">-</span> @endif
                    </td>
                    <td>
                        <div class="d-flex gap-1 flex-wrap">
                            <a href="{{ route('staf.kehadiran.show', $att) }}" class="btn btn-sm btn-outline-primary" title="Detail"><i class="bi bi-eye"></i></a>
                            @if(!$att->tanggal->isToday())
                            <button type="button" class="btn btn-sm btn-outline-secondary edit-note-btn"
                                    data-id="{{ $att->id }}"
                                    data-status="{{ $att->status }}"
                                    data-note="{{ $att->catatan ?? '' }}"
                                    data-date="{{ $att->tanggal->format('d/m/Y') }}"
                                    title="Edit keterangan">
                                <i class="bi bi-journal-text"></i>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-5">
                    <i class="bi bi-calendar-x" style="font-size:2rem;opacity:.3;display:block;margin-bottom:6px;"></i>Belum ada data absensi
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $attendances->links() }}</div>

<!-- --- KETERANGAN MODAL (past days only) --- -->
<div class="modal fade" id="keteranganModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:460px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius:16px;">
            <div class="modal-header border-0" style="background:linear-gradient(135deg,#6366f1,#4f46e5);">
                <div>
                    <h6 class="modal-title text-white fw-bold mb-0"><i class="bi bi-journal-text me-2"></i>Ubah Keterangan Kehadiran</h6>
                    <small class="text-white opacity-75" id="keteranganDate"></small>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="keteranganForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body p-4">
                    <div class="alert alert-info d-flex gap-2 align-items-start mb-3 p-2" style="font-size:.8rem;border-radius:10px;">
                        <i class="bi bi-info-circle-fill mt-1"></i>
                        <span>Hari yang sudah lewat <strong>tidak dapat diubah jam absennya</strong>. Anda hanya bisa menambahkan keterangan atau mengubah status.</span>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Status Kehadiran</label>
                        <select name="status" id="keteranganStatus" class="form-select" required>
                            <option value="hadir">Hadir</option>
                            <option value="terlambat">Terlambat</option>
                            <option value="izin">Izin</option>
                            <option value="sakit">Sakit</option>
                            <option value="cuti">Cuti</option>
                            <option value="alpha">Alpha</option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-semibold">Keterangan / Catatan <span class="text-muted fw-normal">(opsional)</span></label>
                        <textarea name="catatan" id="keteranganNote" class="form-control" rows="3" maxlength="500"
                                  placeholder="Contoh: Izin karena acara keluarga, surat terlampir"></textarea>
                        <small class="text-muted"><span id="noteCharCount">0</span>/500 karakter</small>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light" style="border-radius:0 0 16px 16px;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-check-circle me-1"></i>Simpan Keterangan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
const OFFICE_LAT = {{ $officeLat }};
const OFFICE_LNG = {{ $officeLng }};
const MAX_DIST   = {{ $maxDist }};
const HAS_OFFICE = {{ ($setting && $setting->lat_kantor) ? 'true' : 'false' }};

const iconUser   = L.divIcon({ html:'<div style="background:#10b981;width:14px;height:14px;border-radius:50%;border:3px solid #fff;box-shadow:0 1px 6px rgba(0,0,0,.5);"></div>', className:'', iconSize:[14,14], iconAnchor:[7,7] });
const iconOutM   = L.divIcon({ html:'<div style="background:#f59e0b;width:14px;height:14px;border-radius:50%;border:3px solid #fff;box-shadow:0 1px 6px rgba(0,0,0,.5);"></div>', className:'', iconSize:[14,14], iconAnchor:[7,7] });
const iconOffice = L.divIcon({ html:'<div style="background:#ef4444;width:22px;height:22px;border-radius:6px;border:3px solid #fff;box-shadow:0 2px 8px rgba(0,0,0,.4);display:flex;align-items:center;justify-content:center;font-size:12px;">??</div>', className:'', iconSize:[22,22], iconAnchor:[11,11] });

let maps = {}, streams = {};

function initMap(id, lat, lng, userIcon) {
    if (maps[id]) { maps[id].remove(); }
    const m = L.map(id, { zoomControl:true, attributionControl:false }).setView([lat, lng], 17);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom:19 }).addTo(m);
    if (HAS_OFFICE) {
        L.marker([OFFICE_LAT, OFFICE_LNG], { icon: iconOffice }).addTo(m).bindPopup('<b>?? SMA Negeri 2 Jember</b>');
        L.polyline([[OFFICE_LAT,OFFICE_LNG],[lat,lng]], { color:'#6366f1', weight:2, dashArray:'6,4', opacity:.7 }).addTo(m);
        // Fit bounds to show both markers
        m.fitBounds([[OFFICE_LAT,OFFICE_LNG],[lat,lng]], { padding:[30,30] });
    }
    L.marker([lat, lng], { icon: userIcon }).addTo(m).bindPopup('<b>?? Posisi Anda</b>').openPopup();
    maps[id] = m;
    return m;
}

function haversine(lat1, lng1, lat2, lng2) {
    const R=6371000, dL=(lat2-lat1)*Math.PI/180, dG=(lng2-lng1)*Math.PI/180;
    const a=Math.sin(dL/2)**2+Math.cos(lat1*Math.PI/180)*Math.cos(lat2*Math.PI/180)*Math.sin(dG/2)**2;
    return Math.round(R*2*Math.atan2(Math.sqrt(a),Math.sqrt(1-a)));
}

async function reverseGeocode(lat, lng) {
    try {
        const r = await fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json`);
        const d = await r.json();
        return d.display_name || null;
    } catch { return null; }
}

function getLocation(type) {
    const S = type === 'in' ? 'In' : 'Out';
    const locEl  = document.getElementById('locInfo' + S);
    const addrEl = document.getElementById('alamat' + S);
    const distEl = document.getElementById('distance' + S);
    const latEl  = document.getElementById('lat' + S);
    const lngEl  = document.getElementById('lng' + S);
    const mapId  = 'map' + S;
    const icon   = type === 'in' ? iconUser : iconOutM;

    if (!navigator.geolocation) {
        locEl.innerHTML = '<i class="bi bi-exclamation-triangle text-danger me-1"></i>GPS tidak didukung browser ini.';
        return;
    }
    navigator.geolocation.getCurrentPosition(async pos => {
        const lat = pos.coords.latitude, lng = pos.coords.longitude, acc = Math.round(pos.coords.accuracy);
        latEl.value = lat; lngEl.value = lng;
        locEl.innerHTML = `<i class="bi bi-geo-alt-fill text-success me-1"></i><strong>${lat.toFixed(5)}, ${lng.toFixed(5)}</strong> <span class="text-muted">(akurasi �${acc}m)</span>`;

        initMap(mapId, lat, lng, icon);

        const addr = await reverseGeocode(lat, lng);
        if (addr) {
            addrEl.innerHTML = `<i class="bi bi-house-fill text-success me-1"></i>${addr}`;
            addrEl.classList.remove('d-none');
            // Save address to hidden field
            const addrInput = document.getElementById('addr' + S);
            if (addrInput) addrInput.value = addr;
        }

        if (HAS_OFFICE) {
            const dist = haversine(lat, lng, OFFICE_LAT, OFFICE_LNG);
            const ok = dist <= MAX_DIST;
            distEl.innerHTML = `<span class="distance-badge ${ok?'distance-ok':'distance-far'}">
                <i class="bi bi-${ok?'check-circle-fill':'exclamation-circle-fill'}"></i>
                Jarak ke sekolah: <strong>${dist}m</strong> ${ok?'? Dalam radius':'? Melebihi batas '+MAX_DIST+'m'}
            </span>`;
        } else {
            distEl.innerHTML = `<span class="distance-badge distance-unknown"><i class="bi bi-info-circle"></i> Koordinat sekolah belum diatur admin</span>`;
        }
        checkReady(type);
    }, err => {
        const msg = { 1:'Izin GPS ditolak. Aktifkan izin lokasi.', 2:'GPS tidak tersedia.', 3:'Timeout GPS. Coba lagi.' };
        locEl.innerHTML = `<i class="bi bi-exclamation-triangle-fill text-danger me-1"></i>${msg[err.code]||'Gagal ambil GPS.'}`;
    }, { enableHighAccuracy:true, timeout:15000, maximumAge:0 });
}

function startCamera(type) {
    const S = type === 'in' ? 'In' : 'Out';
    const video = document.getElementById('video' + S);
    navigator.mediaDevices?.getUserMedia({ video:{ facingMode:'user', width:{ideal:640}, height:{ideal:480} } })
        .then(s => { streams[type]=s; video.srcObject=s; })
        .catch(() => {
            const wrap = document.getElementById('cameraWrap' + S.charAt(0).toUpperCase() + S.slice(1));
            if (wrap) wrap.innerHTML = '<div class="d-flex align-items-center justify-content-center h-100 bg-dark text-white p-3 text-center" style="min-height:200px;border-radius:12px;"><div><i class="bi bi-camera-slash" style="font-size:2rem;"></i><br>Kamera tidak tersedia</div></div>';
        });
}

function stopCamera(type) {
    if (streams[type]) { streams[type].getTracks().forEach(t=>t.stop()); streams[type]=null; }
}

document.getElementById('clockInModal').addEventListener('shown.bs.modal', () => { startCamera('in'); getLocation('in'); });
document.getElementById('clockInModal').addEventListener('hidden.bs.modal', () => { stopCamera('in'); if(maps['mapIn']){maps['mapIn'].remove();delete maps['mapIn'];} });
document.getElementById('clockOutModal').addEventListener('shown.bs.modal', () => { startCamera('out'); getLocation('out'); });
document.getElementById('clockOutModal').addEventListener('hidden.bs.modal', () => { stopCamera('out'); if(maps['mapOut']){maps['mapOut'].remove();delete maps['mapOut'];} });

document.querySelectorAll('.captureBtn').forEach(btn => {
    btn.addEventListener('click', function() {
        const type = this.dataset.target, S = type==='in'?'In':'Out';
        const video  = document.getElementById('video'+S);
        const canvas = document.getElementById('canvas'+S);
        const preview= document.getElementById('preview'+S);
        const photoI = document.getElementById('foto'+S);
        const wrap   = document.getElementById('cameraWrap'+S.charAt(0).toUpperCase()+S.slice(1));
        canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
        photoI.value = canvas.toDataURL('image/jpeg', 0.85);
        preview.src  = photoI.value;
        preview.classList.remove('d-none');
        if (wrap) wrap.style.display = 'none';
        document.querySelector(`.retakeBtn[data-target="${type}"]`)?.classList.remove('d-none');
        checkReady(type);
    });
});

document.querySelectorAll('.retakeBtn').forEach(btn => {
    btn.addEventListener('click', function() {
        const type=this.dataset.target, S=type==='in'?'In':'Out';
        document.getElementById('foto'+S).value='';
        document.getElementById('preview'+S).classList.add('d-none');
        const wrap=document.getElementById('cameraWrap'+S.charAt(0).toUpperCase()+S.slice(1));
        if(wrap) wrap.style.display='';
        this.classList.add('d-none');
        startCamera(type);
        checkReady(type);
    });
});

function checkReady(type) {
    const S=type==='in'?'In':'Out';
    const ok = document.getElementById('foto'+S)?.value && document.getElementById('lat'+S)?.value;
    const btn= document.getElementById('submit'+S);
    if(btn) btn.disabled = !ok;
}

// Live clock in modals
setInterval(() => {
    const t=new Date().toLocaleTimeString('id-ID');
    ['clockInTime','clockOutTime'].forEach(id=>{const e=document.getElementById(id);if(e)e.textContent=t;});
}, 1000);

// -- SweetAlert confirmation for re-doing today's attendance --
document.querySelectorAll('.ubah-masuk-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const targetModal = this.dataset.modal;
        const isOut = targetModal === '#clockOutModal';
        Swal.fire({
            title: 'Ubah Data Kehadiran?',
            html: `Data absen <strong>${isOut ? 'pulang':'masuk'}</strong> hari ini akan <strong>ditimpa</strong> dengan data baru.<br><small class="text-muted">Foto & lokasi lama akan dihapus.</small>`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: isOut ? '#f59e0b' : '#10b981',
            cancelButtonColor: '#6b7280',
            confirmButtonText: '<i class="bi bi-pencil-square"></i> Ya, Ubah',
            cancelButtonText: 'Batal',
            customClass: { popup: 'swal-rounded' }
        }).then(result => {
            if (result.isConfirmed) {
                const modal = new bootstrap.Modal(document.querySelector(targetModal));
                modal.show();
            }
        });
    });
});

// -- Edit Keterangan (past days) --
document.querySelectorAll('.edit-note-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const id     = this.dataset.id;
        const status = this.dataset.status;
        const note   = this.dataset.note;
        const date   = this.dataset.date;

        document.getElementById('keteranganDate').textContent = date;
        document.getElementById('keteranganStatus').value     = status;
        document.getElementById('keteranganNote').value       = note;
        document.getElementById('noteCharCount').textContent  = note.length;
        document.getElementById('keteranganForm').action      = `/staff/attendance/${id}/note`;

        new bootstrap.Modal(document.getElementById('keteranganModal')).show();
    });
});

document.getElementById('keteranganNote')?.addEventListener('input', function() {
    document.getElementById('noteCharCount').textContent = this.value.length;
});

// Mini maps (today status)
@if($todayAttendance && $todayAttendance->lat_masuk && !$todayAttendance->jam_pulang)
document.addEventListener('DOMContentLoaded', () => {
    const m=L.map('miniMapIn',{zoomControl:false,attributionControl:false}).setView([{{ $todayAttendance->lat_masuk }},{{ $todayAttendance->lng_masuk }}],16);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(m);
    L.marker([{{ $todayAttendance->lat_masuk }},{{ $todayAttendance->lng_masuk }}],{icon:iconUser}).addTo(m).bindPopup('? Masuk {{ $todayAttendance->jam_masuk }}').openPopup();
    if(HAS_OFFICE) L.marker([OFFICE_LAT,OFFICE_LNG],{icon:iconOffice}).addTo(m).bindPopup('?? Sekolah');
});
@endif
@if($todayAttendance && $todayAttendance->jam_pulang && ($todayAttendance->lat_masuk || $todayAttendance->lat_pulang))
document.addEventListener('DOMContentLoaded', () => {
    const latC={{ $todayAttendance->lat_masuk ?? $todayAttendance->lat_pulang }};
    const lngC={{ $todayAttendance->lng_masuk ?? $todayAttendance->lng_pulang }};
    const m=L.map('miniMapBoth',{zoomControl:false,attributionControl:false}).setView([latC,lngC],15);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(m);
    @if($todayAttendance->lat_masuk)
    L.marker([{{ $todayAttendance->lat_masuk }},{{ $todayAttendance->lng_masuk }}],{icon:iconUser}).addTo(m).bindPopup('? Masuk: {{ $todayAttendance->jam_masuk }}');
    @endif
    @if($todayAttendance->lat_pulang)
    L.marker([{{ $todayAttendance->lat_pulang }},{{ $todayAttendance->lng_pulang }}],{icon:iconOutM}).addTo(m).bindPopup('?? Pulang: {{ $todayAttendance->jam_pulang }}');
    @endif
    if(HAS_OFFICE) L.marker([OFFICE_LAT,OFFICE_LNG],{icon:iconOffice}).addTo(m).bindPopup('?? Sekolah');
});
@endif
</script>
@endpush
@endsection
