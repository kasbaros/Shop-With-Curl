<div>
    <h2 class="text-xl font-semibold mb-4">Change Password</h2>

    @if (session('success'))
        <div class="mb-4 p-3 rounded bg-green-100 text-green-700">{{ session('success') }}</div>
    @endif

    <form wire:submit.prevent="updatePassword" class="space-y-4">
        <div>
            <label class="block text-sm font-medium">Current Password</label>
            <input type="password" wire:model.defer="current_password" class="mt-1 w-full border rounded p-2">
            @error('current_password') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium">New Password</label>
                <input type="password" wire:model.defer="password" class="mt-1 w-full border rounded p-2">
                @error('password') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium">Confirm New Password</label>
                <input type="password" wire:model.defer="password_confirmation" class="mt-1 w-full border rounded p-2">
            </div>
        </div>
        <div>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Update Password</button>
        </div>
    </form>
</div>
