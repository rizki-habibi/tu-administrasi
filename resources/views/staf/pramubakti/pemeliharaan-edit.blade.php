@extends('peran.staf.app')
@section('judul', 'Edit Laporan Pemeliharaan')

@section('konten')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-pencil-square"></i> Edit Laporan Pemeliharaan</h4>
    <a href="{{ route('staf.pemeliharaan.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="{{ route('staf.pemeliharaan.update', $laporan) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label fw-bold">Judul <span class="text-danger">*</span></label>
                    <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror" value="{{ old('judul', $laporan->judul) }}" required>
                    @error('judul') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Prioritas <span class="text-danger">*</span></label>
                    <select name="prioritas" class="form-select @error('prioritas') is-invalid @enderror" required>
                        <option value="rendah" {{ old('prioritas', $laporan->prioritas) == 'rendah' ? 'selected' : '' }}>Rendah</option>
                        <option value="sedang" {{ old('prioritas', $laporan->prioritas) == 'sedang' ? 'selected' : '' }}>Sedang</option>
                        <option value="tinggi" {{ old('prioritas', $laporan->prioritas) == 'tinggi' ? 'selected' : '' }}>Tinggi</option>
                    </select>
                    @error('prioritas') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label fw-bold">Deskripsi <span class="text-danger">*</span></label>
                    <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" rows="4" required>{{ old('deskripsi', $laporan->deskripsi) }}</textarea>
                    @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Ganti Lampiran <small class="text-muted">(kosongkan jika tidak)</small></label>
                    <input type="file" name="lampiran" class="form-control @error('lampiran') is-invalid @enderror">
                    @error('lampiran') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    @if($laporan->lampiran)
                        <small class="text-muted mt-1 d-block">File saat ini: {{ basename($laporan->lampiran) }}</small>
                    @endif
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
                <a href="{{ route('staf.pemeliharaan.index') }}" class="btn btn-light ms-2">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
