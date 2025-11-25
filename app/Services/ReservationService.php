<?php

namespace App\Services;

use App\Models\Reservation;
use App\Models\Studio;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ReservationService
{
    public const MIN_DURATION_MINUTES = 90;
    public const MAX_DURATION_MINUTES = 120;
    public const BUFFER_MINUTES       = 15;
    public const WEEKLY_QUOTA         = 3;

    /**
     * Buat reservasi baru dengan aturan:
     * - durasi 90–120 menit
     * - cek kuota mingguan user
     * - cek bentrok jadwal + buffer
     */
    public function createReservation(array $data, User $user): Reservation
    {
        $studio = Studio::findOrFail($data['studio_id']);

        $start = Carbon::parse($data['start_time']);
        $end   = Carbon::parse($data['end_time']);

        $this->validateDuration($start, $end);
        $this->checkWeeklyQuota($user, $start);
        $this->ensureNoConflict($studio->id, $start, $end);

        return DB::transaction(function () use ($data, $user, $studio, $start, $end) {
            $reservation = Reservation::create([
                'user_id'      => $user->id,
                'studio_id'    => $studio->id,
                'start_time'   => $start,
                'end_time'     => $end,
                'status'       => 'confirmed',
                'checkin_code' => uniqid('CHK-'),
                'total_price'  => $this->calculateTotalPrice($studio, $start, $end, $data['addons'] ?? []),
            ]);

            $this->syncAddons($reservation, $studio, $data['addons'] ?? []);

            return $reservation;
        });
    }

    /**
     * Update jadwal/durasi/addons reservasi yang sudah ada.
     * Aturan:
     * - pakai policy untuk cek siapa yang boleh update
     * - cek durasi & bentrok (pakai ignoreId untuk diri sendiri)
     */
    public function updateReservation(array $data, Reservation $reservation): Reservation
    {
        $studio = $reservation->studio;

        $start = Carbon::parse($data['start_time']);
        $end   = Carbon::parse($data['end_time']);

        $this->validateDuration($start, $end);
        $this->ensureNoConflict($studio->id, $start, $end, $reservation->id);

        return DB::transaction(function () use ($data, $reservation, $studio, $start, $end) {
            $reservation->update([
                'start_time'  => $start,
                'end_time'    => $end,
                'total_price' => $this->calculateTotalPrice($studio, $start, $end, $data['addons'] ?? []),
            ]);

            if (array_key_exists('addons', $data)) {
                $this->syncAddons($reservation, $studio, $data['addons'] ?? []);
            }

            return $reservation->fresh(['studio', 'addons']);
        });
    }

    public function cancelReservation(Reservation $reservation): Reservation
    {
        if (! in_array($reservation->status, ['pending', 'confirmed'], true)) {
            throw ValidationException::withMessages([
                'status' => 'Reservasi tidak bisa dibatalkan pada status ini.',
            ]);
        }

        $reservation->update([
            'status' => 'cancelled',
        ]);

        return $reservation;
    }

    /**
     * Check-in berbasis kode (biasanya dipindai dari QR).
     */
    public function checkInReservation(Reservation $reservation, string $code): Reservation
    {
        if ($reservation->status !== 'confirmed') {
            throw ValidationException::withMessages([
                'status' => 'Reservasi tidak dalam status yang bisa check-in.',
            ]);
        }

        if ($reservation->checkin_code !== $code) {
            throw ValidationException::withMessages([
                'code' => 'Kode check-in tidak valid.',
            ]);
        }

        $now   = now();
        $start = $reservation->start_time;
        $windowStart = $start->copy()->subMinutes(15);
        $windowEnd   = $start->copy()->addMinutes(30);

        if ($now->lt($windowStart) || $now->gt($windowEnd)) {
            throw ValidationException::withMessages([
                'time' => 'Check-in di luar jendela waktu yang diizinkan.',
            ]);
        }

        $reservation->update([
            'status'        => 'completed',
            'checked_in_at' => $now,
        ]);

        return $reservation;
    }

    /**
     * Dipanggil oleh scheduler tiap 5 menit untuk auto-cancel no-show.
     */
    public function autoCancelNoShow(): int
    {
        $threshold = now()->subMinutes(10);

        return Reservation::where('status', 'confirmed')
            ->where('start_time', '<', $threshold)
            ->whereNull('checked_in_at')
            ->update(['status' => 'cancelled']);
    }

    /**
     * Validasi durasi reservasi.
     */
    protected function validateDuration(Carbon $start, Carbon $end): void
    {
        if ($end->lte($start)) {
            throw ValidationException::withMessages([
                'end_time' => 'Waktu selesai harus lebih besar dari waktu mulai.',
            ]);
        }

        $minutes = $start->diffInMinutes($end);

        if ($minutes < self::MIN_DURATION_MINUTES || $minutes > self::MAX_DURATION_MINUTES) {
            throw ValidationException::withMessages([
                'duration' => 'Durasi harus antara ' . self::MIN_DURATION_MINUTES . '–' . self::MAX_DURATION_MINUTES . ' menit.',
            ]);
        }
    }

    /**
     * Cek kuota booking per minggu per user.
     */
    protected function checkWeeklyQuota(User $user, Carbon $date): void
    {
        $startOfWeek = $date->copy()->startOfWeek();
        $endOfWeek   = $date->copy()->endOfWeek();

        $count = Reservation::where('user_id', $user->id)
            ->whereBetween('start_time', [$startOfWeek, $endOfWeek])
            ->whereIn('status', ['pending', 'confirmed', 'completed'])
            ->count();

        if ($count >= self::WEEKLY_QUOTA) {
            throw ValidationException::withMessages([
                'quota' => 'Kuota booking mingguan sudah tercapai.',
            ]);
        }
    }

    /**
     * Cek bentrok jadwal dengan buffer.
     */
    protected function ensureNoConflict(int $studioId, Carbon $start, Carbon $end, ?int $ignoreId = null): void
    {
        $bufferStart = $start->copy()->subMinutes(self::BUFFER_MINUTES);
        $bufferEnd   = $end->copy()->addMinutes(self::BUFFER_MINUTES);

        $hasConflict = Reservation::where('studio_id', $studioId)
            ->whereIn('status', ['pending', 'confirmed'])
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->where(function ($q) use ($bufferStart, $bufferEnd) {
                $q->whereBetween('start_time', [$bufferStart, $bufferEnd])
                    ->orWhereBetween('end_time', [$bufferStart, $bufferEnd])
                    ->orWhere(function ($q2) use ($bufferStart, $bufferEnd) {
                        $q2->where('start_time', '<=', $bufferStart)
                            ->where('end_time', '>=', $bufferEnd);
                    });
            })
            ->exists();

        if ($hasConflict) {
            throw ValidationException::withMessages([
                'conflict' => 'Jadwal bentrok dengan reservasi lain (termasuk buffer).',
            ]);
        }
    }

    /**
     * Hitung total harga (studio + addons).
     */
    protected function calculateTotalPrice(Studio $studio, Carbon $start, Carbon $end, array $addons): float
    {
        $minutes = $start->diffInMinutes($end);
        $hours   = $minutes / 60;
        $total   = $studio->price_per_hour * $hours;

        if (! empty($addons)) {
            $addonModels = $studio->addons()->whereIn('addons.id', array_keys($addons))->get();

            foreach ($addonModels as $addon) {
                $qty = (int) ($addons[$addon->id] ?? 0);
                if ($qty > 0) {
                    $total += $addon->price * $qty;
                }
            }
        }

        return (float) $total;
    }

    /**
     * Sinkronisasi addons ke pivot reservation_addons.
     * --- PENTING ---
     * Di database: kolomnya bernama `qty`, bukan `quantity`.
     */
    protected function syncAddons(Reservation $reservation, Studio $studio, array $addons): void
    {
        $pivotData = [];

        foreach ($addons as $addonId => $qty) {
            $qty = (int) $qty;
            if ($qty <= 0) {
                continue;
            }

            // Pastikan addon memang tersedia di studio tersebut.
            if ($studio->addons()->where('addons.id', $addonId)->exists()) {
                // Sesuaikan dengan nama kolom pivot di DB: `qty`
                $pivotData[$addonId] = ['qty' => $qty];
            }
        }

        $reservation->addons()->sync($pivotData);
    }
}
