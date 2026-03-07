@extends('peran.admin.app')
@section('judul', 'Edit Analisis STAR')

@section('konten')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('admin.evaluasi.star') }}" class="btn btn-outline-secondary btn-sm me-3"><i class="bi bi-arrow-left"></i></a>
    <h4 class="fw-bold mb-0" style="color:#1e293b;">Edit Analisis STAR</h4>
</div>

<div class="card">
    <div class="card-body p-4">
        <form action="{{ route('admin.evaluasi.star.update', $star) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Judul <span class="text-danger">*</span></label>
                    <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror" value="{{ old('judul', $star->judul) }}" required>
                    @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Kategori <span class="text-danger">*</span></label>
                    <select name="kategori" class="form-select @error('kategori') is-invalid @enderror" required>
                        <option value="">Pilih Kategori</option>
                        @foreach(['pembelajaran','administrasi','manajemen'] as $k)
                        <option value="{{ $k }}" {{ old('kategori', $star->kategori)==$k?'selected':'' }}>{{ ucfirst($k) }}</option>
                        @endforeach
                    </select>
                    @error('kategori')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <hr class="my-3">
            <h6 class="fw-semibold"><span class="badge bg-primary me-1">S</span> Situasi</h6>
            <textarea name="situasi" class="form-control @error('situasi') is-invalid @enderror mb-3" rows="3" required>{{ old('situasi', $star->situasi) }}</textarea>
            @error('situasi')<div class="invalid-feedback">{{ $message }}</div>@enderror

            <h6 class="fw-semibold"><span class="badge bg-warning text-dark me-1">T</span> Tugas</h6>
            <textarea name="tugas" class="form-control @error('tugas') is-invalid @enderror mb-3" rows="3" required>{{ old('tugas', $star->tugas) }}</textarea>
            @error('tugas')<div class="invalid-feedback">{{ $message }}</div>@enderror

            <h6 class="fw-semibold"><span class="badge bg-success me-1">A</span> Aksi</h6>
            <textarea name="aksi" class="form-control @error('aksi') is-invalid @enderror mb-3" rows="3" required>{{ old('aksi', $star->aksi) }}</textarea>
            @error('aksi')<div class="invalid-feedback">{{ $message }}</div>@enderror

            <h6 class="fw-semibold"><span class="badge bg-danger me-1">R</span> Hasil</h6>
            <textarea name="hasil" class="form-control @error('hasil') is-invalid @enderror mb-3" rows="3" required>{{ old('hasil', $star->hasil) }}</textarea>
            @error('hasil')<div class="invalid-feedback">{{ $message }}</div>@enderror

            <div class="mb-3">
                <label class="form-label">Refleksi (Opsional)</label>
                <textarea name="refleksi" class="form-control" rows="2">{{ old('refleksi', $star->refleksi) }}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Tindak Lanjut (Opsional)</label>
                <textarea name="tindak_lanjut" class="form-control" rows="2">{{ old('tindak_lanjut', $star->tindak_lanjut) }}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">File Pendukung</label>
                @if($star->path_file)
                <div class="mb-2"><small class="text-muted">File saat ini: <a href="{{ asset('storage/'.$star->path_file) }}" target="_blank">{{ basename($star->path_file) }}</a></small></div>
                @endif
                <input type="file" name="file" class="form-control" accept=".pdf,.doc,.docx">
            </div>

            <hr class="my-3">
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.evaluasi.star') }}" class="btn btn-outline-secondary">Batal</a>
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection
