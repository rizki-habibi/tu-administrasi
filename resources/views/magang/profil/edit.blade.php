@extends('peran.magang.app')
@section('judul', 'Profil Saya')

@section('konten')
<div class="mb-4">
    <h5 class="fw-bold mb-1">Profil Saya</h5>
    <p class="text-muted mb-0" style="font-size:.82rem;">Perbarui informasi profil dan password</p>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="fw-bold mb-0"><i class="bi bi-person me-2 text-primary"></i>Informasi Profil</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('magang.profil.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama', $user->nama) }}">
                            @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" class="form-control" value="{{ $user->email }}" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">No. Telepon</label>
                            <input type="text" name="telepon" class="form-control @error('telepon') is-invalid @enderror" value="{{ old('telepon', $user->telepon) }}">
                            @error('telepon')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Instansi Asal</label>
                            <input type="text" name="instansi_asal" class="form-control @error('instansi_asal') is-invalid @enderror" value="{{ old('instansi_asal', $user->instansi_asal) }}">
                            @error('instansi_asal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Alamat</label>
                            <textarea name="alamat" rows="2" class="form-control @error('alamat') is-invalid @enderror">{{ old('alamat', $user->alamat) }}</textarea>
                            @error('alamat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Foto Profil</label>
                            <input type="file" name="foto" class="form-control @error('foto') is-invalid @enderror" accept=".jpg,.jpeg,.png">
                            @error('foto')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <hr>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle me-1"></i>Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body text-center py-4">
                <div class="mb-3">
                    @if($user->foto)
                    <img src="{{ asset('storage/' . $user->foto) }}" class="rounded-circle" width="80" height="80" style="object-fit:cover;">
                    @else
                    <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center" style="width:80px;height:80px;background:#ecfeff;color:#0891b2;font-size:2rem;font-weight:700;">
                        {{ strtoupper(substr($user->nama,0,1)) }}
                    </div>
                    @endif
                </div>
                <h6 class="fw-bold mb-0">{{ $user->nama }}</h6>
                <small class="text-muted">Staff Magang</small>
                @if($user->instansi_asal)
                <p class="mt-1 mb-0" style="font-size:.82rem;"><i class="bi bi-building me-1"></i>{{ $user->instansi_asal }}</p>
                @endif
                @if($user->pembimbing_lapangan)
                <p class="mt-1 mb-0" style="font-size:.82rem;"><i class="bi bi-person-badge me-1"></i>Pembimbing: {{ $user->pembimbing_lapangan }}</p>
                @endif
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="fw-bold mb-0"><i class="bi bi-shield-lock me-2 text-primary"></i>Ubah Password</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('magang.profil.password') }}" method="POST">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Password Lama</label>
                        <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror">
                        @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Password Baru</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-key me-1"></i>Ubah Password</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
