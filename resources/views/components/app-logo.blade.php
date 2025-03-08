@php
    use App\Models\Setting;
    $appName = Setting::get('app_name', config('app.name'));
@endphp

<div class="flex aspect-square size-8 items-center justify-center rounded-md bg-accent-content text-accent-foreground">
    @if($logo = Setting::get('app_logo'))
        <img src="{{ Storage::url($logo) }}" alt="{{ $appName }}" class="size-8 rounded-md">
    @else
        <x-app-logo-icon class="size-5 fill-current text-white dark:text-black" />
    @endif
</div>
<div class="ml-1 grid flex-1 text-left text-sm">
    <span class="mb-0.5 truncate leading-none font-semibold">{{ $appName }}</span>
</div>
