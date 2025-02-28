<div class="p-6">
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <flux:heading>{{ __('User Details') }}</flux:heading>
            <flux:button wire:navigate href="{{ route('users.index') }}" variant="outline">
                {{ __('Back to Users') }}
            </flux:button>
        </div>
    </div>

    <!-- User Information Card -->
    <div class="mb-8 grid gap-6 lg:grid-cols-2">
        <flux:card>
            <flux:card.header>
                <flux:heading size="sm">{{ __('User Information') }}</flux:heading>
            </flux:card.header>
            <flux:card.content class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <div class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('Name') }}</div>
                        <div>{{ $user->name }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('Email') }}</div>
                        <div>{{ $user->email }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('Joined') }}</div>
                        <div>{{ $user->created_at->format('M d, Y') }}</div>
                    </div>
                </div>
            </flux:card.content>
        </flux:card>

        <flux:card>
            <flux:card.header>
                <flux:heading size="sm">{{ __('Activity Summary') }}</flux:heading>
            </flux:card.header>
            <flux:card.content class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <div class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('Last Login') }}</div>
                        <div class="text-2xl font-semibold">
                            {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}
                        </div>
                    </div>
                    <div>
                        <div class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('Last Active') }}</div>
                        <div class="text-2xl font-semibold">
                            {{ $user->last_active_at ? $user->last_active_at->diffForHumans() : 'Never' }}
                        </div>
                    </div>
                </div>
            </flux:card.content>
        </flux:card>
    </div>

    <!-- Recent Activity -->
    <flux:card class="mb-6 mt-3">
        <flux:card.header>
            <flux:heading size="sm">{{ __('Recent Activity') }}</flux:heading>
        </flux:card.header>
        <flux:card.content>
            <flux:table>
                <x-slot:header>
                    <flux:table.head>{{ __('Action') }}</flux:table.head>
                    <flux:table.head>{{ __('Description') }}</flux:table.head>
                    <flux:table.head>{{ __('Date') }}</flux:table.head>
                </x-slot:header>

                <x-slot:body>
                    <flux:table.row>
                        <flux:table.cell colspan="3" class="text-center">
                            {{ __('No activity found.') }}
                        </flux:table.cell>
                    </flux:table.row>
                </x-slot:body>
            </flux:table>
        </flux:card.content>
    </flux:card>
</div>
