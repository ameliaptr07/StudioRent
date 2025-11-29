@extends('layouts.app')

@section('title', 'Reservasi Saya')

@section('content')
    <div class="-mx-4 sm:-mx-6 lg:-mx-8 -my-6 sm:-my-8 lg:-my-12">
        <div class="min-h-[calc(100vh-64px)] bg-slate-950 text-slate-100">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">

                {{-- Header / Hero --}}
                <div class="relative overflow-hidden rounded-3xl border border-white/10 bg-gradient-to-br from-indigo-500/20 via-indigo-500/5 to-transparent p-6 sm:p-8 shadow-[0_20px_60px_-35px_rgba(0,0,0,0.8)]">
                    <div class="absolute inset-0 pointer-events-none">
                        <div class="absolute -top-24 -right-24 h-64 w-64 rounded-full bg-indigo-500/20 blur-3xl"></div>
                        <div class="absolute -bottom-24 -left-24 h-64 w-64 rounded-full bg-cyan-500/15 blur-3xl"></div>
                    </div>

                    <div class="relative flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-[0.18em] text-slate-300/80">User Reservations</p>
                            <h1 class="mt-2 text-3xl sm:text-4xl font-semibold text-white">Reservasi Saya</h1>
                            <p class="mt-2 text-slate-300">Riwayat dan jadwal reservasi kamu.</p>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-2 sm:items-center sm:justify-end">
                            <a href="{{ route('user.dashboard') }}"
                               class="inline-flex items-center justify-center rounded-2xl border border-white/10 bg-white/5 px-5 py-3 text-sm font-semibold text-white transition hover:bg-white/10 hover:border-white/20 focus:outline-none focus:ring-2 focus:ring-white/20">
                                ← Dashboard
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Table Card --}}
                <section class="mt-6">
                    <div class="overflow-hidden rounded-3xl border border-white/10 bg-white/5 shadow-[0_20px_60px_-35px_rgba(0,0,0,0.8)]">
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead class="bg-white/5">
                                    <tr class="text-left text-slate-300">
                                        <th class="px-6 py-4 font-medium">Studio</th>
                                        <th class="px-6 py-4 font-medium">Mulai</th>
                                        <th class="px-6 py-4 font-medium">Selesai</th>
                                        <th class="px-6 py-4 font-medium">Total Biaya</th>
                                        <th class="px-6 py-4 font-medium">Status</th> <!-- Kolom status ditambahkan -->
                                        <th class="px-6 py-4 font-medium text-right">Aksi</th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-white/10">
                                    @forelse($reservations as $r)
                                        <tr class="transition hover:bg-white/5">
                                            <td class="px-6 py-4 text-slate-100">
                                                {{ $r->studio->name ?? '-' }}
                                            </td>

                                            <td class="px-6 py-4 text-slate-200">
                                                {{ \Illuminate\Support\Carbon::parse($r->start_time)->format('d M Y H:i') }}
                                            </td>

                                            <td class="px-6 py-4 text-slate-200">
                                                {{ \Illuminate\Support\Carbon::parse($r->end_time)->format('d M Y H:i') }}
                                            </td>

                                            <td class="px-6 py-4 text-slate-200">
                                                Rp {{ number_format($r->total_price, 0, ',', '.') }}
                                            </td>

                                            <td class="px-6 py-4 text-slate-200">
                                                <!-- Menampilkan status reservasi -->
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border
                                                            @if($r->status == 'pending') bg-yellow-500 text-yellow-800
                                                            @elseif($r->status == 'confirmed') bg-green-500 text-green-800
                                                            @elseif($r->status == 'completed') bg-blue-500 text-blue-800
                                                            @elseif($r->status == 'canceled') bg-red-500 text-red-800
                                                            @endif">
                                                    {{ ucfirst($r->status) }}
                                                </span>
                                            </td>

                                            <td class="px-6 py-4 text-right">
                                                <div class="flex flex-col sm:flex-row sm:justify-end gap-2 sm:gap-3">
                                                    <a href="{{ route('user.reservations.show', $r) }}"
                                                       class="inline-flex items-center justify-center rounded-2xl border border-white/10 bg-white/5 px-4 py-2 text-xs font-semibold text-white transition hover:bg-white/10 hover:border-white/20">
                                                        Detail ↗
                                                    </a>

                                                    <form action="{{ route('user.reservations.cancel', $r) }}" method="POST" class="inline"
                                                          onsubmit="return confirm('Yakin membatalkan reservasi ini?');">
                                                        @csrf
                                                        <button type="submit"
                                                                class="inline-flex w-full items-center justify-center rounded-2xl bg-gradient-to-b from-rose-500 to-red-600 px-4 py-2 text-xs font-semibold text-white shadow-[0_16px_40px_-18px_rgba(244,63,94,0.8)] transition hover:brightness-110 focus:outline-none focus:ring-2 focus:ring-rose-400/60">
                                                            Batalkan
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-6 py-10 text-center text-slate-300">
                                                Belum ada reservasi.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>

                {{-- Pagination --}}
                <div class="mt-6">
                    <div class="rounded-3xl border border-white/10 bg-white/5 p-4 shadow-[0_20px_60px_-35px_rgba(0,0,0,0.8)]">
                        {{ $reservations->links('pagination::tailwind') }}
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
