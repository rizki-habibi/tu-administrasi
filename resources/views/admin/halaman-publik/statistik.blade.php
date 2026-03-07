@extends('peran.admin.app')
@section('judul', 'Statistik Pengunjung')

@section('konten')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color:var(--dark);">Statistik Pengunjung</h4>
        <p class="text-muted mb-0" style="font-size:.82rem;">Pantau jumlah pengunjung halaman publik SIMPEG-SMART</p>
    </div>
    <a href="{{ route('admin.halaman-publik.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    @php
        $statCards = [
            ['label' => 'Hari Ini', 'value' => $stats['hari_ini'], 'icon' => 'bi-person-check-fill', 'bg' => 'linear-gradient(135deg,#10b981,#06b6d4)'],
            ['label' => 'Bulan Ini', 'value' => $stats['bulan_ini'], 'icon' => 'bi-calendar-month', 'bg' => 'linear-gradient(135deg,#6366f1,#8b5cf6)'],
            ['label' => 'Total Unik', 'value' => $stats['total_unik'], 'icon' => 'bi-people-fill', 'bg' => 'linear-gradient(135deg,#f59e0b,#f97316)'],
            ['label' => 'Total Views', 'value' => $stats['total_kunjungan'], 'icon' => 'bi-eye-fill', 'bg' => 'linear-gradient(135deg,#ec4899,#f43f5e)'],
        ];
    @endphp
    @foreach($statCards as $card)
    <div class="col-lg-3 col-md-6">
        <div class="card" style="border:none;border-radius:var(--card-radius);box-shadow:0 1px 4px rgba(0,0,0,.06);">
            <div class="card-body d-flex align-items-center gap-3 py-3">
                <div style="width:48px;height:48px;border-radius:12px;background:{{ $card['bg'] }};display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.2rem;">
                    <i class="bi {{ $card['icon'] }}"></i>
                </div>
                <div>
                    <div class="fw-bold" style="font-size:1.4rem;color:var(--dark);">{{ number_format($card['value']) }}</div>
                    <small class="text-muted" style="font-size:.72rem;">{{ $card['label'] }}</small>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Chart --}}
<div class="card mb-4" style="border:none;border-radius:var(--card-radius);box-shadow:0 1px 4px rgba(0,0,0,.06);">
    <div class="card-header bg-transparent border-0 pt-3">
        <h6 class="fw-bold" style="font-size:.9rem;"><i class="bi bi-graph-up me-1"></i> Pengunjung 30 Hari Terakhir</h6>
    </div>
    <div class="card-body">
        <canvas id="visitorChart" height="100"></canvas>
    </div>
</div>

{{-- Recent Visitors --}}
<div class="card" style="border:none;border-radius:var(--card-radius);box-shadow:0 1px 4px rgba(0,0,0,.06);">
    <div class="card-header bg-transparent border-0 pt-3">
        <h6 class="fw-bold" style="font-size:.9rem;"><i class="bi bi-clock-history me-1"></i> Pengunjung Terbaru</h6>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" style="font-size:.78rem;">
            <thead style="background:#f8fafc;">
                <tr>
                    <th>IP</th>
                    <th>Halaman</th>
                    <th>Perangkat</th>
                    <th>Browser</th>
                    <th>Platform</th>
                    <th>Waktu</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pengunjungTerbaru as $p)
                <tr>
                    <td><code style="font-size:.72rem;">{{ $p->ip_address }}</code></td>
                    <td>{{ $p->halaman }}</td>
                    <td>
                        <span class="badge bg-{{ $p->perangkat === 'mobile' ? 'warning' : ($p->perangkat === 'tablet' ? 'info' : 'success') }}" style="font-size:.6rem;">
                            {{ ucfirst($p->perangkat ?? '-') }}
                        </span>
                    </td>
                    <td>{{ $p->browser ?? '-' }}</td>
                    <td>{{ $p->platform ?? '-' }}</td>
                    <td class="text-muted">{{ $p->created_at->diffForHumans() }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
<script>
const labels = @json($chartData->pluck('tanggal'));
const unikData = @json($chartData->pluck('unik'));
const totalData = @json($chartData->pluck('total'));

new Chart(document.getElementById('visitorChart'), {
    type: 'bar',
    data: {
        labels: labels.map(d => new Date(d).toLocaleDateString('id-ID', {day:'2-digit',month:'short'})),
        datasets: [
            { label: 'Pengunjung Unik', data: unikData, backgroundColor: 'rgba(99,102,241,.7)', borderRadius: 6 },
            { label: 'Total Views', data: totalData, backgroundColor: 'rgba(236,72,153,.4)', borderRadius: 6 },
        ]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'bottom', labels: { font: { size: 11, family: 'Poppins' } } } },
        scales: { y: { beginAtZero: true, ticks: { font: { size: 10 } } }, x: { ticks: { font: { size: 10 } } } }
    }
});
</script>
@endpush
