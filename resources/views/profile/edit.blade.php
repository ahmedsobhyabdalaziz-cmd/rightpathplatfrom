<x-app-layout>
    <x-slot name="title">Profile Settings - RightPath LMS</x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-slate-900 mb-8">Profile Settings</h1>

            @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl">
                {{ session('success') }}
            </div>
            @endif

            <!-- Update Profile -->
            <div class="bg-white rounded-xl border border-slate-200 p-6 mb-6">
                <h2 class="text-lg font-semibold text-slate-900 mb-6">Update Profile</h2>
                
                <form method="POST" action="{{ route('profile.update') }}" class="space-y-6">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-700 mb-2">Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                            class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700 mb-2">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                            class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <hr class="border-slate-200">

                    <div>
                        <label for="current_password" class="block text-sm font-medium text-slate-700 mb-2">Current Password</label>
                        <input type="password" name="current_password" id="current_password"
                            class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 @error('current_password') border-red-500 @enderror"
                            placeholder="Enter current password to change it">
                        @error('current_password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-700 mb-2">New Password</label>
                        <input type="password" name="password" id="password"
                            class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 @error('password') border-red-500 @enderror"
                            placeholder="Leave blank to keep current password">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-2">Confirm New Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20">
                    </div>

                    <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg transition">
                        Save Changes
                    </button>
                </form>
            </div>

            <!-- Delete Account -->
            <div class="bg-white rounded-xl border border-red-200 p-6">
                <h2 class="text-lg font-semibold text-red-700 mb-2">Delete Account</h2>
                <p class="text-slate-600 mb-6">Once your account is deleted, all of your data will be permanently removed. This action cannot be undone.</p>
                
                <form method="POST" action="{{ route('profile.destroy') }}" onsubmit="return confirm('Are you sure you want to delete your account? This cannot be undone.')">
                    @csrf
                    @method('DELETE')

                    <div class="mb-4">
                        <label for="delete_password" class="block text-sm font-medium text-slate-700 mb-2">Enter your password to confirm</label>
                        <input type="password" name="password" id="delete_password" required
                            class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:border-red-500 focus:ring-2 focus:ring-red-500/20">
                        @error('password', 'userDeletion')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition">
                        Delete Account
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>










