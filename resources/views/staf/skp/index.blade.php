@extends('staf.tata-letak.app')
@section('judul', 'SKP Saya')

@section('konten')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-1">Sasaran Kinerja Pegawai (SKP)</h5>
        <p class="text-muted mb-0" style="font-size:.85rem;">Kelola target dan capaian kinerja Anda</p>
    </div>
    <a href="{{ route('staf.skp.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Buat SKP</a>
</div>

<!-- Filter -->
<div class="card mb-3">
    <div class="card-body py-2">
        <form class="d-flex gap-2 align-items-center flex-wrap" method="GET">
            <select name="tahun" class="form-select form-select-sm" style="width:auto;">
                <option value="">Semua Tahun</option>
                @foreach($tahunList as $t)
                    <option value="{{ $t }}" {{ request('tahun') == $t ? 'selected' : '' }}>{{ $t }}</option>
                @endforeach
            </select>
            <select name="status" class="form-select form-select-sm" style="width:auto;">
                <option value="">Semua Status</option>
                @foreach(['draft','diajukan','dinilai','revisi'] as $s)
                    <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-sm btn-outline-primary"><i class="bi bi-funnel"></i> Filter</button>
            @if(request()->hasAny(['tahun','status']))
                <a href="{{ route('staf.skp.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
            @endif
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Periode</th>
                        <th>Tahun</th>
                        <th>Sasaran Kinerja</th>
                        <th>Nilai Capaian</th>
                        <th>Predikat</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($skps as $i => $skp)
                    <tr>
                        <td>{{ $skps->firstItem() + $i }}</td>
                        <td>{{ $skp->periode === 'januari_juni' ? 'Jan - Jun' : 'Jul - Des' }}</td>
                        <td>{{ $skp->tahun }}</td>
                        <td style="max-width:200px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $skp->sasaran_kinerja }}</td>
                        <td>
                            @if($skp->nilai_capaian)
                                <span class="fw-bold" style="color:{{ $skp->nilai_capaian >= 80 ? '#10b981' : ($skp->nilai_capaian >= 60 ? '#f59e0b' : '#ef4444') }}">
                                    {{ number_format($skp->nilai_capaian, 1) }}
                                </span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>{!! $skp->predikat ? '<span class="badge '.$skp->predikat_badge.'">'.$skp->predikat_label.'</span>' : '-' !!}</td>
                        <td>{!! '<span class="badge '.$skp->status_badge.'">'.ucfirst($skp->status).'</span>' !!}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('staf.skp.show', $skp) }}" class="btn btn-sm btn-outline-primary" title="Detail"><i class="bi bi-eye"></i></a>
                                @if(in_array($skp->status, ['draft','revisi']))
                                    <a href="{{ route('staf.skp.edit', $skp) }}" class="btn btn-sm btn-outline-warning" title="Edit"><i class="bi bi-pencil"></i></a>
                                @endif
                                @if($skp->status === 'draft')
                                    <form action="{{ route('staf.skp.destroy', $skp) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" data-confirm="Hapus SKP ini?"><i class="bi bi-trash"></i></button>
                                    </form>
                                @endif
                            </div>
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
