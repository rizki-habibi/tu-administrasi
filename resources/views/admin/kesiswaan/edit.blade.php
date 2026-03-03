@extends('layouts.admin')
@section('title', 'Edit Data Siswa')

@section('content')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('admin.kesiswaan.index') }}" class="btn btn-outline-secondary btn-sm me-3"><i class="bi bi-arrow-left"></i></a>
    <div>
        <h4 class="fw-bold mb-0" style="color:#1e293b;">Edit Data Siswa</h4>
        <p class="text-muted mb-0" style="font-size:.85rem;">{{ $kesiswaan->name }} - {{ $kesiswaan->nis }}</p>
    </div>
</div>

<div class="card">
    <div class="card-body p-4">
        <form action="{{ route('admin.kesiswaan.update', $kesiswaan) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $kesiswaan->name) }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
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
                    <input type="text" name="class" class="form-control" value="{{ old('class', $kesiswaan->class) }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tahun Ajaran</label>
                    <input type="text" name="academic_year" class="form-control" value="{{ old('academic_year', $kesiswaan->academic_year) }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Jenis Kelamin</label>
                    <select name="gender" class="form-select" required>
                        <option value="L" {{ old('gender',$kesiswaan->gender)=='L'?'selected':'' }}>Laki-laki</option>
                        <option value="P" {{ old('gender',$kesiswaan->gender)=='P'?'selected':'' }}>Perempuan</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tempat Lahir</label>
                    <input type="text" name="place_of_birth" class="form-control" value="{{ old('place_of_birth', $kesiswaan->place_of_birth) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tanggal Lahir</label>
                    <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth', $kesiswaan->date_of_birth) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Agama</label>
                    <select name="religion" class="form-select">
                        <option value="">Pilih</option>
                        @foreach(['Islam','Kristen','Katolik','Hindu','Buddha','Konghucu'] as $ag)
                        <option value="{{ $ag }}" {{ old('religion',$kesiswaan->religion)==$ag?'selected':'' }}>{{ $ag }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-9">
                    <label class="form-label">Alamat</label>
                    <textarea name="address" class="form-control" rows="2">{{ old('address', $kesiswaan->address) }}</textarea>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Nama Orang Tua/Wali</label>
                    <input type="text" name="parent_name" class="form-control" value="{{ old('parent_name', $kesiswaan->parent_name) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">No. HP Orang Tua/Wali</label>
                    <input type="text" name="parent_phone" class="form-control" value="{{ old('parent_phone', $kesiswaan->parent_phone) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="aktif" {{ old('status',$kesiswaan->status)=='aktif'?'selected':'' }}>Aktif</option>
                        <option value="mutasi_masuk" {{ old('status',$kesiswaan->status)=='mutasi_masuk'?'selected':'' }}>Mutasi Masuk</option>
                        <option value="mutasi_keluar" {{ old('status',$kesiswaan->status)=='mutasi_keluar'?'selected':'' }}>Mutasi Keluar</option>
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
