@php
    use App\Models\Setting;
@endphp
<div>
    <flux:modal wire:model="showModal" variant="flyout">
        <form wire:submit.prevent="store" class="space-y-6">
            <flux:heading size="lg">
                {{ __('Create Plan') }}
            </flux:heading>

            <flux:select
                wire:model.live="type"
                label="{{ __('Type') }}"
                required
                :error="$errors->first('type')"
            >
                <option value="">{{ __('Select Type') }}</option>
                @foreach($types as $type)
                    <option value="{{ $type->value }}">{{ $type->label() }}</option>
                @endforeach
            </flux:select>

            <flux:input
                wire:model.live="price"
                label="{{ __('Price') }}"
                type="number"
                step="0.01"
                required
                :error="$errors->first('price')"
            />

            @if(Setting::get('mikrotik_enabled'))
            <flux:select
                wire:model.live="mikrotik_profile"
                label="{{ __('Mikrotik Profile') }}"
                required
                :error="$errors->first('mikrotik_profile')"
            >
                <option value="">{{ __('Select Profile') }}</option>
                @foreach($availableProfiles as $profile)
                    <option value="{{ $profile }}">{{ $profile }}</option>
                @endforeach
            </flux:select>
            @endif

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
