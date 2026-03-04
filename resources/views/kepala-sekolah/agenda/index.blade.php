@extends('peran.kepala-sekolah.app')
@section('judul', 'Agenda & Kegiatan')

@section('konten')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0"><i class="bi bi-calendar-event me-2"></i>Agenda & Kegiatan</h5>
    <form class="d-flex gap-2" method="GET">
        <select name="jenis" class="form-select" style="width: auto;">
            <option value="">Semua Tipe</option>
            @foreach(['rapat','kegiatan','upacara','pelatihan','lainnya'] as $t)
                <option value="{{ $t }}" {{ request('jenis') == $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
            @endforeach
        </select>
        <input type="month" name="month" class="form-control" value="{{ request('month') }}" style="width: auto;">
        <button class="btn btn-primary"><i class="bi bi-search"></i> Filter</button>
    </form>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Judul</th>
                    <th>Tanggal</th>
                    <th>Waktu</th>
                    <th>Lokasi</th>
                    <th>Tipe</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $typeColors = ['rapat'=>'primary','kegiatan'=>'success','upacara'=>'info','pelatihan'=>'warning','lainnya'=>'secondary'];
                    $statusColors = ['upcoming'=>'info','ongoing'=>'primary','completed'=>'success','cancelled'=>'danger'];
                @endphp
                @forelse($events as $i => $event)
                <tr>
                    <td>{{ $events->firstItem() + $i }}</td>
                    <td><strong>{{ $event->judul }}</strong></td>
                    <td>{{ $event->tanggal_acara->format('d/m/Y') }}</td>
                    <td>{{ $event->waktu_mulai }} - {{ $event->waktu_selesai }}</td>
                    <td>{{ $event->lokasi ?? '-' }}</td>
                    <td><span class="badge bg-{{ $typeColors[$event->jenis] ?? 'secondary' }}">{{ ucfirst($event->jenis) }}</span></td>
                    <td><span class="badge bg-{{ $statusColors[$event->status] ?? 'secondary' }}">{{ ucfirst($event->status) }}</span></td>
                    <td>
                        <a href="{{ route('kepala-sekolah.agenda.show', $event) }}" class="btn btn-sm btn-outline-info" title="Detail"><i class="bi bi-eye"></i></a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center text-muted py-4">Belum ada agenda</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $events->links() }}</div>
@endsection
