<section class="w-full">
    <x-settings.layout heading="{{ __('SMS Settings') }}" subheading="{{ __('Configure SMS notification settings') }}">
        <div class="mt-6">
            <form wire:submit.prevent="save" class="mt-4 space-y-6 max-w-xl">
                <label class="flex items-center cursor-pointer">
                    <div class="relative">
                        <input
                            type="checkbox"
                            wire:model="sms_settings.sms_enabled"
                            class="sr-only peer"
                        >
                        <div class="w-11 h-6 bg-zinc-200 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-zinc-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600 dark:bg-zinc-700 dark:peer-checked:bg-primary-500"></div>
                    </div>
                    <span class="ms-3 text-sm font-medium text-zinc-700 dark:text-zinc-200">{{ __('Enable SMS Notifications') }}</span>
                </label>

                <div x-show="$wire.sms_settings.sms_enabled"
                    x-cloak
                    class="space-y-6">
                    <flux:input
                        wire:model="sms_settings.sms_username"
                        label="{{ __('SMS Username') }}"
                        type="text"
                    />

                    <flux:input
                        wire:model="sms_settings.sms_password"
                        label="{{ __('SMS Password') }}"
                        type="password"
                    />

                    <flux:input
                        wire:model="sms_settings.sms_sender_id"
                        label="{{ __('SMS Sender ID') }}"
                        type="text"
                    />
                </div>

                <div class="flex items-center gap-4">
                    <div class="flex items-center justify-end">
                        <flux:button variant="primary" type="submit">
                            {{ __('Save SMS Settings') }}
                        </flux:button>
                    </div>
                </div>
            </form>
        </div>
    </x-settings.layout>
</section>
