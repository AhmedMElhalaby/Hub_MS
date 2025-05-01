<div>
    <flux:modal wire:model="showModal" variant="flyout">
        <form wire:submit.prevent="send" class="space-y-6">
            <flux:heading size="lg">{{ __('Send Access Credentials') }}</flux:heading>

            <div class="space-y-4">
                <div class="rounded-lg bg-zinc-50 p-4 dark:bg-zinc-900">
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span>{{ __('Username') }}</span>
                            <span class="font-mono">{{ $booking?->hotspot_username }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>{{ __('Password') }}</span>
                            <span class="font-mono">{{ $booking?->hotspot_password }}</span>
                        </div>
                    </div>
                </div>

                <flux:textarea
                    wire:model="messageText"
                    label="{{ __('Message') }}"
                    rows="3"
                    :error="$errors->first('messageText')"
                />
            </div>

            <div class="flex justify-end space-x-2">
                <flux:button type="button" wire:click="closeModal" variant="outline">
                    {{ __('Cancel') }}
                </flux:button>
                <flux:button type="submit" variant="primary">
                    {{ __('Send') }}
                </flux:button>
            </div>
        </form>
    </flux:modal>
</div>
