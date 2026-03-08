@extends('peran.staf.app')
@section('judul', 'Ajukan Peminjaman Fasilitas')

@section('konten')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-plus-lg"></i> Peminjaman Fasilitas</h4>
    <a href="{{ route('staf.peminjaman.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="{{ route('staf.peminjaman.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Jenis Fasilitas <span class="text-danger">*</span></label>
                    <select name="jenis" class="form-select @error('jenis') is-invalid @enderror" required id="jenisFasilitas">
                        <option value="">-- Pilih Jenis --</option>
                        @foreach(\App\Models\PeminjamanFasilitas::JENIS as $k=>$v)
                            <option value="{{ $k }}" {{ old('jenis') == $k ? 'selected' : '' }}>{{ $v }}</option>
                        @endforeach
                    </select>
                    @error('jenis') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Nama Fasilitas <span class="text-danger">*</span></label>
                    <select name="nama_fasilitas" class="form-select @error('nama_fasilitas') is-invalid @enderror" required>
                        <option value="">-- Pilih Fasilitas --</option>
                        @foreach(\App\Models\PeminjamanFasilitas::FASILITAS as $f)
                            <option value="{{ $f }}" {{ old('nama_fasilitas') == $f ? 'selected' : '' }}>{{ $f }}</option>
                        @endforeach
                    </select>
                    @error('nama_fasilitas') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Nama Peminjam <span class="text-danger">*</span></label>
                    <input type="text" name="peminjam_nama" class="form-control @error('peminjam_nama') is-invalid @enderror" value="{{ old('peminjam_nama', auth()->user()->nama) }}" required>
                    @error('peminjam_nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Penanggung Jawab</label>
                    <input type="text" name="penanggung_jawab" class="form-control @error('penanggung_jawab') is-invalid @enderror" value="{{ old('penanggung_jawab') }}">
                    @error('penanggung_jawab') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Tanggal <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror" value="{{ old('tanggal') }}" min="{{ date('Y-m-d') }}" required>
                    @error('tanggal') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Jam Mulai <span class="text-danger">*</span></label>
                    <input type="time" name="jam_mulai" class="form-control @error('jam_mulai') is-invalid @enderror" value="{{ old('jam_mulai') }}" required>
                    @error('jam_mulai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Jam Selesai <span class="text-danger">*</span></label>
                    <input type="time" name="jam_selesai" class="form-control @error('jam_selesai') is-invalid @enderror" value="{{ old('jam_selesai') }}" required>
                    @error('jam_selesai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label fw-bold">Keperluan <span class="text-danger">*</span></label>
                    <textarea name="keperluan" class="form-control @error('keperluan') is-invalid @enderror" rows="3" required>{{ old('keperluan') }}</textarea>
                    @error('keperluan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label fw-bold">Catatan</label>
                    <textarea name="catatan" class="form-control @error('catatan') is-invalid @enderror" rows="2">{{ old('catatan') }}</textarea>
                    @error('catatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-send"></i> Ajukan</button>
                <a href="{{ route('staf.peminjaman.index') }}" class="btn btn-light ms-2">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
