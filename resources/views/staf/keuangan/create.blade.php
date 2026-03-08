@extends('peran.staf.app')
@section('judul', 'Tambah Catatan Keuangan')

@section('konten')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-plus-lg"></i> Tambah Catatan Keuangan</h4>
    <a href="{{ route('staf.keuangan.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="{{ route('staf.keuangan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Jenis <span class="text-danger">*</span></label>
                    <select name="jenis" class="form-select @error('jenis') is-invalid @enderror" required>
                        <option value="">-- Pilih --</option>
                        <option value="pemasukan" {{ old('jenis') == 'pemasukan' ? 'selected' : '' }}>Pemasukan</option>
                        <option value="pengeluaran" {{ old('jenis') == 'pengeluaran' ? 'selected' : '' }}>Pengeluaran</option>
                    </select>
                    @error('jenis') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Kategori <span class="text-danger">*</span></label>
                    <input type="text" name="kategori" class="form-control @error('kategori') is-invalid @enderror" value="{{ old('kategori') }}" placeholder="Misal: Gaji, ATK, Operasional..." required>
                    @error('kategori') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label fw-bold">Uraian <span class="text-danger">*</span></label>
                    <input type="text" name="uraian" class="form-control @error('uraian') is-invalid @enderror" value="{{ old('uraian') }}" required>
                    @error('uraian') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Jumlah (Rp) <span class="text-danger">*</span></label>
                    <input type="number" name="jumlah" class="form-control @error('jumlah') is-invalid @enderror" value="{{ old('jumlah') }}" min="0" step="1000" required>
                    @error('jumlah') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Tanggal <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                    @error('tanggal') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Bukti <small class="text-muted">(opsional, maks 5MB)</small></label>
                    <input type="file" name="bukti" class="form-control @error('bukti') is-invalid @enderror" accept=".jpg,.jpeg,.png,.pdf">
                    @error('bukti') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
                <a href="{{ route('staf.keuangan.index') }}" class="btn btn-light ms-2">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
