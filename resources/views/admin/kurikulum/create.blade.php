@extends('peran.admin.app')
@section('judul', 'Tambah Dokumen Kurikulum')

@section('konten')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('admin.kurikulum.index') }}" class="btn btn-outline-secondary btn-sm me-3"><i class="bi bi-arrow-left"></i></a>
    <div>
        <h4 class="fw-bold mb-0" style="color:#1e293b;">Tambah Dokumen Kurikulum</h4>
        <p class="text-muted mb-0" style="font-size:.85rem;">Unggah dokumen RPP, Silabus, Jadwal, dll</p>
    </div>
</div>

<div class="card">
    <div class="card-body p-4">
        <form action="{{ route('admin.kurikulum.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label">Judul Dokumen <span class="text-danger">*</span></label>
                    <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror" value="{{ old('judul') }}" required>
                    @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Jenis Dokumen <span class="text-danger">*</span></label>
                    <select name="jenis" class="form-select @error('jenis') is-invalid @enderror" required>
                        <option value="">Pilih Jenis</option>
                        <option value="rpp" {{ old('jenis')=='rpp'?'selected':'' }}>RPP / Modul Ajar</option>
                        <option value="silabus" {{ old('jenis')=='silabus'?'selected':'' }}>Silabus / ATP</option>
                        <option value="jadwal" {{ old('jenis')=='jadwal'?'selected':'' }}>Jadwal Pelajaran</option>
                        <option value="kalender" {{ old('jenis')=='kalender'?'selected':'' }}>Kalender Pendidikan</option>
                        <option value="kisi_kisi" {{ old('jenis')=='kisi_kisi'?'selected':'' }}>Kisi-kisi</option>
                        <option value="prota" {{ old('jenis')=='prota'?'selected':'' }}>Prota / Promes</option>
                        <option value="lainnya" {{ old('jenis')=='lainnya'?'selected':'' }}>Lainnya</option>
                    </select>
                    @error('jenis')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tahun Ajaran</label>
                    <input type="text" name="tahun_ajaran" class="form-control" value="{{ old('tahun_ajaran', date('Y').'/'.(date('Y')+1)) }}" placeholder="2025/2026">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Semester</label>
                    <select name="semester" class="form-select">
                        <option value="">Pilih Semester</option>
                        <option value="1" {{ old('semester')=='1'?'selected':'' }}>Semester 1 (Ganjil)</option>
                        <option value="2" {{ old('semester')=='2'?'selected':'' }}>Semester 2 (Genap)</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="draft">Draf</option>
                        <option value="active">Aktif</option>
                        <option value="archived">Diarsipkan</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Mata Pelajaran</label>
                    <input type="text" name="mata_pelajaran" class="form-control" value="{{ old('mata_pelajaran') }}" placeholder="Contoh: Matematika, Bahasa Indonesia">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Kelas / Tingkat</label>
                    <input type="text" name="tingkat_kelas" class="form-control" value="{{ old('tingkat_kelas') }}" placeholder="Contoh: X, XI IPA, XII">
                </div>
                <div class="col-12">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" rows="3" placeholder="Keterangan dokumen...">{{ old('deskripsi') }}</textarea>
                </div>
                <div class="col-12">
                    <label class="form-label">File Dokumen <span class="text-danger">*</span></label>
                    <input type="file" name="file" class="form-control @error('file') is-invalid @enderror" required accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx">
                    @error('file')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <small class="text-muted">Format: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX. Maks 10MB</small>
                </div>
            </div>
            <hr class="my-4">
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.kurikulum.index') }}" class="btn btn-outline-secondary">Batal</a>
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
