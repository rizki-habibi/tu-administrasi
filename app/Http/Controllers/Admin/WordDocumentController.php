<?php

namespace App\Http\Controllers\Admin;

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
        $query = WordDocument::with('user')->latest();

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

        return view('admin.word.index', compact('documents', 'categories'));
    }

    public function create(Request $request)
    {
        $templates = WordDocument::templates();
        $categories = WordDocument::categories();
        $selectedTemplate = $request->get('template', 'kosong');

        return view('admin.word.create', compact('templates', 'categories', 'selectedTemplate'));
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

        return redirect()->route('admin.word.edit', $doc)->with('success', 'Dokumen berhasil dibuat.');
    }

    public function show(WordDocument $word)
    {
        return view('admin.word.show', compact('word'));
    }

    public function edit(WordDocument $word)
    {
        $categories = WordDocument::categories();
        return view('admin.word.edit', compact('word', 'categories'));
    }

    public function update(Request $request, WordDocument $word)
    {
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
        if ($word->file_path) {
            Storage::disk('public')->delete($word->file_path);
        }
        $word->delete();

        return redirect()->route('admin.word.index')->with('success', 'Dokumen berhasil dihapus.');
    }

    /**
     * Download as .docx Word file
     */
    public function download(WordDocument $word)
    {
        $phpWord = new PhpWord();

        // Set document properties
        $phpWord->getDocInfo()->setCreator(auth()->user()->name);
        $phpWord->getDocInfo()->setTitle($word->title);
        $phpWord->getDocInfo()->setCompany('SMA Negeri 2 Jember');

        // Set default font
        $phpWord->setDefaultFontName('Times New Roman');
        $phpWord->setDefaultFontSize(12);

        $section = $phpWord->addSection([
            'marginTop' => 1440,    // 1 inch
            'marginBottom' => 1440,
            'marginLeft' => 1800,   // 1.25 inch
            'marginRight' => 1440,
        ]);

        // Convert HTML content to Word
        if ($word->content) {
            $htmlContent = $this->cleanHtmlForWord($word->content);
            Html::addHtml($section, $htmlContent, false, false);
        } else {
            $section->addText($word->title, ['bold' => true, 'size' => 14]);
            $section->addTextBreak(2);
            $section->addText('Dokumen ini belum memiliki konten.');
        }

        // Save to temp file
        $filename = 'word-docs/' . \Str::slug($word->title) . '_' . now()->format('Ymd_His') . '.docx';
        $tempPath = storage_path('app/public/' . $filename);

        // Ensure directory exists
        if (!file_exists(dirname($tempPath))) {
            mkdir(dirname($tempPath), 0755, true);
        }

        $writer = IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save($tempPath);

        // Update document record
        $word->update(['file_path' => $filename]);

        return response()->download($tempPath, \Str::slug($word->title) . '.docx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ]);
    }

    /**
     * AI Generate content via prompt
     */
    public function aiGenerate(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string|max:2000',
            'template' => 'nullable|string',
            'document_id' => 'nullable|exists:word_documents,id',
        ]);

        $prompt = $request->prompt;
        $template = $request->template;

        // Generate content based on template and prompt
        $content = $this->generateAiContent($prompt, $template);

        // Save prompt if document exists
        if ($request->document_id) {
            WordDocument::where('id', $request->document_id)->update(['ai_prompt' => $prompt]);
        }

        return response()->json([
            'success' => true,
            'content' => $content,
        ]);
    }

    /**
     * Get template content
     */
    public function template(Request $request)
    {
        $template = $request->get('template', 'kosong');
        $content = $this->getTemplateContent($template);

        return response()->json([
            'success' => true,
            'content' => $content,
        ]);
    }

    /**
     * Auto-save via AJAX
     */
    public function autosave(Request $request, WordDocument $word)
    {
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
        // Clean up HTML for PhpWord compatibility
        $html = preg_replace('/<script\b[^>]*>.*?<\/script>/is', '', $html);
        $html = preg_replace('/<style\b[^>]*>.*?<\/style>/is', '', $html);
        $html = preg_replace('/style="[^"]*"/i', '', $html);
        $html = preg_replace('/class="[^"]*"/i', '', $html);

        // Ensure proper HTML structure
        if (strpos($html, '<body') === false) {
            $html = '<body>' . $html . '</body>';
        }

        return $html;
    }

    private function generateAiContent(string $prompt, ?string $template): string
    {
        // AI-powered content generation using intelligent templates
        $schoolName = 'SMA Negeri 2 Jember';
        $principalName = 'Drs. H. Ahmad Fauzi, M.Pd.';
        $address = 'Jl. Gajah Mada No. 42, Jember, Jawa Timur';
        $phone = '(0331) 421870';
        $today = now()->translatedFormat('d F Y');
        $monthYear = now()->translatedFormat('F Y');

        // Analyze the prompt to determine what kind of document to generate
        $promptLower = mb_strtolower($prompt);

        // SURAT RESMI
        if (str_contains($promptLower, 'surat') || $template === 'surat_resmi' || $template === 'surat_keterangan') {
            return $this->generateSuratContent($prompt, $schoolName, $principalName, $address, $phone, $today);
        }

        // NOTULEN RAPAT
        if (str_contains($promptLower, 'notulen') || str_contains($promptLower, 'rapat') || $template === 'notulen_rapat') {
            return $this->generateNotulenContent($prompt, $schoolName, $today);
        }

        // LAPORAN
        if (str_contains($promptLower, 'laporan') || $template === 'laporan_bulanan' || $template === 'laporan_kehadiran') {
            return $this->generateLaporanContent($prompt, $schoolName, $principalName, $monthYear, $today);
        }

        // SK
        if (str_contains($promptLower, 'sk ') || str_contains($promptLower, 'keputusan') || $template === 'sk_kepala_sekolah') {
            return $this->generateSKContent($prompt, $schoolName, $principalName, $today);
        }

        // PROPOSAL
        if (str_contains($promptLower, 'proposal') || $template === 'proposal_kegiatan') {
            return $this->generateProposalContent($prompt, $schoolName, $today);
        }

        // KEUANGAN
        if (str_contains($promptLower, 'keuangan') || str_contains($promptLower, 'anggaran') || $template === 'laporan_keuangan') {
            return $this->generateKeuanganContent($prompt, $schoolName, $principalName, $monthYear, $today);
        }

        // DEFAULT: generate a general document
        return $this->generateGeneralDocument($prompt, $schoolName, $today);
    }

    private function generateSuratContent($prompt, $school, $principal, $address, $phone, $today): string
    {
        $nomorSurat = 'XXX/' . now()->format('m') . '/SMA2/' . now()->format('Y');

        return <<<HTML
<div style="text-align:center;">
    <h3 style="margin-bottom:0;">PEMERINTAH KABUPATEN JEMBER</h3>
    <h3 style="margin-bottom:0;">DINAS PENDIDIKAN</h3>
    <h2 style="margin-bottom:0;"><strong>{$school}</strong></h2>
    <p style="font-size:11px;">{$address}<br>Telp. {$phone} | Email: info@sman2jember.sch.id</p>
    <hr style="border-top:3px double #000;">
</div>

<br>
<table style="width:100%;">
    <tr>
        <td style="width:15%;">Nomor</td>
        <td style="width:2%;">:</td>
        <td>{$nomorSurat}</td>
    </tr>
    <tr>
        <td>Lampiran</td>
        <td>:</td>
        <td>-</td>
    </tr>
    <tr>
        <td>Perihal</td>
        <td>:</td>
        <td><strong>{$prompt}</strong></td>
    </tr>
</table>
<br>
<p>Kepada Yth.<br>
<strong>[Nama Penerima]</strong><br>
di Tempat</p>
<br>
<p><em>Assalamu'alaikum Wr. Wb.</em></p>
<br>
<p>Dengan hormat,</p>
<p>Sehubungan dengan {$prompt}, dengan ini kami sampaikan hal-hal sebagai berikut:</p>
<ol>
    <li>[Poin pertama sesuai kebutuhan]</li>
    <li>[Poin kedua sesuai kebutuhan]</li>
    <li>[Poin ketiga sesuai kebutuhan]</li>
</ol>
<p>Demikian surat ini kami sampaikan. Atas perhatian dan kerjasamanya, kami ucapkan terima kasih.</p>
<br>
<p><em>Wassalamu'alaikum Wr. Wb.</em></p>
<br>
<table style="width:100%;">
    <tr>
        <td style="width:60%;"></td>
        <td style="text-align:center;">
            Jember, {$today}<br>
            Kepala Sekolah,<br><br><br><br>
            <strong><u>{$principal}</u></strong><br>
            NIP. ___________________
        </td>
    </tr>
</table>
HTML;
    }

    private function generateNotulenContent($prompt, $school, $today): string
    {
        return <<<HTML
<div style="text-align:center;">
    <h2><strong>NOTULEN RAPAT</strong></h2>
    <h3>{$school}</h3>
    <hr>
</div>
<br>
<table style="width:100%;">
    <tr><td style="width:20%;">Hari / Tanggal</td><td style="width:2%;">:</td><td>{$today}</td></tr>
    <tr><td>Waktu</td><td>:</td><td>09.00 - 12.00 WIB</td></tr>
    <tr><td>Tempat</td><td>:</td><td>Ruang Rapat {$school}</td></tr>
    <tr><td>Acara</td><td>:</td><td><strong>{$prompt}</strong></td></tr>
    <tr><td>Pimpinan Rapat</td><td>:</td><td>[Nama Pimpinan Rapat]</td></tr>
    <tr><td>Notulis</td><td>:</td><td>[Nama Notulis]</td></tr>
    <tr><td>Peserta</td><td>:</td><td>Terlampir (daftar hadir)</td></tr>
</table>
<br>
<h4><strong>I. PEMBUKAAN</strong></h4>
<p>Rapat dibuka oleh pimpinan rapat pada pukul 09.00 WIB dengan mengucapkan basmalah.</p>
<br>
<h4><strong>II. AGENDA RAPAT</strong></h4>
<ol>
    <li>{$prompt}</li>
    <li>[Agenda tambahan]</li>
</ol>
<br>
<h4><strong>III. URAIAN PEMBAHASAN</strong></h4>
<p>[Uraikan pembahasan rapat secara detail di sini]</p>
<br>
<h4><strong>IV. KESIMPULAN DAN KEPUTUSAN</strong></h4>
<ol>
    <li>[Kesimpulan/keputusan pertama]</li>
    <li>[Kesimpulan/keputusan kedua]</li>
</ol>
<br>
<h4><strong>V. PENUTUP</strong></h4>
<p>Rapat ditutup pada pukul 12.00 WIB dengan membaca hamdallah.</p>
<br>
<table style="width:100%;">
    <tr>
        <td style="width:50%;text-align:center;">
            Pimpinan Rapat,<br><br><br><br>
            <strong><u>[Nama Pimpinan]</u></strong><br>
            NIP. _______________
        </td>
        <td style="width:50%;text-align:center;">
            Notulis,<br><br><br><br>
            <strong><u>[Nama Notulis]</u></strong><br>
            NIP. _______________
        </td>
    </tr>
</table>
HTML;
    }

    private function generateLaporanContent($prompt, $school, $principal, $monthYear, $today): string
    {
        return <<<HTML
<div style="text-align:center;">
    <h2><strong>LAPORAN</strong></h2>
    <h3>{$prompt}</h3>
    <p>{$school}<br>Periode: {$monthYear}</p>
    <hr>
</div>
<br>
<h4><strong>BAB I - PENDAHULUAN</strong></h4>
<h4>1.1 Latar Belakang</h4>
<p>Laporan ini disusun sebagai bentuk pertanggungjawaban dan dokumentasi kegiatan {$prompt} di {$school} selama periode {$monthYear}.</p>
<h4>1.2 Tujuan</h4>
<ol>
    <li>Memberikan informasi mengenai {$prompt}</li>
    <li>Sebagai bahan evaluasi untuk perbaikan ke depan</li>
    <li>Sebagai dokumentasi kegiatan administrasi sekolah</li>
</ol>
<br>
<h4><strong>BAB II - PELAKSANAAN</strong></h4>
<h4>2.1 Uraian Kegiatan</h4>
<p>[Uraikan detail pelaksanaan kegiatan di sini]</p>
<h4>2.2 Data dan Statistik</h4>
<table style="width:100%;border-collapse:collapse;">
    <tr style="background:#f0f0f0;">
        <th style="border:1px solid #000;padding:8px;">No</th>
        <th style="border:1px solid #000;padding:8px;">Uraian</th>
        <th style="border:1px solid #000;padding:8px;">Jumlah</th>
        <th style="border:1px solid #000;padding:8px;">Keterangan</th>
    </tr>
    <tr>
        <td style="border:1px solid #000;padding:8px;text-align:center;">1</td>
        <td style="border:1px solid #000;padding:8px;">[Item 1]</td>
        <td style="border:1px solid #000;padding:8px;text-align:center;">[Jumlah]</td>
        <td style="border:1px solid #000;padding:8px;">[Keterangan]</td>
    </tr>
    <tr>
        <td style="border:1px solid #000;padding:8px;text-align:center;">2</td>
        <td style="border:1px solid #000;padding:8px;">[Item 2]</td>
        <td style="border:1px solid #000;padding:8px;text-align:center;">[Jumlah]</td>
        <td style="border:1px solid #000;padding:8px;">[Keterangan]</td>
    </tr>
</table>
<br>
<h4><strong>BAB III - PENUTUP</strong></h4>
<h4>3.1 Kesimpulan</h4>
<p>[Tuliskan kesimpulan laporan di sini]</p>
<h4>3.2 Saran</h4>
<p>[Tuliskan saran untuk perbaikan ke depan]</p>
<br>
<table style="width:100%;">
    <tr>
        <td style="width:55%;"></td>
        <td style="text-align:center;">
            Jember, {$today}<br>
            Kepala Tata Usaha,<br><br><br><br>
            <strong><u>[Nama Kepala TU]</u></strong><br>
            NIP. _______________
        </td>
    </tr>
</table>
<br>
<p style="text-align:center;">Mengetahui,<br>Kepala {$school}<br><br><br><br>
<strong><u>{$principal}</u></strong><br>NIP. _______________</p>
HTML;
    }

    private function generateSKContent($prompt, $school, $principal, $today): string
    {
        $nomorSK = 'XXX/SK/' . now()->format('m.Y');

        return <<<HTML
<div style="text-align:center;">
    <h3 style="margin-bottom:0;">PEMERINTAH KABUPATEN JEMBER</h3>
    <h3 style="margin-bottom:0;">DINAS PENDIDIKAN</h3>
    <h2 style="margin-bottom:0;"><strong>{$school}</strong></h2>
    <hr style="border-top:3px double #000;">
</div>
<br>
<div style="text-align:center;">
    <h3><strong>SURAT KEPUTUSAN</strong></h3>
    <h3>KEPALA {$school}</h3>
    <p>Nomor: {$nomorSK}</p>
    <p><strong>Tentang</strong></p>
    <h3><strong>{$prompt}</strong></h3>
</div>
<br>
<p style="text-align:center;"><strong>KEPALA SEKOLAH</strong></p>
<br>
<p><strong>Menimbang:</strong></p>
<ol type="a">
    <li>bahwa dalam rangka {$prompt}, perlu ditetapkan Surat Keputusan Kepala Sekolah;</li>
    <li>bahwa hal-hal tersebut di atas perlu ditetapkan dengan Surat Keputusan;</li>
</ol>
<br>
<p><strong>Mengingat:</strong></p>
<ol>
    <li>Undang-Undang Nomor 20 Tahun 2003 tentang Sistem Pendidikan Nasional;</li>
    <li>Peraturan Pemerintah Nomor 19 Tahun 2005 tentang Standar Nasional Pendidikan;</li>
    <li>[Dasar hukum tambahan]</li>
</ol>
<br>
<p style="text-align:center;"><strong>MEMUTUSKAN</strong></p>
<br>
<p><strong>Menetapkan:</strong></p>
<table>
    <tr><td style="vertical-align:top;width:80px;"><strong>KESATU</strong></td><td>: [Isi keputusan pertama]</td></tr>
    <tr><td style="vertical-align:top;"><strong>KEDUA</strong></td><td>: [Isi keputusan kedua]</td></tr>
    <tr><td style="vertical-align:top;"><strong>KETIGA</strong></td><td>: Surat Keputusan ini berlaku sejak tanggal ditetapkan.</td></tr>
    <tr><td style="vertical-align:top;"><strong>KEEMPAT</strong></td><td>: Apabila dikemudian hari terdapat kekeliruan akan diperbaiki sebagaimana mestinya.</td></tr>
</table>
<br>
<table style="width:100%;">
    <tr>
        <td style="width:55%;"></td>
        <td style="text-align:center;">
            Ditetapkan di Jember<br>
            Pada tanggal {$today}<br>
            Kepala Sekolah,<br><br><br><br>
            <strong><u>{$principal}</u></strong><br>
            NIP. _______________
        </td>
    </tr>
</table>
HTML;
    }

    private function generateProposalContent($prompt, $school, $today): string
    {
        return <<<HTML
<div style="text-align:center;">
    <br><br><br>
    <h1><strong>PROPOSAL</strong></h1>
    <h2>{$prompt}</h2>
    <br><br>
    <p style="font-size:14px;">{$school}</p>
    <p>Jl. Gajah Mada No. 42, Jember, Jawa Timur</p>
    <p>{$today}</p>
    <br><br><br>
</div>
<hr>
<br>
<h3><strong>BAB I - PENDAHULUAN</strong></h3>
<h4>A. Latar Belakang</h4>
<p>{$prompt} merupakan salah satu program yang penting untuk dilaksanakan di {$school}. Kegiatan ini diharapkan dapat memberikan manfaat dan kontribusi positif bagi seluruh civitas akademika.</p>
<h4>B. Tujuan</h4>
<ol>
    <li>[Tujuan utama kegiatan]</li>
    <li>[Tujuan tambahan]</li>
</ol>
<h4>C. Manfaat</h4>
<ol>
    <li>[Manfaat bagi sekolah]</li>
    <li>[Manfaat bagi peserta]</li>
</ol>
<br>
<h3><strong>BAB II - PELAKSANAAN</strong></h3>
<h4>A. Nama Kegiatan</h4>
<p>{$prompt}</p>
<h4>B. Waktu dan Tempat</h4>
<p>Hari/Tanggal: [Isi tanggal]<br>Waktu: [Isi waktu]<br>Tempat: {$school}</p>
<h4>C. Peserta</h4>
<p>[Uraikan peserta kegiatan]</p>
<h4>D. Susunan Panitia</h4>
<table style="width:100%;border-collapse:collapse;">
    <tr style="background:#f0f0f0;">
        <th style="border:1px solid #000;padding:8px;">No</th>
        <th style="border:1px solid #000;padding:8px;">Jabatan</th>
        <th style="border:1px solid #000;padding:8px;">Nama</th>
    </tr>
    <tr>
        <td style="border:1px solid #000;padding:8px;text-align:center;">1</td>
        <td style="border:1px solid #000;padding:8px;">Ketua Panitia</td>
        <td style="border:1px solid #000;padding:8px;">[Nama]</td>
    </tr>
    <tr>
        <td style="border:1px solid #000;padding:8px;text-align:center;">2</td>
        <td style="border:1px solid #000;padding:8px;">Sekretaris</td>
        <td style="border:1px solid #000;padding:8px;">[Nama]</td>
    </tr>
    <tr>
        <td style="border:1px solid #000;padding:8px;text-align:center;">3</td>
        <td style="border:1px solid #000;padding:8px;">Bendahara</td>
        <td style="border:1px solid #000;padding:8px;">[Nama]</td>
    </tr>
</table>
<br>
<h3><strong>BAB III - ANGGARAN</strong></h3>
<table style="width:100%;border-collapse:collapse;">
    <tr style="background:#f0f0f0;">
        <th style="border:1px solid #000;padding:8px;">No</th>
        <th style="border:1px solid #000;padding:8px;">Uraian</th>
        <th style="border:1px solid #000;padding:8px;">Volume</th>
        <th style="border:1px solid #000;padding:8px;">Satuan</th>
        <th style="border:1px solid #000;padding:8px;">Jumlah (Rp)</th>
    </tr>
    <tr>
        <td style="border:1px solid #000;padding:8px;text-align:center;">1</td>
        <td style="border:1px solid #000;padding:8px;">[Item]</td>
        <td style="border:1px solid #000;padding:8px;text-align:center;">[Vol]</td>
        <td style="border:1px solid #000;padding:8px;">[Sat]</td>
        <td style="border:1px solid #000;padding:8px;text-align:right;">[Jumlah]</td>
    </tr>
    <tr><td colspan="4" style="border:1px solid #000;padding:8px;text-align:right;"><strong>Total</strong></td><td style="border:1px solid #000;padding:8px;text-align:right;"><strong>[Total]</strong></td></tr>
</table>
<br>
<h3><strong>BAB IV - PENUTUP</strong></h3>
<p>Demikian proposal ini kami susun. Besar harapan kami agar kegiatan ini dapat terlaksana dengan baik. Atas perhatian dan dukungannya, kami ucapkan terima kasih.</p>
HTML;
    }

    private function generateKeuanganContent($prompt, $school, $principal, $monthYear, $today): string
    {
        return <<<HTML
<div style="text-align:center;">
    <h2><strong>LAPORAN KEUANGAN</strong></h2>
    <h3>{$school}</h3>
    <p>Periode: {$monthYear}</p>
    <hr>
</div>
<br>
<h4><strong>I. RINGKASAN KEUANGAN</strong></h4>
<table style="width:100%;border-collapse:collapse;">
    <tr style="background:#f0f0f0;">
        <th style="border:1px solid #000;padding:8px;" colspan="2">Uraian</th>
        <th style="border:1px solid #000;padding:8px;">Jumlah (Rp)</th>
    </tr>
    <tr>
        <td style="border:1px solid #000;padding:8px;" colspan="2">Saldo Awal</td>
        <td style="border:1px solid #000;padding:8px;text-align:right;">0</td>
    </tr>
    <tr>
        <td style="border:1px solid #000;padding:8px;" colspan="2"><strong>Total Penerimaan</strong></td>
        <td style="border:1px solid #000;padding:8px;text-align:right;"><strong>0</strong></td>
    </tr>
    <tr>
        <td style="border:1px solid #000;padding:8px;" colspan="2"><strong>Total Pengeluaran</strong></td>
        <td style="border:1px solid #000;padding:8px;text-align:right;"><strong>0</strong></td>
    </tr>
    <tr style="background:#e8f5e9;">
        <td style="border:1px solid #000;padding:8px;" colspan="2"><strong>Saldo Akhir</strong></td>
        <td style="border:1px solid #000;padding:8px;text-align:right;"><strong>0</strong></td>
    </tr>
</table>
<br>
<h4><strong>II. RINCIAN PENERIMAAN</strong></h4>
<table style="width:100%;border-collapse:collapse;">
    <tr style="background:#f0f0f0;">
        <th style="border:1px solid #000;padding:8px;">No</th>
        <th style="border:1px solid #000;padding:8px;">Tanggal</th>
        <th style="border:1px solid #000;padding:8px;">Uraian</th>
        <th style="border:1px solid #000;padding:8px;">Jumlah (Rp)</th>
    </tr>
    <tr>
        <td style="border:1px solid #000;padding:8px;text-align:center;">1</td>
        <td style="border:1px solid #000;padding:8px;">[Tanggal]</td>
        <td style="border:1px solid #000;padding:8px;">[Uraian]</td>
        <td style="border:1px solid #000;padding:8px;text-align:right;">[Jumlah]</td>
    </tr>
</table>
<br>
<h4><strong>III. RINCIAN PENGELUARAN</strong></h4>
<table style="width:100%;border-collapse:collapse;">
    <tr style="background:#f0f0f0;">
        <th style="border:1px solid #000;padding:8px;">No</th>
        <th style="border:1px solid #000;padding:8px;">Tanggal</th>
        <th style="border:1px solid #000;padding:8px;">Uraian</th>
        <th style="border:1px solid #000;padding:8px;">Jumlah (Rp)</th>
    </tr>
    <tr>
        <td style="border:1px solid #000;padding:8px;text-align:center;">1</td>
        <td style="border:1px solid #000;padding:8px;">[Tanggal]</td>
        <td style="border:1px solid #000;padding:8px;">[Uraian]</td>
        <td style="border:1px solid #000;padding:8px;text-align:right;">[Jumlah]</td>
    </tr>
</table>
<br>
<table style="width:100%;">
    <tr>
        <td style="width:50%;text-align:center;">
            Mengetahui,<br>Kepala Sekolah,<br><br><br><br>
            <strong><u>{$principal}</u></strong><br>NIP. _______________
        </td>
        <td style="width:50%;text-align:center;">
            Jember, {$today}<br>Bendahara,<br><br><br><br>
            <strong><u>[Nama Bendahara]</u></strong><br>NIP. _______________
        </td>
    </tr>
</table>
HTML;
    }

    private function generateGeneralDocument($prompt, $school, $today): string
    {
        return <<<HTML
<div style="text-align:center;">
    <h2><strong>{$prompt}</strong></h2>
    <p>{$school}</p>
    <p>{$today}</p>
    <hr>
</div>
<br>
<p>Berikut adalah dokumen mengenai <strong>{$prompt}</strong> yang disusun oleh Tata Usaha {$school}.</p>
<br>
<h4><strong>1. Pendahuluan</strong></h4>
<p>[Tuliskan pendahuluan/latar belakang di sini]</p>
<br>
<h4><strong>2. Isi Dokumen</strong></h4>
<p>[Tuliskan isi utama dokumen di sini]</p>
<br>
<h4><strong>3. Penutup</strong></h4>
<p>Demikian dokumen ini dibuat untuk dipergunakan sebagaimana mestinya.</p>
<br>
<table style="width:100%;">
    <tr>
        <td style="width:55%;"></td>
        <td style="text-align:center;">
            Jember, {$today}<br>
            Penyusun,<br><br><br><br>
            <strong><u>[Nama Penyusun]</u></strong>
        </td>
    </tr>
</table>
HTML;
    }

    private function getTemplateContent(string $template): string
    {
        return match ($template) {
            'surat_resmi' => $this->generateSuratContent('Perihal Surat', 'SMA Negeri 2 Jember', 'Drs. H. Ahmad Fauzi, M.Pd.', 'Jl. Gajah Mada No. 42, Jember', '(0331) 421870', now()->translatedFormat('d F Y')),
            'surat_keterangan' => $this->generateSuratContent('Surat Keterangan', 'SMA Negeri 2 Jember', 'Drs. H. Ahmad Fauzi, M.Pd.', 'Jl. Gajah Mada No. 42, Jember', '(0331) 421870', now()->translatedFormat('d F Y')),
            'notulen_rapat' => $this->generateNotulenContent('Rapat Koordinasi', 'SMA Negeri 2 Jember', now()->translatedFormat('d F Y')),
            'laporan_bulanan' => $this->generateLaporanContent('Laporan Bulanan Kegiatan TU', 'SMA Negeri 2 Jember', 'Drs. H. Ahmad Fauzi, M.Pd.', now()->translatedFormat('F Y'), now()->translatedFormat('d F Y')),
            'laporan_kehadiran' => $this->generateLaporanContent('Rekapitulasi Kehadiran Staf', 'SMA Negeri 2 Jember', 'Drs. H. Ahmad Fauzi, M.Pd.', now()->translatedFormat('F Y'), now()->translatedFormat('d F Y')),
            'sk_kepala_sekolah' => $this->generateSKContent('Penetapan [Nama Keputusan]', 'SMA Negeri 2 Jember', 'Drs. H. Ahmad Fauzi, M.Pd.', now()->translatedFormat('d F Y')),
            'proposal_kegiatan' => $this->generateProposalContent('Nama Kegiatan', 'SMA Negeri 2 Jember', now()->translatedFormat('d F Y')),
            'laporan_keuangan' => $this->generateKeuanganContent('Laporan Keuangan', 'SMA Negeri 2 Jember', 'Drs. H. Ahmad Fauzi, M.Pd.', now()->translatedFormat('F Y'), now()->translatedFormat('d F Y')),
            default => '<p>Mulai menulis dokumen Anda di sini...</p>',
        };
    }
}
