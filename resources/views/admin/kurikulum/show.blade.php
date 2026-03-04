@extends('admin.tata-letak.app')
@section('judul', 'Detail Dokumen Kurikulum')

@section('konten')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('admin.kurikulum.index') }}" class="btn btn-outline-secondary btn-sm me-3"><i class="bi bi-arrow-left"></i></a>
    <div>
        <h4 class="fw-bold mb-0" style="color:#1e293b;">Detail Dokumen</h4>
    </div>
</div>

<div class="card">
    <div class="card-body p-4">
        <div class="row g-3">
            <div class="col-md-8"><label class="form-label text-muted">Judul</label><p class="fw-semibold">{{ $kurikulum->judul }}</p></div>
            <div class="col-md-4"><label class="form-label text-muted">Status</label><p>{!! $kurikulum->status_badge !!}</p></div>
            <div class="col-md-4"><label class="form-label text-muted">Jenis</label><p>{{ strtoupper(str_replace('_',' ',$kurikulum->jenis)) }}</p></div>
            <div class="col-md-4"><label class="form-label text-muted">Tahun Ajaran</label><p>{{ $kurikulum->tahun_ajaran ?? '-' }}</p></div>
            <div class="col-md-4"><label class="form-label text-muted">Semester</label><p>{{ $kurikulum->semester ? 'Semester '.$kurikulum->semester : '-' }}</p></div>
            <div class="col-md-4"><label class="form-label text-muted">Mata Pelajaran</label><p>{{ $kurikulum->mata_pelajaran ?? '-' }}</p></div>
            <div class="col-md-4"><label class="form-label text-muted">Kelas</label><p>{{ $kurikulum->tingkat_kelas ?? '-' }}</p></div>
            <div class="col-md-4"><label class="form-label text-muted">Diunggah</label><p>{{ $kurikulum->created_at->format('d/m/Y H:i') }}</p></div>
            @if($kurikulum->deskripsi)
            <div class="col-12"><label class="form-label text-muted">Deskripsi</label><p>{{ $kurikulum->deskripsi }}</p></div>
            @endif
            @if($kurikulum->path_file)
            <div class="col-12">
                <label class="form-label text-muted">File</label>
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ asset('storage/'.$kurikulum->path_file) }}" class="btn btn-primary" target="_blank"><i class="bi bi-download me-1"></i> Unduh {{ $kurikulum->nama_file }}</a>
                    @if($kurikulum->ukuran_file)<small class="text-muted">{{ number_format($kurikulum->ukuran_file/1024, 1) }} KB</small>@endif
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
