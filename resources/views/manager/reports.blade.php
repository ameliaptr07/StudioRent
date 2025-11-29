@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-2xl font-semibold">Laporan Harian</h1>
    <!-- Tampilkan data laporan di sini -->
    <table class="min-w-full table-auto">
        <thead>
            <tr>
                <th class="px-4 py-2">Tanggal</th>
                <th class="px-4 py-2">Jumlah Reservasi</th>
                <th class="px-4 py-2">Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reports as $report)
            <tr>
                <td class="border px-4 py-2">{{ $report->date }}</td>
                <td class="border px-4 py-2">{{ $report->reservations_count }}</td>
                <td class="border px-4 py-2">{{ $report->revenue }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
