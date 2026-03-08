@extends('peran.staf.app')
@section('judul', 'Edit Dokumen Kepegawaian')

@section('konten')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-pencil-square"></i> Edit Dokumen Kepegawaian</h4>
    <a href="{{ route('staf.dok-kepegawaian.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="{{ route('staf.dok-kepegawaian.update', $dokumen) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Pegawai <span class="text-danger">*</span></label>
                    <select name="pengguna_id" class="form-select @error('pengguna_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Pegawai --</option>
                        @foreach($pegawai as $p)
                            <option value="{{ $p->id }}" {{ old('pengguna_id', $dokumen->pengguna_id) == $p->id ? 'selected' : '' }}>{{ $p->nama }}</option>
                        @endforeach
                    </select>
                    @error('pengguna_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Kategori <span class="text-danger">*</span></label>
                    <select name="kategori" class="form-select @error('kategori') is-invalid @enderror" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach(\App\Models\DokumenKepegawaian::KATEGORI as $k => $v)
                            <option value="{{ $k }}" {{ old('kategori', $dokumen->kategori) == $k ? 'selected' : '' }}>{{ $v }}</option>
                        @endforeach
                    </select>
                    @error('kategori') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-8">
                    <label class="form-label fw-bold">Judul <span class="text-danger">*</span></label>
                    <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror" value="{{ old('judul', $dokumen->judul) }}" required>
                    @error('judul') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Nomor Dokumen</label>
                    <input type="text" name="nomor_dokumen" class="form-control @error('nomor_dokumen') is-invalid @enderror" value="{{ old('nomor_dokumen', $dokumen->nomor_dokumen) }}">
                    @error('nomor_dokumen') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Tanggal Dokumen</label>
                    <input type="date" name="tanggal_dokumen" class="form-control @error('tanggal_dokumen') is-invalid @enderror" value="{{ old('tanggal_dokumen', $dokumen->tanggal_dokumen?->format('Y-m-d')) }}">
                    @error('tanggal_dokumen') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-8">
                    <label class="form-label fw-bold">Keterangan</label>
                    <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror" rows="2">{{ old('keterangan', $dokumen->keterangan) }}</textarea>
                    @error('keterangan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Ganti File <small class="text-muted">(kosongkan jika tidak)</small></label>
                    <input type="file" name="file" class="form-control @error('file') is-invalid @enderror">
                    @error('file') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    @if($dokumen->file_path)
                        <small class="text-muted mt-1 d-block">File saat ini: {{ basename($dokumen->file_path) }}</small>
                    @endif
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
                <a href="{{ route('staf.dok-kepegawaian.index') }}" class="btn btn-light ms-2">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
