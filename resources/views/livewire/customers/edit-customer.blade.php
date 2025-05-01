<div>
    <flux:modal wire:model="showModal" variant="flyout">
        <form wire:submit.prevent="update" class="space-y-6">
            <flux:heading size="lg">
                {{ __('Edit Customer') }}
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
                :error="$errors->first('email')"
            />
            <flux:input
                wire:model.live="mobile"
                label="{{ __('Mobile') }}"
                :error="$errors->first('mobile')"
            />
            <flux:input
                wire:model.live="address"
                label="{{ __('Address') }}"
                :error="$errors->first('address')"
            />
            <flux:select
                wire:model.live="specialization"
                label="{{ __('Specialization') }}"
                required
                :error="$errors->first('specialization')"
            >
                <option value="">{{ __('Select Specialization') }}</option>
                @foreach($specializations as $specialization)
                    <option value="{{ $specialization->value }}">{{ $specialization->name }}</option>
                @endforeach
            </flux:select>

            <div class="flex justify-end space-x-2 mt-10">
                <flux:button type="button" wire:click="$set('showModal', false)" variant="outline">
                    {{ __('Cancel') }}
                </flux:button>
                <flux:button wire:loading.attr="disabled" wire:target="save" type="submit" variant="primary">
                    <span wire:loading.remove wire:target="update">{{ __('Save') }}</span>
                    <span wire:loading wire:target="update">{{ __('Saving...') }}</span>
                </flux:button>
            </div>
        </form>
    </flux:modal>
</div>
