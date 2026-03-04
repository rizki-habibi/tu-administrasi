@extends('peran.admin.app')
@section('judul', 'Tambah Model Pembelajaran')

@section('konten')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('admin.evaluasi.pembelajaran') }}" class="btn btn-outline-secondary btn-sm me-3"><i class="bi bi-arrow-left"></i></a>
    <h4 class="fw-bold mb-0" style="color:#1e293b;">Tambah Model Pembelajaran</h4>
</div>

<div class="card">
    <div class="card-body p-4">
        <form action="{{ route('admin.evaluasi.pembelajaran.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nama Model/Metode <span class="text-danger">*</span></label>
                    <input type="text" name="nama_metode" class="form-control @error('nama_metode') is-invalid @enderror" value="{{ old('nama_metode') }}" required placeholder="Contoh: Problem Based Learning">
                    @error('nama_metode')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Jenis <span class="text-danger">*</span></label>
                    <select name="jenis" class="form-select @error('jenis') is-invalid @enderror" required>
                        <option value="">Pilih</option>
                        <option value="model_pembelajaran" {{ old('jenis')=='model_pembelajaran'?'selected':'' }}>Model Pembelajaran</option>
                        <option value="teknologi_pembelajaran" {{ old('jenis')=='teknologi_pembelajaran'?'selected':'' }}>Teknologi Pembelajaran</option>
                        <option value="media_pembelajaran" {{ old('jenis')=='media_pembelajaran'?'selected':'' }}>Media Pembelajaran</option>
                    </select>
                    @error('jenis')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Mata Pelajaran</label>
                    <input type="text" name="mata_pelajaran" class="form-control" value="{{ old('mata_pelajaran') }}">
                </div>
                <div class="col-12">
                    <label class="form-label">Deskripsi <span class="text-danger">*</span></label>
                    <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" rows="4" required>{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Langkah Pelaksanaan</label>
                    <textarea name="langkah_pelaksanaan" class="form-control" rows="3">{{ old('langkah_pelaksanaan') }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Kelebihan / Manfaat</label>
                    <textarea name="kelebihan" class="form-control" rows="2">{{ old('kelebihan') }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Kekurangan / Kendala</label>
                    <textarea name="kekurangan" class="form-control" rows="2">{{ old('kekurangan') }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Hasil</label>
                    <textarea name="hasil" class="form-control" rows="2">{{ old('hasil') }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label">File Pendukung</label>
                    <input type="file" name="file" class="form-control" accept=".pdf,.doc,.docx">
                </div>
            </div>
            <hr class="my-4">
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.evaluasi.pembelajaran') }}" class="btn btn-outline-secondary">Batal</a>
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
