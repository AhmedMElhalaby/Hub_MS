<div class="p-6">
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <flux:heading>{{ __('crud.customers.labels.details') }}</flux:heading>
            <div class="flex space-x-2">
                <flux:button wire:click="$dispatch('open-edit-customer', { customerId: {{ $customer->id }} })" variant="primary">
                    {{ __('crud.customers.actions.edit') }}
                </flux:button>
                <flux:button wire:navigate href="{{ route('tenant.customers.index') }}" variant="outline">
                    {{ __('crud.common.actions.back') }}
                </flux:button>
            </div>
        </div>
    </div>
    <div class="mb-8 grid gap-6 lg:grid-cols-2">
        <flux:card>
            <flux:card.header>
                <flux:heading size="sm">{{ __('crud.customers.labels.personal_info') }}</flux:heading>
            </flux:card.header>
            <flux:card.content class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <div class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('crud.customers.fields.name') }}</div>
                        <div>{{ $customer->name }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('crud.customers.fields.email') }}</div>
                        <div>{{ $customer->email ?: '-' }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('crud.customers.fields.mobile') }}</div>
                        <div>{{ $customer->mobile }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('crud.customers.fields.specialization') }}</div>
                        <div>{{ $customer->specialization->value }}</div>
                    </div>
                    <div class="col-span-2">
                        <div class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('crud.customers.fields.address') }}</div>
                        <div>{{ $customer->address ?: '-' }}</div>
                    </div>
                </div>
            </flux:card.content>
        </flux:card>

        <flux:card>
            <flux:card.header>
                <flux:heading size="sm">{{ __('crud.customers.labels.financial_summary') }}</flux:heading>
            </flux:card.header>
            <flux:card.content class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <div class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('crud.customers.labels.total_bookings') }}</div>
                        <div class="text-2xl font-semibold">{{ $customer->bookings->count() }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('crud.customers.labels.total_payments') }}</div>
                        <div class="text-2xl font-semibold">{{ number_format($this->totalPayments, 2) }}</div>
                    </div>
                </div>
            </flux:card.content>
        </flux:card>
    </div>
    <flux:card class="mb-6 mt-3">
        <flux:card.header>
            <flux:heading size="sm">{{ __('crud.customers.labels.booking_history') }}</flux:heading>
        </flux:card.header>
        <flux:card.content>
            <flux:table>
                <x-slot:header>
                    <flux:table.head>{{ __('crud.bookings.fields.started_at') }}</flux:table.head>
                    <flux:table.head>{{ __('crud.bookings.fields.ended_at') }}</flux:table.head>
                    <flux:table.head>{{ __('crud.bookings.fields.workspace') }}</flux:table.head>
                    <flux:table.head>{{ __('crud.bookings.fields.plan') }}</flux:table.head>
                    <flux:table.head>{{ __('crud.bookings.fields.status') }}</flux:table.head>
                    <flux:table.head>{{ __('crud.bookings.fields.total') }}</flux:table.head>
                    <flux:table.head>{{ __('crud.bookings.fields.balance') }}</flux:table.head>
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
                                {{ __('crud.common.messages.no_records', ['model' => __('crud.bookings.model.plural')]) }}
                            </flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </x-slot:body>
            </flux:table>
        </flux:card.content>
    </flux:card>


    <livewire:customers.edit-customer />
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('customer-updated', () => {
                Livewire.dispatch('refresh');
            });
        });
    </script>
</div>
