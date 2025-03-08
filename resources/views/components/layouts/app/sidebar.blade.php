@php
    use App\Models\Setting;

    $appName = Setting::get('app_name', config('app.name'));
    $primaryColor = Setting::get('primary_color', '#000000');
    $secondaryColor = Setting::get('secondary_color', '#666666');
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
        <title>{{ $appName }}</title>

        @if($favicon = Setting::get('app_favicon'))
            <link rel="icon" type="image/x-icon" href="{{ Storage::url($favicon) }}">
        @endif

        <style>
            :root {
                --primary-color: {{ $primaryColor }};
                --secondary-color: {{ $secondaryColor }};
            }
        </style>
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:sidebar sticky stashable class="border-r border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <a href="{{ route('dashboard') }}" class="mr-5 flex items-center space-x-2" wire:navigate>
                <x-app-logo class="size-8" href="#"></x-app-logo>
            </a>

            <flux:navlist variant="outline">
                <flux:navlist.group heading="Platform" class="grid">
                    <flux:navlist.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>{{ __('Dashboard') }}</flux:navlist.item>
                    <flux:navlist.item icon="users" :href="route('customers.index')" :current="request()->routeIs('customers.*')" wire:navigate>{{ __('Customers') }}</flux:navlist.item>
                    <flux:navlist.item icon="briefcase" :href="route('workspaces.index')" :current="request()->routeIs('workspaces.*')" wire:navigate>{{ __('Workspaces') }}</flux:navlist.item>
                    <flux:navlist.item icon="currency-dollar" :href="route('plans.index')" :current="request()->routeIs('plans.*')" wire:navigate>{{ __('Plans') }}</flux:navlist.item>
                    <flux:navlist.item icon="receipt-percent" :href="route('expenses.index')" :current="request()->routeIs('expenses.*')" wire:navigate>{{ __('Expenses') }}</flux:navlist.item>
                    <flux:navlist.item icon="calendar" :href="route('bookings.index')" :current="request()->routeIs('bookings.*')" wire:navigate>{{ __('Bookings') }}</flux:navlist.item>
                </flux:navlist.group>
            </flux:navlist>

            <flux:spacer />

            <flux:navlist variant="outline">
                <flux:navlist.item icon="banknotes" :href="route('finances.index')" :current="request()->routeIs('finances.*')" wire:navigate>{{ __('Finances') }}</flux:navlist.item>
                <flux:navlist.item icon="users" :href="route('users.index')" :current="request()->routeIs('users.index')" wire:navigate>{{ __('Manage Users') }}</flux:navlist.item>
                <flux:navlist.item icon="bell" :href="route('notifications.index')" :current="request()->routeIs('notifications.*')" wire:navigate class="relative">
                    {{ __('Notifications') }}
                    @php
                        $unreadCount = auth()->user()->unreadNotifications()->count();
                    @endphp
                    @if($unreadCount > 0)
                        <span class="absolute right-2 top-1/2 -translate-y-1/2 flex items-center justify-center min-w-[20px] h-5 text-xs font-bold text-white bg-red-600 rounded-full px-1">
                            {{ $unreadCount }}
                        </span>
                    @endif
                </flux:navlist.item>
            </flux:navlist>

            <!-- Desktop User Menu -->
            <flux:dropdown position="bottom" align="start">
                <flux:profile
                    :name="auth()->user()->name"
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevrons-up-down"
                />

                <flux:menu class="w-[220px]">
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-left text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item href="/settings/profile" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-left text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item href="/settings/profile" icon="cog" wire:navigate>Settings</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        @fluxScripts
    </body>
</html>
