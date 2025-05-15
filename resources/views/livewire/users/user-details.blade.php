<div class="p-6">
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <flux:heading>{{ __('crud.users.labels.management') }}</flux:heading>
            <div class="flex space-x-2">

                <flux:button wire:click="$dispatch('open-edit-user', { userId: {{ $user->id }} })" variant="primary">
                    {{ __('crud.users.actions.edit') }}
                </flux:button>
                <flux:button wire:navigate href="{{ route('tenant.users.index') }}" variant="outline">
                    {{ __('crud.users.labels.back_to_users') }}
                </flux:button>
            </div>
        </div>
    </div>

    <!-- User Information Card -->
    <div class="mb-8 grid gap-6 lg:grid-cols-2">
        <flux:card>
            <flux:card.header>
                <flux:heading size="sm">{{ __('crud.users.labels.management') }}</flux:heading>
            </flux:card.header>
            <flux:card.content class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <div class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('crud.users.fields.name') }}</div>
                        <div>{{ $user->name }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('crud.users.fields.email') }}</div>
                        <div>{{ $user->email }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('crud.common.fields.created_at') }}</div>
                        <div>{{ $user->created_at->format('M d, Y') }}</div>
                    </div>
                </div>
            </flux:card.content>
        </flux:card>

        <flux:card>
            <flux:card.header>
                <flux:heading size="sm">{{ __('crud.common.labels.details') }}</flux:heading>
            </flux:card.header>
            <flux:card.content class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <div class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('crud.common.fields.updated_at') }}</div>
                        <div class="text-2xl font-semibold">
                            {{ $user->updated_at ? $user->updated_at->diffForHumans() : __('crud.common.messages.not_found') }}
                        </div>
                    </div>
                    <div>
                        <div class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('crud.common.fields.deleted_at') }}</div>
                        <div class="text-2xl font-semibold">
                            {{ $user->deleted_at ? $user->deleted_at->diffForHumans() : ''}}
                        </div>
                    </div>
                </div>
            </flux:card.content>
        </flux:card>
    </div>

    <!-- Recent Activity -->
    <flux:card class="mb-6 mt-3">
        <flux:card.header>
            <flux:heading size="sm">{{ __('crud.common.labels.details') }}</flux:heading>
        </flux:card.header>
        <flux:card.content class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <div class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('crud.common.fields.note') }}</div>
                    <div class="text-2xl font-semibold">
                        {{ $user->note ?: __('crud.common.messages.no_records', ['model' => __('crud.users.model.singular')]) }}
                    </div>
                </div>
            </div>
        </flux:card.content>
    </flux:card>

    <livewire:users.edit-user />
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('user-updated', () => {
                Livewire.dispatch('refresh');
            });
        });
    </script>
</div>
