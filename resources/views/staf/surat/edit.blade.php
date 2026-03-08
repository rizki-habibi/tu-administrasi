@extends('peran.staf.app')
@section('judul', 'Ubah Surat')

@section('konten')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-pencil"></i> Ubah Surat</h4>
    <a href="{{ route('staf.surat.show', $surat) }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="{{ route('staf.surat-kelola.update', $surat) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Jenis Surat <span class="text-danger">*</span></label>
                    <select name="jenis" class="form-select @error('jenis') is-invalid @enderror" required>
                        <option value="masuk" {{ old('jenis', $surat->jenis) == 'masuk' ? 'selected' : '' }}>Surat Masuk</option>
                        <option value="keluar" {{ old('jenis', $surat->jenis) == 'keluar' ? 'selected' : '' }}>Surat Keluar</option>
                    </select>
                    @error('jenis') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Kategori <span class="text-danger">*</span></label>
                    <select name="kategori" class="form-select @error('kategori') is-invalid @enderror" required>
                        @foreach(['dinas'=>'Dinas','undangan'=>'Undangan','keterangan'=>'Keterangan','keputusan'=>'Keputusan','edaran'=>'Edaran','tugas'=>'Tugas','pemberitahuan'=>'Pemberitahuan','lainnya'=>'Lainnya'] as $k => $v)
                            <option value="{{ $k }}" {{ old('kategori', $surat->kategori) == $k ? 'selected' : '' }}>{{ $v }}</option>
                        @endforeach
                    </select>
                    @error('kategori') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label fw-bold">Perihal <span class="text-danger">*</span></label>
                    <input type="text" name="perihal" class="form-control @error('perihal') is-invalid @enderror" value="{{ old('perihal', $surat->perihal) }}" required>
                    @error('perihal') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Tujuan</label>
                    <input type="text" name="tujuan" class="form-control @error('tujuan') is-invalid @enderror" value="{{ old('tujuan', $surat->tujuan) }}">
                    @error('tujuan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Asal</label>
                    <input type="text" name="asal" class="form-control @error('asal') is-invalid @enderror" value="{{ old('asal', $surat->asal) }}">
                    @error('asal') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Tanggal Surat <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_surat" class="form-control @error('tanggal_surat') is-invalid @enderror" value="{{ old('tanggal_surat', $surat->tanggal_surat?->format('Y-m-d')) }}" required>
                    @error('tanggal_surat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Tanggal Terima</label>
                    <input type="date" name="tanggal_terima" class="form-control @error('tanggal_terima') is-invalid @enderror" value="{{ old('tanggal_terima', $surat->tanggal_terima?->format('Y-m-d')) }}">
                    @error('tanggal_terima') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Sifat <span class="text-danger">*</span></label>
                    <select name="sifat" class="form-select @error('sifat') is-invalid @enderror" required>
                        @foreach(['biasa'=>'Biasa','penting'=>'Penting','segera'=>'Segera','rahasia'=>'Rahasia'] as $k => $v)
                            <option value="{{ $k }}" {{ old('sifat', $surat->sifat) == $k ? 'selected' : '' }}>{{ $v }}</option>
                        @endforeach
                    </select>
                    @error('sifat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label fw-bold">Isi Surat</label>
                    <textarea name="isi" class="form-control @error('isi') is-invalid @enderror" rows="5">{{ old('isi', $surat->isi) }}</textarea>
                    @error('isi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label fw-bold">Catatan</label>
                    <textarea name="catatan" class="form-control @error('catatan') is-invalid @enderror" rows="2">{{ old('catatan', $surat->catatan) }}</textarea>
                    @error('catatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">File <small class="text-muted">(opsional, maks 10MB)</small></label>
                    <input type="file" name="file" class="form-control @error('file') is-invalid @enderror" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                    @error('file') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    @if($surat->path_file)
                        <small class="text-muted mt-1 d-block"><i class="bi bi-paperclip"></i> File saat ini: <a href="{{ asset('storage/' . $surat->path_file) }}" target="_blank">{{ $surat->nama_file ?? 'Lihat' }}</a></small>
                    @endif
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Perbarui</button>
                <a href="{{ route('staf.surat.show', $surat) }}" class="btn btn-light ms-2">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
