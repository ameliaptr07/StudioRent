<nav class="bg-[#1a202c] border-b shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-14">
            {{-- Brand --}}
            <div class="flex items-center gap-3">
                {{-- StudioRent logo (untuk Manager) --}}
                <a href="{{ route('manager.dashboard') }}" class="text-lg font-semibold text-white">
                    StudioRent
                </a>
            </div>

            {{-- Menu --}}
            <div class="flex items-center gap-3 text-sm">
                @auth
                    @php
                        $roleName = Auth::user()?->role?->name;

                        $dashboardUrl = match ($roleName) {
                            'Admin' => route('admin.dashboard'),
                            'Manager' => route('manager.dashboard'),
                            'User', 'Penyewa' => route('dashboard'),
                            default => route('dashboard'),
                        };
                    @endphp

                    <a href="{{ $dashboardUrl }}" class="text-white hover:text-indigo-600">
                        Dashboard
                    </a>

                    <a href="{{ route('manager.profile') }}" class="text-white hover:text-indigo-600">
                        Profil
                    </a>

                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-red-600 hover:text-red-700">
                            Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-white hover:text-indigo-600">
                        Login
                    </a>
                    <a href="{{ route('register') }}"
                       class="px-3 py-1 rounded bg-indigo-600 text-white hover:bg-indigo-700">
                        Register
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>
