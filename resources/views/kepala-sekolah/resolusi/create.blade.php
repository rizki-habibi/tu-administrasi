@extends('peran.kepala-sekolah.app')
@section('judul', 'Buat Resolusi')

@section('konten')
<div class="mb-4"><a href="{{ route('kepala-sekolah.resolusi.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Kembali</a></div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <h6 class="fw-bold mb-0"><i class="bi bi-journal-plus me-2 text-primary"></i>Buat Resolusi / Keputusan Baru</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('kepala-sekolah.resolusi.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Nomor Resolusi</label>
                    <input type="text" class="form-control" value="{{ $nomorOtomatis }}" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Kategori <span class="text-danger">*</span></label>
                    <select name="kategori" class="form-select @error('kategori') is-invalid @enderror" required>
                        @foreach(['kebijakan'=>'📋 Kebijakan','sanksi'=>'⚠️ Sanksi','penghargaan'=>'🏆 Penghargaan','mutasi'=>'🔄 Mutasi','anggaran'=>'💰 Anggaran','kurikulum'=>'📚 Kurikulum','lainnya'=>'📌 Lainnya'] as $v => $l)
                            <option value="{{ $v }}" {{ old('kategori') == $v ? 'selected' : '' }}>{{ $l }}</option>
                        @endforeach
                    </select>
                    @error('kategori') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label fw-bold">Judul <span class="text-danger">*</span></label>
                    <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror" value="{{ old('judul') }}" required>
                    @error('judul') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label fw-bold">Latar Belakang <span class="text-danger">*</span></label>
                    <textarea name="latar_belakang" class="form-control @error('latar_belakang') is-invalid @enderror" rows="4" required>{{ old('latar_belakang') }}</textarea>
                    @error('latar_belakang') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label fw-bold">Isi Keputusan <span class="text-danger">*</span></label>
                    <textarea name="isi_keputusan" class="form-control @error('isi_keputusan') is-invalid @enderror" rows="5" required>{{ old('isi_keputusan') }}</textarea>
                    @error('isi_keputusan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label fw-bold">Tindak Lanjut</label>
                    <textarea name="tindak_lanjut" class="form-control" rows="3">{{ old('tindak_lanjut') }}</textarea>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select" required>
                        <option value="draft" {{ old('status')=='draft' ? 'selected' : '' }}>Draft</option>
                        <option value="berlaku" {{ old('status')=='berlaku' ? 'selected' : '' }}>Berlaku</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Tanggal Berlaku <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_berlaku" class="form-control @error('tanggal_berlaku') is-invalid @enderror" value="{{ old('tanggal_berlaku', date('Y-m-d')) }}" required>
                    @error('tanggal_berlaku') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Tanggal Berakhir</label>
                    <input type="date" name="tanggal_berakhir" class="form-control" value="{{ old('tanggal_berakhir') }}">
                </div>
            </div>
            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('kepala-sekolah.resolusi.index') }}" class="btn btn-outline-secondary">Batal</a>
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Simpan Resolusi</button>
            </div>
        </form>
    </div>
</div>
@endsection
