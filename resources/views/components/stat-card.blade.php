<div class="rounded-lg bg-white p-6 shadow-sm dark:bg-zinc-900">
    <div class="flex items-center justify-between">
        <h3 class="text-sm font-medium text-zinc-500 dark:text-zinc-400">{{ $title }}</h3>
        @if(isset($trend))
            <flux:icon
                name="{{ $trend === 'up' ? 'arrow-up' : 'arrow-down' }}"
                class="size-4 {{ $trend === 'up' ? 'text-success-500' : 'text-danger-500' }}"
            />
        @endif
    </div>
    <div class="mt-2 flex items-baseline">
        <p class="text-2xl font-semibold {{ isset($color) ? "text-{$color}-500" : 'text-zinc-900 dark:text-white' }}">
            {{ $value }}
        </p>
    </div>
    @if(isset($description))
        <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">{{ $description }}</p>
    @endif
</div>
