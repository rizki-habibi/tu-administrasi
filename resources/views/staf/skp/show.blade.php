@extends('staf.tata-letak.app')
@section('judul', 'Detail SKP')

@section('konten')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-1">Detail SKP</h5>
        <p class="text-muted mb-0" style="font-size:.85rem;">Periode: {{ $skp->periode === 'januari_juni' ? 'Januari - Juni' : 'Juli - Desember' }} {{ $skp->tahun }}</p>
    </div>
    <div class="d-flex gap-2">
        @if(in_array($skp->status, ['draft','revisi']))
            <a href="{{ route('staf.skp.edit', $skp) }}" class="btn btn-warning btn-sm"><i class="bi bi-pencil me-1"></i> Ubah</a>
        @endif
        <a href="{{ route('staf.skp.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-8">
        <div class="card mb-3">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0" style="font-size:.9rem;"><i class="bi bi-person-lines-fill text-primary me-2"></i>Sasaran Kinerja</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label text-muted" style="font-size:.78rem;">Sasaran Kinerja</label>
                    <p>{{ $skp->sasaran_kinerja }}</p>
                </div>
                <div>
                    <label class="form-label text-muted" style="font-size:.78rem;">Indikator Kinerja</label>
                    <p>{{ $skp->indikator_kinerja }}</p>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0" style="font-size:.9rem;"><i class="bi bi-graph-up-arrow text-success me-2"></i>Target vs Realisasi</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead class="table-light">
                            <tr><th>Aspek</th><th class="text-center">Target</th><th class="text-center">Realisasi</th><th class="text-center">Capaian</th></tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Kuantitas</td>
                                <td class="text-center">{{ $skp->target_kuantitas ?? '-' }}</td>
                                <td class="text-center">{{ $skp->realisasi_kuantitas ?? '-' }}</td>
                                <td class="text-center">
                                    @if($skp->target_kuantitas && $skp->realisasi_kuantitas)
                                        {{ number_format(($skp->realisasi_kuantitas / $skp->target_kuantitas) * 100, 1) }}%
                                    @else - @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Kualitas (%)</td>
                                <td class="text-center">{{ $skp->target_kualitas ?? '-' }}</td>
                                <td class="text-center">{{ $skp->realisasi_kualitas ?? '-' }}</td>
                                <td class="text-center">
                                    @if($skp->target_kualitas && $skp->realisasi_kualitas)
                                        {{ number_format(($skp->realisasi_kualitas / $skp->target_kualitas) * 100, 1) }}%
                                    @else - @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Waktu (hari)</td>
                                <td class="text-center">{{ $skp->target_waktu ?? '-' }}</td>
                                <td class="text-center">{{ $skp->realisasi_waktu ?? '-' }}</td>
                                <td class="text-center">
                                    @if($skp->target_waktu && $skp->realisasi_waktu)
                                        {{ number_format(($skp->target_waktu / $skp->realisasi_waktu) * 100, 1) }}%
                                    @else - @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0" style="font-size:.9rem;"><i class="bi bi-info-circle text-info me-2"></i>Status</h6>
            </div>
            <div class="card-body text-center">
                <div class="mb-3">
                    <span class="badge {{ $skp->status_badge }} px-3 py-2" style="font-size:.9rem;">{{ ucfirst($skp->status) }}</span>
                </div>
                @if($skp->nilai_capaian)
                <div class="mb-3">
                    <label class="text-muted d-block" style="font-size:.78rem;">Nilai Capaian</label>
                    <h3 class="fw-bold" style="color:{{ $skp->nilai_capaian >= 80 ? '#10b981' : ($skp->nilai_capaian >= 60 ? '#f59e0b' : '#ef4444') }}">{{ number_format($skp->nilai_capaian, 1) }}</h3>
                </div>
                @endif
                @if($skp->predikat)
                <div class="mb-3">
                    <label class="text-muted d-block" style="font-size:.78rem;">Predikat</label>
                    <span class="badge {{ $skp->predikat_badge }} px-3 py-2" style="font-size:.85rem;">{{ $skp->predikat_label }}</span>
                </div>
                @endif
                @if($skp->approvedBy)
                <div class="mt-3">
                    <label class="text-muted d-block" style="font-size:.78rem;">Dinilai oleh</label>
                    <span style="font-size:.85rem;">{{ $skp->approvedBy->nama }}</span>
                    @if($skp->disetujui_pada)
                    <br><small class="text-muted">{{ $skp->disetujui_pada->translatedFormat('d F Y') }}</small>
                    @endif
                </div>
                @endif

                @if(in_array($skp->status, ['draft','revisi']))
                <hr>
                <form action="{{ route('staf.skp.update', $skp) }}" method="POST">
                    @csrf @method('PUT')
                    <input type="hidden" name="periode" value="{{ $skp->periode }}">
                    <input type="hidden" name="tahun" value="{{ $skp->tahun }}">
                    <input type="hidden" name="sasaran_kinerja" value="{{ $skp->sasaran_kinerja }}">
                    <input type="hidden" name="indikator_kinerja" value="{{ $skp->indikator_kinerja }}">
                    <input type="hidden" name="submit_action" value="ajukan">
                    <button type="submit" class="btn btn-primary w-100" data-confirm="Ajukan SKP ini untuk penilaian Kepala Sekolah?">
                        <i class="bi bi-send me-1"></i> Ajukan untuk Penilaian
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
