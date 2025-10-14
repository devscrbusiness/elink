<x-layouts.app :title="__('Dashboard')">
    @if (auth()->user()->business?->name)
        {{-- El usuario ya tiene una empresa, muestra el dashboard --}}
        @livewire('dashboard')
    @else
        {{-- El usuario no tiene empresa, muestra el formulario de creación --}}
        @livewire('create-business')
    @endif
</x-layouts.app>
