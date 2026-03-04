@extends('kepala-sekolah.tata-letak.app')
@section('judul', 'Edit Profil')

@section('konten')
<div class="mb-4">
    <h5 class="fw-bold mb-1">Edit Profil</h5>
    <p class="text-muted mb-0" style="font-size:.85rem;">Perbarui informasi akun Anda</p>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert" style="font-size:.85rem;">
    <i class="bi bi-check-circle me-1"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show" role="alert" style="font-size:.85rem;">
    <ul class="mb-0">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="row g-4">
    {{-- Profile Information --}}
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="fw-bold mb-0" style="font-size:.9rem;"><i class="bi bi-person-circle text-warning me-2"></i>Informasi Profil</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('kepala-sekolah.profil.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')

                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="rounded-3 d-flex align-items-center justify-content-center text-white fw-bold overflow-hidden" style="width:72px;height:72px;font-size:1.5rem;background:linear-gradient(135deg,#d97706,#ea580c);flex-shrink:0;">
                            @if($user->foto)
                                <img src="{{ asset('storage/' . $user->foto) }}" style="width:72px;height:72px;object-fit:cover;" alt="">
                            @else
                                {{ strtoupper(substr($user->nama, 0, 2)) }}
                            @endif
                        </div>
                        <div>
                            <label class="form-label mb-1">Foto Profil</label>
                            <input type="file" name="photo" class="form-control form-control-sm" accept="image/*">
                            <small class="text-muted">Maks 2MB. Format: JPG, PNG</small>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="nama" class="form-control" value="{{ old('nama', $user->nama) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nomor Telepon</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('telepon', $user->telepon) }}" placeholder="08xxxxxxxxxx">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">NIP</label>
                            <input type="text" class="form-control" value="{{ $user->nip ?? '-' }}" disabled>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Alamat</label>
                            <textarea name="address" class="form-control" rows="3" placeholder="Masukkan alamat lengkap...">{{ old('alamat', $user->alamat) }}</textarea>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Change Password --}}
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="fw-bold mb-0" style="font-size:.9rem;"><i class="bi bi-shield-lock text-warning me-2"></i>Ubah Password</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('kepala-sekolah.profil.password') }}" method="POST">
                    @csrf @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Password Lama <span class="text-danger">*</span></label>
                        <input type="password" name="current_password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password Baru <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control" required>
                        <small class="text-muted">Minimal 8 karakter</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Konfirmasi Password Baru <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-outline-warning w-100"><i class="bi bi-key me-1"></i>Ubah Password</button>
                </form>
            </div>
        </div>

        {{-- Account Info --}}
        <div class="card mt-3">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="fw-bold mb-0" style="font-size:.9rem;">Info Akun</h6>
            </div>
            <div class="card-body" style="font-size:.85rem;">
                <div class="mb-2"><strong class="text-muted d-block">Role</strong><span class="badge bg-warning bg-opacity-10 text-warning">{{ $user->role_label }}</span></div>
                <div class="mb-2"><strong class="text-muted d-block">Status</strong>
                    @if($user->aktif)
                        <span class="badge bg-success bg-opacity-10 text-success">Aktif</span>
                    @else
                        <span class="badge bg-danger bg-opacity-10 text-danger">Non-aktif</span>
                    @endif
                </div>
                <div><strong class="text-muted d-block">Bergabung</strong>{{ $user->created_at->translatedFormat('d F Y') }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
