<div class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <flux:heading>{{ __('Workspaces Management') }}</flux:heading>
        <flux:button wire:click="$dispatch('open-create-workspace')" variant="primary">
            {{ __('Add New Workspace') }}
        </flux:button>
    </div>

    <!-- Search and Filter -->
    <div class="flex gap-4 mb-4">
        <div class="flex-1">
            <flux:input wire:model.live="search" type="search" label="{{ __('Search') }}"
                placeholder="Search workspaces..." />
        </div>
        <div class="w-64">
            <flux:select wire:model.live="statusFilter" label="{{ __('Filter by Status') }}">
                <option value="">{{ __('All Statuses') }}</option>
                @foreach($statuses as $status)
                    <option value="{{ $status->value }}">{{ $status->label() }}</option>
                @endforeach
            </flux:select>
        </div>
    </div>

    <!-- Workspaces Table -->
    <div class="overflow-x-auto">
        <flux:table>
            <x-slot:header>
                <flux:table.head>
                    <button wire:click="sortBy('desk')" class="flex items-center space-x-1">
                        <span>{{ __('Desk') }}</span>
                        @if ($sortField === 'desk')
                            <flux:icon name="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}"
                                class="size-4" />
                        @endif
                    </button>
                </flux:table.head>
                <flux:table.head>
                    <button wire:click="sortBy('status')" class="flex items-center space-x-1">
                        <span>{{ __('Status') }}</span>
                        @if ($sortField === 'status')
                            <flux:icon name="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}"
                                class="size-4" />
                        @endif
                    </button>
                </flux:table.head>
                <flux:table.head>{{ __('Actions') }}</flux:table.head>
            </x-slot:header>

            <x-slot:body>
                @forelse($workspaces as $workspace)
                    <flux:table.row wire:key="{{ $workspace->id }}">
                        <flux:table.cell>Desk - {{ $workspace->desk }}</flux:table.cell>
                        <flux:table.cell>
                            <flux:badge variant="solid" :color="$workspace->status->color()">
                                {{ $workspace->status->label() }}
                            </flux:badge>
                        </flux:table.cell>
                        <flux:table.cell>
                            <div class="flex space-x-2">
                                <flux:button wire:navigate href="{{ route('tenant.workspaces.show', $workspace) }}" size="sm">
                                    {{ __('View') }}
                                </flux:button>
                                <flux:button wire:click="$dispatch('open-edit-workspace', { workspaceId: {{ $workspace->id }} })" size="sm">
                                    {{ __('Edit') }}
                                </flux:button>
                                <flux:button wire:click="$dispatch('open-delete-workspace', { workspaceId: {{ $workspace->id }} })" variant="danger" size="sm">
                                    {{ __('Delete') }}
                                </flux:button>
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="3" class="text-center">
                            {{ __('No workspaces found.') }}
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </x-slot:body>
        </flux:table>

        <!-- Pagination -->
        <div class="mt-6">
            @include('flux.pagination', ['paginator' => $workspaces])
        </div>
    </div>

    <livewire:workspaces.create-workspace />
    <livewire:workspaces.edit-workspace />
    <livewire:workspaces.delete-workspace />
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('workspace-created', () => {
                Livewire.dispatch('refresh');
            });
            Livewire.on('workspace-updated', () => {
                Livewire.dispatch('refresh');
            });
            Livewire.on('workspace-deleted', () => {
                Livewire.dispatch('refresh');
            });
        });
    </script>
</div>
