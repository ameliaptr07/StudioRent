@extends('layouts.app')

@section('title', 'Dashboard Penyewa')

@section('content')
@php
    $user = auth()->user();
@endphp

<div class="mb-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-semibold text-gray-800">
                Halo, {{ $user?->name ?? 'Penyewa' }} 👋
            </h1>
            <p class="text-sm text-gray-500 mt-1">
                Siap booking studio? Pilih studio favoritmu, atur jadwal, dan pantau reservasi dari sini.
            </p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('profile.edit') }}"
               class="px-4 py-2 rounded-lg text-sm border bg-white hover:bg-gray-50 transition">
                Profil
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="px-4 py-2 rounded-lg text-sm bg-indigo-600 text-white hover:bg-indigo-700 transition">
                    Logout
                </button>
            </form>
        </div>
    </div>
</div>

@if(session('status'))
    <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
        {{ session('status') }}
    </div>
@endif

@if(session('error'))
    <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
        {{ session('error') }}
    </div>
@endif

<div class="grid md:grid-cols-3 gap-4">
    <a href="{{ route('user.studios.index') }}"
       class="group block p-5 bg-white rounded-lg shadow-lg hover:shadow-xl transition">
        <div class="flex items-start justify-between gap-3">
            <div>
                <h2 class="text-lg font-semibold text-gray-800 group-hover:text-indigo-700 transition">
                    Jelajahi Studio
                </h2>
                <p class="text-sm text-gray-600 mt-1">
                    Lihat studio yang tersedia, cek fitur/addon, lalu pilih yang paling cocok.
                </p>
            </div>
            <div class="shrink-0 w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center">
                <span class="text-indigo-600 text-lg">🏠</span>
            </div>
        </div>
        <div class="mt-4 text-sm text-indigo-600 font-medium">
            Lihat daftar studio →
        </div>
    </a>

    <a href="{{ route('user.reservations.index') }}"
       class="group block p-5 bg-white rounded-lg shadow-lg hover:shadow-xl transition">
        <div class="flex items-start justify-between gap-3">
            <div>
                <h2 class="text-lg font-semibold text-gray-800 group-hover:text-indigo-700 transition">
                    Reservasi Saya
                </h2>
                <p class="text-sm text-gray-600 mt-1">
                    Pantau jadwal, lihat detail, dan batalkan reservasi jika diperlukan.
                </p>
            </div>
            <div class="shrink-0 w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center">
                <span class="text-indigo-600 text-lg">📅</span>
            </div>
        </div>
        <div class="mt-4 text-sm text-indigo-600 font-medium">
            Buka reservasi →
        </div>
    </a>

    <a href="{{ route('profile.edit') }}"
       class="group block p-5 bg-white rounded-lg shadow-lg hover:shadow-xl transition">
        <div class="flex items-start justify-between gap-3">
            <div>
                <h2 class="text-lg font-semibold text-gray-800 group-hover:text-indigo-700 transition">
                    Atur Profil
                </h2>
                <p class="text-sm text-gray-600 mt-1">
                    Ubah nama, email, atau password agar akunmu tetap aman dan up-to-date.
                </p>
            </div>
            <div class="shrink-0 w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center">
                <span class="text-indigo-600 text-lg">👤</span>
            </div>
        </div>
        <div class="mt-4 text-sm text-indigo-600 font-medium">
            Edit profil →
        </div>
    </a>
</div>
@endsection
