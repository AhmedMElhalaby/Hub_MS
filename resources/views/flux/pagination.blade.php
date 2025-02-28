{{-- @if ($paginator->hasPages()) --}}
<nav class="flex items-center justify-between">
    <div class="flex items-center gap-4">
        <div class="w-24">
            <select wire:model.live="perPage"
                class="block w-full rounded-md border-0 py-1.5 text-sm ring-1 ring-inset ring-zinc-300 focus:ring-2 focus:ring-primary-600 dark:bg-zinc-900 dark:ring-zinc-700 dark:focus:ring-primary-500">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
        </div>
        <p class="text-sm text-zinc-600 dark:text-zinc-400">
            {{ __('Showing') }}
            <span class="font-medium">{{ $paginator->firstItem() ?? 0 }}</span>
            {{ __('to') }}
            <span class="font-medium">{{ $paginator->lastItem() ?? 0 }}</span>
            {{ __('of') }}
            <span class="font-medium">{{ $paginator->total() }}</span>
            {{ __('results') }}
        </p>
    </div>

    <div class="flex items-center gap-1">
        <button wire:click="previousPage" @class([
            'flex h-8 w-8 items-center justify-center rounded-md border text-sm transition-colors',
            'border-zinc-300 text-zinc-700 hover:bg-zinc-50 dark:border-zinc-600 dark:text-zinc-300 dark:hover:bg-zinc-800' => !$paginator->onFirstPage(),
            'border-zinc-200 text-zinc-400 dark:border-zinc-700 dark:text-zinc-500' => $paginator->onFirstPage(),
        ]) @disabled($paginator->onFirstPage())>
            <flux:icon name="chevron-left" class="size-4" />
        </button>

        @php
            $window = 2; // Number of pages to show on each side of current page
            $start = max($paginator->currentPage() - $window, 1);
            $end = min($paginator->currentPage() + $window, $paginator->lastPage());
        @endphp

        @if ($start > 1)
            <button wire:click="gotoPage(1)"
                class="flex h-8 min-w-8 items-center justify-center rounded-md border px-2 text-sm transition-colors border-zinc-300 text-zinc-700 hover:bg-zinc-50 dark:border-zinc-600 dark:text-zinc-300 dark:hover:bg-zinc-800">1</button>
            @if ($start > 2)
                <span class="flex h-8 w-8 items-center justify-center text-sm text-zinc-500">...</span>
            @endif
        @endif

        @for ($i = $start; $i <= $end; $i++)
            <button wire:click="gotoPage({{ $i }})" @class([
                'flex h-8 min-w-8 items-center justify-center rounded-md border px-2 text-sm transition-colors',
                'border-primary-600 bg-primary-600 dark:text-white' =>
                    $i === $paginator->currentPage(),
                'border-zinc-300 text-zinc-700 hover:bg-zinc-50 dark:border-zinc-600 dark:text-zinc-300 dark:hover:bg-zinc-800' =>
                    $i !== $paginator->currentPage(),
            ])>{{ $i }}</button>
        @endfor

        @if ($end < $paginator->lastPage())
            @if ($end < $paginator->lastPage() - 1)
                <span class="flex h-8 w-8 items-center justify-center text-sm text-zinc-500">...</span>
            @endif
            <button wire:click="gotoPage({{ $paginator->lastPage() }})"
                class="flex h-8 min-w-8 items-center justify-center rounded-md border px-2 text-sm transition-colors border-zinc-300 text-zinc-700 hover:bg-zinc-50 dark:border-zinc-600 dark:text-zinc-300 dark:hover:bg-zinc-800">{{ $paginator->lastPage() }}</button>
        @endif

        <button wire:click="nextPage" @class([
            'flex h-8 w-8 items-center justify-center rounded-md border text-sm transition-colors',
            'border-zinc-300 text-zinc-700 hover:bg-zinc-50 dark:border-zinc-600 dark:text-zinc-300 dark:hover:bg-zinc-800' => !$paginator->onLastPage(),
            'border-zinc-200 text-zinc-400 dark:border-zinc-700 dark:text-zinc-500' => $paginator->onLastPage(),
        ]) @disabled($paginator->onLastPage())>
            <flux:icon name="chevron-right" class="size-4" />
        </button>
    </div>
</nav>
{{-- @endif --}}
