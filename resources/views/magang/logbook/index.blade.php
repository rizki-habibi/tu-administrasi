@extends('peran.magang.app')
@section('judul', 'Logbook Harian')

@section('konten')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-1">Logbook Harian</h5>
        <p class="text-muted mb-0" style="font-size:.82rem;">{{ $bulanIni }} logbook bulan ini</p>
    </div>
    <a href="{{ route('magang.logbook.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle me-1"></i>Tulis Logbook</a>
</div>

@if($logbookHariIni)
<div class="alert alert-info d-flex align-items-center" style="border-radius:10px;border-left:4px solid #0891b2;">
    <i class="bi bi-journal-check me-2"></i>
    <span>Logbook hari ini sudah ditulis. <a href="{{ route('magang.logbook.show', $logbookHariIni) }}">Lihat &rarr;</a></span>
</div>
@endif

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th>Tanggal</th><th>Jam</th><th>Kegiatan</th><th>Status</th><th></th></tr>
                </thead>
                <tbody>
                    @forelse($logbook as $item)
                    <tr>
                        <td class="fw-semibold">{{ $item->tanggal->format('d/m/Y') }}</td>
                        <td>{{ $item->jam_mulai ? substr($item->jam_mulai,0,5) : '-' }} - {{ $item->jam_selesai ? substr($item->jam_selesai,0,5) : '-' }}</td>
                        <td style="max-width:300px;" class="text-truncate">{{ $item->kegiatan }}</td>
                        <td><span class="badge bg-{{ $item->status === 'final' ? 'success' : 'warning' }}">{{ ucfirst($item->status) }}</span></td>
                        <td>
                            <a href="{{ route('magang.logbook.show', $item) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                            @if($item->status !== 'final')
                                <a href="{{ route('magang.logbook.edit', $item) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">Belum ada logbook. Mulai tulis logbook hari ini!</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-3 py-2">{{ $logbook->links() }}</div>
    </div>
</div>
@endsection
