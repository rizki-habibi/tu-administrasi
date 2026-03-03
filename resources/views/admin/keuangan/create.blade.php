@extends('layouts.admin')
@section('title', 'Tambah Transaksi')

@section('content')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('admin.keuangan.index') }}" class="btn btn-outline-secondary btn-sm me-3"><i class="bi bi-arrow-left"></i></a>
    <h4 class="fw-bold mb-0" style="color:#1e293b;">Tambah Transaksi Keuangan</h4>
</div>

<div class="card">
    <div class="card-body p-4">
        <form action="{{ route('admin.keuangan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Jenis Transaksi <span class="text-danger">*</span></label>
                    <select name="jenis" class="form-select @error('jenis') is-invalid @enderror" required>
                        <option value="pemasukan" {{ old('jenis')=='pemasukan'?'selected':'' }}>Pemasukan</option>
                        <option value="pengeluaran" {{ old('jenis')=='pengeluaran'?'selected':'' }}>Pengeluaran</option>
                    </select>
                    @error('jenis')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Kategori <span class="text-danger">*</span></label>
                    <select name="kategori" class="form-select @error('kategori') is-invalid @enderror" required>
                        <option value="">Pilih Kategori</option>
                        <option value="bos" {{ old('kategori')=='bos'?'selected':'' }}>Dana BOS</option>
                        <option value="spp" {{ old('kategori')=='spp'?'selected':'' }}>SPP</option>
                        <option value="gaji" {{ old('kategori')=='gaji'?'selected':'' }}>Gaji</option>
                        <option value="operasional" {{ old('kategori')=='operasional'?'selected':'' }}>Operasional</option>
                        <option value="pengadaan" {{ old('kategori')=='pengadaan'?'selected':'' }}>Pengadaan Barang</option>
                        <option value="pemeliharaan" {{ old('kategori')=='pemeliharaan'?'selected':'' }}>Pemeliharaan</option>
                        <option value="kegiatan" {{ old('kategori')=='kegiatan'?'selected':'' }}>Kegiatan</option>
                        <option value="lainnya" {{ old('kategori')=='lainnya'?'selected':'' }}>Lainnya</option>
                    </select>
                    @error('kategori')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                    @error('tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Jumlah (Rp) <span class="text-danger">*</span></label>
                    <input type="number" name="jumlah" class="form-control @error('jumlah') is-invalid @enderror" value="{{ old('jumlah') }}" min="0" required>
                    @error('jumlah')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Sumber Dana</label>
                    <input type="text" name="sumber_dana" class="form-control" value="{{ old('sumber_dana') }}" placeholder="BOS, APBD, Komite, dll">
                </div>
                <div class="col-12">
                    <label class="form-label">Keterangan <span class="text-danger">*</span></label>
                    <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror" rows="3" required>{{ old('keterangan') }}</textarea>
                    @error('keterangan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Bukti / Kuitansi</label>
                    <input type="file" name="bukti" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                    <small class="text-muted">Format: PDF, JPG, PNG. Max 5MB</small>
                </div>
            </div>
            <hr class="my-4">
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.keuangan.index') }}" class="btn btn-outline-secondary">Batal</a>
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
