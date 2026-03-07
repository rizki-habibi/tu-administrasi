@extends('peran.admin.app')
@section('judul', 'Detail Analisis STAR')

@section('konten')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('admin.evaluasi.star') }}" class="btn btn-outline-secondary btn-sm me-3"><i class="bi bi-arrow-left"></i></a>
    <div>
        <h4 class="fw-bold mb-0" style="color:#1e293b;">{{ $star->judul }}</h4>
        <small class="text-muted">{{ $star->creator->nama ?? '-' }} &middot; {{ $star->created_at->translatedFormat('d F Y H:i') }}</small>
    </div>
    <div class="ms-auto d-flex gap-2">
        <a href="{{ route('admin.evaluasi.star.edit', $star) }}" class="btn btn-outline-warning btn-sm"><i class="bi bi-pencil me-1"></i> Edit</a>
        <form action="{{ route('admin.evaluasi.star.destroy', $star) }}" method="POST" class="d-inline">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-outline-danger btn-sm" data-confirm="Pindahkan ke sampah?"><i class="bi bi-trash me-1"></i> Hapus</button>
        </form>
    </div>
</div>

<div class="row g-3 mb-4">
    @php $sections = [
        ['S', 'Situasi', $star->situasi, '#6366f1', 'bi-geo-alt'],
        ['T', 'Tugas', $star->tugas, '#f59e0b', 'bi-list-task'],
        ['A', 'Aksi', $star->aksi, '#10b981', 'bi-lightning'],
        ['R', 'Hasil', $star->hasil, '#ec4899', 'bi-trophy'],
    ]; @endphp
    @foreach($sections as $s)
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header bg-white d-flex align-items-center gap-2">
                <span class="badge" style="background:{{ $s[3] }};font-size:.85rem;">{{ $s[0] }}</span>
                <h6 class="fw-bold mb-0">{{ $s[1] }}</h6>
            </div>
            <div class="card-body">
                <p class="mb-0" style="white-space:pre-line;font-size:.85rem;">{{ $s[2] ?? '-' }}</p>
            </div>
        </div>
    </div>
    @endforeach
</div>

@if($star->refleksi || $star->tindak_lanjut)
<div class="row g-3 mb-4">
    @if($star->refleksi)
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white"><h6 class="fw-bold mb-0"><i class="bi bi-journal-text me-2 text-info"></i>Refleksi</h6></div>
            <div class="card-body"><p class="mb-0" style="white-space:pre-line;font-size:.85rem;">{{ $star->refleksi }}</p></div>
        </div>
    </div>
    @endif
    @if($star->tindak_lanjut)
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white"><h6 class="fw-bold mb-0"><i class="bi bi-arrow-right-circle me-2 text-warning"></i>Tindak Lanjut</h6></div>
            <div class="card-body"><p class="mb-0" style="white-space:pre-line;font-size:.85rem;">{{ $star->tindak_lanjut }}</p></div>
        </div>
    </div>
    @endif
</div>
@endif

@if($star->path_file)
<div class="card">
    <div class="card-body d-flex align-items-center gap-3">
        <i class="bi bi-file-earmark-arrow-down" style="font-size:1.5rem;color:#6366f1;"></i>
        <div>
            <strong>File Pendukung</strong><br>
            <a href="{{ asset('storage/' . $star->path_file) }}" target="_blank" class="text-primary" style="font-size:.85rem;">Unduh File</a>
        </div>
    </div>
</div>
@endif
@endsection
