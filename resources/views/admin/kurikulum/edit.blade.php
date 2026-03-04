@extends('admin.tata-letak.app')
@section('judul', 'Edit Dokumen Kurikulum')

@section('konten')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('admin.kurikulum.index') }}" class="btn btn-outline-secondary btn-sm me-3"><i class="bi bi-arrow-left"></i></a>
    <div>
        <h4 class="fw-bold mb-0" style="color:#1e293b;">Edit Dokumen Kurikulum</h4>
    </div>
</div>

<div class="card">
    <div class="card-body p-4">
        <form action="{{ route('admin.kurikulum.update', $kurikulum) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label">Judul Dokumen <span class="text-danger">*</span></label>
                    <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror" value="{{ old('judul', $kurikulum->judul) }}" required>
                    @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Jenis Dokumen <span class="text-danger">*</span></label>
                    <select name="type" class="form-select" required>
                        <option value="rpp" {{ old('jenis',$kurikulum->jenis)=='rpp'?'selected':'' }}>RPP / Modul Ajar</option>
                        <option value="silabus" {{ old('jenis',$kurikulum->jenis)=='silabus'?'selected':'' }}>Silabus / ATP</option>
                        <option value="jadwal" {{ old('jenis',$kurikulum->jenis)=='jadwal'?'selected':'' }}>Jadwal Pelajaran</option>
                        <option value="kalender" {{ old('jenis',$kurikulum->jenis)=='kalender'?'selected':'' }}>Kalender Pendidikan</option>
                        <option value="kisi_kisi" {{ old('jenis',$kurikulum->jenis)=='kisi_kisi'?'selected':'' }}>Kisi-kisi</option>
                        <option value="prota" {{ old('jenis',$kurikulum->jenis)=='prota'?'selected':'' }}>Prota / Promes</option>
                        <option value="lainnya" {{ old('jenis',$kurikulum->jenis)=='lainnya'?'selected':'' }}>Lainnya</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tahun Ajaran</label>
                    <input type="text" name="academic_year" class="form-control" value="{{ old('tahun_ajaran', $kurikulum->tahun_ajaran) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Semester</label>
                    <select name="semester" class="form-select">
                        <option value="">Pilih</option>
                        <option value="1" {{ old('semester',$kurikulum->semester)=='1'?'selected':'' }}>Semester 1</option>
                        <option value="2" {{ old('semester',$kurikulum->semester)=='2'?'selected':'' }}>Semester 2</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="draft" {{ old('status',$kurikulum->status)=='draft'?'selected':'' }}>Draft</option>
                        <option value="active" {{ old('status',$kurikulum->status)=='active'?'selected':'' }}>Aktif</option>
                        <option value="archived" {{ old('status',$kurikulum->status)=='archived'?'selected':'' }}>Diarsipkan</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Mata Pelajaran</label>
                    <input type="text" name="subject" class="form-control" value="{{ old('mata_pelajaran', $kurikulum->mata_pelajaran) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Kelas / Tingkat</label>
                    <input type="text" name="class_level" class="form-control" value="{{ old('tingkat_kelas', $kurikulum->tingkat_kelas) }}">
                </div>
                <div class="col-12">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('deskripsi', $kurikulum->deskripsi) }}</textarea>
                </div>
                <div class="col-12">
                    <label class="form-label">Ganti File (opsional)</label>
                    <input type="file" name="file" class="form-control" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx">
                    @if($kurikulum->nama_file)<small class="text-muted">File saat ini: {{ $kurikulum->nama_file }}</small>@endif
                </div>
            </div>
            <hr class="my-4">
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.kurikulum.index') }}" class="btn btn-outline-secondary">Batal</a>
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection
