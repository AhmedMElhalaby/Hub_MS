@use('App\Enums\BookingStatus')
<div class="p-6">
    <div class="mb-6 flex items-center justify-between">
        <flux:heading>{{ __('crud.bookings.labels.management') }}</flux:heading>
        <div class="flex space-x-2">
            @if($booking->status->canEdit())
                <flux:button wire:click="$dispatch('open-edit-booking', { bookingId: {{ $booking->id }} })" size="sm" variant="outline">
                    {{ __('crud.bookings.actions.edit') }}
                </flux:button>
            @endif
            @if($booking->status->canConfirm())
                <flux:button wire:click="$dispatch('open-confirm-booking', { bookingId: {{ $booking->id }} })" size="sm">
                    {{ __('crud.common.actions.confirm') }}
                </flux:button>
            @endif
            @if($booking->status->canPay() && $booking->balance > 0)
                <flux:button wire:click="$dispatch('open-pay-booking', { bookingId: {{ $booking->id }} })" variant="primary" size="sm">
                    {{ __('crud.bookings.actions.pay') }}
                </flux:button>
            @endif
            @if($booking->status->canCancel())
                <flux:button wire:click="$dispatch('open-cancel-booking', { bookingId: {{ $booking->id }} })" variant="danger" size="sm">
                    {{ __('crud.bookings.actions.cancel') }}
                </flux:button>
            @endif
            @if($booking->status->canRenew())
                <flux:button wire:click="$dispatch('open-renew-booking', { bookingId: {{ $booking->id }} })" variant="primary" size="sm">
                    {{ __('crud.bookings.actions.renew') }}
                </flux:button>
            @endif
            <flux:button wire:navigate href="{{ route('tenant.bookings.index') }}" variant="outline" size="sm">
                {{ __('crud.bookings.labels.back_to_bookings') }}
            </flux:button>
        </div>
    </div>

    <div class="grid gap-6 md:grid-cols-2">
        <!-- Booking Information -->
        <div class="space-y-6">
            <flux:card>
                <flux:card.header>
                    <flux:heading size="sm">{{ __('crud.bookings.labels.basic_information') }}</flux:heading>
                </flux:card.header>
                <flux:card.content class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <div class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('crud.bookings.fields.status') }}</div>
                            <div>
                                <flux:badge variant="solid" :color="$booking->status->color()">
                                    {{ $booking->status->label() }}
                                </flux:badge>
                            </div>
                        </div>
                        <div>
                            <div class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('crud.bookings.fields.total') }}</div>
                            <div class="font-semibold">{{ number_format($booking->total, 2) }}</div>
                        </div>
                        <div>
                            <div class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('crud.bookings.fields.balance') }}</div>
                            <div class="font-semibold">{{ number_format($booking->balance, 2) }}</div>
                        </div>
                        <div>
                            <div class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('crud.bookings.fields.started_at') }}</div>
                            <div>{{ $booking->started_at->format('M d, Y H:i') }}</div>
                        </div>
                        <div>
                            <div class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('crud.bookings.fields.ended_at') }}</div>
                            <div>{{ $booking->ended_at->format('M d, Y H:i') }}</div>
                        </div>
                    </div>
                </flux:card.content>
            </flux:card>

            <!-- Customer Information -->
            <flux:card>
                <flux:card.header>
                    <flux:heading size="sm">{{ __('crud.bookings.labels.customer_information') }}</flux:heading>
                </flux:card.header>
                <flux:card.content class="space-y-4">
                    <div>
                        <div class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('crud.customers.fields.name') }}</div>
                        <div class="font-semibold">{{ $booking->customer->name }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('crud.customers.fields.email') }}</div>
                        <div>{{ $booking->customer->email }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('crud.customers.fields.mobile') }}</div>
                        <div>{{ $booking->customer->mobile }}</div>
                    </div>
                </flux:card.content>
            </flux:card>
        </div>

        <!-- Workspace and Plan Information -->
        <div class="space-y-6">
            <flux:card>
                <flux:card.header>
                    <flux:heading size="sm">{{ __('crud.bookings.labels.workspace_information') }}</flux:heading>
                </flux:card.header>
                <flux:card.content>
                    <div class="space-y-4">
                        <div>
                            <div class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('crud.workspaces.fields.desk') }}</div>
                            <div class="font-semibold">{{ $booking->workspace->desk }}</div>
                        </div>
                        <div>
                            <div class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('crud.workspaces.fields.status') }}</div>
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
                    <flux:heading size="sm">{{ __('crud.bookings.labels.plan_information') }}</flux:heading>
                </flux:card.header>
                <flux:card.content>
                    <div class="space-y-4">
                        <div>
                            <div class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('crud.plans.model.singular') }}</div>
                            <div class="font-semibold">{{ $booking->plan->title }}</div>
                        </div>
                        <div>
                            <div class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('crud.plans.fields.type') }}</div>
                            <div>{{ $booking->plan->type->label() }}</div>
                        </div>
                        <div>
                            <div class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('crud.plans.fields.price') }}</div>
                            <div>{{ number_format($booking->plan->price, 2) }}</div>
                        </div>
                    </div>
                </flux:card.content>
            </flux:card>
        </div>
    </div>

    <div class="mt-6">
        <flux:card>
            <flux:card.header>
                <flux:heading size="sm">{{ __('crud.bookings.labels.payment_history') }}</flux:heading>
            </flux:card.header>
            <flux:card.content>
                <flux:table>
                    <x-slot:header>
                        <flux:table.head>{{ __('crud.common.fields.created_at') }}</flux:table.head>
                        <flux:table.head>{{ __('crud.bookings.fields.amount') }}</flux:table.head>
                        <flux:table.head>{{ __('crud.common.fields.note') }}</flux:table.head>
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
                                <flux:table.cell colspan="3" class="text-center">{{ __('crud.common.messages.no_payments') }}</flux:table.cell>
                            </flux:table.row>
                        @endforelse
                    </x-slot:body>
                </flux:table>
            </flux:card.content>
        </flux:card>
    </div>

    <div class="mt-6">
        <flux:card>
            <flux:card.header>
                <flux:heading size="sm">{{ __('crud.bookings.labels.event_history') }}</flux:heading>
            </flux:card.header>
            <flux:card.content>
                <flux:table>
                    <x-slot:header>
                        <flux:table.head>{{ __('crud.common.fields.date') }}</flux:table.head>
                        <flux:table.head>{{ __('crud.bookings.fields.event') }}</flux:table.head>
                        <flux:table.head>{{ __('crud.common.fields.user') }}</flux:table.head>
                        <flux:table.head>{{ __('crud.common.fields.details') }}</flux:table.head>
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
                                <flux:table.cell colspan="5" class="text-center">{{ __('crud.common.messages.no_events') }}</flux:table.cell>
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
                <div class="flex items-center space-x-4">
                    <flux:heading size="sm">{{ __('crud.bookings.labels.hotspot_credentials') }}</flux:heading>
                    <div class="flex items-center space-x-2">
                        @if($booking->hotspot_is_created)
                            <flux:badge color="green">
                                <flux:icon name="check-circle" class="size-4 mr-1" />
                                {{ __('crud.common.messages.created') }}
                            </flux:badge>
                        @endif
                        @if($booking->credentials_is_sent)
                            <flux:badge color="green">
                                <flux:icon name="envelope" class="size-4 mr-1" />
                                {{ __('crud.common.messages.sent') }}
                            </flux:badge>
                        @endif
                    </div>
                </div>
                <flux:button
                    wire:click="$dispatch('open-send-credentials', { bookingId: {{ $booking->id }} })"
                    variant="outline"
                    size="sm"
                    class="flex space-x-2"
                >
                    {{ __('crud.bookings.actions.send_credentials') }}
                </flux:button>
            </flux:card.header>
            <flux:card.content>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium">{{ __('crud.bookings.fields.hotspot_username') }}</span>
                        <div class="flex items-center space-x-2">
                            <span class="font-mono">{{ $booking->hotspot_username }}</span>
                            <flux:button
                                x-data
                                x-on:click="
                                    $el.setAttribute('disabled', true);
                                    await navigator.clipboard.writeText('{{ $booking->hotspot_username }}');
                                    $dispatch('notify', { message: '{{ __('crud.bookings.messages.username_copied') }}' });
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
                        <span class="text-sm font-medium">{{ __('crud.bookings.fields.hotspot_password') }}</span>
                        <div class="flex items-center space-x-2">
                            <span class="font-mono">{{ $booking->hotspot_password }}</span>
                            <flux:button
                                x-data
                                x-on:click="
                                    $el.setAttribute('disabled', true);
                                    await navigator.clipboard.writeText('{{ $booking->hotspot_password }}');
                                    $dispatch('notify', { message: '{{ __('crud.bookings.messages.password_copied') }}' });
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

    <livewire:bookings.send-credentials :booking="$booking" />
    <livewire:bookings.pay-booking />
    <livewire:bookings.edit-booking bookingId='{{ $booking->id }}'/>
    <livewire:bookings.confirm-booking />
    <livewire:bookings.renew-booking />
    <livewire:bookings.cancel-booking />

    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('booking-updated', () => {
                Livewire.dispatch('refresh');
            });
            Livewire.on('booking-canceled', () => {
                Livewire.dispatch('refresh');
            });
            Livewire.on('booking-confirm', () => {
                Livewire.dispatch('refresh');
            });
            Livewire.on('booking-payed', () => {
                Livewire.dispatch('refresh');
            });
            Livewire.on('booking-renewed', () => {
                Livewire.dispatch('refresh');
            });
            Livewire.on('credentials-sent', () => {
                Livewire.dispatch('refresh');
            });
        });
    </script>
</div>
