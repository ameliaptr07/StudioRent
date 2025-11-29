@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
    <h1 class="text-2xl font-semibold mb-4">Dashboard Admin</h1>

    <div class="grid md:grid-cols-3 gap-4">
        <a href="{{ route('admin.studios.index') }}" class="block p-4 bg-white rounded-lg shadow hover:shadow-md">
            <h2 class="font-semibold mb-1">Kelola Studio</h2>
            <p class="text-sm text-gray-600">
                Tambah, edit, dan hapus data studio.
            </p>
        </a>

        <a href="{{ route('admin.addons.index') }}" class="block p-4 bg-white rounded-lg shadow hover:shadow-md">
            <h2 class="font-semibold mb-1">Kelola Addon</h2>
            <p class="text-sm text-gray-600">
                Atur paket tambahan (misalnya peralatan).
            </p>
        </a>

        <a href="{{ route('admin.features.index') }}" class="block p-4 bg-white rounded-lg shadow hover:shadow-md">
            <h2 class="font-semibold mb-1">Kelola Feature</h2>
            <p class="text-sm text-gray-600">
                Atur fitur-fitur fasilitas studio.
            </p>
        </a>
    </div>
@endsection
