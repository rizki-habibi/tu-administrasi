@extends('peran.admin.app')
@section('judul', 'Buat Dokumen Word')

@section('konten')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
        <h5 class="fw-bold mb-1"><i class="bi bi-file-earmark-plus-fill text-primary me-2"></i>Buat Dokumen Baru</h5>
        <p class="text-muted mb-0" style="font-size:.82rem;">Pilih template, gunakan AI, atau mulai dari kosong</p>
    </div>
    <a href="{{ route('admin.word-ai.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
</div>

<form action="{{ route('admin.word-ai.store') }}" method="POST" id="wordForm">
    @csrf

    <div class="row g-4">
        <!-- Left Column: Editor -->
        <div class="col-lg-8">
            <!-- AI Generator Card -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-gradient text-white py-3" style="background:linear-gradient(135deg,#6366f1,#8b5cf6)!important;">
                    <h6 class="fw-bold mb-0"><i class="bi bi-stars me-2"></i>AI Generator — Buat Dokumen Otomatis</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Template AI</label>
                        <select id="aiTemplate" class="form-select form-select-sm">
                            <option value="">-- Deteksi Otomatis --</option>
                            @foreach($templates as $key => $tpl)
                                <option value="{{ $key }}" {{ $selectedTemplate == $key ? 'selected' : '' }}>{{ $tpl['nama'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Prompt / Instruksi AI</label>
                        <textarea id="aiPrompt" class="form-control" rows="3" placeholder="Contoh: Buatkan surat undangan rapat koordinasi untuk hari Senin...&#10;Contoh: Buat notulen rapat evaluasi kinerja staf bulan ini...&#10;Contoh: Buat laporan kehadiran siswa semester ganjil..."></textarea>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" id="btnAiGenerate" class="btn btn-primary">
                            <i class="bi bi-stars me-1"></i>Generate dengan AI
                        </button>
                        <button type="button" id="btnLoadTemplate" class="btn btn-outline-primary">
                            <i class="bi bi-file-earmark-text me-1"></i>Muat Template
                        </button>
                    </div>
                    <div id="aiLoading" class="text-center py-3 d-none">
                        <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                        <small class="ms-2 text-muted">Menggenerate dokumen...</small>
                    </div>
                </div>
            </div>

            <!-- Editor Card -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="fw-bold mb-0"><i class="bi bi-pencil-square me-2 text-primary"></i>Editor Dokumen</h6>
                </div>
                <div class="card-body">
                    <!-- Toolbar -->
                    <div class="btn-toolbar mb-2 border rounded p-2 bg-light flex-wrap gap-1" id="editorToolbar">
                        <div class="btn-group btn-group-sm me-1">
                            <button type="button" class="btn btn-outline-secondary" onclick="execCmd('bold')" title="Bold"><i class="bi bi-type-bold"></i></button>
                            <button type="button" class="btn btn-outline-secondary" onclick="execCmd('italic')" title="Italic"><i class="bi bi-type-italic"></i></button>
                            <button type="button" class="btn btn-outline-secondary" onclick="execCmd('underline')" title="Underline"><i class="bi bi-type-underline"></i></button>
                            <button type="button" class="btn btn-outline-secondary" onclick="execCmd('strikeThrough')" title="Strikethrough"><i class="bi bi-type-strikethrough"></i></button>
                        </div>
                        <div class="btn-group btn-group-sm me-1">
                            <button type="button" class="btn btn-outline-secondary" onclick="execCmd('justifyLeft')" title="Rata Kiri"><i class="bi bi-text-left"></i></button>
                            <button type="button" class="btn btn-outline-secondary" onclick="execCmd('justifyCenter')" title="Rata Tengah"><i class="bi bi-text-center"></i></button>
                            <button type="button" class="btn btn-outline-secondary" onclick="execCmd('justifyRight')" title="Rata Kanan"><i class="bi bi-text-right"></i></button>
                            <button type="button" class="btn btn-outline-secondary" onclick="execCmd('justifyFull')" title="Rata Semua"><i class="bi bi-justify"></i></button>
                        </div>
                        <div class="btn-group btn-group-sm me-1">
                            <button type="button" class="btn btn-outline-secondary" onclick="execCmd('insertOrderedList')" title="Ordered List"><i class="bi bi-list-ol"></i></button>
                            <button type="button" class="btn btn-outline-secondary" onclick="execCmd('insertUnorderedList')" title="Unordered List"><i class="bi bi-list-ul"></i></button>
                        </div>
                        <div class="btn-group btn-group-sm me-1">
                            <button type="button" class="btn btn-outline-secondary" onclick="execCmd('indent')" title="Indent"><i class="bi bi-text-indent-left"></i></button>
                            <button type="button" class="btn btn-outline-secondary" onclick="execCmd('outdent')" title="Outdent"><i class="bi bi-text-indent-right"></i></button>
                        </div>
                        <div class="btn-group btn-group-sm me-1">
                            <select class="form-select form-select-sm" onchange="execCmdArg('fontName', this.value)" style="width:auto;" title="Font">
                                <option value="Times New Roman">Times New Roman</option>
                                <option value="Arial">Arial</option>
                                <option value="Calibri">Calibri</option>
                                <option value="Georgia">Georgia</option>
                                <option value="Verdana">Verdana</option>
                            </select>
                        </div>
                        <div class="btn-group btn-group-sm me-1">
                            <select class="form-select form-select-sm" onchange="execCmdArg('formatBlock', this.value)" style="width:auto;">
                                <option value="p">Normal</option>
                                <option value="h1">Heading 1</option>
                                <option value="h2">Heading 2</option>
                                <option value="h3">Heading 3</option>
                                <option value="h4">Heading 4</option>
                            </select>
                        </div>
                        <div class="btn-group btn-group-sm me-1">
                            <select class="form-select form-select-sm" onchange="execCmdArg('fontSize', this.value)" style="width:auto;">
                                <option value="2">10pt</option>
                                <option value="3" selected>12pt</option>
                                <option value="4">14pt</option>
                                <option value="5">18pt</option>
                                <option value="6">24pt</option>
                            </select>
                        </div>
                        <div class="btn-group btn-group-sm me-1">
                            <label class="btn btn-outline-secondary position-relative" title="Warna Teks">
                                <i class="bi bi-palette"></i>
                                <input type="color" class="position-absolute opacity-0" style="width:1px;height:1px;top:0;left:0;" onchange="execCmdArg('foreColor', this.value)">
                            </label>
                            <label class="btn btn-outline-secondary position-relative" title="Warna Latar">
                                <i class="bi bi-paint-bucket"></i>
                                <input type="color" value="#ffff00" class="position-absolute opacity-0" style="width:1px;height:1px;top:0;left:0;" onchange="execCmdArg('hiliteColor', this.value)">
                            </label>
                        </div>
                        <div class="btn-group btn-group-sm me-1">
                            <button type="button" class="btn btn-outline-secondary" onclick="insertTable()" title="Insert Table"><i class="bi bi-table"></i></button>
                            <button type="button" class="btn btn-outline-secondary" onclick="execCmd('insertHorizontalRule')" title="Horizontal Line"><i class="bi bi-hr"></i></button>
                        </div>
                        <div class="btn-group btn-group-sm me-1">
                            <button type="button" class="btn btn-outline-info" onclick="insertLogo()" title="Sisipkan Logo/Gambar"><i class="bi bi-image"></i></button>
                            <button type="button" class="btn btn-outline-warning" onclick="insertStamp()" title="Sisipkan Stempel"><i class="bi bi-patch-check-fill"></i></button>
                        </div>
                        <div class="btn-group btn-group-sm">
                            <button type="button" class="btn btn-outline-secondary" onclick="execCmd('undo')" title="Undo"><i class="bi bi-arrow-counterclockwise"></i></button>
                            <button type="button" class="btn btn-outline-secondary" onclick="execCmd('redo')" title="Redo"><i class="bi bi-arrow-clockwise"></i></button>
                        </div>
                    </div>
                    <input type="file" id="imageUpload" accept="image/*" class="d-none">
                    <input type="file" id="stampUpload" accept="image/*" class="d-none">

                    <!-- Content Editable Area -->
                    <div id="editor" contenteditable="true" class="border rounded p-4" style="min-height:500px;max-height:700px;overflow-y:auto;font-family:'Times New Roman',serif;font-size:12pt;line-height:1.6;background:#fff;">
                        <p>Mulai menulis dokumen Anda di sini...</p>
                    </div>
                    <input type="hidden" name="konten" id="contentInput">
                </div>
            </div>
        </div>

        <!-- Right Column: Properties -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="fw-bold mb-0"><i class="bi bi-gear me-2 text-primary"></i>Properti Dokumen</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Judul Dokumen <span class="text-danger">*</span></label>
                        <input type="text" name="judul" class="form-control" value="{{ old('judul') }}" required placeholder="Masukkan judul dokumen...">
                        @error('judul')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Kategori <span class="text-danger">*</span></label>
                        <select name="kategori" class="form-select" required>
                            @foreach($categories as $key => $label)
                                <option value="{{ $key }}" {{ old('kategori','umum')==$key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Status</label>
                        <select name="status" class="form-select">
                            <option value="draft" selected>Draf</option>
                            <option value="final">Final</option>
                            <option value="archived">Arsip</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="dibagikan" value="1" id="isShared">
                            <label class="form-check-label small" for="isShared">Bagikan ke semua staf</label>
                        </div>
                    </div>
                    <input type="hidden" name="templat" value="{{ $selectedTemplate }}">
                </div>
            </div>

            <!-- Actions -->
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <button type="submit" class="btn btn-primary w-100 mb-2" onclick="syncContent()">
                        <i class="bi bi-floppy me-1"></i>Simpan Dokumen
                    </button>
                    <button type="button" class="btn btn-outline-success w-100 mb-2" onclick="saveAndDownload()">
                        <i class="bi bi-file-earmark-arrow-down me-1"></i>Simpan & Download .docx
                    </button>
                    <a href="{{ route('admin.word-ai.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-x-circle me-1"></i>Batal
                    </a>
                </div>
            </div>

            <!-- Help -->
            <div class="card border-0 shadow-sm mt-3">
                <div class="card-body">
                    <h6 class="fw-bold small"><i class="bi bi-lightbulb text-warning me-2"></i>Tips AI Generator</h6>
                    <ul class="small text-muted mb-0 ps-3" style="font-size:.78rem;">
                        <li>Ketik "surat undangan rapat" untuk generate surat otomatis</li>
                        <li>Ketik "notulen rapat evaluasi" untuk notulen rapat</li>
                        <li>Ketik "laporan kehadiran bulan ini" untuk laporan</li>
                        <li>Ketik "SK penetapan panitia" untuk SK Kepsek</li>
                        <li>Ketik "proposal kegiatan OSIS" untuk proposal</li>
                        <li>Pilih template untuk format yang lebih spesifik</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
// Editor functions
function execCmd(cmd) {
    document.execCommand(cmd, false, null);
    document.getElementById('editor').focus();
}
function execCmdArg(cmd, val) {
    document.execCommand(cmd, false, val);
    document.getElementById('editor').focus();
}
function insertTable() {
    const html = '<table style="width:100%;border-collapse:collapse;"><tr><th style="border:1px solid #000;padding:8px;">Header 1</th><th style="border:1px solid #000;padding:8px;">Header 2</th><th style="border:1px solid #000;padding:8px;">Header 3</th></tr><tr><td style="border:1px solid #000;padding:8px;">Data 1</td><td style="border:1px solid #000;padding:8px;">Data 2</td><td style="border:1px solid #000;padding:8px;">Data 3</td></tr></table><br>';
    document.execCommand('insertHTML', false, html);
}

function insertLogo() {
    document.getElementById('imageUpload').click();
}
function insertStamp() {
    document.getElementById('stampUpload').click();
}
function handleImageInsert(file, isStamp) {
    if (!file || !file.type.startsWith('image/')) return;
    if (file.size > 2 * 1024 * 1024) { Swal.fire('Error', 'Ukuran gambar maksimal 2MB', 'error'); return; }
    const reader = new FileReader();
    reader.onload = function(e) {
        const maxW = isStamp ? 150 : 200;
        const style = isStamp
            ? 'max-width:'+maxW+'px;opacity:0.85;display:block;margin:10px auto;'
            : 'max-width:'+maxW+'px;display:block;margin:10px auto;';
        const html = '<img src="'+e.target.result+'" style="'+style+'" alt="'+(isStamp?'Stempel':'Logo')+'"><br>';
        document.getElementById('editor').focus();
        document.execCommand('insertHTML', false, html);
    };
    reader.readAsDataURL(file);
}
document.getElementById('imageUpload').addEventListener('change', function() {
    handleImageInsert(this.files[0], false); this.value = '';
});
document.getElementById('stampUpload').addEventListener('change', function() {
    handleImageInsert(this.files[0], true); this.value = '';
});

function syncContent() {
    document.getElementById('contentInput').value = document.getElementById('editor').innerHTML;
}

function saveAndDownload() {
    syncContent();
    const form = document.getElementById('wordForm');
    const action = form.action;
    form.submit();
    // After save, will redirect to edit page where download is available
}

// AI Generate
document.getElementById('btnAiGenerate').addEventListener('click', function() {
    const prompt = document.getElementById('aiPrompt').value.trim();
    if (!prompt) {
        Swal.fire('Oops', 'Masukkan prompt/instruksi terlebih dahulu!', 'warning');
        return;
    }

    const template = document.getElementById('aiTemplate').value;
    const loading = document.getElementById('aiLoading');
    const btn = this;

    btn.disabled = true;
    loading.classList.remove('d-none');

    fetch('{{ route("admin.word-ai.ai-generate") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ prompt, templat: template })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            document.getElementById('editor').innerHTML = data.konten;
            Swal.fire({icon:'success', title:'Berhasil!', text: data.sumber === 'gemini-ai' ? 'Dokumen digenerate oleh Gemini AI!' : 'Dokumen digenerate dari template lokal.', timer:2500, showConfirmButton:false});
            // Auto-fill title from prompt if empty
            const titleInput = document.querySelector('input[name="judul"]');
            if (!titleInput.value) {
                titleInput.value = prompt.substring(0, 100);
            }
        }
    })
    .catch(e => Swal.fire('Error', 'Gagal generate dokumen.', 'error'))
    .finally(() => {
        btn.disabled = false;
        loading.classList.add('d-none');
    });
});

// Load Template
document.getElementById('btnLoadTemplate').addEventListener('click', function() {
    const template = document.getElementById('aiTemplate').value;
    if (!template || template === 'kosong') {
        document.getElementById('editor').innerHTML = '<p>Mulai menulis dokumen Anda di sini...</p>';
        return;
    }

    fetch('{{ route("admin.word-ai.template") }}?templat=' + template, {
        headers: { 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            document.getElementById('editor').innerHTML = data.konten;
            Swal.fire({icon:'success', title:'Template Dimuat!', timer:1500, showConfirmButton:false});
        }
    });
});

// Sync content before form submit
document.getElementById('wordForm').addEventListener('submit', function() {
    syncContent();
});
</script>
@endpush
