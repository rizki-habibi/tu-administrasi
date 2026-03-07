@extends('peran.kepala-sekolah.app')
@section('judul', 'Edit Resolusi')

@section('konten')
<div class="mb-4"><a href="{{ route('kepala-sekolah.resolusi.show', $resolusi) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Kembali</a></div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3"><h6 class="fw-bold mb-0"><i class="bi bi-pencil-square me-2 text-primary"></i>Edit Resolusi</h6></div>
    <div class="card-body">
        <form action="{{ route('kepala-sekolah.resolusi.update', $resolusi) }}" method="POST">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Nomor Resolusi</label>
                    <input type="text" class="form-control" value="{{ $resolusi->nomor_resolusi }}" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Kategori <span class="text-danger">*</span></label>
                    <select name="kategori" class="form-select" required>
                        @foreach(['kebijakan','sanksi','penghargaan','mutasi','anggaran','kurikulum','lainnya'] as $k)
                            <option value="{{ $k }}" {{ old('kategori', $resolusi->kategori) == $k ? 'selected' : '' }}>{{ ucfirst($k) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label fw-bold">Judul <span class="text-danger">*</span></label>
                    <input type="text" name="judul" class="form-control" value="{{ old('judul', $resolusi->judul) }}" required>
                </div>
                <div class="col-12">
                    <label class="form-label fw-bold">Latar Belakang <span class="text-danger">*</span></label>
                    <textarea name="latar_belakang" class="form-control" rows="4" required>{{ old('latar_belakang', $resolusi->latar_belakang) }}</textarea>
                </div>
                <div class="col-12">
                    <label class="form-label fw-bold">Isi Keputusan <span class="text-danger">*</span></label>
                    <textarea name="isi_keputusan" class="form-control" rows="5" required>{{ old('isi_keputusan', $resolusi->isi_keputusan) }}</textarea>
                </div>
                <div class="col-12">
                    <label class="form-label fw-bold">Tindak Lanjut</label>
                    <textarea name="tindak_lanjut" class="form-control" rows="3">{{ old('tindak_lanjut', $resolusi->tindak_lanjut) }}</textarea>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Status</label>
                    <select name="status" class="form-select" required>
                        @foreach(['draft','berlaku','dicabut'] as $s)
                            <option value="{{ $s }}" {{ old('status', $resolusi->status) == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Tanggal Berlaku</label>
                    <input type="date" name="tanggal_berlaku" class="form-control" value="{{ old('tanggal_berlaku', $resolusi->tanggal_berlaku->format('Y-m-d')) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Tanggal Berakhir</label>
                    <input type="date" name="tanggal_berakhir" class="form-control" value="{{ old('tanggal_berakhir', $resolusi->tanggal_berakhir?->format('Y-m-d')) }}">
                </div>
            </div>
            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('kepala-sekolah.resolusi.show', $resolusi) }}" class="btn btn-outline-secondary">Batal</a>
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection
