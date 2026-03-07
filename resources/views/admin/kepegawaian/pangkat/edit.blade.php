@extends('peran.admin.app')
@section('judul', 'Edit Riwayat Pangkat')

@section('konten')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
        <h5 class="fw-bold mb-1">Edit Riwayat Pangkat</h5>
        <p class="text-muted mb-0" style="font-size:.82rem;">Ubah data pangkat: {{ $pangkat->pangkat }} ({{ $pangkat->golongan }})</p>
    </div>
    <a href="{{ route('admin.kepegawaian.pangkat.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.kepegawaian.pangkat.update', $pangkat) }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-medium" style="font-size:.85rem;">Pegawai <span class="text-danger">*</span></label>
                    <select name="pengguna_id" class="form-select form-select-sm @error('pengguna_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Pegawai --</option>
                        @foreach($pegawaiList as $p)
                            <option value="{{ $p->id }}" {{ old('pengguna_id', $pangkat->pengguna_id) == $p->id ? 'selected' : '' }}>{{ $p->nama }}</option>
                        @endforeach
                    </select>
                    @error('pengguna_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-medium" style="font-size:.85rem;">Pangkat <span class="text-danger">*</span></label>
                    <input type="text" name="pangkat" class="form-control form-control-sm @error('pangkat') is-invalid @enderror" value="{{ old('pangkat', $pangkat->pangkat) }}" required>
                    @error('pangkat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-medium" style="font-size:.85rem;">Golongan <span class="text-danger">*</span></label>
                    <input type="text" name="golongan" class="form-control form-control-sm @error('golongan') is-invalid @enderror" value="{{ old('golongan', $pangkat->golongan) }}" required>
                    @error('golongan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-medium" style="font-size:.85rem;">TMT Pangkat <span class="text-danger">*</span></label>
                    <input type="date" name="tmt_pangkat" class="form-control form-control-sm @error('tmt_pangkat') is-invalid @enderror" value="{{ old('tmt_pangkat', $pangkat->tmt_pangkat->format('Y-m-d')) }}" required>
                    @error('tmt_pangkat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-medium" style="font-size:.85rem;">Jenis Kenaikan</label>
                    <select name="jenis_kenaikan" class="form-select form-select-sm @error('jenis_kenaikan') is-invalid @enderror">
                        <option value="">-- Pilih --</option>
                        <option value="reguler" {{ old('jenis_kenaikan', $pangkat->jenis_kenaikan) == 'reguler' ? 'selected' : '' }}>Reguler</option>
                        <option value="pilihan" {{ old('jenis_kenaikan', $pangkat->jenis_kenaikan) == 'pilihan' ? 'selected' : '' }}>Pilihan</option>
                        <option value="penyesuaian" {{ old('jenis_kenaikan', $pangkat->jenis_kenaikan) == 'penyesuaian' ? 'selected' : '' }}>Penyesuaian</option>
                    </select>
                    @error('jenis_kenaikan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-medium" style="font-size:.85rem;">Nomor SK</label>
                    <input type="text" name="nomor_sk" class="form-control form-control-sm @error('nomor_sk') is-invalid @enderror" value="{{ old('nomor_sk', $pangkat->nomor_sk) }}">
                    @error('nomor_sk')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-medium" style="font-size:.85rem;">Tanggal SK</label>
                    <input type="date" name="tanggal_sk" class="form-control form-control-sm @error('tanggal_sk') is-invalid @enderror" value="{{ old('tanggal_sk', $pangkat->tanggal_sk?->format('Y-m-d')) }}">
                    @error('tanggal_sk')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-medium" style="font-size:.85rem;">Pejabat Penetap</label>
                    <input type="text" name="pejabat_penetap" class="form-control form-control-sm @error('pejabat_penetap') is-invalid @enderror" value="{{ old('pejabat_penetap', $pangkat->pejabat_penetap) }}">
                    @error('pejabat_penetap')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-medium" style="font-size:.85rem;">File SK <small class="text-muted">(PDF/JPG/PNG, maks 5MB)</small></label>
                    <input type="file" name="file_sk" class="form-control form-control-sm @error('file_sk') is-invalid @enderror" accept=".pdf,.jpg,.jpeg,.png">
                    @if($pangkat->file_sk)
                        <small class="text-muted"><i class="bi bi-paperclip"></i> File saat ini: <a href="{{ Storage::url($pangkat->file_sk) }}" target="_blank">Lihat</a></small>
                    @endif
                    @error('file_sk')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label fw-medium" style="font-size:.85rem;">Keterangan</label>
                    <textarea name="keterangan" class="form-control form-control-sm @error('keterangan') is-invalid @enderror" rows="2">{{ old('keterangan', $pangkat->keterangan) }}</textarea>
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
