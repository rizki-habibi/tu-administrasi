@extends('peran.admin.app')
@section('judul', 'Kelola Dokumen')

@section('konten')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
        <h5 class="fw-bold mb-1">Kelola Dokumen</h5>
        <p class="text-muted mb-0" style="font-size:.82rem;">Arsip dokumen kurikulum, administrasi, keuangan, dan lainnya</p>
    </div>
    <div class="d-flex gap-2">
        <div class="dropdown">
            <button class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown"><i class="bi bi-download me-1"></i>Ekspor</button>
            <ul class="dropdown-menu shadow-sm border-0">
                <li><a class="dropdown-item export-btn" href="{{ route('admin.dokumen.ekspor', ['format' => 'csv']) }}" data-format="csv"><i class="bi bi-file-earmark-spreadsheet me-2 text-success"></i>CSV / Excel</a></li>
                <li><a class="dropdown-item" href="{{ route('admin.dokumen.ekspor', ['format' => 'pdf']) }}" target="_blank"><i class="bi bi-file-earmark-pdf me-2 text-danger"></i>PDF / Print</a></li>
            </ul>
        </div>
        <a href="{{ route('admin.dokumen.create') }}" class="btn btn-primary"><i class="bi bi-cloud-upload me-1"></i>Upload Dokumen</a>
    </div>
</div>

<!-- Filter -->
<div class="card mb-3">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Kategori</label>
                <select name="kategori" class="form-select form-select-sm">
                    <option value="">Semua Kategori</option>
                    @foreach(['kurikulum','administrasi','keuangan','kepegawaian','kesiswaan','surat','inventaris','lainnya'] as $c)
                        <option value="{{ $c }}" {{ request('kategori') == $c ? 'selected' : '' }}>{{ ucfirst($c) }}</option>
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
                    <th width="40">No</th>
                    <th>Dokumen</th>
                    <th>Kategori</th>
                    <th>Ukuran</th>
                    <th>Diupload</th>
                    <th>Tanggal</th>
                    <th width="140" class="text-center">Aksi</th>
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
                                <div class="fw-medium">{{ Str::limit($doc->judul, 40) }}</div>
                                <small class="text-muted">{{ $doc->nama_file }}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        @php $catColors = ['kurikulum'=>'primary','administrasi'=>'info','keuangan'=>'success','kepegawaian'=>'warning','kesiswaan'=>'secondary','surat'=>'dark','inventaris'=>'danger','lainnya'=>'light text-dark']; @endphp
                        <span class="badge bg-{{ $catColors[$doc->kategori] ?? 'secondary' }}">{{ ucfirst($doc->kategori) }}</span>
                    </td>
                    <td><small>{{ $doc->file_size_formatted }}</small></td>
                    <td><small>{{ $doc->uploader->nama ?? '-' }}</small></td>
                    <td><small>{{ $doc->created_at->format('d/m/Y') }}</small></td>
                    <td>
                        <div class="d-flex gap-1 justify-content-center">
                            <a href="{{ asset('storage/' . $doc->path_file) }}" class="btn btn-sm btn-outline-success" target="_blank" title="Download"><i class="bi bi-download"></i></a>
                            <a href="{{ route('admin.dokumen.edit', $doc) }}" class="btn btn-sm btn-outline-warning" title="Ubah"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('admin.dokumen.destroy', $doc) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" data-confirm="Hapus dokumen ini?"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
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

@push('scripts')
<script>
// Real-time Export with progress
document.querySelectorAll('.export-btn').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        const url = this.href;
        Swal.fire({
            title: 'Mengekspor Data...', html: '<div class="mb-2">Sedang memproses file export</div><div class="progress" style="height:6px;border-radius:4px;"><div class="progress-bar bg-primary progress-bar-striped progress-bar-animated" style="width:0%"></div></div>',
            allowOutsideClick: false, showConfirmButton: false, didOpen: () => {
                const bar = Swal.getHtmlContainer().querySelector('.progress-bar');
                let w = 0;
                const interval = setInterval(() => { w = Math.min(w + Math.random() * 15, 90); bar.style.width = w + '%'; }, 200);
                fetch(url).then(r => r.blob()).then(blob => {
                    clearInterval(interval); bar.style.width = '100%';
                    const a = document.createElement('a'); a.href = URL.createObjectURL(blob);
                    a.download = 'dokumen.csv'; document.body.appendChild(a); a.click(); a.remove();
                    Swal.fire({ icon: 'success', title: 'Export Berhasil!', text: 'File telah diunduh', timer: 2000, showConfirmButton: false });
                }).catch(() => { clearInterval(interval); Swal.fire({ icon: 'error', title: 'Gagal Export', text: 'Terjadi kesalahan' }); });
            }
        });
    });
});
</script>
@endpush
