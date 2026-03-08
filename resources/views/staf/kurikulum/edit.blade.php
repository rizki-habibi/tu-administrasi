@extends('peran.staf.app')
@section('judul', 'Edit Dokumen Kurikulum')

@section('konten')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-pencil-square"></i> Edit Dokumen Kurikulum</h4>
    <a href="{{ route('staf.kurikulum.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="{{ route('staf.kurikulum-kelola.update', $dokumen) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label fw-bold">Judul <span class="text-danger">*</span></label>
                    <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror" value="{{ old('judul', $dokumen->judul) }}" required>
                    @error('judul') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Jenis <span class="text-danger">*</span></label>
                    <select name="jenis" class="form-select @error('jenis') is-invalid @enderror" required>
                        <option value="">-- Pilih --</option>
                        @foreach(['silabus'=>'Silabus','rpp'=>'RPP','prota'=>'Prota','prosem'=>'Prosem','kkm'=>'KKM','ki_kd'=>'KI/KD','lainnya'=>'Lainnya'] as $k=>$v)
                            <option value="{{ $k }}" {{ old('jenis', $dokumen->jenis) == $k ? 'selected' : '' }}>{{ $v }}</option>
                        @endforeach
                    </select>
                    @error('jenis') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Tahun Ajaran</label>
                    <input type="text" name="tahun_ajaran" class="form-control @error('tahun_ajaran') is-invalid @enderror" value="{{ old('tahun_ajaran', $dokumen->tahun_ajaran) }}" placeholder="2024/2025">
                    @error('tahun_ajaran') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Semester</label>
                    <select name="semester" class="form-select @error('semester') is-invalid @enderror">
                        <option value="">-- Pilih --</option>
                        <option value="ganjil" {{ old('semester', $dokumen->semester) == 'ganjil' ? 'selected' : '' }}>Ganjil</option>
                        <option value="genap" {{ old('semester', $dokumen->semester) == 'genap' ? 'selected' : '' }}>Genap</option>
                    </select>
                    @error('semester') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Mata Pelajaran</label>
                    <input type="text" name="mata_pelajaran" class="form-control @error('mata_pelajaran') is-invalid @enderror" value="{{ old('mata_pelajaran', $dokumen->mata_pelajaran) }}">
                    @error('mata_pelajaran') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label fw-bold">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" rows="3">{{ old('deskripsi', $dokumen->deskripsi) }}</textarea>
                    @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Ganti File <small class="text-muted">(maks 10MB, kosongkan jika tidak)</small></label>
                    <input type="file" name="file" class="form-control @error('file') is-invalid @enderror">
                    @error('file') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    @if($dokumen->path_file)
                        <small class="text-muted mt-1 d-block">File saat ini: {{ $dokumen->nama_file }}</small>
                    @endif
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
                <a href="{{ route('staf.kurikulum.index') }}" class="btn btn-light ms-2">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
