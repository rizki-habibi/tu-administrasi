@extends('peran.staf.app')
@section('judul', 'Catat Prestasi')

@section('konten')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-plus-lg"></i> Catat Prestasi Siswa</h4>
    <a href="{{ route('staf.prestasi.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="{{ route('staf.prestasi.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Siswa <span class="text-danger">*</span></label>
                    <select name="siswa_id" class="form-select @error('siswa_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Siswa --</option>
                        @foreach($siswa as $s)
                            <option value="{{ $s->id }}" {{ old('siswa_id') == $s->id ? 'selected' : '' }}>{{ $s->nis }} - {{ $s->nama }} ({{ $s->kelas }})</option>
                        @endforeach
                    </select>
                    @error('siswa_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Tanggal <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                    @error('tanggal') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Tingkat <span class="text-danger">*</span></label>
                    <select name="tingkat" class="form-select @error('tingkat') is-invalid @enderror" required>
                        @foreach(['sekolah'=>'Sekolah','kecamatan'=>'Kecamatan','kabupaten'=>'Kabupaten','provinsi'=>'Provinsi','nasional'=>'Nasional','internasional'=>'Internasional'] as $k=>$v)
                            <option value="{{ $k }}" {{ old('tingkat') == $k ? 'selected' : '' }}>{{ $v }}</option>
                        @endforeach
                    </select>
                    @error('tingkat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-8">
                    <label class="form-label fw-bold">Judul Prestasi <span class="text-danger">*</span></label>
                    <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror" value="{{ old('judul') }}" required>
                    @error('judul') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Jenis <span class="text-danger">*</span></label>
                    <select name="jenis" class="form-select @error('jenis') is-invalid @enderror" required>
                        @foreach(['akademik'=>'Akademik','non_akademik'=>'Non Akademik','olahraga'=>'Olahraga','seni'=>'Seni','lainnya'=>'Lainnya'] as $k=>$v)
                            <option value="{{ $k }}" {{ old('jenis') == $k ? 'selected' : '' }}>{{ $v }}</option>
                        @endforeach
                    </select>
                    @error('jenis') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Penyelenggara</label>
                    <input type="text" name="penyelenggara" class="form-control @error('penyelenggara') is-invalid @enderror" value="{{ old('penyelenggara') }}">
                    @error('penyelenggara') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Hasil</label>
                    <input type="text" name="hasil" class="form-control @error('hasil') is-invalid @enderror" value="{{ old('hasil') }}" placeholder="Misal: Juara 1">
                    @error('hasil') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Bukti/Sertifikat <small class="text-muted">(opsional)</small></label>
                    <input type="file" name="file" class="form-control @error('file') is-invalid @enderror" accept=".jpg,.jpeg,.png,.pdf">
                    @error('file') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
                <a href="{{ route('staf.prestasi.index') }}" class="btn btn-light ms-2">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
