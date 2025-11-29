@extends('layouts.app')

@section('title', 'Tambah Addon')

@section('content')
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
    {{-- Header --}}
    <div class="relative overflow-hidden rounded-3xl border border-white/10 bg-gradient-to-br from-white/10 via-white/5 to-transparent p-6 sm:p-8 shadow-[0_20px_60px_-35px_rgba(0,0,0,0.8)]">
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute -top-24 -right-24 h-64 w-64 rounded-full bg-indigo-500/20 blur-3xl"></div>
            <div class="absolute -bottom-24 -left-24 h-64 w-64 rounded-full bg-cyan-500/15 blur-3xl"></div>
        </div>

        <div class="relative flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.18em] text-slate-300/80">Admin • Addons</p>
                <h1 class="mt-2 text-3xl sm:text-4xl font-semibold text-white">Tambah Addon</h1>
                <p class="mt-2 max-w-2xl text-slate-300">
                    Buat layanan tambahan baru agar paket rental kamu makin fleksibel. Nama jelas, deskripsi ringkas, harga konsisten.
                </p>

                <div class="mt-5">
                    <a href="{{ route('admin.addons.index') }}"
                       class="inline-flex items-center justify-center rounded-2xl px-5 py-2.5 text-sm font-semibold border border-white/10 bg-white/5 text-slate-100 transition hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-white/20">
                        ← Kembali ke Daftar Addon
                    </a>
                </div>
            </div>

            <div class="hidden sm:flex items-center gap-2">
                <span class="inline-flex items-center rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs text-slate-200">
                    Create
                </span>
                <span class="inline-flex items-center rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs text-slate-200">
                    Addons
                </span>
            </div>
        </div>
    </div>

    {{-- Form Card --}}
    <form action="{{ route('admin.addons.store') }}" method="POST"
          class="mt-6 rounded-3xl border border-white/10 bg-white/5 backdrop-blur-xl shadow-[0_20px_60px_-35px_rgba(0,0,0,0.8)] overflow-hidden">
        @csrf

        <div class="p-5 sm:p-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                {{-- Left: form --}}
                <div class="lg:col-span-8 space-y-5">
                    <div>
                        <label class="block text-sm font-semibold text-slate-200 mb-2">Nama Addon</label>
                        <input type="text" name="name" value="{{ old('name') }}"
                               class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-slate-100 placeholder:text-slate-400
                                      focus:outline-none focus:ring-2 focus:ring-indigo-400/50 focus:border-indigo-400/40"
                               placeholder="Contoh: Lighting Kit Basic"
                               required>
                        @error('name') <p class="mt-2 text-xs text-rose-200">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-200 mb-2">Deskripsi</label>
                        <textarea name="description" rows="5"
                                  class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-slate-100 placeholder:text-slate-400
                                         focus:outline-none focus:ring-2 focus:ring-indigo-400/50 focus:border-indigo-400/40"
                                  placeholder="Jelaskan isi addon, benefit, dan ketentuannya (jika ada).">{{ old('description') }}</textarea>
                        @error('description') <p class="mt-2 text-xs text-rose-200">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-200 mb-2">Harga</label>
                        <input type="number" name="price" value="{{ old('price') }}"
                               class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-slate-100 placeholder:text-slate-400
                                      focus:outline-none focus:ring-2 focus:ring-indigo-400/50 focus:border-indigo-400/40"
                               min="0" step="1000" required>
                        @error('price') <p class="mt-2 text-xs text-rose-200">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Right: helper --}}
                <div class="lg:col-span-4 space-y-5">
                    <div class="rounded-3xl border border-white/10 bg-gradient-to-br from-indigo-500/15 via-white/5 to-transparent p-5 sm:p-6">
                        <p class="text-sm text-slate-200 font-semibold">Contoh addon populer</p>
                        <ul class="mt-2 space-y-2 text-sm text-slate-300">
                            <li class="flex gap-2"><span class="text-slate-200">•</span> Mic tambahan</li>
                            <li class="flex gap-2"><span class="text-slate-200">•</span> Lighting kit</li>
                            <li class="flex gap-2"><span class="text-slate-200">•</span> Crew 1–2 orang</li>
                            <li class="flex gap-2"><span class="text-slate-200">•</span> Make-up / stylist</li>
                            <li class="flex gap-2"><span class="text-slate-200">•</span> Props tematik</li>
                        </ul>
                    </div>

                    <div class="rounded-3xl border border-white/10 bg-white/5 p-5 sm:p-6">
                        <p class="text-sm text-slate-200 font-semibold">Catatan harga</p>
                        <p class="mt-2 text-sm text-slate-300">
                            Pakai kelipatan <span class="text-white/90 font-medium">1000</span> agar konsisten di tampilan dan laporan.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 border-t border-white/10 bg-white/5 px-5 py-4 sm:px-8">
            <a href="{{ route('admin.addons.index') }}"
               class="inline-flex items-center justify-center rounded-2xl px-5 py-3 text-sm font-semibold border border-white/10 bg-white/5 text-slate-100 transition hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-white/20">
                Batal
            </a>

            <button type="submit"
                    class="inline-flex items-center justify-center rounded-2xl bg-gradient-to-b from-indigo-500 to-indigo-600 px-6 py-3 text-sm font-semibold text-white shadow-[0_16px_40px_-18px_rgba(99,102,241,0.7)] transition hover:brightness-110 focus:outline-none focus:ring-2 focus:ring-indigo-400/60">
                Simpan
            </button>
        </div>
    </form>
</div>
@endsection
