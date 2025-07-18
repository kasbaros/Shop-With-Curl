<?php

namespace App\Livewire\User;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class OrderHistory extends Component
{
    use WithPagination;

    public $selectedStatus = 'all';

    public function updatedSelectedStatus()
    {
        $this->resetPage();
    }

    public function getOrdersProperty()
    {
        if (!Auth::check()) {
            return collect();
        }

        return Auth::user()->orders()
            ->with(['items.product', 'items.productVariant'])
            ->when($this->selectedStatus !== 'all', function ($query) {
                $query->where('status', $this->selectedStatus);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    public function getStatusOptionsProperty()
    {
        return [
            'all' => 'All Orders',
            'pending' => 'Pending',
            'processing' => 'Processing',
            'shipped' => 'Shipped',
            'delivered' => 'Delivered',
            'cancelled' => 'Cancelled',
        ];
    }

    public function render()
    {
        return view('livewire.user.order-history');
    }
}
