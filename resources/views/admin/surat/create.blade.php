@extends('layouts.admin')
@section('title', $jenis == 'masuk' ? 'Catat Surat Masuk' : 'Buat Surat Keluar')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
        <h5 class="fw-bold mb-1">
            @if($jenis == 'masuk')
                <i class="bi bi-envelope-arrow-down text-success me-2"></i>Catat Surat Masuk
            @else
                <i class="bi bi-envelope-arrow-up text-primary me-2"></i>Buat Surat Keluar
            @endif
        </h5>
        <p class="text-muted mb-0" style="font-size:.82rem;">Nomor surat akan digenerate otomatis oleh sistem</p>
    </div>
    <a href="{{ route('admin.surat.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="{{ route('admin.surat.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="jenis" value="{{ $jenis }}">

            <div class="row g-3">
                <!-- Kategori Surat -->
                <div class="col-md-4">
                    <label class="form-label fw-bold">Kategori Surat <span class="text-danger">*</span></label>
                    <select name="kategori" class="form-select @error('kategori') is-invalid @enderror" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach(['dinas'=>'Surat Dinas', 'undangan'=>'Undangan', 'keterangan'=>'Surat Keterangan', 'keputusan'=>'Surat Keputusan', 'edaran'=>'Surat Edaran', 'tugas'=>'Surat Tugas', 'pemberitahuan'=>'Pemberitahuan', 'lainnya'=>'Lainnya'] as $val => $label)
                            <option value="{{ $val }}" {{ old('kategori') == $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('kategori') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- Sifat Surat -->
                <div class="col-md-4">
                    <label class="form-label fw-bold">Sifat Surat <span class="text-danger">*</span></label>
                    <select name="sifat" class="form-select @error('sifat') is-invalid @enderror" required>
                        @foreach(['biasa'=>'Biasa', 'penting'=>'Penting', 'segera'=>'Segera', 'rahasia'=>'Rahasia'] as $val => $label)
                            <option value="{{ $val }}" {{ old('sifat', 'biasa') == $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('sifat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- Tanggal Surat -->
                <div class="col-md-4">
                    <label class="form-label fw-bold">Tanggal Surat <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_surat" class="form-control @error('tanggal_surat') is-invalid @enderror" value="{{ old('tanggal_surat', now()->toDateString()) }}" required>
                    @error('tanggal_surat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                @if($jenis == 'masuk')
                <!-- Tanggal Terima -->
                <div class="col-md-4">
                    <label class="form-label fw-bold">Tanggal Diterima</label>
                    <input type="date" name="tanggal_terima" class="form-control @error('tanggal_terima') is-invalid @enderror" value="{{ old('tanggal_terima', now()->toDateString()) }}">
                    @error('tanggal_terima') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <!-- Asal Surat -->
                <div class="col-md-8">
                    <label class="form-label fw-bold">Asal Surat</label>
                    <input type="text" name="asal" class="form-control @error('asal') is-invalid @enderror" value="{{ old('asal') }}" placeholder="Instansi / organisasi pengirim">
                    @error('asal') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                @else
                <!-- Tujuan Surat -->
                <div class="col-md-12">
                    <label class="form-label fw-bold">Tujuan / Kepada</label>
                    <input type="text" name="tujuan" class="form-control @error('tujuan') is-invalid @enderror" value="{{ old('tujuan') }}" placeholder="Instansi / organisasi / pihak tujuan">
                    @error('tujuan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                @endif

                <!-- Perihal -->
                <div class="col-md-12">
                    <label class="form-label fw-bold">Perihal <span class="text-danger">*</span></label>
                    <input type="text" name="perihal" class="form-control @error('perihal') is-invalid @enderror" value="{{ old('perihal') }}" placeholder="Perihal / judul surat" required>
                    @error('perihal') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- Isi Surat -->
                <div class="col-12">
                    <label class="form-label fw-bold">Isi / Ringkasan Surat</label>
                    <textarea name="isi" class="form-control @error('isi') is-invalid @enderror" rows="5" placeholder="Tuliskan isi atau ringkasan surat...">{{ old('isi') }}</textarea>
                    @error('isi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- Catatan -->
                <div class="col-md-6">
                    <label class="form-label fw-bold">Catatan Tambahan</label>
                    <textarea name="catatan" class="form-control @error('catatan') is-invalid @enderror" rows="3" placeholder="Catatan internal (opsional)">{{ old('catatan') }}</textarea>
                    @error('catatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- File Lampiran -->
                <div class="col-md-6">
                    <label class="form-label fw-bold">Lampiran / Scan Surat</label>
                    <input type="file" name="file" class="form-control @error('file') is-invalid @enderror" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                    <small class="text-muted">Format: PDF, DOC, DOCX, JPG, PNG. Maks 10MB</small>
                    @error('file') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <hr class="my-4">

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-1"></i>Simpan Surat</button>
                <a href="{{ route('admin.surat.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
