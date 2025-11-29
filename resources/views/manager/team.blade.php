@extends('layouts.app')

@section('title', 'Daftar Admin')

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
                <h1 class="mt-2 text-3xl sm:text-4xl font-semibold text-white">Daftar Admin</h1>
                <p class="mt-2 max-w-2xl text-slate-300">
                    Kelola akun admin yang bekerja di studio.
                </p>

                {{-- Back to Dashboard --}}
                <div class="mt-5 flex flex-wrap items-center gap-3">
                    <a href="{{ route('manager.dashboard') }}"
                       class="inline-flex items-center justify-center rounded-2xl px-5 py-2.5 text-sm font-semibold border border-white/10 bg-white/5 text-slate-100 transition hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-white/20">
                        ← Kembali ke Dashboard
                    </a>

                    <span class="inline-flex items-center rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs text-slate-200">
                        Total: {{ $adminUsers->count() }}
                    </span>
                </div>
            </div>

            <a href="{{ route('manager.team.create') }}"
               class="inline-flex items-center justify-center rounded-2xl bg-gradient-to-b from-indigo-500 to-indigo-600 px-6 py-3 text-sm font-semibold text-white shadow-[0_16px_40px_-18px_rgba(99,102,241,0.7)] transition hover:brightness-110 focus:outline-none focus:ring-2 focus:ring-indigo-400/60">
                + Tambah Admin Baru
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
                <h2 class="text-base sm:text-lg font-semibold text-white">Daftar Admin</h2>
                <p class="mt-1 text-xs sm:text-sm text-slate-300">
                    Klik <span class="text-white/90 font-medium">Edit</span> untuk ubah detail, atau gunakan tombol status untuk aktif/nonaktifkan akun admin.
                </p>
            </div>
            <div class="hidden sm:flex items-center gap-2">
                <span class="inline-flex items-center rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs text-slate-200">
                    Total: {{ $adminUsers->count() }}
                </span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-white/5">
                    <tr class="text-left text-slate-300">
                        <th class="px-6 py-4 font-medium">Nama</th>
                        <th class="px-6 py-4 font-medium">Email</th>
                        <th class="px-6 py-4 font-medium">Status</th>
                        <th class="px-6 py-4 text-right font-medium">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-white/10">
                    @foreach ($adminUsers as $admin)
                        <tr class="group hover:bg-white/5 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-2xl border border-white/10 bg-white/5 text-white/90 shadow-sm">
                                        🧑‍💻
                                    </span>
                                    <div>
                                        <div class="font-semibold text-white leading-tight">
                                            {{ $admin->name }}
                                        </div>
                                        <p class="text-xs text-slate-400 mt-1">
                                            ID: {{ $admin->id }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4 text-slate-200">
                                {{ $admin->email }}
                            </td>

                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $admin->is_active ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }}">
                                    {{ $admin->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                </span>
                            </td>

                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end items-center gap-3">
                                    {{-- Toggle Status --}}
                                    <form action="{{ route('manager.team.toggleStatus', $admin->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                                class="inline-flex items-center justify-center rounded-2xl px-4 py-2 text-xs font-semibold border transition focus:outline-none focus:ring-2
                                                {{ $admin->is_active
                                                    ? 'border-white/10 bg-white/5 text-slate-100 hover:bg-white/10 focus:ring-white/20'
                                                    : 'border-indigo-400/30 bg-indigo-500/20 text-indigo-100 hover:bg-indigo-500/30 focus:ring-indigo-400/40' }}">
                                            {{ $admin->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>

                                    {{-- Edit --}}
                                    <a href="{{ route('manager.team.edit', $admin->id) }}"
                                       class="inline-flex items-center justify-center rounded-2xl px-4 py-2 text-xs font-semibold border border-white/10 bg-white/5 text-slate-100 transition hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-white/20">
                                        Edit
                                    </a>

                                    {{-- Hapus --}}
                                    <form action="{{ route('manager.team.delete', $admin->id) }}" method="POST" class="inline" onsubmit="return confirmDelete()">
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
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    // JavaScript konfirmasi untuk menghapus admin
    function confirmDelete() {
        return confirm("Apakah Anda yakin ingin menghapus admin ini?");
    }
</script>
@endsection
