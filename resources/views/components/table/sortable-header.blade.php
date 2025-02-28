@props(['field', 'sortField', 'sortDirection', 'label'])

<flux:table.head>
    <button wire:click="sortBy('{{ $field }}')" class="flex items-center space-x-1">
        <span>{{ $label }}</span>
        @if ($sortField === $field)
            <flux:icon name="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}"
                class="size-4" />
        @endif
    </button>
</flux:table.head>
