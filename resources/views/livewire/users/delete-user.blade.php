<div>
    <flux:modal wire:model="showModal">
        <div class="space-y-4">
            <flux:heading size="lg">{{ __('Delete User') }}</flux:heading>
            <p>{{ __('Are you sure you want to delete this user?') }}</p>
            <div class="flex justify-end space-x-2">
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