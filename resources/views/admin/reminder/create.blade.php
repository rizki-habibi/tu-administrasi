@extends('layouts.admin')
@section('title', 'Buat Pengingat')

@section('content')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('admin.reminder.index') }}" class="btn btn-outline-secondary btn-sm me-3"><i class="bi bi-arrow-left"></i></a>
    <h4 class="fw-bold mb-0" style="color:#1e293b;">Buat Pengingat Baru</h4>
</div>

<div class="card">
    <div class="card-body p-4">
        <form action="{{ route('admin.reminder.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label">Judul Pengingat <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required placeholder="Contoh: Deadline Pengumpulan Rapor">
                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tipe <span class="text-danger">*</span></label>
                    <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                        <option value="deadline_laporan" {{ old('type')=='deadline_laporan'?'selected':'' }}>Deadline Laporan</option>
                        <option value="bkd" {{ old('type')=='bkd'?'selected':'' }}>BKD</option>
                        <option value="evaluasi_semester" {{ old('type')=='evaluasi_semester'?'selected':'' }}>Evaluasi Semester</option>
                        <option value="tugas" {{ old('type')=='tugas'?'selected':'' }}>Tugas</option>
                        <option value="lainnya" {{ old('type')=='lainnya'?'selected':'' }}>Lainnya</option>
                    </select>
                    @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tanggal Deadline <span class="text-danger">*</span></label>
                    <input type="date" name="due_date" class="form-control @error('due_date') is-invalid @enderror" value="{{ old('due_date') }}" required>
                    @error('due_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Waktu Pengingat</label>
                    <input type="time" name="reminder_time" class="form-control" value="{{ old('reminder_time') }}">
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
                        <option value="{{ $staff->id }}">{{ $staff->name }} - {{ $staff->position ?? 'Staff' }}</option>
                        @endforeach
                    </select>
                    <small class="text-muted">Tahan Ctrl untuk memilih beberapa staff</small>
                </div>
            </div>
            <hr class="my-4">
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.reminder.index') }}" class="btn btn-outline-secondary">Batal</a>
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
