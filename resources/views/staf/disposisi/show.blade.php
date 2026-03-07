@extends('peran.staf.app')
@section('judul', 'Detail Disposisi')

@section('konten')
<div class="d-flex align-items-center mb-4 gap-2">
    <a href="{{ route('staf.disposisi.index') }}" class="btn btn-light btn-sm"><i class="bi bi-arrow-left"></i></a>
    <div>
        <h5 class="fw-bold mb-0"><i class="bi bi-envelope-open text-primary me-2"></i>Detail Disposisi</h5>
        <p class="text-muted mb-0" style="font-size:.82rem;">{{ $disposisi->surat->perihal ?? $disposisi->surat->nomor_surat ?? 'Disposisi' }}</p>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3">Instruksi</h6>
                <div class="bg-light rounded p-3 mb-3" style="font-size:.9rem; white-space:pre-line;">{{ $disposisi->instruksi }}</div>

                @if($disposisi->surat)
                <h6 class="fw-bold mb-2 mt-4">Surat Terkait</h6>
                <div class="border rounded p-3" style="font-size:.85rem;">
                    <p class="mb-1"><strong>No. Surat:</strong> {{ $disposisi->surat->nomor_surat }}</p>
                    <p class="mb-1"><strong>Perihal:</strong> {{ $disposisi->surat->perihal }}</p>
                    <p class="mb-0"><strong>Pengirim:</strong> {{ $disposisi->surat->pengirim ?? '-' }}</p>
                </div>
                @endif
            </div>
        </div>

        {{-- Action Form --}}
        @if(in_array($disposisi->status, ['belum_dibaca', 'dibaca']))
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3"><i class="bi bi-play-circle me-2 text-primary"></i>Tindak Lanjut</h6>
                <form method="POST" action="{{ route('staf.disposisi.proses', $disposisi) }}">
                    @csrf
                    <textarea name="catatan_tindakan" class="form-control mb-3" rows="3" placeholder="Catatan tindak lanjut (opsional)...">{{ old('catatan_tindakan', $disposisi->catatan_tindakan) }}</textarea>
                    <button type="submit" class="btn btn-warning"><i class="bi bi-arrow-right-circle me-1"></i>Mulai Proses</button>
                </form>
            </div>
        </div>
        @endif

        @if($disposisi->status == 'diproses')
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3"><i class="bi bi-check-circle me-2 text-success"></i>Selesaikan Disposisi</h6>
                <form method="POST" action="{{ route('staf.disposisi.selesai', $disposisi) }}">
                    @csrf
                    <textarea name="catatan_tindakan" class="form-control mb-3" rows="3" placeholder="Catatan penyelesaian..." required>{{ old('catatan_tindakan', $disposisi->catatan_tindakan) }}</textarea>
                    <button type="submit" class="btn btn-success"><i class="bi bi-check-lg me-1"></i>Tandai Selesai</button>
                </form>
            </div>
        </div>
        @endif

        @if($disposisi->status == 'selesai' && $disposisi->catatan_tindakan)
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-2"><i class="bi bi-check-circle text-success me-2"></i>Catatan Penyelesaian</h6>
                <p style="font-size:.9rem; white-space:pre-line;">{{ $disposisi->catatan_tindakan }}</p>
            </div>
        </div>
        @endif
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3">Info Disposisi</h6>
                <ul class="list-unstyled mb-0" style="font-size:.85rem;">
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Status</span>
                        @php $stBadge = ['belum_dibaca' => 'danger', 'dibaca' => 'info', 'diproses' => 'warning', 'selesai' => 'success']; @endphp
                        <span class="badge bg-{{ $stBadge[$disposisi->status] ?? 'secondary' }}">{{ str_replace('_', ' ', ucfirst($disposisi->status)) }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Prioritas</span>
                        @php $priBadge = ['biasa' => 'info', 'penting' => 'warning', 'segera' => 'danger']; @endphp
                        <span class="badge bg-{{ $priBadge[$disposisi->prioritas] ?? 'secondary' }}">{{ ucfirst($disposisi->prioritas) }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Dari</span><strong>{{ $disposisi->dariPengguna->nama ?? '-' }}</strong>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Tenggat</span>
                        <strong class="{{ $disposisi->tenggat && $disposisi->tenggat->isPast() && $disposisi->status != 'selesai' ? 'text-danger' : '' }}">
                            {{ $disposisi->tenggat ? $disposisi->tenggat->format('d/m/Y') : '-' }}
                        </strong>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Dibuat</span><strong>{{ $disposisi->created_at->diffForHumans() }}</strong>
                    </li>
                    @if($disposisi->selesai_pada)
                    <li class="d-flex justify-content-between py-2">
                        <span class="text-muted">Selesai</span><strong class="text-success">{{ $disposisi->selesai_pada->diffForHumans() }}</strong>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
