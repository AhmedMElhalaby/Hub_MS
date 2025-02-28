<div class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <flux:heading>{{ __('Workspaces Management') }}</flux:heading>
        <flux:button wire:click="create" variant="primary">
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
                        <flux:table.cell>{{ $workspace->desk }}</flux:table.cell>
                        <flux:table.cell>
                            <flux:badge variant="solid" :color="$workspace->status->color()">
                                {{ $workspace->status->label() }}
                            </flux:badge>
                        </flux:table.cell>
                        <flux:table.cell>
                            <div class="flex space-x-2">
                                <flux:button wire:navigate href="{{ route('workspaces.show', $workspace) }}" size="sm">
                                    {{ __('View') }}
                                </flux:button>
                                <flux:button wire:click="edit({{ $workspace->id }})" size="sm">
                                    {{ __('Edit') }}
                                </flux:button>
                                <flux:button wire:click="confirmDelete({{ $workspace->id }})" variant="danger"
                                    size="sm">
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

    <!-- Create/Edit Modal -->
    <flux:modal wire:model="showModal" variant="flyout">
        <form wire:submit.prevent="save" class="space-y-6">
            <flux:heading size="lg">
                {{ $workspaceId ? __('Edit Workspace') : __('Create Workspace') }}
            </flux:heading>

            <flux:input
                wire:model="desk"
                label="{{ __('Desk') }}"
                required
                :error="$errors->first('desk')"
            />

            <flux:select
                wire:model="status"
                label="{{ __('Status') }}"
                required
                :error="$errors->first('status')"
            >
                <option value="">{{ __('Select Status') }}</option>
                @foreach($statuses as $status)
                    <option value="{{ $status->value }}">{{ $status->label() }}</option>
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
            <flux:heading size="lg">{{ __('Delete Workspace') }}</flux:heading>
            <p>{{ __('Are you sure you want to delete this workspace?') }}</p>
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
