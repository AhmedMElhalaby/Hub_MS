<div class="flex flex-col items-start">
    <x-settings.layout heading="{{ __('crud.settings.labels.appearance') }}" subheading="{{ __('crud.settings.messages.updated') }}">
        <flux:radio.group x-data variant="segmented" class="mt-5" x-model="$flux.appearance">
            <flux:radio value="light" icon="sun">{{ __('crud.common.fields.light') }}</flux:radio>
            <flux:radio value="dark" icon="moon">{{ __('crud.common.fields.dark') }}</flux:radio>
            <flux:radio value="system" icon="computer-desktop">{{ __('crud.common.fields.system') }}</flux:radio>
        </flux:radio.group>
    </x-settings.layout>
</div>
