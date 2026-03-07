@extends('peran.staf.app')
@section('judul', 'Disposisi Masuk')

@section('konten')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
        <h5 class="fw-bold mb-1"><i class="bi bi-envelope-open-fill text-primary me-2"></i>Disposisi Masuk</h5>
        <p class="text-muted mb-0" style="font-size:.82rem;">Disposisi surat yang ditujukan kepada Anda</p>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #ef4444, #f87171);">
            <div><p>Belum Dibaca</p><h3>{{ $belumDibaca }}</h3><p>disposisi</p></div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #f59e0b, #fbbf24);">
            <div><p>Diproses</p><h3>{{ $diproses }}</h3><p>disposisi</p></div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #10b981, #34d399);">
            <div><p>Selesai</p><h3>{{ $selesai }}</h3><p>disposisi</p></div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th style="font-size:.8rem;">Dari</th>
                    <th style="font-size:.8rem;">Surat</th>
                    <th style="font-size:.8rem;">Prioritas</th>
                    <th style="font-size:.8rem;">Tenggat</th>
                    <th style="font-size:.8rem;">Status</th>
                    <th style="font-size:.8rem;" width="80">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($disposisi as $d)
                <tr class="{{ !$d->dibaca_pada ? 'table-warning' : '' }}">
                    <td style="font-size:.85rem;">
                        <div class="d-flex align-items-center gap-2">
                            <div class="d-flex align-items-center justify-content-center rounded-circle bg-primary text-white" style="width:32px;height:32px;font-size:.75rem;">
                                {{ strtoupper(substr($d->dariPengguna->nama ?? '-', 0, 2)) }}
                            </div>
                            <div>
                                <div class="fw-semibold">{{ $d->dariPengguna->nama ?? '-' }}</div>
                                <small class="text-muted">{{ $d->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    </td>
                    <td style="font-size:.85rem;">{{ Str::limit($d->surat->perihal ?? $d->surat->nomor_surat ?? '-', 40) }}</td>
                    <td>
                        @php $priBadge = ['biasa' => 'info', 'penting' => 'warning', 'segera' => 'danger']; @endphp
                        <span class="badge bg-{{ $priBadge[$d->prioritas] ?? 'secondary' }}">{{ ucfirst($d->prioritas) }}</span>
                    </td>
                    <td style="font-size:.85rem;">
                        @if($d->tenggat)
                            <span class="{{ $d->tenggat->isPast() && $d->status != 'selesai' ? 'text-danger fw-bold' : '' }}">{{ $d->tenggat->format('d/m/Y') }}</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        @php $stBadge = ['belum_dibaca' => 'danger', 'dibaca' => 'info', 'diproses' => 'warning', 'selesai' => 'success']; @endphp
                        <span class="badge bg-{{ $stBadge[$d->status] ?? 'secondary' }}">{{ str_replace('_', ' ', ucfirst($d->status)) }}</span>
                    </td>
                    <td>
                        <a href="{{ route('staf.disposisi.show', $d) }}" class="btn btn-outline-primary btn-sm"><i class="bi bi-eye"></i></a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-4"><i class="bi bi-inbox" style="font-size:2rem;"></i><br>Belum ada disposisi masuk</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($disposisi->hasPages())
    <div class="card-footer bg-white border-0 d-flex justify-content-center py-3">{{ $disposisi->links() }}</div>
    @endif
</div>
@endsection
