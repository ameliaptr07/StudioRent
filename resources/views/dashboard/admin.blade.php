<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Admin Dashboard</h2>
    </x-slot>

    <div class="py-6">
        <p>Halo, {{ auth()->user()->name }} (Admin)</p>
    </div>
</x-app-layout>
