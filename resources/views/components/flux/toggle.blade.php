@props([
    'label' => null,
    'error' => null,
])

<div>
    @if($label)
        <label class="flex items-center cursor-pointer">
            <div class="relative">
                <input
                    type="checkbox"
                    class="sr-only peer"
                    {{ $attributes }}
                >
                <div class="w-11 h-6 bg-zinc-200 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-zinc-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600 dark:bg-zinc-700 dark:peer-checked:bg-primary-500"></div>
            </div>
            <span class="ms-3 text-sm font-medium text-zinc-700 dark:text-zinc-200">{{ $label }}</span>
        </label>
    @endif

    @if($error)
        <p class="mt-1 text-sm text-red-600">{{ $error }}</p>
    @endif
</div>
