@extends('peran.admin.app')
@section('judul', 'Edit Konten Publik')

@section('konten')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color:var(--dark);">Edit Konten Publik</h4>
        <p class="text-muted mb-0" style="font-size:.82rem;">{{ $kontenPublik->judul }}</p>
    </div>
    <a href="{{ route('admin.halaman-publik.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<form action="{{ route('admin.halaman-publik.update', $kontenPublik) }}" method="POST" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card" style="border:none;border-radius:var(--card-radius);box-shadow:0 1px 4px rgba(0,0,0,.06);">
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:.82rem;">Judul <span class="text-danger">*</span></label>
                        <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror" value="{{ old('judul', $kontenPublik->judul) }}" required>
                        @error('judul') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:.82rem;">Deskripsi Singkat</label>
                        <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" rows="2">{{ old('deskripsi', $kontenPublik->deskripsi) }}</textarea>
                        @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:.82rem;">Konten / Detail</label>
                        <textarea name="konten" class="form-control @error('konten') is-invalid @enderror" rows="8">{{ old('konten', $kontenPublik->konten) }}</textarea>
                        @error('konten') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:.82rem;">URL External</label>
                        <input type="url" name="url_external" class="form-control @error('url_external') is-invalid @enderror" value="{{ old('url_external', $kontenPublik->url_external) }}">
                        @error('url_external') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size:.82rem;">Upload File Baru</label>
                            <input type="file" name="file" class="form-control form-control-sm @error('file') is-invalid @enderror">
                            @error('file') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            @if($kontenPublik->path_file)
                                <small class="text-muted">File saat ini: <a href="{{ $kontenPublik->file_url }}" target="_blank">{{ $kontenPublik->nama_file }}</a> ({{ $kontenPublik->ukuran_format }})</small>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size:.82rem;">Thumbnail Baru</label>
                            <input type="file" name="thumbnail" class="form-control form-control-sm @error('thumbnail') is-invalid @enderror" accept="image/*">
                            @error('thumbnail') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            @if($kontenPublik->thumbnail)
                                <div class="mt-1"><img src="{{ $kontenPublik->thumbnail_url }}" alt="" style="height:50px;border-radius:6px;"></div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-3" style="border:none;border-radius:var(--card-radius);box-shadow:0 1px 4px rgba(0,0,0,.06);">
                <div class="card-header bg-transparent border-0 pt-3 pb-1">
                    <h6 class="fw-bold" style="font-size:.85rem;"><i class="bi bi-sliders me-1"></i> Pengaturan</h6>
                </div>
                <div class="card-body pt-2">
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:.78rem;">Kategori <span class="text-danger">*</span></label>
                        <select name="kategori" class="form-select form-select-sm" required>
                            @foreach(['profil'=>'Profil Sekolah','visi_misi'=>'Visi & Misi','pengurus'=>'Pengurus / Struktur','dokumen'=>'Dokumen','galeri'=>'Galeri Foto','video'=>'Video','kerjasama'=>'Kerjasama / MOU','prestasi'=>'Prestasi','pengumuman'=>'Pengumuman'] as $val => $label)
                                <option value="{{ $val }}" {{ old('kategori', $kontenPublik->kategori) === $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:.78rem;">Tipe Konten <span class="text-danger">*</span></label>
                        <select name="tipe" class="form-select form-select-sm" required>
                            @foreach(['teks'=>'Teks / Artikel','gambar'=>'Gambar','video'=>'Video','dokumen'=>'Dokumen (PDF/Word)','link'=>'Link External'] as $val => $label)
                                <option value="{{ $val }}" {{ old('tipe', $kontenPublik->tipe) === $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:.78rem;">Tampilkan Di <span class="text-danger">*</span></label>
                        <select name="bagian" class="form-select form-select-sm" required>
                            <option value="kinerja" {{ old('bagian', $kontenPublik->bagian) === 'kinerja' ? 'selected' : '' }}>Halaman Kinerja</option>
                            <option value="halaman_utama" {{ old('bagian', $kontenPublik->bagian) === 'halaman_utama' ? 'selected' : '' }}>Halaman Utama</option>
                            <option value="keduanya" {{ old('bagian', $kontenPublik->bagian) === 'keduanya' ? 'selected' : '' }}>Keduanya</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:.78rem;">Urutan</label>
                        <input type="number" name="urutan" class="form-control form-control-sm" value="{{ old('urutan', $kontenPublik->urutan) }}" min="0">
                    </div>

                    <div class="d-flex gap-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="aktif" value="1" id="aktifSwitch" {{ old('aktif', $kontenPublik->aktif) ? 'checked' : '' }}>
                            <label class="form-check-label" for="aktifSwitch" style="font-size:.78rem;">Aktif</label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="unggulan" value="1" id="unggulanSwitch" {{ old('unggulan', $kontenPublik->unggulan) ? 'checked' : '' }}>
                            <label class="form-check-label" for="unggulanSwitch" style="font-size:.78rem;">Unggulan</label>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100">
                <i class="bi bi-check-lg"></i> Simpan Perubahan
            </button>
        </div>
    </div>
</form>
@endsection
