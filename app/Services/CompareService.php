<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;

class CompareService
{
    private const COMPARE_SESSION_KEY = 'product_compare';
    private const MAX_COMPARE_ITEMS = 4;

    public function add(int $productId): bool
    {
        $product = Product::find($productId);

        if (!$product || !$product->is_active || $product->status !== 'published') {
            return false;
        }

        $compareList = $this->getCompareList();

        // Check if already in comparison
        if (in_array($productId, $compareList)) {
            return false;
        }

        // Check maximum limit
        if (count($compareList) >= self::MAX_COMPARE_ITEMS) {
            return false;
        }

        $compareList[] = $productId;
        Session::put(self::COMPARE_SESSION_KEY, $compareList);

        return true;
    }

    public function remove(int $productId): bool
    {
        $compareList = $this->getCompareList();
        $key = array_search($productId, $compareList);

        if ($key === false) {
            return false;
        }

        unset($compareList[$key]);
        Session::put(self::COMPARE_SESSION_KEY, array_values($compareList));

        return true;
    }

    public function clear(): void
    {
        Session::forget(self::COMPARE_SESSION_KEY);
    }

    public function getCompareList(): array
    {
        return Session::get(self::COMPARE_SESSION_KEY, []);
    }

    public function getProducts(): Collection
    {
        $productIds = $this->getCompareList();

        if (empty($productIds)) {
            return collect();
        }

        return Product::whereIn('id', $productIds)
            ->active()
            ->with(['categories', 'media'])
            ->get();
    }

    public function getCount(): int
    {
        return count($this->getCompareList());
    }

    public function isEmpty(): bool
    {
        return empty($this->getCompareList());
    }

    public function isInCompare(int $productId): bool
    {
        return in_array($productId, $this->getCompareList());
    }

    public function canAdd(): bool
    {
        return count($this->getCompareList()) < self::MAX_COMPARE_ITEMS;
    }

    public function getMaxItems(): int
    {
        return self::MAX_COMPARE_ITEMS;
    }
}
