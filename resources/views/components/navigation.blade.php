@php
    use App\Models\Setting;
    $appName = Setting::get('app_name', config('app.name'));
@endphp

<nav>
    <div class="flex items-center">
        @if($logo = Setting::get('app_logo'))
            <img src="{{ Storage::url($logo) }}" alt="{{ $appName }}" class="h-8">
        @else
            <span class="text-xl font-bold">{{ $appName }}</span>
        @endif
    </div>
    <!-- ... rest of navigation ... -->
</nav>
