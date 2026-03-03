@extends('layouts.admin')
@section('title', 'Edit Data Siswa')

@section('content')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('admin.kesiswaan.index') }}" class="btn btn-outline-secondary btn-sm me-3"><i class="bi bi-arrow-left"></i></a>
    <div>
        <h4 class="fw-bold mb-0" style="color:#1e293b;">Edit Data Siswa</h4>
        <p class="text-muted mb-0" style="font-size:.85rem;">{{ $kesiswaan->nama }} - {{ $kesiswaan->nis }}</p>
    </div>
</div>

<div class="card">
    <div class="card-body p-4">
        <form action="{{ route('admin.kesiswaan.update', $kesiswaan) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama', $kesiswaan->nama) }}" required>
                    @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">NIS <span class="text-danger">*</span></label>
                    <input type="text" name="nis" class="form-control @error('nis') is-invalid @enderror" value="{{ old('nis', $kesiswaan->nis) }}" required>
                    @error('nis')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">NISN</label>
                    <input type="text" name="nisn" class="form-control" value="{{ old('nisn', $kesiswaan->nisn) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Kelas</label>
                    <input type="text" name="kelas" class="form-control" value="{{ old('kelas', $kesiswaan->kelas) }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-select" required>
                        <option value="L" {{ old('jenis_kelamin',$kesiswaan->jenis_kelamin)=='L'?'selected':'' }}>Laki-laki</option>
                        <option value="P" {{ old('jenis_kelamin',$kesiswaan->jenis_kelamin)=='P'?'selected':'' }}>Perempuan</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" class="form-control" value="{{ old('tempat_lahir', $kesiswaan->tempat_lahir) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir', $kesiswaan->tanggal_lahir) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Agama</label>
                    <select name="agama" class="form-select">
                        <option value="">Pilih</option>
                        @foreach(['Islam','Kristen','Katolik','Hindu','Buddha','Konghucu'] as $ag)
                        <option value="{{ $ag }}" {{ old('agama',$kesiswaan->agama)==$ag?'selected':'' }}>{{ $ag }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-9">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" class="form-control" rows="2">{{ old('alamat', $kesiswaan->alamat) }}</textarea>
                </div>
                <div class="col-md-3">
                    <label class="form-label">No. HP</label>
                    <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp', $kesiswaan->no_hp) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Nama Wali</label>
                    <input type="text" name="nama_wali" class="form-control" value="{{ old('nama_wali', $kesiswaan->nama_wali) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">No. HP Wali</label>
                    <input type="text" name="no_hp_wali" class="form-control" value="{{ old('no_hp_wali', $kesiswaan->no_hp_wali) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="aktif" {{ old('status',$kesiswaan->status)=='aktif'?'selected':'' }}>Aktif</option>
                        <option value="mutasi" {{ old('status',$kesiswaan->status)=='mutasi'?'selected':'' }}>Mutasi</option>
                        <option value="lulus" {{ old('status',$kesiswaan->status)=='lulus'?'selected':'' }}>Lulus</option>
                        <option value="do" {{ old('status',$kesiswaan->status)=='do'?'selected':'' }}>Drop Out</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Ganti Foto (opsional)</label>
                    <input type="file" name="photo" class="form-control" accept="image/*">
                </div>
            </div>
            <hr class="my-4">
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.kesiswaan.index') }}" class="btn btn-outline-secondary">Batal</a>
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection
