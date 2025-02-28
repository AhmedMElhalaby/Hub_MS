<div class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <flux:heading>{{ __('Expenses Management') }}</flux:heading>
        <flux:button wire:click="create" variant="primary">
            {{ __('Add New Expense') }}
        </flux:button>
    </div>

    <!-- Search and Filter -->
    <div class="flex gap-4 mb-4">
        <div class="flex-1">
            <flux:input wire:model.live="search" type="search" label="{{ __('Search') }}"
                placeholder="Search expenses..." />
        </div>
    </div>

    <!-- Expenses Table -->
    <div class="overflow-x-auto">
        <flux:table>
            <x-slot:header>
                <flux:table.head>
                    <button wire:click="sortBy('category')" class="flex items-center space-x-1">
                        <span>{{ __('Category') }}</span>
                        @if ($sortField === 'category')
                            <flux:icon name="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}"
                                class="size-4" />
                        @endif
                    </button>
                </flux:table.head>
                <flux:table.head>
                    <button wire:click="sortBy('amount')" class="flex items-center space-x-1">
                        <span>{{ __('Amount') }}</span>
                        @if ($sortField === 'amount')
                            <flux:icon name="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}"
                                class="size-4" />
                        @endif
                    </button>
                </flux:table.head>
                <flux:table.head>{{ __('Actions') }}</flux:table.head>
            </x-slot:header>

            <x-slot:body>
                @forelse($expenses as $expense)
                    <flux:table.row wire:key="{{ $expense->id }}">
                        <flux:table.cell>{{ $expense->category->label() }}</flux:table.cell>
                        <flux:table.cell>{{ number_format($expense->amount, 2) }}</flux:table.cell>
                        <flux:table.cell>
                            <div class="flex space-x-2">
                                <flux:button wire:navigate href="{{ route('expenses.show', $expense) }}" size="sm">
                                    {{ __('View') }}
                                </flux:button>
                                <flux:button wire:click="edit({{ $expense->id }})" size="sm">
                                    {{ __('Edit') }}
                                </flux:button>
                                <flux:button wire:click="confirmDelete({{ $expense->id }})" variant="danger"
                                    size="sm">
                                    {{ __('Delete') }}
                                </flux:button>
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="3" class="text-center">
                            {{ __('No expenses found.') }}
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </x-slot:body>
        </flux:table>

        <!-- Pagination -->
        <div class="mt-6">
            @include('flux.pagination', ['paginator' => $expenses])
        </div>
    </div>

    <!-- Create/Edit Modal -->
    <flux:modal wire:model="showModal" variant="flyout">
        <form wire:submit.prevent="save" class="space-y-6">
            <flux:heading size="lg">
                {{ $expenseId ? __('Edit Expense') : __('Create Expense') }}
            </flux:heading>

            <flux:select
                wire:model="category"
                label="{{ __('Category') }}"
                required
                :error="$errors->first('category')"
            >
                <option value="">{{ __('Select Category') }}</option>
                @foreach($categories as $category)
                    <option value="{{ $category->value }}">{{ $category->label() }}</option>
                @endforeach
            </flux:select>

            <flux:input
                wire:model="amount"
                label="{{ __('Amount') }}"
                type="number"
                step="0.01"
                required
                :error="$errors->first('amount')"
            />

            <div class="flex justify-end space-x-2 mt-10">
                <flux:button type="button" wire:click="resetForm" variant="outline">
                    {{ __('Cancel') }}
                </flux:button>
                <flux:button wire:loading.attr="disabled" wire:target="save" type="submit"
                    variant="primary">
                    <span wire:loading.remove wire:target="save">{{ __('Save') }}</span>
                    <span wire:loading wire:target="save">{{ __('Saving...') }}</span>
                </flux:button>
            </div>
        </form>
    </flux:modal>

    <!-- Delete Confirmation Modal -->
    <flux:modal wire:model="showDeleteModal">
        <div class="space-y-6">
            <flux:heading size="lg">{{ __('Delete Expense') }}</flux:heading>
            <p>{{ __('Are you sure you want to delete this expense?') }}</p>
            <div class="flex justify-end space-x-2">
                <flux:button wire:click="resetForm" variant="outline">
                    {{ __('Cancel') }}
                </flux:button>
                <flux:button wire:click="delete" variant="danger">
                    {{ __('Delete') }}
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>
