<div class="p-6">
    <!-- Header -->
    <div class="mb-6 flex justify-between items-center">
        <flux:heading>{{ __('crud.finances.labels.management') }}</flux:heading>
        <flux:button wire:click="$set('showExportModal', true)" variant="primary">
            <div class="flex items-center">
                <flux:icon name="document-arrow-down" class="w-4 h-4 mr-2" />
                {{ __('crud.finances.actions.export') }}
            </div>
        </flux:button>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 gap-4 mb-6 md:grid-cols-4">
        <x-stat-card
            title="{{ __('crud.finances.labels.total_income') }}"
            value="{{ number_format($statistics['total_income'], 2) }}"
            description="{{ __('crud.finances.labels.all_time_income') }}"
            trend="up"
            color="success"
        />
        <x-stat-card
            title="{{ __('crud.finances.labels.total_expected_payment') }}"
            value="{{ number_format($statistics['total_expected_payment'], 2) }}"
            description="{{ __('crud.finances.labels.active_booking_income') }}"
            trend="up"
            color="success"
        />
        <x-stat-card
            title="{{ __('crud.finances.labels.total_expense') }}"
            value="{{ number_format($statistics['total_expense'], 2) }}"
            description="{{ __('crud.finances.labels.all_time_expenses') }}"
            trend="down"
            color="danger"
        />
        <x-stat-card
            title="{{ __('crud.finances.labels.net_amount') }}"
            value="{{ number_format($statistics['net_amount'], 2) }}"
            description="{{ __('crud.finances.labels.total_profit_loss') }}"
            :trend="$statistics['net_amount'] >= 0 ? 'up' : 'down'"
            :color="$statistics['net_amount'] >= 0 ? 'success' : 'danger'"
        />
    </div>

    <!-- Filters -->
    <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-5">
        <flux:input
            wire:model.live="search"
            type="search"
            placeholder="{{ __('crud.finances.labels.search_finances') }}"
        />

        <flux:select wire:model.live="typeFilter">
            <option value="">{{ __('crud.finances.labels.all_types') }}</option>
            @foreach(\App\Enums\FinanceType::cases() as $type)
                <option value="{{ $type->value }}">{{ $type->label() }}</option>
            @endforeach
        </flux:select>

        <flux:select wire:model.live="paymentMethodFilter">
            <option value="">{{ __('crud.finances.labels.all_payment_methods') }}</option>
            @foreach(\App\Enums\PaymentMethod::cases() as $method)
                <option value="{{ $method->value }}">{{ $method->label() }}</option>
            @endforeach
        </flux:select>

        <flux:select wire:model.live="statusFilter">
            <option value="">{{ __('crud.finances.labels.all_statuses') }}</option>
            <option value="active">{{ __('crud.finances.labels.active') }}</option>
            <option value="voided">{{ __('crud.finances.labels.voided') }}</option>
        </flux:select>

        <flux:select wire:model.live="dateFilter">
            <option value="">{{ __('crud.finances.labels.all_dates') }}</option>
            <option value="today">{{ __('crud.finances.labels.today') }}</option>
            <option value="week">{{ __('crud.finances.labels.this_week') }}</option>
            <option value="month">{{ __('crud.finances.labels.this_month') }}</option>
            <option value="last_month">{{ __('crud.finances.labels.last_month') }}</option>
        </flux:select>
    </div>

    <!-- Finances Table -->
    <div class="overflow-x-auto">
        <flux:table>
            <x-slot:header>
                <flux:table.head>
                    <button wire:click="sortBy('type')" class="flex items-center space-x-1">
                        <span>{{ __('crud.finances.fields.type') }}</span>
                        @if ($sortField === 'type')
                            <flux:icon name="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}" class="size-4" />
                        @endif
                    </button>
                </flux:table.head>
                <flux:table.head>{{ __('crud.finances.fields.reference') }}</flux:table.head>
                <flux:table.head>
                    <button wire:click="sortBy('amount')" class="flex items-center space-x-1">
                        <span>{{ __('crud.finances.fields.amount') }}</span>
                        @if ($sortField === 'amount')
                            <flux:icon name="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}" class="size-4" />
                        @endif
                    </button>
                </flux:table.head>
                <flux:table.head>{{ __('crud.finances.fields.note') }}</flux:table.head>
                <flux:table.head>
                    <button wire:click="sortBy('created_at')" class="flex items-center space-x-1">
                        <span>{{ __('crud.common.fields.date') }}</span>
                        @if ($sortField === 'created_at')
                            <flux:icon name="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}" class="size-4" />
                        @endif
                    </button>
                </flux:table.head>
                <flux:table.head>{{ __('crud.finances.fields.payment_method') }}</flux:table.head>
                <flux:table.head>{{ __('crud.finances.fields.status') }}</flux:table.head>
                <flux:table.head>{{ __('crud.common.fields.actions') }}</flux:table.head>
            </x-slot:header>

            <x-slot:body>
                @forelse($finances as $finance)
                    <flux:table.row wire:key="{{ $finance->id }}">
                        <flux:table.cell>
                            @if($finance->booking)
                                <a href="{{ route('tenant.bookings.show', $finance->booking) }}" class="block">
                                    <flux:badge variant="solid" :color="$finance->type === \App\Enums\FinanceType::Income ? 'success' : 'danger'">
                                        {{ $finance->type->label() }}
                                    </flux:badge>
                                </a>
                            @elseif($finance->expense)
                                <a href="{{ route('tenant.expenses.show', $finance->expense) }}" class="block">
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
                                <a href="{{ route('tenant.bookings.show', $finance->booking) }}" class="text-primary-600 hover:underline">
                                    {{ $finance->booking->customer->name }}
                                </a>
                            @elseif($finance->expense)
                                <a href="{{ route('tenant.expenses.show', $finance->expense) }}" class="text-primary-600 hover:underline">
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
                                    {{ __('crud.finances.labels.voided') }}
                                </flux:badge>
                            @else
                                <flux:badge variant="solid" color="emerald">
                                    {{ __('crud.finances.labels.active') }}
                                </flux:badge>
                            @endif
                        </flux:table.cell>
                        <flux:table.cell>
                            <div class="flex space-x-2">
                                @if(!str_contains($finance->note ?? '', 'Voided:'))
                                    <flux:button wire:click="voidPayment({{ $finance->id }})" variant="danger" size="sm">
                                        {{ __('crud.finances.actions.void') }}
                                    </flux:button>
                                @endif
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="6" class="text-center">
                            {{ __('crud.finances.messages.no_finances_found') }}
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
            <flux:heading size="lg">{{ __('crud.finances.actions.void_payment') }}</flux:heading>

            <p>{{ __('crud.finances.messages.void_confirm') }}</p>

            <div class="flex justify-end space-x-2">
                <flux:button type="button" wire:click="$set('showVoidModal', false)" variant="outline">
                    {{ __('crud.common.actions.cancel') }}
                </flux:button>
                <flux:button wire:click="confirmVoid" variant="danger">
                    {{ __('crud.finances.actions.void_payment') }}
                </flux:button>
            </div>
        </div>
    </flux:modal>

    <!-- Export Modal -->
    <flux:modal wire:model="showExportModal" variant="flyout">
        <div class="space-y-6">
            <flux:heading size="lg">{{ __('crud.finances.actions.export_finances') }}</flux:heading>

            <div class="space-y-4">
                <p>{{ __('crud.finances.labels.select_columns') }}</p>

                <div class="grid grid-cols-2 gap-4">
                    <label class="flex items-center space-x-2">
                        <flux:checkbox wire:model="selectedColumns" value="type" />
                        <span>{{ __('crud.finances.fields.type') }}</span>
                    </label>
                    <label class="flex items-center space-x-2">
                        <flux:checkbox wire:model="selectedColumns" value="reference" />
                        <span>{{ __('crud.finances.fields.reference') }}</span>
                    </label>
                    <label class="flex items-center space-x-2">
                        <flux:checkbox wire:model="selectedColumns" value="amount" />
                        <span>{{ __('crud.finances.fields.amount') }}</span>
                    </label>
                    <label class="flex items-center space-x-2">
                        <flux:checkbox wire:model="selectedColumns" value="note" />
                        <span>{{ __('crud.finances.fields.note') }}</span>
                    </label>
                    <label class="flex items-center space-x-2">
                        <flux:checkbox wire:model="selectedColumns" value="date" />
                        <span>{{ __('crud.common.fields.date') }}</span>
                    </label>
                    <label class="flex items-center space-x-2">
                        <flux:checkbox wire:model="selectedColumns" value="payment_method" />
                        <span>{{ __('crud.finances.fields.payment_method') }}</span>
                    </label>
                    <label class="flex items-center space-x-2">
                        <flux:checkbox wire:model="selectedColumns" value="status" />
                        <span>{{ __('crud.finances.fields.status') }}</span>
                    </label>
                </div>
            </div>

            <div class="flex justify-end space-x-2">
                <flux:button type="button" wire:click="$set('showExportModal', false)" variant="outline">
                    {{ __('crud.common.actions.cancel') }}
                </flux:button>
                <flux:button wire:click="exportSelected" variant="primary">
                    {{ __('crud.finances.actions.export') }}
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>
