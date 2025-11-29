@extends('layouts.app')

@section('title', 'Detail Reservasi')

@section('content')
    <h1 class="text-2xl font-semibold mb-4">Detail Reservasi</h1>

    <div class="bg-white rounded-lg shadow p-4 space-y-3">
        <div>
            <h2 class="font-semibold">Studio</h2>
            <p>{{ $reservation->studio->name ?? '-' }}</p>
        </div>

        <div>
            <h2 class="font-semibold">Waktu</h2>
            <p>
                {{ $reservation->start_time->format('d-m-Y H:i') }}
                -
                {{ $reservation->end_time->format('d-m-Y H:i') }}
            </p>
        </div>

        <div>
            <h2 class="font-semibold">Status</h2>
            <p>{{ ucfirst($reservation->status) }}</p>
        </div>

        <div>
            <h2 class="font-semibold">Total Harga</h2>
            <p>Rp {{ number_format($reservation->total_price, 0, ',', '.') }}</p>
        </div>

        <div>
            <h2 class="font-semibold">Addon</h2>
            @if($reservation->addons->count())
                <ul class="list-disc list-inside text-sm">
                    @foreach($reservation->addons as $addon)
                        <li>
                            {{ $addon->name }} x {{ $addon->pivot->qty }}
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-sm text-gray-600">Tidak ada addon.</p>
            @endif
        </div>

        <div>
            <h2 class="font-semibold">Kode Check-in</h2>
            <p class="font-mono text-sm bg-gray-100 inline-block px-2 py-1 rounded">
                {{ $reservation->checkin_code ?? '-' }}
            </p>
        </div>
    </div>
    
    @if(in_array($reservation->status, ['pending', 'confirmed']))
        <form action="{{ route('user.reservations.cancel', $reservation) }}"
              method="POST"
              class="mt-4"
              onsubmit="return confirm('Yakin ingin membatalkan reservasi ini?');">
            @csrf
            <button type="submit"
                    class="px-4 py-2 bg-red-600 text-white rounded text-sm hover:bg-red-700">
                Batalkan Reservasi
            </button>
        </form>
    @endif    
@endsection
