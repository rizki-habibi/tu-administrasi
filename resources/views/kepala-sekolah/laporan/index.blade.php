@extends('peran.kepala-sekolah.app')
@section('judul', 'Laporan')

@section('konten')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-1">Laporan</h5>
        <p class="text-muted mb-0" style="font-size:.85rem;">Daftar laporan dari staff</p>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body py-2">
        <form class="d-flex gap-2 align-items-center flex-wrap" method="GET">
            <select name="kategori" class="form-select form-select-sm" style="width:auto;">
                <option value="">Semua Kategori</option>
                @foreach(['surat_masuk','surat_keluar','inventaris','keuangan','kegiatan','lainnya'] as $c)
                    <option value="{{ $c }}" {{ request('kategori') == $c ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$c)) }}</option>
                @endforeach
            </select>
            <select name="status" class="form-select form-select-sm" style="width:auto;">
                <option value="">Semua Status</option>
                @foreach(['draft','submitted','reviewed','completed'] as $s)
                    <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-sm btn-outline-warning"><i class="bi bi-funnel"></i> Filter</button>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr><th>#</th><th>Judul</th><th>Kategori</th><th>Pembuat</th><th>Prioritas</th><th>Status</th><th>Tanggal</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                @forelse($reports as $i => $report)
                    <tr>
                        <td>{{ $reports instanceof \Illuminate\Pagination\LengthAwarePaginator ? $reports->firstItem() + $i : $i + 1 }}</td>
                        <td class="fw-semibold" style="max-width:200px;">{{ \Str::limit($report->judul, 45) }}</td>
                        <td><span class="badge bg-warning bg-opacity-10 text-warning">{{ $report->category_label }}</span></td>
                        <td>{{ $report->user->nama ?? '-' }}</td>
                        <td>
                            @php
                                $prBadge = match($report->prioritas) {
                                    'high' => 'danger', 'medium' => 'warning', default => 'secondary'
                                };
                            @endphp
                            <span class="badge bg-{{ $prBadge }} bg-opacity-10 text-{{ $prBadge }}">{{ ucfirst($report->prioritas ?? '-') }}</span>
                        </td>
                        <td><span class="badge bg-{{ $report->status_badge }} bg-opacity-10 text-{{ $report->status_badge }}">{{ ucfirst($report->status) }}</span></td>
                        <td style="font-size:.8rem;">{{ $report->created_at->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('kepala-sekolah.laporan.show', $report) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-eye"></i></a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center py-4 text-muted">Tidak ada data laporan</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@if($reports instanceof \Illuminate\Pagination\LengthAwarePaginator)
<div class="mt-3 d-flex justify-content-center">{{ $reports->withQueryString()->links() }}</div>
@endif
@endsection
