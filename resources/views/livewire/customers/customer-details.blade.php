<div class="p-6">
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <flux:heading>{{ __('Customer Details') }}</flux:heading>
            <flux:button wire:navigate href="{{ route('customers.index') }}" variant="outline">
                {{ __('Back to Customers') }}
            </flux:button>
        </div>
    </div>

    <!-- Customer Information Card -->
    <div class="mb-8 grid gap-6 lg:grid-cols-2">
        <flux:card>
            <flux:card.header>
                <flux:heading size="sm">{{ __('Personal Information') }}</flux:heading>
            </flux:card.header>
            <flux:card.content class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <div class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('Name') }}</div>
                        <div>{{ $customer->name }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('Email') }}</div>
                        <div>{{ $customer->email ?: '-' }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('Mobile') }}</div>
                        <div>{{ $customer->mobile }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('Specialization') }}</div>
                        <div>{{ $customer->specialization->value }}</div>
                    </div>
                    <div class="col-span-2">
                        <div class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('Address') }}</div>
                        <div>{{ $customer->address ?: '-' }}</div>
                    </div>
                </div>
            </flux:card.content>
        </flux:card>

        <flux:card>
            <flux:card.header>
                <flux:heading size="sm">{{ __('Financial Summary') }}</flux:heading>
            </flux:card.header>
            <flux:card.content class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <div class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('Total Bookings') }}</div>
                        <div class="text-2xl font-semibold">{{ $customer->bookings->count() }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('Total Payments') }}</div>
                        <div class="text-2xl font-semibold">{{ number_format($this->totalPayments, 2) }}</div>
                    </div>
                </div>
            </flux:card.content>
        </flux:card>
    </div>

    <!-- Bookings History -->
    <flux:card class="mb-6 mt-3">
        <flux:card.header>
            <flux:heading size="sm">{{ __('Booking History') }}</flux:heading>
        </flux:card.header>
        <flux:card.content>
            <flux:table>
                <x-slot:header>
                    <flux:table.head>{{ __('Started At') }}</flux:table.head>
                    <flux:table.head>{{ __('Ended At') }}</flux:table.head>
                    <flux:table.head>{{ __('Workspace') }}</flux:table.head>
                    <flux:table.head>{{ __('Plan') }}</flux:table.head>
                    <flux:table.head>{{ __('Status') }}</flux:table.head>
                    <flux:table.head>{{ __('Total') }}</flux:table.head>
                    <flux:table.head>{{ __('Balance') }}</flux:table.head>
                </x-slot:header>

                <x-slot:body>
                    @forelse($customer->bookings as $booking)
                        <flux:table.row wire:key="{{ $booking->id }}">
                            <flux:table.cell>{{ $booking->started_at->format('Y-m-d H:i') }}</flux:table.cell>
                            <flux:table.cell>{{ $booking->ended_at->format('Y-m-d H:i') }}</flux:table.cell>
                            <flux:table.cell>Desk {{ $booking->workspace->desk }}</flux:table.cell>
                            <flux:table.cell>{{ $booking->plan->type->value }}</flux:table.cell>
                            <flux:table.cell>
                                <flux:badge color="{{ $booking->status->color() }}">
                                    {{ $booking->status->value }}
                                </flux:badge>
                            </flux:table.cell>
                            <flux:table.cell>{{ number_format($booking->total, 2) }}</flux:table.cell>
                            <flux:table.cell>{{ number_format($booking->balance, 2) }}</flux:table.cell>
                        </flux:table.row>
                    @empty
                        <flux:table.row>
                            <flux:table.cell colspan="7" class="text-center">
                                {{ __('No bookings found.') }}
                            </flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </x-slot:body>
            </flux:table>
        </flux:card.content>
    </flux:card>
</div>
