<div class="container mx-auto py-8">
    <form wire:submit.prevent="saveAdmin" class="bg-white shadow-md rounded-lg px-8 pt-6 pb-8 mb-4">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">
            {{ $adminId ? 'Edit Admin User' : 'Create Admin User' }}
        </h2>

        <!-- Name -->
        <div class="mb-4">
            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name</label>
            <input wire:model.defer="name" type="text" id="name" placeholder="Full Name"
                   class="shadow appearance-none border @error('name') border-red-500 @enderror rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            @error('name') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
        </div>

        <!-- Email -->
        <div class="mb-4">
            <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
            <input wire:model.defer="email" type="email" id="email" placeholder="user@example.com"
                   class="shadow appearance-none border @error('email') border-red-500 @enderror rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            @error('email') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
        </div>

        <!-- Role -->
        <div class="mb-4">
            <label for="role" class="block text-gray-700 text-sm font-bold mb-2">Role</label>
            <input wire:model.defer="role" type="text" id="role" placeholder="e.g., super-admin, editor"
                   class="shadow appearance-none border @error('role') border-red-500 @enderror rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            @error('role') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
            {{-- TODO: Consider changing to a select dropdown if roles become predefined --}}
        </div>

        <!-- Password -->
        <div class="mb-4">
            <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password</label>
            <input wire:model.defer="password" type="password" id="password" placeholder="******************"
                   class="shadow appearance-none border @error('password') border-red-500 @enderror rounded w-full py-2 px-3 text-gray-700 mb-1 leading-tight focus:outline-none focus:shadow-outline">
            @if ($adminId)
                <p class="text-gray-600 text-xs italic">Leave blank to keep current password.</p>
            @endif
            @error('password') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
        </div>

        <!-- Confirm Password -->
        <div class="mb-6">
            <label for="password_confirmation" class="block text-gray-700 text-sm font-bold mb-2">Confirm Password</label>
            <input wire:model.defer="password_confirmation" type="password" id="password_confirmation" placeholder="******************"
                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>
        
        <!-- Active Status -->
        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="isActive">
                Status
            </label>
            <div class="mt-2">
                <label class="inline-flex items-center">
                    <input type="checkbox" wire:model="isActive" id="isActive" class="form-checkbox h-5 w-5 text-indigo-600 rounded">
                    <span class="ml-2 text-gray-700">Active</span>
                </label>
            </div>
            @error('isActive') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
        </div>


        <!-- Actions -->
        <div class="flex items-center justify-between">
            <button type="submit"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                {{ $adminId ? 'Update Admin User' : 'Create Admin User' }}
            </button>
            <a href="{{ route('admin.admin_users.index') }}"
               class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                Cancel
            </a>
        </div>
    </form>
</div>
