<div>
    <flux:modal wire:model="showModal">
        <div class="space-y-6">
            <flux:heading size="lg">{{ __('crud.expenses.actions.delete') }}</flux:heading>
            <p>{{ __('crud.expenses.messages.delete_confirm') }}</p>
            <div class="flex justify-end space-x-2">
                <flux:button wire:click="$set('showModal', false)" variant="outline">
                    {{ __('crud.common.actions.cancel') }}
                </flux:button>
                <flux:button wire:click="delete" variant="danger">
                    {{ __('crud.common.actions.delete') }}
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>
