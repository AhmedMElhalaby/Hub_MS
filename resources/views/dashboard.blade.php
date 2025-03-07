<x-layouts.app>
    <div class="p-6">
        <!-- Statistics Cards -->
        <div class="grid gap-4 md:grid-cols-3 mb-6">
            <x-stat-card
                title="{{ __('Total Revenue') }}"
                value="{{ number_format($totalRevenue, 2) }}"
                description="{{ __('All time income') }}"
                trend="up"
                color="success"
            />
            <x-stat-card
                title="{{ __('Active Bookings') }}"
                value="{{ $activeBookings }}"
                description="{{ __('Current active bookings') }}"
                color="primary"
            />
            <x-stat-card
                title="{{ __('Available Workspaces') }}"
                value="{{ $availableWorkspaces }}"
                description="{{ __('Ready to book') }}"
                color="info"
            />
        </div>
        <!-- Recent Bookings and Finances -->
        <div class="grid gap-6 md:grid-cols-2">
            <!-- Recent Bookings -->
            <flux:card>
                <flux:card.header>
                    <flux:heading size="sm">{{ __('Recent Bookings') }}</flux:heading>
                </flux:card.header>
                <flux:card.content>
                    <flux:table>
                        <x-slot:header>
                            <flux:table.head>{{ __('Customer') }}</flux:table.head>
                            <flux:table.head>{{ __('Workspace') }}</flux:table.head>
                            <flux:table.head>{{ __('Status') }}</flux:table.head>
                            <flux:table.head>{{ __('Amount') }}</flux:table.head>
                        </x-slot:header>
                        <x-slot:body>
                            @forelse($recentBookings as $booking)
                                <flux:table.row>
                                    <flux:table.cell>{{ $booking->customer->name }}</flux:table.cell>
                                    <flux:table.cell>{{ $booking->workspace->desk }}</flux:table.cell>
                                    <flux:table.cell>
                                        <flux:badge variant="solid" :color="$booking->status->color()">
                                            {{ $booking->status->label() }}
                                        </flux:badge>
                                    </flux:table.cell>
                                    <flux:table.cell>{{ number_format($booking->total, 2) }}</flux:table.cell>
                                </flux:table.row>
                            @empty
                                <flux:table.row>
                                    <flux:table.cell colspan="4" class="text-center">{{ __('No recent bookings.') }}</flux:table.cell>
                                </flux:table.row>
                            @endforelse
                        </x-slot:body>
                    </flux:table>
                </flux:card.content>
            </flux:card>

            <!-- Recent Finances -->
            <flux:card>
                <flux:card.header>
                    <flux:heading size="sm">{{ __('Recent Finances') }}</flux:heading>
                </flux:card.header>
                <flux:card.content>
                    <flux:table>
                        <x-slot:header>
                            <flux:table.head>{{ __('Type') }}</flux:table.head>
                            <flux:table.head>{{ __('Amount') }}</flux:table.head>
                            <flux:table.head>{{ __('Date') }}</flux:table.head>
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
                                    <flux:table.cell colspan="3" class="text-center">{{ __('No recent finances.') }}</flux:table.cell>
                                </flux:table.row>
                            @endforelse
                        </x-slot:body>
                    </flux:table>
                </flux:card.content>
            </flux:card>
        </div>
    </div>

</x-layouts.app>
