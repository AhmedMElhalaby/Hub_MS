<div class="p-6">
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <flux:heading>{{ __('Plan Details') }}</flux:heading>
            <flux:button wire:navigate href="{{ route('plans.index') }}" variant="outline">
                {{ __('Back to Plans') }}
            </flux:button>
        </div>
    </div>

    <!-- Plan Information Card -->
    <div class="mb-8 grid gap-6 lg:grid-cols-2">
        <flux:card>
            <x-slot:header>
                <flux:heading size="sm">{{ __('Plan Information') }}</flux:heading>
            </x-slot:header>
            <x-slot:content class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <div class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('Type') }}</div>
                        <div>{{ $plan->type->label() }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('Price') }}</div>
                        <div>{{ number_format($plan->price, 2) }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('Created') }}</div>
                        <div>{{ $plan->created_at->format('M d, Y') }}</div>
                    </div>
                </div>
            </x-slot:content>
        </flux:card>

        @if($plan->bookings->count() > 0)
            <flux:card>
                <x-slot:header>
                    <flux:heading size="sm">{{ __('Bookings') }}</flux:heading>
                </x-slot:header>
                <x-slot:content>
                    <flux:table>
                        <x-slot:header>
                            <flux:table.head>{{ __('Customer') }}</flux:table.head>
                            <flux:table.head>{{ __('Date') }}</flux:table.head>
                            <flux:table.head>{{ __('Status') }}</flux:table.head>
                        </x-slot:header>

                        <x-slot:body>
                            @foreach($plan->bookings as $booking)
                                <flux:table.row>
                                    <flux:table.cell>{{ $booking->customer->name }}</flux:table.cell>
                                    <flux:table.cell>{{ $booking->started_at->format('M d, Y') }}</flux:table.cell>
                                    <flux:table.cell>
                                        <flux:badge>{{ $booking->status }}</flux:badge>
                                    </flux:table.cell>
                                </flux:table.row>
                            @endforeach
                        </x-slot:body>
                    </flux:table>
                </x-slot:content>
            </flux:card>
        @endif
    </div>
</div>
