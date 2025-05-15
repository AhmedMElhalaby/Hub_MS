<div>
    <flux:modal wire:model="showModal">
        <div class="space-y-4">
            <flux:heading size="lg">{{ __('crud.users.actions.delete') }}</flux:heading>
            <p>{{ __('crud.common.messages.delete_confirm', ['model' => __('crud.users.model.singular')]) }}</p>
            <div class="flex justify-end space-x-2">
                <flux:button wire:click="closeModal" variant="outline">
                    {{ __('crud.common.actions.cancel') }}
                </flux:button>
                <flux:button wire:click="delete" variant="danger">
                    {{ __('crud.common.actions.delete') }}
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>
