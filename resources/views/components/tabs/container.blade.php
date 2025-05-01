<div x-data="{ activeTab: '{{ $activeTab }}' }" id="{{ $id }}" class="tabs-container">
    <div class="border-b border-zinc-200 dark:border-zinc-700">
        <nav class="flex space-x-4" aria-label="Tabs">
            {{ $tabs }}
        </nav>
    </div>
    <div class="mt-4">
        {{ $slot }}
    </div>
</div>
