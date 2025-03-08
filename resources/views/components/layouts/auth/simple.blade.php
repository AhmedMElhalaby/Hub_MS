@php
    use App\Models\Setting;
    $appName = Setting::get('app_name', config('app.name'));
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
        @if($favicon = Setting::get('app_favicon'))
            <link rel="icon" type="image/x-icon" href="{{ Storage::url($favicon) }}">
        @endif
    </head>
    <body class="min-h-screen bg-white antialiased dark:bg-linear-to-b dark:from-neutral-950 dark:to-neutral-900">
        <div class="bg-background flex min-h-svh flex-col items-center justify-center gap-6 p-6 md:p-10">
            <div class="flex w-full max-w-sm flex-col gap-2">
                <a href="{{ route('home') }}" class="flex flex-col items-center gap-2 font-medium" wire:navigate>
                    @if($logo = Setting::get('app_logo'))
                        <img src="{{ Storage::url($logo) }}" alt="{{ $appName }}" class="h-9 w-9">
                    @else
                        <span class="flex h-9 w-9 mb-1 items-center justify-center rounded-md">
                            <x-app-logo-icon class="size-9 fill-current text-black dark:text-white" />
                        </span>
                    @endif
                    <span class="sr-only">{{ $appName }}</span>
                </a>
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
