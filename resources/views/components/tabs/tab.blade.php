<button
    @click="activeTab = '{{ $name }}'"
    :class="{ 'border-primary-500 text-primary-600 dark:text-primary-400': activeTab === '{{ $name }}', 'border-transparent text-zinc-500 hover:text-zinc-700 hover:border-zinc-300 dark:text-zinc-400 dark:hover:text-zinc-300': activeTab !== '{{ $name }}' }"
    class="group inline-flex items-center py-4 px-1 border-b-2 font-medium text-sm"
    role="tab"
    :aria-selected="activeTab === '{{ $name }}'"
    aria-controls="tab-panel-{{ $name }}"
>
    @if($icon)
        <span class="mr-2">
            <flux:icon name="{{ $icon }}" class="size-5" />
        </span>
    @endif

    {{ $slot }}

    @if($badge)
        <span class="ml-2 px-2 py-0.5 text-xs font-medium rounded-full bg-{{ $badgeColor }}-100 text-{{ $badgeColor }}-800 dark:bg-{{ $badgeColor }}-800 dark:text-{{ $badgeColor }}-100">
            {{ $badge }}
        </span>
    @endif
</button>
