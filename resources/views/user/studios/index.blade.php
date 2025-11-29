@extends('layouts.app')

@section('title', 'Daftar Studio')

@section('content')
    {{-- Full-bleed wrapper: biar senada dengan gaya admin (tanpa “kotak di dalam kotak”) --}}
    <div class="-mx-4 sm:-mx-6 lg:-mx-8 -my-6 sm:-my-8 lg:-my-12">
        <div class="min-h-[calc(100vh-64px)] bg-slate-950 text-slate-100">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">

                {{-- Header / Hero --}}
                <div class="relative overflow-hidden rounded-3xl border border-white/10 bg-gradient-to-br from-white/10 via-white/5 to-transparent p-6 sm:p-8 shadow-[0_20px_60px_-35px_rgba(0,0,0,0.8)]">
                    <div class="absolute inset-0 pointer-events-none">
                        <div class="absolute -top-24 -right-24 h-64 w-64 rounded-full bg-indigo-500/20 blur-3xl"></div>
                        <div class="absolute -bottom-24 -left-24 h-64 w-64 rounded-full bg-cyan-500/15 blur-3xl"></div>
                    </div>

                    <div class="relative flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-[0.18em] text-slate-300/80">User Studios</p>
                            <h1 class="mt-2 text-3xl sm:text-4xl font-semibold text-white">Daftar Studio</h1>
                            <p class="mt-2 text-slate-300">Pilih studio yang aktif dan siap kamu booking.</p>
                        </div>

                        <div class="flex w-full flex-col gap-2 sm:flex-row lg:w-auto lg:items-center lg:justify-end">
                            <a href="{{ route('user.dashboard') }}"
                               class="inline-flex w-full items-center justify-center rounded-2xl border border-white/10 bg-white/5 px-5 py-3 text-sm font-semibold text-white transition hover:bg-white/10 hover:border-white/20 focus:outline-none focus:ring-2 focus:ring-white/20 sm:w-auto">
                                ← Dashboard
                            </a>

                            <form action="{{ route('user.studios.index') }}" method="GET" class="flex w-full gap-2 sm:w-auto">
                                <input
                                    type="text"
                                    name="search"
                                    value="{{ request('search') }}"
                                    placeholder="Cari nama / lokasi..."
                                    class="w-full sm:w-72 rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-slate-100 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-400/50 focus:border-indigo-400/40"
                                >
                                <button
                                    type="submit"
                                    class="shrink-0 rounded-2xl bg-gradient-to-b from-indigo-500 to-indigo-600 px-5 py-3 text-sm font-semibold text-white shadow-[0_16px_40px_-18px_rgba(99,102,241,0.8)] transition hover:brightness-110 focus:outline-none focus:ring-2 focus:ring-indigo-400/60"
                                >
                                    Cari
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Grid Studios --}}
                <section class="mt-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($studios as $studio)
                            <div class="group relative overflow-hidden rounded-3xl border border-white/10 bg-white/5 shadow-[0_20px_60px_-35px_rgba(0,0,0,0.8)] transition hover:bg-white/10 hover:border-white/20">
                                {{-- Decorative blobs --}}
                                <div class="absolute -top-24 -right-24 h-64 w-64 rounded-full bg-indigo-500/15 blur-3xl transition group-hover:bg-indigo-500/25"></div>
                                <div class="absolute -bottom-24 -left-24 h-64 w-64 rounded-full bg-cyan-500/10 blur-3xl"></div>

                                {{-- ✅ Cover Image --}}
                                <div class="relative">
                                    <img
                                        src="{{ $studio->image_path ? asset('storage/'.$studio->image_path) : asset('images/studio-placeholder.jpg') }}"
                                        alt="{{ $studio->name }}"
                                        class="h-44 w-full object-cover"
                                        loading="lazy"
                                    />

                                    {{-- overlay biar teks lebih “nempel” dan elegan --}}
                                    <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-slate-950/70 via-slate-950/10 to-transparent"></div>

                                    <div class="absolute left-4 top-4">
                                        <span class="inline-flex items-center rounded-full border border-white/15 bg-slate-950/40 px-3 py-1 text-xs text-slate-100 backdrop-blur">
                                            Studio
                                        </span>
                                    </div>

                                    <div class="absolute right-4 top-4">
                                        <span class="inline-flex items-center rounded-2xl border border-white/15 bg-slate-950/40 px-3 py-2 text-xs text-slate-100 backdrop-blur">
                                            View ↗
                                        </span>
                                    </div>
                                </div>

                                <div class="relative p-6">
                                    <div class="flex items-start justify-between gap-4">
                                        <div class="min-w-0">
                                            <h3 class="text-xl font-semibold text-white truncate">{{ $studio->name }}</h3>
                                            <p class="mt-1 text-sm text-slate-300 truncate">
                                                📍 {{ $studio->location ?? 'Lokasi belum diisi' }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="mt-4 space-y-2 text-sm text-slate-300">
                                        <p>👥 Kapasitas: <span class="font-medium text-white">{{ $studio->capacity }}</span> orang</p>
                                        <p>💸 <span class="font-medium text-white">Rp {{ number_format($studio->price_per_hour, 0, ',', '.') }}</span> / jam</p>
                                    </div>

                                    @if($studio->features?->count() || $studio->addons?->count())
                                        <div class="mt-5 flex flex-wrap gap-2">
                                            @foreach(($studio->features ?? collect())->take(2) as $f)
                                                <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs text-slate-200">
                                                    {{ $f->feature_name ?? $f->name }}
                                                </span>
                                            @endforeach

                                            @foreach(($studio->addons ?? collect())->take(1) as $a)
                                                <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs text-slate-200">
                                                    + Addon: {{ $a->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif

                                    <a href="{{ route('user.studios.show', $studio) }}"
                                       class="mt-6 inline-flex w-full items-center justify-center rounded-2xl bg-gradient-to-b from-indigo-500 to-indigo-600 px-4 py-3 text-sm font-semibold text-white shadow-[0_16px_40px_-18px_rgba(99,102,241,0.8)] transition hover:brightness-110 focus:outline-none focus:ring-2 focus:ring-indigo-400/60">
                                        Lihat Detail
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full">
                                <div class="rounded-3xl border border-white/10 bg-white/5 p-8 text-center text-slate-300 shadow-[0_20px_60px_-35px_rgba(0,0,0,0.8)]">
                                    Belum ada studio aktif yang bisa ditampilkan.
                                </div>
                            </div>
                        @endforelse
                    </div>
                </section>

                {{-- Pagination --}}
                <div class="mt-6">
                    <div class="rounded-3xl border border-white/10 bg-white/5 p-4 shadow-[0_20px_60px_-35px_rgba(0,0,0,0.8)]">
                        {{ $studios->links('pagination::tailwind') }}
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
