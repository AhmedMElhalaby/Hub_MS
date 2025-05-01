<div>
    <flux:modal wire:model="showModal" variant="flyout">
        <form wire:submit.prevent="renew" class="space-y-6">
            <flux:heading size="lg">{{ __('Renew Booking') }}</flux:heading>

            <flux:select
                wire:model.live="planId"
                label="{{ __('Plan') }}"
                required
                :error="$errors->first('planId')"
            >
                @foreach($plans as $plan)
                    <option value="{{ $plan->id }}">{{ $plan->type->label() }} - {{ number_format($plan->price, 2) }}</option>
                @endforeach
            </flux:select>

            <div class="grid grid-cols-2 gap-4">
                <flux:input
                    wire:model.live="startedAt"
                    type="datetime-local"
                    label="{{ __('Start Date') }}"
                    required
                    :error="$errors->first('startedAt')"
                />

                <flux:input
                    wire:model.live="duration"
                    type="number"
                    min="1"
                    label="{{ __('Duration (Times)') }}"
                    required
                    :error="$errors->first('duration')"
                />
            </div>

            @if($endedAt || $total)
                <div class="grid grid-cols-3 gap-4">
                    @if($endedAt && !is_numeric($endedAt))
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

            <div class="flex justify-end space-x-2">
                <flux:button type="button" wire:click="closeModal" variant="outline">
                    {{ __('Cancel') }}
                </flux:button>
                <flux:button type="submit" variant="primary">
                    {{ __('Renew') }}
                </flux:button>
            </div>
        </form>
    </flux:modal>
</div>
