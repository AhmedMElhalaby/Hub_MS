<div class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <flux:heading>{{ __('crud.expenses.labels.management') }}</flux:heading>
        <flux:button wire:click="$dispatch('open-create-expense')" variant="primary">
            {{ __('crud.expenses.actions.create') }}
        </flux:button>
    </div>

    <!-- Search and Filter -->
    <div class="flex gap-4 mb-4">
        <div class="flex-1">
            <flux:input wire:model.live="search" type="search" label="{{ __('crud.common.actions.search') }}"
                placeholder="{{ __('crud.expenses.labels.search') }}" />
        </div>
        <div class="w-64">
            <flux:select wire:model.live="categoryFilter" label="{{ __('crud.expenses.labels.filter_by_category') }}">
                <option value="">{{ __('crud.expenses.labels.all_categories') }}</option>
                @foreach($categories as $category)
                    <option value="{{ $category->value }}">{{ $category->label() }}</option>
                @endforeach
            </flux:select>
        </div>
    </div>

    <!-- Expenses Table -->
    <div class="overflow-x-auto">
        <flux:table>
            <x-slot:header>
                <flux:table.head>
                    <button wire:click="sortBy('category')" class="flex items-center space-x-1">
                        <span>{{ __('crud.expenses.fields.category') }}</span>
                        @if ($sortField === 'category')
                            <flux:icon name="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}"
                                class="size-4" />
                        @endif
                    </button>
                </flux:table.head>
                <flux:table.head>
                    <button wire:click="sortBy('amount')" class="flex items-center space-x-1">
                        <span>{{ __('crud.expenses.fields.amount') }}</span>
                        @if ($sortField === 'amount')
                            <flux:icon name="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}"
                                class="size-4" />
                        @endif
                    </button>
                </flux:table.head>
                <flux:table.head>
                    <button wire:click="sortBy('created_at')" class="flex items-center space-x-1">
                        <span>{{ __('crud.common.fields.created_at') }}</span>
                        @if ($sortField === 'created_at')
                            <flux:icon name="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}"
                                class="size-4" />
                        @endif
                    </button>
                </flux:table.head>
                <flux:table.head>{{ __('crud.common.fields.actions') }}</flux:table.head>
            </x-slot:header>

            <x-slot:body>
                @forelse($expenses as $expense)
                    <flux:table.row wire:key="{{ $expense->id }}">
                        <flux:table.cell>{{ $expense->category->label() }}</flux:table.cell>
                        <flux:table.cell>{{ number_format($expense->amount, 2) }}</flux:table.cell>
                        <flux:table.cell>{{ $expense->created_at->format('M d, Y H:i') }}</flux:table.cell>
                        <flux:table.cell>
                            <div class="flex space-x-2">
                                <flux:button wire:navigate href="{{ route('tenant.expenses.show', $expense) }}" size="sm">
                                    {{ __('crud.common.actions.view') }}
                                </flux:button>
                                <flux:button wire:click="$dispatch('open-edit-expense', { expenseId: {{ $expense->id }} })" size="sm">
                                    {{ __('crud.common.actions.edit') }}
                                </flux:button>
                                <flux:button wire:click="$dispatch('open-delete-expense', { expenseId: {{ $expense->id }} })" variant="danger" size="sm">
                                    {{ __('crud.common.actions.delete') }}
                                </flux:button>
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="4" class="text-center">
                            {{ __('crud.expenses.labels.no_expenses') }}
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </x-slot:body>
        </flux:table>

        <div class="mt-6">
            @include('flux.pagination', ['paginator' => $expenses])
        </div>
    </div>

    <livewire:expenses.create-expense />
    <livewire:expenses.edit-expense />
    <livewire:expenses.delete-expense />
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('expense-created', () => {
                Livewire.dispatch('refresh');
            });
            Livewire.on('expense-updated', () => {
                Livewire.dispatch('refresh');
            });
            Livewire.on('expense-deleted', () => {
                Livewire.dispatch('refresh');
            });
        });
    </script>
</div>
