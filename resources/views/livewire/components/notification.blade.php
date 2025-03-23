<div class="fixed top-4 left-4 z-50 space-y-4">
    @foreach($notifications as $notification)
        <div
            x-data="{ show: true }"
            x-show="show"
            x-init="setTimeout(() => { show = false; $wire.remove('{{ $notification['id'] }}') }, 3000)"
            class="notification-{{ $notification['type'] }} rounded-lg p-4 flex items-center shadow-lg max-w-sm dark:bg-gray-800"
            role="alert"
        >
            <div class="flex-shrink-0">
                @if($notification['type'] === 'success')
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                @endif
                @if($notification['type'] === 'error')
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                @endif
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                    {{ $notification['message'] }}
                </p>
            </div>
        </div>
    @endforeach
</div>
