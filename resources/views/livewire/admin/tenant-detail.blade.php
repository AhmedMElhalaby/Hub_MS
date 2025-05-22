<div class="container mx-auto py-8">
    <div class="bg-white shadow-md rounded-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Tenant Details: {{ $tenant->name }}</h2>
            <a href="{{ route('admin.tenants.index') }}" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-150">
                Back to Tenants List
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Core Information</h3>
                <dl class="space-y-2">
                    <div class="flex">
                        <dt class="w-1/3 text-sm font-medium text-gray-500">ID</dt>
                        <dd class="w-2/3 text-sm text-gray-900">{{ $tenant->id }}</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-1/3 text-sm font-medium text-gray-500">Name</dt>
                        <dd class="w-2/3 text-sm text-gray-900">{{ $tenant->name }}</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-1/3 text-sm font-medium text-gray-500">Domain</dt>
                        <dd class="w-2/3 text-sm text-gray-900">{{ $tenant->domain ?? 'N/A' }}</dd>
                    </div>
                    <div class="flex items-center">
                        <dt class="w-1/3 text-sm font-medium text-gray-500">Status</dt>
                        <dd class="w-2/3 text-sm text-gray-900">
                            @if($tenant->active)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Active
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Inactive
                                </span>
                            @endif
                        </dd>
                    </div>
                    <div class="flex">
                        <dt class="w-1/3 text-sm font-medium text-gray-500">Joined Date</dt>
                        <dd class="w-2/3 text-sm text-gray-900">{{ $tenant->created_at->format('Y-m-d H:i:s') }}</dd>
                    </div>
                </dl>
            </div>

            <div>
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Technical Information</h3>
                <dl class="space-y-2">
                    <div class="flex">
                        <dt class="w-1/3 text-sm font-medium text-gray-500">API Key</dt>
                        <dd class="w-2/3 text-sm text-gray-900 break-all">{{ $tenant->api_key ?? 'N/A' }}</dd>
                    </div>
                    {{-- Add other technical details if needed --}}
                </dl>
            </div>
        </div>

        <div class="mt-8 border-t pt-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Subscription History</h3>
            @if($tenant->subscriptions && $tenant->subscriptions->count() > 0)
                <div class="overflow-x-auto bg-white shadow-sm rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Plan Name</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start Date</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">End Date</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($tenant->subscriptions as $subscription)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $subscription->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $subscription->plan->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ \Carbon\Carbon::parse($subscription->start_date)->format('Y-m-d') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $subscription->end_date ? \Carbon\Carbon::parse($subscription->end_date)->format('Y-m-d') : 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @if($subscription->status === 'active') bg-green-100 text-green-800
                                            @elseif(in_array($subscription->status, ['cancelled', 'expired'])) bg-red-100 text-red-800
                                            @else bg-yellow-100 text-yellow-800 @endif">
                                            {{ ucfirst($subscription->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="mt-2 text-gray-500">This tenant has no subscription history.</p>
            @endif
        </div>

        <div class="mt-8 border-t pt-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Tenant Settings</h3>
            @if($tenant->settings && $tenant->settings->count() > 0)
                <div class="overflow-x-auto bg-white shadow-sm rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Key</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Value</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Group</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($tenant->settings as $setting)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $setting->key }}</td>
                                    <td class="px-6 py-4 whitespace-pre-wrap text-sm text-gray-700 break-all">{{ $setting->value }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $setting->group }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="mt-2 text-gray-500">This tenant has no specific settings.</p>
            @endif
        </div>

    </div>
</div>
