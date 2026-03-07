@extends('peran.staf.app')
@section('judul', 'Laporan Kerusakan')

@section('konten')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0" style="color:#1e293b;"><i class="bi bi-tools me-2"></i>Laporan Kerusakan</h4>
    <a href="{{ route('staf.kerusakan.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i> Buat Laporan</a>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle me-1"></i>{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

<div class="card mb-3">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari nama barang..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select form-select-sm">
                    <option value="">Semua Status</option>
                    <option value="dilaporkan" {{ request('status')=='dilaporkan'?'selected':'' }}>Dilaporkan</option>
                    <option value="diproses" {{ request('status')=='diproses'?'selected':'' }}>Diproses</option>
                    <option value="selesai" {{ request('status')=='selesai'?'selected':'' }}>Selesai</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-primary btn-sm w-100"><i class="bi bi-search me-1"></i> Cari</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Barang</th>
                    <th>Tingkat</th>
                    <th>Status</th>
                    <th>Pelapor</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reports as $i => $report)
                <tr>
                    <td>{{ $reports->firstItem() + $i }}</td>
                    <td>{{ $report->tanggal_laporan->format('d/m/Y') }}</td>
                    <td>{{ $report->inventaris->nama_barang ?? '-' }}</td>
                    <td>
                        <span class="badge bg-{{ $report->tingkat_kerusakan == 'berat' ? 'danger' : ($report->tingkat_kerusakan == 'sedang' ? 'warning' : 'info') }}">
                            {{ ucfirst($report->tingkat_kerusakan) }}
                        </span>
                    </td>
                    <td>
                        <span class="badge bg-{{ $report->status == 'selesai' ? 'success' : ($report->status == 'diproses' ? 'primary' : 'secondary') }}">
                            {{ ucfirst($report->status) }}
                        </span>
                    </td>
                    <td>{{ $report->reporter->nama ?? '-' }}</td>
                    <td class="text-center">
                        <a href="{{ route('staf.kerusakan.show', $report) }}" class="btn btn-outline-primary btn-sm"><i class="bi bi-eye"></i></a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-4">Belum ada laporan kerusakan</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($reports->hasPages())
    <div class="card-footer bg-white">{{ $reports->withQueryString()->links() }}</div>
    @endif
</div>
@endsection
