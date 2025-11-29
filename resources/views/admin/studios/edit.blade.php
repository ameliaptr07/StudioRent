@extends('layouts.app')

@section('title', 'Edit Studio')

@section('content')
<style>
    /* Scrollbar biar senada (Chrome/Edge + Firefox) */
    .cool-scroll { scrollbar-width: thin; scrollbar-color: rgba(148,163,184,.35) rgba(15,23,42,.35); }
    .cool-scroll::-webkit-scrollbar { width: 10px; height: 10px; }
    .cool-scroll::-webkit-scrollbar-track { background: rgba(15,23,42,.35); border-radius: 999px; }
    .cool-scroll::-webkit-scrollbar-thumb {
        background: rgba(148,163,184,.35);
        border-radius: 999px;
        border: 2px solid rgba(15,23,42,.75);
    }
    .cool-scroll::-webkit-scrollbar-thumb:hover { background: rgba(148,163,184,.55); }

    /* Status chip real-time (tanpa JS) */
    .status-wrap .chip { display: none; }
    .status-wrap:has(input[value="active"]:checked) .chip-active { display: inline-flex; }
    .status-wrap:has(input[value="inactive"]:checked) .chip-inactive { display: inline-flex; }
</style>

<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
    {{-- Header --}}
    <div class="relative overflow-hidden rounded-3xl border border-white/10 bg-gradient-to-br from-white/10 via-white/5 to-transparent p-6 sm:p-8 shadow-[0_20px_60px_-35px_rgba(0,0,0,0.8)]">
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute -top-24 -right-24 h-64 w-64 rounded-full bg-indigo-500/20 blur-3xl"></div>
            <div class="absolute -bottom-24 -left-24 h-64 w-64 rounded-full bg-cyan-500/15 blur-3xl"></div>
        </div>

        <div class="relative flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.18em] text-slate-300/80">Admin • Studios</p>
                <h1 class="mt-2 text-3xl sm:text-4xl font-semibold text-white">Edit Studio</h1>
                <p class="mt-2 max-w-2xl text-slate-300">
                    Perbarui detail studio dengan aman. Pastikan harga, kapasitas, dan status sesuai sebelum disimpan.
                </p>

                <div class="mt-5 flex flex-wrap items-center gap-3">
                    <a href="{{ route('admin.studios.index') }}"
                       class="inline-flex items-center justify-center rounded-2xl px-5 py-2.5 text-sm font-semibold border border-white/10 bg-white/5 text-slate-100 transition hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-white/20">
                        ← Kembali ke Daftar Studio
                    </a>

                    <span class="inline-flex items-center rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs text-slate-200">
                        ID: {{ $studio->id }}
                    </span>
                </div>
            </div>

            <div class="hidden sm:flex items-center gap-2">
                <span class="inline-flex items-center rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs text-slate-200">
                    Update
                </span>
                <span class="inline-flex items-center rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs text-slate-200">
                    Studios
                </span>
            </div>
        </div>
    </div>

    @php
        // preselect addons/features (old() > relasi)
        $selectedAddons   = old('addons', $studio->addons->pluck('id')->toArray());
        $selectedFeatures = old('features', $studio->features->pluck('id')->toArray());

        $currentStatus = strtolower(old('status', $studio->status ?? 'active'));

        // bantu tampilkan chip selected (collection)
        $selectedAddonModels = $addons->whereIn('id', $selectedAddons);
        $selectedFeatureModels = $features->whereIn('id', $selectedFeatures);
    @endphp

    {{-- Form Card --}}
    <form action="{{ route('admin.studios.update', $studio) }}" method="POST" enctype="multipart/form-data"
          class="mt-6 rounded-3xl border border-white/10 bg-white/5 backdrop-blur-xl shadow-[0_20px_60px_-35px_rgba(0,0,0,0.8)] overflow-hidden">
        @csrf
        @method('PUT')

        <div class="p-5 sm:p-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                {{-- Left: Main fields --}}
                <div class="lg:col-span-8 space-y-6">

                    <div>
                        <label class="block text-sm font-semibold text-slate-200 mb-2">Nama</label>
                        <input type="text" name="name" value="{{ old('name', $studio->name) }}"
                               class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-slate-100 placeholder:text-slate-400
                                      focus:outline-none focus:ring-2 focus:ring-indigo-400/50 focus:border-indigo-400/40"
                               placeholder="Contoh: Studio Foto Aurora" required>
                        @error('name') <p class="mt-2 text-xs text-rose-200">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-200 mb-2">Deskripsi</label>
                        <textarea name="description" rows="5"
                                  class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-slate-100 placeholder:text-slate-400
                                         focus:outline-none focus:ring-2 focus:ring-indigo-400/50 focus:border-indigo-400/40"
                                  placeholder="Tulis deskripsi singkat studio: konsep, fasilitas, vibe, dan hal penting lainnya.">{{ old('description', $studio->description) }}</textarea>
                        @error('description') <p class="mt-2 text-xs text-rose-200">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-slate-200 mb-2">Kapasitas (orang)</label>
                            <input type="number" name="capacity" value="{{ old('capacity', $studio->capacity) }}"
                                   class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-slate-100 placeholder:text-slate-400
                                          focus:outline-none focus:ring-2 focus:ring-indigo-400/50 focus:border-indigo-400/40"
                                   min="1" required>
                            @error('capacity') <p class="mt-2 text-xs text-rose-200">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-200 mb-2">Harga per jam</label>
                            <input type="number" name="price_per_hour" value="{{ old('price_per_hour', $studio->price_per_hour) }}"
                                   class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-slate-100 placeholder:text-slate-400
                                          focus:outline-none focus:ring-2 focus:ring-indigo-400/50 focus:border-indigo-400/40"
                                   min="0" step="1000" required>
                            @error('price_per_hour') <p class="mt-2 text-xs text-rose-200">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Addons (Checkbox scrollable) --}}
                    <div class="rounded-3xl border border-white/10 bg-white/5 p-5 sm:p-6">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <label class="block text-sm font-semibold text-slate-200">Pilih Addon</label>
                                <p class="mt-1 text-xs text-slate-400">Centang addon yang tersedia untuk studio ini.</p>
                            </div>

                            <span class="shrink-0 rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs text-slate-200">
                                {{ count($selectedAddons) }} dipilih
                            </span>
                        </div>

                        @if($selectedAddonModels->count())
                            <div class="mt-3 flex flex-wrap gap-2">
                                @foreach($selectedAddonModels as $a)
                                    <span class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs text-slate-200">
                                        ✓ {{ $a->name }}
                                    </span>
                                @endforeach
                            </div>
                        @endif

                        <div class="mt-4 max-h-56 overflow-auto cool-scroll rounded-2xl border border-white/10 bg-slate-950/30 p-2">
                            <div class="grid gap-2">
                                @foreach($addons as $addon)
                                    <label class="flex items-center justify-between gap-3 rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-slate-200 transition hover:bg-white/10 hover:border-white/20">
                                        <span class="truncate">➕ {{ $addon->name }}</span>

                                        <span class="flex items-center gap-3 shrink-0">
                                            <span class="text-xs text-slate-400">Rp {{ number_format($addon->price, 0, ',', '.') }}</span>
                                            <input
                                                type="checkbox"
                                                name="addons[]"
                                                value="{{ $addon->id }}"
                                                class="h-4 w-4 rounded border-white/20 bg-white/5 text-indigo-500 focus:ring-indigo-400/50"
                                                {{ in_array($addon->id, $selectedAddons) ? 'checked' : '' }}
                                            >
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        @error('addons') <p class="mt-2 text-xs text-rose-200">{{ $message }}</p> @enderror
                    </div>

                    {{-- Features (Checkbox scrollable) --}}
                    <div class="rounded-3xl border border-white/10 bg-white/5 p-5 sm:p-6">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <label class="block text-sm font-semibold text-slate-200">Pilih Features</label>
                                <p class="mt-1 text-xs text-slate-400">Fasilitas studio (mis. soundproof, AC, green screen).</p>
                            </div>

                            <span class="shrink-0 rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs text-slate-200">
                                {{ count($selectedFeatures) }} dipilih
                            </span>
                        </div>

                        @if($selectedFeatureModels->count())
                            <div class="mt-3 flex flex-wrap gap-2">
                                @foreach($selectedFeatureModels as $f)
                                    <span class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs text-slate-200">
                                        ✓ {{ $f->feature_name ?? $f->name }}
                                    </span>
                                @endforeach
                            </div>
                        @endif

                        <div class="mt-4 max-h-56 overflow-auto cool-scroll rounded-2xl border border-white/10 bg-slate-950/30 p-2">
                            <div class="grid gap-2">
                                @foreach($features as $feature)
                                    @php
                                        $label = $feature->feature_name ?? $feature->name; // ✅ FIX label
                                    @endphp

                                    <label class="flex items-center justify-between gap-3 rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-slate-200 transition hover:bg-white/10 hover:border-white/20">
                                        <span class="truncate">✅ {{ $label }}</span>

                                        <input
                                            type="checkbox"
                                            name="features[]"
                                            value="{{ $feature->id }}"
                                            class="h-4 w-4 rounded border-white/20 bg-white/5 text-indigo-500 focus:ring-indigo-400/50"
                                            {{ in_array($feature->id, $selectedFeatures) ? 'checked' : '' }}
                                        >
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        @error('features') <p class="mt-2 text-xs text-rose-200">{{ $message }}</p> @enderror
                    </div>

                </div>

                {{-- Right: Status + Image + helper --}}
                <div class="lg:col-span-4 space-y-5">

                    {{-- ✅ Status (Toggle pills real-time) --}}
                    <div class="status-wrap rounded-3xl border border-white/10 bg-white/5 p-5 sm:p-6">
                        <p class="text-sm font-semibold text-slate-200">Status</p>
                        <p class="mt-1 text-xs text-slate-400">Klik untuk ubah, lalu simpan.</p>

                        <div class="mt-4 rounded-2xl border border-white/10 bg-white/5 p-1">
                            <div class="grid grid-cols-2 gap-1">
                                {{-- ACTIVE --}}
                                <label class="cursor-pointer select-none">
                                    <input
                                        type="radio"
                                        name="status"
                                        value="active"
                                        class="sr-only peer"
                                        {{ $currentStatus === 'active' ? 'checked' : '' }}
                                    >
                                    <div class="rounded-xl px-4 py-2 text-center text-sm font-semibold transition
                                                border border-transparent text-slate-200 hover:bg-white/5
                                                peer-checked:bg-emerald-500/20 peer-checked:text-emerald-200 peer-checked:border-emerald-400/20
                                                peer-focus-visible:ring-2 peer-focus-visible:ring-emerald-400/40">
                                        ✅ Active
                                    </div>
                                </label>

                                {{-- INACTIVE --}}
                                <label class="cursor-pointer select-none">
                                    <input
                                        type="radio"
                                        name="status"
                                        value="inactive"
                                        class="sr-only peer"
                                        {{ $currentStatus === 'inactive' ? 'checked' : '' }}
                                    >
                                    <div class="rounded-xl px-4 py-2 text-center text-sm font-semibold transition
                                                border border-transparent text-slate-200 hover:bg-white/5
                                                peer-checked:bg-rose-500/20 peer-checked:text-rose-200 peer-checked:border-rose-400/20
                                                peer-focus-visible:ring-2 peer-focus-visible:ring-rose-400/40">
                                        ⛔ Inactive
                                    </div>
                                </label>
                            </div>
                        </div>

                        @error('status') <p class="mt-2 text-xs text-rose-200">{{ $message }}</p> @enderror

                        <div class="mt-4">
                            <span class="chip chip-active inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold border border-emerald-400/20 bg-emerald-400/10 text-emerald-200">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-300"></span>
                                Aktif — tampil di user
                            </span>

                            <span class="chip chip-inactive inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold border border-rose-400/20 bg-rose-400/10 text-rose-200">
                                <span class="h-1.5 w-1.5 rounded-full bg-rose-300"></span>
                                Nonaktif — disembunyikan
                            </span>
                        </div>
                    </div>

                    {{-- Upload/Replace Image --}}
                    <div class="rounded-3xl border border-white/10 bg-white/5 p-5 sm:p-6">
                        <p class="text-sm font-semibold text-slate-200">Gambar Studio</p>

                        @if(!empty($studio->image_path))
                            <div class="mt-3">
                                <img
                                    src="{{ asset('storage/'.$studio->image_path) }}"
                                    alt="{{ $studio->name }}"
                                    class="h-44 w-full rounded-2xl border border-white/10 object-cover"
                                />
                                <p class="mt-2 text-xs text-slate-400">Gambar saat ini. Upload file baru untuk mengganti.</p>
                            </div>
                        @else
                            <p class="mt-2 text-xs text-slate-400">Belum ada gambar. Upload untuk menambahkan cover.</p>
                        @endif

                        <input
                            type="file"
                            name="image"
                            accept="image/jpeg,image/jpg"
                            class="mt-3 w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-slate-100
                                   file:mr-4 file:rounded-xl file:border-0 file:bg-white/10 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-slate-100
                                   hover:file:bg-white/15 focus:outline-none focus:ring-2 focus:ring-indigo-400/50 focus:border-indigo-400/40"
                        />

                        <p class="mt-2 text-xs text-slate-400">Maks 2MB. Format: JPG/JPEG.</p>

                        @error('image')
                            <p class="mt-2 text-xs text-rose-200">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="rounded-3xl border border-white/10 bg-gradient-to-br from-indigo-500/15 via-white/5 to-transparent p-5 sm:p-6">
                        <p class="text-sm text-slate-200 font-semibold">Catatan singkat</p>
                        <p class="mt-2 text-sm text-slate-300 leading-relaxed">
                            Pastikan <span class="text-white/90 font-medium">nama</span> mudah dibaca,
                            <span class="text-white/90 font-medium">deskripsi</span> informatif, dan
                            <span class="text-white/90 font-medium">harga</span> sesuai paket rental-mu.
                        </p>
                    </div>

                </div>

            </div>
        </div>

        {{-- Actions --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 border-t border-white/10 bg-white/5 px-5 py-4 sm:px-8">
            <a href="{{ route('admin.studios.index') }}"
               class="inline-flex items-center justify-center rounded-2xl px-5 py-3 text-sm font-semibold border border-white/10 bg-white/5 text-slate-100 transition hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-white/20">
                Batal
            </a>

            <button type="submit"
                    class="inline-flex items-center justify-center rounded-2xl bg-gradient-to-b from-indigo-500 to-indigo-600 px-6 py-3 text-sm font-semibold text-white shadow-[0_16px_40px_-18px_rgba(99,102,241,0.7)] transition hover:brightness-110 focus:outline-none focus:ring-2 focus:ring-indigo-400/60">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
