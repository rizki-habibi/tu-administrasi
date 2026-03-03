@extends('layouts.admin')
@section('title', 'Detail Dokumen Kurikulum')

@section('content')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('admin.kurikulum.index') }}" class="btn btn-outline-secondary btn-sm me-3"><i class="bi bi-arrow-left"></i></a>
    <div>
        <h4 class="fw-bold mb-0" style="color:#1e293b;">Detail Dokumen</h4>
    </div>
</div>

<div class="card">
    <div class="card-body p-4">
        <div class="row g-3">
            <div class="col-md-8"><label class="form-label text-muted">Judul</label><p class="fw-semibold">{{ $kurikulum->title }}</p></div>
            <div class="col-md-4"><label class="form-label text-muted">Status</label><p>{!! $kurikulum->status_badge !!}</p></div>
            <div class="col-md-4"><label class="form-label text-muted">Jenis</label><p>{{ strtoupper(str_replace('_',' ',$kurikulum->type)) }}</p></div>
            <div class="col-md-4"><label class="form-label text-muted">Tahun Ajaran</label><p>{{ $kurikulum->academic_year ?? '-' }}</p></div>
            <div class="col-md-4"><label class="form-label text-muted">Semester</label><p>{{ $kurikulum->semester ? 'Semester '.$kurikulum->semester : '-' }}</p></div>
            <div class="col-md-4"><label class="form-label text-muted">Mata Pelajaran</label><p>{{ $kurikulum->subject ?? '-' }}</p></div>
            <div class="col-md-4"><label class="form-label text-muted">Kelas</label><p>{{ $kurikulum->class_level ?? '-' }}</p></div>
            <div class="col-md-4"><label class="form-label text-muted">Diunggah</label><p>{{ $kurikulum->created_at->format('d/m/Y H:i') }}</p></div>
            @if($kurikulum->description)
            <div class="col-12"><label class="form-label text-muted">Deskripsi</label><p>{{ $kurikulum->description }}</p></div>
            @endif
            @if($kurikulum->file_path)
            <div class="col-12">
                <label class="form-label text-muted">File</label>
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ asset('storage/'.$kurikulum->file_path) }}" class="btn btn-primary" target="_blank"><i class="bi bi-download me-1"></i> Unduh {{ $kurikulum->file_name }}</a>
                    @if($kurikulum->file_size)<small class="text-muted">{{ number_format($kurikulum->file_size/1024, 1) }} KB</small>@endif
                </div>
            </div>
            @endif
        </div>
        <hr class="my-4">
        <div class="d-flex gap-2">
            <a href="{{ route('admin.kurikulum.edit', $kurikulum) }}" class="btn btn-warning"><i class="bi bi-pencil me-1"></i> Edit</a>
            <form action="{{ route('admin.kurikulum.destroy', $kurikulum) }}" method="POST">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-outline-danger" data-confirm="Hapus dokumen ini?"><i class="bi bi-trash me-1"></i> Hapus</button>
            </form>
        </div>
    </div>
</div>
@endsection
