<x-layouts.auth.card>
    @if ($errors->any())
        <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4">
            <div class="flex items-center gap-2">
                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <h3 class="text-sm font-medium text-red-800">Please fix the following errors:</h3>
            </div>
            <div class="mt-2 text-sm text-red-700">
                <ul class="list-inside list-disc space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="flex flex-col gap-6">
        <div class="flex flex-col items-center gap-2">
            <h1 class="text-2xl font-semibold tracking-tight text-gray-900">Register Your Space</h1>
            <p class="text-sm text-gray-600">Create a new flow for your space</p>
        </div>

        <form method="POST" action="{{ route('register.tenant.store') }}" class="flex flex-col gap-4">
            @csrf

            <!-- Space Name -->
            <div class="flex flex-col gap-1.5">
                <label for="name" class="flex items-center gap-1 text-sm font-medium text-gray-700">
                    Space Name
                    <span class="text-red-500">*</span>
                </label>
                <input
                    type="text"
                    name="name"
                    id="name"
                    value="{{ old('name') }}"
                    class="block w-full rounded-lg border {{ $errors->has('name') ? 'border-red-300 bg-red-50' : 'border-gray-300 bg-white' }} px-4 py-2.5 text-gray-900 placeholder:text-gray-500 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                    placeholder="Enter your space name"
                    required
                    autofocus
                />
                @error('name')
                    <p class="flex items-center gap-1 text-sm text-red-600">
                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Domain -->
            <div class="flex flex-col gap-1.5">
                <label for="domain" class="flex items-center gap-1 text-sm font-medium text-gray-700">
                    Domain
                    <span class="text-red-500">*</span>
                </label>
                <div class="flex rounded-lg border {{ $errors->has('domain') ? 'border-red-300 bg-red-50' : 'border-gray-300 bg-white' }} focus-within:border-blue-500 focus-within:ring-1 focus-within:ring-blue-500">
                    <input
                        type="text"
                        name="domain"
                        id="domain"
                        value="{{ old('domain') }}"
                        class="block w-full rounded-l-lg border-0 bg-transparent px-4 py-2.5 text-gray-900 placeholder:text-gray-500 focus:outline-none"
                        placeholder="your-space"
                        required
                    />
                    <span class="inline-flex items-center rounded-r-lg border-0 border-l border-gray-300 bg-gray-50 px-4 text-gray-500">.wtrhub.com</span>
                </div>
                @error('domain')
                    <p class="flex items-center gap-1 text-sm text-red-600">
                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
                <p class="text-xs text-gray-500">This will be your workspace URL</p>
            </div>

            <!-- Admin Information -->
            <div class="space-y-4 rounded-lg border {{ $errors->hasAny(['admin_name', 'admin_email', 'admin_password']) ? 'border-red-200 bg-red-50' : 'border-gray-200 bg-gray-50' }} p-4">
                <h2 class="text-lg font-medium text-gray-900">Admin Account</h2>

                <!-- Admin Name -->
                <div class="flex flex-col gap-1.5">
                    <label for="admin_name" class="flex items-center gap-1 text-sm font-medium text-gray-700">
                        Full Name
                        <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        name="admin_name"
                        id="admin_name"
                        value="{{ old('admin_name') }}"
                        class="block w-full rounded-lg border {{ $errors->has('admin_name') ? 'border-red-300 bg-red-50' : 'border-gray-300 bg-white' }} px-4 py-2.5 text-gray-900 placeholder:text-gray-500 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                        placeholder="John Doe"
                        required
                    />
                    @error('admin_name')
                        <p class="flex items-center gap-1 text-sm text-red-600">
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Admin Email -->
                <div class="flex flex-col gap-1.5">
                    <label for="admin_email" class="flex items-center gap-1 text-sm font-medium text-gray-700">
                        Email Address
                        <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="email"
                        name="admin_email"
                        id="admin_email"
                        value="{{ old('admin_email') }}"
                        class="block w-full rounded-lg border {{ $errors->has('admin_email') ? 'border-red-300 bg-red-50' : 'border-gray-300 bg-white' }} px-4 py-2.5 text-gray-900 placeholder:text-gray-500 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                        placeholder="john@example.com"
                        required
                    />
                    @error('admin_email')
                        <p class="flex items-center gap-1 text-sm text-red-600">
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Admin Password -->
                <div class="flex flex-col gap-1.5">
                    <label for="admin_password" class="flex items-center gap-1 text-sm font-medium text-gray-700">
                        Password
                        <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="password"
                        name="admin_password"
                        id="admin_password"
                        class="block w-full rounded-lg border {{ $errors->has('admin_password') ? 'border-red-300 bg-red-50' : 'border-gray-300 bg-white' }} px-4 py-2.5 text-gray-900 placeholder:text-gray-500 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                        placeholder="••••••••"
                        required
                    />
                    @error('admin_password')
                        <p class="flex items-center gap-1 text-sm text-red-600">
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                    <p class="text-xs text-gray-500">Must be at least 8 characters long</p>
                </div>
            </div>

            <button type="submit" class="inline-flex w-full items-center justify-center rounded-lg bg-blue-500 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                Create Space
            </button>
        </form>

        <p class="text-center text-sm text-gray-600">
            Already have an account?
            <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-700">
                Sign in
            </a>
        </p>
    </div>
</x-layouts.auth.card>
