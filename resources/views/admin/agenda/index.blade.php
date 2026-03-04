@extends('peran.admin.app')
@section('judul', 'Kelola Event')

@section('konten')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <form class="d-flex gap-2 flex-wrap" method="GET">
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
    <a href="{{ route('admin.agenda.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah Event</a>
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
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('admin.agenda.show', $event) }}" class="btn btn-outline-info" title="Detail"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('admin.agenda.edit', $event) }}" class="btn btn-outline-warning" title="Edit"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('admin.agenda.destroy', $event) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus event ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center text-muted py-4">Belum ada event</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $events->links() }}</div>
@endsection
