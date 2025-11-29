@extends('layouts.app')

@section('title', 'Reports')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-semibold text-gray-800">Reports</h1>
        <span class="text-sm text-gray-500">Ringkasan laporan & reservasi</span>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-6">
        <p class="text-gray-600">
            Halaman Reports manager menampilkan ringkasan dari seluruh laporan yang dikelola.
        </p>
        <div class="mt-6 grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="rounded-3xl border border-white/10 bg-white/5 p-5 sm:p-6 shadow-xl">
                <p class="text-xs uppercase tracking-[0.14em] text-slate-300/80">Total Reservasi</p>
                <p class="mt-2 text-3xl font-semibold text-white">{{ $reservationsCount }}</p>
                <p class="mt-1 text-sm text-slate-300">Total reservasi yang tercatat di studio.</p>
            </div>
            <div class="rounded-3xl border border-white/10 bg-white/5 p-5 sm:p-6 shadow-xl">
                <p class="text-xs uppercase tracking-[0.14em] text-slate-300/80">Studio Terlibat</p>
                <p class="mt-2 text-3xl font-semibold text-white">{{ $studiosCount }}</p>
                <p class="mt-1 text-sm text-slate-300">Jumlah studio yang dibooking.</p>
            </div>
            <div class="rounded-3xl border border-white/10 bg-white/5 p-5 sm:p-6 shadow-xl">
                <p class="text-xs uppercase tracking-[0.14em] text-slate-300/80">User Terlibat</p>
                <p class="mt-2 text-3xl font-semibold text-white">{{ $usersCount }}</p>
                <p class="mt-1 text-sm text-slate-300">Jumlah pengguna yang melakukan reservasi.</p>
            </div>
        </div>
    </div>
@endsection
