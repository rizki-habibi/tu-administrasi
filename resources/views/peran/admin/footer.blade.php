{{-- Footer Scripts for Admin Layout --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Sidebar Toggle (header only) ────────────────────────────────
    const sidebar = document.getElementById('sidebar');
    const toggle  = document.getElementById('sidebarToggle');
    const body    = document.body;
    const isMobile = () => window.innerWidth <= 991;

    function doToggle() {
        if (isMobile()) {
            body.classList.toggle('sidebar-open');
        } else {
            body.classList.toggle('sidebar-collapsed');
            localStorage.setItem('sidebar', body.classList.contains('sidebar-collapsed') ? 'collapsed' : 'expanded');
        }
    }

    if (toggle) toggle.addEventListener('click', doToggle);

    if (!isMobile() && localStorage.getItem('sidebar') === 'collapsed') {
        body.classList.add('sidebar-collapsed');
    }

    document.addEventListener('click', e => {
        if (isMobile() && body.classList.contains('sidebar-open') && !sidebar.contains(e.target) && e.target !== toggle && (!toggle || !toggle.contains(e.target))) {
            body.classList.remove('sidebar-open');
        }
    });

    // ── Submenu Toggle (supports nested submenus) ───────────────────
    document.querySelectorAll('[data-toggle="submenu"]').forEach(el => {
        el.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const navItem = this.closest('.nav-item');
            if (!navItem) return;

            const isOpening = !navItem.classList.contains('open');
            navItem.classList.toggle('open');

            if (isOpening) {
                // Recalculate parent max-heights for nested submenus
                let parent = navItem.parentElement;
                while (parent) {
                    if (parent.classList.contains('submenu') || parent.classList.contains('nav-group-items')) {
                        parent.style.maxHeight = parent.scrollHeight + 500 + 'px';
                    }
                    parent = parent.parentElement;
                }
            } else {
                // When closing, remove inline maxHeight on the submenu itself
                const sub = navItem.querySelector('.submenu');
                if (sub) sub.style.maxHeight = '';
                // Recalculate parent max-heights after closing
                setTimeout(() => {
                    let parent = navItem.parentElement;
                    while (parent) {
                        if (parent.classList.contains('submenu') || parent.classList.contains('nav-group-items')) {
                            parent.style.maxHeight = parent.scrollHeight + 'px';
                        }
                        parent = parent.parentElement;
                    }
                }, 50);
            }

            // Keep the clicked item visible in sidebar
            setTimeout(() => {
                this.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }, 120);
        });
    });

    // ── Sidebar Search ──────────────────────────────────────────────
    const searchInput = document.getElementById('sidebarSearch');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            document.querySelectorAll('.nav-group').forEach(group => {
                const items = group.querySelectorAll('.nav-item');
                let groupMatch = false;
                items.forEach(item => {
                    const text = item.textContent.toLowerCase();
                    const match = !query || text.includes(query);
                    item.style.display = match ? '' : 'none';
                    if (match) groupMatch = true;
                });
                group.style.display = groupMatch || !query ? '' : 'none';
                group.classList.toggle('search-match', !!(query && groupMatch));
            });
        });
    }

    // ═══════════════════════════════════════════════════════════════
    // SETTINGS RIGHT DRAWER
    // ═══════════════════════════════════════════════════════════════
    const settingsDrawer  = document.getElementById('settingsDrawer');
    const settingsOverlay = document.getElementById('settingsOverlay');
    const closeSettingsDrawer = document.getElementById('closeSettingsDrawer');
    const headerSettingsBtn = document.getElementById('headerSettingsBtn');

    function openDrawer() {
        if (settingsDrawer)  settingsDrawer.classList.add('open');
        if (settingsOverlay) settingsOverlay.classList.add('open');
        loadDrawerStorage();
    }
    function closeDrawer() {
        if (settingsDrawer)  settingsDrawer.classList.remove('open');
        if (settingsOverlay) settingsOverlay.classList.remove('open');
    }

    if (headerSettingsBtn) headerSettingsBtn.addEventListener('click', openDrawer);
    if (closeSettingsDrawer) closeSettingsDrawer.addEventListener('click', closeDrawer);
    if (settingsOverlay) settingsOverlay.addEventListener('click', closeDrawer);

    // ── Drawer: Dark Mode Toggle ──
    const darkToggle = document.getElementById('darkModeToggle');
    if (darkToggle) {
        darkToggle.checked = localStorage.getItem('darkMode') === '1';
        if (darkToggle.checked) body.classList.add('dark-mode');
        darkToggle.addEventListener('change', function() {
            body.classList.toggle('dark-mode', this.checked);
            localStorage.setItem('darkMode', this.checked ? '1' : '0');
        });
    }

    // ── Drawer: Load Storage ──
    function loadDrawerStorage() {
        const bar  = document.getElementById('drawerStorageBar');
        const text = document.getElementById('drawerStorageText');
        const pct  = document.getElementById('drawerStoragePct');
        if (!bar) return;
        fetch('{{ route("api.cek-storage") }}', {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(d => {
            const persen = d.persen || 0;
            bar.style.width = persen + '%';
            bar.style.background = persen > 85 ? '#ef4444' : persen > 60 ? '#f59e0b' : '#6366f1';
            if (text) text.textContent = (d.terpakai_format || '0 B') + ' / ' + (d.total_format || '—');
            if (pct) pct.textContent = persen + '%';
        })
        .catch(() => {
            if (text) text.textContent = 'Gagal memuat';
        });
    }

    // ═══════════════════════════════════════════════════════════════
    // AI CHAT POPUP
    // ═══════════════════════════════════════════════════════════════
    const fabAi   = document.getElementById('fabAi');
    const aiPopup = document.getElementById('aiPopup');
    const closeAi = document.getElementById('closeAi');
    const headerAiBtn = document.getElementById('headerAiBtn');

    function showAi() { if (aiPopup) { aiPopup.classList.add('show'); if (fabAi) fabAi.classList.add('hidden'); } }
    function hideAi() { if (aiPopup) { aiPopup.classList.remove('show'); if (fabAi) fabAi.classList.remove('hidden'); } }

    if (fabAi)       fabAi.addEventListener('click', showAi);
    if (closeAi)     closeAi.addEventListener('click', hideAi);
    if (headerAiBtn) headerAiBtn.addEventListener('click', function() { aiPopup && aiPopup.classList.contains('show') ? hideAi() : showAi(); });

    // ── SweetAlert Confirm (data-confirm) ───────────────────────────
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('[data-confirm]');
        if (!btn) return;
        e.preventDefault();

        Swal.fire({
            title: 'Konfirmasi',
            text: btn.dataset.confirm || 'Yakin ingin melanjutkan?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#6366f1',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Ya',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: { popup: 'rounded-4' }
        }).then(r => {
            if (r.isConfirmed) {
                const form = btn.closest('form');
                if (form) form.submit();
                else location.href = btn.href;
            }
        });
    });

    // ── Auto-close Alerts after 4 seconds ───────────────────────────
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(a => {
            try { new bootstrap.Alert(a).close(); } catch (err) {}
        });
    }, 4000);

    // ── Notification Count Polling (every 30s) ──────────────────────
    setInterval(() => {
        fetch('{{ route("admin.notifikasi.json") }}', {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(data => {
            const badge = document.getElementById('notifBadge');
            const count = document.getElementById('notifCount');
            if (count) count.textContent = data.unread_count;
            if (badge) badge.style.display = data.unread_count > 0 ? '' : 'none';
        })
        .catch(() => {});
    }, 30000);

    // ═══════════════════════════════════════════════════════════════
    // AI CHAT — Enhanced with full knowledge base
    // ═══════════════════════════════════════════════════════════════

    const aiMessages    = document.getElementById('aiMessages');
    const aiInput       = document.getElementById('aiInput');
    const aiSend        = document.getElementById('aiSend');
    const aiVoice       = document.getElementById('aiVoice');
    const aiQuickActions = document.getElementById('aiQuickActions');

    function addAiMessage(text, isUser) {
        const div = document.createElement('div');
        div.className = 'ai-msg ' + (isUser ? 'user' : 'bot');
        div.innerHTML = isUser
            ? '<div class="ai-msg-bubble">' + escapeHtml(text) + '</div>'
            : '<div class="ai-msg-avatar"><div class="ai-3d-icon"><i class="bi bi-robot"></i></div></div><div class="ai-msg-bubble">' + text + '</div>';
        aiMessages.appendChild(div);
        aiMessages.scrollTop = aiMessages.scrollHeight;
    }

    function escapeHtml(str) {
        const d = document.createElement('div');
        d.textContent = str;
        return d.innerHTML;
    }

    // ── Comprehensive AI Knowledge Base ──
    function simulateAiResponse(userText) {
        const lower = userText.toLowerCase();
        let response = '';

        // ── Surat & Dokumen ──
        if (lower.includes('surat') && (lower.includes('alur') || lower.includes('proses') || lower.includes('status'))) {
            response = 'Alur surat di SIMPEG-SMART:<br><br>' +
                '<strong>📋 Status Surat:</strong> Draft → Diproses → Dikirim → Diterima → Diarsipkan<br><br>' +
                '<strong>Format Nomor Otomatis:</strong><br>• <code>{nomor}/{SM|SK}/{kode}/{TU-SMA2}/{bulan}/{tahun}</code><br>• Contoh: <code>001/SK/DN/TU-SMA2/03/2026</code><br><br>' +
                '<strong>Jenis Surat:</strong> Masuk / Keluar<br>' +
                '<strong>Kategori:</strong> Dinas, Undangan, Keterangan, Keputusan, Edaran, Surat Tugas, Pemberitahuan<br><br>' +
                'Buka di: <strong>Sidebar → Surat Menyurat → Buat Surat Baru</strong><br>' +
                'Untuk disposisi: <strong>Sidebar → Disposisi Surat</strong>';
        } else if (lower.includes('surat') || lower.includes('draft') || lower.includes('disposisi')) {
            response = 'Fitur <strong>Surat Menyurat</strong> SIMPEG-SMART:<br><br>' +
                '📝 <strong>Buat Surat:</strong> Sidebar → Surat Menyurat → Buat Surat Baru<br>' +
                '• Jenis: Masuk / Keluar<br>• Kategori: Dinas, Undangan, Keterangan, Keputusan, Edaran, Surat Tugas<br>' +
                '• Nomor surat di-generate otomatis oleh sistem<br><br>' +
                '📋 <strong>Disposisi Surat:</strong> Sidebar → Disposisi Surat<br>• Tindak lanjut surat masuk oleh admin<br><br>' +
                '🤖 <strong>AI Dokumen:</strong> Sidebar → Word & AI → Buat Dokumen AI<br>• Generate draft surat otomatis menggunakan Gemini AI<br>' +
                '• Jenis: surat tugas, undangan, edaran, SK, notulen, proposal<br><br>' +
                'Silakan tentukan jenis surat yang ingin dibuat!';
        }

        // ── Kehadiran & Absensi ──
        else if (lower.includes('kehadiran') || lower.includes('absen') || lower.includes('hadir') || lower.includes('clock')) {
            response = 'Fitur <strong>Kehadiran / Absensi</strong> lengkap:<br><br>' +
                '⏰ <strong>Clock In / Out:</strong><br>' +
                '• Sidebar → Absensi → Absen Hari Ini<br>' +
                '• Klik Clock In di pagi hari → GPS + foto selfie otomatis<br>' +
                '• Klik Clock Out di sore/malam<br>' +
                '• Status: <span style="color:#10b981">Hadir</span> (tepat waktu) / <span style="color:#f59e0b">Terlambat</span> (lewat toleransi)<br><br>' +
                '⚙️ <strong>Pengaturan Absensi (Admin):</strong><br>' +
                '• Sidebar → Kehadiran → Pengaturan Absensi<br>' +
                '• Atur: jam masuk/pulang, toleransi terlambat, koordinat GPS, jarak maks<br><br>' +
                '📊 <strong>Rekap Kehadiran:</strong><br>' +
                '• Sidebar → Kehadiran → Rekap Kehadiran<br>' +
                '• Filter per tanggal, bulan, staff tertentu<br>' +
                '• Export ke PDF/Excel';
        }

        // ── Izin & Cuti ──
        else if (lower.includes('izin') || lower.includes('cuti') || lower.includes('sakit')) {
            response = 'Fitur <strong>Pengajuan Izin / Cuti</strong>:<br><br>' +
                '📝 <strong>Mengajukan Izin (Staff):</strong><br>' +
                '• Sidebar → Pengajuan Izin → Ajukan Izin Baru<br>' +
                '• Tipe: Cuti / Izin / Sakit<br>' +
                '• Isi tanggal mulai & selesai, alasan, lampiran (opsional)<br><br>' +
                '✅ <strong>Persetujuan (Admin):</strong><br>' +
                '• Sidebar → Pengajuan Izin → Menunggu Persetujuan<br>' +
                '• Setujui / Tolak → Staff dapat notifikasi otomatis<br><br>' +
                '📊 Status: <span style="color:#f59e0b">Pending</span> → <span style="color:#10b981">Approved</span> / <span style="color:#ef4444">Rejected</span>';
        }

        // ── Evaluasi Kinerja ──
        else if (lower.includes('kinerja') || lower.includes('evaluasi') || lower.includes('pkg') || lower.includes('skp') || lower.includes('bkd') || lower.includes('star')) {
            response = 'Modul <strong>Evaluasi Kinerja</strong> lengkap:<br><br>' +
                '📋 <strong>PKG / BKD / SKP:</strong><br>' +
                '• Sidebar → Evaluasi & Penilaian → PKG/BKD/SKP<br>' +
                '• PKG = Penilaian Kinerja Guru<br>' +
                '• BKD = Beban Kerja Dosen/Guru<br>' +
                '• SKP = Sasaran Kinerja Pegawai<br><br>' +
                '⭐ <strong>Metode STAR:</strong><br>' +
                '• <strong>S</strong>ituation → <strong>T</strong>ask → <strong>A</strong>ction → <strong>R</strong>esult<br>' +
                '• Ditambah Refleksi & Tindak Lanjut<br><br>' +
                '🎓 <strong>Asesmen P5:</strong><br>' +
                '• Profil Pelajar Pancasila<br>' +
                '• Dimensi: Beriman, Mandiri, Gotong Royong, Berkebinekaan Global, Bernalar Kritis, Kreatif<br><br>' +
                'Semua di menu: <strong>Evaluasi & Penilaian</strong>';
        }

        // ── Inventaris ──
        else if (lower.includes('inventaris') || lower.includes('barang') || lower.includes('sarana') || lower.includes('kerusakan')) {
            response = 'Fitur <strong>Inventaris / Sarana Prasarana</strong>:<br><br>' +
                '📦 <strong>Tambah Barang (Admin):</strong><br>' +
                '• Sidebar → Inventaris → Tambah Barang<br>' +
                '• Kategori: Mebeulair (MBL), Elektronik (ELK), Buku (BKU), Alat Lab (LAB), Olahraga (OLR), Lainnya (LNY)<br>' +
                '• Kode otomatis: ELK-0001, BKU-0015, dll<br><br>' +
                '📊 <strong>Kondisi:</strong> 🟢 Baik | 🟡 Rusak Ringan | 🔴 Rusak Berat<br><br>' +
                '🔧 <strong>Laporkan Kerusakan (Staff):</strong><br>' +
                '• Pilih barang → Laporkan Kerusakan → isi deskripsi + foto<br>' +
                '• Admin mendapat notifikasi otomatis';
        }

        // ── Keuangan & Anggaran ──
        else if (lower.includes('keuangan') || lower.includes('anggaran') || lower.includes('rkas') || lower.includes('transaksi')) {
            response = 'Modul <strong>Keuangan & Anggaran</strong>:<br><br>' +
                '💰 <strong>Transaksi:</strong><br>' +
                '• Sidebar → Keuangan → Tambah Transaksi<br>' +
                '• Jenis: Pemasukan / Pengeluaran<br>' +
                '• Kode otomatis: IN-202603-0001, OUT-202603-0003<br><br>' +
                '📋 <strong>RKAS / Anggaran:</strong><br>' +
                '• Sidebar → Keuangan → RKAS / Anggaran<br>' +
                '• Otomatis hitung: Sisa = Total - Terpakai<br>' +
                '• Persentase terpakai<br><br>' +
                '✅ <strong>Verifikasi:</strong> Admin memverifikasi setiap transaksi';
        }

        // ── Siswa & Kesiswaan ──
        else if (lower.includes('siswa') || lower.includes('kesiswaan') || lower.includes('prestasi') || lower.includes('pelanggaran')) {
            response = 'Fitur <strong>Kesiswaan</strong>:<br><br>' +
                '👤 <strong>Data Siswa:</strong> Sidebar → Kesiswaan → Tambah/Lihat Siswa<br>' +
                '• Data: NIS, NISN, Nama, Kelas, dll<br>' +
                '• Status: Aktif, Mutasi Masuk/Keluar, Lulus, Drop Out<br><br>' +
                '🏆 <strong>Prestasi:</strong> Catat judul, level, jenis, penyelenggara, hasil<br>' +
                '⚠️ <strong>Pelanggaran:</strong> Catat jenis, deskripsi, tindakan<br><br>' +
                'Staff dapat melihat semua data siswa & detail lengkap';
        }

        // ── Kurikulum ──
        else if (lower.includes('kurikulum') || lower.includes('rpp') || lower.includes('silabus') || lower.includes('modul ajar')) {
            response = 'Fitur <strong>Kurikulum & Akademik</strong>:<br><br>' +
                '📚 <strong>Tipe Dokumen:</strong><br>' +
                '• Kalender Pendidikan, Jadwal Pelajaran<br>' +
                '• RPP / Modul Ajar, Silabus<br>' +
                '• Kisi-kisi Soal, Analisis Butir Soal<br>' +
                '• Berita Acara Ujian, Daftar Nilai<br>' +
                '• Rekap Nilai, Leger, Raport<br><br>' +
                '📤 Admin & Staff dapat upload dokumen kurikulum<br>' +
                'Buka di: <strong>Sidebar → Kurikulum</strong>';
        }

        // ── Akreditasi & EDS ──
        else if (lower.includes('akreditasi') || lower.includes('eds') || lower.includes('standar')) {
            response = 'Modul <strong>Akreditasi & EDS</strong>:<br><br>' +
                '📋 <strong>8 Standar Pendidikan:</strong><br>' +
                '1. Standar Isi<br>2. Standar Proses<br>3. Standar Kompetensi Lulusan<br>4. Standar Pendidik<br>' +
                '5. Standar Sarpras<br>6. Standar Pengelolaan<br>7. Standar Pembiayaan<br>8. Standar Penilaian<br><br>' +
                '📊 <strong>Evaluasi Diri Sekolah (EDS):</strong><br>' +
                '• Isi: Tahun, Aspek, Kondisi Saat Ini, Target, Program Tindak Lanjut<br><br>' +
                'Buka di: <strong>Sidebar → Akreditasi</strong>';
        }

        // ── Agenda & Event ──
        else if (lower.includes('agenda') || lower.includes('event') || lower.includes('acara') || lower.includes('rapat')) {
            response = 'Fitur <strong>Agenda & Event</strong>:<br><br>' +
                '📅 <strong>Buat Event (Admin):</strong><br>' +
                '• Sidebar → Agenda & Event → Buat Event Baru<br>' +
                '• Tipe: Rapat, Kegiatan, Upacara, Pelatihan, Lainnya<br>' +
                '• Isi: Judul, Tanggal, Waktu, Lokasi<br>' +
                '• Staff mendapat notifikasi otomatis<br><br>' +
                '📊 <strong>Status:</strong> 🔵 Upcoming | 🟢 Ongoing | ⚫ Completed | 🔴 Cancelled';
        }

        // ── Notifikasi ──
        else if (lower.includes('notifikasi') || lower.includes('pengumuman') || lower.includes('pemberitahuan')) {
            response = 'Sistem <strong>Notifikasi & Pengumuman</strong>:<br><br>' +
                '📢 <strong>Kirim Pengumuman (Admin):</strong> Sidebar → Notifikasi → Kirim<br>' +
                '🔔 <strong>Lihat Notifikasi:</strong> Klik ikon 🔔 di header<br><br>' +
                '📋 <strong>Tipe Notifikasi:</strong><br>' +
                '• 🟢 Kehadiran<br>• 🔵 Izin/Cuti<br>• 🔵 Event<br>• 🟡 Laporan<br>• 🔴 Sistem<br>• ⚫ Pengumuman<br><br>' +
                '🔄 <strong>Notifikasi Otomatis:</strong> izin disetujui/ditolak, event baru, status surat berubah, kerusakan inventaris, pengingat jatuh tempo';
        }

        // ── Backup & Google Drive ──
        else if (lower.includes('backup') || lower.includes('google drive') || lower.includes('cadangan') || lower.includes('ekspor')) {
            response = 'Fitur <strong>Backup & Google Drive</strong>:<br><br>' +
                '☁️ <strong>Backup Otomatis:</strong><br>' +
                '• Database (MySQL dump) + File uploads → kompres .zip → upload ke Google Drive<br>' +
                '• Maks 5 backup di Drive (lama auto dihapus)<br><br>' +
                '⚙️ <strong>Setup Google Drive:</strong><br>' +
                '1. Buat project di Google Cloud Console<br>' +
                '2. Aktifkan Google Drive API<br>' +
                '3. Buat OAuth → download credentials.json → simpan di storage/app/google/<br>' +
                '4. Set di .env: GOOGLE_DRIVE_BACKUP_FOLDER=TU_Admin_Backup<br><br>' +
                '📤 <strong>Ekspor Data:</strong> Sidebar → Sistem → Ekspor & Backup<br>' +
                '💡 Jalankan: <code>php artisan backup:google-drive</code>';
        }

        // ── AI / Gemini ──
        else if (lower.includes('gemini') || lower.includes('api key') || lower.includes('ai') && (lower.includes('setup') || lower.includes('config') || lower.includes('konfigurasi'))) {
            response = 'Konfigurasi <strong>Gemini AI</strong>:<br><br>' +
                '🔑 <strong>Dapatkan API Key:</strong><br>' +
                '1. Buka <strong>aistudio.google.com/apikey</strong><br>' +
                '2. Login Google → Create API Key → Salin<br><br>' +
                '⚙️ <strong>Tambah ke .env:</strong><br>' +
                '<code>GEMINI_API_KEY=your-key-here</code><br>' +
                '<code>GEMINI_MODEL=gemini-2.0-flash</code><br><br>' +
                '📋 <strong>Model Tersedia:</strong><br>' +
                '• gemini-2.0-flash ⚡ (default, gratis)<br>' +
                '• gemini-2.0-flash-lite ⚡⚡ (tercepat)<br>' +
                '• gemini-2.5-pro (terbaik, terbatas)<br><br>' +
                '💡 Tier gratis: 15 RPM, 1.500 RPD — cukup untuk sekolah';
        }

        // ── Fitur Sistem (lengkap) ──
        else if (lower.includes('fitur') && (lower.includes('sistem') || lower.includes('semua') || lower.includes('apa saja') || lower.includes('lengkap'))) {
            response = 'Fitur lengkap <strong>SIMPEG-SMART</strong>:<br><br>' +
                '👥 <strong>SDM:</strong> Manajemen Pegawai, Riwayat Jabatan, SKP/PKG/BKD<br>' +
                '⏰ <strong>Kehadiran:</strong> Absensi GPS+Selfie, Rekap, Pengaturan<br>' +
                '📝 <strong>Izin & Cuti:</strong> Pengajuan, Persetujuan, Notifikasi otomatis<br>' +
                '📧 <strong>Surat:</strong> Masuk/Keluar, Disposisi, Nomor otomatis<br>' +
                '📁 <strong>Dokumen:</strong> Upload, Arsip, Template AI<br>' +
                '📊 <strong>Laporan:</strong> Kehadiran, Keuangan, Inventaris, Ekspor<br>' +
                '📚 <strong>Kurikulum:</strong> RPP, Silabus, Nilai, Raport<br>' +
                '👨‍🎓 <strong>Kesiswaan:</strong> Data Siswa, Prestasi, Pelanggaran<br>' +
                '📦 <strong>Inventaris:</strong> Barang, Kerusakan, Kode otomatis<br>' +
                '💰 <strong>Keuangan:</strong> Transaksi, RKAS, Verifikasi<br>' +
                '⭐ <strong>Evaluasi:</strong> PKG, STAR, P5, Bukti Fisik<br>' +
                '📋 <strong>Akreditasi:</strong> 8 Standar, EDS<br>' +
                '📅 <strong>Agenda:</strong> Event, Rapat, Pengingat<br>' +
                '🔔 <strong>Notifikasi:</strong> Push, Email, Popup, Pengumuman<br>' +
                '🤖 <strong>AI:</strong> Dokumen AI (Gemini), SIMPEG-AI, Chat<br>' +
                '☁️ <strong>Backup:</strong> Google Drive otomatis<br>' +
                '🌐 <strong>Publik:</strong> Berita, Profil Sekolah, Saran Pengunjung';
        }

        // ── Panduan Penggunaan ──
        else if (lower.includes('panduan') || lower.includes('guide') || lower.includes('tutorial') || lower.includes('cara pakai')) {
            response = 'Panduan lengkap <strong>SIMPEG-SMART</strong>:<br><br>' +
                '📖 <strong>Panduan Tersedia:</strong><br>' +
                '• <strong>Panduan Penggunaan</strong> — cara login, dashboard, semua fitur A-Z<br>' +
                '• <strong>Panduan AI (Gemini)</strong> — setup API key, model, generate dokumen<br>' +
                '• <strong>Panduan Google Drive</strong> — backup otomatis, OAuth setup<br>' +
                '• <strong>Panduan Deployment</strong> — hosting gratis (Railway, Render, Fly.io)<br>' +
                '• <strong>Database & Peran</strong> — 10 peran pengguna, struktur database<br>' +
                '• <strong>Use Case Diagram</strong> — alur interaksi seluruh aktor<br><br>' +
                '💡 <strong>Tips Cepat:</strong><br>' +
                '• Gunakan pencarian sidebar untuk navigasi cepat<br>' +
                '• Klik stat card di beranda untuk langsung ke fitur<br>' +
                '• Buka menu Sistem → Panduan untuk akses lengkap<br><br>' +
                'Tanyakan topik spesifik untuk penjelasan detail!';
        }

        // ── Login & Peran ──
        else if (lower.includes('login') || lower.includes('peran') || lower.includes('role') || lower.includes('akses')) {
            response = '<strong>Login & Peran Pengguna:</strong><br><br>' +
                '🔐 <strong>Cara Login:</strong> Masukkan Email + Password → sistem arahkan ke dashboard sesuai peran<br><br>' +
                '👥 <strong>10 Peran:</strong><br>' +
                '• <strong>Admin (Kepala TU)</strong> — akses penuh seluruh modul<br>' +
                '• <strong>Kepala Sekolah</strong> — supervisor, approve izin/SKP<br>' +
                '• <strong>Staf Kepegawaian (IKI 1)</strong> — SKP, evaluasi guru<br>' +
                '• <strong>Pramu Bakti (IKI 2)</strong> — laporan kerja, kerusakan<br>' +
                '• <strong>Staf Keuangan (IKI 3)</strong> — laporan & dokumen keuangan<br>' +
                '• <strong>Staf Persuratan (IKI 4)</strong> — surat masuk/keluar, arsip<br>' +
                '• <strong>Staf Perpustakaan (IKI 5)</strong> — koleksi & dokumen<br>' +
                '• <strong>Staf Inventaris (IKI 6)</strong> — barang, kerusakan, laporan<br>' +
                '• <strong>Staf Kesiswaan/Kurikulum (IKI 7)</strong> — data siswa, kurikulum, P5<br>' +
                '• <strong>Staf Umum</strong> — akses dasar<br><br>' +
                '⚠️ Akun tidak aktif tidak bisa login. Hubungi Admin untuk reset password.';
        }

        // ── Deployment / Hosting ──
        else if (lower.includes('deploy') || lower.includes('hosting') || lower.includes('online') || lower.includes('railway') || lower.includes('render')) {
            response = 'Panduan <strong>Deployment Gratis</strong>:<br><br>' +
                '⭐ <strong>Railway.app</strong> (Rekomendasi #1):<br>' +
                '• $5 free credit/bulan, MySQL tersedia, Git push otomatis<br>' +
                '• Setup: Daftar → Deploy from GitHub → Tambah MySQL → Set .env<br><br>' +
                '🔵 <strong>Render.com:</strong> PostgreSQL gratis 256MB, auto deploy<br>' +
                '✈️ <strong>Fly.io:</strong> 3 shared VMs, bisa pilih region Asia<br><br>' +
                '📦 <strong>Database Cloud Gratis:</strong><br>' +
                '• Neon.tech (512MB), Supabase (500MB), TiDB Cloud (5GB MySQL)<br><br>' +
                '☁️ <strong>Storage:</strong> Cloudinary (25GB), Backblaze B2 (10GB)';
        }

        // ── Laporan ──
        else if (lower.includes('rekap') || lower.includes('laporan') || lower.includes('report')) {
            response = 'Fitur <strong>Laporan</strong>:<br><br>' +
                '📝 <strong>Buat Laporan (Staff):</strong><br>' +
                '• Sidebar → Laporan → Buat Laporan<br>' +
                '• Kategori: Surat, Inventaris, Keuangan, Kegiatan, Lainnya<br>' +
                '• Prioritas: Low / Medium / High<br><br>' +
                '📊 <strong>Alur:</strong> Draft → Submitted → Reviewed → Completed<br><br>' +
                '📥 <strong>Ekspor:</strong><br>' +
                '• Laporan Kehadiran — Kehadiran → Rekap<br>' +
                '• Laporan Keuangan — Laporan → Keuangan<br>' +
                '• Laporan Inventaris — Laporan → Inventaris<br>' +
                '• Download PDF/Excel via Ekspor & Backup';
        }

        // ── Bantuan ──
        else if (lower.includes('bantuan') || lower.includes('help') || lower.includes('cara') || lower.includes('bagaimana')) {
            response = 'Saya siap membantu! Berikut yang bisa saya jelaskan:<br><br>' +
                '📖 <strong>Fitur:</strong> Semua modul sistem (surat, kehadiran, keuangan, dll)<br>' +
                '⚙️ <strong>Pengaturan:</strong> Konfigurasi absensi, AI, backup<br>' +
                '📋 <strong>Alur Kerja:</strong> Proses surat, izin, laporan, evaluasi<br>' +
                '🔐 <strong>Peran:</strong> 10 peran pengguna & hak akses<br>' +
                '🚀 <strong>Deployment:</strong> Hosting gratis untuk lomba<br>' +
                '🤖 <strong>AI:</strong> Setup Gemini, generate dokumen<br>' +
                '☁️ <strong>Backup:</strong> Google Drive, ekspor data<br><br>' +
                '💡 <strong>Tips:</strong> Ketik topik spesifik seperti "kehadiran", "surat", "inventaris" untuk jawaban detail!';
        }

        // ── Pengingat / Reminder ──
        else if (lower.includes('pengingat') || lower.includes('reminder') || lower.includes('tenggat')) {
            response = 'Fitur <strong>Pengingat (Reminder)</strong>:<br><br>' +
                '⏰ <strong>Buat Pengingat (Admin):</strong><br>' +
                '• Sidebar → Pengingat → Buat Pengingat Baru<br>' +
                '• Isi: Judul, Deskripsi, Tenggat Waktu<br>' +
                '• Pengingat yang overdue muncul di dashboard<br><br>' +
                '🔔 Notifikasi otomatis saat pengingat jatuh tempo';
        }

        // ── Dokumen & Arsip ──
        else if (lower.includes('dokumen') || lower.includes('arsip') || lower.includes('upload') || lower.includes('file')) {
            response = 'Fitur <strong>Dokumen & Arsip</strong>:<br><br>' +
                '📤 <strong>Upload:</strong> Sidebar → Dokumen & Arsip → Upload Dokumen<br>' +
                '• Format: PDF, DOC, XLS, PPT, JPG, PNG<br>' +
                '• Kategori: Surat Menyurat, Keuangan, Kepegawaian, Administrasi<br><br>' +
                '📋 <strong>Ikon per format:</strong><br>' +
                '• 📕 PDF | 📘 DOC | 📗 XLS | 📙 PPT | 🖼️ Gambar<br><br>' +
                '🤖 <strong>Dokumen AI:</strong> Sidebar → Word & AI → Buat Dokumen AI<br>' +
                '• Generate otomatis via Gemini AI';
        }

        // ── Fallback ──
        else {
            response = 'Terima kasih atas pertanyaan Anda. Saya memproses: <em>"' + escapeHtml(userText) + '"</em><br><br>' +
                'Saya bisa menjelaskan tentang:<br>' +
                '• <strong>Fitur sistem</strong> — surat, kehadiran, keuangan, inventaris, dll<br>' +
                '• <strong>Pengaturan</strong> — absensi, AI (Gemini), backup (Google Drive)<br>' +
                '• <strong>Alur kerja</strong> — proses surat, izin, laporan, evaluasi<br>' +
                '• <strong>Peran pengguna</strong> — 10 role & hak akses masing-masing<br>' +
                '• <strong>Deployment</strong> — hosting gratis untuk lomba<br><br>' +
                'Coba ketik kata kunci seperti <strong>"kehadiran"</strong>, <strong>"surat"</strong>, <strong>"inventaris"</strong>, atau <strong>"panduan"</strong> 🤖';
        }

        setTimeout(() => addAiMessage(response, false), 800 + Math.random() * 500);
    }

    function handleAiSend() {
        if (!aiInput) return;
        const text = aiInput.value.trim();
        if (!text) return;
        addAiMessage(text, true);
        aiInput.value = '';
        aiInput.style.height = 'auto';

        const typing = document.createElement('div');
        typing.className = 'ai-msg bot';
        typing.id = 'aiTyping';
        typing.innerHTML = '<div class="ai-msg-avatar"><div class="ai-3d-icon"><i class="bi bi-robot"></i></div></div><div class="ai-msg-bubble"><em style="opacity:.6;">Mengetik...</em></div>';
        aiMessages.appendChild(typing);
        aiMessages.scrollTop = aiMessages.scrollHeight;

        setTimeout(() => {
            const t = document.getElementById('aiTyping');
            if (t) t.remove();
            simulateAiResponse(text);
        }, 600);
    }

    if (aiSend) aiSend.addEventListener('click', handleAiSend);
    if (aiInput) {
        aiInput.addEventListener('keydown', e => {
            if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); handleAiSend(); }
        });
        aiInput.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 100) + 'px';
        });
    }

    // Quick action buttons
    if (aiQuickActions) {
        aiQuickActions.querySelectorAll('.ai-quick-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const prompt = this.dataset.prompt;
                if (prompt && aiInput) {
                    aiInput.value = prompt;
                    handleAiSend();
                }
            });
        });
    }

    // ── Voice Input (Web Speech API) ──
    if (aiVoice && ('webkitSpeechRecognition' in window || 'SpeechRecognition' in window)) {
        const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
        const recognition = new SpeechRecognition();
        recognition.lang = 'id-ID';
        recognition.continuous = false;
        recognition.interimResults = false;
        let isRecording = false;

        aiVoice.addEventListener('click', function() {
            if (isRecording) { recognition.stop(); }
            else { recognition.start(); this.classList.add('recording'); isRecording = true; }
        });

        recognition.onresult = function(event) {
            const transcript = event.results[0][0].transcript;
            if (aiInput) { aiInput.value = transcript; handleAiSend(); }
        };
        recognition.onend = function() { aiVoice.classList.remove('recording'); isRecording = false; };
        recognition.onerror = function() { aiVoice.classList.remove('recording'); isRecording = false; };
    } else if (aiVoice) {
        aiVoice.title = 'Speech recognition tidak didukung browser ini';
        aiVoice.style.opacity = '.4';
        aiVoice.style.cursor = 'not-allowed';
    }

    // ── Close AI on Escape ──
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            hideAi();
            closeDrawer();
        }
    });

});
</script>
