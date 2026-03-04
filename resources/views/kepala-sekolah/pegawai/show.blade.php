@extends('peran.kepala-sekolah.app')
@section('judul', 'Detail Staf - ' . $staff->nama)

@section('konten')
<div class="mb-4">
    <a href="{{ route('kepala-sekolah.pegawai.index') }}" class="text-decoration-none text-warning" style="font-size:.85rem;"><i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar Staf</a>
</div>

{{-- Profile Card --}}
<div class="card mb-4">
    <div class="card-body">
        <div class="d-flex align-items-center gap-3 flex-wrap">
            <div class="rounded-3 d-flex align-items-center justify-content-center text-white fw-bold" style="width:64px;height:64px;font-size:1.3rem;background:linear-gradient(135deg,#d97706,#ea580c);flex-shrink:0;">
                @if($staff->foto)
                    <img src="{{ asset('storage/' . $staff->foto) }}" class="rounded-3" style="width:64px;height:64px;object-fit:cover;" alt="">
                @else
                    {{ strtoupper(substr($staff->nama, 0, 2)) }}
                @endif
            </div>
            <div>
                <h5 class="fw-bold mb-0">{{ $staff->nama }}</h5>
                <span class="badge bg-warning bg-opacity-10 text-warning mt-1">{{ $staff->role_label }}</span>
            </div>
            <div class="ms-auto text-end d-none d-md-block">
                @if($staff->aktif)
                    <span class="badge bg-success bg-opacity-10 text-success"><i class="bi bi-check-circle me-1"></i>Aktif</span>
                @else
                    <span class="badge bg-danger bg-opacity-10 text-danger"><i class="bi bi-x-circle me-1"></i>Non-aktif</span>
                @endif
            </div>
        </div>
        <hr>
        <div class="row g-3" style="font-size:.85rem;">
            <div class="col-md-3"><strong class="text-muted d-block">NIP</strong>{{ $staff->nip ?? '-' }}</div>
            <div class="col-md-3"><strong class="text-muted d-block">Email</strong>{{ $staff->email }}</div>
            <div class="col-md-3"><strong class="text-muted d-block">Telepon</strong>{{ $staff->telepon ?? '-' }}</div>
            <div class="col-md-3"><strong class="text-muted d-block">Jabatan</strong>{{ $staff->jabatan ?? '-' }}</div>
            <div class="col-md-6"><strong class="text-muted d-block">Alamat</strong>{{ $staff->alamat ?? '-' }}</div>
        </div>
    </div>
</div>

{{-- Tabs --}}
<ul class="nav nav-tabs mb-3" role="tablist">
    <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#tab-attendance" style="font-size:.85rem;">Riwayat Kehadiran</a></li>
    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-skp" style="font-size:.85rem;">SKP</a></li>
    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-leave" style="font-size:.85rem;">Riwayat Izin/Cuti</a></li>
</ul>

<div class="tab-content">
    {{-- Attendance Tab --}}
    <div class="tab-pane fade show active" id="tab-attendance">
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead><tr><th>Tanggal</th><th>Masuk</th><th>Keluar</th><th>Status</th><th>Catatan</th></tr></thead>
                        <tbody>
                        @forelse($staff->attendances->sortByDesc('date')->take(30) as $att)
                            <tr>
                                <td>{{ $att->date->translatedFormat('d M Y') }}</td>
                                <td>{{ $att->jam_masuk ?? '-' }}</td>
                                <td>{{ $att->jam_pulang ?? '-' }}</td>
                                <td><span class="badge bg-{{ $att->status_badge }} bg-opacity-10 text-{{ $att->status_badge }}">{{ ucfirst($att->status) }}</span></td>
                                <td style="max-width:200px;">{{ $att->catatan ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center py-3 text-muted">Belum ada data kehadiran</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- SKP Tab --}}
    <div class="tab-pane fade" id="tab-skp">
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead><tr><th>Periode</th><th>Sasaran Kinerja</th><th>Nilai</th><th>Status</th><th>Aksi</th></tr></thead>
                        <tbody>
                        @forelse($staff->skp as $skp)
                            <tr>
                                <td>{{ $skp->periode === 'januari_juni' ? 'Jan-Jun' : 'Jul-Des' }} {{ $skp->tahun }}</td>
                                <td style="max-width:200px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $skp->sasaran_kinerja }}</td>
                                <td>{{ $skp->nilai_capaian ? number_format($skp->nilai_capaian, 1) : '-' }}</td>
                                <td>{!! '<span class="badge '.$skp->status_badge.'">'.ucfirst($skp->status).'</span>' !!}</td>
                                <td><a href="{{ route('kepala-sekolah.skp.show', $skp) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-eye"></i></a></td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center py-3 text-muted">Belum ada data SKP</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Leave Tab --}}
    <div class="tab-pane fade" id="tab-leave">
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead><tr><th>Jenis</th><th>Tanggal</th><th>Durasi</th><th>Alasan</th><th>Status</th></tr></thead>
                        <tbody>
                        @forelse($staff->leaveRequests->sortByDesc('created_at') as $lr)
                            <tr>
                                <td><span class="badge bg-info bg-opacity-10 text-info">{{ ucfirst($lr->jenis) }}</span></td>
                                <td>{{ $lr->tanggal_mulai->format('d/m/Y') }} - {{ $lr->tanggal_selesai->format('d/m/Y') }}</td>
                                <td>{{ $lr->duration }} hari</td>
                                <td style="max-width:200px;">{{ \Str::limit($lr->reason, 50) }}</td>
                                <td><span class="badge bg-{{ $lr->status_badge }} bg-opacity-10 text-{{ $lr->status_badge }}">{{ ucfirst($lr->status) }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center py-3 text-muted">Belum ada riwayat izin/cuti</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
