<div>
    <flux:modal wire:model="showModal" variant="flyout">
        <form wire:submit.prevent="send" class="space-y-6">
            <flux:heading size="lg">{{ __('crud.bookings.actions.send_credentials') }}</flux:heading>

            <div class="space-y-4">
                <div class="rounded-lg bg-zinc-50 p-4 dark:bg-zinc-900">
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span>{{ __('crud.bookings.fields.hotspot_username') }}</span>
                            <span class="font-mono">{{ $booking?->hotspot_username }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>{{ __('crud.bookings.fields.hotspot_password') }}</span>
                            <span class="font-mono">{{ $booking?->hotspot_password }}</span>
                        </div>
                    </div>
                </div>

                <flux:textarea
                    wire:model="messageText"
                    label="{{ __('crud.common.messages.message') }}"
                    rows="3"
                    :error="$errors->first('messageText')"
                />
            </div>

            <div class="flex justify-end space-x-2">
                <flux:button type="button" wire:click="closeModal" variant="outline">
                    {{ __('crud.common.actions.cancel') }}
                </flux:button>
                <flux:button type="submit" variant="primary">
                    {{ __('crud.bookings.actions.send_credentials') }}
                </flux:button>
            </div>
        </form>
    </flux:modal>
</div>
