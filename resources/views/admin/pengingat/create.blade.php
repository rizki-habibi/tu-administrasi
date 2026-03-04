@extends('peran.admin.app')
@section('judul', 'Buat Pengingat')

@section('konten')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('admin.pengingat.index') }}" class="btn btn-outline-secondary btn-sm me-3"><i class="bi bi-arrow-left"></i></a>
    <h4 class="fw-bold mb-0" style="color:#1e293b;">Buat Pengingat Baru</h4>
</div>

<div class="card">
    <div class="card-body p-4">
        <form action="{{ route('admin.pengingat.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label">Judul Pengingat <span class="text-danger">*</span></label>
                    <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror" value="{{ old('judul') }}" required placeholder="Contoh: Deadline Pengumpulan Rapor">
                    @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tipe <span class="text-danger">*</span></label>
                    <select name="jenis" class="form-select @error('jenis') is-invalid @enderror" required>
                        <option value="deadline_laporan" {{ old('jenis')=='deadline_laporan'?'selected':'' }}>Deadline Laporan</option>
                        <option value="bkd" {{ old('jenis')=='bkd'?'selected':'' }}>BKD</option>
                        <option value="evaluasi_semester" {{ old('jenis')=='evaluasi_semester'?'selected':'' }}>Evaluasi Semester</option>
                        <option value="tugas" {{ old('jenis')=='tugas'?'selected':'' }}>Tugas</option>
                        <option value="lainnya" {{ old('jenis')=='lainnya'?'selected':'' }}>Lainnya</option>
                    </select>
                    @error('jenis')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi') }}</textarea>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tanggal Deadline <span class="text-danger">*</span></label>
                    <input type="date" name="tenggat" class="form-control @error('tenggat') is-invalid @enderror" value="{{ old('tenggat') }}" required>
                    @error('tenggat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Waktu Pengingat</label>
                    <input type="time" name="waktu_pengingat" class="form-control" value="{{ old('waktu_pengingat') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Target <span class="text-danger">*</span></label>
                    <select name="target" class="form-select" id="targetSelect" required>
                        <option value="all">Semua Staff</option>
                        <option value="specific">Staff Tertentu</option>
                    </select>
                </div>
                <div class="col-md-6" id="targetUserDiv" style="display:none;">
                    <label class="form-label">Pilih Staff</label>
                    <select name="user_ids[]" class="form-select" multiple>
                        @foreach($staffs ?? [] as $staff)
                        <option value="{{ $staff->id }}">{{ $staff->nama }} - {{ $staff->jabatan ?? 'Staff' }}</option>
                        @endforeach
                    </select>
                    <small class="text-muted">Tahan Ctrl untuk memilih beberapa staff</small>
                </div>
            </div>
            <hr class="my-4">
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.pengingat.index') }}" class="btn btn-outline-secondary">Batal</a>
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('targetSelect').addEventListener('change', function(){
    document.getElementById('targetUserDiv').style.display = this.value === 'specific' ? 'block' : 'none';
});
</script>
@endpush
