<div>
    <flux:modal wire:model="showModal" variant="flyout">
        <form wire:submit.prevent="update" class="space-y-6">
            <flux:heading size="lg">
                {{ __('crud.expenses.actions.edit') }}
            </flux:heading>

            <flux:select
                wire:model.live="category"
                label="{{ __('crud.expenses.fields.category') }}"
                required
                :error="$errors->first('category')"
            >
                <option value="">{{ __('crud.common.actions.select', ['model' => __('crud.expenses.fields.category')]) }}</option>
                @foreach($categories as $category)
                    <option value="{{ $category->value }}">{{ $category->label() }}</option>
                @endforeach
            </flux:select>

            <flux:input
                wire:model.live="amount"
                label="{{ __('crud.expenses.fields.amount') }}"
                type="number"
                step="0.01"
                required
                :error="$errors->first('amount')"
            />

            <div class="flex justify-end space-x-2 mt-10">
                <flux:button type="button" wire:click="$set('showModal', false)" variant="outline">
                    {{ __('crud.common.actions.cancel') }}
                </flux:button>
                <flux:button wire:loading.attr="disabled" wire:target="update" type="submit" variant="primary">
                    <span wire:loading.remove wire:target="update">{{ __('crud.common.actions.save') }}</span>
                    <span wire:loading wire:target="update">{{ __('crud.common.actions.saving') }}</span>
                </flux:button>
            </div>
        </form>
    </flux:modal>
</div>
