<div class="p-6">
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <flux:heading>{{ __('crud.notifications.model.plural') }}</flux:heading>
            <flux:button wire:click="markAllAsRead" variant="outline">
                {{ __('crud.notifications.actions.mark_all_as_read') }}
            </flux:button>
        </div>
    </div>

    <div class="space-y-4">
        @forelse($notifications as $notification)
            <div wire:click="redirectAndMarkAsRead('{{ $notification->id }}')" class="cursor-pointer">
                <flux:card class="{{ is_null($notification->read_at) ? 'bg-primary-50 dark:bg-primary-900/10' : '' }} hover:ring-2 hover:ring-primary-500/50 transition-all">
                    <flux:card.content>
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h3 class="text-sm font-medium">{{ $notification->data['title'] ?? '' }}</h3>
                                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                                    {{ $notification->data['message'] ?? '' }}
                                </p>
                                <div class="mt-2 text-xs text-zinc-500">
                                    {{ $notification->created_at->diffForHumans() }}
                                </div>
                            </div>
                            <div class="ml-4">
                                @if(is_null($notification->read_at))
                                    <flux:button wire:click.stop="markAsRead('{{ $notification->id }}')" size="sm">
                                        {{ __('crud.notifications.actions.mark_as_read') }}
                                    </flux:button>
                                @endif
                            </div>
                        </div>
                    </flux:card.content>
                </flux:card>
            </div>
        @empty
            <div class="text-center py-12">
                <flux:icon name="bell" class="mx-auto size-12 text-zinc-400" />
                <h3 class="mt-2 text-sm font-medium">{{ __('crud.notifications.messages.no_notifications') }}</h3>
                <p class="mt-1 text-sm text-zinc-500">{{ __('crud.notifications.messages.all_caught_up') }}</p>
            </div>
        @endforelse

        <div class="mt-6">
            {{ $notifications->links() }}
        </div>
    </div>
</div>
