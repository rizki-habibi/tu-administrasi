@extends('peran.staf.app')
@section('judul', 'Tambah Buku')

@section('konten')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-plus-lg"></i> Tambah Buku Baru</h4>
    <a href="{{ route('staf.buku.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="{{ route('staf.buku.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label fw-bold">Judul Buku <span class="text-danger">*</span></label>
                    <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror" value="{{ old('judul') }}" required>
                    @error('judul') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">ISBN</label>
                    <input type="text" name="isbn" class="form-control @error('isbn') is-invalid @enderror" value="{{ old('isbn') }}">
                    @error('isbn') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Pengarang <span class="text-danger">*</span></label>
                    <input type="text" name="pengarang" class="form-control @error('pengarang') is-invalid @enderror" value="{{ old('pengarang') }}" required>
                    @error('pengarang') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Penerbit</label>
                    <input type="text" name="penerbit" class="form-control @error('penerbit') is-invalid @enderror" value="{{ old('penerbit') }}">
                    @error('penerbit') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Tahun Terbit</label>
                    <input type="number" name="tahun_terbit" class="form-control @error('tahun_terbit') is-invalid @enderror" value="{{ old('tahun_terbit') }}" min="1900" max="{{ date('Y')+1 }}">
                    @error('tahun_terbit') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Kategori <span class="text-danger">*</span></label>
                    <select name="kategori" class="form-select @error('kategori') is-invalid @enderror" required>
                        <option value="">-- Pilih --</option>
                        @foreach(\App\Models\BukuPerpustakaan::KATEGORI as $k => $v)
                            <option value="{{ $k }}" {{ old('kategori') == $k ? 'selected' : '' }}>{{ $v }}</option>
                        @endforeach
                    </select>
                    @error('kategori') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Lokasi Rak</label>
                    <input type="text" name="lokasi_rak" class="form-control @error('lokasi_rak') is-invalid @enderror" value="{{ old('lokasi_rak') }}" placeholder="Misal: A1, B2">
                    @error('lokasi_rak') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Jumlah <span class="text-danger">*</span></label>
                    <input type="number" name="jumlah_total" class="form-control @error('jumlah_total') is-invalid @enderror" value="{{ old('jumlah_total', 1) }}" min="1" required>
                    @error('jumlah_total') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Harga (Rp)</label>
                    <input type="number" name="harga" class="form-control @error('harga') is-invalid @enderror" value="{{ old('harga') }}" min="0">
                    @error('harga') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Sumber Dana</label>
                    <select name="sumber_dana" class="form-select @error('sumber_dana') is-invalid @enderror">
                        <option value="">-- Pilih --</option>
                        @foreach(\App\Models\BukuPerpustakaan::SUMBER_DANA as $k => $v)
                            <option value="{{ $k }}" {{ old('sumber_dana') == $k ? 'selected' : '' }}>{{ $v }}</option>
                        @endforeach
                    </select>
                    @error('sumber_dana') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Kondisi <span class="text-danger">*</span></label>
                    <select name="kondisi" class="form-select @error('kondisi') is-invalid @enderror" required>
                        <option value="baik" {{ old('kondisi', 'baik') == 'baik' ? 'selected' : '' }}>Baik</option>
                        <option value="rusak_ringan" {{ old('kondisi') == 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                        <option value="rusak_berat" {{ old('kondisi') == 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
                    </select>
                    @error('kondisi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label fw-bold">Keterangan</label>
                    <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror" rows="2">{{ old('keterangan') }}</textarea>
                    @error('keterangan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
                <a href="{{ route('staf.buku.index') }}" class="btn btn-light ms-2">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
