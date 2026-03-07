@extends('peran.magang.app')
@section('judul', 'Edit Logbook')

@section('konten')
<div class="mb-4">
    <a href="{{ route('magang.logbook.index') }}" class="text-muted text-decoration-none"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 py-3">
        <h6 class="fw-bold mb-0"><i class="bi bi-pencil-square me-2 text-primary"></i>Edit Logbook — {{ $logbook->tanggal->format('d M Y') }}</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('magang.logbook.update', $logbook) }}" method="POST">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Tanggal</label>
                    <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror" value="{{ old('tanggal', $logbook->tanggal->format('Y-m-d')) }}">
                    @error('tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Jam Mulai</label>
                    <input type="time" name="jam_mulai" class="form-control @error('jam_mulai') is-invalid @enderror" value="{{ old('jam_mulai', $logbook->jam_mulai) }}">
                    @error('jam_mulai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Jam Selesai</label>
                    <input type="time" name="jam_selesai" class="form-control @error('jam_selesai') is-invalid @enderror" value="{{ old('jam_selesai', $logbook->jam_selesai) }}">
                    @error('jam_selesai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Kegiatan <span class="text-danger">*</span></label>
                    <textarea name="kegiatan" rows="4" class="form-control @error('kegiatan') is-invalid @enderror">{{ old('kegiatan', $logbook->kegiatan) }}</textarea>
                    @error('kegiatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Hasil</label>
                    <textarea name="hasil" rows="3" class="form-control @error('hasil') is-invalid @enderror">{{ old('hasil', $logbook->hasil) }}</textarea>
                    @error('hasil')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Kendala</label>
                    <textarea name="kendala" rows="3" class="form-control @error('kendala') is-invalid @enderror">{{ old('kendala', $logbook->kendala) }}</textarea>
                    @error('kendala')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Rencana Besok</label>
                    <textarea name="rencana_besok" rows="2" class="form-control @error('rencana_besok') is-invalid @enderror">{{ old('rencana_besok', $logbook->rencana_besok) }}</textarea>
                    @error('rencana_besok')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Status</label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror">
                        <option value="draft" {{ old('status', $logbook->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="final" {{ old('status', $logbook->status) === 'final' ? 'selected' : '' }}>Final</option>
                    </select>
                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            @if($logbook->catatan_pembimbing)
            <div class="alert alert-light mt-3 border" style="border-left:4px solid #0891b2 !important;">
                <strong><i class="bi bi-chat-quote me-1"></i>Catatan Pembimbing:</strong>
                <p class="mb-0 mt-1">{{ $logbook->catatan_pembimbing }}</p>
            </div>
            @endif

            <hr>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle me-1"></i>Perbarui</button>
                <a href="{{ route('magang.logbook.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
