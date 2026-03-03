@extends('layouts.admin')
@section('title', 'Tambah Asesmen P5')

@section('content')
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
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Dimensi <span class="text-danger">*</span></label>
                    <select name="dimension" class="form-select @error('dimension') is-invalid @enderror" required>
                        <option value="">Pilih Dimensi</option>
                        <option value="Beriman & Bertaqwa" {{ old('dimension')=='Beriman & Bertaqwa'?'selected':'' }}>Beriman & Bertaqwa</option>
                        <option value="Berkebhinekaan Global" {{ old('dimension')=='Berkebhinekaan Global'?'selected':'' }}>Berkebhinekaan Global</option>
                        <option value="Bergotong Royong" {{ old('dimension')=='Bergotong Royong'?'selected':'' }}>Bergotong Royong</option>
                        <option value="Mandiri" {{ old('dimension')=='Mandiri'?'selected':'' }}>Mandiri</option>
                        <option value="Bernalar Kritis" {{ old('dimension')=='Bernalar Kritis'?'selected':'' }}>Bernalar Kritis</option>
                        <option value="Kreatif" {{ old('dimension')=='Kreatif'?'selected':'' }}>Kreatif</option>
                    </select>
                    @error('dimension')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tema</label>
                    <input type="text" name="theme" class="form-control" value="{{ old('theme') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Kelas</label>
                    <input type="text" name="class_level" class="form-control" value="{{ old('class_level') }}" placeholder="X, XI, XII">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Periode</label>
                    <input type="text" name="period" class="form-control" value="{{ old('period') }}" placeholder="Semester 1 2025/2026">
                </div>
                <div class="col-12">
                    <label class="form-label">Deskripsi Projek <span class="text-danger">*</span></label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4" required>{{ old('description') }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Target Capaian</label>
                    <textarea name="target" class="form-control" rows="2">{{ old('target') }}</textarea>
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
