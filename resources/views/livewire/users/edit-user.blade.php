<div>
    <flux:modal wire:model="showModal" variant="flyout">
        <form wire:submit.prevent="update" class="space-y-6">
            <flux:heading size="lg">
                {{ __('crud.users.actions.edit') }}
            </flux:heading>

            <flux:input
                wire:model.live="name"
                label="{{ __('crud.users.fields.name') }}"
                required
                :error="$errors->first('name')"
            />
            <flux:input
                wire:model.live="email"
                type="email"
                label="{{ __('crud.users.fields.email') }}"
                required
                :error="$errors->first('email')"
            />
            <flux:input
                wire:model.live="password"
                type="password"
                label="{{ __('crud.users.fields.password') }} ({{ __('crud.common.actions.leave_empty') }})"
                :error="$errors->first('password')"
            />

            <div class="flex justify-end space-x-2 mt-10">
                <flux:button type="button" wire:click="closeModal" variant="outline">
                    {{ __('crud.common.actions.cancel') }}
                </flux:button>
                <flux:button wire:loading.attr="disabled" wire:target="update" type="submit"
                    variant="primary">
                    <span wire:loading.remove wire:target="update">{{ __('crud.common.actions.save') }}</span>
                    <span wire:loading wire:target="update">{{ __('crud.common.actions.saving') }}</span>
                </flux:button>
            </div>
        </form>
    </flux:modal>
</div>
