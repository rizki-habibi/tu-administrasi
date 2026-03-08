@extends('peran.staf.app')
@section('judul', 'Catat Pelanggaran')

@section('konten')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-plus-lg"></i> Catat Pelanggaran Siswa</h4>
    <a href="{{ route('staf.pelanggaran.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="{{ route('staf.pelanggaran.store') }}" method="POST">
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
                    <label class="form-label fw-bold">Jenis <span class="text-danger">*</span></label>
                    <select name="jenis" class="form-select @error('jenis') is-invalid @enderror" required>
                        <option value="ringan" {{ old('jenis') == 'ringan' ? 'selected' : '' }}>Ringan</option>
                        <option value="sedang" {{ old('jenis') == 'sedang' ? 'selected' : '' }}>Sedang</option>
                        <option value="berat" {{ old('jenis') == 'berat' ? 'selected' : '' }}>Berat</option>
                    </select>
                    @error('jenis') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Tanggal <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                    @error('tanggal') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label fw-bold">Deskripsi Pelanggaran <span class="text-danger">*</span></label>
                    <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" rows="4" required>{{ old('deskripsi') }}</textarea>
                    @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label fw-bold">Tindakan/Sanksi</label>
                    <textarea name="tindakan" class="form-control @error('tindakan') is-invalid @enderror" rows="2">{{ old('tindakan') }}</textarea>
                    @error('tindakan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
                <a href="{{ route('staf.pelanggaran.index') }}" class="btn btn-light ms-2">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
