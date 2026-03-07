@extends('peran.admin.app')
@section('judul', 'Edit Model Pembelajaran')

@section('konten')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('admin.evaluasi.pembelajaran') }}" class="btn btn-outline-secondary btn-sm me-3"><i class="bi bi-arrow-left"></i></a>
    <h4 class="fw-bold mb-0" style="color:#1e293b;">Edit Model Pembelajaran</h4>
</div>

<div class="card">
    <div class="card-body p-4">
        <form action="{{ route('admin.evaluasi.pembelajaran.update', $method) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nama Model/Metode <span class="text-danger">*</span></label>
                    <input type="text" name="nama_metode" class="form-control @error('nama_metode') is-invalid @enderror" value="{{ old('nama_metode', $method->nama_metode) }}" required>
                    @error('nama_metode')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Jenis <span class="text-danger">*</span></label>
                    <select name="jenis" class="form-select @error('jenis') is-invalid @enderror" required>
                        <option value="">Pilih</option>
                        @foreach(['model_pembelajaran'=>'Model Pembelajaran','teknologi_pembelajaran'=>'Teknologi Pembelajaran','media_pembelajaran'=>'Media Pembelajaran'] as $v => $l)
                        <option value="{{ $v }}" {{ old('jenis', $method->jenis)==$v?'selected':'' }}>{{ $l }}</option>
                        @endforeach
                    </select>
                    @error('jenis')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Mata Pelajaran</label>
                    <input type="text" name="mata_pelajaran" class="form-control" value="{{ old('mata_pelajaran', $method->mata_pelajaran) }}">
                </div>
                <div class="col-12">
                    <label class="form-label">Deskripsi <span class="text-danger">*</span></label>
                    <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" rows="4" required>{{ old('deskripsi', $method->deskripsi) }}</textarea>
                    @error('deskripsi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Langkah Pelaksanaan</label>
                    <textarea name="langkah_pelaksanaan" class="form-control" rows="3">{{ old('langkah_pelaksanaan', $method->langkah_pelaksanaan) }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Kelebihan / Manfaat</label>
                    <textarea name="kelebihan" class="form-control" rows="2">{{ old('kelebihan', $method->kelebihan) }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Kekurangan / Kendala</label>
                    <textarea name="kekurangan" class="form-control" rows="2">{{ old('kekurangan', $method->kekurangan) }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Hasil</label>
                    <textarea name="hasil" class="form-control" rows="2">{{ old('hasil', $method->hasil) }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label">File Pendukung</label>
                    @if($method->path_file)
                    <div class="mb-2"><small class="text-muted">File saat ini: <a href="{{ asset('storage/'.$method->path_file) }}" target="_blank">{{ $method->nama_file ?? basename($method->path_file) }}</a></small></div>
                    @endif
                    <input type="file" name="file" class="form-control" accept=".pdf,.doc,.docx">
                </div>
            </div>
            <hr class="my-4">
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.evaluasi.pembelajaran') }}" class="btn btn-outline-secondary">Batal</a>
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection
