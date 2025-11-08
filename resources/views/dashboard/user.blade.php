<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">User Dashboard</h2>
    </x-slot>

    <div class="py-6">
        <p>Halo, {{ auth()->user()->name }} (Penyewa)</p>
    </div>
</x-app-layout>
