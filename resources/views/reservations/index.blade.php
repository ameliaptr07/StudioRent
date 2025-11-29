@extends('layouts.app')

@section('title', 'Reservasi Saya')

@section('content')
    <h1 class="text-2xl font-semibold mb-4">Reservasi Saya</h1>

    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left">Studio</th>
                    <th class="px-4 py-2 text-left">Waktu</th>
                    <th class="px-4 py-2 text-left">Status</th>
                    <th class="px-4 py-2 text-left">Total</th>
                    <th class="px-4 py-2"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($reservations as $reservation)
                    <tr class="border-t">
                        <td class="px-4 py-2">
                            {{ $reservation->studio->name ?? '-' }}
                        </td>
                        <td class="px-4 py-2">
                            {{ $reservation->start_time->format('d-m-Y H:i') }} -
                            {{ $reservation->end_time->format('H:i') }}
                        </td>
                        <td class="px-4 py-2">
                            <span class="px-2 py-1 rounded text-xs
                                @if($reservation->status === 'confirmed') bg-green-100 text-green-700
                                @elseif($reservation->status === 'cancelled') bg-red-100 text-red-700
                                @else bg-gray-100 text-gray-700
                                @endif">
                                {{ ucfirst($reservation->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-2">
                            Rp {{ number_format($reservation->total_price, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-2 text-right">
                            <a href="{{ route('user.reservations.show', $reservation) }}"
                               class="text-indigo-600 hover:underline">
                                Detail
                            </a>
                            
                            @if(in_array($reservation->status, ['pending', 'confirmed']))
                                    <form action="{{ route('user.reservations.cancel', $reservation) }}"
                                        method="POST"
                                        class="inline"
                                        onsubmit="return confirm('Yakin ingin membatalkan reservasi ini?');">
                                        @csrf
                                        <button type="submit" class="text-red-600 hover:underline text-xs">
                                            Batalkan
                                        </button>
                                    </form>
                                @endif                            
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-4 text-center text-gray-500">
                            Belum ada reservasi.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $reservations->links() }}
    </div>
@endsection
