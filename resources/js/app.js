import './bootstrap';

document.addEventListener('DOMContentLoaded', () => {
    const bookingForm = document.getElementById('booking-form');
    const bookingCard = document.getElementById('booking-card');

    if (bookingForm && bookingCard) {
        const studioId = bookingCard.dataset.studioId;
        const messageEl = document.getElementById('booking-message');

        bookingForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            messageEl.className = 'text-sm mt-2 text-gray-600';
            messageEl.textContent = 'Memproses...';

            const formData = new FormData(bookingForm);
            const date = formData.get('date');          // yyyy-mm-dd
            const startTime = formData.get('start_time'); // HH:MM
            const duration = parseInt(formData.get('duration'), 10);

            if (!date || !startTime || !duration) {
                messageEl.className = 'text-sm mt-2 text-red-600';
                messageEl.textContent = 'Tanggal, jam mulai, dan durasi wajib diisi.';
                return;
            }

            const startDateTime = `${date} ${startTime}:00`;
            const endDateTime = computeEndTime(startDateTime, duration);

            const addons = [];
            for (const [key, value] of formData.entries()) {
                const match = key.match(/^addons\[(\d+)]\[enabled]$/);
                if (match && value === '1') {
                    const addonId = match[1];
                    const qty = formData.get(`addons[${addonId}][qty]`) || 1;
                    addons.push({ addon_id: parseInt(addonId, 10), qty: parseInt(qty, 10) });
                }
            }

            try {
                const response = await fetch('/api/reservations', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        studio_id: parseInt(studioId, 10),
                        start_time: startDateTime,
                        end_time: endDateTime,
                        addons: addons,
                    })
                });

                const data = await response.json();

                if (!response.ok) {
                    console.error('Error response:', data);
                    messageEl.className = 'text-sm mt-2 text-red-600';
                    messageEl.textContent =
                        data.message || 'Gagal membuat reservasi. Periksa input atau jadwal.';
                    return;
                }

                messageEl.className = 'text-sm mt-2 text-green-600';
                messageEl.textContent =
                    'Reservasi berhasil dibuat. Silakan cek di menu "Reservasi Saya".';

                // optional: redirect otomatis
                // setTimeout(() => window.location.href = '/my/reservations', 1500);
            } catch (err) {
                console.error(err);
                messageEl.className = 'text-sm mt-2 text-red-600';
                messageEl.textContent = 'Terjadi kesalahan jaringan.';
            }
        });
    }
});

function computeEndTime(startDateTime, durationMinutes) {
    // startDateTime: "YYYY-MM-DD HH:MM:SS"
    const [datePart, timePart] = startDateTime.split(' ');
    const [year, month, day] = datePart.split('-').map(Number);
    const [hour, minute, second] = timePart.split(':').map(Number);
    const d = new Date(year, month - 1, day, hour, minute, second || 0);
    d.setMinutes(d.getMinutes() + durationMinutes);

    const pad = (n) => String(n).padStart(2, '0');

    const result =
        `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())} ` +
        `${pad(d.getHours())}:${pad(d.getMinutes())}:${pad(d.getSeconds())}`;

    return result;
}
