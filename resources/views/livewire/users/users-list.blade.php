<div class="p-6">

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <flux:heading>{{ __('Users Management') }}</flux:heading>
        <flux:button wire:click="create" variant="primary">
            {{ __('Add New User') }}
        </flux:button>
    </div>

    <!-- Search and Filter -->
    <div class="flex gap-4 mb-4">
        <div class="flex-1">
            <flux:input wire:model.live="search" type="search" label="{{ __('Search') }}"
                placeholder="Search users..." />
        </div>
    </div>

    <!-- Users Table -->
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
                                <flux:button wire:navigate href="{{ route('users.show', $user) }}" size="sm">
                                    {{ __('View') }}
                                </flux:button>
                                <flux:button wire:click="edit({{ $user->id }})" size="sm">
                                    {{ __('Edit') }}
                                </flux:button>
                                <flux:button wire:click="confirmDelete({{ $user->id }})" variant="danger"
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

        <!-- Pagination -->
        <div class="mt-6">
            @include('flux.pagination', ['paginator' => $users])
        </div>
    </div>

    <!-- Create/Edit Modal -->
    <flux:modal wire:model="showModal" variant="flyout">
        <form wire:submit.prevent="save" class="space-y-6">
            <flux:heading size="lg">
                {{ $userId ? __('Edit User') : __('Create User') }}
            </flux:heading>

            <flux:input
                wire:model="name"
                label="{{ __('Name') }}"
                required
                :error="$errors->first('name')"
            />
            <flux:input
                wire:model="email"
                label="{{ __('Email') }}"
                type="email"
                required
                :error="$errors->first('email')"
            />
            <flux:input
                wire:model="password"
                label="{{ $userId ? __('Password (leave empty to keep current)') : __('Password') }}"
                type="password"
                :error="$errors->first('password')"
                :required="!$userId"
            />

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
        <div class="space-y-4">
            <flux:heading size="lg">{{ __('Delete User') }}</flux:heading>
            <p>{{ __('Are you sure you want to delete this user?') }}</p>
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
