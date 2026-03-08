@extends('peran.staf.app')
@section('judul', 'Prestasi Siswa')

@section('konten')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-trophy"></i> Prestasi Siswa</h4>
    <a href="{{ route('staf.prestasi.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Catat Prestasi</a>
</div>

<div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Tingkat</label>
                <select name="tingkat" class="form-select">
                    <option value="">Semua</option>
                    @foreach(['sekolah','kecamatan','kabupaten','provinsi','nasional','internasional'] as $t)
                        <option value="{{ $t }}" {{ request('tingkat') == $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Cari</label>
                <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Judul/nama siswa...">
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
                <tr><th>Tanggal</th><th>Siswa</th><th>Prestasi</th><th>Tingkat</th><th>Jenis</th><th>Hasil</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                @forelse($prestasi as $p)
                <tr>
                    <td>{{ $p->tanggal?->format('d/m/Y') }}</td>
                    <td>{{ $p->student?->nama ?? '-' }}</td>
                    <td>{{ Str::limit($p->judul, 40) }}</td>
                    <td><span class="badge bg-info">{{ ucfirst($p->tingkat) }}</span></td>
                    <td>{{ ucfirst(str_replace('_',' ',$p->jenis)) }}</td>
                    <td>{{ $p->hasil ?? '-' }}</td>
                    <td>
                        <a href="{{ route('staf.prestasi.show', $p) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-4">Belum ada data prestasi</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $prestasi->links() }}</div>
@endsection
