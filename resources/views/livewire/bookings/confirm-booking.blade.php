<div>
    <flux:modal wire:model="showModal">
        <div class="space-y-6">
            <flux:heading size="lg">{{ __('Confirm Booking') }}</flux:heading>
            <p>{{ __('Are you sure you want to Confirm this booking?') }}</p>
            <div class="flex justify-end space-x-2">
                <flux:button wire:click="closeModal" variant="outline">
                    {{ __('Cancel') }}
                </flux:button>
                <flux:button wire:click="confirm" variant="primary">
                    {{ __('Yes, Confirm') }}
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>
