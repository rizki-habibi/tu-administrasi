@extends('layouts.admin')
@section('title', 'Tambah Dokumen Akreditasi')

@section('content')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('admin.akreditasi.index') }}" class="btn btn-outline-secondary btn-sm me-3"><i class="bi bi-arrow-left"></i></a>
    <h4 class="fw-bold mb-0" style="color:#1e293b;">Tambah Dokumen Akreditasi</h4>
</div>

<div class="card">
    <div class="card-body p-4">
        <form action="{{ route('admin.akreditasi.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label">Judul Dokumen <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Standar <span class="text-danger">*</span></label>
                    <select name="standar" class="form-select @error('standar') is-invalid @enderror" required>
                        <option value="">Pilih Standar</option>
                        @for($i=1;$i<=8;$i++)
                        <option value="Standar {{ $i }}" {{ old('standar')=="Standar $i"?'selected':'' }}>Standar {{ $i }}</option>
                        @endfor
                    </select>
                    @error('standar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tahun</label>
                    <input type="text" name="year" class="form-control" value="{{ old('year', date('Y')) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="draft">Draft</option>
                        <option value="final">Final</option>
                        <option value="terverifikasi">Terverifikasi</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Komponen</label>
                    <input type="text" name="component" class="form-control" value="{{ old('component') }}" placeholder="Sub komponen standar">
                </div>
                <div class="col-12">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label">File Dokumen</label>
                    <input type="file" name="file" class="form-control" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.png">
                    <small class="text-muted">Maks 10MB</small>
                </div>
            </div>
            <hr class="my-4">
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.akreditasi.index') }}" class="btn btn-outline-secondary">Batal</a>
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
