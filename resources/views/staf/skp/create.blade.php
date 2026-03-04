@extends('peran.staf.app')
@section('judul', 'Buat SKP')

@section('konten')
<div class="mb-4">
    <h5 class="fw-bold mb-1">Buat SKP Baru</h5>
    <p class="text-muted mb-0" style="font-size:.85rem;">Isi formulir sasaran kinerja pegawai</p>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('staf.skp.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Periode <span class="text-danger">*</span></label>
                    <select name="periode" class="form-select @error('periode') is-invalid @enderror" required>
                        <option value="">Pilih Periode</option>
                        <option value="januari_juni" {{ old('periode') == 'januari_juni' ? 'selected' : '' }}>Januari - Juni</option>
                        <option value="juli_desember" {{ old('periode') == 'juli_desember' ? 'selected' : '' }}>Juli - Desember</option>
                    </select>
                    @error('periode')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tahun <span class="text-danger">*</span></label>
                    <input type="number" name="tahun" class="form-control @error('tahun') is-invalid @enderror" value="{{ old('tahun', now()->year) }}" min="2020" max="2035" required>
                    @error('tahun')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Sasaran Kinerja <span class="text-danger">*</span></label>
                    <textarea name="sasaran_kinerja" class="form-control @error('sasaran_kinerja') is-invalid @enderror" rows="3" placeholder="Uraian sasaran kinerja yang ingin dicapai..." required>{{ old('sasaran_kinerja') }}</textarea>
                    @error('sasaran_kinerja')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Indikator Kinerja <span class="text-danger">*</span></label>
                    <textarea name="indikator_kinerja" class="form-control @error('indikator_kinerja') is-invalid @enderror" rows="3" placeholder="Indikator pengukuran keberhasilan..." required>{{ old('indikator_kinerja') }}</textarea>
                    @error('indikator_kinerja')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-12"><hr class="my-1"><h6 class="fw-bold" style="font-size:.9rem;">Target Kinerja</h6></div>
                <div class="col-md-4">
                    <label class="form-label">Target Kuantitas</label>
                    <input type="number" step="0.01" name="target_kuantitas" class="form-control" value="{{ old('target_kuantitas') }}" placeholder="Jumlah target">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Target Kualitas (%)</label>
                    <input type="number" step="0.01" name="target_kualitas" class="form-control" value="{{ old('target_kualitas') }}" placeholder="0-100" max="100">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Target Waktu (hari)</label>
                    <input type="number" step="0.01" name="target_waktu" class="form-control" value="{{ old('target_waktu') }}" placeholder="Jumlah hari">
                </div>

                <div class="col-12"><hr class="my-1"><h6 class="fw-bold" style="font-size:.9rem;">Realisasi Kinerja</h6></div>
                <div class="col-md-4">
                    <label class="form-label">Realisasi Kuantitas</label>
                    <input type="number" step="0.01" name="realisasi_kuantitas" class="form-control" value="{{ old('realisasi_kuantitas') }}" placeholder="Jumlah realisasi">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Realisasi Kualitas (%)</label>
                    <input type="number" step="0.01" name="realisasi_kualitas" class="form-control" value="{{ old('realisasi_kualitas') }}" placeholder="0-100" max="100">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Realisasi Waktu (hari)</label>
                    <input type="number" step="0.01" name="realisasi_waktu" class="form-control" value="{{ old('realisasi_waktu') }}" placeholder="Jumlah hari">
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Simpan Draf</button>
                <a href="{{ route('staf.skp.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
