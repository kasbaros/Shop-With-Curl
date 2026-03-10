<?php

namespace App\Livewire\Components;

use App\Services\CompareService;
use Livewire\Attributes\On;
use Livewire\Component;

class CompareModal extends Component
{
    public bool $showModal = false;

    #[On('compare:toggle')]
    public function toggle($id = null)
    {
        $productId = is_array($id) ? (int) ($id['id'] ?? $id['productId'] ?? 0) : (int) $id;
        if (!$productId) return;

        $compareService = app(CompareService::class);

        if ($compareService->isInCompare($productId)) {
            $compareService->remove($productId);
            $this->dispatch('notify', ['message' => 'Product removed from comparison!', 'type' => 'success']);
        } else {
            if (!$compareService->canAdd()) {
                $this->dispatch('notify', ['message' => 'Maximum ' . $compareService->getMaxItems() . ' products allowed for comparison.', 'type' => 'error']);
                return;
            }
            $compareService->add($productId);
            $this->dispatch('notify', ['message' => 'Product added to comparison!', 'type' => 'success']);
        }

        $this->showModal = true;
        $this->dispatch('compare:updated', count: $compareService->getCount());
    }

    #[On('compare:show')]
    public function showCompareModal()
    {
        $this->showModal = true;
    }

    #[On('compare:clear')]
    public function clear()
    {
        app(CompareService::class)->clear();
        $this->showModal = false;
        $this->dispatch('compare:updated', count: 0);
        $this->dispatch('notify', ['message' => 'Comparison cleared!', 'type' => 'success']);
    }

    public function remove(int $productId): void
    {
        $compareService = app(CompareService::class);
        $compareService->remove($productId);
        $this->dispatch('compare:updated', count: $compareService->getCount());
        $this->dispatch('notify', ['message' => 'Product removed from comparison.', 'type' => 'success']);

        if ($compareService->isEmpty()) {
            $this->showModal = false;
        }
    }

    public function closeModal(): void
    {
        $this->showModal = false;
    }

    public function getCompareProductsProperty()
    {
        return app(CompareService::class)->getProducts();
    }

    public function getCompareCountProperty()
    {
        return app(CompareService::class)->getCount();
    }

    public function render()
    {
        return view('livewire.components.compare-modal');
    }
}
