@extends('peran.staf.app')
@section('judul', 'Catatan Harian')

@section('konten')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
        <h5 class="fw-bold mb-1"><i class="bi bi-journal-text text-primary me-2"></i>Catatan Harian Kerja</h5>
        <p class="text-muted mb-0" style="font-size:.82rem;">Jurnal harian kegiatan kerja Anda</p>
    </div>
    <a href="{{ route('staf.catatan-harian.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>Tulis Hari Ini</a>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #10b981, #34d399);">
            <div><p>Bulan Ini</p><h3>{{ $bulanIni }}</h3><p>catatan</p></div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #6366f1, #818cf8);">
            <div><p>Hari Kerja</p><h3>{{ $hariKerja }}</h3><p>bulan ini</p></div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm mb-3">
    <div class="card-header bg-white border-0 py-3">
        <form method="GET" class="d-flex gap-2">
            <select name="bulan" class="form-select form-select-sm" style="width:140px;">
                @for($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" {{ request('bulan', now()->month) == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::create(null, $m)->translatedFormat('F') }}</option>
                @endfor
            </select>
            <select name="tahun" class="form-select form-select-sm" style="width:100px;">
                @for($y = now()->year; $y >= now()->year - 2; $y--)
                    <option value="{{ $y }}" {{ request('tahun', now()->year) == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
            <button class="btn btn-primary btn-sm"><i class="bi bi-funnel"></i></button>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th style="font-size:.8rem;">Tanggal</th>
                    <th style="font-size:.8rem;">Kegiatan</th>
                    <th style="font-size:.8rem;">Status</th>
                    <th style="font-size:.8rem;" width="120">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($catatan as $c)
                <tr>
                    <td style="font-size:.85rem;">
                        <div class="fw-semibold">{{ $c->tanggal->translatedFormat('d F Y') }}</div>
                        <small class="text-muted">{{ $c->tanggal->translatedFormat('l') }}</small>
                    </td>
                    <td style="font-size:.85rem;">{{ Str::limit($c->kegiatan, 80) }}</td>
                    <td>
                        @php $statusBadge = ['draft' => 'secondary', 'selesai' => 'success']; @endphp
                        <span class="badge bg-{{ $statusBadge[$c->status] ?? 'secondary' }}">{{ ucfirst($c->status) }}</span>
                    </td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('staf.catatan-harian.show', $c) }}" class="btn btn-outline-primary"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('staf.catatan-harian.edit', $c) }}" class="btn btn-outline-warning"><i class="bi bi-pencil"></i></a>
                            <form method="POST" action="{{ route('staf.catatan-harian.destroy', $c) }}" class="d-inline" onsubmit="return confirm('Hapus catatan ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-outline-danger btn-sm"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center text-muted py-4"><i class="bi bi-journal-x" style="font-size:2rem;"></i><br>Belum ada catatan</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($catatan->hasPages())
    <div class="card-footer bg-white border-0 d-flex justify-content-center py-3">{{ $catatan->withQueryString()->links() }}</div>
    @endif
</div>
@endsection
