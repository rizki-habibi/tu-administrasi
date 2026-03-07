@extends('peran.admin.app')
@section('judul', 'Cloud Drive — Semua Peran')

@section('konten')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
        <h5 class="fw-bold mb-1"><i class="bi bi-cloud-fill text-primary me-2"></i>Cloud Drive — Penyimpanan Semua Peran</h5>
        <p class="text-muted mb-0" style="font-size:.82rem;">Kelola link cloud storage (Google Drive, OneDrive, TeraBox, dll) dari semua peran</p>
    </div>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="bi bi-plus-lg me-1"></i>Tambah Cloud Drive
    </button>
</div>

{{-- Statistik --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center py-3">
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width:44px;height:44px;background:linear-gradient(135deg,#6366f1,#818cf8);">
                    <i class="bi bi-cloud-fill text-white"></i>
                </div>
                <h4 class="fw-bold mb-0">{{ $drives->total() }}</h4>
                <small class="text-muted">Total Cloud Drive</small>
            </div>
        </div>
    </div>
    @foreach($statPerDrive->take(3) as $jd => $total)
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center py-3">
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width:44px;height:44px;background:linear-gradient(135deg,#10b981,#34d399);">
                    <i class="bi {{ $jd === 'google_drive' || $jd === 'google_drive_bisnis' ? 'bi-google' : ($jd === 'onedrive' ? 'bi-microsoft' : 'bi-cloud') }} text-white"></i>
                </div>
                <h4 class="fw-bold mb-0">{{ $total }}</h4>
                <small class="text-muted">{{ \App\Models\PenyimpananCloud::jenisDriveOptions()[$jd] ?? ucfirst($jd) }}</small>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Filter --}}
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari nama..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="peran" class="form-select form-select-sm">
                    <option value="">Semua Peran</option>
                    @foreach(\App\Models\Pengguna::ROLES as $k => $v)
                        <option value="{{ $k }}" {{ request('peran') == $k ? 'selected' : '' }}>{{ $v }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="jenis_drive" class="form-select form-select-sm">
                    <option value="">Semua Drive</option>
                    @foreach(\App\Models\PenyimpananCloud::jenisDriveOptions() as $k => $v)
                        <option value="{{ $k }}" {{ request('jenis_drive') == $k ? 'selected' : '' }}>{{ $v }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="jenis_data" class="form-select form-select-sm">
                    <option value="">Semua Data</option>
                    @foreach(\App\Models\PenyimpananCloud::jenisDataOptions() as $k => $v)
                        <option value="{{ $k }}" {{ request('jenis_data') == $k ? 'selected' : '' }}>{{ $v }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button class="btn btn-primary btn-sm flex-grow-1"><i class="bi bi-search me-1"></i>Filter</button>
                <a href="{{ route('admin.database.cloud') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-x-lg"></i></a>
            </div>
        </form>
    </div>
</div>

{{-- Per Peran Summary --}}
@if($statPerPeran->count() > 0)
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body py-3">
        <h6 class="fw-bold mb-2" style="font-size:.85rem;"><i class="bi bi-people me-1"></i>Distribusi per Peran</h6>
        <div class="d-flex flex-wrap gap-2">
            @foreach($statPerPeran as $peran => $total)
                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2">
                    {{ \App\Models\Pengguna::ROLES[$peran] ?? ucfirst($peran) }}: <strong>{{ $total }}</strong>
                </span>
            @endforeach
        </div>
    </div>
</div>
@endif

{{-- Daftar Cloud Drive --}}
<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" style="font-size:.85rem;">
            <thead class="table-light">
                <tr>
                    <th style="width:40px">#</th>
                    <th>Nama</th>
                    <th>Pemilik</th>
                    <th>Drive</th>
                    <th>Jenis Data</th>
                    <th>Status</th>
                    <th>Dihapus?</th>
                    <th>Tanggal</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($drives as $i => $d)
                <tr>
                    <td class="text-muted">{{ $drives->firstItem() + $i }}</td>
                    <td>
                        <div class="fw-semibold">{{ Str::limit($d->nama, 40) }}</div>
                        @if($d->deskripsi)<small class="text-muted">{{ Str::limit($d->deskripsi, 50) }}</small>@endif
                    </td>
                    <td>
                        <div class="fw-semibold">{{ $d->pengguna->nama ?? '-' }}</div>
                        <small class="text-muted">{{ \App\Models\Pengguna::ROLES[$d->peran_pemilik] ?? $d->peran_pemilik }}</small>
                    </td>
                    <td>
                        <span class="badge bg-light text-dark"><i class="bi {{ $d->icon_drive }} me-1"></i>{{ $d->nama_drive }}</span>
                    </td>
                    <td><span class="badge bg-info bg-opacity-10 text-info">{{ ucfirst($d->jenis_data) }}</span></td>
                    <td><span class="badge bg-{{ $d->status_badge }}">{{ ucfirst($d->status) }}</span></td>
                    <td class="text-center">
                        @if($d->bisa_dihapus)
                            <i class="bi bi-unlock text-success" title="Bisa dihapus"></i>
                        @else
                            <i class="bi bi-lock-fill text-danger" title="Tidak bisa dihapus (data penting)"></i>
                        @endif
                    </td>
                    <td class="text-muted" style="font-size:.8rem;">{{ $d->created_at->format('d/m/Y') }}</td>
                    <td class="text-center">
                        <div class="d-flex gap-1 justify-content-center">
                            <a href="{{ $d->url_link }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-success" title="Buka Link">
                                <i class="bi bi-box-arrow-up-right"></i>
                            </a>
                            <button class="btn btn-sm btn-outline-primary btn-edit"
                                data-id="{{ $d->id }}" data-nama="{{ $d->nama }}" data-jenis_drive="{{ $d->jenis_drive }}"
                                data-jenis_drive_kustom="{{ $d->jenis_drive_kustom }}" data-jenis_data="{{ $d->jenis_data }}"
                                data-url_link="{{ $d->url_link }}" data-deskripsi="{{ $d->deskripsi }}"
                                data-bisa_dihapus="{{ $d->bisa_dihapus ? '1' : '0' }}" data-peran_pemilik="{{ $d->peran_pemilik }}"
                                data-bs-toggle="modal" data-bs-target="#modalEdit" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <form action="{{ route('admin.database.cloud.destroy', $d) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" data-confirm="Yakin ingin menghapus cloud drive '{{ $d->nama }}'? Data ini tidak bisa dikembalikan." title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center py-4 text-muted">
                        <i class="bi bi-cloud-slash" style="font-size:2rem;"></i>
                        <p class="mb-0 mt-2">Belum ada cloud drive</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($drives->hasPages())
    <div class="card-footer bg-white border-0 py-3">{{ $drives->links() }}</div>
    @endif
</div>

{{-- Modal Tambah --}}
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('admin.database.cloud.store') }}" method="POST">
            @csrf
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-0 pb-0">
                    <h6 class="modal-title fw-bold"><i class="bi bi-plus-circle me-2"></i>Tambah Cloud Drive</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nama <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control form-control-sm" required placeholder="Contoh: Backup DB Maret 2026">
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Jenis Drive <span class="text-danger">*</span></label>
                            <select name="jenis_drive" class="form-select form-select-sm drive-select" required>
                                @foreach(\App\Models\PenyimpananCloud::jenisDriveOptions() as $k => $v)
                                    <option value="{{ $k }}">{{ $v }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 custom-drive-field" style="display:none;">
                            <label class="form-label small fw-bold">Nama Platform</label>
                            <input type="text" name="jenis_drive_kustom" class="form-control form-control-sm" placeholder="Contoh: Mega, Dropbox...">
                        </div>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Jenis Data <span class="text-danger">*</span></label>
                            <select name="jenis_data" class="form-select form-select-sm" required>
                                @foreach(\App\Models\PenyimpananCloud::jenisDataOptions() as $k => $v)
                                    <option value="{{ $k }}">{{ $v }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Peran Pemilik <span class="text-danger">*</span></label>
                            <select name="peran_pemilik" class="form-select form-select-sm" required>
                                @foreach(\App\Models\Pengguna::ROLES as $k => $v)
                                    <option value="{{ $k }}">{{ $v }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">URL / Link <span class="text-danger">*</span></label>
                        <input type="url" name="url_link" class="form-control form-control-sm" required placeholder="https://drive.google.com/...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control form-control-sm" rows="2" placeholder="Catatan opsional..."></textarea>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="bisa_dihapus" value="1" id="bisaDihapusTambah">
                        <label class="form-check-label small" for="bisaDihapusTambah">Boleh dihapus oleh peran lain</label>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-save me-1"></i>Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Modal Edit --}}
<div class="modal fade" id="modalEdit" tabindex="-1">
    <div class="modal-dialog">
        <form id="formEdit" method="POST">
            @csrf @method('PUT')
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-0 pb-0">
                    <h6 class="modal-title fw-bold"><i class="bi bi-pencil me-2"></i>Edit Cloud Drive</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nama <span class="text-danger">*</span></label>
                        <input type="text" name="nama" id="editNama" class="form-control form-control-sm" required>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Jenis Drive</label>
                            <select name="jenis_drive" id="editJenisDrive" class="form-select form-select-sm drive-select" required>
                                @foreach(\App\Models\PenyimpananCloud::jenisDriveOptions() as $k => $v)
                                    <option value="{{ $k }}">{{ $v }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 custom-drive-field" style="display:none;">
                            <label class="form-label small fw-bold">Nama Platform</label>
                            <input type="text" name="jenis_drive_kustom" id="editKustom" class="form-control form-control-sm">
                        </div>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Jenis Data</label>
                            <select name="jenis_data" id="editJenisData" class="form-select form-select-sm" required>
                                @foreach(\App\Models\PenyimpananCloud::jenisDataOptions() as $k => $v)
                                    <option value="{{ $k }}">{{ $v }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Peran Pemilik</label>
                            <select name="peran_pemilik" id="editPeran" class="form-select form-select-sm" required>
                                @foreach(\App\Models\Pengguna::ROLES as $k => $v)
                                    <option value="{{ $k }}">{{ $v }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">URL / Link</label>
                        <input type="url" name="url_link" id="editUrl" class="form-control form-control-sm" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Deskripsi</label>
                        <textarea name="deskripsi" id="editDeskripsi" class="form-control form-control-sm" rows="2"></textarea>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="bisa_dihapus" value="1" id="editBisaDihapus">
                        <label class="form-check-label small" for="editBisaDihapus">Boleh dihapus oleh peran lain</label>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-save me-1"></i>Perbarui</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Toggle custom drive field
document.querySelectorAll('.drive-select').forEach(sel => {
    sel.addEventListener('change', function() {
        const cf = this.closest('.row').querySelector('.custom-drive-field');
        if (cf) cf.style.display = this.value === 'custom' ? '' : 'none';
    });
    sel.dispatchEvent(new Event('change'));
});

// Edit modal population
document.querySelectorAll('.btn-edit').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.dataset.id;
        document.getElementById('formEdit').action = '{{ route("admin.database.cloud.update", ":id") }}'.replace(':id', id);
        document.getElementById('editNama').value = this.dataset.nama;
        document.getElementById('editJenisDrive').value = this.dataset.jenis_drive;
        document.getElementById('editKustom').value = this.dataset.jenis_drive_kustom || '';
        document.getElementById('editJenisData').value = this.dataset.jenis_data;
        document.getElementById('editUrl').value = this.dataset.url_link;
        document.getElementById('editDeskripsi').value = this.dataset.deskripsi || '';
        document.getElementById('editPeran').value = this.dataset.peran_pemilik;
        document.getElementById('editBisaDihapus').checked = this.dataset.bisa_dihapus === '1';
        // Toggle custom field
        const editDrive = document.getElementById('editJenisDrive');
        editDrive.dispatchEvent(new Event('change'));
    });
});
</script>
@endpush
