@extends('peran.admin.app')
@section('judul', 'Laporan Kepegawaian')

@section('konten')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
        <h5 class="fw-bold mb-1">Laporan Kepegawaian</h5>
        <p class="text-muted mb-0" style="font-size:.82rem;">Ringkasan statistik data kepegawaian</p>
    </div>
</div>

{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 d-flex align-items-center justify-content-center" style="width:48px;height:48px;background:linear-gradient(135deg,#6366f1,#8b5cf6);">
                    <i class="bi bi-people-fill text-white" style="font-size:1.2rem;"></i>
                </div>
                <div>
                    <div class="text-muted" style="font-size:.78rem;">Total Pegawai</div>
                    <h4 class="fw-bold mb-0">{{ $totalPegawai }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 d-flex align-items-center justify-content-center" style="width:48px;height:48px;background:linear-gradient(135deg,#10b981,#059669);">
                    <i class="bi bi-person-check-fill text-white" style="font-size:1.2rem;"></i>
                </div>
                <div>
                    <div class="text-muted" style="font-size:.78rem;">Pegawai Aktif</div>
                    <h4 class="fw-bold mb-0">{{ $pegawaiAktif }}</h4>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    {{-- By Jenis Pegawai --}}
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-bold" style="font-size:.85rem;">Berdasarkan Jenis Pegawai</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead style="background:#f8fafc;">
                            <tr>
                                <th style="font-size:.75rem;text-transform:uppercase;color:#64748b;padding:.5rem .75rem;">Jenis</th>
                                <th style="font-size:.75rem;text-transform:uppercase;color:#64748b;" class="text-end">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($byJenisPegawai as $item)
                            <tr>
                                <td style="font-size:.85rem;padding:.5rem .75rem;">{{ $item->jenis_pegawai ?? 'Belum diisi' }}</td>
                                <td style="font-size:.85rem;" class="text-end fw-medium">{{ $item->total }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="2" class="text-center text-muted py-3">Belum ada data</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- By Golongan --}}
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-bold" style="font-size:.85rem;">Berdasarkan Golongan</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead style="background:#f8fafc;">
                            <tr>
                                <th style="font-size:.75rem;text-transform:uppercase;color:#64748b;padding:.5rem .75rem;">Golongan</th>
                                <th style="font-size:.75rem;text-transform:uppercase;color:#64748b;" class="text-end">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($byGolongan as $item)
                            <tr>
                                <td style="font-size:.85rem;padding:.5rem .75rem;">
                                    <span class="badge bg-primary bg-opacity-10 text-primary">{{ $item->golongan ?? 'Belum diisi' }}</span>
                                </td>
                                <td style="font-size:.85rem;" class="text-end fw-medium">{{ $item->total }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="2" class="text-center text-muted py-3">Belum ada data</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- By Pendidikan --}}
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-bold" style="font-size:.85rem;">Berdasarkan Pendidikan</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead style="background:#f8fafc;">
                            <tr>
                                <th style="font-size:.75rem;text-transform:uppercase;color:#64748b;padding:.5rem .75rem;">Pendidikan</th>
                                <th style="font-size:.75rem;text-transform:uppercase;color:#64748b;" class="text-end">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($byPendidikan as $item)
                            <tr>
                                <td style="font-size:.85rem;padding:.5rem .75rem;">{{ $item->pendidikan_terakhir ?? 'Belum diisi' }}</td>
                                <td style="font-size:.85rem;" class="text-end fw-medium">{{ $item->total }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="2" class="text-center text-muted py-3">Belum ada data</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
