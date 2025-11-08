<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Manager Dashboard</h2>
    </x-slot>

    <div class="py-6">
        <p>Halo, {{ auth()->user()->name }} (Manager)</p>
    </div>
</x-app-layout>
