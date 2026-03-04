@extends('peran.admin.app')
@section('judul', 'Tambah Analisis STAR')

@section('konten')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('admin.evaluasi.star') }}" class="btn btn-outline-secondary btn-sm me-3"><i class="bi bi-arrow-left"></i></a>
    <h4 class="fw-bold mb-0" style="color:#1e293b;">Tambah Analisis Metode STAR</h4>
</div>

<div class="card">
    <div class="card-body p-4">
        <form action="{{ route('admin.evaluasi.star.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Judul <span class="text-danger">*</span></label>
                    <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror" value="{{ old('judul') }}" required>
                    @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Kategori <span class="text-danger">*</span></label>
                    <select name="kategori" class="form-select @error('kategori') is-invalid @enderror" required>
                        <option value="">Pilih Kategori</option>
                        <option value="pembelajaran" {{ old('kategori')=='pembelajaran'?'selected':'' }}>Pembelajaran</option>
                        <option value="administrasi" {{ old('kategori')=='administrasi'?'selected':'' }}>Administrasi</option>
                        <option value="manajemen" {{ old('kategori')=='manajemen'?'selected':'' }}>Manajemen</option>
                    </select>
                    @error('kategori')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <hr class="my-3">
            <h6 class="fw-semibold"><span class="badge bg-primary me-1">S</span> Situation (Situasi)</h6>
            <textarea name="situation" class="form-control @error('situasi') is-invalid @enderror mb-3" rows="3" placeholder="Jelaskan situasi atau konteks yang dihadapi..." required>{{ old('situasi') }}</textarea>
            @error('situasi')<div class="invalid-feedback">{{ $message }}</div>@enderror

            <h6 class="fw-semibold"><span class="badge bg-warning text-dark me-1">T</span> Task (Tugas)</h6>
            <textarea name="task" class="form-control @error('tugas') is-invalid @enderror mb-3" rows="3" placeholder="Tugas atau tantangan yang harus diselesaikan..." required>{{ old('tugas') }}</textarea>
            @error('tugas')<div class="invalid-feedback">{{ $message }}</div>@enderror

            <h6 class="fw-semibold"><span class="badge bg-success me-1">A</span> Action (Tindakan)</h6>
            <textarea name="action" class="form-control @error('aksi') is-invalid @enderror mb-3" rows="3" placeholder="Langkah-langkah atau tindakan yang diambil..." required>{{ old('aksi') }}</textarea>
            @error('aksi')<div class="invalid-feedback">{{ $message }}</div>@enderror

            <h6 class="fw-semibold"><span class="badge bg-danger me-1">R</span> Result (Hasil)</h6>
            <textarea name="result" class="form-control @error('hasil') is-invalid @enderror mb-3" rows="3" placeholder="Hasil atau dampak dari tindakan yang diambil..." required>{{ old('hasil') }}</textarea>
            @error('hasil')<div class="invalid-feedback">{{ $message }}</div>@enderror

            <div class="mb-3">
                <label class="form-label">Refleksi (Opsional)</label>
                <textarea name="refleksi" class="form-control" rows="2">{{ old('refleksi') }}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Tindak Lanjut (Opsional)</label>
                <textarea name="tindak_lanjut" class="form-control" rows="2">{{ old('tindak_lanjut') }}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">File Pendukung</label>
                <input type="file" name="file" class="form-control" accept=".pdf,.doc,.docx">
            </div>

            <hr class="my-3">
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.evaluasi.star') }}" class="btn btn-outline-secondary">Batal</a>
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
