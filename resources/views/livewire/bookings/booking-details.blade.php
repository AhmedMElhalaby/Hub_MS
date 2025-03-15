<div class="p-6">
    <div class="mb-6 flex items-center justify-between">
        <flux:heading>{{ __('Booking Details') }}</flux:heading>
        <x-booking.actions :booking="$booking" />
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
                        <div>{{ $booking->customer->mobile }}</div>
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

    <!-- Event History -->
    <div class="mt-6">
        <flux:card>
            <flux:card.header>
                <flux:heading size="sm">{{ __('Event History') }}</flux:heading>
            </flux:card.header>
            <flux:card.content>
                <flux:table>
                    <x-slot:header>
                        <flux:table.head>{{ __('Date') }}</flux:table.head>
                        <flux:table.head>{{ __('Event') }}</flux:table.head>
                        <flux:table.head>{{ __('User') }}</flux:table.head>
                        <flux:table.head>{{ __('Details') }}</flux:table.head>
                    </x-slot:header>
                    <x-slot:body>
                        @forelse($booking->events()->latest()->get() as $event)
                            <flux:table.row>
                                <flux:table.cell>{{ $event->created_at->format('M d, Y H:i') }}</flux:table.cell>
                                <flux:table.cell>{{ $event->event_type }}</flux:table.cell>
                                <flux:table.cell>{{ $event->user?->name ?? '-' }}</flux:table.cell>
                                <flux:table.cell>
                                    @if($event->metadata)
                                        <div class="space-y-1">
                                            @foreach($event->metadata as $key => $value)
                                                <div class="text-sm">
                                                    <span class="font-medium">{{ Str::title($key) }}:</span>
                                                    <span>{{ $value }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        -
                                    @endif
                                </flux:table.cell>
                            </flux:table.row>
                        @empty
                            <flux:table.row>
                                <flux:table.cell colspan="5" class="text-center">{{ __('No events found.') }}</flux:table.cell>
                            </flux:table.row>
                        @endforelse
                    </x-slot:body>
                </flux:table>
            </flux:card.content>
        </flux:card>
    </div>

    @if($booking->hotspot_username)
        <flux:card class="mt-6">
            <flux:card.header class="flex items-center justify-between">
                <flux:heading size="sm">{{ __('Hotspot Access Details') }}</flux:heading>
                <flux:button
                    wire:click="$set('showCredentialsModal', true)"
                    variant="outline"
                    size="sm"
                    class="flex space-x-2"
                >
                    {{ __('Send Credentials') }}
                </flux:button>
            </flux:card.header>
            <flux:card.content>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium">{{ __('Username') }}</span>
                        <div class="flex items-center space-x-2">
                            <span class="font-mono">{{ $booking->hotspot_username }}</span>
                            <flux:button
                                x-data
                                x-on:click="
                                    $el.setAttribute('disabled', true);
                                    await navigator.clipboard.writeText('{{ $booking->hotspot_username }}');
                                    $dispatch('notify', { message: 'Username copied!' });
                                    $el.removeAttribute('disabled');
                                "
                                size="xs"
                                variant="ghost"
                            >
                                <flux:icon name="clipboard" class="size-4" />
                            </flux:button>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium">{{ __('Password') }}</span>
                        <div class="flex items-center space-x-2">
                            <span class="font-mono">{{ $booking->hotspot_password }}</span>
                            <flux:button
                                x-data
                                x-on:click="
                                    $el.setAttribute('disabled', true);
                                    await navigator.clipboard.writeText('{{ $booking->hotspot_password }}');
                                    $dispatch('notify', { message: 'Password copied!' });
                                    $el.removeAttribute('disabled');
                                "
                                size="xs"
                                variant="ghost"
                            >
                                <flux:icon name="clipboard" class="size-4" />
                            </flux:button>
                        </div>
                    </div>
                </div>
            </flux:card.content>
        </flux:card>
    @endif

    <!-- Add this at the bottom of the file -->
    <flux:modal wire:model="showCredentialsModal" variant="flyout">
        <form wire:submit.prevent="sendCredentials" class="space-y-6">
            <flux:heading size="lg">{{ __('Send Access Credentials') }}</flux:heading>

            <div class="space-y-4">
                <div class="rounded-lg bg-zinc-50 p-4 dark:bg-zinc-900">
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span>{{ __('Username') }}</span>
                            <span class="font-mono">{{ $booking->hotspot_username }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>{{ __('Password') }}</span>
                            <span class="font-mono">{{ $booking->hotspot_password }}</span>
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
                <flux:button type="button" wire:click="$set('showCredentialsModal', false)" variant="outline">
                    {{ __('Cancel') }}
                </flux:button>
                <flux:button type="submit" variant="primary">
                    {{ __('Send') }}
                </flux:button>
            </div>
        </form>
    </flux:modal>
    <x-booking.modals :selected-booking="$selectedBooking" :plans="$plans" :renewalEndedAt="$renewalEndedAt" />
</div>
