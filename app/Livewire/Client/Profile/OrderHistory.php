<?php

    namespace App\Livewire\Client\Profile;

    use Illuminate\Support\Facades\Auth;
    use Livewire\Attributes\Layout;
    use Livewire\Attributes\Title;
    use Livewire\Component;
    use Livewire\WithPagination;

    #[Title('Order History - ShopWithCarl')]
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
            logger('OrderHistory component is being rendered');

            // Make sure this path matches your actual view file location
            return view('livewire.client.profile.order-history');
        }
    }
