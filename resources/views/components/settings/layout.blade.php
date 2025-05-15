<div>
    <livewire:components.notification />
    <x-tabs.container active-tab="{{ request()->route()->getName() }}">
        <x-slot:tabs>
            <a href="{{ route('tenant.settings.general') }}" wire:navigate>
                <x-tabs.tab name="settings.general" icon="cog">
                    {{ __('crud.settings.labels.basic_information') }}
                </x-tabs.tab>
            </a>
            <a href="{{ route('tenant.settings.profile') }}" wire:navigate>
                <x-tabs.tab name="settings.profile" icon="user">
                    {{ __('crud.settings.labels.profile') }}
                </x-tabs.tab>
            </a>
            <a href="{{ route('tenant.settings.password') }}" wire:navigate>
                <x-tabs.tab name="settings.password" icon="lock-closed">
                    {{ __('crud.settings.labels.password') }}
                </x-tabs.tab>
            </a>
            <a href="{{ route('tenant.settings.appearance') }}" wire:navigate>
                <x-tabs.tab name="settings.appearance" icon="paint-brush">
                    {{ __('crud.settings.labels.appearance') }}
                </x-tabs.tab>
            </a>
            <a href="{{ route('tenant.settings.mikrotik') }}" wire:navigate>
                <x-tabs.tab name="settings.mikrotik" icon="server">
                    {{ __('crud.settings.labels.mikrotik') }}
                </x-tabs.tab>
            </a>
            <a href="{{ route('tenant.settings.sms') }}" wire:navigate>
                <x-tabs.tab name="settings.sms" icon="chat-bubble-oval-left-ellipsis">
                    {{ __('crud.settings.labels.sms') }}
                </x-tabs.tab>
            </a>
        </x-slot:tabs>

        <div class="mt-5 w-full max-w-lg">
            <flux:heading>{{ $heading }}</flux:heading>
            <flux:subheading>{{ $subheading }}</flux:subheading>
            {{ $slot }}
        </div>
    </x-tabs.container>
</div>
