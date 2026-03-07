@extends('peran.staf.app')
@section('judul', 'Kinerja')

@section('konten')
@php
    $userRole = auth()->user()->peran;
    $fiturKinerja = [
        [
            'label' => 'Konten Kinerja',
            'deskripsi' => 'Dokumen, informasi, dan publikasi kinerja yang bisa dibaca atau diunduh.',
            'route' => route('staf.kinerja.index'),
            'icon' => 'bi-collection-fill',
            'jumlah' => $ringkasan['konten_total'] ?? 0,
            'warna' => 'primary',
        ],
        [
            'label' => 'SKP Saya',
            'deskripsi' => 'Kelola target kerja, capaian, dan pengajuan penilaian SKP.',
            'route' => route('staf.skp.index'),
            'icon' => 'bi-file-earmark-bar-graph-fill',
            'jumlah' => $ringkasan['skp_total'] ?? 0,
            'warna' => 'success',
            'show' => !in_array($userRole, ['kepegawaian']),
        ],
        [
            'label' => 'PKG / BKD',
            'deskripsi' => 'Lihat hasil penilaian kinerja guru atau evaluasi kerja pribadi.',
            'route' => route('staf.evaluasi.pkg'),
            'icon' => 'bi-clipboard2-data-fill',
            'jumlah' => $ringkasan['pkg_total'] ?? 0,
            'warna' => 'warning',
        ],
        [
            'label' => 'Asesmen P5',
            'deskripsi' => 'Pantau daftar penilaian P5 yang tersedia untuk staf.',
            'route' => route('staf.evaluasi.p5'),
            'icon' => 'bi-award-fill',
            'jumlah' => $ringkasan['p5_total'] ?? 0,
            'warna' => 'danger',
        ],
        [
            'label' => 'Analisis STAR',
            'deskripsi' => 'Susun refleksi kerja dan analisis perbaikan berbasis STAR.',
            'route' => route('staf.evaluasi.star'),
            'icon' => 'bi-stars',
            'jumlah' => $ringkasan['star_total'] ?? 0,
            'warna' => 'info',
        ],
        [
            'label' => 'Bukti Fisik',
            'deskripsi' => 'Unggah bukti dokumen pendukung evaluasi dan kegiatan.',
            'route' => route('staf.evaluasi.bukti-fisik'),
            'icon' => 'bi-folder2-open',
            'jumlah' => $ringkasan['bukti_total'] ?? 0,
            'warna' => 'secondary',
        ],
        [
            'label' => 'Metode Pembelajaran',
            'deskripsi' => 'Kelola model atau strategi pembelajaran yang sudah diterapkan.',
            'route' => route('staf.evaluasi.pembelajaran'),
            'icon' => 'bi-journal-richtext',
            'jumlah' => $ringkasan['pembelajaran_total'] ?? 0,
            'warna' => 'dark',
        ],
    ];
@endphp

<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h4 class="fw-bold mb-1">Pusat Kinerja</h4>
        <p class="text-muted mb-0" style="font-size:.85rem;">Semua fitur kinerja staf dalam satu dashboard: konten, SKP, evaluasi, dan dokumen pendukung.</p>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <div class="text-muted small">Konten Kinerja Aktif</div>
                        <div class="fw-bold fs-3">{{ $ringkasan['konten_total'] }}</div>
                    </div>
                    <span class="badge text-bg-primary"><i class="bi bi-collection"></i></span>
                </div>
                <div class="small text-muted">{{ $ringkasan['konten_unggulan'] }} konten unggulan siap dibaca staf.</div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <div class="text-muted small">SKP Saya</div>
                        <div class="fw-bold fs-3">{{ $ringkasan['skp_total'] }}</div>
                    </div>
                    <span class="badge text-bg-success"><i class="bi bi-file-earmark-bar-graph"></i></span>
                </div>
                <div class="small text-muted">{{ $ringkasan['skp_diajukan'] }} SKP sedang menunggu penilaian.</div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <div class="text-muted small">Evaluasi & Penilaian</div>
                        <div class="fw-bold fs-3">{{ ($ringkasan['pkg_total'] ?? 0) + ($ringkasan['p5_total'] ?? 0) }}</div>
                    </div>
                    <span class="badge text-bg-warning"><i class="bi bi-clipboard2-check"></i></span>
                </div>
                <div class="small text-muted">Gabungan PKG/BKD dan asesmen P5 yang tersedia.</div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <div class="text-muted small">Portofolio Pribadi</div>
                        <div class="fw-bold fs-3">{{ ($ringkasan['star_total'] ?? 0) + ($ringkasan['bukti_total'] ?? 0) + ($ringkasan['pembelajaran_total'] ?? 0) }}</div>
                    </div>
                    <span class="badge text-bg-info"><i class="bi bi-briefcase-fill"></i></span>
                </div>
                <div class="small text-muted">Analisis STAR, bukti fisik, dan metode pembelajaran yang Anda simpan.</div>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4 border-0 shadow-sm">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            <div>
                <h5 class="fw-bold mb-1">Modul Kinerja</h5>
                <p class="text-muted mb-0 small">Akses cepat ke seluruh fitur yang terkait dengan penilaian, target kerja, dan portofolio staf.</p>
            </div>
            <span class="badge rounded-pill text-bg-dark">{{ collect($fiturKinerja)->filter(fn ($fitur) => $fitur['show'] ?? true)->count() }} fitur</span>
        </div>

        <div class="row g-3">
            @foreach($fiturKinerja as $fitur)
                @continue(isset($fitur['show']) && !$fitur['show'])
                <div class="col-md-6 col-xl-4">
                    <a href="{{ $fitur['route'] }}" class="text-decoration-none text-reset d-block h-100">
                        <div class="card h-100 border border-{{ $fitur['warna'] }}-subtle shadow-sm">
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div class="rounded-3 bg-{{ $fitur['warna'] }}-subtle text-{{ $fitur['warna'] }} px-3 py-2">
                                        <i class="bi {{ $fitur['icon'] }}"></i>
                                    </div>
                                    <span class="badge text-bg-light">{{ $fitur['jumlah'] }} item</span>
                                </div>
                                <h6 class="fw-bold mb-1">{{ $fitur['label'] }}</h6>
                                <p class="text-muted small mb-3">{{ $fitur['deskripsi'] }}</p>
                                <div class="mt-auto small fw-semibold text-{{ $fitur['warna'] }}">
                                    Buka fitur <i class="bi bi-arrow-right-short"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body py-3">
        <form class="row g-2">
            <div class="col-md-4">
                <label class="form-label">Kategori</label>
                <select name="kategori" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Semua kategori</option>
                    @foreach($kategoriList as $kategori)
                        <option value="{{ $kategori }}" {{ request('kategori') === $kategori ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_', ' ', $kategori)) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Cari</label>
                <input type="text" name="cari" class="form-control form-control-sm" placeholder="Cari judul/deskripsi..." value="{{ request('cari') }}">
            </div>
            <div class="col-md-2 d-grid">
                <label class="form-label d-none d-md-block">&nbsp;</label>
                <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-search me-1"></i>Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
    <div>
        <h5 class="fw-bold mb-1">Dokumen & Publikasi Kinerja</h5>
        <p class="text-muted mb-0 small">Konten publik dan internal yang bisa dibaca staf sebagai referensi kinerja.</p>
    </div>
    <span class="badge text-bg-primary">{{ $konten->total() }} hasil</span>
</div>

<div class="row g-3">
    @forelse($konten as $item)
    <div class="col-md-6 col-xl-4">
        <div class="card h-100">
            <div class="card-body d-flex flex-column">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="badge bg-info-subtle text-info-emphasis">{{ ucfirst(str_replace('_', ' ', $item->kategori)) }}</span>
                    @if($item->unggulan)
                        <span class="badge bg-warning text-dark"><i class="bi bi-star-fill"></i> Unggulan</span>
                    @endif
                </div>

                <h6 class="fw-bold mb-1">{{ $item->judul }}</h6>
                <p class="text-muted mb-2" style="font-size:.82rem;">{{ Str::limit($item->deskripsi ?: strip_tags($item->konten), 120) }}</p>

                <div class="small text-muted mb-3">
                    <div><i class="bi bi-file-earmark-text me-1"></i>Tipe: {{ ucfirst($item->tipe) }}</div>
                    <div><i class="bi bi-calendar3 me-1"></i>Update: {{ $item->updated_at->translatedFormat('d M Y') }}</div>
                    @if($item->ukuran_file)
                        <div><i class="bi bi-hdd me-1"></i>Ukuran: {{ $item->ukuran_format }}</div>
                    @endif
                </div>

                <div class="d-flex gap-2 mt-auto">
                    <a href="{{ route('staf.kinerja.show', $item) }}" class="btn btn-outline-primary btn-sm w-100">
                        <i class="bi bi-eye me-1"></i>Detail
                    </a>
                    @if($item->path_file || $item->url_external)
                        <a href="{{ route('staf.kinerja.download', $item) }}" class="btn btn-success btn-sm w-100">
                            <i class="bi bi-download me-1"></i>Unduh
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="card">
            <div class="card-body text-center py-5 text-muted">
                <i class="bi bi-inbox" style="font-size:2rem;"></i>
                <p class="mb-0 mt-2">Belum ada konten kinerja tersedia.</p>
            </div>
        </div>
    </div>
    @endforelse
</div>

@if($konten->hasPages())
<div class="mt-4">
    {{ $konten->links() }}
</div>
@endif
@endsection
