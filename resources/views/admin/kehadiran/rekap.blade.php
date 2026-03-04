@extends('admin.tata-letak.app')
@section('judul', 'Rekap Kehadiran')

@section('konten')
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Tanggal Mulai</label>
                <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Tanggal Akhir</label>
                <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
            </div>
            <div class="col-md-4">
                <button class="btn btn-primary w-100"><i class="bi bi-search"></i> Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h6 class="mb-0">Rekap Periode: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</h6>
        <div class="dropdown">
            <button class="btn btn-outline-success btn-sm dropdown-toggle" data-bs-toggle="dropdown"><i class="bi bi-download me-1"></i>Export</button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item export-btn" href="{{ route('admin.kehadiran.ekspor', ['format'=>'csv','tanggal_mulai'=>$startDate,'tanggal_selesai'=>$endDate]) }}"><i class="bi bi-filetype-csv me-2"></i>CSV / Excel</a></li>
                <li><a class="dropdown-item" href="{{ route('admin.kehadiran.ekspor', ['format'=>'pdf','tanggal_mulai'=>$startDate,'tanggal_selesai'=>$endDate]) }}" target="_blank"><i class="bi bi-printer me-2"></i>Print / PDF</a></li>
            </ul>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Nama</th>
                    <th class="text-center">Hadir</th>
                    <th class="text-center">Terlambat</th>
                    <th class="text-center">Izin</th>
                    <th class="text-center">Sakit</th>
                    <th class="text-center">Alpha</th>
                    <th class="text-center">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($attendanceData as $i => $data)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $data['staff']->nama }}</td>
                    <td class="text-center"><span class="badge bg-success">{{ $data['hadir'] }}</span></td>
                    <td class="text-center"><span class="badge bg-warning">{{ $data['terlambat'] }}</span></td>
                    <td class="text-center"><span class="badge bg-info">{{ $data['izin'] }}</span></td>
                    <td class="text-center"><span class="badge bg-primary">{{ $data['sakit'] }}</span></td>
                    <td class="text-center"><span class="badge bg-danger">{{ $data['alpha'] }}</span></td>
                    <td class="text-center"><strong>{{ $data['total'] }}</strong></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.export-btn').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        const url = this.href;
        Swal.fire({
            title: 'Mengekspor Data...', html: '<div class="mb-2">Sedang memproses rekap kehadiran</div><div class="progress" style="height:6px;border-radius:4px;"><div class="progress-bar bg-primary progress-bar-striped progress-bar-animated" style="width:0%"></div></div>',
            allowOutsideClick: false, showConfirmButton: false, didOpen: () => {
                const bar = Swal.getHtmlContainer().querySelector('.progress-bar');
                let w = 0;
                const interval = setInterval(() => { w = Math.min(w + Math.random() * 15, 90); bar.style.width = w + '%'; }, 200);
                fetch(url).then(r => r.blob()).then(blob => {
                    clearInterval(interval); bar.style.width = '100%';
                    const a = document.createElement('a'); a.href = URL.createObjectURL(blob);
                    a.download = 'kehadiran.csv'; document.body.appendChild(a); a.click(); a.remove();
                    Swal.fire({ icon: 'success', title: 'Export Berhasil!', text: 'File telah diunduh', timer: 2000, showConfirmButton: false });
                }).catch(() => { clearInterval(interval); Swal.fire({ icon: 'error', title: 'Gagal Export', text: 'Terjadi kesalahan' }); });
            }
        });
    });
});
</script>
@endpush
