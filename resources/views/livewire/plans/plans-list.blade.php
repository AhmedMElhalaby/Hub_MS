@php
    use App\Models\Setting;
@endphp
<div class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <flux:heading>{{ __('Plans Management') }}</flux:heading>
        <flux:button wire:click="create" variant="primary">
            {{ __('Add New Plan') }}
        </flux:button>
    </div>

    <!-- Search and Filter -->
    <div class="flex gap-4 mb-4">
        <div class="flex-1">
            <flux:input wire:model.live="search" type="search" label="{{ __('Search') }}"
                placeholder="Search plans..." />
        </div>
    </div>

    <!-- Plans Table -->
    <div class="overflow-x-auto">
        <flux:table>
            <x-slot:header>
                <flux:table.head>
                    <button wire:click="sortBy('type')" class="flex items-center space-x-1">
                        <span>{{ __('Type') }}</span>
                        @if ($sortField === 'type')
                            <flux:icon name="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}"
                                class="size-4" />
                        @endif
                    </button>
                </flux:table.head>
                <flux:table.head>
                    <button wire:click="sortBy('price')" class="flex items-center space-x-1">
                        <span>{{ __('Price') }}</span>
                        @if ($sortField === 'price')
                            <flux:icon name="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}"
                                class="size-4" />
                        @endif
                    </button>
                </flux:table.head>
                @if(Setting::get('mikrotik_enabled'))
                <flux:table.head>
                    <button wire:click="sortBy('mikrotik_profile')" class="flex items-center space-x-1">
                        <span>{{ __('Mikrotik Profile') }}</span>
                        @if ($sortField === 'mikrotik_profile')
                            <flux:icon name="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}"
                                class="size-4" />
                        @endif
                    </button>
                </flux:table.head>
                @endif
                <flux:table.head>{{ __('Actions') }}</flux:table.head>
            </x-slot:header>

            <x-slot:body>
                @forelse($plans as $plan)
                    <flux:table.row wire:key="{{ $plan->id }}">
                        <flux:table.cell>{{ $plan->type->label() }}</flux:table.cell>
                        <flux:table.cell>{{ number_format($plan->price, 2) }}</flux:table.cell>
                        @if(Setting::get('mikrotik_enabled'))
                            <flux:table.cell>{{ $plan->mikrotik_profile }}</flux:table.cell>
                        @endif
                        <flux:table.cell>
                            <div class="flex space-x-2">
                                <flux:button wire:navigate href="{{ route('plans.show', $plan) }}" size="sm">
                                    {{ __('View') }}
                                </flux:button>
                                <flux:button wire:click="edit({{ $plan->id }})" size="sm">
                                    {{ __('Edit') }}
                                </flux:button>
                                <flux:button wire:click="confirmDelete({{ $plan->id }})" variant="danger"
                                    size="sm">
                                    {{ __('Delete') }}
                                </flux:button>
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="{{ Setting::get('mikrotik_enabled') ? 4 : 3 }}" class="text-center">
                            {{ __('No plans found.') }}
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </x-slot:body>
        </flux:table>

        <!-- Pagination -->
        <div class="mt-6">
            @include('flux.pagination', ['paginator' => $plans])
        </div>
    </div>

    <!-- Create/Edit Modal -->
    <flux:modal wire:model="showModal" variant="flyout">
        <form wire:submit.prevent="save" class="space-y-6">
            <flux:heading size="lg">
                {{ $planId ? __('Edit Plan') : __('Create Plan') }}
            </flux:heading>

            <flux:select
                wire:model="type"
                label="{{ __('Type') }}"
                required
                :error="$errors->first('type')"
            >
                <option value="">{{ __('Select Type') }}</option>
                @foreach($types as $type)
                    <option value="{{ $type->value }}">{{ $type->label() }}</option>
                @endforeach
            </flux:select>

            <flux:input
                wire:model="price"
                label="{{ __('Price') }}"
                type="number"
                step="0.01"
                required
                :error="$errors->first('price')"
            />
            @if(Setting::get('mikrotik_enabled'))
            <flux:select
                wire:model="mikrotik_profile"
                label="{{ __('Mikrotik Profile') }}"
                required
                :error="$errors->first('mikrotik_profile')">
                <option value="">{{ __('Select Profile') }}</option>
                @foreach($availableProfiles as $profile)
                    <option value="{{ $profile }}">{{ $profile }}</option>
                @endforeach
            </flux:select>
            @endif
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
            <flux:heading size="lg">{{ __('Delete Plan') }}</flux:heading>
            <p>{{ __('Are you sure you want to delete this plan?') }}</p>
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
```
