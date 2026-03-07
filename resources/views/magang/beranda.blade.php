@extends('peran.magang.app')
@section('judul', 'Beranda')

@section('konten')
<div class="mb-4">
    <h4 class="fw-bold mb-1" style="color:#0f172a;">Selamat Datang, {{ auth()->user()->nama }}!</h4>
    <p class="text-muted mb-0" style="font-size:.85rem;">
        <i class="bi bi-mortarboard-fill me-1"></i>Staff Magang
        @if(auth()->user()->instansi_asal) — {{ auth()->user()->instansi_asal }}@endif
        @if(auth()->user()->pembimbing_lapangan) | Pembimbing: {{ auth()->user()->pembimbing_lapangan }}@endif
    </p>
</div>

{{-- Info Magang --}}
@if(auth()->user()->tanggal_mulai_magang || auth()->user()->tanggal_selesai_magang)
<div class="card mb-4" style="border-left: 4px solid #0891b2;">
    <div class="card-body py-3 d-flex flex-wrap gap-4 align-items-center">
        @if(auth()->user()->tanggal_mulai_magang)
        <div>
            <small class="text-muted d-block" style="font-size:.7rem;">Mulai Magang</small>
            <span class="fw-semibold" style="font-size:.85rem;">{{ auth()->user()->tanggal_mulai_magang->format('d M Y') }}</span>
        </div>
        @endif
        @if(auth()->user()->tanggal_selesai_magang)
        <div>
            <small class="text-muted d-block" style="font-size:.7rem;">Selesai Magang</small>
            <span class="fw-semibold" style="font-size:.85rem;">{{ auth()->user()->tanggal_selesai_magang->format('d M Y') }}</span>
        </div>
        @endif
        @if($sisaHari !== null)
        <div class="ms-auto text-end">
            <small class="text-muted d-block" style="font-size:.7rem;">Sisa Hari</small>
            <span class="fw-bold fs-5" style="color:#0891b2;">{{ $sisaHari }}</span> <small class="text-muted">hari</small>
        </div>
        @endif
    </div>
</div>
@endif

{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #0891b2, #06b6d4);">
            <div class="d-flex align-items-center gap-3">
                <div class="icon-box"><i class="bi bi-fingerprint"></i></div>
                <div>
                    <h3>{{ $monthlyStats['hadir'] }}</h3>
                    <p>Hadir Bulan Ini</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #8b5cf6, #a78bfa);">
            <div class="d-flex align-items-center gap-3">
                <div class="icon-box"><i class="bi bi-journal-text"></i></div>
                <div>
                    <h3>{{ $logbookBulanIni }}</h3>
                    <p>Logbook Bulan Ini</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #f59e0b, #fbbf24);">
            <div class="d-flex align-items-center gap-3">
                <div class="icon-box"><i class="bi bi-clipboard2-check"></i></div>
                <div>
                    <h3>{{ $kegiatanAktif }}</h3>
                    <p>Kegiatan Aktif</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #10b981, #34d399);">
            <div class="d-flex align-items-center gap-3">
                <div class="icon-box"><i class="bi bi-check-circle"></i></div>
                <div>
                    <h3>{{ $kegiatanSelesai }}</h3>
                    <p>Kegiatan Selesai</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- Quick Actions --}}
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header bg-white border-0 pt-3 pb-0">
                <h6 class="fw-bold mb-0"><i class="bi bi-lightning-fill text-warning me-2"></i>Aksi Cepat</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    @if(!$todayAttendance || !$todayAttendance->jam_masuk)
                        <a href="{{ route('magang.kehadiran.index') }}" class="btn btn-primary"><i class="bi bi-fingerprint me-2"></i>Absen Masuk</a>
                    @elseif(!$todayAttendance->jam_pulang)
                        <a href="{{ route('magang.kehadiran.index') }}" class="btn btn-outline-primary"><i class="bi bi-fingerprint me-2"></i>Absen Pulang</a>
                    @else
                        <button class="btn btn-success" disabled><i class="bi bi-check-circle me-2"></i>Sudah Absen Hari Ini</button>
                    @endif

                    @if(!$logbookHariIni)
                        <a href="{{ route('magang.logbook.create') }}" class="btn btn-outline-primary"><i class="bi bi-journal-plus me-2"></i>Tulis Logbook Hari Ini</a>
                    @else
                        <a href="{{ route('magang.logbook.show', $logbookHariIni) }}" class="btn btn-outline-success"><i class="bi bi-journal-check me-2"></i>Lihat Logbook Hari Ini</a>
                    @endif

                    <a href="{{ route('magang.kegiatan.create') }}" class="btn btn-outline-secondary"><i class="bi bi-plus-circle me-2"></i>Tambah Kegiatan</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Notifications --}}
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header bg-white border-0 pt-3 pb-0 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0"><i class="bi bi-bell-fill text-info me-2"></i>Notifikasi Terbaru</h6>
                @if($unreadNotifications > 0)
                    <span class="badge bg-danger">{{ $unreadNotifications }} baru</span>
                @endif
            </div>
            <div class="card-body">
                @forelse($recentNotifications as $notif)
                    <div class="d-flex gap-2 mb-2 p-2 rounded {{ !$notif->sudah_dibaca ? 'bg-light' : '' }}" style="font-size:.82rem;">
                        <i class="bi bi-circle-fill mt-1" style="font-size:.4rem;color:{{ !$notif->sudah_dibaca ? '#0891b2' : '#cbd5e1' }};"></i>
                        <div>
                            <div class="fw-semibold">{{ $notif->judul }}</div>
                            <div class="text-muted text-truncate" style="max-width:280px;">{{ $notif->pesan }}</div>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted py-3" style="font-size:.82rem;">
                        <i class="bi bi-bell-slash d-block mb-1" style="font-size:1.5rem;"></i>
                        Belum ada notifikasi
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
