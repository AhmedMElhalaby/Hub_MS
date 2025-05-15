<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <flux:heading>{{ __('crud.users.labels.management') }}</flux:heading>
        <flux:button wire:click="$dispatch('open-create-user')" variant="primary">
            {{ __('crud.users.actions.create') }}
        </flux:button>
    </div>
    <div class="flex gap-4 mb-4">
        <div class="flex-1">
            <flux:input wire:model.live="search" type="search" label="{{ __('crud.common.actions.search') }}"
                placeholder="{{ __('crud.users.labels.search') }}" />
        </div>
    </div>
    <div class="overflow-x-auto">
        <flux:table>
            <x-slot:header>
                <flux:table.head>
                    <button wire:click="sortBy('name')" class="flex items-center space-x-1">
                        <span>{{ __('crud.users.fields.name') }}</span>
                        @if ($sortField === 'name')
                            <flux:icon name="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}"
                                class="size-4" />
                        @endif
                    </button>
                </flux:table.head>
                <flux:table.head>
                    <button wire:click="sortBy('email')" class="flex items-center space-x-1">
                        <span>{{ __('crud.users.fields.email') }}</span>
                        @if ($sortField === 'email')
                            <flux:icon name="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}"
                                class="size-4" />
                        @endif
                    </button>
                </flux:table.head>
                <flux:table.head>{{ __('crud.common.fields.actions') }}</flux:table.head>
            </x-slot:header>

            <x-slot:body>
                @forelse($users as $user)
                    <flux:table.row wire:key="{{ $user->id }}">
                        <flux:table.cell>{{ $user->name }}</flux:table.cell>
                        <flux:table.cell>{{ $user->email }}</flux:table.cell>
                        <flux:table.cell>
                            <div class="flex space-x-2">
                                <flux:button wire:navigate href="{{ route('tenant.users.show', $user) }}" size="sm">
                                    {{ __('crud.common.actions.view') }}
                                </flux:button>
                                <flux:button wire:click="$dispatch('open-edit-user', { userId: {{ $user->id }} })" size="sm">
                                    {{ __('crud.common.actions.edit') }}
                                </flux:button>
                                <flux:button wire:click="$dispatch('open-delete-user', { userId: {{ $user->id }} })" variant="danger"
                                    size="sm">
                                    {{ __('crud.common.actions.delete') }}
                                </flux:button>
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="3" class="text-center">
                            {{ __('crud.common.messages.no_records', ['model' => __('crud.users.model.plural')]) }}
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
