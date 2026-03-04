@extends('staf.tata-letak.app')
@section('judul', 'Word & AI Dokumen')

@section('konten')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
        <h5 class="fw-bold mb-1"><i class="bi bi-file-earmark-word-fill text-primary me-2"></i>Word & AI Dokumen</h5>
        <p class="text-muted mb-0" style="font-size:.82rem;">Buat dokumen otomatis dengan AI, edit, dan unduh sebagai Word (.docx)</p>
    </div>
    <a href="{{ route('staf.word-ai.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Buat Dokumen Baru</a>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm"><div class="card-body text-center py-3">
            <i class="bi bi-files fs-4 text-primary"></i>
            <h4 class="fw-bold mb-0 mt-1">{{ $documents->total() }}</h4>
            <small class="text-muted">Total Dokumen</small>
        </div></div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm"><div class="card-body text-center py-3">
            <i class="bi bi-pencil-square fs-4 text-warning"></i>
            <h4 class="fw-bold mb-0 mt-1">{{ App\Models\WordDocument::accessible(auth()->id())->where('status','draft')->count() }}</h4>
            <small class="text-muted">Draft</small>
        </div></div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm"><div class="card-body text-center py-3">
            <i class="bi bi-check-circle fs-4 text-success"></i>
            <h4 class="fw-bold mb-0 mt-1">{{ App\Models\WordDocument::accessible(auth()->id())->where('status','final')->count() }}</h4>
            <small class="text-muted">Final</small>
        </div></div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm"><div class="card-body text-center py-3">
            <i class="bi bi-share fs-4 text-info"></i>
            <h4 class="fw-bold mb-0 mt-1">{{ App\Models\WordDocument::where('dibagikan', true)->count() }}</h4>
            <small class="text-muted">Dibagikan</small>
        </div></div>
    </div>
</div>

<div class="card border-0 shadow-sm mb-3">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-bold">Cari</label>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari judul..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold">Kategori</label>
                <select name="category" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    @foreach($categories as $key => $label)
                        <option value="{{ $key }}" {{ request('kategori')==$key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    <option value="draft" {{ request('status')=='draft' ? 'selected' : '' }}>Draft</option>
                    <option value="final" {{ request('status')=='final' ? 'selected' : '' }}>Final</option>
                    <option value="archived" {{ request('status')=='archived' ? 'selected' : '' }}>Arsip</option>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-sm btn-primary w-100"><i class="bi bi-search me-1"></i>Filter</button>
            </div>
            @if(request()->hasAny(['search','kategori','status']))
                <div class="col-md-2">
                    <a href="{{ route('staf.word-ai.index') }}" class="btn btn-sm btn-outline-secondary w-100"><i class="bi bi-x-circle me-1"></i>Reset</a>
                </div>
            @endif
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm mb-3">
    <div class="card-header bg-white border-0 py-3">
        <h6 class="fw-bold mb-0"><i class="bi bi-magic me-2 text-primary"></i>Template Cepat</h6>
    </div>
    <div class="card-body pt-0">
        <div class="row g-2">
            @foreach(App\Models\WordDocument::templates() as $key => $tpl)
                @if($key !== 'kosong')
                <div class="col-6 col-md-4 col-lg-3">
                    <a href="{{ route('staf.word-ai.create', ['templat' => $key]) }}" class="card border h-100 text-decoration-none" style="transition:box-shadow .2s;">
                        <div class="card-body py-3 px-3">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <i class="bi bi-file-earmark-text text-primary"></i>
                                <small class="fw-bold text-dark">{{ $tpl['nama'] }}</small>
                            </div>
                            <small class="text-muted" style="font-size:.72rem;">{{ $tpl['deskripsi'] }}</small>
                        </div>
                    </a>
                </div>
                @endif
            @endforeach
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr class="bg-light">
                        <th class="ps-3" style="width:5%;">#</th>
                        <th style="width:30%;">Judul</th>
                        <th style="width:12%;">Kategori</th>
                        <th style="width:12%;">Pembuat</th>
                        <th style="width:10%;">Status</th>
                        <th style="width:10%;">Shared</th>
                        <th style="width:10%;">Tanggal</th>
                        <th style="width:11%;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($documents as $i => $doc)
                    <tr>
                        <td class="ps-3">{{ $documents->firstItem() + $i }}</td>
                        <td>
                            <a href="{{ route('staf.word-ai.show', $doc) }}" class="fw-semibold text-decoration-none">
                                <i class="bi bi-file-earmark-word text-primary me-1"></i>{{ Str::limit($doc->judul, 40) }}
                            </a>
                        </td>
                        <td><span class="badge bg-primary-subtle text-primary">{{ $categories[$doc->kategori] ?? $doc->kategori }}</span></td>
                        <td><small>{{ $doc->user->nama ?? '-' }}</small></td>
                        <td>
                            @if($doc->status=='draft')
                                <span class="badge bg-warning-subtle text-warning">Draft</span>
                            @elseif($doc->status=='final')
                                <span class="badge bg-success-subtle text-success">Final</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary">Arsip</span>
                            @endif
                        </td>
                        <td>{!! $doc->dibagikan ? '<i class="bi bi-check-circle-fill text-success"></i>' : '<i class="bi bi-x-circle text-muted"></i>' !!}</td>
                        <td><small>{{ $doc->created_at->format('d/m/Y') }}</small></td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('staf.word-ai.show', $doc) }}" class="btn btn-outline-primary" title="Lihat"><i class="bi bi-eye"></i></a>
                                @if($doc->pengguna_id == auth()->id())
                                    <a href="{{ route('staf.word-ai.edit', $doc) }}" class="btn btn-outline-success" title="Edit"><i class="bi bi-pencil"></i></a>
                                    <form action="{{ route('staf.word-ai.destroy', $doc) }}" method="POST" class="d-inline" onsubmit="return false;">
                                        @csrf @method('DELETE')
                                        <button type="button" class="btn btn-outline-danger btn-delete" title="Hapus"><i class="bi bi-trash"></i></button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center py-4 text-muted">Belum ada dokumen</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">{{ $documents->withQueryString()->links() }}</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.btn-delete').forEach(btn => {
    btn.addEventListener('click', function() {
        Swal.fire({
            title: 'Hapus Dokumen?', text: 'Tidak dapat dikembalikan!', icon: 'warning',
            showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonText: 'Batal', confirmButtonText: 'Ya, Hapus!'
        }).then(r => { if(r.isConfirmed) this.closest('form').submit(); });
    });
});
</script>
@endpush