<div class="p-6">
    <div class="mb-6">
        <flux:heading>{{ __('Booking Details') }}</flux:heading>
    </div>

    <div class="grid gap-6 md:grid-cols-2">
        <!-- Booking Information -->
        <div class="space-y-6">
            <flux:card>
                <flux:card.header>
                    <flux:heading size="sm">{{ __('Booking Information') }}</flux:heading>
                </flux:card.header>
                <flux:card.content class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <div class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('Status') }}</div>
                            <div>
                                <flux:badge variant="solid" :color="$booking->status->color()">
                                    {{ $booking->status->label() }}
                                </flux:badge>
                            </div>
                        </div>
                        <div>
                            <div class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('Total Amount') }}</div>
                            <div class="font-semibold">{{ number_format($booking->total, 2) }}</div>
                        </div>
                        <div>
                            <div class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('Balance') }}</div>
                            <div class="font-semibold">{{ number_format($booking->balance, 2) }}</div>
                        </div>
                        <div>
                            <div class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('Start Date') }}</div>
                            <div>{{ $booking->started_at->format('M d, Y H:i') }}</div>
                        </div>
                        <div>
                            <div class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('End Date') }}</div>
                            <div>{{ $booking->ended_at->format('M d, Y H:i') }}</div>
                        </div>
                    </div>
                </flux:card.content>
            </flux:card>

            <!-- Customer Information -->
            <flux:card>
                <flux:card.header>
                    <flux:heading size="sm">{{ __('Customer Information') }}</flux:heading>
                </flux:card.header>
                <flux:card.content class="space-y-4">
                    <div>
                        <div class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('Name') }}</div>
                        <div class="font-semibold">{{ $booking->customer->name }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('Email') }}</div>
                        <div>{{ $booking->customer->email }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('Phone') }}</div>
                        <div>{{ $booking->customer->phone }}</div>
                    </div>
                </flux:card.content>
            </flux:card>
        </div>

        <!-- Workspace and Plan Information -->
        <div class="space-y-6">
            <flux:card>
                <flux:card.header>
                    <flux:heading size="sm">{{ __('Workspace Information') }}</flux:heading>
                </flux:card.header>
                <flux:card.content>
                    <div class="space-y-4">
                        <div>
                            <div class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('Desk') }}</div>
                            <div class="font-semibold">{{ $booking->workspace->desk }}</div>
                        </div>
                        <div>
                            <div class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('Status') }}</div>
                            <div>
                                <flux:badge variant="solid" :color="$booking->workspace->status->color()">
                                    {{ $booking->workspace->status->label() }}
                                </flux:badge>
                            </div>
                        </div>
                    </div>
                </flux:card.content>
            </flux:card>

            <flux:card>
                <flux:card.header>
                    <flux:heading size="sm">{{ __('Plan Information') }}</flux:heading>
                </flux:card.header>
                <flux:card.content>
                    <div class="space-y-4">
                        <div>
                            <div class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('Plan') }}</div>
                            <div class="font-semibold">{{ $booking->plan->title }}</div>
                        </div>
                        <div>
                            <div class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('Type') }}</div>
                            <div>{{ $booking->plan->type->label() }}</div>
                        </div>
                        <div>
                            <div class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('Price') }}</div>
                            <div>{{ number_format($booking->plan->price, 2) }}</div>
                        </div>
                    </div>
                </flux:card.content>
            </flux:card>
        </div>
    </div>

    <!-- Payment History -->
    <div class="mt-6">
        <flux:card>
            <flux:card.header>
                <flux:heading size="sm">{{ __('Payment History') }}</flux:heading>
            </flux:card.header>
            <flux:card.content>
                <flux:table>
                    <x-slot:header>
                        <flux:table.head>{{ __('Date') }}</flux:table.head>
                        <flux:table.head>{{ __('Amount') }}</flux:table.head>
                        <flux:table.head>{{ __('Note') }}</flux:table.head>
                    </x-slot:header>
                    <x-slot:body>
                        @forelse($booking->finances as $finance)
                            <flux:table.row>
                                <flux:table.cell>{{ $finance->created_at->format('M d, Y H:i') }}</flux:table.cell>
                                <flux:table.cell>{{ number_format($finance->amount, 2) }}</flux:table.cell>
                                <flux:table.cell>{{ $finance->note }}</flux:table.cell>
                            </flux:table.row>
                        @empty
                            <flux:table.row>
                                <flux:table.cell colspan="3" class="text-center">{{ __('No payments found.') }}</flux:table.cell>
                            </flux:table.row>
                        @endforelse
                    </x-slot:body>
                </flux:table>
            </flux:card.content>
        </flux:card>
    </div>
</div>
