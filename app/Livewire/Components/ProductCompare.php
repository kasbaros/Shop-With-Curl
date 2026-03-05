<?php

    namespace App\Livewire\Components;

    use App\Services\CompareService;
    use Livewire\Attributes\On;
    use Livewire\Component;

    class ProductCompare extends Component
    {
        public bool $showCompare = false;

        #[On('compare:toggle')]
        public function toggleCompare($id)
        {
            $productId = $id;

            if (!$productId) return;

            $compareService = app(CompareService::class); // Resolve from container

            if ($compareService->isInCompare($productId)) {
                $success = $compareService->remove($productId);
                $message = $success ? 'Product removed from comparison!' : 'Failed to remove product';
            } else {
                if (!$compareService->canAdd()) {
                    $this->dispatch('notify', [
                        'message' => 'Maximum ' . $compareService->getMaxItems() . ' products allowed for comparison',
                        'type' => 'error'
                    ]);
                    return;
                }

                $success = $compareService->add($productId);
                $message = $success ? 'Product added to comparison!' : 'Failed to add product';
            }

            if ($success) {
                $this->dispatch('notify', [
                    'message' => $message,
                    'type' => 'success'
                ]);

                $this->dispatch('compare:updated', ['count' => $compareService->getCount()]);
            }
        }

        #[On('compare:show')]
        public function showCompareModal()
        {
            $this->showCompare = true;
        }

        #[On('compare:clear')]
        public function clearCompare()
        {
            $compareService = app(CompareService::class); // Resolve from container
            $compareService->clear();

            $this->dispatch('notify', [
                'message' => 'Comparison cleared!',
                'type' => 'success'
            ]);

            $this->dispatch('compare:updated', ['count' => 0]);
            $this->showCompare = false;
        }

        public function closeCompare()
        {
            $this->showCompare = false;
        }

        public function removeFromCompare($productId)
        {
            $compareService = app(CompareService::class); // Resolve from container
            $compareService->remove($productId);
            $this->dispatch('compare:updated', ['count' => $compareService->getCount()]);

            if ($compareService->isEmpty()) {
                $this->showCompare = false;
            }
        }

        public function getCompareProductsProperty()
        {
            return app(CompareService::class)->getProducts(); // Resolve from container
        }

        public function getCompareCountProperty()
        {
            return app(CompareService::class)->getCount(); // Resolve from container
        }

        public function render()
        {
            return view('livewire.components.product-compare');
        }
    }
