<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    /**
     * Redirect ke halaman laporan reservasi
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        // Mengarahkan ke laporan reservasi
        return redirect()->route('admin.reports.reservations');
    }

    /**
     * Menampilkan laporan semua reservasi
     *
     * @return \Illuminate\View\View
     */
    public function reservations()
    {
        // Ambil semua reservasi beserta studio dan user yang terkait
        $reservations = Reservation::with(['studio', 'user'])->latest()->get();

        // Kirim data ke tampilan laporan
        return view('admin.reports.reservations', compact('reservations'));
        return view('manager.reports.reservations', compact('reservations'));

    }

    /**
     * Menampilkan halaman detail reservasi
     *
     * @param  \App\Models\Reservation  $reservation
     * @return \Illuminate\View\View
     */
    public function show(Reservation $reservation)
    {
        // Menampilkan detail reservasi tertentu
        return view('admin.reports.show', compact('reservation'));
    }

    /**
     * Mengupdate status reservasi
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Reservation  $reservation
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(Request $request, Reservation $reservation)
    {
        // Validasi input status
        $request->validate([
            'status' => 'required|in:pending,confirmed,completed,canceled', // Validasi status
        ]);

        // Mengupdate status reservasi
        $reservation->status = $request->status;
        $reservation->save();

        // Redirect kembali ke laporan reservasi dengan pesan sukses
        return redirect()->route('admin.reports.reservations')->with('success', 'Status reservasi berhasil diperbarui!');
    }
}
