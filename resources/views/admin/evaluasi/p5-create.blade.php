@extends('admin.tata-letak.app')
@section('judul', 'Tambah Asesmen P5')

@section('konten')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('admin.evaluasi.p5') }}" class="btn btn-outline-secondary btn-sm me-3"><i class="bi bi-arrow-left"></i></a>
    <h4 class="fw-bold mb-0" style="color:#1e293b;">Tambah Asesmen P5</h4>
</div>

<div class="card">
    <div class="card-body p-4">
        <form action="{{ route('admin.evaluasi.p5.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label">Judul Projek <span class="text-danger">*</span></label>
                    <input type="text" name="judul_projek" class="form-control @error('judul_projek') is-invalid @enderror" value="{{ old('judul_projek') }}" required>
                    @error('judul_projek')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Dimensi <span class="text-danger">*</span></label>
                    <select name="dimensi" class="form-select @error('dimensi') is-invalid @enderror" required>
                        <option value="">Pilih Dimensi</option>
                        <option value="beriman" {{ old('dimensi')=='beriman'?'selected':'' }}>Beriman & Bertaqwa</option>
                        <option value="berkebinekaan" {{ old('dimensi')=='berkebinekaan'?'selected':'' }}>Berkebhinekaan Global</option>
                        <option value="gotong_royong" {{ old('dimensi')=='gotong_royong'?'selected':'' }}>Bergotong Royong</option>
                        <option value="mandiri" {{ old('dimensi')=='mandiri'?'selected':'' }}>Mandiri</option>
                        <option value="bernalar_kritis" {{ old('dimensi')=='bernalar_kritis'?'selected':'' }}>Bernalar Kritis</option>
                        <option value="kreatif" {{ old('dimensi')=='kreatif'?'selected':'' }}>Kreatif</option>
                    </select>
                    @error('dimensi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tema <span class="text-danger">*</span></label>
                    <input type="text" name="tema" class="form-control @error('tema') is-invalid @enderror" value="{{ old('tema') }}" required>
                    @error('tema')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-2">
                    <label class="form-label">Kelas <span class="text-danger">*</span></label>
                    <input type="text" name="kelas" class="form-control @error('kelas') is-invalid @enderror" value="{{ old('kelas') }}" placeholder="X, XI, XII" required>
                    @error('kelas')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-2">
                    <label class="form-label">Fase <span class="text-danger">*</span></label>
                    <select name="fase" class="form-select @error('fase') is-invalid @enderror" required>
                        <option value="E" {{ old('fase')=='E'?'selected':'' }}>Fase E</option>
                        <option value="F" {{ old('fase')=='F'?'selected':'' }}>Fase F</option>
                    </select>
                    @error('fase')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-2">
                    <label class="form-label">Tahun Ajaran <span class="text-danger">*</span></label>
                    <input type="text" name="tahun_ajaran" class="form-control @error('tahun_ajaran') is-invalid @enderror" value="{{ old('tahun_ajaran') }}" placeholder="2025/2026" required>
                    @error('tahun_ajaran')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-2">
                    <label class="form-label">Semester <span class="text-danger">*</span></label>
                    <select name="semester" class="form-select @error('semester') is-invalid @enderror" required>
                        <option value="ganjil" {{ old('semester')=='ganjil'?'selected':'' }}>Ganjil</option>
                        <option value="genap" {{ old('semester')=='genap'?'selected':'' }}>Genap</option>
                    </select>
                    @error('semester')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Deskripsi Projek <span class="text-danger">*</span></label>
                    <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" rows="4" required>{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Target Capaian</label>
                    <textarea name="target_capaian" class="form-control" rows="2">{{ old('target_capaian') }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label">File Pendukung</label>
                    <input type="file" name="file" class="form-control" accept=".pdf,.doc,.docx,.ppt,.pptx">
                </div>
            </div>
            <hr class="my-4">
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.evaluasi.p5') }}" class="btn btn-outline-secondary">Batal</a>
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
