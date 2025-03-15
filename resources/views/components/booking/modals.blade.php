@props(['selectedBooking', 'plans','renewalEndedAt'])

<!-- Payment Modal -->
<flux:modal wire:model="showPaymentModal" variant="flyout">
    <form wire:submit.prevent="processPayment" class="space-y-6">
        <flux:heading size="lg">{{ __('Process Payment') }}</flux:heading>

        @if($selectedBooking)
            <div class="rounded-lg bg-zinc-50 p-4 dark:bg-zinc-900">
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span>{{ __('Total Amount') }}</span>
                        <span>{{ number_format($selectedBooking->total, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>{{ __('Balance') }}</span>
                        <span>{{ number_format($selectedBooking->balance, 2) }}</span>
                    </div>
                </div>
            </div>
        @endif

        <flux:input
            wire:model="paymentAmount"
            type="number"
            step="0.01"
            label="{{ __('Payment Amount') }}"
            required
            :error="$errors->first('paymentAmount')"
        />
        <flux:select
            wire:model="paymentMethod"
            label="{{ __('Payment Method') }}"
            required
        >
            @foreach(App\Enums\PaymentMethod::cases() as $method)
                <option value="{{ $method->value }}">{{ $method->label() }}</option>
            @endforeach
        </flux:select>

        <div class="flex justify-end space-x-2">
            <flux:button type="button" wire:click="$set('showPaymentModal', false)" variant="outline">
                {{ __('Cancel') }}
            </flux:button>
            <flux:button wire:loading.attr="disabled" wire:target="processPayment" type="submit" variant="primary">
                <span wire:loading.remove wire:target="processPayment">{{ __('Process') }}</span>
                <span wire:loading wire:target="processPayment">{{ __('Processing...') }}</span>
            </flux:button>
        </div>
    </form>
</flux:modal>

<!-- Renewal Modal -->
<flux:modal wire:model="showRenewalModal" variant="flyout">
    <form wire:submit.prevent="processRenewal" class="space-y-6">
        <flux:heading size="lg">{{ __('Renew Booking') }}</flux:heading>

        <flux:select
            wire:model.live="renewalPlanId"
            label="{{ __('Plan') }}"
            required
            :error="$errors->first('renewalPlanId')"
        >
            @foreach($plans as $plan)
                <option value="{{ $plan->id }}">{{ $plan->type->label() }} - {{ number_format($plan->price, 2) }}</option>
            @endforeach
        </flux:select>

        <div class="grid grid-cols-2 gap-4">
            <flux:input
                wire:model.live="renewalStartedAt"
                type="datetime-local"
                label="{{ __('Start Date') }}"
                required
                :error="$errors->first('renewalStartedAt')"
            />

            <flux:input
                wire:model.live="renewalDuration"
                type="number"
                min="1"
                label="{{ __('Duration (Times)') }}"
                required
                :error="$errors->first('renewalDuration')"
            />
        </div>

        @if($renewalEndedAt)
            <div class="rounded-lg bg-zinc-50 p-4 dark:bg-zinc-900">
                <div class="font-medium">{{ __('End Date') }}</div>
                <div class="text-lg">{{ \Carbon\Carbon::parse($renewalEndedAt)->format('M d, Y H:i') }}</div>
            </div>
        @endif

        <div class="flex justify-end space-x-2">
            <flux:button type="button" wire:click="$set('showRenewalModal', false)" variant="outline">
                {{ __('Cancel') }}
            </flux:button>
            <flux:button type="submit" variant="primary">
                {{ __('Renew') }}
            </flux:button>
        </div>
    </form>
</flux:modal>
