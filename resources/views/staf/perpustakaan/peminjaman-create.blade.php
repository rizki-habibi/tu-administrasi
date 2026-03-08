@extends('peran.staf.app')
@section('judul', 'Catat Peminjaman Buku')

@section('konten')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-plus-lg"></i> Catat Peminjaman Buku</h4>
    <a href="{{ route('staf.peminjaman-buku.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="{{ route('staf.peminjaman-buku.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-12">
                    <label class="form-label fw-bold">Buku <span class="text-danger">*</span></label>
                    <select name="buku_id" class="form-select @error('buku_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Buku --</option>
                        @foreach($bukuTersedia as $b)
                            <option value="{{ $b->id }}" {{ old('buku_id') == $b->id ? 'selected' : '' }}>
                                {{ $b->kode_buku }} - {{ $b->judul }} (tersedia: {{ $b->jumlah_tersedia }})
                            </option>
                        @endforeach
                    </select>
                    @error('buku_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Nama Peminjam <span class="text-danger">*</span></label>
                    <input type="text" name="nama_peminjam" class="form-control @error('nama_peminjam') is-invalid @enderror" value="{{ old('nama_peminjam') }}" required>
                    @error('nama_peminjam') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Kelas</label>
                    <input type="text" name="kelas" class="form-control @error('kelas') is-invalid @enderror" value="{{ old('kelas') }}" placeholder="Misal: X IPA 1">
                    @error('kelas') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Tanggal Pinjam <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_pinjam" class="form-control @error('tanggal_pinjam') is-invalid @enderror" value="{{ old('tanggal_pinjam', date('Y-m-d')) }}" required>
                    @error('tanggal_pinjam') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Tanggal Kembali (Rencana) <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_kembali_rencana" class="form-control @error('tanggal_kembali_rencana') is-invalid @enderror" value="{{ old('tanggal_kembali_rencana') }}" required>
                    @error('tanggal_kembali_rencana') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label fw-bold">Catatan</label>
                    <textarea name="catatan" class="form-control @error('catatan') is-invalid @enderror" rows="2">{{ old('catatan') }}</textarea>
                    @error('catatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
                <a href="{{ route('staf.peminjaman-buku.index') }}" class="btn btn-light ms-2">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
