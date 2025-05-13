<x-layouts.auth>
    <div class="flex flex-col gap-6">
        <x-auth-header title="Log in to your account" description="Enter your email and password below to log in" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('tenant.login') }}" class="flex flex-col gap-6">
            @csrf
            <!-- Email Address -->
            <flux:input
                name="email"
                label="{{ __('Email address') }}"
                type="email"
                required
                autofocus
                autocomplete="email"
                placeholder="email@example.com"
                :value="old('email')"
            />

            <!-- Password -->
            <div class="relative">
                <flux:input
                    name="password"
                    label="{{ __('Password') }}"
                    type="password"
                    required
                    autocomplete="current-password"
                    placeholder="Password"
                />

                {{-- @if (Route::has('password.request'))
                    <flux:link class="absolute right-0 top-0 text-sm" href="{{ route('tenant.password.request') }}">
                        {{ __('Forgot your password?') }}
                    </flux:link>
                @endif --}}
            </div>

            <!-- Remember Me -->
            <flux:checkbox name="remember" label="{{ __('Remember me') }}" />

            <div class="flex items-center justify-end">
                <flux:button variant="primary" type="submit" class="w-full">{{ __('Log in') }}</flux:button>
            </div>
        </form>

        <div class="space-x-1 text-center text-sm text-zinc-600 dark:text-zinc-400">
            Don't have an account?
            <flux:link href="{{ route('register') }}">Sign up</flux:link>
        </div>
    </div>
</x-layouts.auth>
