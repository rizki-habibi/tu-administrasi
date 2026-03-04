@extends('admin.tata-letak.app')
@section('judul', 'Laporan')

@section('konten')
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="Cari judul..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="kategori" class="form-select">
                    <option value="">Semua Kategori</option>
                    @foreach(['surat_masuk','surat_keluar','inventaris','keuangan','kegiatan','lainnya'] as $c)
                        <option value="{{ $c }}" {{ request('kategori') == $c ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$c)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    @foreach(['draft','submitted','reviewed','completed'] as $s)
                        <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <button class="btn btn-primary w-100"><i class="bi bi-search"></i> Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr><th>#</th><th>Judul</th><th>Pembuat</th><th>Kategori</th><th>Prioritas</th><th>Status</th><th>Tanggal</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                @forelse($reports as $i => $report)
                <tr>
                    <td>{{ $reports->firstItem() + $i }}</td>
                    <td><strong>{{ $report->judul }}</strong></td>
                    <td>{{ $report->user->nama ?? '-' }}</td>
                    <td>{{ ucfirst(str_replace('_',' ',$report->kategori)) }}</td>
                    <td>
                        @php $pColors = ['rendah'=>'success','sedang'=>'warning','tinggi'=>'danger']; @endphp
                        <span class="badge bg-{{ $pColors[$report->prioritas] ?? 'secondary' }}">{{ ucfirst($report->prioritas) }}</span>
                    </td>
                    <td>
                        @php $sColors = ['draft'=>'secondary','submitted'=>'primary','reviewed'=>'warning','completed'=>'success']; @endphp
                        <span class="badge bg-{{ $sColors[$report->status] ?? 'secondary' }}">{{ ucfirst($report->status) }}</span>
                    </td>
                    <td>{{ $report->created_at->format('d/m/Y') }}</td>
                    <td><a href="{{ route('admin.laporan.show', $report) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a></td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center text-muted py-4">Belum ada laporan</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $reports->links() }}</div>
@endsection
