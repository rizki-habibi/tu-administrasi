@extends('staf.tata-letak.app')
@section('judul', 'Laporan')

@section('konten')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-file-earmark-text"></i> Laporan Saya</h4>
    <a href="{{ route('staf.laporan.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Buat Laporan</a>
</div>

<div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Kategori</label>
                <select name="category" class="form-select">
                    <option value="">Semua</option>
                    @foreach(['surat_masuk','surat_keluar','inventaris','keuangan','kegiatan','lainnya'] as $c)
                        <option value="{{ $c }}" {{ request('kategori') == $c ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$c)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua</option>
                    @foreach(['draft','submitted','reviewed','completed'] as $s)
                        <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Cari</label>
                <input type="text" name="search" class="form-control" placeholder="Judul..." value="{{ request('search') }}">
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
                <tr><th>Tanggal</th><th>Judul</th><th>Kategori</th><th>Prioritas</th><th>Status</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                @forelse($reports as $report)
                <tr>
                    <td>{{ $report->created_at->format('d/m/Y') }}</td>
                    <td>{{ Str::limit($report->judul, 40) }}</td>
                    <td><span class="badge bg-secondary">{{ ucfirst(str_replace('_',' ',$report->kategori)) }}</span></td>
                    <td>
                        @php $pc = ['rendah'=>'success','sedang'=>'warning','tinggi'=>'danger']; @endphp
                        <span class="badge bg-{{ $pc[$report->prioritas] ?? 'secondary' }}">{{ ucfirst($report->prioritas) }}</span>
                    </td>
                    <td>
                        @php $sc = ['draft'=>'secondary','submitted'=>'primary','reviewed'=>'info','completed'=>'success']; @endphp
                        <span class="badge bg-{{ $sc[$report->status] ?? 'secondary' }}">{{ ucfirst($report->status) }}</span>
                    </td>
                    <td>
                        <a href="{{ route('staf.laporan.show', $report) }}" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>
                        @if($report->status == 'draft')
                            <a href="{{ route('staf.laporan.edit', $report) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('staf.laporan.destroy', $report) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus laporan?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-4">Belum ada laporan</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $reports->links() }}</div>
@endsection
