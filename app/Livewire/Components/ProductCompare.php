<?php

namespace App\Livewire\Components;

use App\Services\CompareService;
use Livewire\Attributes\On;
use Livewire\Component;

class ProductCompare extends Component
{
    public bool $showCompare = false;
    protected CompareService $compareService;

    public function boot(CompareService $compareService)
    {
        $this->compareService = $compareService;
    }

    #[On('compare:toggle')]
    public function toggleCompare($data)
    {
        $productId = $data['id'] ?? null;

        if (!$productId) return;

        if ($this->compareService->isInCompare($productId)) {
            $success = $this->compareService->remove($productId);
            $message = $success ? 'Product removed from comparison!' : 'Failed to remove product';
        } else {
            if (!$this->compareService->canAdd()) {
                $this->dispatch('notify', [
                    'message' => 'Maximum ' . $this->compareService->getMaxItems() . ' products allowed for comparison',
                    'type' => 'error'
                ]);
                return;
            }

            $success = $this->compareService->add($productId);
            $message = $success ? 'Product added to comparison!' : 'Failed to add product';
        }

        if ($success) {
            $this->dispatch('notify', [
                'message' => $message,
                'type' => 'success'
            ]);

            $this->dispatch('compare:updated', ['count' => $this->compareService->getCount()]);
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
        $this->compareService->clear();

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
        $this->compareService->remove($productId);
        $this->dispatch('compare:updated', ['count' => $this->compareService->getCount()]);

        if ($this->compareService->isEmpty()) {
            $this->showCompare = false;
        }
    }

    public function getCompareProductsProperty()
    {
        return $this->compareService->getProducts();
    }

    public function getCompareCountProperty()
    {
        return $this->compareService->getCount();
    }

    public function render()
    {
        return view('livewire.components.product-compare');
    }
}
