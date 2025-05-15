<section class="mt-10 space-y-6">
    <div class="relative mb-5">
        <flux:heading>{{ __('crud.common.actions.delete') }}</flux:heading>
        <flux:subheading>{{ __('crud.common.messages.delete_confirm', ['model' => __('crud.users.model.singular')]) }}</flux:subheading>
    </div>

    <flux:modal.trigger name="confirm-user-deletion">
        <flux:button variant="danger" x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">
            {{ __('crud.common.actions.delete') }}
        </flux:button>
    </flux:modal.trigger>

    <flux:modal name="confirm-user-deletion" :show="$errors->isNotEmpty()" focusable class="max-w-lg">
        <form wire:submit="delete" class="space-y-6">
            <div>
                <flux:heading size="lg">{{ __('crud.common.messages.delete_confirm', ['model' => __('crud.users.model.singular')]) }}</flux:heading>

                <flux:subheading>
                    {{ __('crud.common.messages.delete_confirm', ['model' => __('crud.users.model.singular')]) }}
                </flux:subheading>
            </div>

            <flux:input wire:model="password" id="password" label="{{ __('crud.users.fields.password') }}" type="password" name="password" />

            <div class="flex justify-end space-x-2">
                <flux:modal.close>
                    <flux:button variant="filled">{{ __('crud.common.actions.cancel') }}</flux:button>
                </flux:modal.close>

                <flux:button variant="danger" type="submit">{{ __('crud.common.actions.delete') }}</flux:button>
            </div>
        </form>
    </flux:modal>
</section>
