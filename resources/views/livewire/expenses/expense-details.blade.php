<div class="p-6">
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <flux:heading>{{ __('Expense Details') }}</flux:heading>
            <div class="flex space-x-2">
                <flux:button wire:click="$dispatch('open-edit-expense', { expenseId: {{ $expense->id }} })" variant="primary">
                    {{ __('Edit Expense') }}
                </flux:button>
                <flux:button wire:navigate href="{{ route('tenant.expenses.index') }}" variant="outline">
                    {{ __('Back to Expenses') }}
                </flux:button>
            </div>
        </div>
    </div>

    <div class="grid gap-6 md:grid-cols-2">
        <!-- Expense Information -->
        <flux:card>
            <flux:card.header>
                <flux:heading size="sm">{{ __('Expense Information') }}</flux:heading>
            </flux:card.header>
            <flux:card.content class="space-y-4">
                <div>
                    <div class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('Category') }}</div>
                    <div class="font-semibold">{{ $expense->category->label() }}</div>
                </div>
                <div>
                    <div class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('Amount') }}</div>
                    <div class="font-semibold">{{ number_format($expense->amount, 2) }}</div>
                </div>
                <div>
                    <div class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('Date') }}</div>
                    <div>{{ $expense->created_at->format('M d, Y') }}</div>
                </div>
                @if($expense->note)
                    <div>
                        <div class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('Note') }}</div>
                        <div>{{ $expense->note }}</div>
                    </div>
                @endif
            </flux:card.content>
        </flux:card>

        <!-- Finance Records -->
        <flux:card>
            <flux:card.header>
                <flux:heading size="sm">{{ __('Finance Records') }}</flux:heading>
            </flux:card.header>
            <flux:card.content>
                <flux:table>
                    <x-slot:header>
                        <flux:table.head>{{ __('Date') }}</flux:table.head>
                        <flux:table.head>{{ __('Amount') }}</flux:table.head>
                        <flux:table.head>{{ __('Note') }}</flux:table.head>
                    </x-slot:header>
                    <x-slot:body>
                        @forelse($expense->finances as $finance)
                            <flux:table.row>
                                <flux:table.cell>{{ $finance->created_at->format('M d, Y H:i') }}</flux:table.cell>
                                <flux:table.cell>{{ number_format($finance->amount, 2) }}</flux:table.cell>
                                <flux:table.cell>{{ $finance->note }}</flux:table.cell>
                            </flux:table.row>
                        @empty
                            <flux:table.row>
                                <flux:table.cell colspan="3" class="text-center">{{ __('No finance records found.') }}</flux:table.cell>
                            </flux:table.row>
                        @endforelse
                    </x-slot:body>
                </flux:table>
            </flux:card.content>
        </flux:card>
    </div>
    <livewire:expenses.edit-expense />
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('expense-updated', () => {
                Livewire.dispatch('refresh');
            });
        });
    </script>
</div>
