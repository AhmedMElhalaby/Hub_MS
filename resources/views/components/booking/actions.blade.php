@props(['booking'])
@use('App\Enums\BookingStatus')
<div class="flex space-x-2">
    @if(!in_array($booking->status, [BookingStatus::Completed, BookingStatus::Cancelled]))
        <flux:button
            wire:click="editBooking({{ $booking->id }})"
            size="sm"
            variant="outline"
        >
            {{ __('Edit') }}
        </flux:button>
    @endif

    @if($booking->status->canConfirm())
        <flux:button wire:click="confirmBooking({{ $booking->id }})" size="sm">
            {{ __('Confirm') }}
        </flux:button>
    @endif

    @if(!in_array($booking->status, [\App\Enums\BookingStatus::Draft, \App\Enums\BookingStatus::Cancelled]) && $booking->balance > 0)
        <flux:button wire:click="showPayment({{ $booking->id }})" variant="primary" size="sm">
            {{ __('Pay') }}
        </flux:button>
    @endif

    @if($booking->status->canCancel())
        <flux:button wire:click="cancelBooking({{ $booking->id }})" variant="danger" size="sm">
            {{ __('Cancel') }}
        </flux:button>
    @endif

    @if($booking->status->canRenew())
        <flux:button wire:click="renewBooking({{ $booking->id }})" variant="primary" size="sm">
            {{ __('Renew') }}
        </flux:button>
    @endif
</div>
