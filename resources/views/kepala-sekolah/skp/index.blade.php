@extends('kepala-sekolah.tata-letak.app')
@section('judul', 'Penilaian SKP')

@section('konten')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-1">Penilaian SKP Staff</h5>
        <p class="text-muted mb-0" style="font-size:.85rem;">{{ $pendingCount }} SKP menunggu penilaian</p>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body py-2">
        <form class="d-flex gap-2 align-items-center flex-wrap" method="GET">
            <select name="status" class="form-select form-select-sm" style="width:auto;">
                <option value="">Semua Status</option>
                @foreach(['draft','diajukan','dinilai','revisi'] as $s)
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
                    <tr><th>#</th><th>Pegawai</th><th>Role</th><th>Periode</th><th>Sasaran Kinerja</th><th>Nilai</th><th>Status</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                @forelse($skps as $i => $skp)
                    <tr>
                        <td>{{ $skps->firstItem() + $i }}</td>
                        <td class="fw-semibold">{{ $skp->user->nama ?? '-' }}</td>
                        <td><span class="badge bg-secondary bg-opacity-10 text-secondary">{{ $skp->user->role_label ?? '-' }}</span></td>
                        <td>{{ $skp->periode === 'januari_juni' ? 'Jan-Jun' : 'Jul-Des' }} {{ $skp->tahun }}</td>
                        <td style="max-width:180px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $skp->sasaran_kinerja }}</td>
                        <td>{{ $skp->nilai_capaian ? number_format($skp->nilai_capaian, 1) : '-' }}</td>
                        <td>{!! '<span class="badge '.$skp->status_badge.'">'.ucfirst($skp->status).'</span>' !!}</td>
                        <td>
                            <a href="{{ route('kepala-sekolah.skp.show', $skp) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-eye me-1"></i>Detail</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center py-4 text-muted">Belum ada data SKP</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="mt-3 d-flex justify-content-center">{{ $skps->withQueryString()->links() }}</div>
@endsection
