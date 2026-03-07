@extends('peran.admin.app')
@section('judul', 'Upload Dokumen Kepegawaian')

@section('konten')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
        <h5 class="fw-bold mb-1">Upload Dokumen Kepegawaian</h5>
        <p class="text-muted mb-0" style="font-size:.82rem;">Arsipkan dokumen kepegawaian secara digital</p>
    </div>
    <a href="{{ route('admin.kepegawaian.dokumen.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.kepegawaian.dokumen.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-medium" style="font-size:.85rem;">Pegawai <span class="text-danger">*</span></label>
                    <select name="pengguna_id" class="form-select form-select-sm @error('pengguna_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Pegawai --</option>
                        @foreach($pegawaiList as $p)
                            <option value="{{ $p->id }}" {{ old('pengguna_id', $pegawaiId ?? '') == $p->id ? 'selected' : '' }}>{{ $p->nama }}</option>
                        @endforeach
                    </select>
                    @error('pengguna_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium" style="font-size:.85rem;">Kategori <span class="text-danger">*</span></label>
                    <select name="kategori" class="form-select form-select-sm @error('kategori') is-invalid @enderror" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($kategoriList as $key => $label)
                            <option value="{{ $key }}" {{ old('kategori') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('kategori')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium" style="font-size:.85rem;">Judul Dokumen <span class="text-danger">*</span></label>
                    <input type="text" name="judul" class="form-control form-control-sm @error('judul') is-invalid @enderror" value="{{ old('judul') }}" required>
                    @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-medium" style="font-size:.85rem;">Nomor Dokumen</label>
                    <input type="text" name="nomor_dokumen" class="form-control form-control-sm @error('nomor_dokumen') is-invalid @enderror" value="{{ old('nomor_dokumen') }}">
                    @error('nomor_dokumen')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-medium" style="font-size:.85rem;">Tanggal Dokumen</label>
                    <input type="date" name="tanggal_dokumen" class="form-control form-control-sm @error('tanggal_dokumen') is-invalid @enderror" value="{{ old('tanggal_dokumen') }}">
                    @error('tanggal_dokumen')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium" style="font-size:.85rem;">File Dokumen <span class="text-danger">*</span> <small class="text-muted">(PDF/JPG/PNG/DOC/DOCX, maks 10MB)</small></label>
                    <input type="file" name="file_path" class="form-control form-control-sm @error('file_path') is-invalid @enderror" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" required>
                    @error('file_path')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium" style="font-size:.85rem;">Keterangan</label>
                    <textarea name="keterangan" class="form-control form-control-sm @error('keterangan') is-invalid @enderror" rows="2">{{ old('keterangan') }}</textarea>
                    @error('keterangan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-upload me-1"></i>Upload & Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
