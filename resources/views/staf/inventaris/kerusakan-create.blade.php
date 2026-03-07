@extends('peran.staf.app')
@section('judul', 'Buat Laporan Kerusakan')

@section('konten')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('staf.kerusakan.index') }}" class="btn btn-outline-secondary btn-sm me-3"><i class="bi bi-arrow-left"></i></a>
    <h4 class="fw-bold mb-0" style="color:#1e293b;">Buat Laporan Kerusakan</h4>
</div>

<div class="card">
    <div class="card-body p-4">
        <form action="{{ route('staf.kerusakan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Barang Inventaris <span class="text-danger">*</span></label>
                    <select name="inventaris_id" class="form-select @error('inventaris_id') is-invalid @enderror" required>
                        <option value="">Pilih Barang</option>
                        @foreach($inventaris as $item)
                        <option value="{{ $item->id }}" {{ old('inventaris_id')==$item->id?'selected':'' }}>
                            {{ $item->kode_barang }} - {{ $item->nama_barang }}
                        </option>
                        @endforeach
                    </select>
                    @error('inventaris_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tingkat Kerusakan <span class="text-danger">*</span></label>
                    <select name="tingkat_kerusakan" class="form-select @error('tingkat_kerusakan') is-invalid @enderror" required>
                        <option value="ringan" {{ old('tingkat_kerusakan')=='ringan'?'selected':'' }}>Ringan</option>
                        <option value="sedang" {{ old('tingkat_kerusakan')=='sedang'?'selected':'' }}>Sedang</option>
                        <option value="berat" {{ old('tingkat_kerusakan')=='berat'?'selected':'' }}>Berat</option>
                    </select>
                    @error('tingkat_kerusakan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Deskripsi Kerusakan <span class="text-danger">*</span></label>
                    <textarea name="deskripsi_kerusakan" class="form-control @error('deskripsi_kerusakan') is-invalid @enderror" rows="4" required>{{ old('deskripsi_kerusakan') }}</textarea>
                    @error('deskripsi_kerusakan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Foto Kerusakan</label>
                    <input type="file" name="foto" class="form-control" accept="image/*">
                    <small class="text-muted">Maks 5MB, format JPG/PNG</small>
                </div>
            </div>
            <hr class="my-4">
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('staf.kerusakan.index') }}" class="btn btn-outline-secondary">Batal</a>
                <button type="submit" class="btn btn-primary"><i class="bi bi-send me-1"></i> Kirim Laporan</button>
            </div>
        </form>
    </div>
</div>
@endsection
