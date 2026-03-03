@extends('layouts.admin')
@section('title', 'Kelola Dokumen')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
        <h5 class="fw-bold mb-1">Kelola Dokumen</h5>
        <p class="text-muted mb-0" style="font-size:.82rem;">Arsip dokumen kurikulum, administrasi, keuangan, dan lainnya</p>
    </div>
    <div class="d-flex gap-2">
        <div class="dropdown">
            <button class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown"><i class="bi bi-download me-1"></i>Export</button>
            <ul class="dropdown-menu shadow-sm border-0">
                <li><a class="dropdown-item" href="{{ route('admin.document.export', ['format' => 'csv']) }}"><i class="bi bi-file-earmark-spreadsheet me-2 text-success"></i>CSV / Excel</a></li>
                <li><a class="dropdown-item" href="{{ route('admin.document.export', ['format' => 'pdf']) }}" target="_blank"><i class="bi bi-file-earmark-pdf me-2 text-danger"></i>PDF / Print</a></li>
            </ul>
        </div>
        <a href="{{ route('admin.document.create') }}" class="btn btn-primary"><i class="bi bi-cloud-upload me-1"></i>Upload Dokumen</a>
    </div>
</div>

<!-- Filter -->
<div class="card mb-3">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Kategori</label>
                <select name="category" class="form-select form-select-sm">
                    <option value="">Semua Kategori</option>
                    @foreach(['kurikulum','administrasi','keuangan','kepegawaian','kesiswaan','surat','inventaris','lainnya'] as $c)
                        <option value="{{ $c }}" {{ request('category') == $c ? 'selected' : '' }}>{{ ucfirst($c) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-5">
                <label class="form-label">Cari</label>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari judul dokumen..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <button class="btn btn-primary btn-sm w-100"><i class="bi bi-search me-1"></i>Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th width="40">#</th>
                    <th>Dokumen</th>
                    <th>Kategori</th>
                    <th>Ukuran</th>
                    <th>Diupload</th>
                    <th>Tanggal</th>
                    <th width="140">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($documents as $i => $doc)
                <tr>
                    <td>{{ $documents->firstItem() + $i }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi {{ $doc->file_icon }}" style="font-size:1.4rem;"></i>
                            <div>
                                <div class="fw-medium">{{ Str::limit($doc->title, 40) }}</div>
                                <small class="text-muted">{{ $doc->file_name }}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        @php $catColors = ['kurikulum'=>'primary','administrasi'=>'info','keuangan'=>'success','kepegawaian'=>'warning','kesiswaan'=>'secondary','surat'=>'dark','inventaris'=>'danger','lainnya'=>'light text-dark']; @endphp
                        <span class="badge bg-{{ $catColors[$doc->category] ?? 'secondary' }}">{{ ucfirst($doc->category) }}</span>
                    </td>
                    <td><small>{{ $doc->file_size_formatted }}</small></td>
                    <td><small>{{ $doc->uploader->name ?? '-' }}</small></td>
                    <td><small>{{ $doc->created_at->format('d/m/Y') }}</small></td>
                    <td>
                        <a href="{{ asset('storage/' . $doc->file_path) }}" class="btn btn-sm btn-outline-success" target="_blank" title="Download"><i class="bi bi-download"></i></a>
                        <a href="{{ route('admin.document.edit', $doc) }}" class="btn btn-sm btn-outline-warning" title="Edit"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('admin.document.destroy', $doc) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" data-confirm="Hapus dokumen ini?"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-5"><i class="bi bi-folder2-open" style="font-size:2rem;"></i><p class="mt-2 mb-0">Belum ada dokumen</p></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $documents->links() }}</div>
@endsection
