@extends('layouts.app')

@section('title', 'Dashboard Penyewa')

@section('content')
    {{-- Full-bleed wrapper: membatalkan padding/card bawaan agar tidak "kotak di dalam kotak" --}}
    <div class="-mx-4 sm:-mx-6 lg:-mx-8 -my-6 sm:-my-8 lg:-my-12">
        <div class="min-h-[calc(100vh-64px)] bg-slate-950 text-slate-100">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                    <!-- Sidebar (Desktop) -->
                    <aside class="hidden lg:block lg:col-span-3">
                        <div class="sticky top-6 rounded-3xl border border-white/10 bg-white/5 backdrop-blur-xl shadow-[0_20px_60px_-35px_rgba(0,0,0,0.8)]">
                            <div class="p-6">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <p class="text-xs uppercase tracking-[0.18em] text-slate-400">StudioRent</p>
                                        <h2 class="mt-1 text-2xl font-semibold text-white">Penyewa Panel</h2>
                                        <p class="mt-2 text-sm text-slate-300">
                                            Hi, <span class="font-medium text-white">{{ Auth::user()->name }}</span>
                                        </p>
                                    </div>
                                    <span class="inline-flex items-center rounded-full border border-emerald-400/20 bg-emerald-400/10 px-3 py-1 text-xs font-medium text-emerald-200">
                                        Online
                                    </span>
                                </div>

                                <div class="mt-6 space-y-2">
                                    <a href="{{ url('/user/dashboard') }}"
                                       class="group flex items-center justify-between rounded-2xl border border-white/10 bg-white/5 px-4 py-3 transition hover:bg-white/10 hover:border-white/20">
                                        <span class="font-medium text-white">Dashboard</span>
                                        <span class="text-slate-400 group-hover:text-white">↗</span>
                                    </a>

                                    <a href="{{ route('user.studios.index') }}"
                                       class="group flex items-center justify-between rounded-2xl border border-white/10 bg-white/5 px-4 py-3 transition hover:bg-white/10 hover:border-white/20">
                                        <span class="font-medium text-white">Studios</span>
                                        <span class="text-slate-400 group-hover:text-white">↗</span>
                                    </a>

                                    <a href="{{ route('user.reservations.index') }}"
                                       class="group flex items-center justify-between rounded-2xl border border-white/10 bg-white/5 px-4 py-3 transition hover:bg-white/10 hover:border-white/20">
                                        <span class="font-medium text-white">Reservasi</span>
                                        <span class="text-slate-400 group-hover:text-white">↗</span>
                                    </a>

                                    <div class="pt-4">
                                        <p class="px-1 text-xs uppercase tracking-[0.18em] text-slate-400">Akun</p>
                                    </div>
                                </div>

                                <form action="{{ route('logout') }}" method="POST" class="mt-6">
                                    @csrf
                                    <button type="submit"
                                            class="w-full rounded-2xl bg-gradient-to-b from-rose-500 to-red-600 px-4 py-3 font-semibold text-white shadow-[0_16px_40px_-18px_rgba(244,63,94,0.8)] transition hover:brightness-110 focus:outline-none focus:ring-2 focus:ring-rose-400/60">
                                        Logout
                                    </button>
                                </form>

                                <div class="mt-6 rounded-2xl border border-white/10 bg-white/5 p-4">
                                    <p class="text-sm text-slate-300">
                                        Mode penyewa: cari studio, booking, dateng tepat waktu, pulang bawa konten.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </aside>

                    <!-- Main -->
                    <main class="lg:col-span-9">
                        <!-- Header -->
                        <div class="relative overflow-hidden rounded-3xl border border-white/10 bg-gradient-to-br from-white/10 via-white/5 to-transparent p-6 sm:p-8 shadow-[0_20px_60px_-35px_rgba(0,0,0,0.8)]">
                            <div class="absolute inset-0 pointer-events-none">
                                <div class="absolute -top-24 -right-24 h-64 w-64 rounded-full bg-indigo-500/20 blur-3xl"></div>
                                <div class="absolute -bottom-24 -left-24 h-64 w-64 rounded-full bg-cyan-500/15 blur-3xl"></div>
                            </div>

                            <div class="relative flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                                <div>
                                    <p class="text-xs uppercase tracking-[0.18em] text-slate-300/80">User Dashboard</p>
                                    <h1 class="mt-2 text-3xl sm:text-4xl font-semibold text-white">
                                        Dashboard Penyewa 👋
                                    </h1>
                                    <p class="mt-2 max-w-2xl text-slate-300">
                                        Selamat datang, <span class="text-white/90 font-medium">{{ Auth::user()->name }}</span>.
                                        Mau sewa studio yang mana hari ini?
                                    </p>
                                </div>

                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs text-slate-200">
                                        Laravel + Blade
                                    </span>
                                    <span class="inline-flex items-center rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs text-slate-200">
                                        Tailwind
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Mobile Nav (sidebar hidden) -->
                        <div class="lg:hidden mt-6">
                            <div class="rounded-3xl border border-white/10 bg-white/5 backdrop-blur-xl p-4">
                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                    <a href="{{ url('/user/dashboard') }}"
                                       class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-center font-medium text-white transition hover:bg-white/10 hover:border-white/20">
                                        Dashboard
                                    </a>
                                    <a href="{{ route('user.studios.index') }}"
                                       class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-center font-medium text-white transition hover:bg-white/10 hover:border-white/20">
                                        Studios
                                    </a>
                                    <a href="{{ route('user.reservations.index') }}"
                                       class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-center font-medium text-white transition hover:bg-white/10 hover:border-white/20">
                                        Reservasi
                                    </a>
                                </div>

                                <form action="{{ route('logout') }}" method="POST" class="mt-3">
                                    @csrf
                                    <button type="submit"
                                            class="w-full rounded-2xl bg-gradient-to-b from-rose-500 to-red-600 px-4 py-3 font-semibold text-white transition hover:brightness-110 focus:outline-none focus:ring-2 focus:ring-rose-400/60">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Stats -->
                        <section class="mt-6">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                                <div class="rounded-3xl border border-white/10 bg-white/5 p-6 shadow-[0_20px_60px_-35px_rgba(0,0,0,0.8)] transition hover:bg-white/10 hover:border-white/20">
                                    <p class="text-xs uppercase tracking-[0.18em] text-slate-300/80">Studio Aktif</p>
                                    <p class="mt-3 text-3xl font-semibold text-white">{{ $activeStudios }}</p>
                                    <p class="mt-2 text-sm text-slate-300">Jumlah studio yang tersedia untuk disewa.</p>
                                </div>

                                <div class="rounded-3xl border border-white/10 bg-white/5 p-6 shadow-[0_20px_60px_-35px_rgba(0,0,0,0.8)] transition hover:bg-white/10 hover:border-white/20">
                                    <p class="text-xs uppercase tracking-[0.18em] text-slate-300/80">Total Reservasi Kamu</p>
                                    <p class="mt-3 text-3xl font-semibold text-white">{{ $myReservationsTotal }}</p>
                                    <p class="mt-2 text-sm text-slate-300">Total riwayat reservasi yang pernah kamu buat.</p>
                                </div>

                                <div class="rounded-3xl border border-white/10 bg-white/5 p-6 shadow-[0_20px_60px_-35px_rgba(0,0,0,0.8)] transition hover:bg-white/10 hover:border-white/20">
                                    <p class="text-xs uppercase tracking-[0.18em] text-slate-300/80">Jadwal Terdekat</p>

                                    @if($upcomingReservation)
                                        <p class="mt-3 text-sm font-medium text-white">
                                            {{ $upcomingReservation->studio->name }}
                                        </p>
                                        <p class="mt-1 text-xs text-slate-300">
                                            {{ \Illuminate\Support\Carbon::parse($upcomingReservation->start_time)->format('d M Y H:i') }}
                                        </p>
                                        <div class="mt-4 inline-flex items-center rounded-full border border-emerald-400/20 bg-emerald-400/10 px-3 py-1 text-xs font-medium text-emerald-200">
                                            Upcoming
                                        </div>
                                    @else
                                        <p class="mt-3 text-sm text-slate-300">Belum ada jadwal. Gas cari studio dulu ✨</p>
                                    @endif
                                </div>
                            </div>
                        </section>

                        <!-- Big Action Tiles -->
                        <section class="mt-6">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                                <a href="{{ route('user.studios.index') }}"
                                   class="group relative overflow-hidden rounded-3xl border border-white/10 bg-white/5 p-6 sm:p-8 shadow-[0_20px_60px_-35px_rgba(0,0,0,0.8)] transition hover:bg-white/10 hover:border-white/20">
                                    <div class="absolute -top-24 -right-24 h-64 w-64 rounded-full bg-indigo-500/20 blur-3xl transition group-hover:bg-indigo-500/30"></div>
                                    <div class="relative">
                                        <div class="flex items-start justify-between gap-4">
                                            <div>
                                                <p class="text-xs uppercase tracking-[0.18em] text-slate-300/80">Explore</p>
                                                <h3 class="mt-2 text-2xl font-semibold text-white">Cari & Lihat Studio</h3>
                                                <p class="mt-2 text-slate-300">
                                                    Jelajahi studio aktif, cek fasilitas, dan pilih yang paling cocok.
                                                </p>
                                            </div>
                                            <div class="shrink-0 rounded-2xl border border-white/10 bg-white/5 px-4 py-2 text-sm text-slate-200">
                                                Open ↗
                                            </div>
                                        </div>
                                        <div class="mt-6 flex flex-wrap gap-2">
                                            <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs text-slate-200">Browse</span>
                                            <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs text-slate-200">Features</span>
                                            <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs text-slate-200">Choose</span>
                                        </div>
                                    </div>
                                </a>

                                <a href="{{ route('user.reservations.index') }}"
                                   class="group relative overflow-hidden rounded-3xl border border-white/10 bg-white/5 p-6 sm:p-8 shadow-[0_20px_60px_-35px_rgba(0,0,0,0.8)] transition hover:bg-white/10 hover:border-white/20">
                                    <div class="absolute -top-24 -right-24 h-64 w-64 rounded-full bg-cyan-500/15 blur-3xl transition group-hover:bg-cyan-500/25"></div>
                                    <div class="relative">
                                        <div class="flex items-start justify-between gap-4">
                                            <div>
                                                <p class="text-xs uppercase tracking-[0.18em] text-slate-300/80">Manage</p>
                                                <h3 class="mt-2 text-2xl font-semibold text-white">Reservasi Saya</h3>
                                                <p class="mt-2 text-slate-300">
                                                    Lihat riwayat reservasi, detail jadwal, dan batalkan jika perlu.
                                                </p>
                                            </div>
                                            <div class="shrink-0 rounded-2xl border border-white/10 bg-white/5 px-4 py-2 text-sm text-slate-200">
                                                Open ↗
                                            </div>
                                        </div>
                                        <div class="mt-6 flex flex-wrap gap-2">
                                            <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs text-slate-200">History</span>
                                            <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs text-slate-200">Schedule</span>
                                            <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs text-slate-200">Cancel</span>
                                        </div>
                                    </div>
                                </a>

                            </div>
                        </section>

                        <!-- Bottom Actions -->
                        <section class="mt-6">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <form action="{{ route('logout') }}" method="POST"
                                      class="rounded-3xl border border-white/10 bg-gradient-to-br from-rose-500/15 via-white/5 to-transparent p-6 sm:p-8 shadow-[0_20px_60px_-35px_rgba(0,0,0,0.8)]">
                                    @csrf
                                    <div class="flex items-start justify-between gap-4">
                                        <div>
                                            <p class="text-xs uppercase tracking-[0.18em] text-slate-300/80">Session</p>
                                            <h3 class="mt-2 text-2xl font-semibold text-white">Logout</h3>
                                            <p class="mt-2 text-slate-300">Keluar dari akun penyewa dengan aman.</p>
                                        </div>
                                        <button type="submit"
                                                class="shrink-0 rounded-2xl bg-gradient-to-b from-rose-500 to-red-600 px-5 py-3 font-semibold text-white shadow-[0_16px_40px_-18px_rgba(244,63,94,0.8)] transition hover:brightness-110 focus:outline-none focus:ring-2 focus:ring-rose-400/60">
                                            Logout
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </section>

                    </main>
                </div>
            </div>
        </div>
    </div>
@endsection
