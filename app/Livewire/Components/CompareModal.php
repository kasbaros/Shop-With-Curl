<?php
//
//namespace App\Livewire\Components;
//
//use App\Models\Product;
//use Livewire\Attributes\On;
//use Livewire\Component;
//
//class CompareModal extends Component
//{
//    public bool $showModal = false;
//    public array $items = [];
//    public string $message = '';
//
//    // No constructor needed - Livewire handles initialization
//
//    #[On('compare:toggle')]
//    public function toggle($productId = null)
//    {
//        $id = is_array($productId) ? ($productId['id'] ?? null) : $productId;
//        if (!$id) return;
//
//        $compare = session()->get('compare', []);
//
//        if (in_array($id, $compare)) {
//            // remove
//            $compare = array_values(array_filter($compare, fn($pid) => (int)$pid !== (int)$id));
//            $this->message = 'Removed from compare.';
//        } else {
//            // limit list size to 4 (common compare limit)
//            $compare[] = (int)$id;
//            $compare = array_values(array_unique($compare));
//            if (count($compare) > 4) {
//                // keep the most recent 4
//                $compare = array_slice($compare, -4);
//            }
//            $this->message = 'Added to compare.';
//        }
//
//        session()->put('compare', $compare);
//
//        $this->loadItems();
//        $this->showModal = true;
//
//        // Let header widgets (if any) update badging/counters
//        $this->dispatch('compare:updated', ['count' => count($compare)]);
//    }
//
//    public function remove(int $productId): void
//    {
//        $compare = session()->get('compare', []);
//        $compare = array_values(array_filter($compare, fn($pid) => (int)$pid !== (int)$productId));
//        session()->put('compare', $compare);
//        $this->loadItems();
//        $this->dispatch('compare:updated', ['count' => count($compare)]);
//    }
//
//    public function clear(): void
//    {
//        session()->forget('compare');
//        $this->loadItems();
//        $this->dispatch('compare:updated', ['count' => 0]);
//    }
//
//    public function closeModal(): void
//    {
//        $this->showModal = false;
//    }
//
//    private function loadItems(): void
//    {
//        $ids = session()->get('compare', []);
//        $this->items = Product::with('media')
//            ->whereIn('id', $ids)
//            ->get()
//            ->map(fn($p) => [
//                'id' => $p->id,
//                'name' => $p->name,
//                'slug' => $p->slug,
//                'price' => $p->sale_price ?? $p->price,
//                'img' => ($p->getFirstMediaUrl('images', 'thumb') ?: $p->getFirstMediaUrl('images')) ?? asset('images/placeholder-product.jpg'),
//            ])->toArray();
//    }
//
//    public function render()
//    {
//        // ensure items reflect session when first mounted
//        if (empty($this->items)) {
//            $this->loadItems();
//        }
//        return view('livewire.components.compare-modal');
//    }
//}


    namespace App\Livewire\Components;

    use App\Models\Product;
    use Livewire\Attributes\On;
    use Livewire\Component;

    class CompareModal extends Component
    {
        public bool $showModal = false;
        public array $items = [];
        public string $message = '';

        #[On('compare:toggle')]
        public function toggle($productId = null)
        {
            $id = is_array($productId) ? ($productId['id'] ?? null) : $productId;
            if (!$id) return;

            $compare = session()->get('compare', []);

            if (in_array($id, $compare)) {
                $compare = array_values(array_filter($compare, fn($pid) => (int)$pid !== (int)$id));
                $this->message = 'Removed from compare.';
            } else {
                $compare[] = (int)$id;
                $compare = array_values(array_unique($compare));
                if (count($compare) > 4) {
                    $compare = array_slice($compare, -4);
                }
                $this->message = 'Added to compare.';
            }

            session()->put('compare', $compare);
            $this->loadItems();
            $this->showModal = true;
            $this->dispatch('compare:updated', ['count' => count($compare)]);
        }

        public function remove(int $productId): void
        {
            $compare = session()->get('compare', []);
            $compare = array_values(array_filter($compare, fn($pid) => (int)$pid !== (int)$productId));
            session()->put('compare', $compare);
            $this->loadItems();
            $this->dispatch('compare:updated', ['count' => count($compare)]);
        }

        public function clear(): void
        {
            session()->forget('compare');
            $this->loadItems();
            $this->dispatch('compare:updated', ['count' => 0]);
        }

        public function closeModal(): void
        {
            $this->showModal = false;
        }

        private function loadItems(): void
        {
            $ids = session()->get('compare', []);
            $this->items = Product::with('media')
                ->whereIn('id', $ids)
                ->get()
                ->map(fn($p) => [
                    'id' => $p->id,
                    'name' => $p->name,
                    'slug' => $p->slug,
                    'price' => $p->sale_price ?? $p->price,
                    'image' => ($p->getFirstMediaUrl('images', 'thumb') ?: $p->getFirstMediaUrl('images')) ?? asset('images/placeholder-product.jpg'),
                ])->toArray();
        }

        public function render()
        {
            if (empty($this->items)) {
                $this->loadItems();
            }
            return view('livewire.components.compare-modal');
        }
    }
