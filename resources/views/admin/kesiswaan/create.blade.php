@extends('layouts.admin')
@section('title', 'Tambah Siswa')

@section('content')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('admin.kesiswaan.index') }}" class="btn btn-outline-secondary btn-sm me-3"><i class="bi bi-arrow-left"></i></a>
    <div>
        <h4 class="fw-bold mb-0" style="color:#1e293b;">Tambah Data Siswa</h4>
        <p class="text-muted mb-0" style="font-size:.85rem;">Masukkan data siswa baru</p>
    </div>
</div>

<div class="card">
    <div class="card-body p-4">
        <form action="{{ route('admin.kesiswaan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <h6 class="fw-semibold mb-3"><i class="bi bi-person me-1 text-primary"></i> Data Pribadi</h6>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" required>
                    @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">NIS <span class="text-danger">*</span></label>
                    <input type="text" name="nis" class="form-control @error('nis') is-invalid @enderror" value="{{ old('nis') }}" required>
                    @error('nis')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">NISN</label>
                    <input type="text" name="nisn" class="form-control" value="{{ old('nisn') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Kelas <span class="text-danger">*</span></label>
                    <input type="text" name="kelas" class="form-control @error('kelas') is-invalid @enderror" value="{{ old('kelas') }}" placeholder="X IPA 1" required>
                    @error('kelas')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                    <select name="jenis_kelamin" class="form-select" required>
                        <option value="L" {{ old('jenis_kelamin')=='L'?'selected':'' }}>Laki-laki</option>
                        <option value="P" {{ old('jenis_kelamin')=='P'?'selected':'' }}>Perempuan</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" class="form-control" value="{{ old('tempat_lahir') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Agama</label>
                    <select name="agama" class="form-select">
                        <option value="">Pilih</option>
                        <option value="Islam" {{ old('agama')=='Islam'?'selected':'' }}>Islam</option>
                        <option value="Kristen" {{ old('agama')=='Kristen'?'selected':'' }}>Kristen</option>
                        <option value="Katolik" {{ old('agama')=='Katolik'?'selected':'' }}>Katolik</option>
                        <option value="Hindu" {{ old('agama')=='Hindu'?'selected':'' }}>Hindu</option>
                        <option value="Buddha" {{ old('agama')=='Buddha'?'selected':'' }}>Buddha</option>
                        <option value="Konghucu" {{ old('agama')=='Konghucu'?'selected':'' }}>Konghucu</option>
                    </select>
                </div>
                <div class="col-md-9">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" class="form-control" rows="2">{{ old('alamat') }}</textarea>
                </div>
                <div class="col-md-3">
                    <label class="form-label">No. HP / WA</label>
                    <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp') }}" placeholder="08xxxxxxxxxx">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Nama Wali</label>
                    <input type="text" name="nama_wali" class="form-control" value="{{ old('nama_wali') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">No. HP Wali</label>
                    <input type="text" name="no_hp_wali" class="form-control" value="{{ old('no_hp_wali') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="aktif">Aktif</option>
                        <option value="mutasi">Mutasi</option>
                        <option value="lulus">Lulus</option>
                        <option value="do">Drop Out</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Foto Siswa</label>
                    <input type="file" name="photo" class="form-control" accept="image/*">
                    <small class="text-muted">Format: JPG, PNG. Maks 2MB</small>
                </div>
            </div>
            <hr class="my-4">
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.kesiswaan.index') }}" class="btn btn-outline-secondary">Batal</a>
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
