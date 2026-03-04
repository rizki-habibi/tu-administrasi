@extends('kepala-sekolah.tata-letak.app')
@section('judul', 'Keuangan')

@section('konten')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-1">Ringkasan Keuangan</h5>
        <p class="text-muted mb-0" style="font-size:.85rem;">Overview pemasukan & pengeluaran</p>
    </div>
</div>

{{-- Summary Cards --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card" style="background:linear-gradient(135deg,#10b981,#34d399);">
            <div class="d-flex justify-content-between align-items-start">
                <div><p>Total Pemasukan</p><h3>Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</h3></div>
                <div class="icon-box"><i class="bi bi-arrow-down-circle"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card" style="background:linear-gradient(135deg,#ef4444,#f87171);">
            <div class="d-flex justify-content-between align-items-start">
                <div><p>Total Pengeluaran</p><h3>Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</h3></div>
                <div class="icon-box"><i class="bi bi-arrow-up-circle"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card" style="background:linear-gradient(135deg,#d97706,#f59e0b);">
            <div class="d-flex justify-content-between align-items-start">
                <div><p>Selisih</p><h3>Rp {{ number_format($totalPemasukan - $totalPengeluaran, 0, ',', '.') }}</h3></div>
                <div class="icon-box"><i class="bi bi-wallet2"></i></div>
            </div>
        </div>
    </div>
</div>

{{-- Budget Table --}}
@if($budgets->count() > 0)
<div class="card mb-4">
    <div class="card-header bg-white border-0 py-3">
        <h6 class="fw-bold mb-0" style="font-size:.9rem;"><i class="bi bi-piggy-bank text-warning me-2"></i>Anggaran</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead><tr><th>Nama Anggaran</th><th>Sumber Dana</th><th>Total</th><th>Terpakai</th><th>Sisa</th><th>Progres</th></tr></thead>
                <tbody>
                @foreach($budgets as $budget)
                    <tr>
                        <td class="fw-semibold">{{ $budget->nama_anggaran }}</td>
                        <td style="font-size:.8rem;">{{ $budget->sumber_dana ?? '-' }}</td>
                        <td style="font-size:.8rem;">Rp {{ number_format($budget->total_anggaran, 0, ',', '.') }}</td>
                        <td style="font-size:.8rem;">Rp {{ number_format($budget->terpakai, 0, ',', '.') }}</td>
                        <td style="font-size:.8rem;">Rp {{ number_format($budget->sisa, 0, ',', '.') }}</td>
                        <td style="width:160px;">
                            <div class="progress" style="height:8px;border-radius:4px;">
                                @php $pct = $budget->persentase_terpakai; @endphp
                                <div class="progress-bar {{ $pct > 80 ? 'bg-danger' : ($pct > 50 ? 'bg-warning' : 'bg-success') }}" style="width:{{ min($pct, 100) }}%;border-radius:4px;"></div>
                            </div>
                            <small class="text-muted" style="font-size:.7rem;">{{ $pct }}%</small>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

{{-- Finance Records --}}
<div class="card">
    <div class="card-header bg-white border-0 py-3">
        <h6 class="fw-bold mb-0" style="font-size:.9rem;"><i class="bi bi-receipt text-warning me-2"></i>Riwayat Transaksi</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr><th>#</th><th>Kode</th><th>Jenis</th><th>Kategori</th><th>Uraian</th><th>Jumlah</th><th>Tanggal</th></tr>
                </thead>
                <tbody>
                @forelse($records as $i => $rec)
                    <tr>
                        <td>{{ $records instanceof \Illuminate\Pagination\LengthAwarePaginator ? $records->firstItem() + $i : $i + 1 }}</td>
                        <td class="fw-semibold" style="font-size:.8rem;">{{ $rec->kode_transaksi }}</td>
                        <td>
                            @if($rec->jenis === 'pemasukan')
                                <span class="badge bg-success bg-opacity-10 text-success">Pemasukan</span>
                            @else
                                <span class="badge bg-danger bg-opacity-10 text-danger">Pengeluaran</span>
                            @endif
                        </td>
                        <td style="font-size:.8rem;">{{ ucfirst($rec->kategori ?? '-') }}</td>
                        <td style="max-width:200px;font-size:.8rem;">{{ \Str::limit($rec->uraian, 40) }}</td>
                        <td class="fw-semibold" style="font-size:.8rem;">Rp {{ number_format($rec->jumlah, 0, ',', '.') }}</td>
                        <td style="font-size:.8rem;">{{ $rec->tanggal ? $rec->tanggal->format('d/m/Y') : '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center py-4 text-muted">Tidak ada data transaksi</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@if($records instanceof \Illuminate\Pagination\LengthAwarePaginator)
<div class="mt-3 d-flex justify-content-center">{{ $records->withQueryString()->links() }}</div>
@endif
@endsection
