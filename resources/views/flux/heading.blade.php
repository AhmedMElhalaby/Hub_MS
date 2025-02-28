@props([
    'size' => 'default'
])

@php
    $classes = match($size) {
        'sm' => 'text-base font-semibold leading-6',
        'lg' => 'text-2xl font-semibold leading-9',
        default => 'text-xl font-semibold leading-7'
    };
@endphp

<h2 {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</h2>
