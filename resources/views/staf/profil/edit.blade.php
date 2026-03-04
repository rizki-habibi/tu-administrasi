@extends('staf.tata-letak.app')
@section('judul', 'Profil Saya')

@section('konten')
@php
    $thisMonth = now()->format('Y-m');
    $totalAttendance = $user->attendances()->whereRaw("DATE_FORMAT(date,'%Y-%m') = ?", [$thisMonth])->count();
    $lateCount = $user->attendances()->whereRaw("DATE_FORMAT(date,'%Y-%m') = ?", [$thisMonth])->where('status', 'late')->count();
    $totalLeave = $user->leaveRequests()->whereYear('tanggal_mulai', now()->year)->count();
    $pendingLeave = $user->leaveRequests()->where('status', 'pending')->count();
    $totalReports = $user->reports()->count();
    $joinDate = $user->created_at;
    $workDuration = $joinDate->diffForHumans(now(), ['parts' => 2, 'short' => true]);
@endphp

<!-- Profile Hero Banner -->
<div class="card border-0 shadow-sm mb-4 overflow-hidden">
    <div style="background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #a78bfa 100%); height: 140px; position: relative;">
        <div style="position:absolute;bottom:0;left:0;right:0;height:50px;background:linear-gradient(transparent,rgba(0,0,0,.15));"></div>
    </div>
    <div class="card-body position-relative" style="margin-top: -70px;">
        <div class="d-flex flex-column flex-md-row align-items-center align-items-md-end gap-3">
            <div class="flex-shrink-0 position-relative" id="profileAvatarWrapper">
                @if($user->foto)
                    <img src="{{ asset('storage/' . $user->foto) }}" class="rounded-circle border border-4 border-white shadow" width="130" height="130" style="object-fit:cover;" alt="Foto" id="profileAvatarImg">
                @else
                    <div class="rounded-circle border border-4 border-white shadow d-inline-flex align-items-center justify-content-center" style="width:130px;height:130px;font-size:2.8rem;background:linear-gradient(135deg,#6366f1,#8b5cf6);color:#fff;" id="profileAvatarInitials">
                        {{ strtoupper(substr($user->nama, 0, 2)) }}
                    </div>
                @endif
                <button type="button" class="btn btn-primary btn-sm rounded-circle position-absolute shadow" style="width:36px;height:36px;bottom:0;right:0;padding:0;" data-bs-toggle="modal" data-bs-target="#changePhotoModal" title="Ganti Foto">
                    <i class="bi bi-camera-fill"></i>
                </button>
            </div>
            <div class="text-center text-md-start flex-grow-1 pb-1">
                <h4 class="fw-bold mb-1">{{ $user->nama }}</h4>
                <p class="text-muted mb-1"><i class="bi bi-briefcase-fill me-1"></i> {{ $user->jabatan ?? 'Staff TU' }}</p>
                <div class="d-flex gap-2 justify-content-center justify-content-md-start flex-wrap">
                    <span class="badge bg-{{ $user->aktif ? 'success' : 'danger' }} bg-opacity-10 text-{{ $user->aktif ? 'success' : 'danger' }}">
                        <i class="bi bi-circle-fill me-1" style="font-size:.5rem;vertical-align:middle;"></i>{{ $user->aktif ? 'Aktif' : 'Nonaktif' }}
                    </span>
                    <span class="badge bg-primary bg-opacity-10 text-primary"><i class="bi bi-envelope-fill me-1"></i>{{ $user->email }}</span>
                    @if($user->telepon)
                    <span class="badge bg-info bg-opacity-10 text-info"><i class="bi bi-telephone-fill me-1"></i>{{ $user->telepon }}</span>
                    @endif
                </div>
            </div>
            <div class="pb-1">
                <small class="text-muted d-block text-center"><i class="bi bi-calendar3 me-1"></i>Bergabung {{ $joinDate->translatedFormat('d M Y') }}</small>
                <small class="text-muted d-block text-center">({{ $workDuration }})</small>
            </div>
        </div>
    </div>
</div>

<!-- Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center py-3">
                <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center mb-2" style="width:48px;height:48px;background:rgba(99,102,241,.1);">
                    <i class="bi bi-fingerprint text-primary" style="font-size:1.3rem;"></i>
                </div>
                <h4 class="fw-bold mb-0 text-primary">{{ $totalAttendance }}</h4>
                <small class="text-muted">Kehadiran Bulan Ini</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center py-3">
                <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center mb-2" style="width:48px;height:48px;background:rgba(245,158,11,.1);">
                    <i class="bi bi-clock-history" style="font-size:1.3rem;color:#f59e0b;"></i>
                </div>
                <h4 class="fw-bold mb-0" style="color:#f59e0b;">{{ $lateCount }}</h4>
                <small class="text-muted">Terlambat Bulan Ini</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center py-3">
                <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center mb-2" style="width:48px;height:48px;background:rgba(16,185,129,.1);">
                    <i class="bi bi-calendar2-check" style="font-size:1.3rem;color:#10b981;"></i>
                </div>
                <h4 class="fw-bold mb-0" style="color:#10b981;">{{ $totalLeave }}</h4>
                <small class="text-muted">Cuti/Izin Tahun Ini</small>
                @if($pendingLeave > 0)
                    <div><span class="badge bg-warning text-dark" style="font-size:.65rem;">{{ $pendingLeave }} menunggu</span></div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center py-3">
                <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center mb-2" style="width:48px;height:48px;background:rgba(139,92,246,.1);">
                    <i class="bi bi-journal-text" style="font-size:1.3rem;color:#8b5cf6;"></i>
                </div>
                <h4 class="fw-bold mb-0" style="color:#8b5cf6;">{{ $totalReports }}</h4>
                <small class="text-muted">Total Laporan</small>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Update Profile -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 pt-3">
                <h6 class="mb-0 fw-bold"><i class="bi bi-pencil-square text-primary me-2"></i>Edit Informasi Profil</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('staf.profil.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nama Lengkap <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-person"></i></span>
                                <input type="text" name="nama" class="form-control border-start-0 @error('nama') is-invalid @enderror" value="{{ old('nama', $user->nama) }}" required>
                            </div>
                            @error('nama') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Email</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope"></i></span>
                                <input type="email" class="form-control border-start-0 bg-light" value="{{ $user->email }}" disabled>
                            </div>
                            <small class="text-muted">Email tidak dapat diubah</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">No. Telepon</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-telephone"></i></span>
                                <input type="text" name="phone" class="form-control border-start-0 @error('telepon') is-invalid @enderror" value="{{ old('telepon', $user->telepon) }}" placeholder="08xxxxxxxxxx">
                            </div>
                            @error('telepon') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Jabatan / Bagian</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-briefcase"></i></span>
                                <input type="text" class="form-control border-start-0 bg-light" value="{{ $user->jabatan ?? '-' }}" disabled>
                            </div>
                            <small class="text-muted">Jabatan diatur oleh admin</small>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Alamat Lengkap</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 align-self-start pt-2"><i class="bi bi-geo-alt"></i></span>
                                <textarea name="address" class="form-control border-start-0 @error('alamat') is-invalid @enderror" rows="3" placeholder="Masukkan alamat lengkap...">{{ old('alamat', $user->alamat) }}</textarea>
                            </div>
                            @error('alamat') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Foto Profil</label>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#changePhotoModal">
                                    <i class="bi bi-camera me-1"></i>Ganti Foto Profil
                                </button>
                            </div>
                            <input type="file" name="photo" id="photoFileInput" class="d-none" accept="image/*">
                            <input type="hidden" name="photo_base64" id="photoBase64Input">
                            <small class="text-muted d-block mt-1">Bisa via kamera/webcam atau unggah file</small>
                            @error('foto') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-1"></i> Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Change Password -->
        <div class="card border-0 shadow-sm mt-4" id="ubah-password">
            <div class="card-header bg-white border-0 pt-3">
                <h6 class="mb-0 fw-bold"><i class="bi bi-shield-lock text-warning me-2"></i>Ubah Password</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('staf.profil.password') }}" method="POST">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Password Lama <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock"></i></span>
                                <input type="password" name="current_password" class="form-control border-start-0 @error('current_password') is-invalid @enderror" required placeholder="Masukkan password saat ini">
                            </div>
                            @error('current_password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Password Baru <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-key"></i></span>
                                <input type="password" name="password" class="form-control border-start-0 @error('password') is-invalid @enderror" required placeholder="Min. 8 karakter">
                            </div>
                            @error('password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Konfirmasi Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-key-fill"></i></span>
                                <input type="password" name="password_confirmation" class="form-control border-start-0" required placeholder="Ulangi password baru">
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="btn btn-warning px-4"><i class="bi bi-shield-check me-1"></i> Ubah Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Right Sidebar - Info Detail -->
    <div class="col-lg-4">
        <!-- Info Card -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 pt-3">
                <h6 class="mb-0 fw-bold"><i class="bi bi-info-circle text-primary me-2"></i>Informasi Akun</h6>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between py-3">
                        <span class="text-muted"><i class="bi bi-person-badge me-2"></i>NIP/ID</span>
                        <span class="fw-semibold">{{ str_pad($user->id, 6, '0', STR_PAD_LEFT) }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between py-3">
                        <span class="text-muted"><i class="bi bi-shield-check me-2"></i>Role</span>
                        <span class="badge bg-info bg-opacity-10 text-info">{{ ucfirst($user->peran) }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between py-3">
                        <span class="text-muted"><i class="bi bi-briefcase me-2"></i>Bagian</span>
                        <span class="fw-semibold">{{ $user->jabatan ?? '-' }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between py-3">
                        <span class="text-muted"><i class="bi bi-telephone me-2"></i>Telepon</span>
                        <span class="fw-semibold">{{ $user->telepon ?? '-' }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between py-3">
                        <span class="text-muted"><i class="bi bi-calendar3 me-2"></i>Terdaftar</span>
                        <span class="fw-semibold">{{ $joinDate->translatedFormat('d M Y') }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between py-3">
                        <span class="text-muted"><i class="bi bi-circle-fill me-2" style="font-size:.5rem;"></i>Status</span>
                        <span class="badge bg-{{ $user->aktif ? 'success' : 'danger' }}">{{ $user->aktif ? 'Aktif' : 'Nonaktif' }}</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="card border-0 shadow-sm mt-3">
            <div class="card-header bg-white border-0 pt-3">
                <h6 class="mb-0 fw-bold"><i class="bi bi-lightning text-warning me-2"></i>Akses Cepat</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('staf.kehadiran.index') }}" class="btn btn-outline-primary btn-sm text-start">
                        <i class="bi bi-fingerprint me-2"></i>Absensi Hari Ini
                    </a>
                    <a href="{{ route('staf.izin.create') }}" class="btn btn-outline-success btn-sm text-start">
                        <i class="bi bi-calendar-plus me-2"></i>Ajukan Izin/Cuti
                    </a>
                    <a href="{{ route('staf.laporan.create') }}" class="btn btn-outline-info btn-sm text-start">
                        <i class="bi bi-journal-plus me-2"></i>Buat Laporan
                    </a>
                    <a href="{{ route('staf.dokumen.index') }}" class="btn btn-outline-secondary btn-sm text-start">
                        <i class="bi bi-folder2-open me-2"></i>Lihat Dokumen
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="card border-0 shadow-sm mt-3">
            <div class="card-header bg-white border-0 pt-3">
                <h6 class="mb-0 fw-bold"><i class="bi bi-clock-history text-success me-2"></i>Aktivitas Terakhir</h6>
            </div>
            <div class="card-body p-0">
                @php
                    $recentAttendances = $user->attendances()->orderBy('date', 'desc')->take(5)->get();
                @endphp
                @forelse($recentAttendances as $att)
                <div class="d-flex align-items-start gap-2 px-3 py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 mt-1" style="width:28px;height:28px;background:rgba(99,102,241,.1);">
                        <i class="bi bi-clock text-primary" style="font-size:.7rem;"></i>
                    </div>
                    <div class="flex-grow-1">
                        <small class="fw-semibold d-block">{{ \Carbon\Carbon::parse($att->date)->translatedFormat('d M Y') }}</small>
                        <small class="text-muted">
                            Masuk: {{ $att->jam_masuk ? \Carbon\Carbon::parse($att->jam_masuk)->format('H:i') : '-' }}
                            | Pulang: {{ $att->jam_pulang ? \Carbon\Carbon::parse($att->jam_pulang)->format('H:i') : '-' }}
                        </small>
                    </div>
                    <span class="badge bg-{{ $att->status == 'present' ? 'success' : ($att->status == 'late' ? 'warning' : 'danger') }} bg-opacity-10 text-{{ $att->status == 'present' ? 'success' : ($att->status == 'late' ? 'warning' : 'danger') }}" style="font-size:.65rem;">
                        {{ $att->status == 'present' ? 'Hadir' : ($att->status == 'late' ? 'Terlambat' : ucfirst($att->status)) }}
                    </span>
                </div>
                @empty
                <div class="text-center py-3 text-muted">
                    <i class="bi bi-inbox" style="font-size:1.5rem;"></i>
                    <p class="small mb-0 mt-1">Belum ada data kehadiran</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Change Photo Modal -->
<div class="modal fade" id="changePhotoModal" tabindex="-1" aria-labelledby="changePhotoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px;">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold" id="changePhotoModalLabel"><i class="bi bi-camera text-primary me-2"></i>Ganti Foto Profil</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Tab navigation -->
                <ul class="nav nav-pills nav-fill mb-3" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#tab-camera" type="button" role="tab">
                            <i class="bi bi-webcam me-1"></i>Kamera
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-upload" type="button" role="tab">
                            <i class="bi bi-upload me-1"></i>Unggah File
                        </button>
                    </li>
                </ul>

                <div class="tab-content">
                    <!-- Camera Tab -->
                    <div class="tab-pane fade show active" id="tab-camera" role="tabpanel">
                        <div class="text-center">
                            <div class="position-relative d-inline-block mb-3" style="border-radius:14px;overflow:hidden;background:#1e293b;">
                                <video id="cameraVideo" width="320" height="240" autoplay playsinline style="display:block;border-radius:14px;transform:scaleX(-1);"></video>
                                <canvas id="cameraCanvas" width="320" height="240" style="display:none;"></canvas>
                                <img id="cameraPreview" style="display:none;width:320px;height:240px;object-fit:cover;border-radius:14px;" alt="Preview">
                            </div>
                            <div id="cameraNotSupported" class="d-none text-center py-4 text-muted">
                                <i class="bi bi-camera-video-off" style="font-size:2rem;"></i>
                                <p class="mt-2 small">Kamera tidak tersedia di perangkat ini.<br>Silakan gunakan tab "Unggah File".</p>
                            </div>
                            <div class="d-flex gap-2 justify-content-center">
                                <button type="button" class="btn btn-primary btn-sm" id="btnStartCamera">
                                    <i class="bi bi-camera-video me-1"></i>Mulai Kamera
                                </button>
                                <button type="button" class="btn btn-success btn-sm d-none" id="btnCapture">
                                    <i class="bi bi-camera me-1"></i>Ambil Foto
                                </button>
                                <button type="button" class="btn btn-warning btn-sm d-none" id="btnRetake">
                                    <i class="bi bi-arrow-counterclockwise me-1"></i>Ulangi
                                </button>
                                <button type="button" class="btn btn-primary btn-sm d-none" id="btnUseCapture">
                                    <i class="bi bi-check-lg me-1"></i>Gunakan Foto Ini
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Upload Tab -->
                    <div class="tab-pane fade" id="tab-upload" role="tabpanel">
                        <div class="text-center">
                            <div id="uploadPreviewWrapper" class="mb-3">
                                <div class="border-2 border-dashed rounded-3 p-4" id="dropZone" style="border:2px dashed #cbd5e1;cursor:pointer;transition:.2s;">
                                    <i class="bi bi-cloud-arrow-up text-primary" style="font-size:2.5rem;"></i>
                                    <p class="mb-1 fw-medium mt-2" style="font-size:.85rem;">Drag & drop foto di sini</p>
                                    <p class="text-muted small mb-0">atau klik untuk memilih file</p>
                                    <small class="text-muted">Format: JPG, PNG. Maks 2MB</small>
                                </div>
                                <img id="uploadPreview" style="display:none;max-width:240px;max-height:240px;object-fit:cover;border-radius:14px;" alt="Preview" class="mt-2">
                            </div>
                            <input type="file" id="modalFileInput" accept="image/jpeg,image/png,image/jpg" class="d-none">
                            <div class="d-flex gap-2 justify-content-center">
                                <button type="button" class="btn btn-outline-primary btn-sm" id="btnChooseFile">
                                    <i class="bi bi-folder2-open me-1"></i>Pilih File
                                </button>
                                <button type="button" class="btn btn-primary btn-sm d-none" id="btnUseUpload">
                                    <i class="bi bi-check-lg me-1"></i>Gunakan Foto Ini
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let cameraStream = null;
    let capturedBlob = null;
    const video = document.getElementById('cameraVideo');
    const canvas = document.getElementById('cameraCanvas');
    const preview = document.getElementById('cameraPreview');
    const btnStart = document.getElementById('btnStartCamera');
    const btnCapture = document.getElementById('btnCapture');
    const btnRetake = document.getElementById('btnRetake');
    const btnUseCapture = document.getElementById('btnUseCapture');
    const notSupported = document.getElementById('cameraNotSupported');
    const modalFileInput = document.getElementById('modalFileInput');
    const uploadPreview = document.getElementById('uploadPreview');
    const dropZone = document.getElementById('dropZone');
    const btnChooseFile = document.getElementById('btnChooseFile');
    const btnUseUpload = document.getElementById('btnUseUpload');
    const photoFileInput = document.getElementById('photoFileInput');
    const photoBase64Input = document.getElementById('photoBase64Input');

    // Camera functions
    btnStart.addEventListener('click', async function() {
        try {
            cameraStream = await navigator.mediaDevices.getUserMedia({
                video: { width: { ideal: 640 }, height: { ideal: 480 }, facingMode: 'user' },
                audio: false
            });
            video.srcObject = cameraStream;
            video.style.display = 'block';
            preview.style.display = 'none';
            btnStart.classList.add('d-none');
            btnCapture.classList.remove('d-none');
            btnRetake.classList.add('d-none');
            btnUseCapture.classList.add('d-none');
            notSupported.classList.add('d-none');
        } catch (err) {
            notSupported.classList.remove('d-none');
            video.style.display = 'none';
            btnStart.classList.add('d-none');
        }
    });

    btnCapture.addEventListener('click', function() {
        canvas.width = video.videoWidth || 320;
        canvas.height = video.videoHeight || 240;
        const ctx = canvas.getContext('2d');
        ctx.save();
        ctx.scale(-1, 1);
        ctx.drawImage(video, -canvas.width, 0, canvas.width, canvas.height);
        ctx.restore();

        canvas.toBlob(function(blob) {
            capturedBlob = blob;
            const url = URL.createObjectURL(blob);
            preview.src = url;
            preview.style.display = 'block';
            video.style.display = 'none';

            btnCapture.classList.add('d-none');
            btnRetake.classList.remove('d-none');
            btnUseCapture.classList.remove('d-none');
        }, 'image/jpeg', 0.9);
    });

    btnRetake.addEventListener('click', function() {
        preview.style.display = 'none';
        video.style.display = 'block';
        btnCapture.classList.remove('d-none');
        btnRetake.classList.add('d-none');
        btnUseCapture.classList.add('d-none');
        capturedBlob = null;
    });

    btnUseCapture.addEventListener('click', function() {
        if (!capturedBlob) return;
        // Convert blob to file and set it on the hidden file input
        const file = new File([capturedBlob], 'camera_photo.jpg', { type: 'image/jpeg' });
        const dt = new DataTransfer();
        dt.items.add(file);
        photoFileInput.files = dt.files;
        photoBase64Input.value = '';

        // Update the profile avatar preview
        updateAvatarPreview(URL.createObjectURL(capturedBlob));

        // Close modal and stop camera
        stopCamera();
        bootstrap.Modal.getInstance(document.getElementById('changePhotoModal')).hide();

        Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Foto dari kamera siap disimpan', showConfirmButton: false, timer: 2000 });
    });

    // Upload functions
    btnChooseFile.addEventListener('click', () => modalFileInput.click());
    dropZone.addEventListener('click', () => modalFileInput.click());

    dropZone.addEventListener('dragover', function(e) {
        e.preventDefault();
        dropZone.style.borderColor = '#6366f1';
        dropZone.style.background = 'rgba(99,102,241,.05)';
    });
    dropZone.addEventListener('dragleave', function(e) {
        e.preventDefault();
        dropZone.style.borderColor = '#cbd5e1';
        dropZone.style.background = '';
    });
    dropZone.addEventListener('drop', function(e) {
        e.preventDefault();
        dropZone.style.borderColor = '#cbd5e1';
        dropZone.style.background = '';
        if (e.dataTransfer.files.length > 0) {
            handleUploadFile(e.dataTransfer.files[0]);
        }
    });

    modalFileInput.addEventListener('change', function() {
        if (this.files.length > 0) handleUploadFile(this.files[0]);
    });

    function handleUploadFile(file) {
        if (!file.type.match(/^image\/(jpeg|png|jpg)$/)) {
            Swal.fire({ icon: 'error', title: 'Format tidak didukung', text: 'Gunakan file JPG atau PNG', confirmButtonColor: '#6366f1' });
            return;
        }
        if (file.size > 2 * 1024 * 1024) {
            Swal.fire({ icon: 'error', title: 'File terlalu besar', text: 'Maksimal 2MB', confirmButtonColor: '#6366f1' });
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            uploadPreview.src = e.target.result;
            uploadPreview.style.display = 'inline-block';
            dropZone.style.display = 'none';
            btnUseUpload.classList.remove('d-none');
        };
        reader.readAsDataURL(file);

        // Store the file
        const dt = new DataTransfer();
        dt.items.add(file);
        modalFileInput._selectedFile = file;
    }

    btnUseUpload.addEventListener('click', function() {
        const file = modalFileInput._selectedFile || (modalFileInput.files.length > 0 ? modalFileInput.files[0] : null);
        if (!file) return;

        const dt = new DataTransfer();
        dt.items.add(file);
        photoFileInput.files = dt.files;
        photoBase64Input.value = '';

        updateAvatarPreview(URL.createObjectURL(file));

        bootstrap.Modal.getInstance(document.getElementById('changePhotoModal')).hide();

        Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Foto profil siap disimpan', showConfirmButton: false, timer: 2000 });
    });

    function updateAvatarPreview(url) {
        const wrapper = document.getElementById('profileAvatarWrapper');
        const existingImg = document.getElementById('profileAvatarImg');
        const initials = document.getElementById('profileAvatarInitials');

        if (existingImg) {
            existingImg.src = url;
        } else if (initials) {
            const img = document.createElement('img');
            img.id = 'profileAvatarImg';
            img.src = url;
            img.className = 'rounded-circle border border-4 border-white shadow';
            img.width = 130; img.height = 130;
            img.style.objectFit = 'cover';
            initials.replaceWith(img);
        }
    }

    function stopCamera() {
        if (cameraStream) {
            cameraStream.getTracks().forEach(t => t.stop());
            cameraStream = null;
        }
        video.style.display = 'block';
        preview.style.display = 'none';
        btnStart.classList.remove('d-none');
        btnCapture.classList.add('d-none');
        btnRetake.classList.add('d-none');
        btnUseCapture.classList.add('d-none');
    }

    // Stop camera when modal closes
    document.getElementById('changePhotoModal').addEventListener('hidden.bs.modal', function() {
        stopCamera();
        // Reset upload tab
        uploadPreview.style.display = 'none';
        dropZone.style.display = '';
        btnUseUpload.classList.add('d-none');
    });
});
</script>
@endpush
