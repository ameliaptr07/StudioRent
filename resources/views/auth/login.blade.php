<x-guest-layout>

        <!-- Judul -->
    <div class="text-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Selamat Datang di StudioRent</span></h1>
        <p class="text-gray-600 mt-1 text-sm">Silakan login untuk melanjutkan</p>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email"
                class="block mt-1 w-full"
                type="email"
                name="email"
                :value="old('email')"
                required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password"
                class="block mt-1 w-full"
                type="password"
                name="password"
                required />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Login Button -->
        <div class="mt-6">
            <x-primary-button class="w-full justify-center">
                {{ __('Log in') }}
            </x-primary-button>
        </div>

        <!-- Forgot Password -->
        @if (Route::has('password.request'))
            <div class="mt-3 text-center">
                <a href="{{ route('password.request') }}"
                   class="text-sm text-indigo-600 hover:underline">
                    Lupa password?
                </a>
            </div>
        @endif

        <!-- Register Link -->
        @if (Route::has('register'))
            <div class="mt-2 text-center">
                <span class="text-sm text-gray-600">Belum punya akun?</span>
                <a href="{{ route('register') }}"
                   class="text-sm text-indigo-600 font-semibold hover:underline">
                    Daftar sekarang
                </a>
            </div>
        @endif

    </form>
</x-guest-layout>
