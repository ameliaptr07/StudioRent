@extends('layouts.app')

@section('title', 'Kelola Studio')

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
                <p class="text-xs uppercase tracking-[0.18em] text-slate-300/80">Admin • Studios</p>
                <h1 class="mt-2 text-3xl sm:text-4xl font-semibold text-white">Kelola Studio</h1>
                <p class="mt-2 max-w-2xl text-slate-300">
                    Atur data studio dan aktif/nonaktifkan studio agar tampil di sisi user.
                </p>

                {{-- Back to Dashboard --}}
                <div class="mt-5 flex flex-wrap items-center gap-3">
                    <a href="{{ url('/admin/dashboard') }}"
                       class="inline-flex items-center justify-center rounded-2xl px-5 py-2.5 text-sm font-semibold border border-white/10 bg-white/5 text-slate-100 transition hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-white/20">
                        ← Kembali ke Dashboard
                    </a>

                    <span class="hidden sm:inline-flex items-center rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs text-slate-200">
                        Total: {{ $studios->count() }}
                    </span>
                </div>
            </div>

            <a href="{{ route('admin.studios.create') }}"
               class="inline-flex items-center justify-center rounded-2xl bg-gradient-to-b from-indigo-500 to-indigo-600 px-6 py-3 text-sm font-semibold text-white shadow-[0_16px_40px_-18px_rgba(99,102,241,0.7)] transition hover:brightness-110 focus:outline-none focus:ring-2 focus:ring-indigo-400/60">
                + Tambah Studio
            </a>
        </div>
    </div>

    {{-- Flash message --}}
    @if(session('status'))
        <div class="mt-6 rounded-2xl border border-emerald-400/20 bg-emerald-400/10 px-4 py-3 text-sm text-emerald-200">
            {{ session('status') }}
        </div>
    @endif

    {{-- Table Card --}}
    <div class="mt-6 rounded-3xl border border-white/10 bg-white/5 backdrop-blur-xl shadow-[0_20px_60px_-35px_rgba(0,0,0,0.8)] overflow-hidden">
        <div class="flex items-center justify-between gap-3 p-5 sm:p-6 border-b border-white/10">
            <div>
                <h2 class="text-base sm:text-lg font-semibold text-white">Daftar Studio</h2>
                <p class="mt-1 text-xs sm:text-sm text-slate-300">
                    Klik <span class="text-white/90 font-medium">Edit</span> untuk ubah detail, atau gunakan tombol status untuk tampil/sembunyikan di halaman user.
                </p>
            </div>
            <div class="hidden sm:flex items-center gap-2">
                <span class="inline-flex items-center rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs text-slate-200">
                    Total: {{ $studios->count() }}
                </span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-white/5">
                    <tr class="text-left text-slate-300">
                        <th class="px-6 py-4 font-medium">Nama</th>
                        <th class="px-6 py-4 font-medium">Kapasitas</th>
                        <th class="px-6 py-4 font-medium">Harga / jam</th>
                        <th class="px-6 py-4 font-medium">Status</th>
                        <th class="px-6 py-4 text-right font-medium">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-white/10">
                @forelse($studios as $studio)
                    @php
                        $isActive = strtolower($studio->status) === 'active';
                    @endphp

                    <tr class="group hover:bg-white/5 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <span class="inline-flex h-9 w-9 items-center justify-center rounded-2xl border border-white/10 bg-white/5 text-white/90 shadow-sm">
                                    🎬
                                </span>
                                <div>
                                    <p class="font-semibold text-white leading-tight">
                                        {{ $studio->name }}
                                    </p>
                                    <p class="text-xs text-slate-400 mt-1">
                                        ID: {{ $studio->id }}
                                    </p>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4 text-slate-200">
                            {{ $studio->capacity }}
                        </td>

                        <td class="px-6 py-4 text-slate-200">
                            Rp {{ number_format($studio->price_per_hour, 0, ',', '.') }}
                        </td>

                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold border
                                {{ $isActive ? 'border-emerald-400/20 bg-emerald-400/10 text-emerald-200' : 'border-white/10 bg-white/5 text-slate-200' }}">
                                <span class="h-1.5 w-1.5 rounded-full {{ $isActive ? 'bg-emerald-300' : 'bg-slate-400' }}"></span>
                                {{ $isActive ? 'Active' : 'Inactive' }}
                            </span>
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex justify-end items-center gap-3">
                                {{-- Toggle Status --}}
                                <form action="{{ route('admin.studios.toggleStatus', $studio) }}"
                                      method="POST"
                                      class="inline"
                                      onsubmit="return confirm('Ubah status studio ini?');">
                                    @csrf
                                    @method('PATCH')

                                    <button type="submit"
                                        class="inline-flex items-center justify-center rounded-2xl px-4 py-2 text-xs font-semibold border transition focus:outline-none focus:ring-2
                                        {{ $isActive
                                            ? 'border-white/10 bg-white/5 text-slate-100 hover:bg-white/10 focus:ring-white/20'
                                            : 'border-indigo-400/30 bg-indigo-500/20 text-indigo-100 hover:bg-indigo-500/30 focus:ring-indigo-400/40'
                                        }}">
                                        {{ $isActive ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>

                                {{-- Edit --}}
                                <a href="{{ route('admin.studios.edit', $studio) }}"
                                   class="inline-flex items-center justify-center rounded-2xl px-4 py-2 text-xs font-semibold border border-white/10 bg-white/5 text-slate-100 transition hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-white/20">
                                    Edit
                                </a>

                                {{-- Hapus --}}
                                <form action="{{ route('admin.studios.destroy', $studio) }}"
                                      method="POST"
                                      class="inline"
                                      onsubmit="return confirm('Yakin menghapus studio ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center justify-center rounded-2xl px-4 py-2 text-xs font-semibold border border-rose-400/20 bg-rose-500/10 text-rose-200 transition hover:bg-rose-500/15 focus:outline-none focus:ring-2 focus:ring-rose-400/40">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-slate-300">
                            Belum ada studio.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
