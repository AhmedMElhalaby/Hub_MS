<div>
    <flux:modal wire:model="showModal">
        <div class="space-y-6">
            <flux:heading size="lg">{{ __('Cancel Booking') }}</flux:heading>
            <p>{{ __('Are you sure you want to cancel this booking?') }}</p>
            <div class="flex justify-end space-x-2">
                <flux:button wire:click="closeModal" variant="outline">
                    {{ __('Close') }}
                </flux:button>
                <flux:button wire:click="cancel" variant="danger">
                    {{ __('Yes, Cancel') }}
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>
