@extends('layouts.staff')
@section('title', 'Profil Saya')

@section('content')
@php
    $thisMonth = now()->format('Y-m');
    $totalAttendance = $user->attendances()->whereRaw("DATE_FORMAT(date,'%Y-%m') = ?", [$thisMonth])->count();
    $lateCount = $user->attendances()->whereRaw("DATE_FORMAT(date,'%Y-%m') = ?", [$thisMonth])->where('status', 'late')->count();
    $totalLeave = $user->leaveRequests()->whereYear('start_date', now()->year)->count();
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
            <div class="flex-shrink-0">
                @if($user->photo)
                    <img src="{{ asset('storage/' . $user->photo) }}" class="rounded-circle border border-4 border-white shadow" width="130" height="130" style="object-fit:cover;" alt="Foto">
                @else
                    <div class="rounded-circle border border-4 border-white shadow d-inline-flex align-items-center justify-content-center" style="width:130px;height:130px;font-size:2.8rem;background:linear-gradient(135deg,#6366f1,#8b5cf6);color:#fff;">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                @endif
            </div>
            <div class="text-center text-md-start flex-grow-1 pb-1">
                <h4 class="fw-bold mb-1">{{ $user->name }}</h4>
                <p class="text-muted mb-1"><i class="bi bi-briefcase-fill me-1"></i> {{ $user->position ?? 'Staff TU' }}</p>
                <div class="d-flex gap-2 justify-content-center justify-content-md-start flex-wrap">
                    <span class="badge bg-{{ $user->is_active ? 'success' : 'danger' }} bg-opacity-10 text-{{ $user->is_active ? 'success' : 'danger' }}">
                        <i class="bi bi-circle-fill me-1" style="font-size:.5rem;vertical-align:middle;"></i>{{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                    <span class="badge bg-primary bg-opacity-10 text-primary"><i class="bi bi-envelope-fill me-1"></i>{{ $user->email }}</span>
                    @if($user->phone)
                    <span class="badge bg-info bg-opacity-10 text-info"><i class="bi bi-telephone-fill me-1"></i>{{ $user->phone }}</span>
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
                <form action="{{ route('staff.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nama Lengkap <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-person"></i></span>
                                <input type="text" name="name" class="form-control border-start-0 @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                            </div>
                            @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
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
                                <input type="text" name="phone" class="form-control border-start-0 @error('phone') is-invalid @enderror" value="{{ old('phone', $user->phone) }}" placeholder="08xxxxxxxxxx">
                            </div>
                            @error('phone') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Jabatan / Bagian</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-briefcase"></i></span>
                                <input type="text" class="form-control border-start-0 bg-light" value="{{ $user->position ?? '-' }}" disabled>
                            </div>
                            <small class="text-muted">Jabatan diatur oleh admin</small>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Alamat Lengkap</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 align-self-start pt-2"><i class="bi bi-geo-alt"></i></span>
                                <textarea name="address" class="form-control border-start-0 @error('address') is-invalid @enderror" rows="3" placeholder="Masukkan alamat lengkap...">{{ old('address', $user->address) }}</textarea>
                            </div>
                            @error('address') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Foto Profil</label>
                            <input type="file" name="photo" class="form-control @error('photo') is-invalid @enderror" accept="image/*">
                            <small class="text-muted">Format: JPG, PNG. Maks 2MB</small>
                            @error('photo') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
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
                <form action="{{ route('staff.profile.password') }}" method="POST">
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
                        <span class="badge bg-info bg-opacity-10 text-info">{{ ucfirst($user->role) }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between py-3">
                        <span class="text-muted"><i class="bi bi-briefcase me-2"></i>Bagian</span>
                        <span class="fw-semibold">{{ $user->position ?? '-' }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between py-3">
                        <span class="text-muted"><i class="bi bi-telephone me-2"></i>Telepon</span>
                        <span class="fw-semibold">{{ $user->phone ?? '-' }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between py-3">
                        <span class="text-muted"><i class="bi bi-calendar3 me-2"></i>Terdaftar</span>
                        <span class="fw-semibold">{{ $joinDate->translatedFormat('d M Y') }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between py-3">
                        <span class="text-muted"><i class="bi bi-circle-fill me-2" style="font-size:.5rem;"></i>Status</span>
                        <span class="badge bg-{{ $user->is_active ? 'success' : 'danger' }}">{{ $user->is_active ? 'Aktif' : 'Nonaktif' }}</span>
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
                    <a href="{{ route('staff.attendance.index') }}" class="btn btn-outline-primary btn-sm text-start">
                        <i class="bi bi-fingerprint me-2"></i>Absensi Hari Ini
                    </a>
                    <a href="{{ route('staff.leave.create') }}" class="btn btn-outline-success btn-sm text-start">
                        <i class="bi bi-calendar-plus me-2"></i>Ajukan Izin/Cuti
                    </a>
                    <a href="{{ route('staff.report.create') }}" class="btn btn-outline-info btn-sm text-start">
                        <i class="bi bi-journal-plus me-2"></i>Buat Laporan
                    </a>
                    <a href="{{ route('staff.document.index') }}" class="btn btn-outline-secondary btn-sm text-start">
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
                            Masuk: {{ $att->clock_in ? \Carbon\Carbon::parse($att->clock_in)->format('H:i') : '-' }}
                            | Pulang: {{ $att->clock_out ? \Carbon\Carbon::parse($att->clock_out)->format('H:i') : '-' }}
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
@endsection
