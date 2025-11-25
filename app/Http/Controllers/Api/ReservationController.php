<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\User;
use App\Services\ReservationService;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    protected ReservationService $reservationService;

    public function __construct(ReservationService $reservationService)
    {
        $this->reservationService = $reservationService;
        // MODE DEV / TEST:
        // Tidak pakai middleware auth agar bisa dipanggil dari test.http tanpa login.
    }

    /**
     * List semua reservasi (bisa difilter via query string).
     *
     * Query params optional:
     * - status=confirmed/pending/completed/cancelled
     * - studio_id=1
     * - date_from=2025-11-25
     * - date_to=2025-11-26
     */
    public function index(Request $request)
    {
        // TIDAK pakai $request->user(), karena kita mode tanpa auth
        $query = Reservation::with(['studio', 'addons']);

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        if ($studioId = $request->query('studio_id')) {
            $query->where('studio_id', $studioId);
        }

        if ($from = $request->query('date_from')) {
            $query->whereDate('start_time', '>=', $from);
        }

        if ($to = $request->query('date_to')) {
            $query->whereDate('start_time', '<=', $to);
        }

        $reservations = $query
            ->orderByDesc('start_time')
            ->get();

        return response()->json([
            'data' => $reservations,
        ]);
    }

    /**
     * Helper: ambil user “dummy” untuk mode tanpa auth.
     * - Prioritas: user dengan role Penyewa
     * - Kalau tidak ada, ambil user pertama di tabel users
     */
    protected function resolveUserForTesting(): ?User
    {
        $user = User::whereHas('role', function ($q) {
            $q->where('name', 'Penyewa');
        })->first();

        if (! $user) {
            $user = User::first();
        }

        return $user;
    }

    /**
     * Membuat reservasi baru.
     * Body minimal:
     * {
     *   "studio_id": 1,
     *   "start_time": "2025-11-26 10:00:00",
     *   "end_time": "2025-11-26 11:30:00",
     *   "addons": { "1": 2 }
     * }
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'studio_id'  => ['required', 'integer', 'exists:studios,id'],
            'start_time' => ['required', 'date'],
            'end_time'   => ['required', 'date'],
            'addons'     => ['nullable', 'array'],
        ]);

        // Mode tanpa auth: pakai user dummy
        $user = $this->resolveUserForTesting();

        if (! $user) {
            return response()->json([
                'message' => 'Tidak ada user di database untuk membuat reservasi (mode tanpa auth). Tambahkan minimal 1 user dulu.',
            ], 500);
        }

        $reservation = $this->reservationService->createReservation($data, $user);
        $reservation->load(['studio', 'addons']);

        return response()->json([
            'message'     => 'Reservasi berhasil dibuat.',
            'reservation' => $reservation,
        ], 201);
    }

    /**
     * Update jam dan addons reservasi.
     * Body mirip dengan store, tapi semua field opsional.
     */
    public function update(Request $request, Reservation $reservation)
    {
        $data = $request->validate([
            'start_time' => ['sometimes', 'required', 'date'],
            'end_time'   => ['sometimes', 'required', 'date'],
            'addons'     => ['nullable', 'array'],
        ]);

        $updated = $this->reservationService->updateReservation($data, $reservation);
        $updated->load(['studio', 'addons']);

        return response()->json([
            'message'     => 'Reservasi berhasil diupdate.',
            'reservation' => $updated,
        ]);
    }

    /**
     * Cancel reservasi (soft cancel, status -> cancelled).
     */
    public function cancel(Reservation $reservation)
    {
        $cancelled = $this->reservationService->cancelReservation($reservation);
        $cancelled->load(['studio', 'addons']);

        return response()->json([
            'message'     => 'Reservasi berhasil dibatalkan.',
            'reservation' => $cancelled,
        ]);
    }

    /**
     * Check-in reservasi menggunakan kode check-in.
     * Body:
     * {
     *   "code": "CHK-XXXX"
     * }
     */
    public function checkin(Request $request, Reservation $reservation)
    {
        $data = $request->validate([
            'code' => ['required', 'string'],
        ]);

        $checkedIn = $this->reservationService->checkInReservation($reservation, $data['code']);
        $checkedIn->load(['studio', 'addons']);

        return response()->json([
            'message'     => 'Check-in berhasil.',
            'reservation' => $checkedIn,
        ]);
    }

    /**
     * Hard delete reservasi (opsional, untuk cleaning).
     */
    public function destroy(Reservation $reservation)
    {
        $reservation->delete();

        return response()->json([
            'message' => 'Reservasi berhasil dihapus.',
        ]);
    }
}
