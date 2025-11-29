@extends('layouts.app')

@section('title', 'Detail Studio')

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

                    <div class="relative flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                        <div class="min-w-0">
                            <p class="text-xs uppercase tracking-[0.18em] text-slate-300/80">User Studio Detail</p>
                            <h1 class="mt-2 text-3xl sm:text-4xl font-semibold text-white truncate">
                                {{ $studio->name }}
                            </h1>
                            <p class="mt-2 text-slate-300 truncate">
                                📍 {{ $studio->location ?: 'Lokasi belum diisi' }}
                            </p>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-2">
                            <a href="{{ route('user.studios.index') }}"
                               class="inline-flex items-center justify-center rounded-2xl border border-white/10 bg-white/5 px-5 py-3 text-sm font-semibold text-white transition hover:bg-white/10 hover:border-white/20 focus:outline-none focus:ring-2 focus:ring-white/20">
                                ← Kembali
                            </a>
                            <a href="{{ route('user.reservations.index') }}"
                               class="inline-flex items-center justify-center rounded-2xl bg-gradient-to-b from-indigo-500 to-indigo-600 px-5 py-3 text-sm font-semibold text-white shadow-[0_16px_40px_-18px_rgba(99,102,241,0.8)] transition hover:brightness-110 focus:outline-none focus:ring-2 focus:ring-indigo-400/60">
                                Reservasi Saya
                            </a>
                        </div>
                    </div>
                </div>

                {{-- ✅ Cover Image --}}
                <div class="mt-6 relative overflow-hidden rounded-3xl border border-white/10 bg-white/5 shadow-[0_20px_60px_-35px_rgba(0,0,0,0.8)]">
                    <div class="absolute inset-0 pointer-events-none">
                        <div class="absolute -top-24 -right-24 h-64 w-64 rounded-full bg-indigo-500/10 blur-3xl"></div>
                        <div class="absolute -bottom-24 -left-24 h-64 w-64 rounded-full bg-cyan-500/10 blur-3xl"></div>
                    </div>

                    <div class="relative">
                        <img
                            src="{{ $studio->image_path ? asset('storage/'.$studio->image_path) : asset('images/studio-placeholder.jpg') }}"
                            alt="{{ $studio->name }}"
                            class="h-64 sm:h-72 w-full object-cover"
                            loading="lazy"
                        />
                        <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-slate-950/70 via-slate-950/10 to-transparent"></div>

                        <div class="absolute left-5 top-5">
                            <span class="inline-flex items-center rounded-full border border-white/15 bg-slate-950/40 px-3 py-1 text-xs text-slate-100 backdrop-blur">
                                Cover Studio
                            </span>
                        </div>

                        <div class="absolute right-5 bottom-5">
                            <span class="inline-flex items-center rounded-2xl border border-white/15 bg-slate-950/40 px-4 py-2 text-xs text-slate-100 backdrop-blur">
                                Rp {{ number_format($studio->price_per_hour, 0, ',', '.') }} / jam • 👥 {{ $studio->capacity }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="grid gap-6 lg:grid-cols-3 mt-6">

                    {{-- Deskripsi --}}
                    <div class="relative overflow-hidden rounded-3xl border border-white/10 bg-white/5 p-6 shadow-[0_20px_60px_-35px_rgba(0,0,0,0.8)]">
                        <div class="absolute -top-24 -right-24 h-64 w-64 rounded-full bg-indigo-500/10 blur-3xl"></div>

                        <div class="relative">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="text-xs uppercase tracking-[0.18em] text-slate-300/80">Info</p>
                                    <h2 class="mt-2 text-2xl font-semibold text-white">Deskripsi</h2>
                                </div>
                                <span class="shrink-0 rounded-2xl border border-white/10 bg-white/5 px-4 py-2 text-sm text-slate-200">
                                    Detail ↗
                                </span>
                            </div>

                            <p class="mt-4 text-sm leading-relaxed text-slate-300">
                                {{ $studio->description ?: 'Belum ada deskripsi.' }}
                            </p>

                            <div class="mt-6 grid grid-cols-2 gap-3">
                                <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                                    <p class="text-xs uppercase tracking-[0.18em] text-slate-300/80">Kapasitas</p>
                                    <p class="mt-2 text-xl font-semibold text-white">{{ $studio->capacity }} orang</p>
                                </div>
                                <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                                    <p class="text-xs uppercase tracking-[0.18em] text-slate-300/80">Harga / jam</p>
                                    <p class="mt-2 text-xl font-semibold text-white">
                                        Rp {{ number_format($studio->price_per_hour, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Fasilitas --}}
                    <div class="relative overflow-hidden rounded-3xl border border-white/10 bg-white/5 p-6 shadow-[0_20px_60px_-35px_rgba(0,0,0,0.8)]">
                        <div class="absolute -bottom-24 -left-24 h-64 w-64 rounded-full bg-cyan-500/10 blur-3xl"></div>

                        <div class="relative">
                            <p class="text-xs uppercase tracking-[0.18em] text-slate-300/80">Facilities</p>
                            <h2 class="mt-2 text-2xl font-semibold text-white">Fasilitas</h2>

                            <div class="mt-5">
                                <p class="text-xs uppercase tracking-[0.18em] text-slate-300/80 mb-3">Features</p>

                                @if($studio->features && $studio->features->count())
                                    <ul class="space-y-2">
                                        @foreach($studio->features as $feature)
                                            <li class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-slate-200">
                                                ✅ {{ $feature->name ?? $feature->feature_name }}
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-sm text-slate-400">Belum ada feature.</p>
                                @endif
                            </div>

                            <div class="mt-6">
                                <p class="text-xs uppercase tracking-[0.18em] text-slate-300/80 mb-3">Addons</p>

                                @if($studio->addons && $studio->addons->count())
                                    <ul class="space-y-2">
                                        @foreach($studio->addons as $addon)
                                            <li class="flex items-center justify-between gap-3 rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-slate-200">
                                                <span class="truncate">➕ {{ $addon->name }}</span>
                                                <span class="shrink-0 text-slate-300">
                                                    Rp {{ number_format($addon->price, 0, ',', '.') }}
                                                </span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-sm text-slate-400">Belum ada addon.</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Buat Reservasi --}}
                    <div class="relative overflow-hidden rounded-3xl border border-white/10 bg-white/5 p-6 shadow-[0_20px_60px_-35px_rgba(0,0,0,0.8)]">
                        <div class="absolute -top-24 -right-24 h-64 w-64 rounded-full bg-emerald-500/10 blur-3xl"></div>

                        <div class="relative">
                            <p class="text-xs uppercase tracking-[0.18em] text-slate-300/80">Booking</p>
                            <h2 class="mt-2 text-2xl font-semibold text-white">Buat Reservasi</h2>
                            <p class="mt-2 text-sm text-slate-300">
                                Pilih tanggal & jam, lalu klik tombolnya. Studio bakal kami “kunci” untuk kamu 😄
                            </p>

                            <form action="{{ route('user.reservations.store', $studio) }}" method="POST" class="space-y-4 mt-6">
                                @csrf

                                <div>
                                    <label class="block text-xs uppercase tracking-[0.18em] text-slate-300/80 mb-2">Tanggal</label>
                                    <input type="date" name="reservation_date" value="{{ old('reservation_date') }}"
                                           class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-slate-100 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-400/50 focus:border-indigo-400/40"
                                           required>
                                    @error('reservation_date')
                                        <p class="text-xs text-rose-200 mt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs uppercase tracking-[0.18em] text-slate-300/80 mb-2">Jam Mulai</label>
                                        <input type="time" name="start_time" value="{{ old('start_time') }}"
                                               class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-400/50 focus:border-indigo-400/40"
                                               required>
                                        @error('start_time')
                                            <p class="text-xs text-rose-200 mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-xs uppercase tracking-[0.18em] text-slate-300/80 mb-2">Jam Selesai</label>
                                        <input type="time" name="end_time" value="{{ old('end_time') }}"
                                               class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-400/50 focus:border-indigo-400/40"
                                               required>
                                        @error('end_time')
                                            <p class="text-xs text-rose-200 mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div>
                                    <p class="block text-xs uppercase tracking-[0.18em] text-slate-300/80 mb-3">Addon (opsional)</p>

                                    <div class="space-y-2">
                                        @forelse($studio->addons as $addon)
                                            <label class="flex items-center justify-between gap-3 rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm transition hover:bg-white/10 hover:border-white/20">
                                                <span class="text-slate-200 truncate">➕ {{ $addon->name }}</span>

                                                <span class="flex items-center gap-3 shrink-0">
                                                    <span class="text-slate-300">Rp {{ number_format($addon->price, 0, ',', '.') }}</span>
                                                    <input type="checkbox" name="addons[]" value="{{ $addon->id }}"
                                                           class="h-4 w-4 rounded border-white/20 bg-white/5 text-indigo-500 focus:ring-indigo-400/50"
                                                           {{ in_array($addon->id, old('addons', [])) ? 'checked' : '' }}>
                                                </span>
                                            </label>
                                        @empty
                                            <p class="text-sm text-slate-400">Tidak ada addon untuk studio ini.</p>
                                        @endforelse
                                    </div>

                                    @error('addons')
                                        <p class="text-xs text-rose-200 mt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                                <button type="submit"
                                        class="w-full rounded-2xl bg-gradient-to-b from-indigo-500 to-indigo-600 px-4 py-3 text-sm font-semibold text-white shadow-[0_16px_40px_-18px_rgba(99,102,241,0.8)] transition hover:brightness-110 focus:outline-none focus:ring-2 focus:ring-indigo-400/60">
                                    Buat Reservasi
                                </button>

                                <p class="text-xs text-slate-400 text-center">
                                    Tips: status awal biasanya <b class="text-slate-200">pending</b> sampai admin/manager konfirmasi.
                                </p>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
