@extends('layouts.admin')
@section('title', 'Tambah Analisis STAR')

@section('content')
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
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Guru / Staff <span class="text-danger">*</span></label>
                    <select name="user_id" class="form-select" required>
                        <option value="">Pilih</option>
                        @foreach($staffList ?? [] as $s)
                        <option value="{{ $s->id }}" {{ old('user_id')==$s->id?'selected':'' }}>{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Periode</label>
                    <input type="text" name="period" class="form-control" value="{{ old('period') }}">
                </div>
            </div>

            <hr class="my-3">
            <h6 class="fw-semibold"><span class="badge bg-primary me-1">S</span> Situation (Situasi)</h6>
            <textarea name="situation" class="form-control @error('situation') is-invalid @enderror mb-3" rows="3" placeholder="Jelaskan situasi atau konteks yang dihadapi..." required>{{ old('situation') }}</textarea>
            @error('situation')<div class="invalid-feedback">{{ $message }}</div>@enderror

            <h6 class="fw-semibold"><span class="badge bg-warning text-dark me-1">T</span> Task (Tugas)</h6>
            <textarea name="task" class="form-control @error('task') is-invalid @enderror mb-3" rows="3" placeholder="Tugas atau tantangan yang harus diselesaikan..." required>{{ old('task') }}</textarea>
            @error('task')<div class="invalid-feedback">{{ $message }}</div>@enderror

            <h6 class="fw-semibold"><span class="badge bg-success me-1">A</span> Action (Tindakan)</h6>
            <textarea name="action" class="form-control @error('action') is-invalid @enderror mb-3" rows="3" placeholder="Langkah-langkah atau tindakan yang diambil..." required>{{ old('action') }}</textarea>
            @error('action')<div class="invalid-feedback">{{ $message }}</div>@enderror

            <h6 class="fw-semibold"><span class="badge bg-danger me-1">R</span> Result (Hasil)</h6>
            <textarea name="result" class="form-control @error('result') is-invalid @enderror mb-3" rows="3" placeholder="Hasil atau dampak dari tindakan yang diambil..." required>{{ old('result') }}</textarea>
            @error('result')<div class="invalid-feedback">{{ $message }}</div>@enderror

            <div class="mb-3">
                <label class="form-label">Refleksi (Opsional)</label>
                <textarea name="reflection" class="form-control" rows="2">{{ old('reflection') }}</textarea>
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
