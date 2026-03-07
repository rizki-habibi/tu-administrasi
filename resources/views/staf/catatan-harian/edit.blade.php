@extends('peran.staf.app')
@section('judul', 'Edit Catatan')

@section('konten')
<div class="d-flex align-items-center mb-4 gap-2">
    <a href="{{ route('staf.catatan-harian.show', $catatan) }}" class="btn btn-light btn-sm"><i class="bi bi-arrow-left"></i></a>
    <div>
        <h5 class="fw-bold mb-0"><i class="bi bi-journal-text text-primary me-2"></i>Edit Catatan</h5>
        <p class="text-muted mb-0" style="font-size:.82rem;">{{ $catatan->tanggal->translatedFormat('d F Y') }}</p>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('staf.catatan-harian.update', $catatan) }}">
            @csrf @method('PUT')

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Tanggal</label>
                    <input type="date" class="form-control" value="{{ $catatan->tanggal->format('Y-m-d') }}" disabled>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Status</label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror">
                        <option value="draft" {{ old('status', $catatan->status) == 'draft' ? 'selected' : '' }}>📝 Draft</option>
                        <option value="selesai" {{ old('status', $catatan->status) == 'selesai' ? 'selected' : '' }}>✅ Selesai</option>
                    </select>
                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Kegiatan Hari Ini <span class="text-danger">*</span></label>
                    <textarea name="kegiatan" class="form-control @error('kegiatan') is-invalid @enderror" rows="4" required>{{ old('kegiatan', $catatan->kegiatan) }}</textarea>
                    @error('kegiatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Hasil / Capaian</label>
                    <textarea name="hasil" class="form-control @error('hasil') is-invalid @enderror" rows="3">{{ old('hasil', $catatan->hasil) }}</textarea>
                    @error('hasil')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Kendala / Hambatan</label>
                    <textarea name="kendala" class="form-control @error('kendala') is-invalid @enderror" rows="2">{{ old('kendala', $catatan->kendala) }}</textarea>
                    @error('kendala')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Rencana Besok</label>
                    <textarea name="rencana_besok" class="form-control @error('rencana_besok') is-invalid @enderror" rows="2">{{ old('rencana_besok', $catatan->rencana_besok) }}</textarea>
                    @error('rencana_besok')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('staf.catatan-harian.show', $catatan) }}" class="btn btn-light">Batal</a>
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Update</button>
            </div>
        </form>
    </div>
</div>
@endsection
