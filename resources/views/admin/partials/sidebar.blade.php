<aside class="w-64 h-screen p-4 bg-gray-800 text-white">
    <h2 class="mb-6 text-xl font-semibold">Admin Menu</h2>
    <nav>
        <ul>
            <li class="mb-2">
                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 rounded hover:bg-gray-700">
                    Dashboard
                </a>
            </li>
            <li class="mb-2">
                <a href="{{ route('admin.tenants.index') }}" class="block px-4 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('admin.tenants.index') ? 'bg-gray-700' : '' }}">
                    Tenants
                </a>
            </li>
            <li class="mb-2">
                <a href="{{ route('admin.subscriptions.index') }}" class="block px-4 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('admin.subscriptions.index') ? 'bg-gray-700' : '' }}">
                    Subscriptions
                </a>
            </li>
            <li class="mb-2">
                <a href="{{ route('admin.admin_users.index') }}" class="block px-4 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('admin.admin_users.index') ? 'bg-gray-700' : '' }}">
                    Admin Users
                </a>
            </li>
            <li class="mb-2">
                <a href="#" class="block px-4 py-2 rounded hover:bg-gray-700">
                    Settings
                </a>
            </li>
            <!-- Add more admin links here -->
        </ul>
    </nav>
</aside>
