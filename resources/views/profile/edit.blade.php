@auth
<div class="container mx-auto p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="md:col-span-2">
        <livewire:client.profile.index />
    </div>
    <div class="md:col-span-1">
        <livewire:client.profile.edit-password />
    </div>
</div>
@else
<div class="container mx-auto p-6">Please login to edit your profile.</div>
@endauth
