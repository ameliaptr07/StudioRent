<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'StudioRent')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

@php
    $isAdmin = request()->is('admin/*');
    $isManager = request()->is('manager/*');
    $isUser  = request()->is('user/*'); // penyewa
    $isDark  = $isAdmin || $isManager || $isUser;

    // Admin & Manager: biarkan view admin handle full-bleed sendiri
    $mainClass = $isAdmin || $isManager
        ? 'flex-1'
        : 'flex-1 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-12';

    $bodyClass = $isDark ? 'bg-slate-950 text-slate-100' : 'bg-neutral-50 text-neutral-900';

    // Header: tetap ada untuk non-admin, tapi "kosongan" (cuma bar gelap + border) agar selaras.
    // Navigation tetap di-include di dalamnya.
    $headerShellClass = $isDark
        ? 'sticky top-0 z-50 border-b border-white/10 bg-slate-950/80 backdrop-blur-xl'
        : 'sticky top-0 z-50 border-b border-black/5 bg-white/80 backdrop-blur';

    $footerClass = $isDark
        ? 'border-t border-white/10 bg-slate-950/80 backdrop-blur'
        : 'border-t border-black/5 bg-white';

    $footerTextClass = $isDark ? 'text-slate-400' : 'text-gray-500';

    $flashStatusClass = $isDark
        ? 'rounded-2xl border border-emerald-400/20 bg-emerald-400/10 px-4 py-3 text-sm text-emerald-200'
        : 'rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-800';

    $flashErrorClass = $isDark
        ? 'rounded-2xl border border-rose-400/20 bg-rose-400/10 px-4 py-3 text-sm text-rose-200'
        : 'rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-800';
@endphp

<body class="font-sans antialiased {{ $bodyClass }}">
    <div class="min-h-dvh flex flex-col">

        {{-- HEADER: non-admin only. "Kosongan" shell-nya, tapi navigation tetap tampil --}}
        @unless($isAdmin || $isManager || $isUser)
            <header class="{{ $headerShellClass }}">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    @include('layouts.navigation')
                </div>
            </header>
        @endunless

        <main class="{{ $mainClass }} mt-16"> <!-- Menambahkan margin atas lebih besar -->
            @if (session('status'))
                <div class="mb-4 {{ $flashStatusClass }}">
                    {{ session('status') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 {{ $flashErrorClass }}">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>

        {{-- FOOTER: non-admin only, diselaraskan untuk dark theme user --}}
        @unless($isAdmin || $isManager)
            <footer class="{{ $footerClass }}">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 text-xs {{ $footerTextClass }} flex justify-between">
                    <span>&copy; {{ date('Y') }} StudioRent</span>
                    <span>niki gemoy</span>
                </div>
            </footer>
        @endunless

    </div>
</body>
</html>
