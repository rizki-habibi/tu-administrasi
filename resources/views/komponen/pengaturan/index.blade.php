{{-- Pengaturan: Profil + Tampilan + Preferensi --}}
@php
    $routePrefix = auth()->user()->getRoutePrefix();
    $user = auth()->user();
@endphp

<div class="row g-4">
    {{-- Header --}}
    <div class="col-12">
        <h5 class="fw-bold mb-0"><i class="bi bi-gear-fill text-primary me-2"></i>Pengaturan</h5>
        <p class="text-muted mb-0" style="font-size:.82rem;">Kelola profil, password, dan tampilan aplikasi.</p>
    </div>

    {{-- Tab Navigation --}}
    <div class="col-12">
        <ul class="nav nav-pills gap-2" id="settingsTab" role="tablist">
            <li class="nav-item">
                <button class="nav-link active px-3 py-2" id="profil-tab" data-bs-toggle="pill" data-bs-target="#profil-panel" type="button" style="font-size:.82rem;border-radius:10px;">
                    <i class="bi bi-person-fill me-1"></i> Profil
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link px-3 py-2" id="password-tab" data-bs-toggle="pill" data-bs-target="#password-panel" type="button" style="font-size:.82rem;border-radius:10px;">
                    <i class="bi bi-key-fill me-1"></i> Password
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link px-3 py-2" id="tampilan-tab" data-bs-toggle="pill" data-bs-target="#tampilan-panel" type="button" style="font-size:.82rem;border-radius:10px;">
                    <i class="bi bi-palette-fill me-1"></i> Tampilan
                </button>
            </li>
        </ul>
    </div>

    {{-- Tab Content --}}
    <div class="col-12">
        <div class="tab-content" id="settingsTabContent">

            {{-- ═══ TAB: PROFIL ═══ --}}
            <div class="tab-pane fade show active" id="profil-panel">
                <div class="card border-0 shadow-sm" style="border-radius:14px;">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3"><i class="bi bi-person-circle text-primary me-2"></i>Informasi Profil</h6>

                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" style="font-size:.82rem;">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" style="font-size:.6rem;"></button>
                            </div>
                        @endif

                        <form action="{{ route($routePrefix . '.pengaturan.profil') }}" method="POST" enctype="multipart/form-data">
                            @csrf @method('PUT')

                            <div class="row g-3">
                                {{-- Foto --}}
                                <div class="col-12 d-flex align-items-center gap-3 mb-2">
                                    <div style="width:80px;height:80px;border-radius:50%;background:linear-gradient(135deg,#6366f1,#818cf8);display:flex;align-items:center;justify-content:center;overflow:hidden;flex-shrink:0;">
                                        @if($user->foto)
                                            <img src="{{ asset('storage/' . $user->foto) }}" style="width:100%;height:100%;object-fit:cover;">
                                        @else
                                            <span style="color:#fff;font-size:1.5rem;font-weight:700;">{{ strtoupper(substr($user->nama, 0, 2)) }}</span>
                                        @endif
                                    </div>
                                    <div>
                                        <label class="btn btn-sm btn-outline-primary" for="fotoInput" style="font-size:.78rem;">
                                            <i class="bi bi-camera me-1"></i> Ganti Foto
                                        </label>
                                        <input type="file" name="foto" id="fotoInput" class="d-none" accept="image/*">
                                        <p class="text-muted mb-0 mt-1" style="font-size:.7rem;">JPG, PNG. Maks 2MB.</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-medium" style="font-size:.82rem;">Nama Lengkap</label>
                                    <input type="text" name="nama" class="form-control" value="{{ old('nama', $user->nama) }}" style="border-radius:10px;">
                                    @error('nama') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-medium" style="font-size:.82rem;">Email</label>
                                    <input type="email" class="form-control" value="{{ $user->email }}" disabled style="border-radius:10px;background:#f1f5f9;">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-medium" style="font-size:.82rem;">NIP</label>
                                    <input type="text" class="form-control" value="{{ $user->nip ?? '-' }}" disabled style="border-radius:10px;background:#f1f5f9;">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-medium" style="font-size:.82rem;">Telepon</label>
                                    <input type="text" name="telepon" class="form-control" value="{{ old('telepon', $user->telepon) }}" style="border-radius:10px;">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-medium" style="font-size:.82rem;">Tanggal Lahir</label>
                                    <input type="date" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir', $user->tanggal_lahir?->format('Y-m-d')) }}" style="border-radius:10px;">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-medium" style="font-size:.82rem;">Jabatan</label>
                                    <input type="text" class="form-control" value="{{ $user->jabatan ?? '-' }}" disabled style="border-radius:10px;background:#f1f5f9;">
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-medium" style="font-size:.82rem;">Alamat</label>
                                    <textarea name="alamat" class="form-control" rows="2" style="border-radius:10px;">{{ old('alamat', $user->alamat) }}</textarea>
                                </div>

                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary px-4" style="border-radius:10px;font-size:.82rem;">
                                        <i class="bi bi-check-lg me-1"></i> Simpan Profil
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- ═══ TAB: PASSWORD ═══ --}}
            <div class="tab-pane fade" id="password-panel">
                <div class="card border-0 shadow-sm" style="border-radius:14px;">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3"><i class="bi bi-key-fill text-warning me-2"></i>Ubah Password</h6>

                        @if($errors->has('password_lama'))
                            <div class="alert alert-danger" style="font-size:.82rem;">{{ $errors->first('password_lama') }}</div>
                        @endif

                        <form action="{{ route($routePrefix . '.pengaturan.password') }}" method="POST">
                            @csrf @method('PUT')
                            <div class="row g-3" style="max-width:500px;">
                                <div class="col-12">
                                    <label class="form-label fw-medium" style="font-size:.82rem;">Password Lama</label>
                                    <input type="password" name="password_lama" class="form-control" style="border-radius:10px;" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-medium" style="font-size:.82rem;">Password Baru</label>
                                    <input type="password" name="password_baru" class="form-control" style="border-radius:10px;" required>
                                    @error('password_baru') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-medium" style="font-size:.82rem;">Konfirmasi Password Baru</label>
                                    <input type="password" name="password_baru_confirmation" class="form-control" style="border-radius:10px;" required>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-warning px-4" style="border-radius:10px;font-size:.82rem;">
                                        <i class="bi bi-key me-1"></i> Ubah Password
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- ═══ TAB: TAMPILAN ═══ --}}
            <div class="tab-pane fade" id="tampilan-panel">
                <div class="card border-0 shadow-sm" style="border-radius:14px;">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3"><i class="bi bi-palette-fill text-info me-2"></i>Pengaturan Tampilan</h6>
                        <p class="text-muted mb-4" style="font-size:.78rem;">Sesuaikan tampilan aplikasi sesuai preferensi Anda.</p>

                        <div class="row g-4" style="max-width:600px;">
                            {{-- Tema --}}
                            <div class="col-12">
                                <label class="form-label fw-medium" style="font-size:.82rem;">Tema Aplikasi</label>
                                <div class="d-flex gap-2">
                                    <div class="tema-option {{ ($settings['tema'] ?? 'gelap') === 'gelap' ? 'active' : '' }}" data-value="gelap" onclick="pilihTema(this)" style="cursor:pointer;padding:12px 20px;border-radius:12px;border:2px solid {{ ($settings['tema'] ?? 'gelap') === 'gelap' ? '#6366f1' : '#e5e7eb' }};background:#1e1b4b;color:#fff;text-align:center;flex:1;transition:border-color .2s;">
                                        <i class="bi bi-moon-stars-fill" style="font-size:1.2rem;"></i>
                                        <div style="font-size:.78rem;margin-top:4px;">Gelap</div>
                                    </div>
                                    <div class="tema-option {{ ($settings['tema'] ?? 'gelap') === 'terang' ? 'active' : '' }}" data-value="terang" onclick="pilihTema(this)" style="cursor:pointer;padding:12px 20px;border-radius:12px;border:2px solid {{ ($settings['tema'] ?? 'gelap') === 'terang' ? '#6366f1' : '#e5e7eb' }};background:#f8fafc;color:#1e293b;text-align:center;flex:1;transition:border-color .2s;">
                                        <i class="bi bi-sun-fill" style="font-size:1.2rem;"></i>
                                        <div style="font-size:.78rem;margin-top:4px;">Terang</div>
                                    </div>
                                </div>
                            </div>

                            {{-- Ukuran Font --}}
                            <div class="col-12">
                                <label class="form-label fw-medium" style="font-size:.82rem;">Ukuran Teks</label>
                                <div class="d-flex gap-2">
                                    @foreach(['kecil' => 'A', 'normal' => 'A', 'besar' => 'A'] as $uk => $label)
                                    <div class="font-option {{ ($settings['ukuran_font'] ?? 'normal') === $uk ? 'active' : '' }}" data-value="{{ $uk }}" onclick="pilihFont(this)" style="cursor:pointer;padding:10px 20px;border-radius:10px;border:2px solid {{ ($settings['ukuran_font'] ?? 'normal') === $uk ? '#6366f1' : '#e5e7eb' }};text-align:center;flex:1;transition:border-color .2s;">
                                        <span style="font-size:{{ $uk === 'kecil' ? '12px' : ($uk === 'normal' ? '16px' : '22px') }};font-weight:600;">{{ $label }}</span>
                                        <div style="font-size:.7rem;color:#64748b;margin-top:2px;">{{ ucfirst($uk) }}</div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Warna Aksen --}}
                            <div class="col-12">
                                <label class="form-label fw-medium" style="font-size:.82rem;">Warna Aksen</label>
                                <div class="d-flex gap-2 flex-wrap">
                                    @php $colors = ['#6366f1' => 'Indigo', '#10b981' => 'Hijau', '#f59e0b' => 'Amber', '#ef4444' => 'Merah', '#3b82f6' => 'Biru', '#8b5cf6' => 'Ungu', '#ec4899' => 'Pink']; @endphp
                                    @foreach($colors as $hex => $label)
                                    <div class="color-option {{ ($settings['warna_aksen'] ?? '#6366f1') === $hex ? 'active' : '' }}" data-value="{{ $hex }}" onclick="pilihWarna(this)" style="cursor:pointer;width:38px;height:38px;border-radius:50%;background:{{ $hex }};border:3px solid {{ ($settings['warna_aksen'] ?? '#6366f1') === $hex ? '#1e293b' : 'transparent' }};transition:border-color .2s;display:flex;align-items:center;justify-content:center;" title="{{ $label }}">
                                        @if(($settings['warna_aksen'] ?? '#6366f1') === $hex)
                                            <i class="bi bi-check" style="color:#fff;font-size:1rem;"></i>
                                        @endif
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Sidebar Mini --}}
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <label class="form-label fw-medium mb-0" style="font-size:.82rem;">Sidebar Compact</label>
                                        <p class="text-muted mb-0" style="font-size:.72rem;">Minimal sidebar hanya ikon</p>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="sidebarMini" {{ ($settings['sidebar_mini'] ?? 'false') === 'true' ? 'checked' : '' }} onchange="toggleSetting('sidebar_mini', this.checked)">
                                    </div>
                                </div>
                            </div>

                            {{-- Suara Notifikasi --}}
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <label class="form-label fw-medium mb-0" style="font-size:.82rem;">Suara Notifikasi</label>
                                        <p class="text-muted mb-0" style="font-size:.72rem;">Aktifkan suara saat ada notifikasi baru</p>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="soundToggle" {{ ($settings['notifikasi_suara'] ?? 'true') === 'true' ? 'checked' : '' }} onchange="toggleSetting('notifikasi_suara', this.checked)">
                                    </div>
                                </div>
                            </div>

                            {{-- Simpan Button --}}
                            <div class="col-12">
                                <button type="button" class="btn btn-primary px-4" onclick="simpanTampilan()" style="border-radius:10px;font-size:.82rem;">
                                    <i class="bi bi-check-lg me-1"></i> Simpan Pengaturan Tampilan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script>
const tampilanUrl = '{{ route($routePrefix . ".pengaturan.tampilan") }}';
const csrfToken = '{{ csrf_token() }}';

let tampilanData = {
    tema: '{{ $settings['tema'] ?? 'gelap' }}',
    ukuran_font: '{{ $settings['ukuran_font'] ?? 'normal' }}',
    sidebar_mini: '{{ $settings['sidebar_mini'] ?? 'false' }}',
    warna_aksen: '{{ $settings['warna_aksen'] ?? '#6366f1' }}',
    notifikasi_suara: '{{ $settings['notifikasi_suara'] ?? 'true' }}'
};

function pilihTema(el) {
    document.querySelectorAll('.tema-option').forEach(e => e.style.borderColor = '#e5e7eb');
    el.style.borderColor = '#6366f1';
    tampilanData.tema = el.dataset.value;
}

function pilihFont(el) {
    document.querySelectorAll('.font-option').forEach(e => e.style.borderColor = '#e5e7eb');
    el.style.borderColor = '#6366f1';
    tampilanData.ukuran_font = el.dataset.value;
}

function pilihWarna(el) {
    document.querySelectorAll('.color-option').forEach(e => { e.style.borderColor = 'transparent'; e.innerHTML = ''; });
    el.style.borderColor = '#1e293b';
    el.innerHTML = '<i class="bi bi-check" style="color:#fff;font-size:1rem;"></i>';
    tampilanData.warna_aksen = el.dataset.value;
}

function toggleSetting(key, checked) {
    tampilanData[key] = checked ? 'true' : 'false';
}

async function simpanTampilan() {
    try {
        const res = await fetch(tampilanUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
            body: JSON.stringify(tampilanData)
        });
        const data = await res.json();
        if (data.success) {
            Swal.fire({ icon: 'success', title: 'Berhasil!', text: data.pesan, confirmButtonColor: '#6366f1', timer: 2000, timerProgressBar: true, showConfirmButton: false });
        }
    } catch (err) {
        Swal.fire({ icon: 'error', title: 'Gagal', text: 'Terjadi kesalahan', confirmButtonColor: '#ef4444' });
    }
}
</script>
@endpush
