@extends('peran.admin.app')
@section('judul', 'Buat Disposisi Surat')

@section('konten')
<div class="mb-4">
    <a href="{{ route('admin.disposisi.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <h6 class="fw-bold mb-0"><i class="bi bi-send-plus me-2 text-primary"></i>Buat Disposisi Surat Baru</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.disposisi.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-12">
                    <label class="form-label fw-bold">Pilih Surat Masuk <span class="text-danger">*</span></label>
                    <select name="surat_id" class="form-select @error('surat_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Surat --</option>
                        @foreach($suratList as $surat)
                            <option value="{{ $surat->id }}" {{ old('surat_id') == $surat->id ? 'selected' : '' }}>
                                [{{ $surat->nomor_surat }}] {{ $surat->perihal }} — {{ $surat->tanggal_surat->format('d/m/Y') }}
                            </option>
                        @endforeach
                    </select>
                    @error('surat_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Ditujukan Kepada <span class="text-danger">*</span></label>
                    <select name="kepada_pengguna_id" class="form-select @error('kepada_pengguna_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Staff --</option>
                        @foreach($staffList as $staff)
                            <option value="{{ $staff->id }}" {{ old('kepada_pengguna_id') == $staff->id ? 'selected' : '' }}>
                                {{ $staff->nama }} — {{ $staff->jabatan ?? $staff->role_label }}
                            </option>
                        @endforeach
                    </select>
                    @error('kepada_pengguna_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-bold">Prioritas <span class="text-danger">*</span></label>
                    <select name="prioritas" class="form-select @error('prioritas') is-invalid @enderror" required>
                        @foreach(['rendah'=>'🟢 Rendah','sedang'=>'🟡 Sedang','tinggi'=>'🟠 Tinggi','urgent'=>'🔴 Urgent'] as $val => $label)
                            <option value="{{ $val }}" {{ old('prioritas','sedang') == $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('prioritas') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-bold">Tenggat</label>
                    <input type="date" name="tenggat" class="form-control @error('tenggat') is-invalid @enderror" value="{{ old('tenggat') }}" min="{{ date('Y-m-d') }}">
                    @error('tenggat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-12">
                    <label class="form-label fw-bold">Instruksi <span class="text-danger">*</span></label>
                    <textarea name="instruksi" class="form-control @error('instruksi') is-invalid @enderror" rows="4" placeholder="Tuliskan instruksi atau arahan terkait surat ini..." required>{{ old('instruksi') }}</textarea>
                    @error('instruksi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('admin.disposisi.index') }}" class="btn btn-outline-secondary">Batal</a>
                <button type="submit" class="btn btn-primary"><i class="bi bi-send me-1"></i>Kirim Disposisi</button>
            </div>
        </form>
    </div>
</div>
@endsection
