<div>
    <div class="mb_20">
        <h5 class="fw-6 m-0">Account Details</h5>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h6 class="fw-6 mb_12">Profile Information</h6>
                    <form wire:submit.prevent="updateProfile" class="vstack gap-3">
                        <div>
                            <label class="form-label">Full Name</label>
                            <input type="text" class="form-control" wire:model.defer="name">
                            @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>
                        <div>
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" wire:model.defer="email">
                            @error('email') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="tf-btn btn-fill animate-hover-btn rounded-0 justify-content-center">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h6 class="fw-6 mb_12">Change Password</h6>
                    <form wire:submit.prevent="updatePassword" class="vstack gap-3">
                        <div>
                            <label class="form-label">Current Password</label>
                            <input type="password" class="form-control" wire:model.defer="current_password" autocomplete="current-password">
                            @error('current_password') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>
                        <div>
                            <label class="form-label">New Password</label>
                            <input type="password" class="form-control" wire:model.defer="password" autocomplete="new-password">
                            @error('password') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>
                        <div>
                            <label class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" wire:model.defer="password_confirmation" autocomplete="new-password">
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="tf-btn btn-fill animate-hover-btn rounded-0 justify-content-center">Update Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
