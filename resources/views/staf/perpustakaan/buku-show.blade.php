@extends('peran.staf.app')
@section('judul', 'Detail Buku')

@section('konten')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-book"></i> Detail Buku</h4>
    <div>
        <a href="{{ route('staf.buku.edit', $buku) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Ubah</a>
        <a href="{{ route('staf.buku.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent fw-bold">Informasi Buku</div>
            <div class="card-body">
                <div class="row mb-2"><div class="col-5 text-muted">Kode Buku</div><div class="col-7"><code>{{ $buku->kode_buku }}</code></div></div>
                <div class="row mb-2"><div class="col-5 text-muted">Judul</div><div class="col-7 fw-bold">{{ $buku->judul }}</div></div>
                <div class="row mb-2"><div class="col-5 text-muted">Pengarang</div><div class="col-7">{{ $buku->pengarang }}</div></div>
                <div class="row mb-2"><div class="col-5 text-muted">Penerbit</div><div class="col-7">{{ $buku->penerbit ?? '-' }}</div></div>
                <div class="row mb-2"><div class="col-5 text-muted">Tahun Terbit</div><div class="col-7">{{ $buku->tahun_terbit ?? '-' }}</div></div>
                <div class="row mb-2"><div class="col-5 text-muted">ISBN</div><div class="col-7">{{ $buku->isbn ?? '-' }}</div></div>
                <div class="row mb-2"><div class="col-5 text-muted">Kategori</div><div class="col-7"><span class="badge bg-info">{{ \App\Models\BukuPerpustakaan::KATEGORI[$buku->kategori] ?? $buku->kategori }}</span></div></div>
                <div class="row mb-2"><div class="col-5 text-muted">Lokasi Rak</div><div class="col-7">{{ $buku->lokasi_rak ?? '-' }}</div></div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent fw-bold">Stok & Kondisi</div>
            <div class="card-body">
                <div class="row mb-2"><div class="col-5 text-muted">Total</div><div class="col-7 fw-bold">{{ $buku->jumlah_total }} eksemplar</div></div>
                <div class="row mb-2"><div class="col-5 text-muted">Tersedia</div><div class="col-7"><span class="badge bg-{{ $buku->jumlah_tersedia > 0 ? 'success' : 'danger' }} fs-6">{{ $buku->jumlah_tersedia }}</span></div></div>
                <div class="row mb-2"><div class="col-5 text-muted">Dipinjam</div><div class="col-7">{{ $buku->jumlah_total - $buku->jumlah_tersedia }}</div></div>
                <div class="row mb-2"><div class="col-5 text-muted">Kondisi</div><div class="col-7">
                    @php $kc = ['baik'=>'success','rusak_ringan'=>'warning','rusak_berat'=>'danger']; @endphp
                    <span class="badge bg-{{ $kc[$buku->kondisi] ?? 'secondary' }}">{{ ucfirst(str_replace('_',' ',$buku->kondisi)) }}</span>
                </div></div>
                <div class="row mb-2"><div class="col-5 text-muted">Harga</div><div class="col-7">{{ $buku->harga ? 'Rp '.number_format($buku->harga, 0, ',', '.') : '-' }}</div></div>
                <div class="row mb-2"><div class="col-5 text-muted">Sumber Dana</div><div class="col-7">{{ $buku->sumber_dana ? (\App\Models\BukuPerpustakaan::SUMBER_DANA[$buku->sumber_dana] ?? $buku->sumber_dana) : '-' }}</div></div>
                @if($buku->keterangan)
                <div class="row mb-2"><div class="col-5 text-muted">Keterangan</div><div class="col-7">{{ $buku->keterangan }}</div></div>
                @endif
            </div>
        </div>
    </div>
</div>

@if($buku->peminjaman->count())
<div class="card border-0 shadow-sm mt-4">
    <div class="card-header bg-transparent fw-bold">Riwayat Peminjaman</div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr><th>Peminjam</th><th>Kelas</th><th>Tgl Pinjam</th><th>Tgl Kembali</th><th>Status</th></tr>
            </thead>
            <tbody>
                @foreach($buku->peminjaman->sortByDesc('created_at') as $p)
                <tr>
                    <td>{{ $p->nama_peminjam }}</td>
                    <td>{{ $p->kelas ?? '-' }}</td>
                    <td>{{ $p->tanggal_pinjam?->format('d/m/Y') }}</td>
                    <td>{{ $p->tanggal_kembali_aktual?->format('d/m/Y') ?? $p->tanggal_kembali_rencana?->format('d/m/Y').' (rencana)' }}</td>
                    <td>
                        @php $sc = ['dipinjam'=>'warning','dikembalikan'=>'success','terlambat'=>'danger']; @endphp
                        <span class="badge bg-{{ $sc[$p->status] ?? 'secondary' }}">{{ ucfirst($p->status) }}</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection
