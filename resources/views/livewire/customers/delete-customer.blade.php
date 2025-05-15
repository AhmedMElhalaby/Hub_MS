<div>
    <flux:modal wire:model="showModal">
        <div class="space-y-6">
            <flux:heading size="lg">{{ __('crud.common.actions.delete') }}</flux:heading>
            <p>{{ __('crud.common.messages.delete_confirm', ['model' => __('crud.customers.labels.management')]) }}</p>
            <div class="flex justify-end space-x-2 mt-3">
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
