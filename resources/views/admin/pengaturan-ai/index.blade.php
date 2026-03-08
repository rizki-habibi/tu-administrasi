@extends('peran.admin.app')
@section('judul', 'Konfigurasi AI')

@section('konten')
<div class="container-fluid">
    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1"><i class="bi bi-cpu me-2"></i>Konfigurasi AI</h4>
            <p class="text-muted mb-0" style="font-size:.85rem;">Kelola API key dan provider AI untuk sistem</p>
        </div>
        <button class="btn btn-primary rounded-3" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="bi bi-plus-lg me-1"></i> Tambah Provider
        </button>
    </div>

    {{-- Active Config Card --}}
    @if($active)
    <div class="card border-0 shadow-sm mb-4" style="border-radius:var(--card-radius);">
        <div class="card-body p-4">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="rounded-3 d-flex align-items-center justify-content-center" style="width:48px;height:48px;background:{{ $active->warna_tema ?? '#6366f1' }};">
                    <i class="bi {{ $active->ikon ?? 'bi-robot' }} text-white" style="font-size:1.4rem;"></i>
                </div>
                <div>
                    <h6 class="mb-0 fw-bold">Provider Aktif: {{ $active->nama_tampilan }}</h6>
                    <small class="text-muted">Model: {{ $active->model }} &bull; API Key: {{ $active->masked_api_key }}</small>
                </div>
                <span class="badge bg-success ms-auto">Aktif</span>
            </div>
            <div class="row g-3">
                <div class="col-sm-3">
                    <div class="bg-light rounded-3 p-3 text-center">
                        <div class="text-muted" style="font-size:.72rem;">Provider</div>
                        <div class="fw-bold text-capitalize">{{ $active->provider }}</div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="bg-light rounded-3 p-3 text-center">
                        <div class="text-muted" style="font-size:.72rem;">Model</div>
                        <div class="fw-bold">{{ $active->model }}</div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="bg-light rounded-3 p-3 text-center">
                        <div class="text-muted" style="font-size:.72rem;">Kapabilitas</div>
                        <div class="fw-bold">{{ count($active->kapabilitas ?? ['teks']) }}</div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="bg-light rounded-3 p-3 text-center">
                        <div class="text-muted" style="font-size:.72rem;">Terakhir Diperbarui</div>
                        <div class="fw-bold">{{ $active->updated_at->diffForHumans() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="alert alert-warning d-flex align-items-center gap-2 rounded-3 border-0 shadow-sm mb-4">
        <i class="bi bi-exclamation-triangle-fill fs-5"></i>
        <div>
            <strong>Belum ada konfigurasi AI aktif.</strong> Tambahkan API key untuk mengaktifkan fitur AI.
            <br><small>Fitur AI (SIATU-AI, Word AI, Chat AI, Ringkasan Dashboard) memerlukan konfigurasi provider aktif.</small>
        </div>
    </div>
    @endif

    {{-- All Configs --}}
    <div class="card border-0 shadow-sm" style="border-radius:var(--card-radius);">
        <div class="card-header bg-white border-0 px-4 pt-4 pb-0">
            <h6 class="fw-bold mb-0"><i class="bi bi-list-ul me-2"></i>Semua Konfigurasi</h6>
        </div>
        <div class="card-body p-4">
            @if($configs->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-cpu fs-1 d-block mb-2 opacity-50"></i>
                    Belum ada konfigurasi AI. Klik <strong>Tambah Provider</strong> untuk memulai.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="font-size:.78rem;">Provider</th>
                                <th style="font-size:.78rem;">Model</th>
                                <th style="font-size:.78rem;">API Key</th>
                                <th style="font-size:.78rem;">Status</th>
                                <th style="font-size:.78rem;">Diperbarui</th>
                                <th style="font-size:.78rem;" class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($configs as $config)
                            <tr>
                                <td>
                                    <div class="fw-semibold" style="font-size:.82rem;">{{ $config->nama_tampilan }}</div>
                                    <small class="text-muted text-capitalize">{{ $config->provider }}</small>
                                </td>
                                <td><code style="font-size:.78rem;">{{ $config->model }}</code></td>
                                <td><code style="font-size:.75rem;">{{ $config->masked_api_key }}</code></td>
                                <td>{!! $config->status_badge !!}</td>
                                <td style="font-size:.78rem;">{{ $config->updated_at->diffForHumans() }}</td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        @if(!$config->aktif)
                                        <form action="{{ route('admin.pengaturan-ai.activate', $config) }}" method="POST" class="d-inline">
                                            @csrf @method('PATCH')
                                            <button class="btn btn-outline-success rounded-start-2" title="Aktifkan"><i class="bi bi-check-circle"></i></button>
                                        </form>
                                        @endif
                                        <button class="btn btn-outline-primary" title="Edit" onclick="editConfig({{ $config->id }}, {{ Js::from($config->only(['provider','model','nama_tampilan','base_url','ikon','warna_tema','kapabilitas'])) }})"><i class="bi bi-pencil"></i></button>
                                        <button class="btn btn-outline-info" title="Test koneksi" onclick="testConfig({{ $config->id }}, '{{ $config->provider }}', '{{ $config->model }}')"><i class="bi bi-wifi"></i></button>
                                        <form action="{{ route('admin.pengaturan-ai.destroy', $config) }}" method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-outline-danger rounded-end-2" data-confirm="Hapus konfigurasi ini?"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Modal Tambah --}}
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('admin.pengaturan-ai.store') }}" method="POST" class="modal-content border-0 rounded-4 shadow">
            @csrf
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold"><i class="bi bi-plus-circle me-2"></i>Tambah Provider AI</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size:.82rem;">Provider <span class="text-danger">*</span></label>
                        <select name="provider" id="addProvider" class="form-select" required onchange="onProviderChange(this, 'add')">
                            <option value="">— Pilih Provider —</option>
                            @foreach($providers as $key => $p)
                            <option value="{{ $key }}">{{ $p['nama'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size:.82rem;">Nama Tampilan</label>
                        <input type="text" name="nama_tampilan" id="addNama" class="form-control" placeholder="Otomatis dari provider">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size:.82rem;">Model <span class="text-danger">*</span></label>
                        <select name="model" id="addModel" class="form-select" required>
                            <option value="">— Pilih provider dulu —</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size:.82rem;">API Key <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" name="api_key" id="addApiKey" class="form-control" required minlength="10" placeholder="Masukkan API key...">
                            <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('addApiKey', this)"><i class="bi bi-eye"></i></button>
                        </div>
                    </div>
                    <div class="col-12" id="addBaseUrlGroup" style="display:none;">
                        <label class="form-label fw-semibold" style="font-size:.82rem;">Base URL (Custom)</label>
                        <input type="url" name="base_url" id="addBaseUrl" class="form-control" placeholder="https://api.example.com/v1">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size:.82rem;">Ikon Widget</label>
                        <select name="ikon" id="addIkon" class="form-select">
                            <option value="bi-robot">🤖 Robot (default)</option>
                            <option value="bi-stars">✨ Bintang</option>
                            <option value="bi-lightning-charge-fill">⚡ Petir</option>
                            <option value="bi-cpu">🔲 CPU</option>
                            <option value="bi-chat-dots-fill">💬 Chat</option>
                            <option value="bi-magic">🪄 Magic</option>
                            <option value="bi-braces">{ } Kode</option>
                            <option value="bi-mortarboard-fill">🎓 Akademik</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size:.82rem;">Warna Tema</label>
                        <div class="d-flex align-items-center gap-2">
                            <input type="color" name="warna_tema" id="addWarnaTema" value="#6366f1" class="form-control form-control-color" style="width:50px;height:38px;">
                            <span id="addWarnaLabel" class="text-muted" style="font-size:.78rem;">#6366f1</span>
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold" style="font-size:.82rem;">Kapabilitas AI</label>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($kapabilitas as $key => $label)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="kapabilitas[]" value="{{ $key }}" id="addKap_{{ $key }}" {{ $key === 'teks' ? 'checked' : '' }}>
                                <label class="form-check-label" for="addKap_{{ $key }}" style="font-size:.82rem;">{{ $label }}</label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <button type="button" class="btn btn-outline-info btn-sm rounded-3" onclick="testFromModal('add')">
                        <i class="bi bi-wifi me-1"></i> Test Koneksi
                    </button>
                    <span id="addTestResult" class="ms-2" style="font-size:.82rem;"></span>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary rounded-3"><i class="bi bi-check-lg me-1"></i> Simpan & Aktifkan</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Edit --}}
<div class="modal fade" id="modalEdit" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form id="editForm" method="POST" class="modal-content border-0 rounded-4 shadow">
            @csrf @method('PUT')
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2"></i>Edit Konfigurasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size:.82rem;">Provider</label>
                        <select name="provider" id="editProvider" class="form-select" required onchange="onProviderChange(this, 'edit')">
                            @foreach($providers as $key => $p)
                            <option value="{{ $key }}">{{ $p['nama'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size:.82rem;">Nama Tampilan</label>
                        <input type="text" name="nama_tampilan" id="editNama" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size:.82rem;">Model</label>
                        <select name="model" id="editModel" class="form-select" required>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size:.82rem;">API Key <small class="text-muted">(kosongkan jika tidak diubah)</small></label>
                        <div class="input-group">
                            <input type="password" name="api_key" id="editApiKey" class="form-control" minlength="10" placeholder="••••••••">
                            <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('editApiKey', this)"><i class="bi bi-eye"></i></button>
                        </div>
                    </div>
                    <div class="col-12" id="editBaseUrlGroup" style="display:none;">
                        <label class="form-label fw-semibold" style="font-size:.82rem;">Base URL</label>
                        <input type="url" name="base_url" id="editBaseUrl" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size:.82rem;">Ikon Widget</label>
                        <select name="ikon" id="editIkon" class="form-select">
                            <option value="bi-robot">🤖 Robot (default)</option>
                            <option value="bi-stars">✨ Bintang</option>
                            <option value="bi-lightning-charge-fill">⚡ Petir</option>
                            <option value="bi-cpu">🔲 CPU</option>
                            <option value="bi-chat-dots-fill">💬 Chat</option>
                            <option value="bi-magic">🪄 Magic</option>
                            <option value="bi-braces">{ } Kode</option>
                            <option value="bi-mortarboard-fill">🎓 Akademik</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size:.82rem;">Warna Tema</label>
                        <div class="d-flex align-items-center gap-2">
                            <input type="color" name="warna_tema" id="editWarnaTema" value="#6366f1" class="form-control form-control-color" style="width:50px;height:38px;">
                            <span id="editWarnaLabel" class="text-muted" style="font-size:.78rem;">#6366f1</span>
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold" style="font-size:.82rem;">Kapabilitas AI</label>
                        <div class="d-flex flex-wrap gap-2" id="editKapContainer">
                            @foreach($kapabilitas as $key => $label)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="kapabilitas[]" value="{{ $key }}" id="editKap_{{ $key }}">
                                <label class="form-check-label" for="editKap_{{ $key }}" style="font-size:.82rem;">{{ $label }}</label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary rounded-3"><i class="bi bi-check-lg me-1"></i> Perbarui</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
const providerData = @json($providers);
const needsBaseUrl = ['custom', 'openrouter', 'deepseek', 'mistral', 'cohere', 'groq'];

function onProviderChange(select, prefix) {
    const provider = select.value;
    const modelSelect = document.getElementById(prefix + 'Model');
    const baseUrlGroup = document.getElementById(prefix + 'BaseUrlGroup');
    const customInput = modelSelect.closest('.col-md-6').querySelector('input[name="model"]');

    if (customInput) customInput.remove();
    modelSelect.innerHTML = '';
    modelSelect.style.display = '';

    if (provider && providerData[provider]) {
        const models = providerData[provider].models;
        if (models.length > 0) {
            models.forEach(m => {
                const opt = document.createElement('option');
                opt.value = m; opt.textContent = m;
                modelSelect.appendChild(opt);
            });
        } else {
            modelSelect.style.display = 'none';
            const input = document.createElement('input');
            input.type = 'text'; input.name = 'model'; input.className = 'form-control';
            input.placeholder = 'Nama model custom...'; input.required = true;
            modelSelect.closest('.col-md-6').appendChild(input);
        }

        baseUrlGroup.style.display = needsBaseUrl.includes(provider) ? '' : 'none';
    }
}

function togglePassword(id, btn) {
    const input = document.getElementById(id);
    const icon = btn.querySelector('i');
    if (input.type === 'password') { input.type = 'text'; icon.className = 'bi bi-eye-slash'; }
    else { input.type = 'password'; icon.className = 'bi bi-eye'; }
}

function editConfig(id, data) {
    document.getElementById('editForm').action = '{{ url("admin/pengaturan-ai") }}/' + id;
    document.getElementById('editProvider').value = data.provider;
    document.getElementById('editNama').value = data.nama_tampilan || '';
    onProviderChange(document.getElementById('editProvider'), 'edit');
    setTimeout(() => { document.getElementById('editModel').value = data.model; }, 100);
    document.getElementById('editBaseUrl').value = data.base_url || '';
    document.getElementById('editBaseUrlGroup').style.display = needsBaseUrl.includes(data.provider) ? '' : 'none';

    // Icon & color
    document.getElementById('editIkon').value = data.ikon || 'bi-robot';
    document.getElementById('editWarnaTema').value = data.warna_tema || '#6366f1';
    document.getElementById('editWarnaLabel').textContent = data.warna_tema || '#6366f1';

    // Capabilities
    document.querySelectorAll('#editKapContainer input[type="checkbox"]').forEach(cb => {
        cb.checked = (data.kapabilitas || ['teks']).includes(cb.value);
    });

    new bootstrap.Modal(document.getElementById('modalEdit')).show();
}

// Color label sync
['add', 'edit'].forEach(prefix => {
    const colorInput = document.getElementById(prefix + 'WarnaTema');
    const label = document.getElementById(prefix + 'WarnaLabel');
    if (colorInput && label) {
        colorInput.addEventListener('input', () => { label.textContent = colorInput.value; });
    }
});

function testFromModal(prefix) {
    const provider = document.getElementById(prefix + 'Provider').value;
    const apiKey = document.getElementById(prefix + 'ApiKey').value;
    const modelEl = document.getElementById(prefix + 'Model');
    const model = modelEl.style.display === 'none'
        ? modelEl.closest('.col-md-6').querySelector('input[name="model"]')?.value
        : modelEl.value;
    const baseUrl = document.getElementById(prefix + 'BaseUrl')?.value;
    const result = document.getElementById(prefix + 'TestResult');

    if (!provider || !apiKey || !model) {
        if (result) result.innerHTML = '<span class="text-danger">Lengkapi dulu provider, API key, dan model.</span>';
        return;
    }

    if (result) result.innerHTML = '<span class="text-info"><i class="bi bi-hourglass-split"></i> Testing...</span>';

    fetch('{{ route("admin.pengaturan-ai.test") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        },
        body: JSON.stringify({ provider, api_key: apiKey, model, base_url: baseUrl })
    })
    .then(r => r.json())
    .then(data => {
        if (result) result.innerHTML = data.success
            ? '<span class="text-success"><i class="bi bi-check-circle-fill"></i> ' + data.message + '</span>'
            : '<span class="text-danger"><i class="bi bi-x-circle-fill"></i> ' + data.message + '</span>';
    })
    .catch(() => {
        if (result) result.innerHTML = '<span class="text-danger">Gagal terhubung.</span>';
    });
}

function testConfig(id, provider, model) {
    Swal.fire({
        title: 'Test Koneksi',
        html: 'Gunakan tombol <strong>Test Koneksi</strong> di modal Edit untuk menguji API key.',
        icon: 'info',
        confirmButtonColor: '#6366f1',
    });
}
</script>
@endpush
