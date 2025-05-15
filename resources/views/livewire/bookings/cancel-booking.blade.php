<div>
    <flux:modal wire:model="showModal">
        <div class="space-y-6">
            <flux:heading size="lg">{{ __('crud.bookings.actions.cancel') }}</flux:heading>
            <p>{{ __('crud.bookings.messages.confirm_cancel') }}</p>
            <div class="flex justify-end space-x-2">
                <flux:button wire:click="closeModal" variant="outline">
                    {{ __('crud.common.actions.cancel') }}
                </flux:button>
                <flux:button wire:click="cancel" variant="danger">
                    {{ __('crud.bookings.actions.cancel') }}
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>
