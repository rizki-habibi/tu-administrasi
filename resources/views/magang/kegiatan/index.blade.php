@extends('peran.magang.app')
@section('judul', 'Kegiatan Magang')

@section('konten')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-1">Kegiatan Magang</h5>
        <p class="text-muted mb-0" style="font-size:.82rem;">Daftar tugas & kegiatan selama magang</p>
    </div>
    <a href="{{ route('magang.kegiatan.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle me-1"></i>Tambah Kegiatan</a>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm text-center py-3">
            <div class="fw-bold fs-4 text-primary">{{ $stats['berlangsung'] ?? 0 }}</div>
            <small class="text-muted">Berlangsung</small>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm text-center py-3">
            <div class="fw-bold fs-4 text-success">{{ $stats['selesai'] ?? 0 }}</div>
            <small class="text-muted">Selesai</small>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm text-center py-3">
            <div class="fw-bold fs-4 text-secondary">{{ $stats['belum_mulai'] ?? 0 }}</div>
            <small class="text-muted">Belum Mulai</small>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th>Judul</th><th>Tanggal</th><th>Prioritas</th><th>Status</th><th></th></tr>
                </thead>
                <tbody>
                    @forelse($kegiatan as $item)
                    <tr>
                        <td class="fw-semibold">{{ $item->judul }}</td>
                        <td>{{ $item->tanggal_mulai?->format('d/m/Y') }} @if($item->tanggal_selesai)— {{ $item->tanggal_selesai->format('d/m/Y') }}@endif</td>
                        <td>
                            @php $warna = ['tinggi'=>'danger','sedang'=>'warning','rendah'=>'info']; @endphp
                            <span class="badge bg-{{ $warna[$item->prioritas] ?? 'secondary' }}">{{ ucfirst($item->prioritas) }}</span>
                        </td>
                        <td>
                            @php $ws = ['selesai'=>'success','berlangsung'=>'primary','belum_mulai'=>'secondary']; @endphp
                            <span class="badge bg-{{ $ws[$item->status] ?? 'secondary' }}">{{ str_replace('_',' ',ucfirst($item->status)) }}</span>
                        </td>
                        <td>
                            <a href="{{ route('magang.kegiatan.show', $item) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('magang.kegiatan.edit', $item) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">Belum ada kegiatan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-3 py-2">{{ $kegiatan->links() }}</div>
    </div>
</div>
@endsection
