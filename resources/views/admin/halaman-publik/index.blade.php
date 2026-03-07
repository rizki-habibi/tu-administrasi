@extends('peran.admin.app')
@section('judul', 'Kelola Konten Publik')

@section('konten')
{{-- Header --}}
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color:var(--dark);">Kelola Konten Publik</h4>
        <p class="text-muted mb-0" style="font-size:.82rem;">Kelola konten yang ditampilkan di halaman utama & kinerja publik</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.halaman-publik.statistik') }}" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-graph-up"></i> Statistik Pengunjung
        </a>
        <a href="{{ route('admin.halaman-publik.saran') }}" class="btn btn-outline-warning btn-sm position-relative">
            <i class="bi bi-chat-square-text"></i> Saran
            @php $saranBaru = \App\Models\SaranPengunjung::baru()->count(); @endphp
            @if($saranBaru > 0)
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size:.6rem;">{{ $saranBaru }}</span>
            @endif
        </a>
        <a href="{{ route('admin.halaman-publik.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg"></i> Tambah Konten
        </a>
    </div>
</div>

{{-- Filter --}}
<div class="card mb-4" style="border:none;border-radius:var(--card-radius);box-shadow:0 1px 4px rgba(0,0,0,.06);">
    <div class="card-body py-3">
        <form class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label" style="font-size:.75rem;font-weight:600;">Kategori</label>
                <select name="kategori" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Semua Kategori</option>
                    @foreach(['profil'=>'Profil','visi_misi'=>'Visi & Misi','pengurus'=>'Pengurus','dokumen'=>'Dokumen','galeri'=>'Galeri','video'=>'Video','kerjasama'=>'Kerjasama','prestasi'=>'Prestasi','pengumuman'=>'Pengumuman'] as $val => $label)
                        <option value="{{ $val }}" {{ request('kategori') === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label" style="font-size:.75rem;font-weight:600;">Bagian</label>
                <select name="bagian" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Semua Bagian</option>
                    <option value="halaman_utama" {{ request('bagian') === 'halaman_utama' ? 'selected' : '' }}>Halaman Utama</option>
                    <option value="kinerja" {{ request('bagian') === 'kinerja' ? 'selected' : '' }}>Kinerja</option>
                    <option value="keduanya" {{ request('bagian') === 'keduanya' ? 'selected' : '' }}>Keduanya</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label" style="font-size:.75rem;font-weight:600;">Cari</label>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari judul atau deskripsi..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-sm btn-primary w-100"><i class="bi bi-search"></i> Filter</button>
            </div>
        </form>
    </div>
</div>

{{-- Table --}}
<div class="card" style="border:none;border-radius:var(--card-radius);box-shadow:0 1px 4px rgba(0,0,0,.06);">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" style="font-size:.82rem;">
            <thead style="background:#f8fafc;">
                <tr>
                    <th style="width:40px;">No</th>
                    <th>Konten</th>
                    <th style="width:110px;">Kategori</th>
                    <th style="width:100px;">Bagian</th>
                    <th style="width:60px;">Tipe</th>
                    <th style="width:70px;">Status</th>
                    <th style="width:80px;">Urutan</th>
                    <th style="width:120px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($konten as $i => $item)
                <tr>
                    <td class="text-muted">{{ $konten->firstItem() + $i }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            @if($item->thumbnail)
                                <img src="{{ $item->thumbnail_url }}" alt="" style="width:40px;height:40px;border-radius:8px;object-fit:cover;">
                            @elseif($item->tipe === 'gambar' && $item->path_file)
                                <img src="{{ $item->file_url }}" alt="" style="width:40px;height:40px;border-radius:8px;object-fit:cover;">
                            @else
                                <div style="width:40px;height:40px;border-radius:8px;background:#f0f0ff;display:flex;align-items:center;justify-content:center;">
                                    @switch($item->tipe)
                                        @case('gambar') <i class="bi bi-image text-success"></i> @break
                                        @case('video') <i class="bi bi-play-circle text-danger"></i> @break
                                        @case('dokumen') <i class="bi bi-file-earmark text-primary"></i> @break
                                        @case('link') <i class="bi bi-link-45deg text-info"></i> @break
                                        @default <i class="bi bi-file-text text-secondary"></i>
                                    @endswitch
                                </div>
                            @endif
                            <div>
                                <div class="fw-semibold">{{ Str::limit($item->judul, 50) }}</div>
                                @if($item->deskripsi)
                                    <small class="text-muted">{{ Str::limit($item->deskripsi, 60) }}</small>
                                @endif
                                @if($item->unggulan)
                                    <span class="badge bg-warning text-dark" style="font-size:.55rem;"><i class="bi bi-star-fill"></i> Unggulan</span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>
                        @php
                            $kategoriColors = [
                                'profil' => 'primary', 'visi_misi' => 'info', 'pengurus' => 'success',
                                'dokumen' => 'secondary', 'galeri' => 'warning', 'video' => 'danger',
                                'kerjasama' => 'dark', 'prestasi' => 'primary', 'pengumuman' => 'info',
                            ];
                        @endphp
                        <span class="badge bg-{{ $kategoriColors[$item->kategori] ?? 'secondary' }}" style="font-size:.65rem;">
                            {{ ucfirst(str_replace('_', ' ', $item->kategori)) }}
                        </span>
                    </td>
                    <td>
                        <span class="badge bg-{{ $item->bagian === 'keduanya' ? 'success' : ($item->bagian === 'kinerja' ? 'info' : 'primary') }}" style="font-size:.6rem;">
                            {{ ucfirst(str_replace('_', ' ', $item->bagian)) }}
                        </span>
                    </td>
                    <td><small class="text-muted">{{ ucfirst($item->tipe) }}</small></td>
                    <td>
                        <form action="{{ route('admin.halaman-publik.toggle-aktif', $item) }}" method="POST" class="d-inline">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-sm p-0 border-0" title="{{ $item->aktif ? 'Nonaktifkan' : 'Aktifkan' }}">
                                <span class="badge bg-{{ $item->aktif ? 'success' : 'secondary' }}" style="font-size:.65rem;cursor:pointer;">
                                    {{ $item->aktif ? 'Aktif' : 'Draft' }}
                                </span>
                            </button>
                        </form>
                    </td>
                    <td class="text-muted text-center">{{ $item->urutan }}</td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('admin.halaman-publik.edit', $item) }}" class="btn btn-outline-primary btn-sm" style="padding:2px 8px;font-size:.7rem;" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @if($item->path_file)
                                <a href="{{ $item->file_url }}" target="_blank" class="btn btn-outline-success btn-sm" style="padding:2px 8px;font-size:.7rem;" title="Lihat File">
                                    <i class="bi bi-eye"></i>
                                </a>
                            @endif
                            <form action="{{ route('admin.halaman-publik.destroy', $item) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm" style="padding:2px 8px;font-size:.7rem;" data-confirm="Hapus konten ini?" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-5">
                        <div style="color:#94a3b8;">
                            <i class="bi bi-inbox" style="font-size:2.5rem;"></i>
                            <p class="mt-2 mb-0">Belum ada konten publik</p>
                            <a href="{{ route('admin.halaman-publik.create') }}" class="btn btn-sm btn-primary mt-2">
                                <i class="bi bi-plus-lg"></i> Tambah Konten Pertama
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($konten->hasPages())
        <div class="card-footer bg-transparent border-0 py-3">
            {{ $konten->links() }}
        </div>
    @endif
</div>
@endsection
