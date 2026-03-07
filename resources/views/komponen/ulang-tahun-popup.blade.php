{{-- Birthday Popup Component --}}
{{-- Tampil satu-per-satu. Jika user sendiri ultah → ucapan otomatis dari sistem. --}}

@if(isset($birthdayUsers) && $birthdayUsers->count() > 0)
@php
    $birthdayData = $birthdayUsers->map(fn($u) => [
        'id' => $u->id,
        'nama' => $u->nama,
        'foto' => $u->foto,
        'jabatan' => $u->jabatan ?? $u->peran,
    ]);
    $routePrefix = auth()->user()->getRoutePrefix();
@endphp
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.3/dist/confetti.browser.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const todayKey = 'birthday_popup_shown_' + new Date().toISOString().slice(0, 10);
    if (localStorage.getItem(todayKey)) return;

    const currentUserId = {{ auth()->id() }};
    const allBirthdayUsers = @json($birthdayData);

    if (allBirthdayUsers.length === 0) return;
    localStorage.setItem(todayKey, '1');
    const greetingUrl = "{{ route($routePrefix . '.ulang-tahun.ucapan') }}";
    const csrfToken = "{{ csrf_token() }}";

    // Pisahkan: user sendiri vs orang lain
    const selfBirthday = allBirthdayUsers.find(u => u.id === currentUserId);
    const otherBirthdays = allBirthdayUsers.filter(u => u.id !== currentUserId);

    // === Fungsi confetti ===
    function fireConfetti() {
        const duration = 4000;
        const animationEnd = Date.now() + duration;
        const defaults = { startVelocity: 30, spread: 360, ticks: 60, zIndex: 99999 };
        function rnd(min, max) { return Math.random() * (max - min) + min; }
        const interval = setInterval(function() {
            if (Date.now() > animationEnd) return clearInterval(interval);
            const pc = 50 * ((animationEnd - Date.now()) / duration);
            const colors = ['#f59e0b','#ef4444','#3b82f6','#10b981','#8b5cf6','#ec4899'];
            confetti(Object.assign({}, defaults, { particleCount: pc, origin: { x: rnd(0.1,0.3), y: Math.random()-0.2 }, colors }));
            confetti(Object.assign({}, defaults, { particleCount: pc, origin: { x: rnd(0.7,0.9), y: Math.random()-0.2 }, colors }));
        }, 250);
    }

    // === Helper: buat avatar HTML ===
    function avatarHtml(user, size) {
        const url = user.foto
            ? '{{ asset("storage") }}/' + user.foto
            : 'https://ui-avatars.com/api/?name=' + encodeURIComponent(user.nama) + '&background=f59e0b&color=fff&size=' + size;
        return `<img src="${url}" alt="${user.nama}" style="width:${size}px;height:${size}px;border-radius:50%;border:3px solid #f59e0b;object-fit:cover;">`;
    }

    // === 1. Popup untuk diri sendiri (ucapan otomatis dari sistem) ===
    async function showSelfBirthday() {
        if (!selfBirthday) return;
        await Swal.fire({
            html: `
                <div style="text-align:center;padding:10px 0;">
                    <div style="font-size:72px;margin-bottom:8px;animation:bdBounce 1s infinite;">🎂</div>
                    <h2 style="color:#1e293b;font-size:22px;font-weight:700;margin-bottom:6px;">
                        🎉 Selamat Ulang Tahun, ${selfBirthday.nama}! 🎉
                    </h2>
                    <p style="color:#64748b;font-size:14px;margin-bottom:16px;">
                        Semoga sehat selalu, sukses, dan bahagia.<br>
                        Terima kasih atas dedikasi Anda! 🙏
                    </p>
                    <div style="display:flex;align-items:center;justify-content:center;gap:12px;padding:16px;background:rgba(245,158,11,0.1);border-radius:16px;">
                        ${avatarHtml(selfBirthday, 80)}
                        <div style="text-align:left;">
                            <strong style="font-size:16px;color:#1e293b;">${selfBirthday.nama}</strong>
                            <br><small style="color:#64748b;">${selfBirthday.jabatan}</small>
                        </div>
                    </div>
                    <p style="color:#94a3b8;font-size:12px;margin-top:12px;font-style:italic;">
                        — Sistem SIMPEG-SMART, SMA Negeri 2 Jember
                    </p>
                </div>
                <style>@keyframes bdBounce{0%,20%,50%,80%,100%{transform:translateY(0)}40%{transform:translateY(-15px)}60%{transform:translateY(-8px)}}</style>
            `,
            confirmButtonText: 'Terima Kasih! 🎁',
            confirmButtonColor: '#f59e0b',
            width: '480px',
            backdrop: 'rgba(0,0,0,0.6)',
            allowOutsideClick: false,
            didOpen: () => fireConfetti()
        });
    }

    // === 2. Popup satu-per-satu untuk orang lain ===
    async function showOtherBirthday(user, index, total) {
        const counterText = total > 1 ? `<span style="color:#94a3b8;font-size:12px;">${index + 1} dari ${total}</span>` : '';

        const result = await Swal.fire({
            html: `
                <div style="text-align:center;padding:10px 0;">
                    <div style="font-size:64px;margin-bottom:8px;animation:bdBounce 1s infinite;">🎂</div>
                    ${counterText}
                    <h2 style="color:#1e293b;font-size:20px;font-weight:700;margin:8px 0 4px;">
                        🎉 Selamat Ulang Tahun! 🎉
                    </h2>
                    <p style="color:#64748b;font-size:13px;margin-bottom:16px;">
                        Hari ini ada yang berulang tahun di kantor kita!
                    </p>
                    <div style="display:flex;align-items:center;gap:14px;padding:14px 18px;background:rgba(245,158,11,0.1);border-radius:16px;margin-bottom:16px;">
                        ${avatarHtml(user, 64)}
                        <div style="text-align:left;">
                            <strong style="font-size:16px;color:#1e293b;">${user.nama}</strong>
                            <br><small style="color:#64748b;">${user.jabatan}</small>
                        </div>
                    </div>
                    <hr style="border:none;border-top:1px solid #e5e7eb;margin:12px 0;">
                    <p style="color:#374151;font-size:14px;font-weight:600;margin-bottom:8px;">
                        ✉️ Kirim Ucapan Selamat
                    </p>
                    <textarea id="pesan_birthday" rows="3" placeholder="Tulis ucapan selamat ulang tahun..."
                        style="width:100%;border:2px solid #e5e7eb;border-radius:10px;padding:10px 14px;font-size:13px;resize:none;transition:border-color 0.2s;"
                        onfocus="this.style.borderColor='#f59e0b'" onblur="this.style.borderColor='#e5e7eb'"></textarea>
                </div>
                <style>@keyframes bdBounce{0%,20%,50%,80%,100%{transform:translateY(0)}40%{transform:translateY(-15px)}60%{transform:translateY(-8px)}}</style>
            `,
            showConfirmButton: true,
            confirmButtonText: '🎁 Kirim Ucapan',
            showCancelButton: true,
            cancelButtonText: total > 1 && index < total - 1 ? 'Lewati →' : 'Tutup',
            confirmButtonColor: '#f59e0b',
            cancelButtonColor: '#94a3b8',
            width: '460px',
            backdrop: 'rgba(0,0,0,0.6)',
            allowOutsideClick: false,
            didOpen: () => { if (index === 0) fireConfetti(); }
        });

        if (result.isConfirmed) {
            const pesan = document.getElementById('pesan_birthday')?.value?.trim();
            if (pesan) {
                try {
                    const res = await fetch(greetingUrl, {
                        method: 'POST',
                        headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept':'application/json' },
                        body: JSON.stringify({ penerima_id: user.id, pesan: pesan })
                    });
                    const data = await res.json();
                    if (data.success) {
                        await Swal.fire({
                            icon: 'success', title: 'Berhasil!',
                            text: `Ucapan untuk ${user.nama} berhasil dikirim! 🎉`,
                            confirmButtonColor: '#f59e0b', timer: 2000, timerProgressBar: true, showConfirmButton: false
                        });
                    }
                } catch (err) {
                    await Swal.fire({ icon: 'error', title: 'Gagal', text: 'Gagal mengirim ucapan.', confirmButtonColor: '#ef4444', timer: 2000 });
                }
            } else {
                await Swal.fire({
                    icon: 'info', title: 'Info',
                    text: 'Ucapan kosong. Anda bisa kirim nanti dari halaman Ulang Tahun.',
                    confirmButtonColor: '#3b82f6', timer: 2000, timerProgressBar: true, showConfirmButton: false
                });
            }
        }
    }

    // === Jalankan semua popup berurutan ===
    (async function() {
        await showSelfBirthday();
        for (let i = 0; i < otherBirthdays.length; i++) {
            await showOtherBirthday(otherBirthdays[i], i, otherBirthdays.length);
        }
    })();
});
</script>
@endif
