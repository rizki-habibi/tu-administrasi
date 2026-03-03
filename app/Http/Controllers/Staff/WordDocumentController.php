<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\WordDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Shared\Html;

class WordDocumentController extends Controller
{
    public function index(Request $request)
    {
        $query = WordDocument::with('user')
            ->accessible(auth()->id())
            ->latest();

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $documents = $query->paginate(15);
        $categories = WordDocument::categories();

        return view('staff.word.index', compact('documents', 'categories'));
    }

    public function create(Request $request)
    {
        $templates = WordDocument::templates();
        $categories = WordDocument::categories();
        $selectedTemplate = $request->get('template', 'kosong');

        return view('staff.word.create', compact('templates', 'categories', 'selectedTemplate'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'content' => 'nullable|string',
            'template' => 'nullable|string',
            'status' => 'required|in:draft,final,archived',
            'is_shared' => 'nullable|boolean',
        ]);

        $doc = WordDocument::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'category' => $request->category,
            'content' => $request->content,
            'template' => $request->template,
            'status' => $request->status,
            'is_shared' => $request->boolean('is_shared'),
        ]);

        return redirect()->route('staff.word.edit', $doc)->with('success', 'Dokumen berhasil dibuat.');
    }

    public function show(WordDocument $word)
    {
        // Staff can only see own documents or shared
        if ($word->user_id !== auth()->id() && !$word->is_shared) {
            abort(403, 'Akses ditolak.');
        }

        return view('staff.word.show', compact('word'));
    }

    public function edit(WordDocument $word)
    {
        // Staff can only edit own documents
        if ($word->user_id !== auth()->id()) {
            abort(403, 'Anda hanya bisa mengedit dokumen milik sendiri.');
        }

        $categories = WordDocument::categories();
        return view('staff.word.edit', compact('word', 'categories'));
    }

    public function update(Request $request, WordDocument $word)
    {
        if ($word->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'content' => 'nullable|string',
            'status' => 'required|in:draft,final,archived',
            'is_shared' => 'nullable|boolean',
        ]);

        $word->update([
            'title' => $request->title,
            'category' => $request->category,
            'content' => $request->content,
            'status' => $request->status,
            'is_shared' => $request->boolean('is_shared'),
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Dokumen berhasil disimpan.']);
        }

        return redirect()->back()->with('success', 'Dokumen berhasil disimpan.');
    }

    public function destroy(WordDocument $word)
    {
        if ($word->user_id !== auth()->id()) {
            abort(403);
        }

        if ($word->file_path) {
            Storage::disk('public')->delete($word->file_path);
        }
        $word->delete();

        return redirect()->route('staff.word.index')->with('success', 'Dokumen berhasil dihapus.');
    }

    public function download(WordDocument $word)
    {
        if ($word->user_id !== auth()->id() && !$word->is_shared) {
            abort(403);
        }

        $phpWord = new PhpWord();
        $phpWord->getDocInfo()->setCreator(auth()->user()->name);
        $phpWord->getDocInfo()->setTitle($word->title);
        $phpWord->getDocInfo()->setCompany('SMA Negeri 2 Jember');

        $phpWord->setDefaultFontName('Times New Roman');
        $phpWord->setDefaultFontSize(12);

        $section = $phpWord->addSection([
            'marginTop' => 1440,
            'marginBottom' => 1440,
            'marginLeft' => 1800,
            'marginRight' => 1440,
        ]);

        if ($word->content) {
            $htmlContent = $this->cleanHtmlForWord($word->content);
            Html::addHtml($section, $htmlContent, false, false);
        } else {
            $section->addText($word->title, ['bold' => true, 'size' => 14]);
        }

        $filename = 'word-docs/' . \Str::slug($word->title) . '_' . now()->format('Ymd_His') . '.docx';
        $tempPath = storage_path('app/public/' . $filename);

        if (!file_exists(dirname($tempPath))) {
            mkdir(dirname($tempPath), 0755, true);
        }

        $writer = IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save($tempPath);

        $word->update(['file_path' => $filename]);

        return response()->download($tempPath, \Str::slug($word->title) . '.docx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ]);
    }

    public function aiGenerate(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string|max:2000',
            'template' => 'nullable|string',
            'document_id' => 'nullable|exists:word_documents,id',
        ]);

        $prompt = $request->prompt;
        $template = $request->template;

        $content = $this->generateAiContent($prompt, $template);

        if ($request->document_id) {
            $doc = WordDocument::where('id', $request->document_id)->where('user_id', auth()->id())->first();
            if ($doc) {
                $doc->update(['ai_prompt' => $prompt]);
            }
        }

        return response()->json(['success' => true, 'content' => $content]);
    }

    public function template(Request $request)
    {
        $template = $request->get('template', 'kosong');
        $content = $this->getTemplateContent($template);

        return response()->json(['success' => true, 'content' => $content]);
    }

    public function autosave(Request $request, WordDocument $word)
    {
        if ($word->user_id !== auth()->id()) {
            abort(403);
        }

        $word->update([
            'content' => $request->content,
            'title' => $request->title ?? $word->title,
        ]);

        return response()->json(['success' => true, 'saved_at' => now()->format('H:i:s')]);
    }

    // ====================================
    // Private helper methods
    // ====================================

    private function cleanHtmlForWord(string $html): string
    {
        $html = preg_replace('/<script\b[^>]*>.*?<\/script>/is', '', $html);
        $html = preg_replace('/<style\b[^>]*>.*?<\/style>/is', '', $html);
        $html = preg_replace('/style="[^"]*"/i', '', $html);
        $html = preg_replace('/class="[^"]*"/i', '', $html);
        if (strpos($html, '<body') === false) {
            $html = '<body>' . $html . '</body>';
        }
        return $html;
    }

    private function generateAiContent(string $prompt, ?string $template): string
    {
        $schoolName = 'SMA Negeri 2 Jember';
        $principalName = 'Drs. H. Ahmad Fauzi, M.Pd.';
        $address = 'Jl. Gajah Mada No. 42, Jember, Jawa Timur';
        $phone = '(0331) 421870';
        $today = now()->translatedFormat('d F Y');
        $monthYear = now()->translatedFormat('F Y');

        $promptLower = mb_strtolower($prompt);

        if (str_contains($promptLower, 'surat') || $template === 'surat_resmi' || $template === 'surat_keterangan') {
            return $this->generateSuratContent($prompt, $schoolName, $principalName, $address, $phone, $today);
        }
        if (str_contains($promptLower, 'notulen') || str_contains($promptLower, 'rapat') || $template === 'notulen_rapat') {
            return $this->generateNotulenContent($prompt, $schoolName, $today);
        }
        if (str_contains($promptLower, 'laporan') || $template === 'laporan_bulanan' || $template === 'laporan_kehadiran') {
            return $this->generateLaporanContent($prompt, $schoolName, $principalName, $monthYear, $today);
        }
        if (str_contains($promptLower, 'sk ') || str_contains($promptLower, 'keputusan') || $template === 'sk_kepala_sekolah') {
            return $this->generateSKContent($prompt, $schoolName, $principalName, $today);
        }
        if (str_contains($promptLower, 'proposal') || $template === 'proposal_kegiatan') {
            return $this->generateProposalContent($prompt, $schoolName, $today);
        }
        if (str_contains($promptLower, 'keuangan') || str_contains($promptLower, 'anggaran') || $template === 'laporan_keuangan') {
            return $this->generateKeuanganContent($prompt, $schoolName, $principalName, $monthYear, $today);
        }

        return $this->generateGeneralDocument($prompt, $schoolName, $today);
    }

    private function generateSuratContent($prompt, $school, $principal, $address, $phone, $today): string
    {
        $nomorSurat = 'XXX/' . now()->format('m') . '/SMA2/' . now()->format('Y');
        return "<div style=\"text-align:center;\"><h3>PEMERINTAH KABUPATEN JEMBER</h3><h3>DINAS PENDIDIKAN</h3><h2><strong>{$school}</strong></h2><p style=\"font-size:11px;\">{$address}<br>Telp. {$phone}</p><hr style=\"border-top:3px double #000;\"></div><br><table style=\"width:100%;\"><tr><td style=\"width:15%;\">Nomor</td><td style=\"width:2%;\">:</td><td>{$nomorSurat}</td></tr><tr><td>Lampiran</td><td>:</td><td>-</td></tr><tr><td>Perihal</td><td>:</td><td><strong>{$prompt}</strong></td></tr></table><br><p>Kepada Yth.<br><strong>[Nama Penerima]</strong><br>di Tempat</p><br><p><em>Assalamu'alaikum Wr. Wb.</em></p><br><p>Dengan hormat,</p><p>Sehubungan dengan {$prompt}, dengan ini kami sampaikan hal-hal sebagai berikut:</p><ol><li>[Poin pertama]</li><li>[Poin kedua]</li></ol><p>Demikian surat ini kami sampaikan. Atas perhatian dan kerjasamanya, kami ucapkan terima kasih.</p><br><p><em>Wassalamu'alaikum Wr. Wb.</em></p><br><table style=\"width:100%;\"><tr><td style=\"width:60%;\"></td><td style=\"text-align:center;\">Jember, {$today}<br>Kepala Sekolah,<br><br><br><br><strong><u>{$principal}</u></strong><br>NIP. _______________</td></tr></table>";
    }

    private function generateNotulenContent($prompt, $school, $today): string
    {
        return "<div style=\"text-align:center;\"><h2><strong>NOTULEN RAPAT</strong></h2><h3>{$school}</h3><hr></div><br><table style=\"width:100%;\"><tr><td style=\"width:20%;\">Hari/Tanggal</td><td style=\"width:2%;\">:</td><td>{$today}</td></tr><tr><td>Waktu</td><td>:</td><td>09.00 - 12.00 WIB</td></tr><tr><td>Tempat</td><td>:</td><td>Ruang Rapat {$school}</td></tr><tr><td>Acara</td><td>:</td><td><strong>{$prompt}</strong></td></tr></table><br><h4><strong>I. PEMBUKAAN</strong></h4><p>Rapat dibuka pada pukul 09.00 WIB.</p><br><h4><strong>II. PEMBAHASAN</strong></h4><p>[Uraikan pembahasan]</p><br><h4><strong>III. KEPUTUSAN</strong></h4><ol><li>[Keputusan 1]</li><li>[Keputusan 2]</li></ol><br><h4><strong>IV. PENUTUP</strong></h4><p>Rapat ditutup pada pukul 12.00 WIB.</p>";
    }

    private function generateLaporanContent($prompt, $school, $principal, $monthYear, $today): string
    {
        return "<div style=\"text-align:center;\"><h2><strong>LAPORAN</strong></h2><h3>{$prompt}</h3><p>{$school} - Periode: {$monthYear}</p><hr></div><br><h4><strong>BAB I - PENDAHULUAN</strong></h4><p>Laporan {$prompt} disusun sebagai dokumentasi kegiatan di {$school}.</p><br><h4><strong>BAB II - PELAKSANAAN</strong></h4><p>[Uraikan pelaksanaan]</p><br><h4><strong>BAB III - PENUTUP</strong></h4><p>[Kesimpulan dan saran]</p><br><table style=\"width:100%;\"><tr><td style=\"width:55%;\"></td><td style=\"text-align:center;\">Jember, {$today}<br>Kepala TU,<br><br><br><br><strong><u>[Nama]</u></strong><br>NIP. ___</td></tr></table>";
    }

    private function generateSKContent($prompt, $school, $principal, $today): string
    {
        return "<div style=\"text-align:center;\"><h3>PEMERINTAH KABUPATEN JEMBER</h3><h2><strong>{$school}</strong></h2><hr style=\"border-top:3px double #000;\"></div><br><div style=\"text-align:center;\"><h3><strong>SURAT KEPUTUSAN KEPALA SEKOLAH</strong></h3><p>Tentang</p><h3><strong>{$prompt}</strong></h3></div><br><p><strong>Menimbang:</strong></p><ol type=\"a\"><li>bahwa {$prompt} perlu ditetapkan;</li></ol><p><strong>Mengingat:</strong></p><ol><li>UU No. 20 Tahun 2003 tentang Sisdiknas;</li></ol><p style=\"text-align:center;\"><strong>MEMUTUSKAN</strong></p><p><strong>KESATU:</strong> [Keputusan]</p><p><strong>KEDUA:</strong> Berlaku sejak tanggal ditetapkan.</p><br><table style=\"width:100%;\"><tr><td style=\"width:55%;\"></td><td style=\"text-align:center;\">Ditetapkan di Jember<br>Pada tanggal {$today}<br>Kepala Sekolah,<br><br><br><br><strong><u>{$principal}</u></strong><br>NIP. ___</td></tr></table>";
    }

    private function generateProposalContent($prompt, $school, $today): string
    {
        return "<div style=\"text-align:center;\"><h1><strong>PROPOSAL</strong></h1><h2>{$prompt}</h2><p>{$school}</p><p>{$today}</p></div><hr><br><h3><strong>BAB I - PENDAHULUAN</strong></h3><p>{$prompt} merupakan program penting di {$school}.</p><br><h3><strong>BAB II - PELAKSANAAN</strong></h3><p>[Detail pelaksanaan]</p><br><h3><strong>BAB III - ANGGARAN</strong></h3><p>[Detail anggaran]</p><br><h3><strong>BAB IV - PENUTUP</strong></h3><p>Demikian proposal ini kami susun.</p>";
    }

    private function generateKeuanganContent($prompt, $school, $principal, $monthYear, $today): string
    {
        return "<div style=\"text-align:center;\"><h2><strong>LAPORAN KEUANGAN</strong></h2><h3>{$school}</h3><p>Periode: {$monthYear}</p><hr></div><br><h4><strong>I. RINGKASAN</strong></h4><table style=\"width:100%;border-collapse:collapse;\"><tr style=\"background:#f0f0f0;\"><th style=\"border:1px solid #000;padding:8px;\">Uraian</th><th style=\"border:1px solid #000;padding:8px;\">Jumlah (Rp)</th></tr><tr><td style=\"border:1px solid #000;padding:8px;\">Saldo Awal</td><td style=\"border:1px solid #000;padding:8px;text-align:right;\">0</td></tr><tr><td style=\"border:1px solid #000;padding:8px;\">Total Penerimaan</td><td style=\"border:1px solid #000;padding:8px;text-align:right;\">0</td></tr><tr><td style=\"border:1px solid #000;padding:8px;\">Total Pengeluaran</td><td style=\"border:1px solid #000;padding:8px;text-align:right;\">0</td></tr><tr><td style=\"border:1px solid #000;padding:8px;\"><strong>Saldo Akhir</strong></td><td style=\"border:1px solid #000;padding:8px;text-align:right;\"><strong>0</strong></td></tr></table><br><table style=\"width:100%;\"><tr><td style=\"width:50%;text-align:center;\">Mengetahui,<br>Kepala Sekolah,<br><br><br><br><strong><u>{$principal}</u></strong><br>NIP. ___</td><td style=\"width:50%;text-align:center;\">Jember, {$today}<br>Bendahara,<br><br><br><br><strong><u>[Nama]</u></strong><br>NIP. ___</td></tr></table>";
    }

    private function generateGeneralDocument($prompt, $school, $today): string
    {
        return "<div style=\"text-align:center;\"><h2><strong>{$prompt}</strong></h2><p>{$school} - {$today}</p><hr></div><br><h4><strong>1. Pendahuluan</strong></h4><p>[Tuliskan pendahuluan]</p><br><h4><strong>2. Isi</strong></h4><p>[Tuliskan isi dokumen]</p><br><h4><strong>3. Penutup</strong></h4><p>Demikian dokumen ini dibuat.</p>";
    }

    private function getTemplateContent(string $template): string
    {
        $school = 'SMA Negeri 2 Jember';
        $principal = 'Drs. H. Ahmad Fauzi, M.Pd.';
        $address = 'Jl. Gajah Mada No. 42, Jember';
        $phone = '(0331) 421870';
        $today = now()->translatedFormat('d F Y');
        $monthYear = now()->translatedFormat('F Y');

        return match ($template) {
            'surat_resmi' => $this->generateSuratContent('Perihal Surat', $school, $principal, $address, $phone, $today),
            'surat_keterangan' => $this->generateSuratContent('Surat Keterangan', $school, $principal, $address, $phone, $today),
            'notulen_rapat' => $this->generateNotulenContent('Rapat Koordinasi', $school, $today),
            'laporan_bulanan' => $this->generateLaporanContent('Laporan Bulanan', $school, $principal, $monthYear, $today),
            'laporan_kehadiran' => $this->generateLaporanContent('Rekapitulasi Kehadiran', $school, $principal, $monthYear, $today),
            'sk_kepala_sekolah' => $this->generateSKContent('Penetapan [Keputusan]', $school, $principal, $today),
            'proposal_kegiatan' => $this->generateProposalContent('Nama Kegiatan', $school, $today),
            'laporan_keuangan' => $this->generateKeuanganContent('Laporan Keuangan', $school, $principal, $monthYear, $today),
            default => '<p>Mulai menulis dokumen Anda di sini...</p>',
        };
    }
}
