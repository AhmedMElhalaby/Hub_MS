@php
    use App\Models\Setting;
@endphp
<div>
    <flux:modal wire:model="showModal" variant="flyout">
        <form wire:submit.prevent="update" class="space-y-6">
            <flux:heading size="lg">
                {{ __('crud.plans.actions.edit') }}
            </flux:heading>

            <flux:select
                wire:model.live="type"
                label="{{ __('crud.plans.fields.type') }}"
                required
                :error="$errors->first('type')"
            >
                <option value="">{{ __('crud.plans.labels.select_type') }}</option>
                @foreach($types as $type)
                    <option value="{{ $type->value }}">{{ $type->label() }}</option>
                @endforeach
            </flux:select>

            <flux:input
                wire:model.live="price"
                label="{{ __('crud.plans.fields.price') }}"
                type="number"
                step="0.01"
                required
                :error="$errors->first('price')"
            />

            @if(Setting::get('mikrotik_enabled'))
            <flux:select
                wire:model.live="mikrotik_profile"
                label="{{ __('crud.plans.fields.mikrotik_profile') }}"
                required
                :error="$errors->first('mikrotik_profile')"
            >
                <option value="">{{ __('crud.plans.labels.select_profile') }}</option>
                @foreach($availableProfiles as $profile)
                    <option value="{{ $profile }}">{{ $profile }}</option>
                @endforeach
            </flux:select>
            @endif

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
