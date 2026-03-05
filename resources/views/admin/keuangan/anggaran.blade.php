@extends('peran.admin.app')
@section('judul', 'RKAS / Anggaran')

@section('konten')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color:#1e293b;">RKAS / Anggaran Sekolah</h4>
        <p class="text-muted mb-0" style="font-size:.85rem;">Rencana Kegiatan dan Anggaran Sekolah</p>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBudgetModal"><i class="bi bi-plus-lg me-1"></i> Tambah Anggaran</button>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr><th>No</th><th>Nama Anggaran</th><th>Sumber Dana</th><th>Tahun</th><th class="text-end">Total</th><th class="text-end">Terpakai</th><th class="text-end">Sisa</th><th>Progres</th></tr>
                </thead>
                <tbody>
                    @forelse($budgets as $b)
                    @php $pct = $b->total_anggaran > 0 ? round($b->terpakai / $b->total_anggaran * 100) : 0; @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="fw-semibold">{{ $b->nama_anggaran }}</td>
                        <td>{{ $b->sumber_dana ?? '-' }}</td>
                        <td>{{ $b->tahun_anggaran ?? '-' }}</td>
                        <td class="text-end">Rp {{ number_format($b->total_anggaran, 0, ',', '.') }}</td>
                        <td class="text-end text-danger">Rp {{ number_format($b->terpakai, 0, ',', '.') }}</td>
                        <td class="text-end text-success">Rp {{ number_format($b->total_anggaran - $b->terpakai, 0, ',', '.') }}</td>
                        <td style="min-width:120px;">
                            <div class="progress" style="height:8px;border-radius:4px;">
                                <div class="progress-bar {{ $pct > 90 ? 'bg-danger' : ($pct > 70 ? 'bg-warning' : 'bg-success') }}" style="width:{{ $pct }}%"></div>
                            </div>
                            <small class="text-muted">{{ $pct }}% terpakai</small>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center py-4 text-muted">Belum ada data anggaran</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Budget Modal -->
<div class="modal fade" id="addBudgetModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.keuangan.anggaran.store') }}" method="POST">
                @csrf
                <div class="modal-header"><h5 class="modal-title">Tambah Anggaran</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label">Nama Anggaran <span class="text-danger">*</span></label><input name="nama_anggaran" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label">Sumber Dana</label><input name="sumber_dana" class="form-control" placeholder="BOS, APBD, Komite"></div>
                    <div class="mb-3"><label class="form-label">Tahun Anggaran</label><input name="tahun_anggaran" class="form-control" value="{{ date('Y') }}"></div>
                    <div class="mb-3"><label class="form-label">Total Anggaran (Rp) <span class="text-danger">*</span></label><input type="number" name="total_anggaran" class="form-control" min="0" required></div>
                    <div class="mb-3"><label class="form-label">Keterangan</label><textarea name="keterangan" class="form-control" rows="2"></textarea></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
