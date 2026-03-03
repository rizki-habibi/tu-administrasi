@extends('layouts.admin')
@section('title', 'Edit Dokumen')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-bold mb-0">Edit Dokumen</h5>
    <a href="{{ route('admin.document.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.document.update', $document) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label">Judul Dokumen <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $document->title) }}" required>
                    @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Kategori <span class="text-danger">*</span></label>
                    <select name="category" class="form-select @error('category') is-invalid @enderror" required>
                        @foreach(['kurikulum'=>'Kurikulum','administrasi'=>'Administrasi','keuangan'=>'Keuangan','kepegawaian'=>'Kepegawaian','kesiswaan'=>'Kesiswaan','surat'=>'Surat','inventaris'=>'Inventaris','lainnya'=>'Lainnya'] as $k => $v)
                            <option value="{{ $k }}" {{ old('category', $document->category) == $k ? 'selected' : '' }}>{{ $v }}</option>
                        @endforeach
                    </select>
                    @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description', $document->description) }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Ganti File <small class="text-muted">(opsional)</small></label>
                    <input type="file" name="file" class="form-control @error('file') is-invalid @enderror" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png">
                    @error('file') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    <small class="text-muted mt-1 d-block"><i class="bi {{ $document->file_icon }} me-1"></i> File saat ini: {{ $document->file_name }} ({{ $document->file_size_formatted }})</small>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
