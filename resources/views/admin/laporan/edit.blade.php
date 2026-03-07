@extends('peran.admin.app')
@section('judul', 'Edit Laporan')

@section('konten')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0"><i class="bi bi-pencil-square me-2"></i>Edit Laporan</h5>
    <a href="{{ route('admin.laporan.index') }}" class="btn btn-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="{{ route('admin.laporan.update', $report) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-12">
                    <label class="form-label">Judul <span class="text-danger">*</span></label>
                    <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror" value="{{ old('judul', $report->judul) }}" required>
                    @error('judul') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Kategori <span class="text-danger">*</span></label>
                    <select name="kategori" class="form-select @error('kategori') is-invalid @enderror" required>
                        <option value="">Pilih Kategori</option>
                        @foreach(['surat_masuk','surat_keluar','inventaris','keuangan','kegiatan','lainnya'] as $c)
                            <option value="{{ $c }}" {{ old('kategori', $report->kategori) == $c ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$c)) }}</option>
                        @endforeach
                    </select>
                    @error('kategori') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Prioritas <span class="text-danger">*</span></label>
                    <select name="prioritas" class="form-select @error('prioritas') is-invalid @enderror" required>
                        <option value="">Pilih Prioritas</option>
                        @foreach(['rendah','sedang','tinggi'] as $p)
                            <option value="{{ $p }}" {{ old('prioritas', $report->prioritas) == $p ? 'selected' : '' }}>{{ ucfirst($p) }}</option>
                        @endforeach
                    </select>
                    @error('prioritas') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                        @foreach(['draft','submitted','reviewed','completed'] as $s)
                            <option value="{{ $s }}" {{ old('status', $report->status) == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-12">
                    <label class="form-label">Deskripsi <span class="text-danger">*</span></label>
                    <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" rows="5" required>{{ old('deskripsi', $report->deskripsi) }}</textarea>
                    @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-12">
                    <label class="form-label">Lampiran</label>
                    @if($report->lampiran)
                        <div class="mb-2">
                            <a href="{{ asset('storage/' . $report->lampiran) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-paperclip me-1"></i> Lihat Lampiran Saat Ini
                            </a>
                        </div>
                    @endif
                    <input type="file" name="lampiran" class="form-control @error('lampiran') is-invalid @enderror" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.png">
                    <small class="text-muted">Kosongkan jika tidak ingin mengubah lampiran. Maks 5MB</small>
                    @error('lampiran') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Perbarui</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
