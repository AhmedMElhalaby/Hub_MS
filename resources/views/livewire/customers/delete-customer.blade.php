<div>
    <flux:modal wire:model="showModal">
        <div class="space-y-6">
            <flux:heading size="lg">{{ __('Delete Customer') }}</flux:heading>
            <p>{{ __('Are you sure you want to delete this customer?') }}</p>
            <div class="flex justify-end space-x-2 mt-3">
                <flux:button wire:click="closeModal" variant="outline">
                    {{ __('Cancel') }}
                </flux:button>
                <flux:button wire:click="delete" variant="danger">
                    {{ __('Delete') }}
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>
