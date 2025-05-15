<div>
    <flux:modal wire:model="showModal" variant="flyout">
        <form wire:submit.prevent="store" class="space-y-6">
            <flux:heading size="lg">
                {{ __('crud.bookings.actions.create') }}
            </flux:heading>

            <flux:select
                wire:model.live="customerId"
                label="{{ __('crud.bookings.fields.customer') }}"
                required
                :error="$errors->first('customerId')"
            >
                <option value="">{{ __('crud.common.actions.select', ['model' => __('crud.bookings.fields.customer')]) }}</option>
                @foreach($customers as $customer)
                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                @endforeach
            </flux:select>

            <flux:select
                wire:model.live="workspaceId"
                label="{{ __('crud.bookings.fields.workspace') }}"
                required
                :error="$errors->first('workspaceId')"
            >
                <option value="">{{ __('crud.common.actions.select', ['model' => __('crud.bookings.fields.workspace')]) }}</option>
                @foreach($workspaces as $workspace)
                    <option value="{{ $workspace->id }}">Desk {{ $workspace->desk }}</option>
                @endforeach
            </flux:select>

            <flux:select
                wire:model.live="planId"
                label="{{ __('crud.bookings.fields.plan') }}"
                required
                :error="$errors->first('planId')"
            >
                <option value="">{{ __('crud.common.actions.select', ['model' => __('crud.bookings.fields.plan')]) }}</option>
                @foreach($plans as $plan)
                    <option value="{{ $plan->id }}">{{ $plan->type->label() }}</option>
                @endforeach
            </flux:select>

            <div class="grid grid-cols-2 gap-4">
                <flux:input
                    wire:model.live="startedAt"
                    label="{{ __('crud.bookings.fields.started_at') }}"
                    type="datetime-local"
                    required
                    :error="$errors->first('startedAt')"
                />

                <flux:input
                    wire:model.live="duration"
                    label="{{ __('crud.bookings.fields.duration') }}"
                    type="number"
                    min="1"
                    step="1"
                    required
                    :error="$errors->first('duration')"
                />
            </div>

            @if($endedAt || $total)
                <div class="grid grid-cols-3 gap-4">
                    @if($endedAt)
                        <div class="col-span-2 rounded-lg bg-zinc-50 p-4 dark:bg-zinc-900">
                            <div class="font-medium">{{ __('crud.bookings.fields.ended_at') }}</div>
                            <div class="text-lg">{{ \Carbon\Carbon::parse($endedAt)->format('M d, Y h:i A') }}</div>
                        </div>
                    @endif

                    @if($total)
                        <div class="rounded-lg bg-zinc-50 p-4 dark:bg-zinc-900">
                            <div class="font-medium">{{ __('crud.bookings.fields.total') }}</div>
                            <div class="text-2xl font-bold">{{ number_format($total, 2) }}</div>
                        </div>
                    @endif
                </div>
            @endif

            <div class="flex justify-end space-x-2 mt-10">
                <flux:button type="button" wire:click="closeModal" variant="outline">
                    {{ __('crud.common.actions.cancel') }}
                </flux:button>
                <flux:button wire:click="storeAsDraft" wire:loading.attr="disabled" wire:target="storeAsDraft" type="button">
                    <span wire:loading.remove wire:target="storeAsDraft">{{ __('crud.bookings.labels.save_as_draft') }}</span>
                    <span wire:loading wire:target="storeAsDraft">{{ __('crud.common.actions.saving') }}</span>
                </flux:button>
                <flux:button wire:loading.attr="disabled" wire:target="store" type="submit" variant="primary">
                    <span wire:loading.remove wire:target="store">{{ __('crud.common.actions.save') }}</span>
                    <span wire:loading wire:target="store">{{ __('crud.common.actions.saving') }}</span>
                </flux:button>
            </div>
        </form>
    </flux:modal>
</div>
