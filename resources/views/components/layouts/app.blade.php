<x-layouts.app.sidebar>
    <livewire:components.notification />
    <livewire:components.notification-listener />
    <flux:main>
        {{ $slot }}
    </flux:main>
</x-layouts.app.sidebar>
