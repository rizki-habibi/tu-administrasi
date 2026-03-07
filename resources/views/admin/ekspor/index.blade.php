@extends('peran.admin.app')
@section('judul', 'Pusat Ekspor Data')

@section('konten')
<div class="mb-4">
    <h5 class="fw-bold mb-1"><i class="bi bi-download text-primary me-2"></i>Pusat Ekspor Data</h5>
    <p class="text-muted mb-0" style="font-size:.82rem;">Unduh dan cetak data dalam berbagai format (CSV, PDF)</p>
</div>

<div class="row g-3">
    {{-- Export Data Staf --}}
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center" style="width:48px;height:48px;background:linear-gradient(135deg,#6366f1,#818cf8);">
                        <i class="bi bi-people-fill text-white" style="font-size:1.2rem;"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold" style="font-size:.9rem;">Data Staf</h6>
                        <small class="text-muted">{{ $stats['staff'] }} data staf</small>
                    </div>
                </div>
                <p class="text-muted mb-3" style="font-size:.8rem;">Ekspor seluruh data staf meliputi nama, email, jabatan, telepon, dan status.</p>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.ekspor.staff', ['format' => 'csv']) }}" class="btn btn-sm btn-outline-success flex-fill export-btn" data-format="csv">
                        <i class="bi bi-filetype-csv me-1"></i>CSV
                    </a>
                    <a href="{{ route('admin.ekspor.staff', ['format' => 'pdf']) }}" class="btn btn-sm btn-outline-danger flex-fill" target="_blank">
                        <i class="bi bi-printer me-1"></i>PDF
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Export Kehadiran --}}
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center" style="width:48px;height:48px;background:linear-gradient(135deg,#10b981,#34d399);">
                        <i class="bi bi-fingerprint text-white" style="font-size:1.2rem;"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold" style="font-size:.9rem;">Data Kehadiran</h6>
                        <small class="text-muted">{{ $stats['kehadiran'] }} data bulan ini</small>
                    </div>
                </div>
                <p class="text-muted mb-3" style="font-size:.8rem;">Ekspor rekap kehadiran staf meliputi jam masuk, pulang, status, dan lokasi.</p>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.ekspor.kehadiran', ['format' => 'csv']) }}" class="btn btn-sm btn-outline-success flex-fill export-btn" data-format="csv">
                        <i class="bi bi-filetype-csv me-1"></i>CSV
                    </a>
                    <a href="{{ route('admin.ekspor.kehadiran', ['format' => 'pdf']) }}" class="btn btn-sm btn-outline-danger flex-fill" target="_blank">
                        <i class="bi bi-printer me-1"></i>PDF
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Export Dokumen --}}
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center" style="width:48px;height:48px;background:linear-gradient(135deg,#8b5cf6,#a78bfa);">
                        <i class="bi bi-archive-fill text-white" style="font-size:1.2rem;"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold" style="font-size:.9rem;">Dokumen & Arsip</h6>
                        <small class="text-muted">{{ $stats['dokumen'] }} dokumen</small>
                    </div>
                </div>
                <p class="text-muted mb-3" style="font-size:.8rem;">Ekspor daftar dokumen dan arsip digital yang tersimpan di sistem.</p>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.ekspor.dokumen', ['format' => 'csv']) }}" class="btn btn-sm btn-outline-success flex-fill export-btn" data-format="csv">
                        <i class="bi bi-filetype-csv me-1"></i>CSV
                    </a>
                    <a href="{{ route('admin.ekspor.dokumen', ['format' => 'pdf']) }}" class="btn btn-sm btn-outline-danger flex-fill" target="_blank">
                        <i class="bi bi-printer me-1"></i>PDF
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Info Ringkasan --}}
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center" style="width:48px;height:48px;background:linear-gradient(135deg,#f59e0b,#fbbf24);">
                        <i class="bi bi-journal-text text-white" style="font-size:1.2rem;"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold" style="font-size:.9rem;">Laporan</h6>
                        <small class="text-muted">{{ $stats['laporan'] }} laporan</small>
                    </div>
                </div>
                <p class="text-muted mb-3" style="font-size:.8rem;">Ekspor data laporan dari berbagai divisi staf TU.</p>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.ekspor.laporan') }}" class="btn btn-sm btn-outline-success flex-fill export-btn">
                        <i class="bi bi-filetype-csv me-1"></i>CSV
                    </a>
                    <a href="{{ route('admin.laporan.index') }}" class="btn btn-sm btn-outline-primary flex-fill">
                        <i class="bi bi-eye me-1"></i>Lihat
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Surat --}}
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center" style="width:48px;height:48px;background:linear-gradient(135deg,#ef4444,#f87171);">
                        <i class="bi bi-envelope-paper-fill text-white" style="font-size:1.2rem;"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold" style="font-size:.9rem;">Surat Menyurat</h6>
                        <small class="text-muted">{{ $stats['surat'] }} surat</small>
                    </div>
                </div>
                <p class="text-muted mb-3" style="font-size:.8rem;">Ekspor arsip surat masuk dan surat keluar sekolah.</p>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.ekspor.surat') }}" class="btn btn-sm btn-outline-success flex-fill export-btn">
                        <i class="bi bi-filetype-csv me-1"></i>CSV
                    </a>
                    <a href="{{ route('admin.surat.index') }}" class="btn btn-sm btn-outline-primary flex-fill">
                        <i class="bi bi-eye me-1"></i>Lihat
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Pengajuan Izin --}}
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center" style="width:48px;height:48px;background:linear-gradient(135deg,#3b82f6,#60a5fa);">
                        <i class="bi bi-calendar2-check text-white" style="font-size:1.2rem;"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold" style="font-size:.9rem;">Pengajuan Izin</h6>
                        <small class="text-muted">{{ $stats['izin'] }} pengajuan</small>
                    </div>
                </div>
                <p class="text-muted mb-3" style="font-size:.8rem;">Ekspor rekap pengajuan izin, cuti, sakit, dan dinas luar.</p>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.ekspor.izin') }}" class="btn btn-sm btn-outline-success flex-fill export-btn">
                        <i class="bi bi-filetype-csv me-1"></i>CSV
                    </a>
                    <a href="{{ route('admin.izin.index') }}" class="btn btn-sm btn-outline-primary flex-fill">
                        <i class="bi bi-eye me-1"></i>Lihat
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Agenda --}}
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center" style="width:48px;height:48px;background:linear-gradient(135deg,#14b8a6,#2dd4bf);">
                        <i class="bi bi-calendar-event text-white" style="font-size:1.2rem;"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold" style="font-size:.9rem;">Agenda / Event</h6>
                        <small class="text-muted">{{ $stats['agenda'] }} agenda</small>
                    </div>
                </div>
                <p class="text-muted mb-3" style="font-size:.8rem;">Ekspor jadwal kegiatan, rapat, dan event sekolah.</p>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.ekspor.agenda') }}" class="btn btn-sm btn-outline-success flex-fill export-btn">
                        <i class="bi bi-filetype-csv me-1"></i>CSV
                    </a>
                    <a href="{{ route('admin.agenda.index') }}" class="btn btn-sm btn-outline-primary flex-fill">
                        <i class="bi bi-eye me-1"></i>Lihat
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Inventaris --}}
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center" style="width:48px;height:48px;background:linear-gradient(135deg,#f97316,#fb923c);">
                        <i class="bi bi-box-seam text-white" style="font-size:1.2rem;"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold" style="font-size:.9rem;">Inventaris</h6>
                        <small class="text-muted">{{ $stats['inventaris'] }} barang</small>
                    </div>
                </div>
                <p class="text-muted mb-3" style="font-size:.8rem;">Ekspor data inventaris dan sarana prasarana sekolah.</p>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.ekspor.inventaris') }}" class="btn btn-sm btn-outline-success flex-fill export-btn">
                        <i class="bi bi-filetype-csv me-1"></i>CSV
                    </a>
                    <a href="{{ route('admin.inventaris.index') }}" class="btn btn-sm btn-outline-primary flex-fill">
                        <i class="bi bi-eye me-1"></i>Lihat
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Keuangan --}}
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center" style="width:48px;height:48px;background:linear-gradient(135deg,#22c55e,#4ade80);">
                        <i class="bi bi-cash-stack text-white" style="font-size:1.2rem;"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold" style="font-size:.9rem;">Keuangan</h6>
                        <small class="text-muted">{{ $stats['keuangan'] }} transaksi</small>
                    </div>
                </div>
                <p class="text-muted mb-3" style="font-size:.8rem;">Ekspor catatan keuangan, pemasukan, dan pengeluaran.</p>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.ekspor.keuangan') }}" class="btn btn-sm btn-outline-success flex-fill export-btn">
                        <i class="bi bi-filetype-csv me-1"></i>CSV
                    </a>
                    <a href="{{ route('admin.keuangan.index') }}" class="btn btn-sm btn-outline-primary flex-fill">
                        <i class="bi bi-eye me-1"></i>Lihat
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.export-btn').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        const url = this.href;
        const card = this.closest('.card');
        const title = card.querySelector('h6').textContent;
        Swal.fire({
            title: 'Mengekspor ' + title + '...',
            html: '<div class="mb-2">Sedang memproses file export</div><div class="progress" style="height:6px;border-radius:4px;"><div class="progress-bar bg-primary progress-bar-striped progress-bar-animated" style="width:0%"></div></div>',
            allowOutsideClick: false, showConfirmButton: false,
            didOpen: () => {
                const bar = Swal.getHtmlContainer().querySelector('.progress-bar');
                let w = 0;
                const interval = setInterval(() => { w = Math.min(w + Math.random() * 15, 90); bar.style.width = w + '%'; }, 200);
                fetch(url).then(r => r.blob()).then(blob => {
                    clearInterval(interval); bar.style.width = '100%';
                    const a = document.createElement('a'); a.href = URL.createObjectURL(blob);
                    a.download = title.replace(/\s+/g, '_').toLowerCase() + '.csv';
                    document.body.appendChild(a); a.click(); a.remove();
                    Swal.fire({ icon: 'success', title: 'Ekspor Berhasil!', text: 'File telah diunduh', timer: 2000, showConfirmButton: false });
                }).catch(() => { clearInterval(interval); Swal.fire({ icon: 'error', title: 'Gagal Ekspor', text: 'Terjadi kesalahan saat mengekspor data' }); });
            }
        });
    });
});
</script>
@endpush
