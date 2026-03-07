@extends('peran.staf.app')
@section('judul', 'Detail Laporan Kerusakan')

@section('konten')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('staf.kerusakan.index') }}" class="btn btn-outline-secondary btn-sm me-3"><i class="bi bi-arrow-left"></i></a>
    <h4 class="fw-bold mb-0" style="color:#1e293b;">Detail Laporan Kerusakan</h4>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle me-1"></i>{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

<div class="row g-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body p-4">
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="text-muted small">Barang</label>
                        <p class="fw-semibold mb-0">{{ $kerusakan->inventaris->nama_barang ?? '-' }}</p>
                        <small class="text-muted">{{ $kerusakan->inventaris->kode_barang ?? '' }}</small>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted small">Tanggal Laporan</label>
                        <p class="fw-semibold mb-0">{{ $kerusakan->tanggal_laporan->format('d M Y') }}</p>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted small">Pelapor</label>
                        <p class="fw-semibold mb-0">{{ $kerusakan->reporter->nama ?? '-' }}</p>
                    </div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="text-muted small">Tingkat Kerusakan</label>
                        <p>
                            <span class="badge bg-{{ $kerusakan->tingkat_kerusakan == 'berat' ? 'danger' : ($kerusakan->tingkat_kerusakan == 'sedang' ? 'warning' : 'info') }}">
                                {{ ucfirst($kerusakan->tingkat_kerusakan) }}
                            </span>
                        </p>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small">Status</label>
                        <p>
                            <span class="badge bg-{{ $kerusakan->status == 'selesai' ? 'success' : ($kerusakan->status == 'diproses' ? 'primary' : 'secondary') }}">
                                {{ ucfirst($kerusakan->status) }}
                            </span>
                        </p>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="text-muted small">Deskripsi Kerusakan</label>
                    <p>{{ $kerusakan->deskripsi_kerusakan }}</p>
                </div>
                @if($kerusakan->tindakan)
                <div class="mb-3">
                    <label class="text-muted small">Tindakan</label>
                    <p>{{ $kerusakan->tindakan }}</p>
                </div>
                @endif
                @if($kerusakan->foto)
                <div>
                    <label class="text-muted small">Foto Kerusakan</label>
                    <div class="mt-1">
                        <img src="{{ Storage::url($kerusakan->foto) }}" alt="Foto kerusakan" class="rounded" style="max-width:400px; max-height:300px;">
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-white"><h6 class="mb-0">Update Status</h6></div>
            <div class="card-body">
                <form action="{{ route('staf.kerusakan.update-status', $kerusakan) }}" method="POST">
                    @csrf @method('PATCH')
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="dilaporkan" {{ $kerusakan->status=='dilaporkan'?'selected':'' }}>Dilaporkan</option>
                            <option value="diproses" {{ $kerusakan->status=='diproses'?'selected':'' }}>Diproses</option>
                            <option value="selesai" {{ $kerusakan->status=='selesai'?'selected':'' }}>Selesai</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tindakan</label>
                        <textarea name="tindakan" class="form-control" rows="3">{{ old('tindakan', $kerusakan->tindakan) }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm w-100"><i class="bi bi-save me-1"></i> Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
