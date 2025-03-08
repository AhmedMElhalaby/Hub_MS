@props([
    'label' => null,
    'error' => null,
])

<div>
    @if($label)
        <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-200">
            {{ $label }}
        </label>
    @endif

    <input
        {{ $attributes->merge([
            'type' => 'file',
            'class' => 'block w-full text-sm text-zinc-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-zinc-50 file:text-zinc-700 hover:file:bg-zinc-100 dark:file:bg-zinc-800 dark:file:text-zinc-200 dark:hover:file:bg-zinc-700 dark:text-zinc-200' . ($error ? ' border-red-500' : '')
        ]) }}
    >

    @if($error)
        <p class="mt-1 text-sm text-red-600">{{ $error }}</p>
    @endif
</div>
