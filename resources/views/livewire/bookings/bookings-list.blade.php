<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <flux:heading>{{ __('Bookings Management') }}</flux:heading>
        <flux:button wire:click="$dispatch('open-create-booking')" variant="primary">
            {{ __('Add New Booking') }}
        </flux:button>
    </div>

    <div class="space-y-4">
        <!-- Search Box and Advanced Filters Button -->
        <div class="flex gap-4 items-end">
            <div class="flex-1">
                <flux:input wire:model.live="search" type="search" label="{{ __('Search') }}"
                    placeholder="Search bookings..." />
            </div>
            <div>
                <flux:button wire:click="triggerFilter" variant="outline" class="flex items-center gap-2">
                    <span class="inline-flex items-center">
                        {{ __('Advanced Filters') }}
                        <flux:icon name="chevron-{{ $showFilter ? 'up' : 'down' }}" class="size-4 ml-2" />
                    </span>
                </flux:button>
            </div>
        </div>

        <!-- Accordion Filter Section -->
        <div class="rounded-lg overflow-hidden transition-all duration-300 ease-in-out"
            x-cloak
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="transform -translate-y-4 opacity-0"
            x-transition:enter-end="transform translate-y-0 opacity-100"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="transform translate-y-0 opacity-100"
            x-transition:leave-end="transform -translate-y-4 opacity-0"
            @if(!$showFilter) style="max-height: 0px;" @else style="max-height: 500px;" @endif>
            <div class="p-4 space-y-4 bg-zinc-50 p-4 dark:bg-zinc-900 rounded-lg">
                <!-- Status and Plan Filters -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <flux:select wire:model.live="statusFilter" label="{{ __('Filter by Status') }}">
                        <option value="">{{ __('All Statuses') }}</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status->value }}">{{ $status->label() }}</option>
                        @endforeach
                    </flux:select>

                    <flux:select wire:model.live="planFilter" label="{{ __('Filter by Plan') }}">
                        <option value="">{{ __('All Plans') }}</option>
                        @foreach($plans as $plan)
                            <option value="{{ $plan->id }}">{{ $plan->type->label() }}</option>
                        @endforeach
                    </flux:select>
                </div>

                <!-- Date Filters -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <flux:select wire:model.live="dateType" label="{{ __('Date Type') }}">
                        @foreach($dateTypes as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </flux:select>

                    <flux:input
                        wire:model.live="dateFrom"
                        type="date"
                        label="{{ __('From Date') }}"
                    />

                    <flux:input
                        wire:model.live="dateTo"
                        type="date"
                        label="{{ __('To Date') }}"
                    />
                </div>

                <!-- Reset Button -->
                <div class="flex justify-end">
                    <flux:button wire:click="resetFilters" variant="outline" size="sm">
                        {{ __('Reset Filters') }}
                    </flux:button>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="mt-6 overflow-x-auto">
        <flux:table>
            <x-slot:header>
                <flux:table.head>{{ __('Customer') }}</flux:table.head>
                <flux:table.head>{{ __('Workspace') }}</flux:table.head>
                <flux:table.head>{{ __('Plan') }}</flux:table.head>
                <flux:table.head>{{ __('Date') }}</flux:table.head>
                <flux:table.head>{{ __('Status') }}</flux:table.head>
                <flux:table.head>{{ __('Total') }}</flux:table.head>
                <flux:table.head>{{ __('Balance') }}</flux:table.head>
                <flux:table.head>{{ __('Actions') }}</flux:table.head>
            </x-slot:header>

            <x-slot:body>
                @forelse($bookings as $booking)
                    <flux:table.row wire:key="{{ $booking->id }}">
                        <flux:table.cell>{{ $booking->customer?->name }}</flux:table.cell>
                        <flux:table.cell>Desk {{ $booking->workspace?->desk }}</flux:table.cell>
                        <flux:table.cell>{{ $booking->plan?->type->label() }}</flux:table.cell>
                        <flux:table.cell>{{ $booking->started_at->format('Y-m-d h:i A') }} - {{ $booking->ended_at->format('Y-m-d h:i A') }}</flux:table.cell>
                        <flux:table.cell>
                            <flux:badge color="{{ $booking->status->color() }}">
                                {{ $booking->status->label() }}
                            </flux:badge>
                        </flux:table.cell>
                        <flux:table.cell>{{ number_format($booking->total, 2) }}</flux:table.cell>
                        <flux:table.cell>{{ number_format($booking->balance, 2) }}</flux:table.cell>
                        <flux:table.cell>
                            <div class="flex space-x-2">
                                <flux:button href="{{ tenant_route('bookings.show', $booking) }}" size="sm">
                                    {{ __('View') }}
                                </flux:button>
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="9" class="text-center">
                            {{ __('No bookings found.') }}
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </x-slot:body>
        </flux:table>

        <div class="mt-6">
            @include('flux.pagination', ['paginator' => $bookings])
        </div>
    </div>

    <livewire:bookings.create-booking />
    <livewire:bookings.cancel-booking />

    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('booking-created', () => {
                Livewire.dispatch('refresh');
            });
            Livewire.on('booking-updated', () => {
                Livewire.dispatch('refresh');
            });
            Livewire.on('booking-canceled', () => {
                Livewire.dispatch('refresh');
            });
        });
    </script>
</div>
