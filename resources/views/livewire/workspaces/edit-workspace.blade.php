<div>
    <flux:modal wire:model="showModal" variant="flyout">
        <form wire:submit.prevent="update" class="space-y-6">
            <flux:heading size="lg">
                {{ __('crud.workspaces.actions.edit') }}
            </flux:heading>

            <flux:input
                wire:model.live="desk"
                label="{{ __('crud.workspaces.fields.desk') }}"
                required
                :error="$errors->first('desk')"
            />

            <flux:select
                wire:model.live="status"
                label="{{ __('crud.workspaces.fields.status') }}"
                required
                :error="$errors->first('status')"
            >
                <option value="">{{ __('crud.common.actions.select', ['model' => __('crud.workspaces.fields.status')]) }}</option>
                @foreach($statuses as $status)
                    <option value="{{ $status->value }}">{{ $status->label() }}</option>
                @endforeach
            </flux:select>

            <div class="flex justify-end space-x-2 mt-10">
                <flux:button type="button" wire:click="$set('showModal', false)" variant="outline">
                    {{ __('crud.common.actions.cancel') }}
                </flux:button>
                <flux:button wire:loading.attr="disabled" wire:target="update" type="submit" variant="primary">
                    <span wire:loading.remove wire:target="update">{{ __('crud.common.actions.save') }}</span>
                    <span wire:loading wire:target="update">{{ __('crud.common.actions.saving') }}</span>
                </flux:button>
            </div>
        </form>
    </flux:modal>
</div>
