@extends('layouts.admin')
@section('title', 'Edit Surat - ' . $surat->nomor_surat)

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
        <h5 class="fw-bold mb-1"><i class="bi bi-pencil-square text-warning me-2"></i>Edit Surat</h5>
        <p class="text-muted mb-0" style="font-size:.82rem;">{{ $surat->nomor_surat }}</p>
    </div>
    <a href="{{ route('admin.surat.show', $surat) }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="{{ route('admin.surat.update', $surat) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')

            <div class="row g-3">
                <!-- Nomor Surat (readonly) -->
                <div class="col-md-4">
                    <label class="form-label fw-bold">Nomor Surat</label>
                    <input type="text" class="form-control bg-light" value="{{ $surat->nomor_surat }}" disabled>
                </div>

                <!-- Kategori -->
                <div class="col-md-4">
                    <label class="form-label fw-bold">Kategori <span class="text-danger">*</span></label>
                    <select name="kategori" class="form-select @error('kategori') is-invalid @enderror" required>
                        @foreach(['dinas'=>'Surat Dinas', 'undangan'=>'Undangan', 'keterangan'=>'Surat Keterangan', 'keputusan'=>'Surat Keputusan', 'edaran'=>'Surat Edaran', 'tugas'=>'Surat Tugas', 'pemberitahuan'=>'Pemberitahuan', 'lainnya'=>'Lainnya'] as $val => $label)
                            <option value="{{ $val }}" {{ old('kategori', $surat->kategori) == $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('kategori') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- Sifat -->
                <div class="col-md-4">
                    <label class="form-label fw-bold">Sifat <span class="text-danger">*</span></label>
                    <select name="sifat" class="form-select @error('sifat') is-invalid @enderror" required>
                        @foreach(['biasa'=>'Biasa', 'penting'=>'Penting', 'segera'=>'Segera', 'rahasia'=>'Rahasia'] as $val => $label)
                            <option value="{{ $val }}" {{ old('sifat', $surat->sifat) == $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('sifat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- Tanggal -->
                <div class="col-md-4">
                    <label class="form-label fw-bold">Tanggal Surat <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_surat" class="form-control @error('tanggal_surat') is-invalid @enderror" value="{{ old('tanggal_surat', $surat->tanggal_surat->toDateString()) }}" required>
                    @error('tanggal_surat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                @if($surat->jenis == 'masuk')
                <div class="col-md-4">
                    <label class="form-label fw-bold">Tanggal Diterima</label>
                    <input type="date" name="tanggal_terima" class="form-control @error('tanggal_terima') is-invalid @enderror" value="{{ old('tanggal_terima', $surat->tanggal_terima?->toDateString()) }}">
                    @error('tanggal_terima') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Asal Surat</label>
                    <input type="text" name="asal" class="form-control @error('asal') is-invalid @enderror" value="{{ old('asal', $surat->asal) }}">
                    @error('asal') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                @else
                <div class="col-md-8">
                    <label class="form-label fw-bold">Tujuan / Kepada</label>
                    <input type="text" name="tujuan" class="form-control @error('tujuan') is-invalid @enderror" value="{{ old('tujuan', $surat->tujuan) }}">
                    @error('tujuan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                @endif

                <!-- Perihal -->
                <div class="col-md-12">
                    <label class="form-label fw-bold">Perihal <span class="text-danger">*</span></label>
                    <input type="text" name="perihal" class="form-control @error('perihal') is-invalid @enderror" value="{{ old('perihal', $surat->perihal) }}" required>
                    @error('perihal') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- Isi -->
                <div class="col-12">
                    <label class="form-label fw-bold">Isi / Ringkasan</label>
                    <textarea name="isi" class="form-control @error('isi') is-invalid @enderror" rows="5">{{ old('isi', $surat->isi) }}</textarea>
                    @error('isi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- Catatan -->
                <div class="col-md-6">
                    <label class="form-label fw-bold">Catatan</label>
                    <textarea name="catatan" class="form-control @error('catatan') is-invalid @enderror" rows="3">{{ old('catatan', $surat->catatan) }}</textarea>
                    @error('catatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- File -->
                <div class="col-md-6">
                    <label class="form-label fw-bold">Lampiran / Scan</label>
                    @if($surat->file_path)
                    <div class="border rounded p-2 mb-2 d-flex align-items-center gap-2" style="font-size:.82rem;">
                        <i class="bi bi-file-earmark text-primary"></i>
                        <span>{{ $surat->file_name }}</span>
                        <a href="{{ asset('storage/' . $surat->file_path) }}" target="_blank" class="ms-auto"><i class="bi bi-eye"></i></a>
                    </div>
                    @endif
                    <input type="file" name="file" class="form-control @error('file') is-invalid @enderror" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                    <small class="text-muted">Kosongkan jika tidak ingin mengubah file</small>
                    @error('file') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <hr class="my-4">

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-1"></i>Simpan Perubahan</button>
                <a href="{{ route('admin.surat.show', $surat) }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
