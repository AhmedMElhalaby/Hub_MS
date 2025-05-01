<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <flux:heading>{{ __('Users Management') }}</flux:heading>
        <flux:button wire:click="$dispatch('open-create-user')" variant="primary">
            {{ __('Add New User') }}
        </flux:button>
    </div>
    <div class="flex gap-4 mb-4">
        <div class="flex-1">
            <flux:input wire:model.live="search" type="search" label="{{ __('Search') }}"
                placeholder="Search users..." />
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
                <flux:table.head>{{ __('Actions') }}</flux:table.head>
            </x-slot:header>

            <x-slot:body>
                @forelse($users as $user)
                    <flux:table.row wire:key="{{ $user->id }}">
                        <flux:table.cell>{{ $user->name }}</flux:table.cell>
                        <flux:table.cell>{{ $user->email }}</flux:table.cell>
                        <flux:table.cell>
                            <div class="flex space-x-2">
                                <flux:button wire:navigate href="{{ tenant_route('users.show', $user) }}" size="sm">
                                    {{ __('View') }}
                                </flux:button>
                                <flux:button wire:click="$dispatch('open-edit-user', { userId: {{ $user->id }} })" size="sm">
                                    {{ __('Edit') }}
                                </flux:button>
                                <flux:button wire:click="$dispatch('open-delete-user', { userId: {{ $user->id }} })" variant="danger"
                                    size="sm">
                                    {{ __('Delete') }}
                                </flux:button>
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="4" class="text-center">
                            {{ __('No users found.') }}
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </x-slot:body>
        </flux:table>

        <div class="mt-6">
            @include('flux.pagination', ['paginator' => $users])
        </div>
    </div>

    <livewire:users.delete-user />
    <livewire:users.create-user />
    <livewire:users.edit-user />

    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('user-created', () => {
                Livewire.dispatch('refresh');
            });
            Livewire.on('user-updated', () => {
                Livewire.dispatch('refresh');
            });
            Livewire.on('user-deleted', () => {
                Livewire.dispatch('refresh');
            });
        });
    </script>
</div>
