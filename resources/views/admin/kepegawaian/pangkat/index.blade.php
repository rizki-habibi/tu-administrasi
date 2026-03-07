@extends('peran.admin.app')
@section('judul', 'Riwayat Pangkat')

@section('konten')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
        <h5 class="fw-bold mb-1">Riwayat Pangkat</h5>
        <p class="text-muted mb-0" style="font-size:.82rem;">Manajemen riwayat pangkat & golongan pegawai</p>
    </div>
    <a href="{{ route('admin.kepegawaian.pangkat.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>Tambah Riwayat</a>
</div>

<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari pangkat, golongan, nomor SK..." value="{{ request('search') }}">
            </div>
            <div class="col-md-4">
                <select name="pegawai" class="form-select form-select-sm">
                    <option value="">Semua Pegawai</option>
                    @foreach($pegawaiList as $p)
                        <option value="{{ $p->id }}" {{ request('pegawai') == $p->id ? 'selected' : '' }}>{{ $p->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 d-flex gap-1">
                <button class="btn btn-primary btn-sm flex-fill"><i class="bi bi-search me-1"></i>Cari</button>
                <a href="{{ route('admin.kepegawaian.pangkat.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-counterclockwise"></i></a>
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
                        <th style="font-size:.78rem;text-transform:uppercase;letter-spacing:.5px;color:#64748b;">Pangkat</th>
                        <th style="font-size:.78rem;text-transform:uppercase;letter-spacing:.5px;color:#64748b;">Golongan</th>
                        <th style="font-size:.78rem;text-transform:uppercase;letter-spacing:.5px;color:#64748b;">TMT Pangkat</th>
                        <th style="font-size:.78rem;text-transform:uppercase;letter-spacing:.5px;color:#64748b;">Jenis</th>
                        <th style="font-size:.78rem;text-transform:uppercase;letter-spacing:.5px;color:#64748b;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($riwayat as $i => $r)
                    <tr>
                        <td class="ps-3" style="font-size:.82rem;">{{ $riwayat->firstItem() + $i }}</td>
                        <td>
                            <div class="fw-medium" style="font-size:.85rem;">{{ $r->pengguna->nama ?? '-' }}</div>
                        </td>
                        <td style="font-size:.85rem;">{{ $r->pangkat }}</td>
                        <td><span class="badge bg-primary bg-opacity-10 text-primary" style="font-size:.75rem;">{{ $r->golongan }}</span></td>
                        <td style="font-size:.85rem;">{{ $r->tmt_pangkat->format('d M Y') }}</td>
                        <td style="font-size:.85rem;">{{ ucfirst($r->jenis_kenaikan ?? '-') }}</td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('admin.kepegawaian.pangkat.show', $r) }}" class="btn btn-sm btn-outline-primary" title="Detail"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('admin.kepegawaian.pangkat.edit', $r) }}" class="btn btn-sm btn-outline-warning" title="Ubah"><i class="bi bi-pencil"></i></a>
                                <form method="POST" action="{{ route('admin.kepegawaian.pangkat.destroy', $r) }}" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" data-confirm="Hapus riwayat pangkat ini?" title="Hapus"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center py-4 text-muted">Belum ada data riwayat pangkat</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($riwayat->hasPages())
    <div class="card-footer bg-white border-top-0 py-3">
        {{ $riwayat->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection
