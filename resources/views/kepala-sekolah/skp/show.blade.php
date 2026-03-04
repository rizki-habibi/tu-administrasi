@extends('kepala-sekolah.tata-letak.app')
@section('judul', 'Detail & Penilaian SKP')

@section('konten')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-1">Detail SKP - {{ $skp->user->nama }}</h5>
        <p class="text-muted mb-0" style="font-size:.85rem;">{{ $skp->user->role_label ?? '' }} &middot; Periode {{ $skp->periode === 'januari_juni' ? 'Jan-Jun' : 'Jul-Des' }} {{ $skp->tahun }}</p>
    </div>
    <a href="{{ route('kepala-sekolah.skp.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
</div>

<div class="row g-3">
    <div class="col-md-8">
        <div class="card mb-3">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0" style="font-size:.9rem;"><i class="bi bi-person-lines-fill text-warning me-2"></i>Sasaran Kinerja</h6>
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
                        <thead class="table-light"><tr><th>Aspek</th><th class="text-center">Target</th><th class="text-center">Realisasi</th><th class="text-center">Capaian</th></tr></thead>
                        <tbody>
                            <tr>
                                <td>Kuantitas</td>
                                <td class="text-center">{{ $skp->target_kuantitas ?? '-' }}</td>
                                <td class="text-center">{{ $skp->realisasi_kuantitas ?? '-' }}</td>
                                <td class="text-center">@if($skp->target_kuantitas && $skp->realisasi_kuantitas) {{ number_format(($skp->realisasi_kuantitas / $skp->target_kuantitas) * 100, 1) }}% @else - @endif</td>
                            </tr>
                            <tr>
                                <td>Kualitas (%)</td>
                                <td class="text-center">{{ $skp->target_kualitas ?? '-' }}</td>
                                <td class="text-center">{{ $skp->realisasi_kualitas ?? '-' }}</td>
                                <td class="text-center">@if($skp->target_kualitas && $skp->realisasi_kualitas) {{ number_format(($skp->realisasi_kualitas / $skp->target_kualitas) * 100, 1) }}% @else - @endif</td>
                            </tr>
                            <tr>
                                <td>Waktu (hari)</td>
                                <td class="text-center">{{ $skp->target_waktu ?? '-' }}</td>
                                <td class="text-center">{{ $skp->realisasi_waktu ?? '-' }}</td>
                                <td class="text-center">@if($skp->target_waktu && $skp->realisasi_waktu) {{ number_format(($skp->target_waktu / $skp->realisasi_waktu) * 100, 1) }}% @else - @endif</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                @if($skp->nilai_capaian)
                <div class="text-center mt-3 py-2 rounded-3" style="background:#fef3c7;">
                    <span class="text-muted" style="font-size:.8rem;">Nilai Capaian Rata-rata:</span>
                    <span class="fw-bold ms-1" style="font-size:1.2rem;color:#92400e;">{{ number_format($skp->nilai_capaian, 1) }}</span>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0" style="font-size:.9rem;"><i class="bi bi-clipboard-check text-warning me-2"></i>Penilaian</h6>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <span class="badge {{ $skp->status_badge }} px-3 py-2" style="font-size:.9rem;">{{ ucfirst($skp->status) }}</span>
                </div>

                @if($skp->predikat)
                    <div class="text-center mb-3">
                        <label class="text-muted d-block" style="font-size:.78rem;">Predikat</label>
                        <span class="badge {{ $skp->predikat_badge }} px-3 py-2" style="font-size:.9rem;">{{ $skp->predikat_label }}</span>
                    </div>
                @endif

                @if($skp->status === 'diajukan')
                <hr>
                <form action="{{ route('kepala-sekolah.skp.approve', $skp) }}" method="POST" class="mb-3">
                    @csrf @method('PATCH')
                    <label class="form-label fw-semibold">Berikan Penilaian</label>
                    <select name="predikat" class="form-select mb-2" required>
                        <option value="">Pilih Predikat</option>
                        <option value="sangat_baik">Sangat Baik (>= 91)</option>
                        <option value="baik">Baik (76-90)</option>
                        <option value="cukup">Cukup (61-75)</option>
                        <option value="kurang">Kurang (51-60)</option>
                        <option value="sangat_kurang">Sangat Kurang (<= 50)</option>
                    </select>
                    <button type="submit" class="btn btn-success w-100 mb-2" data-confirm="Setujui dan beri penilaian SKP ini?">
                        <i class="bi bi-check-circle me-1"></i> Setujui & Nilai
                    </button>
                </form>
                <form action="{{ route('kepala-sekolah.skp.reject', $skp) }}" method="POST">
                    @csrf @method('PATCH')
                    <textarea name="catatan" class="form-control mb-2" rows="2" placeholder="Catatan revisi (opsional)..."></textarea>
                    <button type="submit" class="btn btn-outline-danger w-100" data-confirm="Kembalikan SKP ini untuk direvisi?">
                        <i class="bi bi-arrow-return-left me-1"></i> Kembalikan untuk Revisi
                    </button>
                </form>
                @endif

                @if($skp->approvedBy)
                <hr>
                <div>
                    <label class="text-muted d-block" style="font-size:.78rem;">Dinilai oleh</label>
                    <span style="font-size:.85rem;">{{ $skp->approvedBy->nama }}</span>
                    @if($skp->disetujui_pada)<br><small class="text-muted">{{ $skp->disetujui_pada->translatedFormat('d F Y H:i') }}</small>@endif
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
