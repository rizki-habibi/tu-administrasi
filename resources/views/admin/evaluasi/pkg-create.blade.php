@extends('layouts.admin')
@section('title', 'Tambah Penilaian PKG')

@section('content')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('admin.evaluasi.pkg') }}" class="btn btn-outline-secondary btn-sm me-3"><i class="bi bi-arrow-left"></i></a>
    <h4 class="fw-bold mb-0" style="color:#1e293b;">Tambah Penilaian Kinerja</h4>
</div>

<div class="card">
    <div class="card-body p-4">
        <form action="{{ route('admin.evaluasi.pkg.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Guru / Staff <span class="text-danger">*</span></label>
                    <select name="user_id" class="form-select @error('user_id') is-invalid @enderror" required>
                        <option value="">Pilih Guru/Staff</option>
                        @foreach($staffs ?? [] as $s)
                        <option value="{{ $s->id }}" {{ old('user_id')==$s->id?'selected':'' }}>{{ $s->name }}</option>
                        @endforeach
                    </select>
                    @error('user_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Jenis Penilaian <span class="text-danger">*</span></label>
                    <select name="jenis" class="form-select" required>
                        <option value="pkg" {{ old('jenis')=='pkg'?'selected':'' }}>PKG</option>
                        <option value="bkd" {{ old('jenis')=='bkd'?'selected':'' }}>BKD</option>
                        <option value="skp" {{ old('jenis')=='skp'?'selected':'' }}>SKP</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Periode</label>
                    <input type="text" name="periode" class="form-control" value="{{ old('periode', 'Semester '.((date('n')<=6)?'1':'2').' '.date('Y')) }}" placeholder="Semester 1 2025">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Nilai <span class="text-danger">*</span></label>
                    <input type="number" name="nilai" class="form-control @error('nilai') is-invalid @enderror" value="{{ old('nilai') }}" min="0" max="100" required>
                    @error('nilai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Predikat</label>
                    <select name="predikat" class="form-select">
                        <option value="amat_baik" {{ old('predikat')=='amat_baik'?'selected':'' }}>Amat Baik</option>
                        <option value="baik" {{ old('predikat')=='baik'?'selected':'' }}>Baik</option>
                        <option value="cukup" {{ old('predikat')=='cukup'?'selected':'' }}>Cukup</option>
                        <option value="kurang" {{ old('predikat')=='kurang'?'selected':'' }}>Kurang</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">File Pendukung</label>
                    <input type="file" name="file" class="form-control" accept=".pdf,.doc,.docx">
                </div>
                <div class="col-12">
                    <label class="form-label">Catatan / Rekomendasi</label>
                    <textarea name="catatan" class="form-control" rows="3">{{ old('catatan') }}</textarea>
                </div>
            </div>
            <hr class="my-4">
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.evaluasi.pkg') }}" class="btn btn-outline-secondary">Batal</a>
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
