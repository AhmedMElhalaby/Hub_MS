@php
    use App\Models\Setting;
@endphp
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <flux:heading>{{ __('Plans Management') }}</flux:heading>
        <flux:button wire:click="$dispatch('open-create-plan')" variant="primary">
            {{ __('Add New Plan') }}
        </flux:button>
    </div>

    <div class="flex gap-4 mb-4">
        <div class="flex-1">
            <flux:input wire:model.live="search" type="search" label="{{ __('Search') }}"
                placeholder="Search plans..." />
        </div>
    </div>

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
                                <flux:button wire:navigate href="{{ tenant_route('plans.show', $plan) }}" size="sm">
                                    {{ __('View') }}
                                </flux:button>
                                <flux:button wire:click="$dispatch('open-edit-plan', { planId: {{ $plan->id }} })" size="sm">
                                    {{ __('Edit') }}
                                </flux:button>
                                <flux:button wire:click="$dispatch('open-delete-plan', { planId: {{ $plan->id }} })" variant="danger"
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

        <div class="mt-6">
            @include('flux.pagination', ['paginator' => $plans])
        </div>
    </div>

    <livewire:plans.create-plan />
    <livewire:plans.edit-plan />
    <livewire:plans.delete-plan />

    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('plan-created', () => {
                Livewire.dispatch('refresh');
            });
            Livewire.on('plan-updated', () => {
                Livewire.dispatch('refresh');
            });
            Livewire.on('plan-deleted', () => {
                Livewire.dispatch('refresh');
            });
        });
    </script>
</div>
