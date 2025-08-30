<?php
namespace App\Livewire\Client\Profile;

use AllowDynamicProperties;
use App\Models\UserAddress;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

//#[Layout('components.app-layout')]
#[AllowDynamicProperties]
class Addresses extends Component
{
    public $addresses = [];
    public $showForm = false;
    public $successMessage = null;

    // Form fields
    public $type = 'shipping';
    public $name;
    public $company;
    public $address_line_1;
    public $address_line_2;
    public $city;
    public $state;
    public $postal_code;
    public $country;
    public $is_default = false;

    public $editingId = null;

    protected function rules()
    {
        return [
            'type' => 'required|in:shipping,billing,both',
            'name' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'is_default' => 'boolean',
        ];
    }

    public function mount()
    {
        $this->loadAddresses();
    }

    public function loadAddresses()
    {
        $this->addresses = Auth::user()->addresses()->get();
    }

    public function showNewAddressForm()
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit($id)
    {
        $address = Auth::user()->addresses()->findOrFail($id);
        $this->editingId = $address->id;
        $this->type = $address->type;
        $this->name = $address->name;
        $this->company = $address->company;
        $this->address_line_1 = $address->address_line_1;
        $this->address_line_2 = $address->address_line_2;
        $this->city = $address->city;
        $this->state = $address->state;
        $this->postal_code = $address->postal_code;
        $this->country = $address->country;
        $this->is_default = (bool) $address->is_default;
        $this->resetErrorBag();
        $this->showForm = true;
    }

    public function resetForm()
    {
        $this->editingId = null;
        $this->type = 'shipping';
        $this->name = $this->company = $this->address_line_1 = $this->address_line_2 = $this->city = $this->state = $this->postal_code = $this->country = '';
        $this->is_default = false;
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function save()
    {
        $this->validate($this->rules());

        $user = Auth::user();

        if ($this->is_default) {
            $user->addresses()
                ->where('type', $this->type)
                ->orWhere('type', 'both')
                ->update(['is_default' => false]);
        }

        $data = [
            'type' => $this->type,
            'name' => $this->name,
            'company' => $this->company ?: null,
            'address_line_1' => $this->address_line_1,
            'address_line_2' => $this->address_line_2 ?: null,
            'city' => $this->city,
            'state' => $this->state,
            'postal_code' => $this->postal_code,
            'country' => $this->country,
            'is_default' => (bool) $this->is_default,
        ];

        if ($this->editingId) {
            $address = $user->addresses()->findOrFail($this->editingId);

            if ($this->is_default) {
                $user->addresses()
                    ->where('type', $this->type)
                    ->orWhere('type', 'both')
                    ->where('id', '!=', $address->id)
                    ->update(['is_default' => false]);
            }

            $address->update($data);
            $this->successMessage = 'Address updated successfully!';
        } else {
            $user->addresses()->create($data);
            $this->successMessage = 'Address added successfully!';
        }

        $this->resetForm();
        $this->loadAddresses();
        $this->showForm = false;
    }

    public function delete($id)
    {
        $address = Auth::user()->addresses()->findOrFail($id);
        $address->delete();
        $this->successMessage = 'Address deleted successfully!';
        $this->loadAddresses();
    }

    public function render()
    {
        return view('livewire.client.profile.addresses');
    }
}
