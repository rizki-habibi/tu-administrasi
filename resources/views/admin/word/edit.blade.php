@extends('layouts.admin')
@section('title', 'Edit: ' . $word->title)

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
        <h5 class="fw-bold mb-1"><i class="bi bi-pencil-square text-primary me-2"></i>Edit Dokumen</h5>
        <p class="text-muted mb-0" style="font-size:.82rem;">{{ $word->title }} <span id="autosaveStatus" class="ms-2 text-success" style="font-size:.72rem;"></span></p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.word.download', $word) }}" class="btn btn-outline-info"><i class="bi bi-download me-1"></i>Download .docx</a>
        <a href="{{ route('admin.word.show', $word) }}" class="btn btn-outline-primary"><i class="bi bi-eye me-1"></i>Preview</a>
        <a href="{{ route('admin.word.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
    </div>
</div>

<form action="{{ route('admin.word.update', $word) }}" method="POST" id="wordForm">
    @csrf @method('PUT')

    <div class="row g-4">
        <!-- Left Column: Editor -->
        <div class="col-lg-8">
            <!-- AI Generator -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header py-2 bg-white border-0 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0 small"><i class="bi bi-stars text-primary me-2"></i>AI Generator</h6>
                    <button type="button" class="btn btn-sm btn-link" data-bs-toggle="collapse" data-bs-target="#aiPanel">
                        <i class="bi bi-chevron-down"></i>
                    </button>
                </div>
                <div id="aiPanel" class="collapse">
                    <div class="card-body pt-0">
                        <div class="row g-2 mb-2">
                            <div class="col-md-4">
                                <select id="aiTemplate" class="form-select form-select-sm">
                                    <option value="">-- Auto Deteksi --</option>
                                    @foreach(App\Models\WordDocument::templates() as $key => $tpl)
                                        <option value="{{ $key }}">{{ $tpl['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-8">
                                <div class="input-group input-group-sm">
                                    <input type="text" id="aiPrompt" class="form-control" placeholder="Ketik instruksi AI...">
                                    <button type="button" id="btnAiGenerate" class="btn btn-primary"><i class="bi bi-stars me-1"></i>Generate</button>
                                </div>
                            </div>
                        </div>
                        <div id="aiLoading" class="text-center py-2 d-none">
                            <div class="spinner-border spinner-border-sm text-primary"></div>
                            <small class="ms-2 text-muted">Generating...</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Editor -->
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="btn-toolbar mb-2 border rounded p-2 bg-light">
                        <div class="btn-group btn-group-sm me-2">
                            <button type="button" class="btn btn-outline-secondary" onclick="execCmd('bold')" title="Bold"><i class="bi bi-type-bold"></i></button>
                            <button type="button" class="btn btn-outline-secondary" onclick="execCmd('italic')" title="Italic"><i class="bi bi-type-italic"></i></button>
                            <button type="button" class="btn btn-outline-secondary" onclick="execCmd('underline')" title="Underline"><i class="bi bi-type-underline"></i></button>
                            <button type="button" class="btn btn-outline-secondary" onclick="execCmd('strikeThrough')"><i class="bi bi-type-strikethrough"></i></button>
                        </div>
                        <div class="btn-group btn-group-sm me-2">
                            <button type="button" class="btn btn-outline-secondary" onclick="execCmd('justifyLeft')"><i class="bi bi-text-left"></i></button>
                            <button type="button" class="btn btn-outline-secondary" onclick="execCmd('justifyCenter')"><i class="bi bi-text-center"></i></button>
                            <button type="button" class="btn btn-outline-secondary" onclick="execCmd('justifyRight')"><i class="bi bi-text-right"></i></button>
                            <button type="button" class="btn btn-outline-secondary" onclick="execCmd('justifyFull')"><i class="bi bi-justify"></i></button>
                        </div>
                        <div class="btn-group btn-group-sm me-2">
                            <button type="button" class="btn btn-outline-secondary" onclick="execCmd('insertOrderedList')"><i class="bi bi-list-ol"></i></button>
                            <button type="button" class="btn btn-outline-secondary" onclick="execCmd('insertUnorderedList')"><i class="bi bi-list-ul"></i></button>
                        </div>
                        <div class="btn-group btn-group-sm me-2">
                            <select class="form-select form-select-sm" onchange="execCmdArg('formatBlock',this.value)" style="width:auto;">
                                <option value="p">Normal</option>
                                <option value="h1">H1</option>
                                <option value="h2">H2</option>
                                <option value="h3">H3</option>
                                <option value="h4">H4</option>
                            </select>
                        </div>
                        <div class="btn-group btn-group-sm me-2">
                            <select class="form-select form-select-sm" onchange="execCmdArg('fontSize',this.value)" style="width:auto;">
                                <option value="2">10pt</option>
                                <option value="3" selected>12pt</option>
                                <option value="4">14pt</option>
                                <option value="5">18pt</option>
                                <option value="6">24pt</option>
                            </select>
                        </div>
                        <div class="btn-group btn-group-sm">
                            <button type="button" class="btn btn-outline-secondary" onclick="insertTable()"><i class="bi bi-table"></i></button>
                            <button type="button" class="btn btn-outline-secondary" onclick="execCmd('insertHorizontalRule')"><i class="bi bi-hr"></i></button>
                            <button type="button" class="btn btn-outline-secondary" onclick="execCmd('undo')"><i class="bi bi-arrow-counterclockwise"></i></button>
                            <button type="button" class="btn btn-outline-secondary" onclick="execCmd('redo')"><i class="bi bi-arrow-clockwise"></i></button>
                        </div>
                    </div>

                    <div id="editor" contenteditable="true" class="border rounded p-4" style="min-height:500px;max-height:700px;overflow-y:auto;font-family:'Times New Roman',serif;font-size:12pt;line-height:1.6;background:#fff;">
                        {!! $word->content ?? '<p>Mulai menulis...</p>' !!}
                    </div>
                    <input type="hidden" name="content" id="contentInput">
                </div>
            </div>
        </div>

        <!-- Right Column: Properties -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="fw-bold mb-0"><i class="bi bi-gear me-2 text-primary"></i>Properti</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Judul <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="docTitle" class="form-control" value="{{ $word->title }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Kategori</label>
                        <select name="category" class="form-select">
                            @foreach($categories as $key => $label)
                                <option value="{{ $key }}" {{ $word->category==$key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Status</label>
                        <select name="status" class="form-select">
                            <option value="draft" {{ $word->status=='draft' ? 'selected' : '' }}>Draft</option>
                            <option value="final" {{ $word->status=='final' ? 'selected' : '' }}>Final</option>
                            <option value="archived" {{ $word->status=='archived' ? 'selected' : '' }}>Arsip</option>
                        </select>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_shared" value="1" id="isShared" {{ $word->is_shared ? 'checked' : '' }}>
                        <label class="form-check-label small" for="isShared">Bagikan ke staf</label>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <button type="submit" class="btn btn-primary w-100 mb-2" onclick="syncContent()">
                        <i class="bi bi-floppy me-1"></i>Simpan
                    </button>
                    <a href="{{ route('admin.word.download', $word) }}" class="btn btn-outline-info w-100 mb-2">
                        <i class="bi bi-file-earmark-arrow-down me-1"></i>Download .docx
                    </a>
                    <form action="{{ route('admin.word.destroy', $word) }}" method="POST" onsubmit="return false;" id="deleteForm">
                        @csrf @method('DELETE')
                        <button type="button" class="btn btn-outline-danger w-100 btn-delete">
                            <i class="bi bi-trash me-1"></i>Hapus Dokumen
                        </button>
                    </form>
                </div>
            </div>

            <!-- Info -->
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <small class="text-muted d-block"><i class="bi bi-person me-1"></i>Pembuat: {{ $word->user->name ?? '-' }}</small>
                    <small class="text-muted d-block"><i class="bi bi-calendar me-1"></i>Dibuat: {{ $word->created_at->format('d/m/Y H:i') }}</small>
                    <small class="text-muted d-block"><i class="bi bi-clock me-1"></i>Diubah: {{ $word->updated_at->format('d/m/Y H:i') }}</small>
                    @if($word->ai_prompt)
                        <small class="text-muted d-block mt-2"><i class="bi bi-stars me-1"></i>AI Prompt: {{ Str::limit($word->ai_prompt, 80) }}</small>
                    @endif
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
function execCmd(cmd) { document.execCommand(cmd, false, null); document.getElementById('editor').focus(); }
function execCmdArg(cmd, val) { document.execCommand(cmd, false, val); document.getElementById('editor').focus(); }
function insertTable() {
    const html = '<table style="width:100%;border-collapse:collapse;"><tr><th style="border:1px solid #000;padding:8px;">Header 1</th><th style="border:1px solid #000;padding:8px;">Header 2</th><th style="border:1px solid #000;padding:8px;">Header 3</th></tr><tr><td style="border:1px solid #000;padding:8px;">Data 1</td><td style="border:1px solid #000;padding:8px;">Data 2</td><td style="border:1px solid #000;padding:8px;">Data 3</td></tr></table><br>';
    document.execCommand('insertHTML', false, html);
}
function syncContent() {
    document.getElementById('contentInput').value = document.getElementById('editor').innerHTML;
}

// Auto-save every 30 seconds
let autosaveTimer;
document.getElementById('editor').addEventListener('input', function() {
    clearTimeout(autosaveTimer);
    autosaveTimer = setTimeout(autoSave, 30000);
});

function autoSave() {
    syncContent();
    fetch('{{ route("admin.word.autosave", $word) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            content: document.getElementById('contentInput').value,
            title: document.getElementById('docTitle').value
        })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            document.getElementById('autosaveStatus').innerHTML = '<i class="bi bi-check-circle"></i> Tersimpan ' + data.saved_at;
        }
    });
}

// Ctrl+S save
document.addEventListener('keydown', function(e) {
    if ((e.ctrlKey || e.metaKey) && e.key === 's') {
        e.preventDefault();
        syncContent();
        document.getElementById('wordForm').submit();
    }
});

// AI Generate
document.getElementById('btnAiGenerate').addEventListener('click', function() {
    const prompt = document.getElementById('aiPrompt').value.trim();
    if (!prompt) { Swal.fire('Oops', 'Masukkan prompt terlebih dahulu!', 'warning'); return; }

    const template = document.getElementById('aiTemplate').value;
    this.disabled = true;
    document.getElementById('aiLoading').classList.remove('d-none');

    fetch('{{ route("admin.word.ai-generate") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ prompt, template, document_id: {{ $word->id }} })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            document.getElementById('editor').innerHTML = data.content;
            Swal.fire({icon:'success', title:'Berhasil!', timer:1500, showConfirmButton:false});
        }
    })
    .catch(() => Swal.fire('Error', 'Gagal generate.', 'error'))
    .finally(() => {
        this.disabled = false;
        document.getElementById('aiLoading').classList.add('d-none');
    });
});

// Delete
document.querySelector('.btn-delete')?.addEventListener('click', function() {
    Swal.fire({
        title: 'Hapus Dokumen?', text: 'Tidak dapat dikembalikan!', icon: 'warning',
        showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonText: 'Batal', confirmButtonText: 'Ya, Hapus!'
    }).then(r => { if(r.isConfirmed) document.getElementById('deleteForm').submit(); });
});

// Form submit sync
document.getElementById('wordForm').addEventListener('submit', function() { syncContent(); });
</script>
@endpush
