@extends('peran.admin.app')
@section('judul', 'Tambah Panduan')

@section('konten')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color:var(--dark);">Tambah Panduan</h4>
        <p class="text-muted mb-0" style="font-size:.82rem;">Buat panduan baru untuk membantu pengguna memahami fitur aplikasi</p>
    </div>
    <a href="{{ route('admin.panduan.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<form action="{{ route('admin.panduan.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row g-4">
        {{-- Form Utama --}}
        <div class="col-lg-8">
            <div class="card" style="border:none;border-radius:var(--card-radius);box-shadow:0 1px 4px rgba(0,0,0,.06);">
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:.82rem;">Judul <span class="text-danger">*</span></label>
                        <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror" value="{{ old('judul') }}" required placeholder="Contoh: Panduan Penggunaan Aplikasi">
                        @error('judul') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:.82rem;">Deskripsi Singkat</label>
                        <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" rows="2" placeholder="Ringkasan isi panduan...">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:.82rem;">Konten <span class="text-danger">*</span></label>
                        <textarea name="konten" class="form-control @error('konten') is-invalid @enderror" rows="18" placeholder="Tulis konten panduan... (mendukung format Markdown)" style="font-family:monospace;font-size:.82rem;">{{ old('konten') }}</textarea>
                        @error('konten') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <small class="text-muted">Mendukung format Markdown: **bold**, *italic*, # heading, - list, ```code```, dll.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:.82rem;">Logo / Gambar</label>
                        <input type="file" name="logo" class="form-control form-control-sm @error('logo') is-invalid @enderror" accept="image/*" id="logoInput">
                        @error('logo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <small class="text-muted">Maks 2MB. Format: JPG, PNG, SVG, WebP.</small>
                        <div id="logoPreview" class="mt-2 d-none">
                            <img src="" alt="Preview" style="max-width:120px;max-height:120px;border-radius:10px;object-fit:contain;">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sidebar Pengaturan --}}
        <div class="col-lg-4">
            <div class="card mb-3" style="border:none;border-radius:var(--card-radius);box-shadow:0 1px 4px rgba(0,0,0,.06);">
                <div class="card-header bg-transparent border-0 pt-3 pb-1">
                    <h6 class="fw-bold" style="font-size:.85rem;"><i class="bi bi-sliders me-1"></i> Pengaturan</h6>
                </div>
                <div class="card-body pt-2">
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:.78rem;">Kategori <span class="text-danger">*</span></label>
                        <select name="kategori" class="form-select form-select-sm @error('kategori') is-invalid @enderror" required>
                            <option value="">Pilih Kategori</option>
                            @foreach(['panduan'=>'Panduan','dokumentasi'=>'Dokumentasi','changelog'=>'Changelog','referensi'=>'Referensi'] as $val => $label)
                                <option value="{{ $val }}" {{ old('kategori') === $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('kategori') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:.78rem;">Visibilitas <span class="text-danger">*</span></label>
                        <select name="visibilitas" class="form-select form-select-sm @error('visibilitas') is-invalid @enderror" required>
                            <option value="semua" {{ old('visibilitas', 'semua') === 'semua' ? 'selected' : '' }}>Semua Pengguna</option>
                            <option value="admin" {{ old('visibilitas') === 'admin' ? 'selected' : '' }}>Admin Only</option>
                        </select>
                        @error('visibilitas') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:.78rem;">Ikon Bootstrap</label>
                        <input type="text" name="ikon" class="form-control form-control-sm @error('ikon') is-invalid @enderror" value="{{ old('ikon', 'bi-file-earmark-text') }}" placeholder="bi-book">
                        @error('ikon') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <small class="text-muted" style="font-size:.7rem;">Contoh: bi-book, bi-gear, bi-question-circle. <a href="https://icons.getbootstrap.com" target="_blank">Lihat ikon</a></small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:.78rem;">Warna</label>
                        <input type="color" name="warna" class="form-control form-control-sm form-control-color" value="{{ old('warna', '#6366f1') }}" style="height:36px;">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:.78rem;">Versi</label>
                        <input type="text" name="versi" class="form-control form-control-sm" value="{{ old('versi') }}" placeholder="v1.0">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:.78rem;">Urutan</label>
                        <input type="number" name="urutan" class="form-control form-control-sm" value="{{ old('urutan', 0) }}" min="0">
                        <small class="text-muted" style="font-size:.7rem;">Semakin kecil, semakin atas tampil.</small>
                    </div>

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="aktif" value="1" id="aktifSwitch" {{ old('aktif', '1') ? 'checked' : '' }}>
                        <label class="form-check-label" for="aktifSwitch" style="font-size:.78rem;">Aktif</label>
                    </div>
                </div>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn text-white" style="background:#6366f1;">
                    <i class="bi bi-plus-circle me-1"></i> Simpan Panduan
                </button>
                <a href="{{ route('admin.panduan.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
document.getElementById('logoInput').addEventListener('change', function(e) {
    const preview = document.getElementById('logoPreview');
    const img = preview.querySelector('img');
    if (this.files && this.files[0]) {
        const reader = new FileReader();
        reader.onload = function(ev) { img.src = ev.target.result; preview.classList.remove('d-none'); };
        reader.readAsDataURL(this.files[0]);
    } else { preview.classList.add('d-none'); }
});
</script>
@endpush
