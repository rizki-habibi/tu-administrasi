@extends('peran.admin.app')
@section('judul', 'Dokumen Kepegawaian')

@section('konten')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
        <h5 class="fw-bold mb-1">Dokumen Kepegawaian</h5>
        <p class="text-muted mb-0" style="font-size:.82rem;">Arsip digital dokumen kepegawaian</p>
    </div>
    <a href="{{ route('admin.kepegawaian.dokumen.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>Upload Dokumen</a>
</div>

<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari judul, nomor dokumen..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="pegawai" class="form-select form-select-sm">
                    <option value="">Semua Pegawai</option>
                    @foreach($pegawaiList as $p)
                        <option value="{{ $p->id }}" {{ request('pegawai') == $p->id ? 'selected' : '' }}>{{ $p->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="kategori" class="form-select form-select-sm">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoriList as $key => $label)
                        <option value="{{ $key }}" {{ request('kategori') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex gap-1">
                <button class="btn btn-primary btn-sm flex-fill"><i class="bi bi-search me-1"></i>Cari</button>
                <a href="{{ route('admin.kepegawaian.dokumen.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-counterclockwise"></i></a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead style="background:#f8fafc;">
                    <tr>
                        <th style="font-size:.78rem;text-transform:uppercase;letter-spacing:.5px;color:#64748b;padding:.75rem 1rem;">No</th>
                        <th style="font-size:.78rem;text-transform:uppercase;letter-spacing:.5px;color:#64748b;">Pegawai</th>
                        <th style="font-size:.78rem;text-transform:uppercase;letter-spacing:.5px;color:#64748b;">Judul</th>
                        <th style="font-size:.78rem;text-transform:uppercase;letter-spacing:.5px;color:#64748b;">Kategori</th>
                        <th style="font-size:.78rem;text-transform:uppercase;letter-spacing:.5px;color:#64748b;">Tipe</th>
                        <th style="font-size:.78rem;text-transform:uppercase;letter-spacing:.5px;color:#64748b;">Tanggal</th>
                        <th style="font-size:.78rem;text-transform:uppercase;letter-spacing:.5px;color:#64748b;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($dokumen as $i => $d)
                    <tr>
                        <td class="ps-3" style="font-size:.82rem;">{{ $dokumen->firstItem() + $i }}</td>
                        <td>
                            <div class="fw-medium" style="font-size:.85rem;">{{ $d->pengguna->nama ?? '-' }}</div>
                        </td>
                        <td style="font-size:.85rem;">{{ Str::limit($d->judul, 40) }}</td>
                        <td>
                            <span class="badge bg-info bg-opacity-10 text-info" style="font-size:.72rem;">{{ \App\Models\DokumenKepegawaian::KATEGORI[$d->kategori] ?? $d->kategori }}</span>
                        </td>
                        <td style="font-size:.85rem;"><i class="bi bi-file-earmark-{{ $d->file_type == 'pdf' ? 'pdf' : 'image' }} text-{{ $d->file_type == 'pdf' ? 'danger' : 'success' }} me-1"></i>{{ strtoupper($d->file_type) }}</td>
                        <td style="font-size:.85rem;">{{ $d->tanggal_dokumen ? $d->tanggal_dokumen->format('d M Y') : '-' }}</td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('admin.kepegawaian.dokumen.show', $d) }}" class="btn btn-sm btn-outline-primary" title="Detail"><i class="bi bi-eye"></i></a>
                                <a href="{{ Storage::url($d->file_path) }}" target="_blank" class="btn btn-sm btn-outline-success" title="Unduh"><i class="bi bi-download"></i></a>
                                <form method="POST" action="{{ route('admin.kepegawaian.dokumen.destroy', $d) }}" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" data-confirm="Hapus dokumen ini?" title="Hapus"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center py-4 text-muted">Belum ada dokumen kepegawaian</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($dokumen->hasPages())
    <div class="card-footer bg-white border-top-0 py-3">
        {{ $dokumen->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection
