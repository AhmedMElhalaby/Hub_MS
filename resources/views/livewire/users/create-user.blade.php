<div>
    <flux:modal wire:model="showModal" variant="flyout">
        <form wire:submit.prevent="store" class="space-y-6">
            <flux:heading size="lg">
                {{ __('Create User') }}
            </flux:heading>

            <flux:input
                wire:model.live="name"
                label="{{ __('Name') }}"
                required
                :error="$errors->first('name')"
            />
            <flux:input
                wire:model.live="email"
                type="email"
                label="{{ __('Email') }}"
                required
                :error="$errors->first('email')"
            />
            <flux:input
                wire:model.live="password"
                type="password"
                label="{{ __('Password') }}"
                required
                :error="$errors->first('password')"
            />

            <div class="flex justify-end space-x-2 mt-10">
                <flux:button type="button" wire:click="closeModal" variant="outline">
                    {{ __('Cancel') }}
                </flux:button>
                <flux:button wire:loading.attr="disabled" wire:target="store" type="submit"
                    variant="primary">
                    <span wire:loading.remove wire:target="store">{{ __('Save') }}</span>
                    <span wire:loading wire:target="store">{{ __('Saving...') }}</span>
                </flux:button>
            </div>
        </form>
    </flux:modal>
</div>
