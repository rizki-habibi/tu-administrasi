@extends('staf.tata-letak.app')
@section('judul', $jenis == 'masuk' ? 'Catat Surat Masuk' : 'Buat Surat Keluar')

@section('konten')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
        <h5 class="fw-bold mb-1">
            @if($jenis == 'masuk')
                <i class="bi bi-envelope-arrow-down text-success me-2"></i>Catat Surat Masuk
            @else
                <i class="bi bi-envelope-arrow-up text-primary me-2"></i>Buat Surat Keluar
            @endif
        </h5>
        <p class="text-muted mb-0" style="font-size:.82rem;">Nomor surat akan digenerate otomatis. Surat perlu persetujuan admin.</p>
    </div>
    <a href="{{ route('staf.surat.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="{{ route('staf.surat.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="jenis" value="{{ $jenis }}">

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-bold">Kategori <span class="text-danger">*</span></label>
                    <select name="kategori" class="form-select @error('kategori') is-invalid @enderror" required>
                        <option value="">-- Pilih --</option>
                        @foreach(['dinas'=>'Surat Dinas', 'undangan'=>'Undangan', 'keterangan'=>'Surat Keterangan', 'keputusan'=>'Surat Keputusan', 'edaran'=>'Surat Edaran', 'tugas'=>'Surat Tugas', 'pemberitahuan'=>'Pemberitahuan', 'lainnya'=>'Lainnya'] as $val => $label)
                            <option value="{{ $val }}" {{ old('kategori') == $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('kategori') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">Sifat <span class="text-danger">*</span></label>
                    <select name="sifat" class="form-select @error('sifat') is-invalid @enderror" required>
                        @foreach(['biasa'=>'Biasa', 'penting'=>'Penting', 'segera'=>'Segera', 'rahasia'=>'Rahasia'] as $val => $label)
                            <option value="{{ $val }}" {{ old('sifat', 'biasa') == $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('sifat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">Tanggal Surat <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_surat" class="form-control @error('tanggal_surat') is-invalid @enderror" value="{{ old('tanggal_surat', now()->toDateString()) }}" required>
                    @error('tanggal_surat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                @if($jenis == 'masuk')
                <div class="col-md-4">
                    <label class="form-label fw-bold">Tanggal Diterima</label>
                    <input type="date" name="tanggal_terima" class="form-control" value="{{ old('tanggal_terima', now()->toDateString()) }}">
                </div>
                <div class="col-md-8">
                    <label class="form-label fw-bold">Asal Surat</label>
                    <input type="text" name="asal" class="form-control" value="{{ old('asal') }}" placeholder="Instansi pengirim">
                </div>
                @else
                <div class="col-md-12">
                    <label class="form-label fw-bold">Tujuan</label>
                    <input type="text" name="tujuan" class="form-control" value="{{ old('tujuan') }}" placeholder="Pihak tujuan surat">
                </div>
                @endif

                <div class="col-12">
                    <label class="form-label fw-bold">Perihal <span class="text-danger">*</span></label>
                    <input type="text" name="perihal" class="form-control @error('perihal') is-invalid @enderror" value="{{ old('perihal') }}" required placeholder="Perihal / judul surat">
                    @error('perihal') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-12">
                    <label class="form-label fw-bold">Isi / Ringkasan</label>
                    <textarea name="isi" class="form-control" rows="5" placeholder="Isi ringkasan surat...">{{ old('isi') }}</textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Catatan</label>
                    <textarea name="catatan" class="form-control" rows="3" placeholder="Catatan internal">{{ old('catatan') }}</textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Lampiran</label>
                    <input type="file" name="file" class="form-control" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                    <small class="text-muted">Maks 10MB (PDF, DOC, JPG)</small>
                </div>
            </div>

            <hr class="my-4">
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-1"></i>Simpan Surat</button>
                <a href="{{ route('staf.surat.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
