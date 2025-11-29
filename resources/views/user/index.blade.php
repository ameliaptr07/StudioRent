@extends('layouts.app')

@section('title', 'Daftar Studio')

@section('content')
    <h1 class="text-2xl font-semibold mb-4">Daftar Studio</h1>

    <div class="grid md:grid-cols-3 gap-4">
        @forelse($studios as $studio)
            <div class="bg-white rounded-lg shadow p-4 flex flex-col">
                <h2 class="text-lg font-semibold mb-1">{{ $studio->name }}</h2>
                <p class="text-sm text-gray-600 mb-2">
                    {{ \Illuminate\Support\Str::limit($studio->description, 80) }}
                </p>
                <p class="text-sm mb-1">Kapasitas: {{ $studio->capacity }} orang</p>
                <p class="text-sm font-semibold mb-3">
                    Rp {{ number_format($studio->price_per_hour, 0, ',', '.') }} / jam
                </p>
                <a href="{{ route('studios.show', $studio) }}"
                   class="mt-auto inline-block text-center px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 text-sm">
                    Lihat Detail & Pesan
                </a>
            </div>
        @empty
            <p>Tidak ada studio yang tersedia.</p>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $studios->links() }}
    </div>
@endsection
