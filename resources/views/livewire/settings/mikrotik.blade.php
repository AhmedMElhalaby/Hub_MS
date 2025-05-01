<section class="w-full">
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

                <div x-show="$wire.mikrotikEnabled"
                     x-cloak
                     class="space-y-6">
                    <flux:input
                        wire:model="mikrotikHost"
                        label="{{ __('Mikrotik Host') }}"
                    />

                    <flux:input
                        wire:model="mikrotikUser"
                        label="{{ __('Mikrotik Username') }}"
                    />

                    <flux:input
                        wire:model="mikrotikPassword"
                        type="password"
                        label="{{ __('Mikrotik Password') }}"
                    />

                    <flux:input
                        wire:model="mikrotikPort"
                        type="number"
                        label="{{ __('Mikrotik Port') }}"
                    />
                </div>

                <div class="flex items-center gap-4">
                    <div class="flex items-center justify-end">
                        <flux:button variant="primary" type="submit">
                            {{ __('Save Mikrotik Settings') }}
                        </flux:button>
                    </div>
                </div>
            </form>
        </div>
    </x-settings.layout>
</section>
