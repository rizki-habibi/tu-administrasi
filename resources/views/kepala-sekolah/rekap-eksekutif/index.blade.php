@extends('peran.kepala-sekolah.app')
@section('judul', 'Rekap Eksekutif')

@section('konten')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
        <h5 class="fw-bold mb-1"><i class="bi bi-bar-chart-line-fill text-primary me-2"></i>Rekap Eksekutif</h5>
        <p class="text-muted mb-0" style="font-size:.82rem;">Ringkasan data sekolah bulan {{ \Carbon\Carbon::create($tahun, $bulan)->translatedFormat('F Y') }}</p>
    </div>
    <form method="GET" class="d-flex gap-2">
        <select name="bulan" class="form-select form-select-sm" style="width:140px;">
            @for($m = 1; $m <= 12; $m++)
                <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::create(null, $m)->translatedFormat('F') }}</option>
            @endfor
        </select>
        <select name="tahun" class="form-select form-select-sm" style="width:100px;">
            @for($y = now()->year; $y >= now()->year - 2; $y--)
                <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
            @endfor
        </select>
        <button class="btn btn-primary btn-sm"><i class="bi bi-arrow-repeat"></i></button>
    </form>
</div>

{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #6366f1, #818cf8);">
            <div><p>Pegawai Aktif</p><h3>{{ $pegawaiAktif }}/{{ $totalPegawai }}</h3><p>total terdaftar</p></div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #10b981, #34d399);">
            <div><p>Kehadiran</p><h3>{{ $persentaseKehadiran }}%</h3><p>{{ $rekapKehadiran['hadir'] + $rekapKehadiran['terlambat'] }} dari {{ $rekapKehadiran['total'] }}</p></div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #f59e0b, #fbbf24);">
            <div><p>Izin Pending</p><h3>{{ $rekapIzin['pending'] }}</h3><p>dari {{ $rekapIzin['total'] }} total</p></div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #ef4444, #f87171);">
            <div><p>SKP Pending</p><h3>{{ $skpStats['diajukan'] }}</h3><p>menunggu penilaian</p></div>
        </div>
    </div>
</div>

{{-- Charts Row --}}
<div class="row g-3 mb-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3"><h6 class="fw-bold mb-0"><i class="bi bi-graph-up me-2"></i>Tren Kehadiran 6 Bulan</h6></div>
            <div class="card-body"><canvas id="trenChart" height="200"></canvas></div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3"><h6 class="fw-bold mb-0"><i class="bi bi-pie-chart me-2"></i>Distribusi Kehadiran</h6></div>
            <div class="card-body"><canvas id="pieChart" height="200"></canvas></div>
        </div>
    </div>
</div>

{{-- Detail Cards --}}
<div class="row g-3 mb-4">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h6 class="fw-bold mb-3"><i class="bi bi-calendar-check me-2 text-success"></i>Kehadiran</h6>
                <ul class="list-unstyled mb-0" style="font-size:.85rem;">
                    <li class="d-flex justify-content-between py-1 border-bottom"><span>Hadir</span><strong class="text-success">{{ $rekapKehadiran['hadir'] }}</strong></li>
                    <li class="d-flex justify-content-between py-1 border-bottom"><span>Terlambat</span><strong class="text-warning">{{ $rekapKehadiran['terlambat'] }}</strong></li>
                    <li class="d-flex justify-content-between py-1 border-bottom"><span>Izin</span><strong class="text-info">{{ $rekapKehadiran['izin'] }}</strong></li>
                    <li class="d-flex justify-content-between py-1 border-bottom"><span>Sakit</span><strong class="text-primary">{{ $rekapKehadiran['sakit'] }}</strong></li>
                    <li class="d-flex justify-content-between py-1"><span>Alpha</span><strong class="text-danger">{{ $rekapKehadiran['alpha'] }}</strong></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h6 class="fw-bold mb-3"><i class="bi bi-cash-stack me-2 text-primary"></i>Keuangan</h6>
                <ul class="list-unstyled mb-0" style="font-size:.85rem;">
                    <li class="d-flex justify-content-between py-1 border-bottom"><span>Pemasukan</span><strong class="text-success">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</strong></li>
                    <li class="d-flex justify-content-between py-1 border-bottom"><span>Pengeluaran</span><strong class="text-danger">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</strong></li>
                    <li class="d-flex justify-content-between py-1"><span>Saldo</span><strong>Rp {{ number_format($totalPemasukan - $totalPengeluaran, 0, ',', '.') }}</strong></li>
                </ul>
                @if($anggaranTahun)
                <div class="mt-3">
                    <small class="text-muted">Anggaran {{ $tahun }}</small>
                    <div class="progress" style="height:8px;">
                        @php $pct = $anggaranTahun->total_anggaran > 0 ? round($anggaranTahun->terpakai / $anggaranTahun->total_anggaran * 100) : 0; @endphp
                        <div class="progress-bar bg-{{ $pct > 80 ? 'danger' : ($pct > 50 ? 'warning' : 'success') }}" style="width:{{ $pct }}%"></div>
                    </div>
                    <small class="text-muted">{{ $pct }}% terpakai</small>
                </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h6 class="fw-bold mb-3"><i class="bi bi-clipboard-data me-2 text-warning"></i>Lainnya</h6>
                <ul class="list-unstyled mb-0" style="font-size:.85rem;">
                    <li class="d-flex justify-content-between py-1 border-bottom"><span>Laporan Bulan Ini</span><strong>{{ $laporanBulan }}</strong></li>
                    <li class="d-flex justify-content-between py-1 border-bottom"><span>Resolusi Berlaku</span><strong>{{ $resolusiBerlaku }}</strong></li>
                    <li class="d-flex justify-content-between py-1 border-bottom"><span>SKP Dinilai</span><strong>{{ $skpStats['dinilai'] }}</strong></li>
                    <li class="d-flex justify-content-between py-1"><span>Izin Disetujui</span><strong>{{ $rekapIzin['disetujui'] }}</strong></li>
                </ul>
            </div>
        </div>
    </div>
</div>

{{-- AI Analisis Button --}}
<div class="card border-0 shadow-sm">
    <div class="card-body text-center py-4">
        <i class="bi bi-stars text-primary" style="font-size:2rem;"></i>
        <h6 class="fw-bold mt-2">Analisis AI Eksekutif</h6>
        <p class="text-muted mb-3" style="font-size:.85rem;">Dapatkan analisis dan rekomendasi dari AI berdasarkan data bulan ini</p>
        <button class="btn btn-primary" onclick="aiAnalisis()" id="btn-ai-analisis"><i class="bi bi-stars me-1"></i>Generate Analisis AI</button>
        <div id="ai-analisis-result" class="text-start mt-3 d-none">
            <div class="bg-light rounded p-3" id="ai-analisis-text" style="font-size:.85rem;"></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
new Chart(document.getElementById('trenChart'), {
    type: 'line',
    data: {
        labels: @json($trenLabels),
        datasets: [
            { label: 'Hadir', data: @json($trenHadir), borderColor: '#10b981', backgroundColor: 'rgba(16,185,129,.1)', fill: true, tension: 0.3 },
            { label: 'Absen', data: @json($trenAbsen), borderColor: '#ef4444', backgroundColor: 'rgba(239,68,68,.1)', fill: true, tension: 0.3 }
        ]
    },
    options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
});

new Chart(document.getElementById('pieChart'), {
    type: 'doughnut',
    data: {
        labels: ['Hadir','Terlambat','Izin','Sakit','Alpha'],
        datasets: [{ data: [{{ $rekapKehadiran['hadir'] }},{{ $rekapKehadiran['terlambat'] }},{{ $rekapKehadiran['izin'] }},{{ $rekapKehadiran['sakit'] }},{{ $rekapKehadiran['alpha'] }}], backgroundColor: ['#10b981','#f59e0b','#3b82f6','#8b5cf6','#ef4444'] }]
    },
    options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
});

function aiAnalisis() {
    const btn = document.getElementById('btn-ai-analisis');
    btn.disabled = true;
    btn.innerHTML = '<div class="spinner-border spinner-border-sm me-1"></div>Menganalisis...';
    fetch('{{ route("kepala-sekolah.rekap-eksekutif.ai-analisis") }}?bulan={{ $bulan }}&tahun={{ $tahun }}', {
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
    })
    .then(r => r.json())
    .then(data => {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-stars me-1"></i>Generate Analisis AI';
        if (data.success) {
            document.getElementById('ai-analisis-text').innerHTML = data.analisis;
            document.getElementById('ai-analisis-result').classList.remove('d-none');
        }
    })
    .catch(() => { btn.disabled = false; btn.innerHTML = '<i class="bi bi-stars me-1"></i>Generate Analisis AI'; });
}
</script>
@endpush
