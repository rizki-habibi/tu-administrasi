@extends('peran.magang.app')
@section('judul', 'Ajukan Izin')

@section('konten')
<div class="mb-4">
    <a href="{{ route('magang.izin.index') }}" class="text-muted text-decoration-none"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 py-3">
        <h6 class="fw-bold mb-0"><i class="bi bi-envelope-plus me-2 text-primary"></i>Ajukan Izin / Sakit</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('magang.izin.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Jenis <span class="text-danger">*</span></label>
                    <select name="jenis" class="form-select @error('jenis') is-invalid @enderror">
                        <option value="izin" {{ old('jenis') === 'izin' ? 'selected' : '' }}>Izin</option>
                        <option value="sakit" {{ old('jenis') === 'sakit' ? 'selected' : '' }}>Sakit</option>
                    </select>
                    @error('jenis')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Tanggal Mulai <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_mulai" class="form-control @error('tanggal_mulai') is-invalid @enderror" value="{{ old('tanggal_mulai', date('Y-m-d')) }}">
                    @error('tanggal_mulai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Tanggal Selesai <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_selesai" class="form-control @error('tanggal_selesai') is-invalid @enderror" value="{{ old('tanggal_selesai', date('Y-m-d')) }}">
                    @error('tanggal_selesai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Alasan <span class="text-danger">*</span></label>
                    <textarea name="alasan" rows="4" class="form-control @error('alasan') is-invalid @enderror" placeholder="Jelaskan alasan izin / sakit...">{{ old('alasan') }}</textarea>
                    @error('alasan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Lampiran (opsional)</label>
                    <input type="file" name="lampiran" class="form-control @error('lampiran') is-invalid @enderror" accept=".jpg,.jpeg,.png,.pdf">
                    <small class="text-muted">Surat keterangan / bukti (JPG, PNG, PDF, maks 2MB)</small>
                    @error('lampiran')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <hr>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-send me-1"></i>Ajukan</button>
                <a href="{{ route('magang.izin.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
