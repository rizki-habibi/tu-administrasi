{{-- Birthday Popup Component --}}
{{-- Include this partial in your dashboard layout. Pass $birthdayUsers and $routePrefix --}}

@if(isset($birthdayUsers) && $birthdayUsers->count() > 0)
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.3/dist/confetti.browser.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const todayKey = 'birthday_popup_shown_' + new Date().toISOString().slice(0, 10);

    // Only show once per day
    if (localStorage.getItem(todayKey)) {
        return;
    }

    const birthdayUsers = @json($birthdayUsers->map(function($u) {
        return ['id' => $u->id, 'nama' => $u->nama, 'foto' => $u->foto];
    }));

    if (birthdayUsers.length === 0) return;

    // Mark as shown
    localStorage.setItem(todayKey, '1');

    @php
        $routePrefix = auth()->user()->getRoutePrefix();
    @endphp
    const greetingUrl = "{{ route($routePrefix . '.ulang-tahun.ucapan') }}";
    const csrfToken = "{{ csrf_token() }}";

    // Build the user list HTML
    let usersHtml = '';
    birthdayUsers.forEach(function(user, index) {
        const photoUrl = user.foto
            ? '{{ asset("storage") }}/' + user.foto
            : 'https://ui-avatars.com/api/?name=' + encodeURIComponent(user.nama) + '&background=f59e0b&color=fff&size=80';

        usersHtml += `
            <div class="birthday-user-item" style="display: flex; align-items: center; gap: 12px; padding: 10px; margin-bottom: 8px; background: rgba(255,255,255,0.15); border-radius: 12px;">
                <img src="${photoUrl}" alt="${user.nama}" style="width: 50px; height: 50px; border-radius: 50%; border: 3px solid #f59e0b; object-fit: cover;">
                <div style="flex: 1; text-align: left;">
                    <strong style="color: #1e293b; font-size: 15px;">${user.nama}</strong>
                </div>
            </div>
        `;
    });

    // Build greeting form for each user
    let greetingFormsHtml = '';
    birthdayUsers.forEach(function(user, index) {
        greetingFormsHtml += `
            <div class="greeting-form-item" style="margin-bottom: 12px; text-align: left;">
                <label style="font-weight: 600; color: #374151; font-size: 13px; display: block; margin-bottom: 4px;">
                    Ucapan untuk <span style="color: #f59e0b;">${user.nama}</span>:
                </label>
                <textarea id="pesan_${user.id}" rows="2" placeholder="Tulis ucapan selamat ulang tahun..."
                    style="width: 100%; border: 2px solid #e5e7eb; border-radius: 8px; padding: 8px 12px; font-size: 13px; resize: none; transition: border-color 0.2s;"
                    onfocus="this.style.borderColor='#f59e0b'" onblur="this.style.borderColor='#e5e7eb'"></textarea>
                <input type="hidden" id="penerima_${user.id}" value="${user.id}">
            </div>
        `;
    });

    const popupHtml = `
        <div style="text-align: center; padding: 10px 0;">
            <div style="font-size: 64px; margin-bottom: 8px; animation: bounce 1s infinite;">🎂</div>
            <h2 style="color: #1e293b; font-size: 22px; font-weight: 700; margin-bottom: 4px;">
                🎉 Selamat Ulang Tahun! 🎉
            </h2>
            <p style="color: #64748b; font-size: 14px; margin-bottom: 16px;">
                Hari ini ada yang berulang tahun di kantor kita!
            </p>
            <div style="max-height: 150px; overflow-y: auto; margin-bottom: 16px;">
                ${usersHtml}
            </div>
            <hr style="border: none; border-top: 1px solid #e5e7eb; margin: 12px 0;">
            <p style="color: #374151; font-size: 14px; font-weight: 600; margin-bottom: 10px;">
                ✉️ Kirim Ucapan Selamat
            </p>
            <div style="max-height: 200px; overflow-y: auto;">
                ${greetingFormsHtml}
            </div>
        </div>
        <style>
            @keyframes bounce {
                0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
                40% { transform: translateY(-15px); }
                60% { transform: translateY(-8px); }
            }
        </style>
    `;

    // Fire confetti from both sides
    function fireConfetti() {
        const duration = 5000;
        const animationEnd = Date.now() + duration;
        const defaults = { startVelocity: 30, spread: 360, ticks: 60, zIndex: 99999 };

        function randomInRange(min, max) {
            return Math.random() * (max - min) + min;
        }

        const interval = setInterval(function() {
            const timeLeft = animationEnd - Date.now();

            if (timeLeft <= 0) {
                return clearInterval(interval);
            }

            const particleCount = 50 * (timeLeft / duration);

            // Left cannon
            confetti(Object.assign({}, defaults, {
                particleCount,
                origin: { x: randomInRange(0.1, 0.3), y: Math.random() - 0.2 },
                colors: ['#f59e0b', '#ef4444', '#3b82f6', '#10b981', '#8b5cf6', '#ec4899']
            }));

            // Right cannon
            confetti(Object.assign({}, defaults, {
                particleCount,
                origin: { x: randomInRange(0.7, 0.9), y: Math.random() - 0.2 },
                colors: ['#f59e0b', '#ef4444', '#3b82f6', '#10b981', '#8b5cf6', '#ec4899']
            }));
        }, 250);
    }

    // Show SweetAlert2 popup
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            html: popupHtml,
            showConfirmButton: true,
            confirmButtonText: '🎁 Kirim Ucapan',
            showCancelButton: true,
            cancelButtonText: 'Tutup',
            confirmButtonColor: '#f59e0b',
            cancelButtonColor: '#94a3b8',
            width: '500px',
            customClass: {
                popup: 'birthday-popup',
                confirmButton: 'birthday-confirm-btn',
            },
            backdrop: `rgba(0,0,0,0.6)`,
            allowOutsideClick: false,
            didOpen: function() {
                fireConfetti();
            }
        }).then(function(result) {
            if (result.isConfirmed) {
                // Send greetings for each user
                let promises = [];
                birthdayUsers.forEach(function(user) {
                    const pesanEl = document.getElementById('pesan_' + user.id);
                    const pesan = pesanEl ? pesanEl.value.trim() : '';

                    if (pesan) {
                        promises.push(
                            fetch(greetingUrl, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': csrfToken,
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({
                                    penerima_id: user.id,
                                    pesan: pesan
                                })
                            }).then(function(res) { return res.json(); })
                        );
                    }
                });

                if (promises.length > 0) {
                    Promise.all(promises).then(function(results) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Ucapan selamat ulang tahun berhasil dikirim! 🎉',
                            confirmButtonColor: '#f59e0b',
                            timer: 3000,
                            timerProgressBar: true
                        });
                    }).catch(function(err) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops!',
                            text: 'Gagal mengirim ucapan. Silakan coba lagi.',
                            confirmButtonColor: '#ef4444'
                        });
                    });
                } else {
                    Swal.fire({
                        icon: 'info',
                        title: 'Info',
                        text: 'Tidak ada ucapan yang ditulis. Anda bisa mengirim ucapan nanti dari halaman Ulang Tahun.',
                        confirmButtonColor: '#3b82f6',
                        timer: 3000,
                        timerProgressBar: true
                    });
                }
            }
        });
    }
});
</script>
@endif
