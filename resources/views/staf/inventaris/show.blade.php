@extends('peran.staf.app')
@section('judul', 'Detail Inventaris')

@section('konten')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('staf.inventaris.index') }}" class="btn btn-outline-secondary btn-sm me-3"><i class="bi bi-arrow-left"></i></a>
    <h4 class="fw-bold mb-0" style="color:#1e293b;">Detail Barang Inventaris</h4>
</div>

<div class="row g-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body p-4">
                <div class="mb-3">
                    <code class="me-2" style="font-size:0.9rem;">{{ $item->kode_barang }}</code>
                    @if($item->kondisi == 'baik')
                    <span class="badge bg-success">Baik</span>
                    @elseif($item->kondisi == 'rusak_ringan')
                    <span class="badge bg-warning text-dark">Rusak Ringan</span>
                    @else
                    <span class="badge bg-danger">Rusak Berat</span>
                    @endif
                </div>
                <h5 class="fw-bold mb-3">{{ $item->nama_barang }}</h5>
                <table class="table table-borderless mb-0">
                    <tr><td class="text-muted" width="180">Kategori</td><td>{{ ucfirst($item->kategori ?? '-') }}</td></tr>
                    <tr><td class="text-muted">Lokasi</td><td>{{ $item->lokasi ?? '-' }}</td></tr>
                    <tr><td class="text-muted">Jumlah</td><td>{{ $item->jumlah ?? 0 }} unit</td></tr>
                    <tr><td class="text-muted">Sumber Dana</td><td>{{ $item->sumber_dana ?? '-' }}</td></tr>
                    <tr><td class="text-muted">Tanggal Perolehan</td><td>{{ $item->tanggal_perolehan ? \Carbon\Carbon::parse($item->tanggal_perolehan)->format('d/m/Y') : '-' }}</td></tr>
                    <tr><td class="text-muted">Harga Perolehan</td><td>Rp {{ number_format($item->harga_perolehan ?? 0, 0, ',', '.') }}</td></tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        @if($item->foto)
        <div class="card mb-3">
            <img src="{{ Storage::url($item->foto) }}" class="card-img-top" alt="Foto Barang">
        </div>
        @endif
        <div class="card">
            <div class="card-header bg-transparent"><h6 class="mb-0"><i class="bi bi-exclamation-triangle me-1"></i> Riwayat Kerusakan</h6></div>
            <div class="card-body">
                @forelse($item->damageReports ?? [] as $r)
                <div class="border-bottom pb-2 mb-2">
                    <small class="text-muted">{{ $r->created_at->format('d/m/Y') }}</small>
                    <p class="mb-0 small">{{ $r->deskripsi_kerusakan }}</p>
                    <small class="text-muted">Pelapor: {{ $r->reporter->nama ?? '-' }}</small>
                </div>
                @empty
                <p class="text-muted mb-0 small">Tidak ada laporan kerusakan</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
