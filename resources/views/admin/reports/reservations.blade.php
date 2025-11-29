@extends('layouts.app')

@section('title', 'Laporan Reservasi')

@section('content')
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
    {{-- Header --}}
    <div class="relative overflow-hidden rounded-3xl border border-white/10 bg-gradient-to-br from-white/10 via-white/5 to-transparent p-6 sm:p-8 shadow-[0_20px_60px_-35px_rgba(0,0,0,0.8)] print:shadow-none print:border-slate-200 print:bg-white">
        <div class="absolute inset-0 pointer-events-none print:hidden">
            <div class="absolute -top-24 -right-24 h-64 w-64 rounded-full bg-indigo-500/20 blur-3xl"></div>
            <div class="absolute -bottom-24 -left-24 h-64 w-64 rounded-full bg-cyan-500/15 blur-3xl"></div>
        </div>

        <div class="relative flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.18em] text-slate-300/80 print:text-slate-500">Admin • Reports</p>
                <h1 class="mt-2 text-3xl sm:text-4xl font-semibold text-white print:text-slate-900">Laporan Reservasi Studio</h1>
                <p class="mt-2 max-w-2xl text-slate-300 print:text-slate-600">
                    Ringkasan seluruh reservasi yang masuk.
                </p>

                <div class="mt-5 flex flex-wrap items-center gap-3 print:hidden">
                    <a href="{{ route('admin.dashboard') }}"
                       class="inline-flex items-center justify-center rounded-2xl px-5 py-2.5 text-sm font-semibold border border-white/10 bg-white/5 text-slate-100 transition hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-white/20">
                        ← Kembali ke Dashboard
                    </a>

                    <span class="inline-flex items-center rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs text-slate-200">
                        Terakhir update: {{ now()->format('d M Y, H:i') }}
                    </span>
                </div>
            </div>

            <div class="flex items-center gap-2 print:hidden">
                <button type="button" onclick="window.print()"
                        class="inline-flex items-center justify-center rounded-2xl bg-gradient-to-b from-indigo-500 to-indigo-600 px-6 py-3 text-sm font-semibold text-white shadow-[0_16px_40px_-18px_rgba(99,102,241,0.7)] transition hover:brightness-110 focus:outline-none focus:ring-2 focus:ring-indigo-400/60">
                    Cetak
                </button>
            </div>
        </div>
    </div>

    {{-- Summary cards --}}
    <div class="mt-6 grid grid-cols-1 sm:grid-cols-3 gap-4 print:grid-cols-3">
        <div class="rounded-3xl border border-white/10 bg-white/5 backdrop-blur-xl p-5 sm:p-6 shadow-[0_20px_60px_-35px_rgba(0,0,0,0.8)] print:shadow-none print:bg-white print:border-slate-200">
            <p class="text-xs uppercase tracking-[0.14em] text-slate-300/80 print:text-slate-500">Total Reservasi</p>
            <p class="mt-2 text-3xl font-semibold text-white print:text-slate-900">{{ $reservations->count() }}</p>
            <p class="mt-1 text-sm text-slate-300 print:text-slate-600">Semua booking yang tercatat.</p>
        </div>

        <div class="rounded-3xl border border-white/10 bg-white/5 backdrop-blur-xl p-5 sm:p-6 shadow-[0_20px_60px_-35px_rgba(0,0,0,0.8)] print:shadow-none print:bg-white print:border-slate-200">
            <p class="text-xs uppercase tracking-[0.14em] text-slate-300/80 print:text-slate-500">Studio Terlibat</p>
            <p class="mt-2 text-3xl font-semibold text-white print:text-slate-900">
                {{ $reservations->pluck('studio_id')->unique()->count() }}
            </p>
            <p class="mt-1 text-sm text-slate-300 print:text-slate-600">Jumlah studio yang dibooking.</p>
        </div>

        <div class="rounded-3xl border border-white/10 bg-white/5 backdrop-blur-xl p-5 sm:p-6 shadow-[0_20px_60px_-35px_rgba(0,0,0,0.8)] print:shadow-none print:bg-white print:border-slate-200">
            <p class="text-xs uppercase tracking-[0.14em] text-slate-300/80 print:text-slate-500">User Terlibat</p>
            <p class="mt-2 text-3xl font-semibold text-white print:text-slate-900">
                {{ $reservations->pluck('user_id')->unique()->count() }}
            </p>
            <p class="mt-1 text-sm text-slate-300 print:text-slate-600">Jumlah user yang melakukan booking.</p>
        </div>
    </div>

    {{-- Table --}}
    <div class="mt-6 rounded-3xl border border-white/10 bg-white/5 backdrop-blur-xl shadow-[0_20px_60px_-35px_rgba(0,0,0,0.8)] overflow-hidden print:shadow-none print:bg-white print:border-slate-200">
        <div class="flex items-center justify-between gap-3 p-5 sm:p-6 border-b border-white/10 print:border-slate-200">
            <div>
                <p class="text-base sm:text-lg font-semibold text-white print:text-slate-900">Daftar Reservasi</p>
                <p class="mt-1 text-xs sm:text-sm text-slate-300 print:text-slate-600">
                    (read-only)
                </p>
            </div>
            <div class="hidden sm:flex items-center gap-2 print:hidden">
                <span class="inline-flex items-center rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs text-slate-200">
                    Report
                </span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-white/5 print:bg-slate-50">
                    <tr class="text-left text-slate-300 print:text-slate-700">
                        <th class="px-6 py-4 font-medium">Studio</th>
                        <th class="px-6 py-4 font-medium">Pengguna</th>
                        <th class="px-6 py-4 font-medium">Mulai</th>
                        <th class="px-6 py-4 font-medium">Selesai</th>
                        <th class="px-6 py-4 font-medium">Status</th>
                        <th class="px-6 py-4 font-medium">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-white/10 print:divide-slate-200">
                    @forelse($reservations as $reservation)
                        @php
                            // Status status
                            $start = $reservation->start_time ? \Carbon\Carbon::parse($reservation->start_time) : null;
                            $end   = $reservation->end_time ? \Carbon\Carbon::parse($reservation->end_time) : null;
                        @endphp

                        <tr class="hover:bg-white/5 transition print:hover:bg-transparent">
                            <td class="px-6 py-4">
                                <div class="flex items-start gap-3">
                                    <span class="mt-0.5 inline-flex h-9 w-9 items-center justify-center rounded-2xl border border-white/10 bg-white/5 text-white/90 shadow-sm print:border-slate-200 print:bg-white print:text-slate-700">
                                        🎛️
                                    </span>
                                    <div>
                                        <div class="font-semibold text-white print:text-slate-900">
                                            {{ $reservation->studio->name ?? '-' }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <div class="font-semibold text-white print:text-slate-900">
                                    {{ $reservation->user->name ?? '-' }}
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($start)
                                    <div class="font-medium text-slate-100 print:text-slate-900">{{ $start->format('d M Y') }}</div>
                                    <div class="text-xs text-slate-400 print:text-slate-500">{{ $start->format('H:i') }}</div>
                                @else
                                    <span class="text-slate-400 print:text-slate-500">-</span>
                                @endif
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($end)
                                    <div class="font-medium text-slate-100 print:text-slate-900">{{ $end->format('d M Y') }}</div>
                                    <div class="text-xs text-slate-400 print:text-slate-500">{{ $end->format('H:i') }}</div>
                                @else
                                    <span class="text-slate-400 print:text-slate-500">-</span>
                                @endif
                            </td>

                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border
                                            @if($reservation->status == 'pending') bg-yellow-500 text-yellow-800
                                            @elseif($reservation->status == 'confirmed') bg-green-500 text-green-800
                                            @elseif($reservation->status == 'completed') bg-blue-500 text-blue-800
                                            @elseif($reservation->status == 'canceled') bg-red-500 text-red-800
                                            @endif">
                                    {{ ucfirst($reservation->status) }}
                                </span>
                            </td>

                            <td class="px-6 py-4 text-right">
                                <!-- Tombol untuk memilih status dan mengubah status -->
                            <form action="{{ route('admin.reservations.updateStatus', $reservation) }}" method="POST">
                                @csrf
                                @method('PATCH')

                                <select name="status" class="bg-white text-black px-3 py-1 rounded-lg text-xs font-semibold border border-white/20 focus:ring-2 focus:ring-indigo-400/50">
                                    <option value="pending" {{ $reservation->status == 'pending' ? 'selected' : '' }} class="bg-yellow-100 text-yellow-800">Pending</option>
                                    <option value="confirmed" {{ $reservation->status == 'confirmed' ? 'selected' : '' }} class="bg-green-100 text-green-800">Confirmed</option>
                                    <option value="completed" {{ $reservation->status == 'completed' ? 'selected' : '' }} class="bg-blue-100 text-blue-800">Completed</option>
                                    <option value="canceled" {{ $reservation->status == 'canceled' ? 'selected' : '' }} class="bg-red-100 text-red-800">Canceled</option>
                                </select>
                                <button type="submit" class="ml-2 px-4 py-2 text-xs font-semibold text-white bg-blue-500 rounded-lg">
                                    Update
                                </button>
                            </form>

                                <!-- Tombol Detail untuk Reservasi -->
                                <a href="{{ route('admin.reservations.show', $reservation) }}"
                                   class="inline-flex items-center justify-center rounded-2xl border border-white/10 bg-white/5 px-4 py-2 text-xs font-semibold text-white transition hover:bg-white/10 hover:border-white/20">
                                    Detail ↗
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="mx-auto max-w-md">
                                    <div class="inline-flex h-12 w-12 items-center justify-center rounded-2xl border border-white/10 bg-white/5 text-white/90 print:border-slate-200 print:bg-white print:text-slate-700">
                                        💤
                                    </div>
                                    <div class="mt-4 text-slate-200 font-semibold print:text-slate-900">
                                        Belum ada reservasi yang tercatat.
                                    </div>
                                    <div class="mt-1 text-sm text-slate-400 print:text-slate-600">
                                        Nanti kalau user mulai booking, data akan muncul di sini.
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
