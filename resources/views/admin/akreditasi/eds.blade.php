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
                <thead><tr><th>#</th><th>Standar</th><th>Komponen</th><th>Kondisi Saat Ini</th><th>Target</th><th>Nilai</th><th>Tahun</th></tr></thead>
                <tbody>
                    @forelse($evaluations ?? [] as $ev)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><span class="badge bg-primary bg-opacity-10 text-primary">{{ $ev->standar }}</span></td>
                        <td class="fw-semibold">{{ $ev->component ?? '-' }}</td>
                        <td>{{ Str::limit($ev->current_condition, 60) }}</td>
                        <td>{{ Str::limit($ev->target, 60) }}</td>
                        <td>
                            @php $score = $ev->score ?? 0; @endphp
                            <span class="badge {{ $score >= 80 ? 'bg-success' : ($score >= 60 ? 'bg-warning text-dark' : 'bg-danger') }}">{{ $score }}</span>
                        </td>
                        <td>{{ $ev->year ?? '-' }}</td>
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
                        <div class="col-md-6"><label class="form-label">Standar <span class="text-danger">*</span></label>
                            <select name="standar" class="form-select" required><option value="">Pilih</option>@for($i=1;$i<=8;$i++)<option value="Standar {{ $i }}">Standar {{ $i }}</option>@endfor</select>
                        </div>
                        <div class="col-md-6"><label class="form-label">Komponen</label><input name="component" class="form-control"></div>
                        <div class="col-12"><label class="form-label">Kondisi Saat Ini <span class="text-danger">*</span></label><textarea name="current_condition" class="form-control" rows="3" required></textarea></div>
                        <div class="col-12"><label class="form-label">Target/Harapan</label><textarea name="target" class="form-control" rows="2"></textarea></div>
                        <div class="col-md-4"><label class="form-label">Nilai (0-100)</label><input type="number" name="score" class="form-control" min="0" max="100"></div>
                        <div class="col-md-4"><label class="form-label">Tahun</label><input name="year" class="form-control" value="{{ date('Y') }}"></div>
                        <div class="col-12"><label class="form-label">Rekomendasi Tindak Lanjut</label><textarea name="recommendation" class="form-control" rows="2"></textarea></div>
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
