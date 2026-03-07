@extends('peran.magang.app')
@section('judul', 'Pengajuan Izin')

@section('konten')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-1">Pengajuan Izin</h5>
        <p class="text-muted mb-0" style="font-size:.82rem;">Riwayat izin & sakit selama magang</p>
    </div>
    <a href="{{ route('magang.izin.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle me-1"></i>Ajukan Izin</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th>Tanggal</th><th>Jenis</th><th>Alasan</th><th>Status</th><th></th></tr>
                </thead>
                <tbody>
                    @forelse($leaveRequests as $item)
                    <tr>
                        <td class="fw-semibold">{{ $item->tanggal_mulai?->format('d/m/Y') }}</td>
                        <td><span class="badge bg-{{ $item->jenis === 'sakit' ? 'danger' : 'info' }}">{{ ucfirst($item->jenis) }}</span></td>
                        <td style="max-width:280px;" class="text-truncate">{{ $item->alasan }}</td>
                        <td>
                            @php $ws = ['approved'=>'success','rejected'=>'danger','pending'=>'warning']; @endphp
                            <span class="badge bg-{{ $ws[$item->status] ?? 'secondary' }}">{{ ucfirst($item->status) }}</span>
                        </td>
                        <td>
                            <a href="{{ route('magang.izin.show', $item) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                            @if($item->status === 'pending')
                            <form action="{{ route('magang.izin.destroy', $item) }}" method="POST" class="d-inline form-hapus">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-x-circle"></i></button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">Belum ada pengajuan izin.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-3 py-2">{{ $leaveRequests->links() }}</div>
    </div>
</div>
@endsection
