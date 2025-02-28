<div class="p-6">

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <flux:heading>{{ __('Customers Management') }}</flux:heading>
        <flux:button wire:click="create" variant="primary">
            {{ __('Add New Customer') }}
        </flux:button>
    </div>

    <!-- Search and Filter -->
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

    <!-- Customers Table -->
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
                                <flux:button wire:navigate href="{{ route('customers.show', $customer) }}"
                                    size="sm">
                                    {{ __('View') }}
                                </flux:button>
                                <flux:button wire:click="edit({{ $customer->id }})" size="sm">
                                    {{ __('Edit') }}
                                </flux:button>
                                <flux:button wire:click="confirmDelete({{ $customer->id }})" variant="danger"
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

        <!-- Pagination -->
        <div class="mt-6">
            @include('flux.pagination', ['paginator' => $customers])
        </div>
    </div>

    <!-- Create/Edit Modal -->
    <flux:modal wire:model="showModal" variant="flyout">
        <form wire:submit.prevent="save" class="space-y-6">
            <flux:heading size="lg">
                {{ $customerId ? __('Edit Customer') : __('Create Customer') }}
            </flux:heading>

            <flux:input
                wire:model.live="name"
                label="{{ __('Name') }}"
                required
                :error="$errors->first('name')"
            />
            <flux:input
                wire:model.live="email"
                label="{{ __('Email') }}"
                type="email"
                required
                :error="$errors->first('email')"
            />
            <flux:input
                wire:model.live="mobile"
                label="{{ __('Mobile') }}"
                :error="$errors->first('mobile')"
            />
            <flux:input
                wire:model.live="address"
                label="{{ __('Address') }}"
                :error="$errors->first('address')"
            />
            <flux:select
                wire:model.live="specialization"
                label="{{ __('Specialization') }}"
                required
                :error="$errors->first('specialization')"
            >
                <option value="">{{ __('Select Specialization') }}</option>
                @foreach($specializations as $specialization)
                    <option value="{{ $specialization->value }}">{{ $specialization->name }}</option>
                @endforeach
            </flux:select>

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
            <flux:heading size="lg">{{ __('Delete Customer') }}</flux:heading>
            <p>{{ __('Are you sure you want to delete this customer?') }}</p>
            <div class="flex justify-end space-x-2 mt-3">
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
