<div>
    <flux:modal wire:model="showModal" variant="flyout">
        <form wire:submit.prevent="update" class="space-y-6">
            <flux:heading size="lg">
                {{ __('Edit Workspace') }}
            </flux:heading>

            <flux:input
                wire:model.live="desk"
                label="{{ __('Desk') }}"
                required
                :error="$errors->first('desk')"
            />

            <flux:select
                wire:model.live="status"
                label="{{ __('Status') }}"
                required
                :error="$errors->first('status')"
            >
                <option value="">{{ __('Select Status') }}</option>
                @foreach($statuses as $status)
                    <option value="{{ $status->value }}">{{ $status->label() }}</option>
                @endforeach
            </flux:select>

            <div class="flex justify-end space-x-2 mt-10">
                <flux:button type="button" wire:click="$set('showModal', false)" variant="outline">
                    {{ __('Cancel') }}
                </flux:button>
                <flux:button wire:loading.attr="disabled" wire:target="update" type="submit" variant="primary">
                    <span wire:loading.remove wire:target="update">{{ __('Save') }}</span>
                    <span wire:loading wire:target="update">{{ __('Saving...') }}</span>
                </flux:button>
            </div>
        </form>
    </flux:modal>
</div>