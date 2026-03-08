@extends('peran.staf.app')
@section('judul', 'Peminjaman Buku')

@section('konten')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-journal-arrow-up"></i> Peminjaman Buku</h4>
    <a href="{{ route('staf.peminjaman-buku.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Catat Peminjaman</a>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm bg-warning bg-opacity-10">
            <div class="card-body text-center">
                <div class="text-warning fw-bold fs-5">{{ $totalDipinjam }}</div>
                <small class="text-muted">Sedang Dipinjam</small>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm bg-danger bg-opacity-10">
            <div class="card-body text-center">
                <div class="text-danger fw-bold fs-5">{{ $totalTerlambat }}</div>
                <small class="text-muted">Terlambat</small>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua</option>
                    <option value="dipinjam" {{ request('status') == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                    <option value="dikembalikan" {{ request('status') == 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                    <option value="terlambat" {{ request('status') == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Cari</label>
                <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Nama peminjam/judul buku...">
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
                <tr><th>Buku</th><th>Peminjam</th><th>Kelas</th><th>Tgl Pinjam</th><th>Tgl Kembali</th><th>Status</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                @forelse($peminjaman as $p)
                <tr>
                    <td>{{ Str::limit($p->buku?->judul ?? '-', 30) }}</td>
                    <td>{{ $p->nama_peminjam }}</td>
                    <td>{{ $p->kelas ?? '-' }}</td>
                    <td>{{ $p->tanggal_pinjam?->format('d/m/Y') }}</td>
                    <td>
                        @if($p->tanggal_kembali_aktual)
                            {{ $p->tanggal_kembali_aktual->format('d/m/Y') }}
                        @else
                            {{ $p->tanggal_kembali_rencana?->format('d/m/Y') }}
                            @if($p->status == 'dipinjam' && $p->tanggal_kembali_rencana < now())
                                <span class="badge bg-danger">Terlambat!</span>
                            @endif
                        @endif
                    </td>
                    <td>
                        @php $sc = ['dipinjam'=>'warning','dikembalikan'=>'success','terlambat'=>'danger']; @endphp
                        <span class="badge bg-{{ $sc[$p->status] ?? 'secondary' }}">{{ ucfirst($p->status) }}</span>
                    </td>
                    <td>
                        @if($p->status == 'dipinjam')
                            <form action="{{ route('staf.peminjaman-buku.kembali', $p) }}" method="POST" class="d-inline" onsubmit="return confirm('Konfirmasi pengembalian buku?')">
                                @csrf @method('PATCH')
                                <button class="btn btn-sm btn-success"><i class="bi bi-check-lg"></i> Kembalikan</button>
                            </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-4">Belum ada peminjaman</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $peminjaman->links() }}</div>
@endsection
