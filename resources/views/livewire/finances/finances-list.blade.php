<div class="p-6">
    <!-- Header -->
    <div class="mb-6 flex justify-between items-center">
        <flux:heading>{{ __('Finance Management') }}</flux:heading>
        <flux:button wire:click="$set('showExportModal', true)" variant="primary">
            <div class="flex items-center">
                <flux:icon name="document-arrow-down" class="w-4 h-4 mr-2" />
                {{ __('Export') }}
            </div>
        </flux:button>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 gap-4 mb-6 md:grid-cols-4">
        <x-stat-card
            title="{{ __('Total Income') }}"
            value="{{ number_format($statistics['total_income'], 2) }}"
            description="{{ __('All time income') }}"
            trend="up"
            color="success"
        />
        <x-stat-card
            title="{{ __('Total Expected Income') }}"
            value="{{ number_format($statistics['total_expected_payment'], 2) }}"
            description="{{ __('Active booking income') }}"
            trend="up"
            color="success"
        />
        <x-stat-card
            title="{{ __('Total Expenses') }}"
            value="{{ number_format($statistics['total_expense'], 2) }}"
            description="{{ __('All time expenses') }}"
            trend="down"
            color="danger"
        />
        <x-stat-card
            title="{{ __('Net Amount') }}"
            value="{{ number_format($statistics['net_amount'], 2) }}"
            description="{{ __('Total profit/loss') }}"
            :trend="$statistics['net_amount'] >= 0 ? 'up' : 'down'"
            :color="$statistics['net_amount'] >= 0 ? 'success' : 'danger'"
        />
    </div>

    <!-- Filters -->
    <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-5">
        <flux:input
            wire:model.live="search"
            type="search"
            placeholder="{{ __('Search finances...') }}"
        />

        <flux:select wire:model.live="typeFilter">
            <option value="">{{ __('All Types') }}</option>
            @foreach(\App\Enums\FinanceType::cases() as $type)
                <option value="{{ $type->value }}">{{ $type->label() }}</option>
            @endforeach
        </flux:select>

        <flux:select wire:model.live="paymentMethodFilter">
            <option value="">{{ __('All Payment Methods') }}</option>
            @foreach(\App\Enums\PaymentMethod::cases() as $method)
                <option value="{{ $method->value }}">{{ $method->label() }}</option>
            @endforeach
        </flux:select>

        <flux:select wire:model.live="statusFilter">
            <option value="">{{ __('All Statuses') }}</option>
            <option value="active">{{ __('Active') }}</option>
            <option value="voided">{{ __('Voided') }}</option>
        </flux:select>

        <flux:select wire:model.live="dateFilter">
            <option value="">{{ __('All Dates') }}</option>
            <option value="today">{{ __('Today') }}</option>
            <option value="week">{{ __('This Week') }}</option>
            <option value="month">{{ __('This Month') }}</option>
            <option value="last_month">{{ __('Last Month') }}</option>
        </flux:select>
    </div>

    <!-- Finances Table -->
    <div class="overflow-x-auto">
        <flux:table>
            <x-slot:header>
                <flux:table.head>
                    <button wire:click="sortBy('type')" class="flex items-center space-x-1">
                        <span>{{ __('Type') }}</span>
                        @if ($sortField === 'type')
                            <flux:icon name="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}" class="size-4" />
                        @endif
                    </button>
                </flux:table.head>
                <flux:table.head>{{ __('Reference') }}</flux:table.head>
                <flux:table.head>
                    <button wire:click="sortBy('amount')" class="flex items-center space-x-1">
                        <span>{{ __('Amount') }}</span>
                        @if ($sortField === 'amount')
                            <flux:icon name="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}" class="size-4" />
                        @endif
                    </button>
                </flux:table.head>
                <flux:table.head>{{ __('Note') }}</flux:table.head>
                <flux:table.head>
                    <button wire:click="sortBy('created_at')" class="flex items-center space-x-1">
                        <span>{{ __('Date') }}</span>
                        @if ($sortField === 'created_at')
                            <flux:icon name="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}" class="size-4" />
                        @endif
                    </button>
                </flux:table.head>
                <flux:table.head>{{ __('Payment Method') }}</flux:table.head>
                <flux:table.head>{{ __('Status') }}</flux:table.head>
                <flux:table.head>{{ __('Actions') }}</flux:table.head>
            </x-slot:header>

            <x-slot:body>
                @forelse($finances as $finance)
                    <flux:table.row wire:key="{{ $finance->id }}">
                        <flux:table.cell>
                            @if($finance->booking)
                                <a href="{{ tenant_route('bookings.show', $finance->booking) }}" class="block">
                                    <flux:badge variant="solid" :color="$finance->type === \App\Enums\FinanceType::Income ? 'success' : 'danger'">
                                        {{ $finance->type->label() }}
                                    </flux:badge>
                                </a>
                            @elseif($finance->expense)
                                <a href="{{ tenant_route('expenses.show', $finance->expense) }}" class="block">
                                    <flux:badge variant="solid" :color="$finance->type === \App\Enums\FinanceType::Income ? 'success' : 'danger'">
                                        {{ $finance->type->label() }}
                                    </flux:badge>
                                </a>
                            @else
                                <flux:badge variant="solid" :color="$finance->type === \App\Enums\FinanceType::Income ? 'success' : 'danger'">
                                    {{ $finance->type->label() }}
                                </flux:badge>
                            @endif
                        </flux:table.cell>
                        <flux:table.cell>
                            @if($finance->booking)
                                <a href="{{ tenant_route('bookings.show', $finance->booking) }}" class="text-primary-600 hover:underline">
                                    {{ $finance->booking->customer->name }}
                                </a>
                            @elseif($finance->expense)
                                <a href="{{ tenant_route('expenses.show', $finance->expense) }}" class="text-primary-600 hover:underline">
                                    {{ $finance->expense->title }}
                                </a>
                            @endif
                        </flux:table.cell>
                        <flux:table.cell>{{ number_format($finance->amount, 2) }}</flux:table.cell>
                        <flux:table.cell>{{ $finance->note }}</flux:table.cell>
                        <flux:table.cell>{{ $finance->created_at->format('M d, Y H:i') }}</flux:table.cell>
                        <flux:table.cell>{{ $finance->payment_method->label() }}</flux:table.cell>
                        <flux:table.cell>
                            @if(str_contains($finance->note ?? '', 'Voided:'))
                                <flux:badge variant="solid" color="red">
                                    {{ __('Voided') }}
                                </flux:badge>
                            @else
                                <flux:badge variant="solid" color="emerald">
                                    {{ __('Active') }}
                                </flux:badge>
                            @endif
                        </flux:table.cell>
                        <flux:table.cell>
                            <div class="flex space-x-2">
                                @if(!str_contains($finance->note ?? '', 'Voided:'))
                                    <flux:button wire:click="voidPayment({{ $finance->id }})" variant="danger" size="sm">
                                        {{ __('Void') }}
                                    </flux:button>
                                @endif
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="6" class="text-center">
                            {{ __('No finances found.') }}
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </x-slot:body>
        </flux:table>

        <!-- Pagination -->
        <div class="mt-6">
            @include('flux.pagination', ['paginator' => $finances])
        </div>
    </div>

    <!-- Void Modal -->
    <flux:modal wire:model="showVoidModal">
        <div class="space-y-6">
            <flux:heading size="lg">{{ __('Void Payment') }}</flux:heading>

            <p>{{ __('Are you sure you want to void this payment? This action cannot be undone.') }}</p>

            <div class="flex justify-end space-x-2">
                <flux:button type="button" wire:click="$set('showVoidModal', false)" variant="outline">
                    {{ __('Cancel') }}
                </flux:button>
                <flux:button wire:click="confirmVoid" variant="danger">
                    {{ __('Void Payment') }}
                </flux:button>
            </div>
        </div>
    </flux:modal>

    <!-- Export Modal -->
    <flux:modal wire:model="showExportModal" variant="flyout">
        <div class="space-y-6">
            <flux:heading size="lg">{{ __('Export Finances') }}</flux:heading>

            <div class="space-y-4">
                <p>{{ __('Select columns to export:') }}</p>

                <div class="grid grid-cols-2 gap-4">
                    <label class="flex items-center space-x-2">
                        <flux:checkbox wire:model="selectedColumns" value="type" />
                        <span>{{ __('Type') }}</span>
                    </label>
                    <label class="flex items-center space-x-2">
                        <flux:checkbox wire:model="selectedColumns" value="reference" />
                        <span>{{ __('Reference') }}</span>
                    </label>
                    <label class="flex items-center space-x-2">
                        <flux:checkbox wire:model="selectedColumns" value="amount" />
                        <span>{{ __('Amount') }}</span>
                    </label>
                    <label class="flex items-center space-x-2">
                        <flux:checkbox wire:model="selectedColumns" value="note" />
                        <span>{{ __('Note') }}</span>
                    </label>
                    <label class="flex items-center space-x-2">
                        <flux:checkbox wire:model="selectedColumns" value="date" />
                        <span>{{ __('Date') }}</span>
                    </label>
                    <label class="flex items-center space-x-2">
                        <flux:checkbox wire:model="selectedColumns" value="payment_method" />
                        <span>{{ __('Payment Method') }}</span>
                    </label>
                    <label class="flex items-center space-x-2">
                        <flux:checkbox wire:model="selectedColumns" value="status" />
                        <span>{{ __('Status') }}</span>
                    </label>
                </div>
            </div>

            <div class="flex justify-end space-x-2">
                <flux:button type="button" wire:click="$set('showExportModal', false)" variant="outline">
                    {{ __('Cancel') }}
                </flux:button>
                <flux:button wire:click="exportSelected" variant="primary">
                    {{ __('Export') }}
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>
