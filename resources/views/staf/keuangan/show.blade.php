@extends('peran.staf.app')
@section('judul', 'Detail Catatan Keuangan')

@section('konten')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-eye"></i> Detail Catatan Keuangan</h4>
    <div>
        <a href="{{ route('staf.keuangan.edit', $catatan) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Ubah</a>
        <form action="{{ route('staf.keuangan.destroy', $catatan) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus catatan ini?')">
            @csrf @method('DELETE')
            <button class="btn btn-danger"><i class="bi bi-trash"></i> Hapus</button>
        </form>
        <a href="{{ route('staf.keuangan.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-3 fw-bold">Kode Transaksi</div>
            <div class="col-md-9"><code>{{ $catatan->kode_transaksi }}</code></div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3 fw-bold">Jenis</div>
            <div class="col-md-9"><span class="badge bg-{{ $catatan->jenis == 'pemasukan' ? 'success' : 'danger' }}">{{ ucfirst($catatan->jenis) }}</span></div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3 fw-bold">Kategori</div>
            <div class="col-md-9">{{ $catatan->kategori }}</div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3 fw-bold">Uraian</div>
            <div class="col-md-9">{{ $catatan->uraian }}</div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3 fw-bold">Jumlah</div>
            <div class="col-md-9 fw-bold text-{{ $catatan->jenis == 'pemasukan' ? 'success' : 'danger' }}">Rp {{ number_format($catatan->jumlah, 0, ',', '.') }}</div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3 fw-bold">Tanggal</div>
            <div class="col-md-9">{{ $catatan->tanggal?->format('d F Y') }}</div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3 fw-bold">Status</div>
            <div class="col-md-9">
                @php $sc = ['draft'=>'secondary','verified'=>'success','rejected'=>'danger']; @endphp
                <span class="badge bg-{{ $sc[$catatan->status] ?? 'secondary' }}">{{ ucfirst($catatan->status) }}</span>
            </div>
        </div>
        @if($catatan->bukti_path)
        <div class="row mb-3">
            <div class="col-md-3 fw-bold">Bukti</div>
            <div class="col-md-9"><a href="{{ asset('storage/' . $catatan->bukti_path) }}" target="_blank" class="btn btn-sm btn-outline-primary"><i class="bi bi-download"></i> Lihat Bukti</a></div>
        </div>
        @endif
    </div>
</div>
@endsection
