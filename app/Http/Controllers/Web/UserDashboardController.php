<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Studio;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    public function index()
    {
        $activeStudios = Studio::where('status', 'active')->count();

        $myReservationsTotal = Reservation::where('user_id', Auth::id())->count();

        $upcomingReservation = Reservation::with('studio')
            ->where('user_id', Auth::id())
            ->where('start_time', '>=', now())
            ->orderBy('start_time')
            ->first();

        return view('user.dashboard', compact(
            'activeStudios',
            'myReservationsTotal',
            'upcomingReservation'
        ));
    }
}
