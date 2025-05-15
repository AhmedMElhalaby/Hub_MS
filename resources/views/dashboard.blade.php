<x-layouts.app>
    <div class="p-6">
        <!-- Statistics Cards -->
        <div class="grid gap-4 md:grid-cols-3 mb-6">
            <x-stat-card
                title="{{ __('crud.finances.labels.total_income') }}"
                value="{{ number_format($totalRevenue, 2) }}"
                description="{{ __('crud.finances.labels.all_time_income') }}"
                trend="up"
                color="success"
            />
            <x-stat-card
                title="{{ __('crud.bookings.model.plural') }}"
                value="{{ $activeBookings }}"
                description="{{ __('crud.bookings.labels.active_bookings') }}"
                color="primary"
            />
            <x-stat-card
                title="{{ __('crud.workspaces.labels.available_workspaces') }}"
                value="{{ $availableWorkspaces }}"
                description="{{ __('crud.workspaces.labels.ready_to_book') }}"
                color="info"
            />
        </div>
        <!-- Recent Bookings and Finances -->
        <div class="grid gap-6 md:grid-cols-2">
            <!-- Recent Bookings -->
            <flux:card>
                <flux:card.header>
                    <flux:heading size="sm">{{ __('crud.bookings.labels.recent_bookings') }}</flux:heading>
                </flux:card.header>
                <flux:card.content>
                    <flux:table>
                        <x-slot:header>
                            <flux:table.head>{{ __('crud.customers.model.singular') }}</flux:table.head>
                            <flux:table.head>{{ __('crud.workspaces.model.singular') }}</flux:table.head>
                            <flux:table.head>{{ __('crud.bookings.fields.status') }}</flux:table.head>
                            <flux:table.head>{{ __('crud.bookings.fields.total') }}</flux:table.head>
                        </x-slot:header>
                        <x-slot:body>
                            @forelse($recentBookings as $booking)
                                <flux:table.row>
                                    <flux:table.cell>{{ $booking->customer?->name }}</flux:table.cell>
                                    <flux:table.cell>{{ $booking->workspace?->desk }}</flux:table.cell>
                                    <flux:table.cell>
                                        <flux:badge variant="solid" :color="$booking->status->color()">
                                            {{ $booking->status->label() }}
                                        </flux:badge>
                                    </flux:table.cell>
                                    <flux:table.cell>{{ number_format($booking->total, 2) }}</flux:table.cell>
                                </flux:table.row>
                            @empty
                                <flux:table.row>
                                    <flux:table.cell colspan="4" class="text-center">{{ __('crud.common.messages.no_records', ['model' => __('crud.bookings.model.plural')]) }}</flux:table.cell>
                                </flux:table.row>
                            @endforelse
                        </x-slot:body>
                    </flux:table>
                </flux:card.content>
            </flux:card>

            <!-- Recent Finances -->
            <flux:card>
                <flux:card.header>
                    <flux:heading size="sm">{{ __('crud.finances.labels.recent_finances') }}</flux:heading>
                </flux:card.header>
                <flux:card.content>
                    <flux:table>
                        <x-slot:header>
                            <flux:table.head>{{ __('crud.finances.fields.type') }}</flux:table.head>
                            <flux:table.head>{{ __('crud.finances.fields.amount') }}</flux:table.head>
                            <flux:table.head>{{ __('crud.common.fields.date') }}</flux:table.head>
                        </x-slot:header>
                        <x-slot:body>
                            @forelse($recentFinances as $finance)
                                <flux:table.row>
                                    <flux:table.cell>
                                        <flux:badge variant="solid" :color="$finance->type === \App\Enums\FinanceType::Income ? 'success' : 'danger'">
                                            {{ $finance->type->label() }}
                                        </flux:badge>
                                    </flux:table.cell>
                                    <flux:table.cell>{{ number_format($finance->amount, 2) }}</flux:table.cell>
                                    <flux:table.cell>{{ $finance->created_at->format('M d, Y') }}</flux:table.cell>
                                </flux:table.row>
                            @empty
                                <flux:table.row>
                                    <flux:table.cell colspan="3" class="text-center">{{ __('crud.common.messages.no_records', ['model' => __('crud.finances.model.plural')]) }}</flux:table.cell>
                                </flux:table.row>
                            @endforelse
                        </x-slot:body>
                    </flux:table>
                </flux:card.content>
            </flux:card>
        </div>
    </div>
</x-layouts.app>
