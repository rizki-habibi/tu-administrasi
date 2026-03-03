@extends('layouts.admin')
@section('title', 'Tambah Model Pembelajaran')

@section('content')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('admin.evaluasi.learning') }}" class="btn btn-outline-secondary btn-sm me-3"><i class="bi bi-arrow-left"></i></a>
    <h4 class="fw-bold mb-0" style="color:#1e293b;">Tambah Model Pembelajaran</h4>
</div>

<div class="card">
    <div class="card-body p-4">
        <form action="{{ route('admin.evaluasi.learning.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nama Model/Metode <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required placeholder="Contoh: Problem Based Learning">
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Jenis <span class="text-danger">*</span></label>
                    <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                        <option value="">Pilih</option>
                        <option value="model" {{ old('type')=='model'?'selected':'' }}>Model Pembelajaran</option>
                        <option value="metode" {{ old('type')=='metode'?'selected':'' }}>Metode</option>
                        <option value="teknologi" {{ old('type')=='teknologi'?'selected':'' }}>Teknologi/Digital</option>
                        <option value="pendekatan" {{ old('type')=='pendekatan'?'selected':'' }}>Pendekatan</option>
                    </select>
                    @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Mata Pelajaran</label>
                    <input type="text" name="subject" class="form-control" value="{{ old('subject') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Kelas / Tingkat</label>
                    <input type="text" name="class_level" class="form-control" value="{{ old('class_level') }}" placeholder="X, XI, XII">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Alat / Teknologi yang Digunakan</label>
                    <input type="text" name="tools_used" class="form-control" value="{{ old('tools_used') }}" placeholder="Google Classroom, Quizziz, dll">
                </div>
                <div class="col-12">
                    <label class="form-label">Deskripsi <span class="text-danger">*</span></label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4" required>{{ old('description') }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Kelebihan / Manfaat</label>
                    <textarea name="benefits" class="form-control" rows="2">{{ old('benefits') }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tantangan / Kendala</label>
                    <textarea name="challenges" class="form-control" rows="2">{{ old('challenges') }}</textarea>
                </div>
            </div>
            <hr class="my-4">
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.evaluasi.learning') }}" class="btn btn-outline-secondary">Batal</a>
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
