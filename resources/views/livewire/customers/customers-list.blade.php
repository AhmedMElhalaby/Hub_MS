<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <flux:heading>{{ __('crud.customers.labels.management') }}</flux:heading>
        <flux:button wire:click="$dispatch('open-create-customer')" variant="primary">
            {{ __('crud.customers.actions.create') }}
        </flux:button>
    </div>

    <div class="flex gap-4 mb-4">
        <div class="flex-1">
            <flux:input wire:model.live="search" type="search" label="{{ __('crud.common.actions.search') }}"
                placeholder="{{ __('crud.customers.labels.search') }}" />
        </div>
        <div class="w-64">
            <flux:select wire:model.live="specializationFilter" label="{{ __('crud.customers.fields.specialization') }}">
                <option value="">{{ __('crud.customers.labels.specialization') }}</option>
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
                        <span>{{ __('crud.customers.fields.name') }}</span>
                        @if ($sortField === 'name')
                            <flux:icon name="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}"
                                class="size-4" />
                        @endif
                    </button>
                </flux:table.head>
                <flux:table.head>
                    <button wire:click="sortBy('email')" class="flex items-center space-x-1">
                        <span>{{ __('crud.customers.fields.email') }}</span>
                        @if ($sortField === 'email')
                            <flux:icon name="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}"
                                class="size-4" />
                        @endif
                    </button>
                </flux:table.head>
                <flux:table.head>
                    <button wire:click="sortBy('mobile')" class="flex items-center space-x-1">
                        <span>{{ __('crud.customers.fields.mobile') }}</span>
                        @if ($sortField === 'mobile')
                            <flux:icon name="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}"
                                class="size-4" />
                        @endif
                    </button>
                </flux:table.head>
                <flux:table.head>
                    <button wire:click="sortBy('specialization')" class="flex items-center space-x-1">
                        <span>{{ __('crud.customers.fields.specialization') }}</span>
                        @if ($sortField === 'specialization')
                            <flux:icon name="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}"
                                class="size-4" />
                        @endif
                    </button>
                </flux:table.head>
                <flux:table.head>{{ __('crud.common.fields.actions') }}</flux:table.head>
            </x-slot:header>

            <x-slot:body>
                @forelse($customers as $customer)
                    <flux:table.row wire:key="{{ $customer->id }}">
                        <flux:table.cell>{{ $customer->name }}</flux:table.cell>
                        <flux:table.cell>{{ $customer->email }}</flux:table.cell>
                        <flux:table.cell>{{ $customer->mobile }}</flux:table.cell>
                        <flux:table.cell>{{ $customer->specialization->label() }}</flux:table.cell>
                        <flux:table.cell>
                            <div class="flex space-x-2">
                                <flux:button wire:navigate href="{{ route('tenant.customers.show', $customer) }}"
                                    size="sm">
                                    {{ __('crud.common.actions.view') }}
                                </flux:button>
                                <flux:button wire:click="$dispatch('open-edit-customer', { customerId: {{ $customer->id }} })" size="sm">
                                    {{ __('crud.common.actions.edit') }}
                                </flux:button>
                                <flux:button wire:click="$dispatch('open-delete-customer', { customerId: {{ $customer->id }} })" variant="danger"
                                    size="sm">
                                    {{ __('crud.common.actions.delete') }}
                                </flux:button>
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="5" class="text-center">
                            {{ __('crud.common.messages.no_records', ['model' => __('customers')]) }}
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
