@extends('layouts.admin')

@section('title', 'Panduan Penggunaan')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/github-markdown-css@5/github-markdown-light.min.css">
<style>
    .markdown-body {
        font-family: 'Poppins', -apple-system, BlinkMacSystemFont, sans-serif;
        font-size: .88rem;
        line-height: 1.7;
        max-width: 100%;
        padding: 28px 32px;
    }
    .markdown-body h1 { font-size: 1.6rem; font-weight: 700; color: #1e293b; padding-bottom: 10px; border-bottom: 2px solid #e2e8f0; }
    .markdown-body h2 { font-size: 1.25rem; font-weight: 600; color: #312e81; margin-top: 2rem; padding-bottom: 8px; border-bottom: 1px solid #e2e8f0; }
    .markdown-body h3 { font-size: 1.05rem; font-weight: 600; color: #4338ca; margin-top: 1.5rem; }
    .markdown-body table { font-size: .82rem; border-collapse: collapse; width: 100%; margin: 12px 0; }
    .markdown-body table th { background: #f0f2f8; font-weight: 600; color: #475569; padding: 10px 14px; text-align: left; border: 1px solid #e2e8f0; }
    .markdown-body table td { padding: 8px 14px; border: 1px solid #e2e8f0; }
    .markdown-body table tr:nth-child(even) { background: #f8fafc; }
    .markdown-body code { background: #f1f5f9; color: #6366f1; padding: 2px 6px; border-radius: 4px; font-size: .82rem; }
    .markdown-body pre { background: #1e293b; color: #e2e8f0; padding: 16px; border-radius: 10px; overflow-x: auto; }
    .markdown-body pre code { background: transparent; color: inherit; padding: 0; }
    .markdown-body blockquote { border-left: 4px solid #6366f1; background: #eef2ff; padding: 12px 16px; border-radius: 0 8px 8px 0; color: #4338ca; }
    .markdown-body hr { border-color: #e2e8f0; margin: 2rem 0; }
    .markdown-body ol, .markdown-body ul { padding-left: 1.5rem; }
    .markdown-body li { margin-bottom: 4px; }
    .markdown-body a { color: #6366f1; text-decoration: none; }
    .markdown-body a:hover { text-decoration: underline; }
    .markdown-body strong { color: #1e293b; }

    .panduan-header { background: linear-gradient(135deg, #312e81 0%, #6366f1 100%); color: #fff; border-radius: 14px; padding: 28px 32px; margin-bottom: 24px; }
    .panduan-header h1 { margin: 0; font-size: 1.4rem; font-weight: 700; }
    .panduan-header p { margin: 6px 0 0; opacity: .85; font-size: .85rem; }

    .toc-sidebar { position: sticky; top: 80px; max-height: calc(100vh - 100px); overflow-y: auto; padding-right: 8px; }
    .toc-sidebar::-webkit-scrollbar { width: 3px; }
    .toc-sidebar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
    .toc-link { display: block; padding: 5px 12px; font-size: .78rem; color: #64748b; text-decoration: none; border-left: 2px solid transparent; transition: all .2s; }
    .toc-link:hover, .toc-link.active { color: #6366f1; border-left-color: #6366f1; background: #eef2ff; }
    .toc-link.toc-h3 { padding-left: 24px; font-size: .74rem; }

    .search-panduan { border: 1px solid #e2e8f0; border-radius: 10px; padding: 9px 14px; font-size: .82rem; width: 100%; }
    .search-panduan:focus { outline: none; border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,.12); }

    @media print {
        .panduan-header, .toc-sidebar, .search-panduan, .btn { display: none !important; }
        .markdown-body { padding: 0; }
    }
</style>
@endpush

@section('content')
<div class="panduan-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h1><i class="bi bi-book me-2"></i>Panduan Penggunaan</h1>
            <p>Dokumentasi lengkap cara menggunakan Sistem TU Administrasi</p>
        </div>
        <div class="d-flex gap-2">
            <button onclick="window.print()" class="btn btn-sm" style="background:rgba(255,255,255,.2);color:#fff;border:1px solid rgba(255,255,255,.3);">
                <i class="bi bi-printer me-1"></i> Cetak
            </button>
        </div>
    </div>
</div>

<div class="row">
    {{-- Table of Contents --}}
    <div class="col-lg-3 d-none d-lg-block">
        <div class="card">
            <div class="card-body p-3">
                <h6 class="fw-bold mb-3" style="font-size:.82rem; color:#475569;">
                    <i class="bi bi-list-ul me-1"></i> Daftar Isi
                </h6>
                <input type="text" class="search-panduan mb-3" placeholder="Cari di panduan..." id="searchPanduan">
                <div class="toc-sidebar" id="tocContainer">
                    {{-- TOC akan digenerate oleh JS --}}
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="col-lg-9">
        <div class="card">
            <div class="card-body p-0">
                <div class="markdown-body" id="markdownContent">
                    {!! \Illuminate\Support\Str::markdown($content) !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Generate TOC from headings
    const content = document.getElementById('markdownContent');
    const tocContainer = document.getElementById('tocContainer');
    const headings = content.querySelectorAll('h2, h3');
    let tocHTML = '';

    headings.forEach((h, i) => {
        const id = 'section-' + i;
        h.id = id;
        const level = h.tagName === 'H3' ? ' toc-h3' : '';
        tocHTML += `<a href="#${id}" class="toc-link${level}">${h.textContent}</a>`;
    });
    tocContainer.innerHTML = tocHTML;

    // Highlight active TOC on scroll
    const tocLinks = document.querySelectorAll('.toc-link');
    window.addEventListener('scroll', () => {
        let current = '';
        headings.forEach(h => {
            if (window.scrollY >= h.offsetTop - 120) current = h.id;
        });
        tocLinks.forEach(link => {
            link.classList.toggle('active', link.getAttribute('href') === '#' + current);
        });
    });

    // Search functionality
    const searchInput = document.getElementById('searchPanduan');
    searchInput.addEventListener('input', function() {
        const query = this.value.toLowerCase();
        tocLinks.forEach(link => {
            const text = link.textContent.toLowerCase();
            link.style.display = text.includes(query) || query === '' ? '' : 'none';
        });

        // Highlight matches in content
        if (window.prevHighlights) {
            window.prevHighlights.forEach(el => {
                el.outerHTML = el.textContent;
            });
        }
        if (query.length >= 2) {
            const walker = document.createTreeWalker(content, NodeFilter.SHOW_TEXT, null, false);
            const matches = [];
            while (walker.nextNode()) {
                if (walker.currentNode.textContent.toLowerCase().includes(query)) {
                    matches.push(walker.currentNode);
                }
            }
            if (matches.length > 0) {
                matches[0].parentElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    });

    // Smooth scroll for TOC links
    tocLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    });
});
</script>
@endpush
