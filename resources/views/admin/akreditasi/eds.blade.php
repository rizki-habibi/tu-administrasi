@extends('layouts.admin')
@section('title', 'Evaluasi Diri Sekolah')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color:#1e293b;">Evaluasi Diri Sekolah (EDS)</h4>
        <p class="text-muted mb-0" style="font-size:.85rem;">Penilaian mandiri sekolah untuk peningkatan mutu</p>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEDSModal"><i class="bi bi-plus-lg me-1"></i> Tambah Evaluasi</button>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead><tr><th>#</th><th>Aspek</th><th>Kondisi Saat Ini</th><th>Target</th><th>Program Tindak Lanjut</th><th>Tahun</th><th>Status</th></tr></thead>
                <tbody>
                    @forelse($evaluations ?? [] as $ev)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><span class="badge bg-primary bg-opacity-10 text-primary">{{ $ev->aspek }}</span></td>
                        <td>{{ Str::limit($ev->kondisi_saat_ini, 60) }}</td>
                        <td>{{ Str::limit($ev->target, 60) }}</td>
                        <td>{{ Str::limit($ev->program_tindak_lanjut, 60) }}</td>
                        <td>{{ $ev->tahun ?? '-' }}</td>
                        <td>
                            @if($ev->status=='final')<span class="badge bg-success">Final</span>
                            @else<span class="badge bg-warning text-dark">Draft</span>@endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-4 text-muted"><i class="bi bi-inbox" style="font-size:2rem;"></i><br>Belum ada data EDS</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add EDS Modal -->
<div class="modal fade" id="addEDSModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('admin.akreditasi.eds.store') }}" method="POST">
                @csrf
                <div class="modal-header"><h5 class="modal-title">Tambah Evaluasi Diri</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6"><label class="form-label">Aspek <span class="text-danger">*</span></label>
                            <input name="aspek" class="form-control" placeholder="Aspek yang dievaluasi" required>
                        </div>
                        <div class="col-md-6"><label class="form-label">Tahun <span class="text-danger">*</span></label><input name="tahun" class="form-control" value="{{ date('Y') }}" required></div>
                        <div class="col-12"><label class="form-label">Kondisi Saat Ini</label><textarea name="kondisi_saat_ini" class="form-control" rows="3"></textarea></div>
                        <div class="col-12"><label class="form-label">Target/Harapan</label><textarea name="target" class="form-control" rows="2"></textarea></div>
                        <div class="col-12"><label class="form-label">Program Tindak Lanjut</label><textarea name="program_tindak_lanjut" class="form-control" rows="2"></textarea></div>
                    </div>
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
