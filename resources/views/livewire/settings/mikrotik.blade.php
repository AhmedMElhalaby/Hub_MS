@php
    use App\Models\Tenant;

    $scriptContent = file_get_contents(base_path('mikrotik/scripts/integration.script'));
    $scriptContent = str_replace(
        ['{{ config(\'app.url\') }}', '{{ auth()->user()->tenant->api_key }}'],
        [config('app.url'), auth()->user()->tenant->api_key],
        $scriptContent
    );
@endphp
<section class="w-full" x-data="{ showScript: false }">
    <x-settings.layout heading="{{ __('Mikrotik Settings') }}" subheading="{{ __('Configure Mikrotik integration settings') }}">
        <div class="mt-6">
            <form wire:submit.prevent="save" class="mt-4 space-y-6 max-w-xl">
                <div>
                    <label class="flex items-center cursor-pointer">
                        <div class="relative">
                            <input
                                type="checkbox"
                                wire:model="mikrotikEnabled"
                                class="sr-only peer"
                            >
                            <div class="w-11 h-6 bg-zinc-200 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-zinc-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600 dark:bg-zinc-700 dark:peer-checked:bg-primary-500"></div>
                        </div>
                        <span class="ms-3 text-sm font-medium text-zinc-700 dark:text-zinc-200">{{ __('Enable Mikrotik Integration') }}</span>
                    </label>
                </div>

                <div class="flex items-center gap-4">
                    <div class="flex items-center justify-end gap-2">
                        <flux:button
                            type="button"
                            variant="outline"
                            wire:click="$set('showScript', true)"
                        >
                            {{ __('View Integration Script') }}
                        </flux:button>
                        <flux:button variant="primary" type="submit">
                            {{ __('Save Mikrotik Settings') }}
                        </flux:button>
                    </div>
                </div>
            </form>

            <!-- Modal -->
            <flux:modal wire:model="showScript">
                <div class="space-y-6">
                    <flux:heading size="lg">{{ __('Mikrotik Integration Script') }}</flux:heading>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400">
                        {{ __('Add this script to your Mikrotik device to enable integration with the system.') }}
                    </p>
                    <div class="relative">
                        <pre class="bg-zinc-900 text-zinc-100 p-4 rounded-lg overflow-x-auto text-sm font-mono">{{ $scriptContent }}</pre>
                        <button
                            class="absolute top-2 right-2 p-2 text-zinc-400 hover:text-zinc-100 transition-colors"
                            @click="navigator.clipboard.writeText($el.previousElementSibling.textContent); $dispatch('notify', { message: '{{ __('Script copied to clipboard!') }}' })"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                            </svg>
                        </button>
                    </div>
                </div>
            </flux:modal>
        </div>
    </x-settings.layout>
</section>
