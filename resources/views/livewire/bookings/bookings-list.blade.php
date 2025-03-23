<div class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <flux:heading>{{ __('Bookings Management') }}</flux:heading>
        <flux:button wire:click="openModal" variant="primary">
            {{ __('Create Booking') }}
        </flux:button>
    </div>

    <!-- Filters -->
    <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-3">
        <flux:input
            wire:model.live="search"
            type="search"
            placeholder="{{ __('Search bookings...') }}"
        />

        <flux:select wire:model.live="statusFilter">
            <option value="">{{ __('All Statuses') }}</option>
            @foreach(\App\Enums\BookingStatus::cases() as $status)
                <option value="{{ $status->value }}">{{ $status->label() }}</option>
            @endforeach
        </flux:select>

        <flux:select wire:model.live="dateFilter">
            <option value="">{{ __('All Dates') }}</option>
            <option value="today">{{ __('Today') }}</option>
            <option value="week">{{ __('This Week') }}</option>
            <option value="month">{{ __('This Month') }}</option>
        </flux:select>
    </div>

    <!-- Bookings Table -->
    <div class="overflow-x-auto">
        <flux:table>
            <x-slot:header>
                <flux:table.head>
                    <button wire:click="sortBy('customer_id')" class="flex items-center space-x-1">
                        <span>{{ __('Customer') }}</span>
                        @if ($sortField === 'customer_id')
                            <flux:icon name="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}" class="size-4" />
                        @endif
                    </button>
                </flux:table.head>
                <flux:table.head>
                    <button wire:click="sortBy('workspace_id')" class="flex items-center space-x-1">
                        <span>{{ __('Workspace') }}</span>
                        @if ($sortField === 'workspace_id')
                            <flux:icon name="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}" class="size-4" />
                        @endif
                    </button>
                </flux:table.head>
                <flux:table.head>
                    <button wire:click="sortBy('plan_id')" class="flex items-center space-x-1">
                        <span>{{ __('Plan') }}</span>
                        @if ($sortField === 'plan_id')
                            <flux:icon name="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}" class="size-4" />
                        @endif
                    </button>
                </flux:table.head>
                <flux:table.head>
                    <button wire:click="sortBy('started_at')" class="flex items-center space-x-1">
                        <span>{{ __('Period') }}</span>
                        @if ($sortField === 'started_at')
                            <flux:icon name="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}" class="size-4" />
                        @endif
                    </button>
                </flux:table.head>
                <flux:table.head>
                    <button wire:click="sortBy('total')" class="flex items-center space-x-1">
                        <span>{{ __('Total') }}</span>
                        @if ($sortField === 'total')
                            <flux:icon name="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}" class="size-4" />
                        @endif
                    </button>
                </flux:table.head>
                <flux:table.head>
                    <button wire:click="sortBy('balance')" class="flex items-center space-x-1">
                        <span>{{ __('Balance') }}</span>
                        @if ($sortField === 'balance')
                            <flux:icon name="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}" class="size-4" />
                        @endif
                    </button>
                </flux:table.head>
                <flux:table.head>
                    <button wire:click="sortBy('status')" class="flex items-center space-x-1">
                        <span>{{ __('Status') }}</span>
                        @if ($sortField === 'status')
                            <flux:icon name="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}" class="size-4" />
                        @endif
                    </button>
                </flux:table.head>
                <flux:table.head>
                    <button wire:click="sortBy('ended_at')" class="flex items-center space-x-1">
                        <span>{{ __('Time Left') }}</span>
                        @if ($sortField === 'ended_at')
                            <flux:icon name="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}" class="size-4" />
                        @endif
                    </button>
                </flux:table.head>
                <flux:table.head>{{ __('Actions') }}</flux:table.head>
            </x-slot:header>

            <x-slot:body>
                @forelse($bookings as $booking)
                    <flux:table.row
                        wire:key="{{ $booking->id }}"
                        class="cursor-pointer hover:bg-zinc-50 dark:hover:bg-zinc-800/50"
                        onclick="window.location.href='{{ route('bookings.show', $booking) }}'"
                    >
                        <flux:table.cell>{{ $booking->customer->name }}</flux:table.cell>
                        <flux:table.cell>{{ $booking->workspace->desk }}</flux:table.cell>
                        <flux:table.cell>{{ $booking->plan->type->label() }}</flux:table.cell>
                        <flux:table.cell>
                            <div class="text-sm">
                                <div>{{ $booking->started_at->format('M d, Y H:i') }}</div>
                                <div>{{ $booking->ended_at->format('M d, Y H:i') }}</div>
                            </div>
                        </flux:table.cell>
                        <flux:table.cell>{{ number_format($booking->total, 2) }}</flux:table.cell>
                        <flux:table.cell>{{ number_format($booking->balance, 2) }}</flux:table.cell>
                        <flux:table.cell>
                            <flux:badge variant="solid" :color="$booking->status->color()">
                                {{ $booking->status->label() }}
                            </flux:badge>
                        </flux:table.cell>
                        <flux:table.cell>
                            @if($booking->ended_at->isPast())
                                <flux:badge variant="solid" color="danger">{{ __('Expired') }}</flux:badge>
                            @else
                                {{ $booking->ended_at->diffForHumans(['parts' => 2]) }}
                            @endif
                        </flux:table.cell>
                        <flux:table.cell>
                            <div onclick="event.stopPropagation()">
                                <x-booking.actions :booking="$booking" />
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="8" class="text-center">
                            {{ __('No bookings found.') }}
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </x-slot:body>
        </flux:table>

        <!-- Pagination -->
        <div class="mt-6">
            @include('flux.pagination', ['paginator' => $bookings])
        </div>
    </div>
    <livewire:customers.create-customer />

    <!-- Inside the Create/Edit Booking Modal -->
    <flux:modal wire:model="showModal" variant="flyout">
        <form wire:submit.prevent="create" class="space-y-6">
            <flux:heading size="lg">
                {{ $bookingId ? __('Edit Booking') : __('Create Booking') }}
            </flux:heading>

            <div class="flex items-end gap-2">
                <div class="flex-1">
                    <flux:select
                        wire:model="customerId"
                        label="{{ __('Customer') }}"
                        required
                        searchable
                        :error="$errors->first('customerId')"
                        wire:key="customer-select-{{ count($customers) }}"
                    >
                        <option value="">{{ __('Select Customer') }}</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                        @endforeach
                    </flux:select>
                </div>
                <div>
                    <flux:button
                        type="button"
                        wire:click="$dispatch('open-create-customer')"
                        variant="outline"
                    >
                        {{ __('New Customer') }}
                    </flux:button>
                </div>
            </div>


            <flux:select
                wire:model="workspaceId"
                label="{{ __('Workspace') }}"
                required
                searchable
                :error="$errors->first('workspaceId')"
            >
                <option value="">{{ __('Select Workspace') }}</option>
                @foreach($workspaces as $workspace)
                    <option value="{{ $workspace->id }}">{{ $workspace->desk }}</option>
                @endforeach
            </flux:select>

            <flux:select
                wire:model.live="planId"
                label="{{ __('Plan') }}"
                required
                searchable
                :error="$errors->first('planId')"
            >
                <option value="">{{ __('Select Plan') }}</option>
                @foreach($plans as $plan)
                    <option value="{{ $plan->id }}">{{ $plan->type->label() }} - {{ number_format($plan->price, 2) }}</option>
                @endforeach
            </flux:select>

            <div class="grid grid-cols-2 gap-4">
                <flux:input
                    wire:model.live="startedAt"
                    type="datetime-local"
                    label="{{ __('Start Date') }}"
                    required
                    :error="$errors->first('startedAt')"
                    value="{{ $startedAt }}"
                />

                <flux:input
                    wire:model.live="duration"
                    type="number"
                    min="1"
                    label="{{ __('Duration (Times)') }}"
                    required
                    :error="$errors->first('duration')"
                />
            </div>

            @if($endedAt)
                <div class="rounded-lg bg-zinc-50 p-4 dark:bg-zinc-900">
                    <div class="font-medium">{{ __('End Date') }}</div>
                    <div class="text-lg">{{ \Carbon\Carbon::parse($endedAt)->format('M d, Y H:i') }}</div>
                </div>
            @endif

            @if($total)
                <div class="rounded-lg bg-zinc-50 p-4 dark:bg-zinc-900">
                    <div class="font-medium">{{ __('Total Amount') }}</div>
                    <div class="text-2xl font-bold">{{ number_format($total, 2) }}</div>
                </div>
            @endif

            <div class="flex justify-end space-x-2 mt-10">
                <flux:button type="button" wire:click="closeModal" variant="outline">
                    {{ __('Cancel') }}
                </flux:button>
                <flux:button wire:loading.attr="disabled" wire:target="create" type="submit" variant="primary">
                    <span wire:loading.remove wire:target="create">
                        {{ $bookingId ? __('Edit') : __('Create') }}
                    </span>
                    <span wire:loading wire:target="create">
                        {{ $bookingId ? __('Editing ... ') : __('Creating ... ') }}
                    </span>
                </flux:button>
            </div>
        </form>
    </flux:modal>

    <x-booking.modals :selected-booking="$selectedBooking" :plans="$plans" :renewalEndedAt="$renewalEndedAt" />
</div>
