<div>
    <flux:modal wire:model="showModal" variant="flyout">
        <form wire:submit.prevent="pay" class="space-y-6">
            <flux:heading size="lg">{{ __('Process Payment') }}</flux:heading>

            @if(isset($booking) && $booking)
            <div class="rounded-lg bg-zinc-50 p-4 dark:bg-zinc-900">
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span>{{ __('Total Amount') }}</span>
                            <span>{{ number_format($booking->total, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>{{ __('Balance') }}</span>
                            <span>{{ number_format($booking->balance, 2) }}</span>
                        </div>
                    </div>
                </div>
            @endif

            <flux:input
                wire:model.live="amount"
                type="number"
                step="1"
                label="{{ __('Payment Amount') }}"
                required
                :error="$errors->first('amount')"
            />
            <flux:select
                wire:model.live="payment_method"
                label="{{ __('Payment Method') }}"
                required
                :error="$errors->first('payment_method')"
            >
                @foreach(App\Enums\PaymentMethod::cases() as $method)
                    <option value="{{ $method->value }}" @if($method == App\Enums\PaymentMethod::Cash) selected @endif>{{ $method->label() }}</option>
                @endforeach
            </flux:select>

            <div class="flex justify-end space-x-2">
                <flux:button type="button" wire:click="closeModal" variant="outline">
                    {{ __('Cancel') }}
                </flux:button>
                <flux:button wire:loading.attr="disabled" wire:target="pay" type="submit" variant="primary">
                    <span wire:loading.remove wire:target="pay">{{ __('Process') }}</span>
                    <span wire:loading wire:target="pay">{{ __('Processing...') }}</span>
                </flux:button>
            </div>
        </form>
    </flux:modal>
</div>
