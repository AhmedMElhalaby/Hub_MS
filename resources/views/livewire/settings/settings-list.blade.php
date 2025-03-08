@php
    use App\Models\Setting;
@endphp

<section class="w-full">
    <livewire:components.notification />

    <x-settings.layout heading="{{ __('Settings') }}" subheading="{{ __('Update General Settings') }}">
        <!-- General Settings -->
        <div class="mt-6">
            <flux:heading size="lg">{{ __('General Settings') }}</flux:heading>
            <form wire:submit.prevent="saveGeneral" class="mt-4 space-y-6 max-w-xl">
                <flux:input
                    wire:model="appName"
                    label="{{ __('App Name') }}"
                    required
                />

                <div>
                    <flux:label>{{ __('App Logo') }}</flux:label>
                    <input
                        type="file"
                        wire:model.live="appLogo"
                        accept="image/*"
                        class="block w-full text-sm text-zinc-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-zinc-50 file:text-zinc-700 hover:file:bg-zinc-100 dark:file:bg-zinc-800 dark:file:text-zinc-200 dark:hover:file:bg-zinc-700 dark:text-zinc-200"
                    />
                    @error('appLogo') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    @if($logo = Setting::get('app_logo'))
                        <div class="mt-2">
                            <img src="{{ Storage::url($logo) }}" alt="Logo" class="h-12">
                        </div>
                    @endif
                </div>

                <div>
                    <flux:label>{{ __('App Favicon') }}</flux:label>
                    <input
                        type="file"
                        wire:model.live="appFavicon"
                        accept="image/*"
                        class="block w-full text-sm text-zinc-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-zinc-50 file:text-zinc-700 hover:file:bg-zinc-100 dark:file:bg-zinc-800 dark:file:text-zinc-200 dark:hover:file:bg-zinc-700 dark:text-zinc-200"
                    />
                    @error('appFavicon') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    @if($favicon = Setting::get('app_favicon'))
                        <div class="mt-2">
                            <img src="{{ Storage::url($favicon) }}" alt="Favicon" class="h-8">
                        </div>
                    @endif
                </div>

                <flux:input
                    wire:model="primaryColor"
                    type="color"
                    label="{{ __('Primary Color') }}"
                    required
                />

                <flux:input
                    wire:model="secondaryColor"
                    type="color"
                    label="{{ __('Secondary Color') }}"
                    required
                />

                <div class="flex items-center gap-4">
                    <div class="flex items-center justify-end">
                        <flux:button variant="primary" type="submit">{{ __('Save General Settings') }}</flux:button>
                    </div>
                </div>
            </form>
        </div>
        <!-- Mikrotik Settings -->
        <div class="mt-12">
            <flux:heading size="lg">{{ __('Mikrotik Settings') }}</flux:heading>
            <form wire:submit.prevent="saveMikrotik" class="mt-4 space-y-6 max-w-xl">
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
