<section class="w-full">
    <x-settings.layout heading="{{ __('crud.settings.model.plural') }}" subheading="{{ __('crud.settings.actions.update') }}">
        <div class="mt-6">
            <flux:heading size="lg">{{ __('crud.settings.labels.basic_information') }}</flux:heading>
            <form wire:submit.prevent="save" class="mt-4 space-y-6 max-w-xl">
                <flux:input
                    wire:model="appName"
                    label="{{ __('crud.common.fields.name') }}"
                    required
                />
                <div class="flex items-center gap-4">
                    <div class="flex items-center justify-end">
                        <flux:button variant="primary" type="submit">{{ __('crud.common.actions.save') }}</flux:button>
                    </div>
                </div>
            </form>
        </div>
    </x-settings.layout>
</section>

