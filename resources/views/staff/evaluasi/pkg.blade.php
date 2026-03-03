@extends('layouts.staff')
@section('title', 'PKG/BKD Saya')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0" style="color:#1e293b;"><i class="bi bi-clipboard-check me-2"></i>Penilaian Kinerja (PKG/BKD)</h4>
</div>

<div class="alert alert-info">
    <i class="bi bi-info-circle me-1"></i> Halaman ini menampilkan penilaian kinerja guru Anda. Penilaian dilakukan oleh Admin/Kepala Sekolah.
</div>

{{-- Ringkasan --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0" style="background:linear-gradient(135deg,#6366f1 0%,#818cf8 100%);">
            <div class="card-body text-white text-center">
                <h2 class="fw-bold mb-0">{{ $evaluations->count() ?? 0 }}</h2>
                <small>Total Evaluasi</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0" style="background:linear-gradient(135deg,#10b981 0%,#34d399 100%);">
            <div class="card-body text-white text-center">
                <h2 class="fw-bold mb-0">{{ $evaluations->count() > 0 ? number_format($evaluations->avg('score'), 1) : '-' }}</h2>
                <small>Rata-rata Nilai</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0" style="background:linear-gradient(135deg,#f59e0b 0%,#fbbf24 100%);">
            <div class="card-body text-white text-center">
                <h2 class="fw-bold mb-0">{{ $evaluations->last()->grade ?? '-' }}</h2>
                <small>Predikat Terakhir</small>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Jenis</th>
                    <th>Periode</th>
                    <th>Nilai</th>
                    <th>Predikat</th>
                    <th>Catatan</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($evaluations ?? [] as $i => $e)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td><span class="badge bg-primary">{{ strtoupper($e->type ?? 'PKG') }}</span></td>
                    <td>{{ $e->period ?? '-' }}</td>
                    <td class="fw-bold">{{ $e->score ?? '-' }}</td>
                    <td>
                        @php
                        $gradeColor = match($e->grade ?? '') {
                            'Amat Baik' => 'success',
                            'Baik' => 'primary',
                            'Cukup' => 'warning',
                            'Kurang' => 'danger',
                            default => 'secondary'
                        };
                        @endphp
                        <span class="badge bg-{{ $gradeColor }}">{{ $e->grade ?? '-' }}</span>
                    </td>
                    <td><small class="text-muted">{{ Str::limit($e->notes, 40) ?? '-' }}</small></td>
                    <td>{{ $e->created_at->format('d/m/Y') }}</td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-4 text-muted"><i class="bi bi-clipboard-check" style="font-size:2rem;"></i><p class="mt-2 mb-0">Belum ada evaluasi kinerja</p></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
