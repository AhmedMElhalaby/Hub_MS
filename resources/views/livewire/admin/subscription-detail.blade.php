<div class="container mx-auto py-8">
    <div class="bg-white shadow-md rounded-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Subscription Details: #{{ $subscription->id }}</h2>
            <a href="{{ route('admin.subscriptions.index') }}" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-150">
                Back to Subscriptions List
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Core Information</h3>
                <dl class="space-y-2">
                    <div class="flex">
                        <dt class="w-1/3 text-sm font-medium text-gray-500">ID</dt>
                        <dd class="w-2/3 text-sm text-gray-900">{{ $subscription->id }}</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-1/3 text-sm font-medium text-gray-500">Tenant</dt>
                        <dd class="w-2/3 text-sm text-gray-900">
                            @if($subscription->tenant)
                                <a href="{{ route('admin.tenants.show', $subscription->tenant_id) }}" class="text-blue-600 hover:underline">
                                    {{ $subscription->tenant->name }}
                                </a>
                            @else
                                N/A
                            @endif
                        </dd>
                    </div>
                    <div class="flex">
                        <dt class="w-1/3 text-sm font-medium text-gray-500">Plan</dt>
                        <dd class="w-2/3 text-sm text-gray-900">{{ $subscription->plan->name ?? 'N/A' }}</dd>
                    </div>
                    <div class="flex items-center">
                        <dt class="w-1/3 text-sm font-medium text-gray-500">Status</dt>
                        <dd class="w-2/3 text-sm text-gray-900">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($subscription->status === 'active') bg-green-100 text-green-800
                                @elseif(in_array($subscription->status, ['cancelled', 'expired'])) bg-red-100 text-red-800
                                @else bg-yellow-100 text-yellow-800 @endif">
                                {{ ucfirst($subscription->status) }}
                            </span>
                        </dd>
                    </div>
                </dl>
            </div>

            <div>
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Dates</h3>
                <dl class="space-y-2">
                    <div class="flex">
                        <dt class="w-1/3 text-sm font-medium text-gray-500">Start Date</dt>
                        <dd class="w-2/3 text-sm text-gray-900">{{ \Carbon\Carbon::parse($subscription->start_date)->format('Y-m-d H:i:s') }}</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-1/3 text-sm font-medium text-gray-500">End Date</dt>
                        <dd class="w-2/3 text-sm text-gray-900">{{ $subscription->end_date ? \Carbon\Carbon::parse($subscription->end_date)->format('Y-m-d H:i:s') : 'N/A' }}</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-1/3 text-sm font-medium text-gray-500">Created At</dt>
                        <dd class="w-2/3 text-sm text-gray-900">{{ $subscription->created_at->format('Y-m-d H:i:s') }}</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-1/3 text-sm font-medium text-gray-500">Updated At</dt>
                        <dd class="w-2/3 text-sm text-gray-900">{{ $subscription->updated_at->format('Y-m-d H:i:s') }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <div class="mt-8 border-t pt-6">
            <h3 class="text-lg font-semibold text-gray-700">Subscription Event Log</h3>
            <p class="mt-2 text-gray-500">A log of status changes or renewals could be displayed here.</p>
        </div>

        <div class="mt-8 border-t pt-6">
            <h3 class="text-lg font-semibold text-gray-700">Manage Subscription</h3>
            <p class="mt-2 text-gray-500">Options for upgrade/downgrade or manual renewal could be here.</p>
        </div>

    </div>
</div>
