@extends('peran.staf.app')
@section('judul', 'Ubah Data Siswa')

@section('konten')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-pencil"></i> Ubah Data Siswa</h4>
    <a href="{{ route('staf.kesiswaan.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="{{ route('staf.kesiswaan-kelola.update', $siswa) }}" method="POST">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-bold">NIS <span class="text-danger">*</span></label>
                    <input type="text" name="nis" class="form-control @error('nis') is-invalid @enderror" value="{{ old('nis', $siswa->nis) }}" required>
                    @error('nis') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">NISN</label>
                    <input type="text" name="nisn" class="form-control @error('nisn') is-invalid @enderror" value="{{ old('nisn', $siswa->nisn) }}">
                    @error('nisn') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                        @foreach(['aktif'=>'Aktif','pindah'=>'Pindah','lulus'=>'Lulus','dikeluarkan'=>'Dikeluarkan'] as $k=>$v)
                            <option value="{{ $k }}" {{ old('status', $siswa->status) == $k ? 'selected' : '' }}>{{ $v }}</option>
                        @endforeach
                    </select>
                    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-8">
                    <label class="form-label fw-bold">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama', $siswa->nama) }}" required>
                    @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Jenis Kelamin <span class="text-danger">*</span></label>
                    <select name="jenis_kelamin" class="form-select @error('jenis_kelamin') is-invalid @enderror" required>
                        <option value="L" {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('jenis_kelamin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Kelas <span class="text-danger">*</span></label>
                    <input type="text" name="kelas" class="form-control @error('kelas') is-invalid @enderror" value="{{ old('kelas', $siswa->kelas) }}" required>
                    @error('kelas') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Tahun Ajaran <span class="text-danger">*</span></label>
                    <input type="text" name="tahun_ajaran" class="form-control @error('tahun_ajaran') is-invalid @enderror" value="{{ old('tahun_ajaran', $siswa->tahun_ajaran) }}" required>
                    @error('tahun_ajaran') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Agama</label>
                    <input type="text" name="agama" class="form-control @error('agama') is-invalid @enderror" value="{{ old('agama', $siswa->agama) }}">
                    @error('agama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" class="form-control @error('tempat_lahir') is-invalid @enderror" value="{{ old('tempat_lahir', $siswa->tempat_lahir) }}">
                    @error('tempat_lahir') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" class="form-control @error('tanggal_lahir') is-invalid @enderror" value="{{ old('tanggal_lahir', $siswa->tanggal_lahir?->format('Y-m-d')) }}">
                    @error('tanggal_lahir') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label fw-bold">Alamat</label>
                    <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror" rows="2">{{ old('alamat', $siswa->alamat) }}</textarea>
                    @error('alamat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Nama Wali</label>
                    <input type="text" name="nama_wali" class="form-control @error('nama_wali') is-invalid @enderror" value="{{ old('nama_wali', $siswa->nama_wali) }}">
                    @error('nama_wali') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Telepon Wali</label>
                    <input type="text" name="telepon_wali" class="form-control @error('telepon_wali') is-invalid @enderror" value="{{ old('telepon_wali', $siswa->telepon_wali) }}">
                    @error('telepon_wali') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Perbarui</button>
                <a href="{{ route('staf.kesiswaan.index') }}" class="btn btn-light ms-2">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
