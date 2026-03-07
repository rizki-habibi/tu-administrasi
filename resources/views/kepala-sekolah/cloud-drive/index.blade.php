@extends('peran.kepala-sekolah.app')
@section('judul', 'Cloud Drive Saya')

@section('konten')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
        <h5 class="fw-bold mb-1"><i class="bi bi-cloud-fill me-2" style="color:#d97706;"></i>Cloud Drive Saya</h5>
        <p class="text-muted mb-0" style="font-size:.82rem;">Simpan link ke penyimpanan cloud Anda (Google Drive, OneDrive, TeraBox, dll)</p>
    </div>
    <button class="btn btn-sm text-white" style="background:#d97706;" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="bi bi-plus-lg me-1"></i>Tambah Link
    </button>
</div>

{{-- Filter --}}
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="jenis_drive" class="form-select form-select-sm">
                    <option value="">Semua Drive</option>
                    @foreach(\App\Models\PenyimpananCloud::jenisDriveOptions() as $k => $v)
                        <option value="{{ $k }}" {{ request('jenis_drive') == $k ? 'selected' : '' }}>{{ $v }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button class="btn btn-sm flex-grow-1 text-white" style="background:#d97706;"><i class="bi bi-search me-1"></i>Filter</button>
                <a href="{{ route('kepala-sekolah.cloud-drive.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-x-lg"></i></a>
            </div>
        </form>
    </div>
</div>

{{-- Daftar --}}
<div class="row g-3">
    @forelse($drives as $d)
    <div class="col-md-6 col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-3">
                <div class="d-flex align-items-start justify-content-between mb-2">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-3 d-flex align-items-center justify-content-center" style="width:40px;height:40px;background:linear-gradient(135deg,#d97706,#f59e0b);">
                            <i class="bi {{ $d->icon_drive }} text-white"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0" style="font-size:.9rem;">{{ Str::limit($d->nama, 30) }}</h6>
                            <small class="text-muted">{{ $d->nama_drive }}</small>
                        </div>
                    </div>
                    <span class="badge bg-{{ $d->status_badge }}">{{ ucfirst($d->status) }}</span>
                </div>
                @if($d->deskripsi)
                <p class="text-muted mb-2" style="font-size:.8rem;">{{ Str::limit($d->deskripsi, 80) }}</p>
                @endif
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="badge bg-warning bg-opacity-10 text-warning" style="font-size:.75rem;">{{ ucfirst($d->jenis_data) }}</span>
                    <small class="text-muted">{{ $d->created_at->format('d/m/Y') }}</small>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ $d->url_link }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm text-white flex-grow-1" style="background:#d97706;">
                        <i class="bi bi-box-arrow-up-right me-1"></i>Buka
                    </a>
                    <button class="btn btn-sm btn-outline-warning btn-edit"
                        data-id="{{ $d->id }}" data-nama="{{ $d->nama }}" data-jenis_drive="{{ $d->jenis_drive }}"
                        data-jenis_drive_kustom="{{ $d->jenis_drive_kustom }}" data-jenis_data="{{ $d->jenis_data }}"
                        data-url_link="{{ $d->url_link }}" data-deskripsi="{{ $d->deskripsi }}"
                        data-bs-toggle="modal" data-bs-target="#modalEdit">
                        <i class="bi bi-pencil"></i>
                    </button>
                </div>
                @if(!$d->bisa_dihapus)
                <div class="mt-2">
                    <small class="text-danger"><i class="bi bi-lock-fill me-1"></i>Data terkunci — hanya admin yang bisa menghapus</small>
                </div>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5 text-muted">
                <i class="bi bi-cloud-slash" style="font-size:3rem;"></i>
                <p class="mt-2 mb-0">Belum ada cloud drive. Klik <strong>Tambah Link</strong> untuk mulai.</p>
            </div>
        </div>
    </div>
    @endforelse
</div>

@if($drives->hasPages())
<div class="mt-3">{{ $drives->links() }}</div>
@endif

{{-- Modal Tambah --}}
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('kepala-sekolah.cloud-drive.store') }}" method="POST">
            @csrf
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-0 pb-0">
                    <h6 class="modal-title fw-bold"><i class="bi bi-plus-circle me-2"></i>Tambah Cloud Drive</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nama <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control form-control-sm" required placeholder="Contoh: Backup Data Sekolah">
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
                            <input type="text" name="jenis_drive_kustom" class="form-control form-control-sm" placeholder="Mega, Dropbox...">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Jenis Data <span class="text-danger">*</span></label>
                        <select name="jenis_data" class="form-select form-select-sm" required>
                            @foreach(\App\Models\PenyimpananCloud::jenisDataOptions() as $k => $v)
                                <option value="{{ $k }}">{{ $v }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">URL / Link <span class="text-danger">*</span></label>
                        <input type="url" name="url_link" class="form-control form-control-sm" required placeholder="https://drive.google.com/...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control form-control-sm" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm text-white" style="background:#d97706;"><i class="bi bi-save me-1"></i>Simpan</button>
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
                        <label class="form-label small fw-bold">Nama</label>
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
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Jenis Data</label>
                        <select name="jenis_data" id="editJenisData" class="form-select form-select-sm" required>
                            @foreach(\App\Models\PenyimpananCloud::jenisDataOptions() as $k => $v)
                                <option value="{{ $k }}">{{ $v }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">URL / Link</label>
                        <input type="url" name="url_link" id="editUrl" class="form-control form-control-sm" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Deskripsi</label>
                        <textarea name="deskripsi" id="editDeskripsi" class="form-control form-control-sm" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm text-white" style="background:#d97706;"><i class="bi bi-save me-1"></i>Perbarui</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.drive-select').forEach(sel => {
    sel.addEventListener('change', function() {
        const cf = this.closest('.row').querySelector('.custom-drive-field');
        if (cf) cf.style.display = this.value === 'custom' ? '' : 'none';
    });
});

document.querySelectorAll('.btn-edit').forEach(btn => {
    btn.addEventListener('click', function() {
        document.getElementById('formEdit').action = '{{ route("kepala-sekolah.cloud-drive.update", ":id") }}'.replace(':id', this.dataset.id);
        document.getElementById('editNama').value = this.dataset.nama;
        document.getElementById('editJenisDrive').value = this.dataset.jenis_drive;
        document.getElementById('editKustom').value = this.dataset.jenis_drive_kustom || '';
        document.getElementById('editJenisData').value = this.dataset.jenis_data;
        document.getElementById('editUrl').value = this.dataset.url_link;
        document.getElementById('editDeskripsi').value = this.dataset.deskripsi || '';
        document.getElementById('editJenisDrive').dispatchEvent(new Event('change'));
    });
});
</script>
@endpush
