<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Notifications\ReservationCancelled;
use App\Notifications\ReservationCreated;
use App\Services\ReservationService;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function __construct(
        protected ReservationService $service
    ) {
        // Pakai guard default "web" (session), bukan sanctum
        $this->middleware('auth');
    }

    /**
     * List semua reservasi (bisa difilter).
     * Cocok untuk endpoint admin / overview.
     */
    public function index(Request $request)
    {
        $query = Reservation::with(['studio', 'user'])
            ->orderByDesc('start_time');

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

        return response()->json(
            $query->paginate(10)
        );
    }

    /**
     * List reservasi milik user yang sedang login.
     * Endpoint: GET /api/me/reservations
     */
    public function myReservations(Request $request)
    {
        $user = $request->user();

        $query = Reservation::with('studio')
            ->where('user_id', $user->id)
            ->orderByDesc('start_time');

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        return response()->json(
            $query->paginate(10)
        );
    }

    /**
     * Membuat reservasi baru.
     * Aturan bisnis (bentrok, kuota, durasi) di-handle di ReservationService.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'studio_id'   => ['required', 'exists:studios,id'],
            'start_time'  => ['required', 'date'],
            'end_time'    => ['required', 'date'],
            'addons'      => ['nullable', 'array'],
            'addons.*'    => ['integer', 'min:0'],
        ]);

        $user        = $request->user();
        $reservation = $this->service->createReservation($data, $user);

        // Kirim email konfirmasi
        $user->notify(new ReservationCreated($reservation));

        return response()->json([
            'message'     => 'Reservasi berhasil dibuat.',
            'reservation' => $reservation->load('studio', 'addons'),
        ], 201);
    }

    /**
     * Update jadwal / addons reservasi.
     * Hanya boleh oleh owner atau admin/manager (cek di Policy@update).
     */
    public function update(Request $request, Reservation $reservation)
    {
        $this->authorize('update', $reservation);

        $data = $request->validate([
            'start_time'  => ['required', 'date'],
            'end_time'    => ['required', 'date'],
            'addons'      => ['nullable', 'array'],
            'addons.*'    => ['integer', 'min:0'],
        ]);

        $reservation = $this->service->updateReservation($data, $reservation);

        return response()->json([
            'message'     => 'Reservasi berhasil diupdate.',
            'reservation' => $reservation,
        ]);
    }

    /**
     * Membatalkan reservasi.
     * Hanya boleh oleh owner atau admin/manager (cek di Policy@cancel).
     */
    public function cancel(Request $request, Reservation $reservation)
    {
        $this->authorize('cancel', $reservation);

        $reservation = $this->service->cancelReservation($reservation);

        // Kirim email notifikasi cancel ke pemilik reservasi
        $reservation->user->notify(new ReservationCancelled($reservation));

        return response()->json([
            'message'     => 'Reservasi berhasil dibatalkan.',
            'reservation' => $reservation,
        ]);
    }

    /**
     * Check-in reservasi berbasis QR code (kode dikirim di body).
     */
    public function checkin(Request $request, Reservation $reservation)
    {
        $this->authorize('checkin', $reservation);

        $validated = $request->validate([
            'code' => ['required', 'string'],
        ]);

        $reservation = $this->service->checkInReservation($reservation, $validated['code']);

        return response()->json([
            'message'     => 'Check-in berhasil.',
            'reservation' => $reservation,
        ]);
    }

    /**
     * Hard delete reservasi (misal untuk cleaning oleh admin).
     */
    public function destroy(Request $request, Reservation $reservation)
    {
        $this->authorize('delete', $reservation);

        $reservation->delete();

        return response()->json([
            'message' => 'Reservasi berhasil dihapus.',
        ]);
    }
}
