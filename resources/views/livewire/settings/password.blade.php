<section class="w-full">
    <x-settings.layout heading="{{ __('crud.settings.labels.password') }}" subheading="{{ __('crud.settings.labels.update_your_password') }}">
        <form wire:submit="save" class="mt-6 space-y-6">
            <flux:input
                wire:model="current_password"
                id="update_password_current_passwordpassword"
                label="{{ __('crud.users.fields.password') }}"
                type="password"
                name="current_password"
                required
                autocomplete="current-password"
            />
            <flux:input
                wire:model="password"
                id="update_password_password"
                label="{{ __('crud.users.fields.password') }}"
                type="password"
                name="password"
                required
                autocomplete="new-password"
            />
            <flux:input
                wire:model="password_confirmation"
                id="update_password_password_confirmation"
                label="{{ __('crud.users.fields.password') }}"
                type="password"
                name="password_confirmation"
                required
                autocomplete="new-password"
            />

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <flux:button variant="primary" type="submit" class="w-full">{{ __('crud.common.actions.save') }}</flux:button>
                </div>

                <x-action-message class="me-3" on="password-updated">
                    {{ __('crud.common.messages.saved') }}
                </x-action-message>
            </div>
        </form>
    </x-settings.layout>
</section>
