<div>
    <h1 class="text-2xl font-semibold mb-4">My Profile</h1>

    @if (session('success'))
        <div class="mb-4 p-3 rounded bg-green-100 text-green-700">{{ session('success') }}</div>
    @endif

    <form wire:submit.prevent="save" class="space-y-4">
        <div>
            <label class="block text-sm font-medium">Name</label>
            <input type="text" wire:model.defer="name" class="mt-1 w-full border rounded p-2">
            @error('name') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium">Email</label>
            <input type="email" wire:model.defer="email" class="mt-1 w-full border rounded p-2">
            @error('email') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium">Phone</label>
                <input type="text" wire:model.defer="phone" class="mt-1 w-full border rounded p-2">
                @error('phone') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium">Date of Birth</label>
                <input type="date" wire:model.defer="date_of_birth" class="mt-1 w-full border rounded p-2">
                @error('date_of_birth') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium">Gender</label>
            <select wire:model.defer="gender" class="mt-1 w-full border rounded p-2">
                <option value="">Select gender</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
                <option value="other">Other</option>
            </select>
            @error('gender') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
        </div>
        <div>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Save</button>
        </div>
    </form>
</div>
