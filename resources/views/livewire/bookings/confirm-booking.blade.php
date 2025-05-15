<div>
    <flux:modal wire:model="showModal">
        <div class="space-y-6">
            <flux:heading size="lg">{{ __('crud.bookings.actions.confirm') }}</flux:heading>
            <p>{{ __('crud.common.messages.confirm', ['model' => __('crud.bookings.model.singular')]) }}</p>
            <div class="flex justify-end space-x-2">
                <flux:button wire:click="closeModal" variant="outline">
                    {{ __('crud.common.actions.cancel') }}
                </flux:button>
                <flux:button wire:click="confirm" variant="primary">
                    {{ __('crud.common.actions.confirm') }}
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>
