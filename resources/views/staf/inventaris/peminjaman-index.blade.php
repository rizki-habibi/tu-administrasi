@extends('peran.staf.app')
@section('judul', 'Peminjaman Fasilitas')

@section('konten')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-building"></i> Peminjaman Fasilitas</h4>
    <a href="{{ route('staf.peminjaman.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Ajukan Peminjaman</a>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm bg-warning bg-opacity-10">
            <div class="card-body text-center">
                <div class="text-warning fw-bold fs-5">{{ $menunggu }}</div>
                <small class="text-muted">Menunggu Persetujuan</small>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm bg-success bg-opacity-10">
            <div class="card-body text-center">
                <div class="text-success fw-bold fs-5">{{ $hariIni }}</div>
                <small class="text-muted">Disetujui Hari Ini</small>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua</option>
                    @foreach(['pending'=>'Menunggu','disetujui'=>'Disetujui','ditolak'=>'Ditolak','selesai'=>'Selesai'] as $k=>$v)
                        <option value="{{ $k }}" {{ request('status') == $k ? 'selected' : '' }}>{{ $v }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Jenis</label>
                <select name="jenis" class="form-select">
                    <option value="">Semua</option>
                    @foreach(\App\Models\PeminjamanFasilitas::JENIS as $k=>$v)
                        <option value="{{ $k }}" {{ request('jenis') == $k ? 'selected' : '' }}>{{ $v }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Cari</label>
                <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Fasilitas/peminjam...">
            </div>
            <div class="col-md-3">
                <button class="btn btn-primary w-100"><i class="bi bi-search"></i> Saring</button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr><th>Fasilitas</th><th>Peminjam</th><th>Tanggal</th><th>Waktu</th><th>Keperluan</th><th>Status</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                @forelse($peminjaman as $p)
                <tr>
                    <td>{{ $p->nama_fasilitas }}</td>
                    <td>{{ $p->peminjam_nama }}</td>
                    <td>{{ $p->tanggal?->format('d/m/Y') }}</td>
                    <td>{{ $p->jam_mulai }} - {{ $p->jam_selesai }}</td>
                    <td>{{ Str::limit($p->keperluan, 30) }}</td>
                    <td>{!! $p->status_badge !!}</td>
                    <td>
                        <a href="{{ route('staf.peminjaman.show', $p) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                        @if($p->status === 'pending')
                            <form action="{{ route('staf.peminjaman.setujui', $p) }}" method="POST" class="d-inline" onsubmit="return confirm('Setujui peminjaman ini?')">
                                @csrf @method('PATCH')
                                <button class="btn btn-sm btn-success"><i class="bi bi-check-lg"></i></button>
                            </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-4">Belum ada peminjaman fasilitas</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $peminjaman->links() }}</div>
@endsection
