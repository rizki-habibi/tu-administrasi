@extends('peran.kepala-sekolah.app')

@section('judul', $panduan->judul)

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/github-markdown-css@5/github-markdown-light.min.css">
<style>
    .markdown-body { font-family: 'Poppins', -apple-system, BlinkMacSystemFont, sans-serif; font-size: .88rem; line-height: 1.7; max-width: 100%; padding: 28px 32px; }
    .markdown-body h1 { font-size: 1.6rem; font-weight: 700; color: #1e293b; padding-bottom: 10px; border-bottom: 2px solid #e2e8f0; }
    .markdown-body h2 { font-size: 1.25rem; font-weight: 600; color: #0f766e; margin-top: 2rem; padding-bottom: 8px; border-bottom: 1px solid #e2e8f0; }
    .markdown-body h3 { font-size: 1.05rem; font-weight: 600; color: #059669; margin-top: 1.5rem; }
    .markdown-body table { font-size: .82rem; border-collapse: collapse; width: 100%; margin: 12px 0; }
    .markdown-body table th { background: #f0fdf4; font-weight: 600; color: #475569; padding: 10px 14px; text-align: left; border: 1px solid #e2e8f0; }
    .markdown-body table td { padding: 8px 14px; border: 1px solid #e2e8f0; }
    .markdown-body table tr:nth-child(even) { background: #f8fafc; }
    .markdown-body code { background: #f1f5f9; color: #059669; padding: 2px 6px; border-radius: 4px; font-size: .82rem; }
    .markdown-body pre { background: #1e293b; color: #e2e8f0; padding: 16px; border-radius: 10px; overflow-x: auto; }
    .markdown-body pre code { background: transparent; color: inherit; padding: 0; }
    .markdown-body blockquote { border-left: 4px solid #10b981; background: #f0fdf4; padding: 12px 16px; border-radius: 0 8px 8px 0; color: #065f46; }
    .markdown-body hr { border-color: #e2e8f0; margin: 2rem 0; }
    .markdown-body ol, .markdown-body ul { padding-left: 1.5rem; }
    .markdown-body li { margin-bottom: 4px; }
    .markdown-body a { color: #059669; text-decoration: none; }
    .markdown-body a:hover { text-decoration: underline; }
    .markdown-body strong { color: #1e293b; }
    .markdown-body img { max-width: 100%; border-radius: 10px; }

    .panduan-header { background: linear-gradient(135deg, #065f46 0%, #10b981 100%); color: #fff; border-radius: 14px; padding: 28px 32px; margin-bottom: 24px; }
    .panduan-header h1 { margin: 0; font-size: 1.4rem; font-weight: 700; }
    .panduan-header p { margin: 6px 0 0; opacity: .85; font-size: .85rem; }
    .panduan-header .logo-img { width: 48px; height: 48px; border-radius: 12px; object-fit: cover; border: 2px solid rgba(255,255,255,.25); margin-right: 14px; }
    .meta-info { font-size: .75rem; color: rgba(255,255,255,.7); display: flex; gap: 14px; flex-wrap: wrap; margin-top: 10px; padding-top: 10px; border-top: 1px solid rgba(255,255,255,.15); }
    .meta-info span i { margin-right: 4px; }

    .toc-sidebar { position: sticky; top: 80px; max-height: calc(100vh - 100px); overflow-y: auto; padding-right: 8px; }
    .toc-sidebar::-webkit-scrollbar { width: 3px; }
    .toc-sidebar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
    .toc-link { display: block; padding: 5px 12px; font-size: .78rem; color: #64748b; text-decoration: none; border-left: 2px solid transparent; transition: all .2s; }
    .toc-link:hover, .toc-link.active { color: #059669; border-left-color: #10b981; background: #f0fdf4; }
    .toc-link.toc-h3 { padding-left: 24px; font-size: .74rem; }

    .search-panduan { border: 1px solid #e2e8f0; border-radius: 10px; padding: 9px 14px; font-size: .82rem; width: 100%; }
    .search-panduan:focus { outline: none; border-color: #10b981; box-shadow: 0 0 0 3px rgba(16,185,129,.12); }

    .doc-nav-list a { display: block; padding: 6px 12px; font-size: .76rem; color: #64748b; text-decoration: none; border-radius: 6px; transition: all .15s; }
    .doc-nav-list a:hover { background: #f0fdf4; color: #059669; }
    .doc-nav-list a.current { background: #10b981; color: #fff; font-weight: 600; }

    .btn-scroll-top { position: fixed; bottom: 30px; right: 30px; width: 44px; height: 44px; border-radius: 50%; background: #10b981; color: #fff; border: none; box-shadow: 0 4px 14px rgba(16,185,129,.3); cursor: pointer; display: none; align-items: center; justify-content: center; font-size: 1.1rem; z-index: 1000; transition: all .2s; }
    .btn-scroll-top:hover { background: #059669; transform: translateY(-2px); }
    .btn-scroll-top.show { display: flex; }

    .header-btn { background:rgba(255,255,255,.2);color:#fff;border:1px solid rgba(255,255,255,.3);font-size:.78rem;padding:5px 12px;border-radius:6px;text-decoration:none;display:inline-flex;align-items:center;gap:4px;transition:all .15s; }
    .header-btn:hover { background:rgba(255,255,255,.35);color:#fff; }

    .action-dropdown .dropdown-menu { font-size: .8rem; border: 1px solid #e2e8f0; box-shadow: 0 8px 24px rgba(0,0,0,.1); border-radius: 10px; padding: 6px; min-width: 220px; }
    .action-dropdown .dropdown-item { border-radius: 6px; padding: 8px 12px; font-size: .8rem; display: flex; align-items: center; gap: 8px; }
    .action-dropdown .dropdown-item:hover { background: #f0fdf4; color: #059669; }
    .action-dropdown .dropdown-item i { width: 18px; text-align: center; }
    .action-dropdown .dropdown-divider { margin: 4px 0; }

    .print-cover { display: none; }

    @media print {
        .panduan-header, .toc-sidebar, .search-panduan, .btn, .btn-scroll-top,
        .doc-nav-list, .action-dropdown, .header-btn, .no-print, nav, .sidebar,
        .navbar, .main-sidebar, #sidebar, .topbar, .flash-msg { display: none !important; }

        .print-cover {
            display: flex !important; flex-direction: column; align-items: center;
            justify-content: center; text-align: center; height: 100vh;
            page-break-after: always; position: relative;
        }
        .print-cover .cover-border { position: absolute; inset: 8mm; border: 3px solid #065f46; border-radius: 6px; }
        .print-cover .cover-border::before { content: ''; position: absolute; inset: 4px; border: 1px solid #10b981; border-radius: 4px; }
        .print-cover .cover-ornament { width: 100px; height: 3px; background: linear-gradient(90deg, transparent, #10b981, transparent); margin: 0 auto; }
        .print-cover .cover-institution { font-size: 10pt; color: #64748b; text-transform: uppercase; letter-spacing: 3px; margin-top: 10px; }
        .print-cover .cover-school { font-size: 13pt; font-weight: 700; color: #065f46; margin-bottom: 30px; }
        .print-cover .cover-divider { width: 70px; height: 3px; background: #10b981; margin: 0 auto 24px; border-radius: 2px; }
        .print-cover .cover-title { font-size: 20pt; font-weight: 800; color: #1e293b; line-height: 1.3; padding: 0 30mm; margin-bottom: 10px; }
        .print-cover .cover-subtitle { font-size: 10pt; color: #64748b; padding: 0 40mm; margin-bottom: 30px; }
        .print-cover .cover-version { display: inline-block; background: #f0fdf4; color: #065f46; padding: 4px 16px; border-radius: 16px; font-size: 9pt; font-weight: 600; margin-bottom: 40px; }
        .print-cover .cover-meta-table td { padding: 4px 12px; color: #475569; }
        .print-cover .cover-meta-table td:first-child { font-weight: 600; text-align: right; }

        .col-lg-3 { display: none !important; }
        .col-lg-9 { width: 100% !important; max-width: 100% !important; flex: 0 0 100% !important; }
        .card { border: none !important; box-shadow: none !important; }
        .markdown-body { padding: 0; font-size: 10pt; }
        .markdown-body h1 { font-size: 14pt; }
        .markdown-body h2 { font-size: 12pt; color: #065f46; }
        .markdown-body h3 { font-size: 11pt; }
        .markdown-body table { font-size: 8.5pt; }
        .markdown-body pre { background: #f8f8f8; color: #333; border: 1px solid #ddd; }

        .print-footer { display: block !important; text-align: center; font-size: 8pt; color: #94a3b8; margin-top: 30px; padding-top: 10px; border-top: 1px solid #e2e8f0; }
    }

    .print-footer { display: none; }
</style>
@endpush

@section('konten')
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show flash-msg" role="alert" style="font-size:.82rem;border-radius:10px;">
    <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" style="font-size:.7rem;"></button>
</div>
@endif
@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show flash-msg" role="alert" style="font-size:.82rem;border-radius:10px;">
    <i class="bi bi-exclamation-triangle me-1"></i> {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" style="font-size:.7rem;"></button>
</div>
@endif

<div class="print-cover">
    <div class="cover-border"></div>
    <div class="cover-ornament" style="position:absolute;top:14mm;left:50%;transform:translateX(-50%);"></div>
    <div class="cover-ornament" style="position:absolute;bottom:14mm;left:50%;transform:translateX(-50%);"></div>
    <div style="width:60px;height:60px;border-radius:14px;background:linear-gradient(135deg,#065f46,#10b981);display:flex;align-items:center;justify-content:center;margin-bottom:16px;">
        <span style="font-size:28px;color:#fff;">&#128214;</span>
    </div>
    <div class="cover-institution">Sistem SIMPEG-SMART</div>
    <div class="cover-school">SMA Negeri 2 Jember</div>
    <div class="cover-divider"></div>
    <div class="cover-title">{{ $panduan->judul }}</div>
    <div class="cover-subtitle">{{ $panduan->deskripsi }}</div>
    @if($panduan->versi)<div class="cover-version">{{ $panduan->versi }}</div>@endif
    <table class="cover-meta-table">
        <tr><td>Kategori</td><td>: {{ ucfirst($panduan->kategori) }}</td></tr>
        <tr><td>Penulis</td><td>: {{ $panduan->pembuat->nama ?? 'Administrator' }}</td></tr>
        <tr><td>Tanggal</td><td>: {{ $panduan->created_at->translatedFormat('d F Y') }}</td></tr>
        @if($panduan->updated_at->ne($panduan->created_at))
        <tr><td>Diperbarui</td><td>: {{ $panduan->updated_at->translatedFormat('d F Y') }}</td></tr>
        @endif
    </table>
</div>

<div class="panduan-header no-print">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
        <div class="d-flex align-items-start">
            @if($panduan->logo)
                <img src="{{ $panduan->logo_url }}" alt="{{ $panduan->judul }}" class="logo-img">
            @endif
            <div>
                <h1><i class="bi {{ $panduan->ikon }} me-2"></i>{{ $panduan->judul }}</h1>
                <p>{{ $panduan->deskripsi }}
                    @if($panduan->versi)<span class="badge bg-light text-dark ms-2" style="font-size:.7rem;">{{ $panduan->versi }}</span>@endif
                </p>
                <div class="meta-info">
                    <span><i class="bi bi-calendar3"></i>{{ $panduan->created_at->translatedFormat('d M Y') }}</span>
                    <span><i class="bi bi-tag"></i>{{ ucfirst($panduan->kategori) }}</span>
                </div>
            </div>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('kepala-sekolah.panduan.index') }}" class="header-btn">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            <div class="dropdown action-dropdown">
                <button class="header-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-three-dots-vertical"></i> Aksi
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><button class="dropdown-item" onclick="window.print()"><i class="bi bi-printer text-primary"></i> Cetak dengan Sampul</button></li>
                    <li><button class="dropdown-item" id="btnDownloadPdf"><i class="bi bi-file-earmark-pdf text-danger"></i> Download PDF</button></li>
                    <li><a class="dropdown-item" href="{{ route('kepala-sekolah.panduan.download', $panduan) }}"><i class="bi bi-filetype-html text-warning"></i> Download HTML</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('kepala-sekolah.panduan.upload-drive', $panduan) }}" method="POST" id="formGDrive">
                            @csrf
                            <button class="dropdown-item" type="submit" id="btnGDrive"><i class="bi bi-google text-success"></i> Simpan ke Google Drive</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-3 d-none d-lg-block">
        <div class="card mb-3">
            <div class="card-body p-3">
                <h6 class="fw-bold mb-3" style="font-size:.82rem; color:#475569;"><i class="bi bi-list-ul me-1"></i> Daftar Isi</h6>
                <input type="text" class="search-panduan mb-3" placeholder="Cari di panduan..." id="searchPanduan">
                <div class="toc-sidebar" id="tocContainer"></div>
            </div>
        </div>
        <div class="card">
            <div class="card-body p-3">
                <h6 class="fw-bold mb-2" style="font-size:.78rem; color:#475569;"><i class="bi bi-folder2-open me-1"></i> Dokumen Lainnya</h6>
                <div class="doc-nav-list">
                    @foreach($dokumenList as $d)
                        <a href="{{ route('kepala-sekolah.panduan.show', $d) }}" class="{{ $d->id === $panduan->id ? 'current' : '' }}">
                            <i class="bi {{ $d->ikon }} me-1"></i> {{ $d->judul }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-9">
        <div class="card">
            <div class="card-body p-0">
                <div class="markdown-body" id="markdownContent">
                    {!! \Illuminate\Support\Str::markdown($panduan->konten ?? '') !!}
                </div>
            </div>
        </div>
        <div class="print-footer">
            Dokumen ini dicetak dari Sistem SIMPEG-SMART &mdash; SMA Negeri 2 Jember &mdash; {{ now()->translatedFormat('d F Y') }}
        </div>
    </div>
</div>

<button class="btn-scroll-top" id="btnScrollTop" title="Kembali ke atas"><i class="bi bi-arrow-up"></i></button>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.2/html2pdf.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const content = document.getElementById('markdownContent');
    const tocContainer = document.getElementById('tocContainer');
    const headings = content.querySelectorAll('h2, h3');
    let tocHTML = '';
    headings.forEach((h, i) => {
        const id = 'section-' + i;
        h.id = id;
        tocHTML += '<a href="#' + id + '" class="toc-link' + (h.tagName === 'H3' ? ' toc-h3' : '') + '">' + h.textContent + '</a>';
    });
    tocContainer.innerHTML = tocHTML;

    const tocLinks = document.querySelectorAll('.toc-link');
    window.addEventListener('scroll', () => {
        let current = '';
        headings.forEach(h => { if (window.scrollY >= h.offsetTop - 120) current = h.id; });
        tocLinks.forEach(link => link.classList.toggle('active', link.getAttribute('href') === '#' + current));
        document.getElementById('btnScrollTop').classList.toggle('show', window.scrollY > 300);
    });

    document.getElementById('searchPanduan').addEventListener('input', function() {
        const query = this.value.toLowerCase();
        tocLinks.forEach(link => { link.style.display = link.textContent.toLowerCase().includes(query) || query === '' ? '' : 'none'; });
    });

    tocLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    });

    document.getElementById('btnScrollTop').addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));

    document.getElementById('btnDownloadPdf').addEventListener('click', function() {
        const btn = this;
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-hourglass-split text-danger"></i> Generating PDF...';
        btn.disabled = true;

        const coverHTML = document.querySelector('.print-cover').cloneNode(true);
        coverHTML.style.display = 'flex';
        coverHTML.style.flexDirection = 'column';
        coverHTML.style.alignItems = 'center';
        coverHTML.style.justifyContent = 'center';
        coverHTML.style.textAlign = 'center';
        coverHTML.style.height = '297mm';
        coverHTML.style.width = '210mm';
        coverHTML.style.position = 'relative';
        coverHTML.style.padding = '20mm';
        coverHTML.style.boxSizing = 'border-box';

        const coverBorder = coverHTML.querySelector('.cover-border');
        if (coverBorder) {
            coverBorder.style.position = 'absolute';
            coverBorder.style.top = '8mm';
            coverBorder.style.right = '8mm';
            coverBorder.style.bottom = '8mm';
            coverBorder.style.left = '8mm';
            coverBorder.style.border = '3px solid #065f46';
            coverBorder.style.borderRadius = '6px';
        }

        const contentClone = document.getElementById('markdownContent').cloneNode(true);
        const wrapper = document.createElement('div');
        wrapper.appendChild(coverHTML);
        const contentWrapper = document.createElement('div');
        contentWrapper.style.padding = '0';
        contentWrapper.appendChild(contentClone);
        wrapper.appendChild(contentWrapper);

        const footer = document.createElement('div');
        footer.style.cssText = 'text-align:center;font-size:8pt;color:#94a3b8;margin-top:30px;padding-top:10px;border-top:1px solid #e2e8f0;';
        footer.textContent = 'Dokumen ini digenerate dari Sistem SIMPEG-SMART - SMA Negeri 2 Jember';
        wrapper.appendChild(footer);

        const opt = {
            margin: [10, 12, 10, 12],
            filename: @json(\Illuminate\Support\Str::slug($panduan->judul)) + '.pdf',
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2, useCORS: true, letterRendering: true },
            jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' },
            pagebreak: { mode: ['avoid-all', 'css', 'legacy'] }
        };

        html2pdf().set(opt).from(wrapper).save().then(() => {
            btn.innerHTML = originalText;
            btn.disabled = false;
        }).catch(() => {
            btn.innerHTML = originalText;
            btn.disabled = false;
            alert('Gagal membuat PDF. Silakan coba lagi.');
        });
    });

    document.getElementById('formGDrive').addEventListener('submit', function(e) {
        if (!confirm('Upload dokumen ini ke Google Drive?')) {
            e.preventDefault();
            return;
        }
        const btn = document.getElementById('btnGDrive');
        btn.innerHTML = '<i class="bi bi-hourglass-split text-success"></i> Mengupload...';
        btn.disabled = true;
    });
});
</script>
@endpush
