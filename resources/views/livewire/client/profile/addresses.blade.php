<div>
    <div class="d-flex justify-content-between align-items-center mb_20">
        <h5 class="fw-6 m-0">My Addresses</h5>
        <button class="tf-btn btn-fill animate-hover-btn rounded-0 justify-content-center" wire:click="resetForm" data-bs-toggle="collapse" data-bs-target="#addressForm">Add New</button>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row g-3 mb_20">
        @forelse($addresses as $addr)
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <span class="badge bg-light text-dark">{{ ucfirst($addr->type) }}</span>
                                @if($addr->is_default)
                                    <span class="badge bg-success ms-1">Default</span>
                                @endif
                            </div>
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-outline-secondary" wire:click="edit({{ $addr->id }})" data-bs-toggle="collapse" data-bs-target="#addressForm">Edit</button>
                                <button class="btn btn-sm btn-outline-danger" wire:click="delete({{ $addr->id }})">Delete</button>
                            </div>
                        </div>
                        <div class="fw-6">{{ $addr->name }}</div>
                        @if($addr->company)
                            <div class="text-muted small">{{ $addr->company }}</div>
                        @endif
                        <div class="mt-2">{{ $addr->address_line_1 }}</div>
                        @if($addr->address_line_2)
                            <div>{{ $addr->address_line_2 }}</div>
                        @endif
                        <div>{{ $addr->city }}, {{ $addr->state }} {{ $addr->postal_code }}</div>
                        <div class="text-muted">{{ $addr->country }}</div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center p-4 border rounded bg-light">
                    <p class="mb-2">You have no saved addresses.</p>
                    <button class="tf-btn btn-fill animate-hover-btn rounded-0 justify-content-center" wire:click="resetForm" data-bs-toggle="collapse" data-bs-target="#addressForm">Add your first address</button>
                </div>
            </div>
        @endforelse
    </div>

    <div class="collapse" id="addressForm">
        <div class="card card-body">
            <h6 class="fw-6 mb_12">{{ $editingId ? 'Edit Address' : 'Add Address' }}</h6>
            <form wire:submit.prevent="save" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Type</label>
                    <select class="form-select" wire:model="type">
                        <option value="shipping">Shipping</option>
                        <option value="billing">Billing</option>
                        <option value="both">Both</option>
                    </select>
                    @error('type') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-8">
                    <label class="form-label">Full Name</label>
                    <input type="text" class="form-control" wire:model.defer="name">
                    @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Company (optional)</label>
                    <input type="text" class="form-control" wire:model.defer="company">
                    @error('company') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Address Line 1</label>
                    <input type="text" class="form-control" wire:model.defer="address_line_1">
                    @error('address_line_1') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Address Line 2 (optional)</label>
                    <input type="text" class="form-control" wire:model.defer="address_line_2">
                    @error('address_line_2') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">City</label>
                    <input type="text" class="form-control" wire:model.defer="city">
                    @error('city') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">State/Region</label>
                    <input type="text" class="form-control" wire:model.defer="state">
                    @error('state') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Postal Code</label>
                    <input type="text" class="form-control" wire:model.defer="postal_code">
                    @error('postal_code') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Country</label>
                    <input type="text" class="form-control" wire:model.defer="country">
                    @error('country') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_default" wire:model="is_default">
                        <label class="form-check-label" for="is_default">Set as default for selected type</label>
                    </div>
                </div>
                <div class="col-12 d-flex justify-content-end gap-2">
                    <button type="button" class="tf-btn btn-fill animate-hover-btn rounded-0 justify-content-center" data-bs-toggle="collapse" data-bs-target="#addressForm">Cancel</button>
                    <button type="submit" class="tf-btn btn-fill animate-hover-btn rounded-0 justify-content-center">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
