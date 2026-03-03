@extends('layouts.admin')
@section('title', 'Tambah Barang Inventaris')

@section('content')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('admin.inventaris.index') }}" class="btn btn-outline-secondary btn-sm me-3"><i class="bi bi-arrow-left"></i></a>
    <div><h4 class="fw-bold mb-0" style="color:#1e293b;">Tambah Barang Inventaris</h4></div>
</div>

<div class="card">
    <div class="card-body p-4">
        <form action="{{ route('admin.inventaris.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nama Barang <span class="text-danger">*</span></label>
                    <input type="text" name="nama_barang" class="form-control @error('nama_barang') is-invalid @enderror" value="{{ old('nama_barang') }}" required>
                    @error('nama_barang')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Kategori <span class="text-danger">*</span></label>
                    <select name="kategori" class="form-select @error('kategori') is-invalid @enderror" required>
                        <option value="">Pilih</option>
                        @foreach(['mebeler','elektronik','alat_peraga','olahraga','laboratorium','kantor','lainnya'] as $kat)
                        <option value="{{ $kat }}" {{ old('kategori')==$kat?'selected':'' }}>{{ ucfirst(str_replace('_',' ',$kat)) }}</option>
                        @endforeach
                    </select>
                    @error('kategori')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Kondisi</label>
                    <select name="kondisi" class="form-select">
                        <option value="baik">Baik</option>
                        <option value="rusak_ringan">Rusak Ringan</option>
                        <option value="rusak_berat">Rusak Berat</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Lokasi</label>
                    <input type="text" name="lokasi" class="form-control" value="{{ old('lokasi') }}" placeholder="Contoh: Ruang Guru, Lab IPA">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Jumlah <span class="text-danger">*</span></label>
                    <input type="number" name="jumlah" class="form-control" value="{{ old('jumlah', 1) }}" min="0" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Satuan</label>
                    <input type="text" name="satuan" class="form-control" value="{{ old('satuan', 'pcs') }}" placeholder="pcs, unit">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Sumber Dana</label>
                    <input type="text" name="sumber_dana" class="form-control" value="{{ old('sumber_dana') }}" placeholder="BOS, APBD, dll">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tahun Pengadaan</label>
                    <input type="text" name="tahun_pengadaan" class="form-control" value="{{ old('tahun_pengadaan', date('Y')) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Harga Satuan (Rp)</label>
                    <input type="number" name="harga_satuan" class="form-control" value="{{ old('harga_satuan') }}" min="0">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Foto Barang</label>
                    <input type="file" name="photo" class="form-control" accept="image/*">
                </div>
                <div class="col-12">
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan" class="form-control" rows="2">{{ old('keterangan') }}</textarea>
                </div>
            </div>
            <hr class="my-4">
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.inventaris.index') }}" class="btn btn-outline-secondary">Batal</a>
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
