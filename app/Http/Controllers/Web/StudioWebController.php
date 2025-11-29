<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Studio;
use App\Models\Reservation;
use App\Models\Addon;
use App\Services\ReservationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class StudioWebController extends Controller
{
    public function index(Request $request)
    {
        $query = Studio::with('features', 'addons')
            ->where('status', 'active');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            });
        }

        $studios = $query->paginate(9)->withQueryString();

        return view('user.studios.index', compact('studios'));
    }

    public function show(Studio $studio)
    {
        $studio->load('features', 'addons');

        return view('user.studios.show', compact('studio'));
    }

    /**
     * ✅ BUAT RESERVASI dari halaman detail studio
     * Route yang direkomendasikan: POST /user/studios/{studio}/reservations
     */
    public function storeReservation(Studio $studio, Request $request)
    {
        $validated = $request->validate([
            'reservation_date' => ['required', 'date'],
            'start_time'       => ['required', 'date_format:H:i'],
            'end_time'         => ['required', 'date_format:H:i', 'after:start_time'],
            'addons'           => ['nullable', 'array'],
            'addons.*'         => ['integer', 'exists:addons,id'],
        ]);

        $start = Carbon::parse($validated['reservation_date'] . ' ' . $validated['start_time']);
        $end   = Carbon::parse($validated['reservation_date'] . ' ' . $validated['end_time']);

        // tidak boleh booking masa lalu
        if ($start->lt(now())) {
            return back()->withInput()->with('error', 'Waktu mulai tidak boleh di masa lalu.');
        }

        // cek bentrok jadwal (overlap)
        $conflictQuery = Reservation::where('studio_id', $studio->id)
            ->where('start_time', '<', $end)
            ->where('end_time', '>', $start);

        // kalau kolom status ada, hanya cek yang masih aktif
        if (Schema::hasColumn('reservations', 'status')) {
            $conflictQuery->whereIn('status', ['pending', 'confirmed']);
        }

        if ($conflictQuery->exists()) {
            return back()->withInput()->with('error', 'Jadwal bentrok. Silakan pilih jam lain.');
        }

        // hitung durasi (minimal 1 jam)
        $minutes = $start->diffInMinutes($end);
        $hours   = max(1, (int) ceil($minutes / 60));

        // addon opsional
        $addonIds = $validated['addons'] ?? [];

        $addonsTotal = 0;
        if (!empty($addonIds) && Schema::hasTable('addons') && Schema::hasColumn('addons', 'price')) {
            $addonsTotal = (float) Addon::whereIn('id', $addonIds)->sum('price');
        }

        $studioTotal = (float) $studio->price_per_hour * $hours;
        $totalPrice  = $studioTotal + $addonsTotal;

        // simpan reservasi
        $reservation = new Reservation();
        $reservation->user_id    = Auth::id();
        $reservation->studio_id  = $studio->id;
        $reservation->start_time = $start;
        $reservation->end_time   = $end;

        // set status kalau kolomnya ada
        if (Schema::hasColumn('reservations', 'status')) {
            $reservation->status = 'pending';
        }

        // set total_price kalau kolomnya ada (biar gak error kalau tabel kamu belum punya)
        if (Schema::hasColumn('reservations', 'total_price')) {
            $reservation->total_price = $totalPrice;
        }

        $reservation->save();

        // simpan addons kalau relasi ada
        if (method_exists($reservation, 'addons')) {
            $reservation->addons()->sync($addonIds);
        }

        return redirect()
            ->route('user.reservations.show', $reservation)
            ->with('status', 'Reservasi berhasil dibuat. Status: pending ✅');
    }

    public function myReservations()
    {
        $reservations = Reservation::with('studio')
            ->where('user_id', Auth::id())
            ->orderByDesc('start_time')
            ->paginate(10);

        return view('user.reservations.index', compact('reservations'));
    }

    public function showReservation(Reservation $reservation)
    {
        if ($reservation->user_id !== Auth::id()) {
            abort(403);
        }

        $reservation->load('studio', 'addons');

        return view('user.reservations.show', compact('reservation'));
    }

    public function cancelReservation(Reservation $reservation, ReservationService $reservationService)
    {
        if ($reservation->user_id !== Auth::id()) {
            abort(403);
        }

        try {
            $reservationService->cancelReservation($reservation);

            return redirect()
                ->route('user.reservations.index')
                ->with('status', 'Reservasi berhasil dibatalkan.');
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
