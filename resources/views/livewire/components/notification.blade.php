<div class="fixed top-4 left-4 w-96 z-20 space-y-2 mt-3 ml-3">
    @foreach ($notifications as $notification)
        <div wire:key="{{ $notification['id'] }}" x-data="{ show: true }" x-show="show"
            {{-- x-init="setTimeout(() => { show = false; $wire.remove('{{ $notification['id'] }}') }, 3000)" --}}
            x-transition:enter="transform ease-out duration-300 transition"
            x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
            x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
            x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="pointer-events-auto w-full max-w-sm overflow-hidden rounded-lg shadow-lg dark:bg-zinc-800 {{ $notification['type'] === 'success' ? 'bg-white border border-green-100' : 'bg-white border border-red-100' }}">
            <div class="p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        @if ($notification['type'] === 'success')
                            <flux:icon name="check-circle" class="size-5 text-green-500" />
                        @else
                            <flux:icon name="x-circle" class="size-5 text-red-500" />
                        @endif
                    </div>
                    <div class="ml-3 w-0 flex-1">
                        <p
                            class="{{ $notification['type'] === 'success' ? 'text-green-900 dark:text-green-400' : 'text-red-900 dark:text-red-400' }} text-sm font-medium">
                            {{ $notification['message'] }}
                        </p>
                    </div>
                    <div class="ml-4 flex flex-shrink-0">
                        <button @click="show = false; $wire.remove('{{ $notification['id'] }}')"
                            class="{{ $notification['type'] === 'success' ? 'text-green-500 hover:text-green-600' : 'text-red-500 hover:text-red-600' }} transition-colors">
                            <flux:icon name="x-mark" class="size-4" />
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
