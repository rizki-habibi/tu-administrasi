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
                <div class="col-md-6">
                    <label class="form-label">Standar <span class="text-danger">*</span></label>
                    <select name="standar" class="form-select @error('standar') is-invalid @enderror" required>
                        <option value="">Pilih Standar</option>
                        @php $standarOptions = ['standar_isi'=>'Standar Isi','standar_proses'=>'Standar Proses','standar_kompetensi_lulusan'=>'Standar Kompetensi Lulusan','standar_pendidik'=>'Standar Pendidik','standar_sarpras'=>'Standar Sarpras','standar_pengelolaan'=>'Standar Pengelolaan','standar_pembiayaan'=>'Standar Pembiayaan','standar_penilaian'=>'Standar Penilaian']; @endphp
                        @foreach($standarOptions as $val => $label)
                        <option value="{{ $val }}" {{ old('standar')==$val?'selected':'' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('standar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Komponen <span class="text-danger">*</span></label>
                    <input type="text" name="komponen" class="form-control @error('komponen') is-invalid @enderror" value="{{ old('komponen') }}" placeholder="Sub komponen standar" required>
                    @error('komponen')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Indikator <span class="text-danger">*</span></label>
                    <input type="text" name="indikator" class="form-control @error('indikator') is-invalid @enderror" value="{{ old('indikator') }}" required>
                    @error('indikator')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">File Dokumen</label>
                    <input type="file" name="file" class="form-control" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.png">
                    <small class="text-muted">Maks 10MB. Upload file → status otomatis "Lengkap"</small>
                </div>
                <div class="col-12">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi') }}</textarea>
                </div>
                <div class="col-12">
                    <label class="form-label">Catatan</label>
                    <textarea name="catatan" class="form-control" rows="2">{{ old('catatan') }}</textarea>
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
