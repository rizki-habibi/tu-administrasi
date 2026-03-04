@extends('peran.admin.app')
@section('judul', 'Pengaturan Absensi')

@section('konten')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
        <h5 class="fw-bold mb-1">Pengaturan Absensi</h5>
        <p class="text-muted mb-0" style="font-size:.82rem;">Konfigurasi jam kerja, toleransi keterlambatan, dan lokasi absensi</p>
    </div>
</div>

<form method="POST" action="{{ route('admin.kehadiran.pengaturan.update') }}">
    @csrf
    @method('PUT')

    <div class="row g-4">
        <!-- Jam Kerja -->
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold" style="font-size:.9rem;"><i class="bi bi-clock text-primary me-2"></i>Jam Kerja</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-medium" style="font-size:.85rem;">Jam Masuk</label>
                        <input type="time" name="clock_in_time" class="form-control @error('jam_masuk') is-invalid @enderror"
                               value="{{ old('jam_masuk', $setting->jam_masuk ? \Carbon\Carbon::parse($setting->jam_masuk)->format('H:i') : '08:00') }}">
                        @error('jam_masuk')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <small class="text-muted">Waktu mulai jam kerja harian</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium" style="font-size:.85rem;">Jam Pulang</label>
                        <input type="time" name="clock_out_time" class="form-control @error('jam_pulang') is-invalid @enderror"
                               value="{{ old('jam_pulang', $setting->jam_pulang ? \Carbon\Carbon::parse($setting->jam_pulang)->format('H:i') : '16:00') }}">
                        @error('jam_pulang')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <small class="text-muted">Waktu akhir jam kerja harian</small>
                    </div>
                    <div>
                        <label class="form-label fw-medium" style="font-size:.85rem;">Toleransi Keterlambatan (menit)</label>
                        <input type="number" name="late_tolerance_minutes" class="form-control @error('toleransi_terlambat_menit') is-invalid @enderror" min="0" max="120"
                               value="{{ old('toleransi_terlambat_menit', $setting->toleransi_terlambat_menit ?? 15) }}">
                        @error('toleransi_terlambat_menit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <small class="text-muted">Menit toleransi sebelum dianggap terlambat</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lokasi Absensi -->
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold" style="font-size:.9rem;"><i class="bi bi-geo-alt text-danger me-2"></i>Lokasi Kantor</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-medium" style="font-size:.85rem;">Latitude</label>
                        <input type="text" name="office_latitude" class="form-control @error('lat_kantor') is-invalid @enderror"
                               value="{{ old('lat_kantor', $setting->lat_kantor ?? '') }}" placeholder="-8.1659...">
                        @error('lat_kantor')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium" style="font-size:.85rem;">Longitude</label>
                        <input type="text" name="office_longitude" class="form-control @error('lng_kantor') is-invalid @enderror"
                               value="{{ old('lng_kantor', $setting->lng_kantor ?? '') }}" placeholder="113.7066...">
                        @error('lng_kantor')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label class="form-label fw-medium" style="font-size:.85rem;">Jarak Maksimal Absensi (meter)</label>
                        <input type="number" name="max_distance_meters" class="form-control @error('jarak_maksimal_meter') is-invalid @enderror" min="0"
                               value="{{ old('jarak_maksimal_meter', $setting->jarak_maksimal_meter ?? 200) }}">
                        @error('jarak_maksimal_meter')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <small class="text-muted">Radius maksimum staff dapat melakukan absensi dari lokasi kantor</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info Sekolah -->
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold" style="font-size:.9rem;"><i class="bi bi-building text-info me-2"></i>Informasi Sekolah</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-medium" style="font-size:.85rem;">Nama Sekolah</label>
                            <input type="text" class="form-control" value="SMA Negeri 2 Jember" disabled>
                            <small class="text-muted">Pengaturan ini ditetapkan oleh sistem</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium" style="font-size:.85rem;">Alamat</label>
                            <input type="text" class="form-control" value="Jl. Jawa No.16, Jember, Jawa Timur" disabled>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Keterangan Status -->
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold" style="font-size:.9rem;"><i class="bi bi-info-circle text-warning me-2"></i>Keterangan Status Absensi</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3" style="font-size:.85rem;">
                        <div class="col-md-4 col-6">
                            <span class="badge bg-success me-1">&nbsp;</span> <strong>Hadir</strong>
                            <div class="text-muted ms-4">Absen tepat waktu</div>
                        </div>
                        <div class="col-md-4 col-6">
                            <span class="badge bg-warning me-1">&nbsp;</span> <strong>Terlambat</strong>
                            <div class="text-muted ms-4">Melewati toleransi keterlambatan</div>
                        </div>
                        <div class="col-md-4 col-6">
                            <span class="badge bg-info me-1">&nbsp;</span> <strong>Izin</strong>
                            <div class="text-muted ms-4">Izin yang disetujui admin</div>
                        </div>
                        <div class="col-md-4 col-6">
                            <span class="badge bg-primary me-1">&nbsp;</span> <strong>Sakit</strong>
                            <div class="text-muted ms-4">Sakit dengan pengajuan</div>
                        </div>
                        <div class="col-md-4 col-6">
                            <span class="badge bg-secondary me-1">&nbsp;</span> <strong>Cuti</strong>
                            <div class="text-muted ms-4">Cuti yang disetujui admin</div>
                        </div>
                        <div class="col-md-4 col-6">
                            <span class="badge bg-danger me-1">&nbsp;</span> <strong>Alpha</strong>
                            <div class="text-muted ms-4">Tidak absen tanpa keterangan</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Submit -->
    <div class="d-flex justify-content-end mt-4 gap-2">
        <a href="{{ route('admin.kehadiran.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Simpan Pengaturan</button>
    </div>
</form>
@endsection
