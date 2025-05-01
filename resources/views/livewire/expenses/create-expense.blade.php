<div>
    <flux:modal wire:model="showModal" variant="flyout">
        <form wire:submit.prevent="store" class="space-y-6">
            <flux:heading size="lg">
                {{ __('Create Expense') }}
            </flux:heading>

            <flux:select
                wire:model.live="category"
                label="{{ __('Category') }}"
                required
                :error="$errors->first('category')"
            >
                <option value="">{{ __('Select Category') }}</option>
                @foreach($categories as $category)
                    <option value="{{ $category->value }}">{{ $category->label() }}</option>
                @endforeach
            </flux:select>

            <flux:input
                wire:model.live="amount"
                label="{{ __('Amount') }}"
                type="number"
                step="0.01"
                required
                :error="$errors->first('amount')"
            />

            <div class="flex justify-end space-x-2 mt-10">
                <flux:button type="button" wire:click="$set('showModal', false)" variant="outline">
                    {{ __('Cancel') }}
                </flux:button>
                <flux:button wire:loading.attr="disabled" wire:target="store" type="submit" variant="primary">
                    <span wire:loading.remove wire:target="store">{{ __('Save') }}</span>
                    <span wire:loading wire:target="store">{{ __('Saving...') }}</span>
                </flux:button>
            </div>
        </form>
    </flux:modal>
</div>
