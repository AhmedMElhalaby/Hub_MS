<div>
    <flux:modal wire:model="showModal" variant="flyout">
        <form wire:submit.prevent="store" class="space-y-6">
            <flux:heading size="lg">
                {{ __('Create Booking') }}
            </flux:heading>

            <flux:select
                wire:model.live="customerId"
                label="{{ __('Customer') }}"
                required
                :error="$errors->first('customerId')"
            >
                <option value="">{{ __('Select Customer') }}</option>
                @foreach($customers as $customer)
                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                @endforeach
            </flux:select>

            <flux:select
                wire:model.live="workspaceId"
                label="{{ __('Workspace') }}"
                required
                :error="$errors->first('workspaceId')"
            >
                <option value="">{{ __('Select Workspace') }}</option>
                @foreach($workspaces as $workspace)
                    <option value="{{ $workspace->id }}">Desk {{ $workspace->desk }}</option>
                @endforeach
            </flux:select>

            <flux:select
                wire:model.live="planId"
                label="{{ __('Plan') }}"
                required
                :error="$errors->first('planId')"
            >
                <option value="">{{ __('Select Plan') }}</option>
                @foreach($plans as $plan)
                    <option value="{{ $plan->id }}">{{ $plan->type->label() }}</option>
                @endforeach
            </flux:select>

            <div class="grid grid-cols-2 gap-4">
                <flux:input
                    wire:model.live="startedAt"
                    label="{{ __('Start Date') }}"
                    type="datetime-local"
                    required
                    :error="$errors->first('startedAt')"
                />

                <flux:input
                    wire:model.live="duration"
                    label="{{ __('Duration (Hours)') }}"
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
                            <div class="font-medium">{{ __('End Date') }}</div>
                            <div class="text-lg">{{ \Carbon\Carbon::parse($endedAt)->format('M d, Y h:i A') }}</div>
                        </div>
                    @endif

                    @if($total)
                        <div class="rounded-lg bg-zinc-50 p-4 dark:bg-zinc-900">
                            <div class="font-medium">{{ __('Total Amount') }}</div>
                            <div class="text-2xl font-bold">{{ number_format($total, 2) }}</div>
                        </div>
                    @endif
                </div>
            @endif

            <div class="flex justify-end space-x-2 mt-10">
                <flux:button type="button" wire:click="closeModal" variant="outline">
                    {{ __('Cancel') }}
                </flux:button>
                <flux:button wire:click="storeAsDraft" wire:loading.attr="disabled" wire:target="storeAsDraft" type="button">
                    <span wire:loading.remove wire:target="storeAsDraft">{{ __('Save as Draft') }}</span>
                    <span wire:loading wire:target="storeAsDraft">{{ __('Saving...') }}</span>
                </flux:button>
                <flux:button wire:loading.attr="disabled" wire:target="store" type="submit" variant="primary">
                    <span wire:loading.remove wire:target="store">{{ __('Save') }}</span>
                    <span wire:loading wire:target="store">{{ __('Saving...') }}</span>
                </flux:button>
            </div>
        </form>
    </flux:modal>
</div>
