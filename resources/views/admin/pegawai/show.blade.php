@extends('peran.admin.app')
@section('judul', 'Detail Staf: ' . $staff->nama)

@section('konten')
<div class="row g-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center p-4">
                @if($staff->foto)
                    <img src="{{ asset('storage/' . $staff->foto) }}" class="rounded-circle mb-3" width="120" height="120" style="object-fit:cover;">
                @else
                    <div class="bg-secondary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width:120px;height:120px;">
                        <i class="bi bi-person" style="font-size:3rem;"></i>
                    </div>
                @endif
                <h5>{{ $staff->nama }}</h5>
                <p class="text-muted">{{ $staff->jabatan ?? 'Staf' }}</p>
                <span class="badge bg-{{ $staff->aktif ? 'success' : 'danger' }}">{{ $staff->aktif ? 'Aktif' : 'Nonaktif' }}</span>
                <hr>
                <div class="text-start">
                    <p><i class="bi bi-envelope"></i> {{ $staff->email }}</p>
                    <p><i class="bi bi-phone"></i> {{ $staff->telepon ?? '-' }}</p>
                    <p><i class="bi bi-geo-alt"></i> {{ $staff->alamat ?? '-' }}</p>
                    <p><i class="bi bi-calendar"></i> Bergabung: {{ $staff->created_at->format('d M Y') }}</p>
                </div>
                <div class="d-flex gap-2 mt-3">
                    <a href="{{ route('admin.pegawai.edit', $staff) }}" class="btn btn-warning btn-sm flex-fill"><i class="bi bi-pencil"></i> Edit</a>
                    <form action="{{ route('admin.pegawai.toggle-status', $staff) }}" method="POST" class="flex-fill">
                        @csrf @method('PATCH')
                        <button class="btn btn-{{ $staff->aktif ? 'secondary' : 'success' }} btn-sm w-100">
                            {{ $staff->aktif ? 'Nonaktifkan' : 'Aktifkan' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white"><h6 class="mb-0"><i class="bi bi-pie-chart"></i> Statistik Kehadiran Bulan Ini</h6></div>
            <div class="card-body">
                <div class="row g-3">
                    @php $colors = ['hadir'=>'success','terlambat'=>'warning','izin'=>'info','sakit'=>'secondary','alpha'=>'danger']; @endphp
                    @foreach($attendanceStats as $status => $count)
                    <div class="col">
                        <div class="text-center p-3 rounded bg-{{ $colors[$status] }} bg-opacity-10">
                            <h4 class="text-{{ $colors[$status] }}">{{ $count }}</h4>
                            <small>{{ ucfirst($status) }}</small>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white"><h6 class="mb-0"><i class="bi bi-clock-history"></i> Riwayat Kehadiran Terbaru</h6></div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr><th>Tanggal</th><th>Masuk</th><th>Pulang</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        @forelse($staff->attendances as $att)
                        <tr>
                            <td>{{ $att->date->format('d/m/Y') }}</td>
                            <td>{{ $att->jam_masuk ?? '-' }}</td>
                            <td>{{ $att->jam_pulang ?? '-' }}</td>
                            <td><span class="badge bg-{{ $colors[$att->status] ?? 'secondary' }}">{{ ucfirst($att->status) }}</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted">Belum ada data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><h6 class="mb-0"><i class="bi bi-envelope-paper"></i> Pengajuan Izin Terbaru</h6></div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr><th>Tipe</th><th>Tanggal Mulai</th><th>Tanggal Selesai</th><th>Status</th><th>Aksi</th></tr>
                    </thead>
                    <tbody>
                        @php $leaveColors = ['pending'=>'warning','approved'=>'success','rejected'=>'danger']; @endphp
                        @forelse($staff->leaveRequests as $leave)
                        <tr>
                            <td>{{ ucfirst($leave->jenis) }}</td>
                            <td>{{ $leave->tanggal_mulai->format('d/m/Y') }}</td>
                            <td>{{ $leave->tanggal_selesai->format('d/m/Y') }}</td>
                            <td><span class="badge bg-{{ $leaveColors[$leave->status] ?? 'secondary' }}">{{ ucfirst($leave->status) }}</span></td>
                            <td><a href="{{ route('admin.izin.show', $leave) }}" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a></td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted">Belum ada pengajuan izin</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
