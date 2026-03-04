@extends('staf.tata-letak.app')
@section('judul', 'Preview: ' . $word->judul)

@section('konten')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
        <h5 class="fw-bold mb-1"><i class="bi bi-eye-fill text-primary me-2"></i>Preview Dokumen</h5>
        <p class="text-muted mb-0" style="font-size:.82rem;">{{ $word->judul }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('staf.word-ai.unduh', $word) }}" class="btn btn-info text-white"><i class="bi bi-download me-1"></i>Download .docx</a>
        @if($word->pengguna_id == auth()->id())
            <a href="{{ route('staf.word-ai.edit', $word) }}" class="btn btn-outline-success"><i class="bi bi-pencil me-1"></i>Edit</a>
        @endif
        <a href="{{ route('staf.word-ai.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="bg-light p-3 d-flex justify-content-between align-items-center border-bottom">
                    <small class="text-muted"><i class="bi bi-file-earmark-word me-1"></i>{{ $word->judul }}</small>
                    <div>
                        @if($word->status=='draft')
                            <span class="badge bg-warning-subtle text-warning">Draft</span>
                        @elseif($word->status=='final')
                            <span class="badge bg-success-subtle text-success">Final</span>
                        @else
                            <span class="badge bg-secondary-subtle text-secondary">Arsip</span>
                        @endif
                    </div>
                </div>
                <div class="d-flex justify-content-center p-4" style="background:#e5e7eb;">
                    <div style="width:210mm;min-height:297mm;background:#fff;padding:25.4mm 25.4mm 25.4mm 31.7mm;box-shadow:0 2px 12px rgba(0,0,0,.12);font-family:'Times New Roman',serif;font-size:12pt;line-height:1.6;">
                        @if($word->konten)
                            {!! $word->konten !!}
                        @else
                            <p class="text-muted text-center mt-5">Dokumen ini belum memiliki konten.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white border-0 py-3"><h6 class="fw-bold mb-0"><i class="bi bi-info-circle me-2 text-primary"></i>Informasi Dokumen</h6></div>
            <div class="card-body">
                <table class="table table-borderless table-sm mb-0">
                    <tr><td class="text-muted" style="width:35%;">Judul</td><td class="fw-semibold">{{ $word->judul }}</td></tr>
                    <tr><td class="text-muted">Kategori</td><td><span class="badge bg-primary-subtle text-primary">{{ App\Models\WordDocument::categories()[$word->kategori] ?? $word->kategori }}</span></td></tr>
                    <tr><td class="text-muted">Status</td><td>
                        @if($word->status=='draft')<span class="badge bg-warning-subtle text-warning">Draft</span>
                        @elseif($word->status=='final')<span class="badge bg-success-subtle text-success">Final</span>
                        @else <span class="badge bg-secondary-subtle text-secondary">Arsip</span>@endif
                    </td></tr>
                    <tr><td class="text-muted">Dibagikan</td><td>{{ $word->dibagikan ? 'Ya' : 'Tidak' }}</td></tr>
                    <tr><td class="text-muted">Pembuat</td><td>{{ $word->user->nama ?? '-' }}</td></tr>
                    <tr><td class="text-muted">Dibuat</td><td>{{ $word->created_at->format('d/m/Y H:i') }}</td></tr>
                    <tr><td class="text-muted">Diubah</td><td>{{ $word->updated_at->format('d/m/Y H:i') }}</td></tr>
                    @if($word->templat)
                    <tr><td class="text-muted">Template</td><td>{{ App\Models\WordDocument::templates()[$word->templat]['nama'] ?? $word->templat }}</td></tr>
                    @endif
                    @if($word->prompt_ai)
                    <tr><td class="text-muted">AI Prompt</td><td><small>{{ $word->prompt_ai }}</small></td></tr>
                    @endif
                </table>
            </div>
        </div>
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <a href="{{ route('staf.word-ai.unduh', $word) }}" class="btn btn-info text-white w-100 mb-2"><i class="bi bi-file-earmark-arrow-down me-1"></i>Download .docx</a>
                @if($word->pengguna_id == auth()->id())
                    <a href="{{ route('staf.word-ai.edit', $word) }}" class="btn btn-outline-success w-100 mb-2"><i class="bi bi-pencil me-1"></i>Edit Dokumen</a>
                    <form action="{{ route('staf.word-ai.destroy', $word) }}" method="POST" onsubmit="return false;" id="deleteForm">
                        @csrf @method('DELETE')
                        <button type="button" class="btn btn-outline-danger w-100 btn-delete"><i class="bi bi-trash me-1"></i>Hapus</button>
                    </form>
                @endif
                <button onclick="window.print()" class="btn btn-outline-primary w-100 mt-2"><i class="bi bi-printer me-1"></i>Print</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.querySelector('.btn-delete')?.addEventListener('click', function() {
    Swal.fire({
        title: 'Hapus Dokumen?', text: 'Tidak dapat dikembalikan!', icon: 'warning',
        showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonText: 'Batal', confirmButtonText: 'Ya, Hapus!'
    }).then(r => { if(r.isConfirmed) document.getElementById('deleteForm').submit(); });
});
</script>
@endpush