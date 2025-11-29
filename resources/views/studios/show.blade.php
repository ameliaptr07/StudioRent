@extends('layouts.app')

@section('title', $studio->name)

@section('content')
    <div class="grid md:grid-cols-3 gap-6">
        <div class="md:col-span-2">
            <h1 class="text-2xl font-semibold mb-2">{{ $studio->name }}</h1>
            <p class="text-gray-700 mb-4">{{ $studio->description }}</p>

            <div class="mb-4 text-sm">
                <p>Kapasitas: {{ $studio->capacity }} orang</p>
                <p>Harga: <span class="font-semibold">
                    Rp {{ number_format($studio->price_per_hour, 0, ',', '.') }} / jam
                </span></p>
            </div>

            <h2 class="font-semibold mb-1">Fitur</h2>
            <ul class="list-disc list-inside text-sm mb-4">
                @forelse($studio->features as $feature)
                    <li>{{ $feature->name }}</li>
                @empty
                    <li>Tidak ada fitur khusus.</li>
                @endforelse
            </ul>

            <h2 class="font-semibold mb-1">Addon</h2>
            <ul class="list-disc list-inside text-sm mb-4">
                @forelse($studio->addons as $addon)
                    <li>{{ $addon->name }} - Rp {{ number_format($addon->price, 0, ',', '.') }}</li>
                @empty
                    <li>Tidak ada addon.</li>
                @endforelse
            </ul>
        </div>

        <div class="bg-white rounded-lg shadow p-4" id="booking-card"
             data-studio-id="{{ $studio->id }}">
            <h2 class="text-lg font-semibold mb-3">Buat Reservasi</h2>

            <form id="booking-form" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-sm font-medium mb-1">Tanggal</label>
                    <input type="date" name="date" class="w-full border rounded px-2 py-1 text-sm" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Jam Mulai</label>
                    <input type="time" name="start_time" class="w-full border rounded px-2 py-1 text-sm" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Durasi (menit)</label>
                    <select name="duration" class="w-full border rounded px-2 py-1 text-sm" required>
                        <option value="90">90 menit</option>
                        <option value="120">120 menit</option>
                    </select>
                </div>

                @if($studio->addons->count())
                    <div>
                        <label class="block text-sm font-medium mb-1">Addon</label>
                        <div class="space-y-1">
                            @foreach($studio->addons as $addon)
                                <label class="flex items-center gap-2 text-sm">
                                    <input type="checkbox" name="addons[{{ $addon->id }}][enabled]" value="1">
                                    <span>{{ $addon->name }} (Rp {{ number_format($addon->price,0,',','.') }})</span>
                                    <input type="number" name="addons[{{ $addon->id }}][qty]"
                                           class="w-16 border rounded px-1 py-0.5 text-xs"
                                           min="1" value="1">
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endif

                <button type="submit"
                        class="w-full px-4 py-2 bg-indigo-600 text-white rounded text-sm font-semibold hover:bg-indigo-700">
                    Pesan Sekarang
                </button>

                <p id="booking-message" class="text-sm mt-2"></p>
            </form>
        </div>
    </div>
@endsection
