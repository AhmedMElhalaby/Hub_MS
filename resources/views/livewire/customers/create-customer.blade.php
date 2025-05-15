<div>
    <flux:modal wire:model="showModal" variant="flyout">
        <form wire:submit.prevent="store" class="space-y-6">
            <flux:heading size="lg">
                {{ __('crud.customers.actions.create') }}
            </flux:heading>

            <flux:input
                wire:model.live="name"
                label="{{ __('crud.customers.fields.name') }}"
                required
                :error="$errors->first('name')"
            />
            <flux:input
                wire:model.live="email"
                type="email"
                label="{{ __('crud.customers.fields.email') }}"
                :error="$errors->first('email')"
            />
            <flux:input
                wire:model.live="mobile"
                label="{{ __('crud.customers.fields.mobile') }}"
                :error="$errors->first('mobile')"
            />
            <flux:input
                wire:model.live="address"
                label="{{ __('crud.customers.fields.address') }}"
                :error="$errors->first('address')"
            />
            <flux:select
                wire:model.live="specialization"
                label="{{ __('crud.customers.fields.specialization') }}"
                required
                :error="$errors->first('specialization')"
            >
                <option value="">{{ __('crud.customers.labels.specialization') }}</option>
                @foreach($specializations as $specialization)
                    <option value="{{ $specialization->value }}">{{ $specialization->name }}</option>
                @endforeach
            </flux:select>

            <div class="flex justify-end space-x-2 mt-10">
                <flux:button type="button" wire:click="$set('showModal', false)" variant="outline">
                    {{ __('crud.common.actions.cancel') }}
                </flux:button>
                <flux:button wire:loading.attr="disabled" wire:target="save" type="submit" variant="primary">
                    <span wire:loading.remove wire:target="store">{{ __('crud.common.actions.save') }}</span>
                    <span wire:loading wire:target="store">{{ __('crud.common.actions.saving') }}</span>
                </flux:button>
            </div>
        </form>
    </flux:modal>
</div>
