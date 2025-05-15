<div class="p-6">
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <flux:heading>{{ __('crud.workspaces.actions.view') }}</flux:heading>
            <div class="flex space-x-2">
                <flux:button wire:click="$dispatch('open-edit-workspace', { workspaceId: {{ $workspace->id }} })" variant="primary">
                    {{ __('crud.workspaces.actions.edit') }}
                </flux:button>
                <flux:button wire:navigate href="{{ route('tenant.workspaces.index') }}" variant="outline">
                    {{ __('crud.workspaces.labels.back_to_workspaces') }}
                </flux:button>
            </div>
        </div>
    </div>

    <!-- Workspace Information Card -->
    <div class="mb-8 grid gap-6 lg:grid-cols-2">
        <flux:card>
            <x-slot:header>
                <flux:heading size="sm">{{ __('crud.workspaces.labels.basic_information') }}</flux:heading>
            </x-slot:header>
            <x-slot:content>
                <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">{{ __('crud.workspaces.fields.desk') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $workspace->desk }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">{{ __('crud.workspaces.fields.status') }}</dt>
                        <dd class="mt-1">
                            <flux:badge variant="solid" :color="$workspace->status->color()">
                                {{ $workspace->status->label() }}
                            </flux:badge>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">{{ __('crud.common.fields.created_at') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $workspace->created_at->format('M d, Y H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">{{ __('crud.common.fields.updated_at') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $workspace->updated_at->format('M d, Y H:i') }}</dd>
                    </div>
                </dl>
            </x-slot:content>
        </flux:card>

        @if($workspace->bookings->count() > 0)
            <flux:card>
                <x-slot:header>
                    <flux:heading size="sm">{{ __('crud.workspaces.labels.bookings') }}</flux:heading>
                </x-slot:header>
                <x-slot:content>
                    <div class="space-y-4">
                        @foreach($workspace->bookings as $booking)
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-sm font-medium">{{ $booking->customer->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $booking->started_at->format('M d, Y H:i') }}</p>
                                </div>
                                <flux:badge>{{ $booking->status }}</flux:badge>
                            </div>
                        @endforeach
                    </div>
                </x-slot:content>
            </flux:card>
        @endif
    </div>
    <livewire:workspaces.edit-workspace />
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('workspace-updated', () => {
                Livewire.dispatch('refresh');
            });
        });
    </script>
</div>
