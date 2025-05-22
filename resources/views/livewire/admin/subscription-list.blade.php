<div class="container mx-auto py-8">
    <h1 class="text-3xl font-bold mb-8 text-gray-800">Subscription Management</h1>

    <!-- Filters -->
    <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label for="filterStatus" class="block text-sm font-medium text-gray-700">Status</label>
            <select wire:model.live="filterStatus" id="filterStatus"
                    class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <option value="">Any Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
                <option value="cancelled">Cancelled</option>
                <option value="expired">Expired</option>
                {{-- Add other distinct statuses if needed --}}
            </select>
        </div>
        <div>
            <label for="filterTenantId" class="block text-sm font-medium text-gray-700">Tenant</label>
            <select wire:model.live="filterTenantId" id="filterTenantId"
                    class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <option value="">Any Tenant</option>
                @foreach($tenants as $tenant)
                    <option value="{{ $tenant->id }}">{{ $tenant->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="filterPlanId" class="block text-sm font-medium text-gray-700">Plan</label>
            <select wire:model.live="filterPlanId" id="filterPlanId"
                    class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <option value="">Any Plan</option>
                @foreach($plans as $plan)
                    <option value="{{ $plan->id }}">{{ $plan->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Subscriptions Table -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tenant Name</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Plan Name</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start Date</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">End Date</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($subscriptions as $subscription)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $subscription->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $subscription->tenant->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $subscription->plan->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ \Carbon\Carbon::parse($subscription->start_date)->format('M d, Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $subscription->end_date ? \Carbon\Carbon::parse($subscription->end_date)->format('M d, Y') : 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if($subscription->status === 'active') bg-green-100 text-green-800
                                    @elseif(in_array($subscription->status, ['cancelled', 'expired'])) bg-red-100 text-red-800
                                    @else bg-yellow-100 text-yellow-800 @endif">
                                    {{ ucfirst($subscription->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-1">
                                @if(!in_array($subscription->status, ['cancelled', 'expired']))
                                    @if($subscription->status !== 'active')
                                        <button wire:click="updateSubscriptionStatus({{ $subscription->id }}, 'active')"
                                                wire:confirm="Are you sure you want to mark this subscription as Active?"
                                                class="px-2 py-1 text-xs font-medium text-white bg-green-600 rounded hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                            Set Active
                                        </button>
                                    @endif
                                    @if($subscription->status !== 'inactive')
                                        <button wire:click="updateSubscriptionStatus({{ $subscription->id }}, 'inactive')"
                                                wire:confirm="Are you sure you want to mark this subscription as Inactive?"
                                                class="px-2 py-1 text-xs font-medium text-white bg-gray-500 rounded hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2">
                                            Set Inactive
                                        </button>
                                    @endif
                                    @if($subscription->status !== 'cancelled')
                                        <button wire:click="updateSubscriptionStatus({{ $subscription->id }}, 'cancelled')"
                                                wire:confirm="Are you sure you want to mark this subscription as Cancelled?"
                                                class="px-2 py-1 text-xs font-medium text-white bg-red-600 rounded hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                            Set Cancelled
                                        </button>
                                    @endif
                                @else
                                    <span class="text-xs text-gray-500">No actions</span>
                                @endif
                                <a href="{{ route('admin.subscriptions.show', $subscription->id) }}"
                                   class="ml-2 px-2 py-1 text-xs font-medium text-white bg-blue-600 rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                    Details
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">No subscriptions found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if ($subscriptions->hasPages())
        <div class="mt-8">
            {{ $subscriptions->links() }}
        </div>
    @endif
</div>
