<div class="p-6">
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <flux:heading>{{ __('crud.plans.actions.view') }}</flux:heading>
            <div class="flex space-x-2">
                <flux:button wire:click="$dispatch('open-edit-plan', { planId: {{ $plan->id }} })" variant="primary">
                    {{ __('crud.plans.actions.edit') }}
                </flux:button>
                <flux:button wire:navigate href="{{ route('tenant.plans.index') }}" variant="outline">
                    {{ __('crud.plans.labels.back_to_plans') }}
                </flux:button>
            </div>
        </div>
    </div>

    <!-- Plan Information Card -->
    <div class="mb-8 grid gap-6 lg:grid-cols-2">
        <flux:card>
            <x-slot:header>
                <flux:heading size="sm">{{ __('crud.plans.labels.basic_information') }}</flux:heading>
            </x-slot:header>
            <x-slot:content class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <div class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('crud.plans.fields.type') }}</div>
                        <div>{{ $plan->type->label() }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('crud.plans.fields.price') }}</div>
                        <div>{{ number_format($plan->price, 2) }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('crud.common.fields.created_at') }}</div>
                        <div>{{ $plan->created_at->format('M d, Y') }}</div>
                    </div>
                </div>
            </x-slot:content>
        </flux:card>

        @if($plan->bookings->count() > 0)
            <flux:card>
                <x-slot:header>
                    <flux:heading size="sm">{{ __('crud.workspaces.labels.bookings') }}</flux:heading>
                </x-slot:header>
                <x-slot:content>
                    <flux:table>
                        <x-slot:header>
                            <flux:table.head>{{ __('crud.customers.fields.name') }}</flux:table.head>
                            <flux:table.head>{{ __('crud.common.fields.created_at') }}</flux:table.head>
                            <flux:table.head>{{ __('crud.workspaces.fields.status') }}</flux:table.head>
                        </x-slot:header>

                        <x-slot:body>
                            @foreach($plan->bookings as $booking)
                                <flux:table.row>
                                    <flux:table.cell>{{ $booking->customer->name }}</flux:table.cell>
                                    <flux:table.cell>{{ $booking->started_at->format('M d, Y') }}</flux:table.cell>
                                    <flux:table.cell>
                                        <flux:badge>{{ $booking->status }}</flux:badge>
                                    </flux:table.cell>
                                </flux:table.row>
                            @endforeach
                        </x-slot:body>
                    </flux:table>
                </x-slot:content>
            </flux:card>
        @endif
    </div>
    <livewire:plans.edit-plan />
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('plan-updated', () => {
                Livewire.dispatch('refresh');
            });
        });
    </script>
</div>
