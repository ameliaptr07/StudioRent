@extends('layouts.app')

@section('title', 'Tambah Admin Baru')

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
                <p class="text-xs uppercase tracking-[0.18em] text-slate-300/80">Manager • Team</p>
                <h1 class="mt-2 text-3xl sm:text-4xl font-semibold text-white">Tambah Admin Baru</h1>
                <p class="mt-2 max-w-2xl text-slate-300">
                    Menambahkan akun admin baru untuk studio.
                </p>

                {{-- Back to Dashboard --}}
                <div class="mt-5 flex flex-wrap items-center gap-3">
                    <a href="{{ route('manager.dashboard') }}"
                       class="inline-flex items-center justify-center rounded-2xl px-5 py-2.5 text-sm font-semibold border border-white/10 bg-white/5 text-slate-100 transition hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-white/20">
                        ← Kembali ke Dashboard
                    </a>
                </div>
            </div>

            <div class="hidden sm:flex items-center gap-2">
                <span class="inline-flex items-center rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs text-slate-200">
                    Create
                </span>
                <span class="inline-flex items-center rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs text-slate-200">
                    Admin
                </span>
            </div>
        </div>
    </div>

    {{-- Form Card --}}
    <form action="{{ route('manager.team.store') }}" method="POST" class="mt-6 rounded-3xl border border-white/10 bg-white/5 backdrop-blur-xl shadow-[0_20px_60px_-35px_rgba(0,0,0,0.8)] overflow-hidden">
        @csrf
        <div class="p-5 sm:p-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                {{-- Left --}}
                <div class="lg:col-span-8 space-y-6">

                    <div>
                        <label class="block text-sm font-semibold text-slate-200 mb-2">Nama</label>
                        <input type="text" name="name" value="{{ old('name') }}"
                               class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-slate-100 placeholder:text-slate-400
                                      focus:outline-none focus:ring-2 focus:ring-indigo-400/50 focus:border-indigo-400/40"
                               placeholder="Contoh: Admin Studio A" required>
                        @error('name') <p class="mt-2 text-xs text-rose-200">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-200 mb-2">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                               class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-slate-100 placeholder:text-slate-400
                                      focus:outline-none focus:ring-2 focus:ring-indigo-400/50 focus:border-indigo-400/40"
                               placeholder="Contoh: admin@studio.com" required>
                        @error('email') <p class="mt-2 text-xs text-rose-200">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-200 mb-2">Password</label>
                        <input type="password" name="password"
                               class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-slate-100 placeholder:text-slate-400
                                      focus:outline-none focus:ring-2 focus:ring-indigo-400/50 focus:border-indigo-400/40"
                               placeholder="Password" required>
                        @error('password') <p class="mt-2 text-xs text-rose-200">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-200 mb-2">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation"
                               class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-slate-100 placeholder:text-slate-400
                                      focus:outline-none focus:ring-2 focus:ring-indigo-400/50 focus:border-indigo-400/40"
                               placeholder="Konfirmasi Password" required>
                    </div>

                </div>

                {{-- Right --}}
                <div class="lg:col-span-4 space-y-5">

                    {{-- Status --}}
                    <div class="rounded-3xl border border-white/10 bg-white/5 p-5 sm:p-6">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <label class="block text-sm font-semibold text-slate-200">Status Admin</label>
                                <p class="mt-1 text-xs text-slate-400">Pilih status aktif atau tidak aktif.</p>
                            </div>
                        </div>

                        <div class="mt-4 max-h-56 overflow-auto cool-scroll rounded-2xl border border-white/10 bg-slate-950/30 p-2">
                            <div class="grid gap-2">
                                <label class="flex items-center justify-between gap-3 rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-slate-200 transition hover:bg-white/10 hover:border-white/20">
                                    <span class="truncate">Aktif</span>
                                    <input type="radio" name="is_active" value="1" class="h-4 w-4 rounded border-white/20 bg-white/5 text-indigo-500 focus:ring-indigo-400/50" checked>
                                </label>
                                <label class="flex items-center justify-between gap-3 rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-slate-200 transition hover:bg-white/10 hover:border-white/20">
                                    <span class="truncate">Tidak Aktif</span>
                                    <input type="radio" name="is_active" value="0" class="h-4 w-4 rounded border-white/20 bg-white/5 text-indigo-500 focus:ring-indigo-400/50">
                                </label>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 border-t border-white/10 bg-white/5 px-5 py-4 sm:px-8">
            <a href="{{ route('manager.dashboard') }}"
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
