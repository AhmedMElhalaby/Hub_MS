<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <flux:heading>{{ __('Customers Management') }}</flux:heading>
        <flux:button wire:click="$dispatch('open-create-customer')" variant="primary">
            {{ __('Add New Customer') }}
        </flux:button>
    </div>

    <div class="flex gap-4 mb-4">
        <div class="flex-1">
            <flux:input wire:model.live="search" type="search" label="{{ __('Search') }}"
                placeholder="Search customers..." />
        </div>
        <div class="w-64">
            <flux:select wire:model.live="specializationFilter" label="{{ __('Specialization') }}">
                <option value="">{{ __('All Specializations') }}</option>
                @foreach($specializations as $specialization)
                    <option value="{{ $specialization->value }}">{{ $specialization->name }}</option>
                @endforeach
            </flux:select>
        </div>
    </div>

    <div class="overflow-x-auto">
        <flux:table>
            <x-slot:header>
                <flux:table.head>
                    <button wire:click="sortBy('name')" class="flex items-center space-x-1">
                        <span>{{ __('Name') }}</span>
                        @if ($sortField === 'name')
                            <flux:icon name="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}"
                                class="size-4" />
                        @endif
                    </button>
                </flux:table.head>
                <flux:table.head>
                    <button wire:click="sortBy('email')" class="flex items-center space-x-1">
                        <span>{{ __('Email') }}</span>
                        @if ($sortField === 'email')
                            <flux:icon name="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}"
                                class="size-4" />
                        @endif
                    </button>
                </flux:table.head>
                <flux:table.head>
                    <button wire:click="sortBy('mobile')" class="flex items-center space-x-1">
                        <span>{{ __('Mobile') }}</span>
                        @if ($sortField === 'mobile')
                            <flux:icon name="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}"
                                class="size-4" />
                        @endif
                    </button>
                </flux:table.head>
                <flux:table.head>
                    <button wire:click="sortBy('specialization')" class="flex items-center space-x-1">
                        <span>{{ __('Specialization') }}</span>
                        @if ($sortField === 'specialization')
                            <flux:icon name="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}"
                                class="size-4" />
                        @endif
                    </button>
                </flux:table.head>
                <flux:table.head>{{ __('Actions') }}</flux:table.head>
            </x-slot:header>

            <x-slot:body>
                @forelse($customers as $customer)
                    <flux:table.row wire:key="{{ $customer->id }}">
                        <flux:table.cell>{{ $customer->name }}</flux:table.cell>
                        <flux:table.cell>{{ $customer->email }}</flux:table.cell>
                        <flux:table.cell>{{ $customer->mobile }}</flux:table.cell>
                        <flux:table.cell>{{ $customer->specialization->name }}</flux:table.cell>
                        <flux:table.cell>
                            <div class="flex space-x-2">
                                <flux:button wire:navigate href="{{ route('tenant.customers.show', $customer) }}"
                                    size="sm">
                                    {{ __('View') }}
                                </flux:button>
                                <flux:button wire:click="$dispatch('open-edit-customer', { customerId: {{ $customer->id }} })" size="sm">
                                    {{ __('Edit') }}
                                </flux:button>
                                <flux:button wire:click="$dispatch('open-delete-customer', { customerId: {{ $customer->id }} })" variant="danger"
                                    size="sm">
                                    {{ __('Delete') }}
                                </flux:button>
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="5" class="text-center">
                            {{ __('No customers found.') }}
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </x-slot:body>
        </flux:table>

        <div class="mt-6">
            @include('flux.pagination', ['paginator' => $customers])
        </div>
    </div>



    <livewire:customers.delete-customer />
    <livewire:customers.create-customer />
    <livewire:customers.edit-customer />

    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('customer-created', () => {
                Livewire.dispatch('refresh');
            });
            Livewire.on('customer-updated', () => {
                Livewire.dispatch('refresh');
            });
            Livewire.on('customer-deleted', () => {
                Livewire.dispatch('refresh');
            });
        });
    </script>
</div>
