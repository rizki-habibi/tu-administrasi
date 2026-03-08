@extends('peran.staf.app')
@section('judul', 'Pelanggaran Siswa')

@section('konten')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-exclamation-triangle"></i> Pelanggaran Siswa</h4>
    <a href="{{ route('staf.pelanggaran.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Catat Pelanggaran</a>
</div>

<div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Jenis</label>
                <select name="jenis" class="form-select">
                    <option value="">Semua</option>
                    @foreach(['ringan'=>'Ringan','sedang'=>'Sedang','berat'=>'Berat'] as $k=>$v)
                        <option value="{{ $k }}" {{ request('jenis') == $k ? 'selected' : '' }}>{{ $v }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Cari</label>
                <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Nama siswa/deskripsi...">
            </div>
            <div class="col-md-4">
                <button class="btn btn-primary w-100"><i class="bi bi-search"></i> Saring</button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr><th>Tanggal</th><th>Siswa</th><th>Jenis</th><th>Deskripsi</th><th>Tindakan</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                @forelse($pelanggaran as $p)
                <tr>
                    <td>{{ $p->tanggal?->format('d/m/Y') }}</td>
                    <td>{{ $p->student?->nama ?? '-' }}</td>
                    <td>
                        @php $jc = ['ringan'=>'warning','sedang'=>'orange','berat'=>'danger']; @endphp
                        <span class="badge bg-{{ $jc[$p->jenis] ?? 'secondary' }}">{{ ucfirst($p->jenis) }}</span>
                    </td>
                    <td>{{ Str::limit($p->deskripsi, 50) }}</td>
                    <td>{{ Str::limit($p->tindakan ?? '-', 30) }}</td>
                    <td>
                        <a href="{{ route('staf.pelanggaran.show', $p) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-4">Belum ada data pelanggaran</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $pelanggaran->links() }}</div>
@endsection
