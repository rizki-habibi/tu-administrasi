@extends('admin.tata-letak.app')
@section('judul', 'Kelola Staff')

@section('konten')
<!-- Header -->
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
        <h5 class="fw-bold mb-1">Kelola Staff</h5>
        <p class="text-muted mb-0" style="font-size:.82rem;">Manajemen data staff TU SMA Negeri 2 Jember</p>
    </div>
    <div class="d-flex gap-2">
        <div class="dropdown">
            <button class="btn btn-outline-success btn-sm dropdown-toggle" data-bs-toggle="dropdown"><i class="bi bi-download me-1"></i>Export</button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item export-btn" href="{{ route('admin.pegawai.ekspor', ['format'=>'csv']) }}" data-format="csv"><i class="bi bi-filetype-csv me-2"></i>CSV / Excel</a></li>
                <li><a class="dropdown-item" href="{{ route('admin.pegawai.ekspor', ['format'=>'pdf']) }}" target="_blank"><i class="bi bi-printer me-2"></i>Print / PDF</a></li>
            </ul>
        </div>
        <button class="btn btn-outline-info btn-sm" data-bs-toggle="modal" data-bs-target="#importModal"><i class="bi bi-upload me-1"></i>Import</button>
        <a href="{{ route('admin.pegawai.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>Tambah Staff</a>
    </div>
</div>

<!-- Filter -->
<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-5">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari nama, email, jabatan..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select form-select-sm">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status')=='active'?'selected':'' }}>Aktif</option>
                    <option value="inactive" {{ request('status')=='inactive'?'selected':'' }}>Nonaktif</option>
                </select>
            </div>
            <div class="col-md-4 d-flex gap-1">
                <button class="btn btn-primary btn-sm flex-fill"><i class="bi bi-search me-1"></i>Cari</button>
                <a href="{{ route('admin.pegawai.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-counterclockwise"></i></a>
            </div>
        </form>
    </div>
</div>

<!-- Table -->
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead style="background:#f8fafc;">
                    <tr>
                        <th style="font-size:.78rem;text-transform:uppercase;letter-spacing:.5px;color:#64748b;padding:.75rem 1rem;">#</th>
                        <th style="font-size:.78rem;text-transform:uppercase;letter-spacing:.5px;color:#64748b;">Staff</th>
                        <th style="font-size:.78rem;text-transform:uppercase;letter-spacing:.5px;color:#64748b;">Jabatan</th>
                        <th style="font-size:.78rem;text-transform:uppercase;letter-spacing:.5px;color:#64748b;">Telepon</th>
                        <th style="font-size:.78rem;text-transform:uppercase;letter-spacing:.5px;color:#64748b;">Status</th>
                        <th style="font-size:.78rem;text-transform:uppercase;letter-spacing:.5px;color:#64748b;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($staffs as $i => $staff)
                    <tr>
                        <td class="ps-3" style="font-size:.82rem;">{{ $staffs->firstItem() + $i }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold"
                                     style="width:36px;height:36px;font-size:.75rem;background:linear-gradient(135deg,#6366f1,#8b5cf6);">
                                    {{ strtoupper(substr($staff->nama,0,2)) }}
                                </div>
                                <div>
                                    <div class="fw-medium" style="font-size:.85rem;">{{ $staff->nama }}</div>
                                    <small class="text-muted">{{ $staff->email }}</small>
                                </div>
                            </div>
                        </td>
                        <td style="font-size:.85rem;">{{ $staff->jabatan ?? '-' }}</td>
                        <td style="font-size:.85rem;">{{ $staff->telepon ?? '-' }}</td>
                        <td>
                            <span class="badge bg-{{ $staff->aktif ? 'success' : 'danger' }} bg-opacity-10 text-{{ $staff->aktif ? 'success' : 'danger' }} px-2 py-1" style="font-size:.75rem;">
                                {{ $staff->aktif ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-1">
                                <button class="btn btn-light btn-sm" title="Lihat Detail"
                                        onclick="showDetail({{ $staff->id }}, '{{ addslashes($staff->nama) }}', '{{ $staff->email }}', '{{ $staff->jabatan ?? '-' }}', '{{ $staff->telepon ?? '-' }}', '{{ $staff->alamat ?? '-' }}', '{{ $staff->aktif ? 'Aktif' : 'Nonaktif' }}', '{{ $staff->created_at->format('d M Y') }}')">
                                    <i class="bi bi-eye text-primary"></i>
                                </button>
                                <a href="{{ route('admin.pegawai.edit', $staff) }}" class="btn btn-light btn-sm" title="Edit"><i class="bi bi-pencil text-warning"></i></a>
                                <form method="POST" action="{{ route('admin.pegawai.toggle-status', $staff) }}" class="d-inline">
                                    @csrf @method('PATCH')
                                    <button class="btn btn-light btn-sm" title="{{ $staff->aktif ? 'Nonaktifkan' : 'Aktifkan' }}">
                                        <i class="bi bi-{{ $staff->aktif ? 'toggle-on text-success' : 'toggle-off text-secondary' }}"></i>
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.pegawai.destroy', $staff) }}" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-light btn-sm" data-confirm="Hapus staff {{ $staff->nama }}?" title="Hapus"><i class="bi bi-trash text-danger"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center py-4 text-muted">Belum ada data staff</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($staffs->hasPages())
    <div class="card-footer bg-white border-top-0 py-3">
        {{ $staffs->withQueryString()->links() }}
    </div>
    @endif
</div>

<!-- Quick View Modal -->
<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold">Detail Staff</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-1">
                <div class="text-center mb-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold mx-auto mb-2"
                         style="width:56px;height:56px;font-size:1rem;background:linear-gradient(135deg,#6366f1,#8b5cf6);" id="mdAvatar"></div>
                    <h6 class="fw-bold mb-0" id="mdName"></h6>
                    <small class="text-muted" id="mdEmail"></small>
                </div>
                <div class="row g-3" style="font-size:.85rem;">
                    <div class="col-6"><span class="text-muted d-block">Jabatan</span><span class="fw-medium" id="mdPosition"></span></div>
                    <div class="col-6"><span class="text-muted d-block">Telepon</span><span class="fw-medium" id="mdPhone"></span></div>
                    <div class="col-6"><span class="text-muted d-block">Status</span><span id="mdStatus"></span></div>
                    <div class="col-6"><span class="text-muted d-block">Bergabung</span><span class="fw-medium" id="mdDate"></span></div>
                    <div class="col-12"><span class="text-muted d-block">Alamat</span><span class="fw-medium" id="mdAddress"></span></div>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <a id="mdViewBtn" href="#" class="btn btn-primary btn-sm"><i class="bi bi-box-arrow-up-right me-1"></i>Lihat Selengkapnya</a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function showDetail(id, name, email, position, phone, address, status, date) {
    document.getElementById('mdAvatar').textContent = name.substring(0,2).toUpperCase();
    document.getElementById('mdName').textContent = name;
    document.getElementById('mdEmail').textContent = email;
    document.getElementById('mdPosition').textContent = position;
    document.getElementById('mdPhone').textContent = phone;
    document.getElementById('mdAddress').textContent = address;
    document.getElementById('mdDate').textContent = date;

    const statusEl = document.getElementById('mdStatus');
    if (status === 'Aktif') {
        statusEl.innerHTML = '<span class="badge bg-success bg-opacity-10 text-success">Aktif</span>';
    } else {
        statusEl.innerHTML = '<span class="badge bg-danger bg-opacity-10 text-danger">Nonaktif</span>';
    }

    document.getElementById('mdViewBtn').href = '/admin/staff/' + id;
    new bootstrap.Modal(document.getElementById('detailModal')).show();
}

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
                    a.download = url.includes('csv') ? 'data_staff.csv' : 'data_staff';
                    document.body.appendChild(a); a.click(); a.remove();
                    Swal.fire({ icon: 'success', title: 'Export Berhasil!', text: 'File telah diunduh', timer: 2000, showConfirmButton: false });
                }).catch(() => { clearInterval(interval); Swal.fire({ icon: 'error', title: 'Gagal Export', text: 'Terjadi kesalahan saat mengekspor data' }); });
            }
        });
    });
});
</script>

<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px;">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold"><i class="bi bi-upload text-primary me-2"></i>Import Data Staff</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.pegawai.impor') }}" method="POST" enctype="multipart/form-data" id="importForm">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">File CSV</label>
                        <input type="file" name="file" class="form-control" accept=".csv,.xlsx,.xls" required id="importFile">
                        <small class="text-muted">Format: CSV/Excel. Kolom: Nama, Email, Password, Jabatan, Telepon, Alamat</small>
                    </div>
                    <div class="mb-3">
                        <a href="{{ route('admin.pegawai.ekspor', ['format'=>'csv']) }}" class="text-primary text-decoration-none" style="font-size:.8rem;">
                            <i class="bi bi-download me-1"></i>Download template CSV
                        </a>
                    </div>
                    <div id="importProgress" class="d-none mb-3">
                        <div class="progress" style="height:6px;border-radius:4px;">
                            <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" style="width:0%"></div>
                        </div>
                        <small class="text-muted mt-1 d-block" id="importStatus">Memproses...</small>
                    </div>
                    <button type="submit" class="btn btn-primary w-100" id="importBtn">
                        <i class="bi bi-upload me-1"></i>Import Data
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('importForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = this;
    const btn = document.getElementById('importBtn');
    const progress = document.getElementById('importProgress');
    const bar = progress.querySelector('.progress-bar');
    const status = document.getElementById('importStatus');
    const file = document.getElementById('importFile');

    if (!file.files.length) return;

    btn.disabled = true;
    btn.innerHTML = '<div class="spinner-border spinner-border-sm me-1"></div> Memproses...';
    progress.classList.remove('d-none');

    const formData = new FormData(form);
    let w = 0;
    const interval = setInterval(() => { w = Math.min(w + Math.random() * 10, 90); bar.style.width = w + '%'; }, 300);

    fetch(form.action, {
        method: 'POST', body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    }).then(r => r.json()).then(data => {
        clearInterval(interval); bar.style.width = '100%';
        bootstrap.Modal.getInstance(document.getElementById('importModal')).hide();
        if (data.success) {
            Swal.fire({ icon: 'success', title: 'Import Berhasil!', text: data.message || 'Data berhasil diimport', confirmButtonColor: '#6366f1' }).then(() => location.reload());
        } else {
            Swal.fire({ icon: 'error', title: 'Gagal Import', text: data.message || 'Terjadi kesalahan', confirmButtonColor: '#6366f1' });
        }
    }).catch(() => {
        clearInterval(interval);
        bootstrap.Modal.getInstance(document.getElementById('importModal')).hide();
        Swal.fire({ icon: 'error', title: 'Gagal Import', text: 'Terjadi kesalahan saat memproses file' });
    }).finally(() => {
        btn.disabled = false; btn.innerHTML = '<i class="bi bi-upload me-1"></i>Import Data';
        progress.classList.add('d-none'); bar.style.width = '0%';
    });
});
</script>
@endpush
