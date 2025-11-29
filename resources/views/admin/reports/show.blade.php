@extends('layouts.app')

@section('title', 'Detail Reservasi')

@section('content')
    {{-- Full-bleed wrapper: senada dengan gaya admin --}}
    <div class="-mx-4 sm:-mx-6 lg:-mx-8 -my-6 sm:-my-8 lg:-my-12">
        <div class="min-h-[calc(100vh-64px)] bg-slate-950 text-slate-100">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">

                {{-- Header / Hero --}}
                <div class="relative overflow-hidden rounded-3xl border border-white/10 bg-gradient-to-br from-white/10 via-white/5 to-transparent p-6 sm:p-8 shadow-[0_20px_60px_-35px_rgba(0,0,0,0.8)]">
                    <div class="absolute inset-0 pointer-events-none">
                        <div class="absolute -top-24 -right-24 h-64 w-64 rounded-full bg-indigo-500/20 blur-3xl"></div>
                        <div class="absolute -bottom-24 -left-24 h-64 w-64 rounded-full bg-cyan-500/15 blur-3xl"></div>
                    </div>

                    <div class="relative flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-[0.18em] text-slate-300/80">Admin Reservation</p>
                            <h1 class="mt-2 text-3xl sm:text-4xl font-semibold text-white">Detail Reservasi</h1>
                            <p class="mt-2 text-slate-300">Ringkasan reservasi yang lebih detail.</p>
                        </div>

                        <a href="{{ route('admin.reports.reservations') }}"
                           class="inline-flex items-center justify-center rounded-2xl border border-white/10 bg-white/5 px-5 py-3 text-sm font-semibold text-white transition hover:bg-white/10 hover:border-white/20 focus:outline-none focus:ring-2 focus:ring-white/20">
                            ← Kembali
                        </a>
                    </div>
                </div>

                {{-- Content Card --}}
                <section class="mt-6">
                    <div class="relative overflow-hidden rounded-3xl border border-white/10 bg-white/5 p-6 sm:p-8 shadow-[0_20px_60px_-35px_rgba(0,0,0,0.8)]">
                        <div class="absolute -top-24 -right-24 h-64 w-64 rounded-full bg-indigo-500/10 blur-3xl"></div>

                        <div class="relative space-y-6">
                            <div class="grid sm:grid-cols-2 gap-4">
                                <div class="rounded-2xl border border-white/10 bg-white/5 p-5">
                                    <p class="text-xs uppercase tracking-[0.18em] text-slate-300/80">Studio</p>
                                    <p class="mt-2 text-xl font-semibold text-white">
                                        {{ $reservation->studio->name ?? '-' }}
                                    </p>
                                    {{-- Menampilkan harga studio --}}
                                    <p class="mt-2 text-sm text-slate-200">
                                        Harga Studio: Rp {{ number_format($reservation->studio->price_per_hour, 0, ',', '.') }} per jam
                                    </p>
                                </div>

                                <div class="rounded-2xl border border-white/10 bg-white/5 p-5">
                                    <p class="text-xs uppercase tracking-[0.18em] text-slate-300/80">Waktu</p>
                                    <p class="mt-2 text-sm text-slate-200">
                                        {{ \Illuminate\Support\Carbon::parse($reservation->start_time)->format('d M Y H:i') }}
                                        <span class="text-slate-400">—</span>
                                        {{ \Illuminate\Support\Carbon::parse($reservation->end_time)->format('d M Y H:i') }}
                                    </p>
                                </div>
                            </div>

                            <div class="border-t border-white/10 pt-6">
                                <p class="text-xs uppercase tracking-[0.18em] text-slate-300/80 mb-3">Addon (jika ada)</p>

                                <div class="flex flex-wrap gap-2">
                                    @forelse($reservation->addons ?? [] as $a)
                                        <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs text-slate-200">
                                            {{ $a->name }} (Rp {{ number_format($a->price, 0, ',', '.') }})
                                        </span>
                                    @empty
                                        <p class="text-sm text-slate-300">Tidak ada addon.</p>
                                    @endforelse
                                </div>
                            </div>

                            <div class="border-t border-white/10 pt-6">
                                <p class="text-xs uppercase tracking-[0.18em] text-slate-300/80 mb-3">Status</p>
                                <span class="px-4 py-1 rounded-full text-xs font-semibold
                                            @if($reservation->status == 'pending') bg-yellow-500 text-yellow-800
                                            @elseif($reservation->status == 'confirmed') bg-green-500 text-green-800
                                            @elseif($reservation->status == 'completed') bg-blue-500 text-blue-800
                                            @elseif($reservation->status == 'canceled') bg-red-500 text-red-800
                                            @endif">
                                    {{ ucfirst($reservation->status) }}
                                </span>
                            </div>

                            <form action="{{ route('admin.reservations.updateStatus', $reservation) }}" method="POST"
                                  onsubmit="return confirm('Yakin ingin memperbarui status?');">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                        class="w-full sm:w-auto rounded-2xl bg-gradient-to-b from-rose-500 to-red-600 px-5 py-3 text-sm font-semibold text-white shadow-[0_16px_40px_-18px_rgba(244,63,94,0.8)] transition hover:brightness-110 focus:outline-none focus:ring-2 focus:ring-rose-400/60">
                                    Update Status
                                </button>
                            </form>
                        </div>
                    </div>
                </section>

            </div>
        </div>
    </div>
@endsection
