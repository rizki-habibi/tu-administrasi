@extends('peran.staf.app')
@section('judul', 'Dokumen')

@section('konten')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
        <h5 class="fw-bold mb-1">Arsip Dokumen</h5>
        <p class="text-muted mb-0" style="font-size:.82rem;">Dokumen kurikulum, administrasi, keuangan, dan lainnya</p>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal"><i class="bi bi-cloud-upload me-1"></i>Upload Dokumen</button>
</div>

<div class="card mb-3">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <select name="kategori" class="form-select form-select-sm">
                    <option value="">Semua Kategori</option>
                    @foreach(['kurikulum','administrasi','keuangan','kepegawaian','kesiswaan','surat','inventaris','lainnya'] as $c)
                        <option value="{{ $c }}" {{ request('kategori') == $c ? 'selected' : '' }}>{{ ucfirst($c) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-5">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari dokumen..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <button class="btn btn-primary btn-sm w-100"><i class="bi bi-search me-1"></i>Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="row g-3">
    @forelse($documents as $doc)
    <div class="col-md-6 col-lg-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex align-items-start gap-3">
                    <i class="bi {{ $doc->file_icon }}" style="font-size:2rem;"></i>
                    <div class="flex-grow-1 overflow-hidden">
                        <h6 class="fw-medium mb-1" style="font-size:.85rem;">{{ Str::limit($doc->judul, 35) }}</h6>
                        @php $catColors = ['kurikulum'=>'primary','administrasi'=>'info','keuangan'=>'success','kepegawaian'=>'warning','kesiswaan'=>'secondary','surat'=>'dark','inventaris'=>'danger','lainnya'=>'light text-dark']; @endphp
                        <span class="badge bg-{{ $catColors[$doc->kategori] ?? 'secondary' }}" style="font-size:.65rem;">{{ ucfirst($doc->kategori) }}</span>
                    </div>
                </div>
                @if($doc->deskripsi)
                    <p class="text-muted mt-2 mb-0" style="font-size:.78rem;">{{ Str::limit($doc->deskripsi, 60) }}</p>
                @endif
            </div>
            <div class="card-footer bg-white border-0 pt-0 pb-3">
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">{{ $doc->file_size_formatted }} &middot; {{ $doc->created_at->format('d/m/Y') }}</small>
                    <a href="{{ asset('storage/' . $doc->path_file) }}" class="btn btn-sm btn-outline-primary" target="_blank"><i class="bi bi-download"></i></a>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 text-center py-5 text-muted">
        <i class="bi bi-folder2-open" style="font-size:3rem;"></i>
        <p class="mt-2">Belum ada dokumen</p>
    </div>
    @endforelse
</div>
<div class="mt-3">{{ $documents->links() }}</div>

<!-- Upload Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title"><i class="bi bi-cloud-upload me-2"></i>Upload Dokumen</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('staf.dokumen.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Judul <span class="text-danger">*</span></label>
                        <input type="text" name="judul" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kategori <span class="text-danger">*</span></label>
                        <select name="kategori" class="form-select" required>
                            <option value="">-- Pilih --</option>
                            @foreach(['kurikulum'=>'Kurikulum','administrasi'=>'Administrasi','keuangan'=>'Keuangan','kepegawaian'=>'Kepegawaian','kesiswaan'=>'Kesiswaan','surat'=>'Surat','inventaris'=>'Inventaris','lainnya'=>'Lainnya'] as $k => $v)
                                <option value="{{ $k }}">{{ $v }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">File <span class="text-danger">*</span></label>
                        <input type="file" name="file" class="form-control" required accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png">
                        <small class="text-muted">Maks 10MB</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-cloud-upload me-1"></i>Unggah</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
