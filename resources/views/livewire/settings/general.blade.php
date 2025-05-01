<section class="w-full">
    <x-settings.layout heading="{{ __('Settings') }}" subheading="{{ __('Update General Settings') }}">
        <div class="mt-6">
            <flux:heading size="lg">{{ __('General Settings') }}</flux:heading>
            <form wire:submit.prevent="save" class="mt-4 space-y-6 max-w-xl">
                <flux:input
                    wire:model="appName"
                    label="{{ __('App Name') }}"
                    required
                />
                <div class="flex items-center gap-4">
                    <div class="flex items-center justify-end">
                        <flux:button variant="primary" type="submit">{{ __('Save General Settings') }}</flux:button>
                    </div>
                </div>
            </form>
        </div>
    </x-settings.layout>
</section>

