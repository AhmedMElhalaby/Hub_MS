<div>
    <livewire:components.notification />
    <x-tabs.container active-tab="{{ request()->route()->getName() }}">
        <x-slot:tabs>
            <a href="{{ tenant_route('settings.general') }}" wire:navigate>
                <x-tabs.tab name="settings.general" icon="cog">
                    {{ __('General Settings') }}
                </x-tabs.tab>
            </a>
            <a href="{{ tenant_route('settings.profile') }}" wire:navigate>
                <x-tabs.tab name="settings.profile" icon="user">
                    {{ __('Profile') }}
                </x-tabs.tab>
            </a>
            <a href="{{ tenant_route('settings.password') }}" wire:navigate>
                <x-tabs.tab name="settings.password" icon="lock-closed">
                    {{ __('Password') }}
                </x-tabs.tab>
            </a>
            <a href="{{ tenant_route('settings.appearance') }}" wire:navigate>
                <x-tabs.tab name="settings.appearance" icon="paint-brush">
                    {{ __('Appearance') }}
                </x-tabs.tab>
            </a>
            <a href="{{ tenant_route('settings.mikrotik') }}" wire:navigate>
                <x-tabs.tab name="settings.mikrotik" icon="server">
                    {{ __('Mikrotik') }}
                </x-tabs.tab>
            </a>
            <a href="{{ tenant_route('settings.sms') }}" wire:navigate>
                <x-tabs.tab name="settings.sms" icon="chat-bubble-oval-left-ellipsis">
                    {{ __('SMS') }}
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
