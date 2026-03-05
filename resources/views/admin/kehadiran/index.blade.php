@extends('peran.admin.app')
@section('judul', 'Data Kehadiran')

@section('konten')
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Tanggal</label>
                <input type="date" name="date" class="form-control" value="{{ request('date', today()->format('Y-m-d')) }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua</option>
                    @foreach(['hadir','terlambat','izin','sakit','alpha'] as $s)
                        <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Staf</label>
                <select name="pengguna_id" class="form-select">
                    <option value="">Semua</option>
                    @foreach($staffs as $s)
                        <option value="{{ $s->id }}" {{ request('pengguna_id') == $s->id ? 'selected' : '' }}>{{ $s->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <button class="btn btn-primary w-100"><i class="bi bi-search"></i> Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="d-flex justify-content-end mb-3">
    <a href="{{ route('admin.kehadiran.laporan') }}" class="btn btn-outline-success"><i class="bi bi-file-earmark-bar-graph"></i> Laporan Rekap</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Nama Staff</th>
                    <th>Tanggal</th>
                    <th>Masuk</th>
                    <th>Pulang</th>
                    <th>Status</th>
                    <th>Foto Masuk</th>
                    <th>Lokasi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @php $colors = ['hadir'=>'success','terlambat'=>'warning','izin'=>'info','sakit'=>'primary','alpha'=>'danger']; @endphp
                @forelse($attendances as $i => $att)
                <tr>
                    <td>{{ $attendances->firstItem() + $i }}</td>
                    <td>{{ $att->user->nama ?? '-' }}</td>
                    <td>{{ $att->tanggal->format('d/m/Y') }}</td>
                    <td>{{ $att->jam_masuk ?? '-' }}</td>
                    <td>{{ $att->jam_pulang ?? '-' }}</td>
                    <td>
                        <span class="badge bg-{{ $colors[$att->status] ?? 'secondary' }}">{{ ucfirst($att->status) }}</span>
                    </td>
                    <td>
                        @if($att->foto_masuk)
                            <a href="{{ asset('storage/' . $att->foto_masuk) }}" target="_blank">
                                <img src="{{ asset('storage/' . $att->foto_masuk) }}" width="40" height="40" class="rounded" style="object-fit:cover;">
                            </a>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        @if($att->lat_masuk && $att->lng_masuk)
                            <small class="text-muted">{{ number_format($att->lat_masuk, 5) }}, {{ number_format($att->lng_masuk, 5) }}</small>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.kehadiran.show', $att) }}" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="9" class="text-center text-muted py-4">Belum ada data kehadiran</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $attendances->links() }}</div>
@endsection
