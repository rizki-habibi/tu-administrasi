@extends('peran.admin.app')
@section('judul', 'Edit Riwayat Jabatan')

@section('konten')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
        <h5 class="fw-bold mb-1">Edit Riwayat Jabatan</h5>
        <p class="text-muted mb-0" style="font-size:.82rem;">Ubah data riwayat jabatan: {{ $jabatan->nama_jabatan }}</p>
    </div>
    <a href="{{ route('admin.kepegawaian.jabatan.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.kepegawaian.jabatan.update', $jabatan) }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-medium" style="font-size:.85rem;">Pegawai <span class="text-danger">*</span></label>
                    <select name="pengguna_id" class="form-select form-select-sm @error('pengguna_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Pegawai --</option>
                        @foreach($pegawaiList as $p)
                            <option value="{{ $p->id }}" {{ old('pengguna_id', $jabatan->pengguna_id) == $p->id ? 'selected' : '' }}>{{ $p->nama }}</option>
                        @endforeach
                    </select>
                    @error('pengguna_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium" style="font-size:.85rem;">Nama Jabatan <span class="text-danger">*</span></label>
                    <input type="text" name="nama_jabatan" class="form-control form-control-sm @error('nama_jabatan') is-invalid @enderror" value="{{ old('nama_jabatan', $jabatan->nama_jabatan) }}" required>
                    @error('nama_jabatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium" style="font-size:.85rem;">Unit Kerja</label>
                    <input type="text" name="unit_kerja" class="form-control form-control-sm @error('unit_kerja') is-invalid @enderror" value="{{ old('unit_kerja', $jabatan->unit_kerja) }}">
                    @error('unit_kerja')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-medium" style="font-size:.85rem;">TMT Jabatan <span class="text-danger">*</span></label>
                    <input type="date" name="tmt_jabatan" class="form-control form-control-sm @error('tmt_jabatan') is-invalid @enderror" value="{{ old('tmt_jabatan', $jabatan->tmt_jabatan->format('Y-m-d')) }}" required>
                    @error('tmt_jabatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-medium" style="font-size:.85rem;">TMT Selesai</label>
                    <input type="date" name="tmt_selesai" class="form-control form-control-sm @error('tmt_selesai') is-invalid @enderror" value="{{ old('tmt_selesai', $jabatan->tmt_selesai?->format('Y-m-d')) }}">
                    @error('tmt_selesai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-medium" style="font-size:.85rem;">Nomor SK</label>
                    <input type="text" name="nomor_sk" class="form-control form-control-sm @error('nomor_sk') is-invalid @enderror" value="{{ old('nomor_sk', $jabatan->nomor_sk) }}">
                    @error('nomor_sk')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-medium" style="font-size:.85rem;">Tanggal SK</label>
                    <input type="date" name="tanggal_sk" class="form-control form-control-sm @error('tanggal_sk') is-invalid @enderror" value="{{ old('tanggal_sk', $jabatan->tanggal_sk?->format('Y-m-d')) }}">
                    @error('tanggal_sk')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-medium" style="font-size:.85rem;">Pejabat Penetap</label>
                    <input type="text" name="pejabat_penetap" class="form-control form-control-sm @error('pejabat_penetap') is-invalid @enderror" value="{{ old('pejabat_penetap', $jabatan->pejabat_penetap) }}">
                    @error('pejabat_penetap')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium" style="font-size:.85rem;">File SK <small class="text-muted">(PDF/JPG/PNG, maks 5MB)</small></label>
                    <input type="file" name="file_sk" class="form-control form-control-sm @error('file_sk') is-invalid @enderror" accept=".pdf,.jpg,.jpeg,.png">
                    @if($jabatan->file_sk)
                        <small class="text-muted"><i class="bi bi-paperclip"></i> File saat ini: <a href="{{ Storage::url($jabatan->file_sk) }}" target="_blank">Lihat</a></small>
                    @endif
                    @error('file_sk')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium" style="font-size:.85rem;">Keterangan</label>
                    <textarea name="keterangan" class="form-control form-control-sm @error('keterangan') is-invalid @enderror" rows="2">{{ old('keterangan', $jabatan->keterangan) }}</textarea>
                    @error('keterangan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-check-lg me-1"></i>Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection
