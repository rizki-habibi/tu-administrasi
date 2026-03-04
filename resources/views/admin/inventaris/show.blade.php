@extends('admin.tata-letak.app')
@section('judul', 'Detail Inventaris')

@section('konten')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('admin.inventaris.index') }}" class="btn btn-outline-secondary btn-sm me-3"><i class="bi bi-arrow-left"></i></a>
    <h4 class="fw-bold mb-0" style="color:#1e293b;">Detail Inventaris</h4>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label text-muted">Kode Barang</label><p class="fw-semibold"><code>{{ $inventaris->kode_barang }}</code></p></div>
                    <div class="col-md-6"><label class="form-label text-muted">Nama Barang</label><p class="fw-semibold">{{ $inventaris->nama_barang }}</p></div>
                    <div class="col-md-4"><label class="form-label text-muted">Kategori</label><p><span class="badge bg-primary bg-opacity-10 text-primary">{{ ucfirst(str_replace('_',' ',$inventaris->kategori)) }}</span></p></div>
                    <div class="col-md-4"><label class="form-label text-muted">Lokasi</label><p>{{ $inventaris->lokasi ?? '-' }}</p></div>
                    <div class="col-md-4"><label class="form-label text-muted">Kondisi</label><p>
                        @if($inventaris->kondisi=='baik')<span class="badge bg-success">Baik</span>
                        @elseif($inventaris->kondisi=='rusak_ringan')<span class="badge bg-warning text-dark">Rusak Ringan</span>
                        @else<span class="badge bg-danger">Rusak Berat</span>@endif
                    </p></div>
                    <div class="col-md-4"><label class="form-label text-muted">Jumlah</label><p>{{ $inventaris->jumlah }}</p></div>
                    <div class="col-md-4"><label class="form-label text-muted">Tanggal Perolehan</label><p>{{ $inventaris->tanggal_perolehan ? \Carbon\Carbon::parse($inventaris->tanggal_perolehan)->format('d/m/Y') : '-' }}</p></div>
                    <div class="col-md-4"><label class="form-label text-muted">Harga Perolehan</label><p>{{ $inventaris->harga_perolehan ? 'Rp '.number_format($inventaris->harga_perolehan,0,',','.') : '-' }}</p></div>
                    @if($inventaris->catatan)<div class="col-12"><label class="form-label text-muted">Catatan</label><p>{{ $inventaris->catatan }}</p></div>@endif
                </div>
                <hr class="my-3">
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.inventaris.edit', $inventaris) }}" class="btn btn-warning"><i class="bi bi-pencil me-1"></i> Edit</a>
                    <form action="{{ route('admin.inventaris.destroy', $inventaris) }}" method="POST">@csrf @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger" data-confirm="Hapus barang ini?"><i class="bi bi-trash me-1"></i> Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        @if($inventaris->foto)
        <div class="card mb-3">
            <img src="{{ asset('storage/'.$inventaris->foto) }}" class="card-img-top" style="border-radius:14px 14px 0 0;max-height:250px;object-fit:cover;">
        </div>
        @endif
        <div class="card">
            <div class="card-header bg-transparent"><h6 class="mb-0 fw-semibold"><i class="bi bi-exclamation-triangle me-1 text-warning"></i> Laporan Kerusakan</h6></div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <tbody>
                        @forelse($inventaris->damageReports ?? [] as $dr)
                            <tr><td><small class="text-muted">{{ $dr->created_at->format('d/m/Y') }}</small><br>{{ $dr->deskripsi_kerusakan }}<br><span class="badge bg-{{ $dr->status=='selesai'?'success':'warning' }} bg-opacity-75">{{ ucfirst($dr->status) }}</span></td></tr>
                        @empty
                            <tr><td class="text-center text-muted py-3"><small>Tidak ada laporan</small></td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
