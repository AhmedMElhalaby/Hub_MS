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
                    </div>
                </div>
            </flux:modal>
        </div>
    </x-settings.layout>
</section>
