<?php

declare(strict_types=1);

namespace app\components\Cart\Domain\Aggregate;

use app\components\Cart\Domain\Contract\CartRepositoryInterface;
use app\components\Cart\Domain\Contract\CartItemCollection;
use app\components\Cart\Domain\ValueObject\CartItem;

class Cart
{
    private ?CartItemCollection $items = null;

    private function __construct(
        private readonly CartRepositoryInterface $repository
    ) {
    }

    public static function create(
        CartRepositoryInterface $repository
    ): self {
        return new self(
            repository: $repository
        );
    }

    private function saveItems(CartItemCollection $items): void
    {
        $this->repository->saveAll($items);
        $this->items = $items;
    }

    public function getItems(): CartItemCollection
    {
        if (is_null($this->items)) {
            $this->items = $this->repository->getAll();
        }
        return $this->items;
    }

    public function setItem(CartItem $cartItem): void
    {  
        $items = $this->getItems();
        
        //find identical item
        foreach ($items as $i => $item) {
            if ($item->isEqualsTo($cartItem)) {
                $items[$i] = $cartItem;
                $this->saveItems($items);
                return;
            }
        }
        //or append new 
        $items[] = $cartItem;

        $this->saveItems($items);
    }

    public function removeItem(CartItem $cartItem): void
    {
        $items = $this->getItems();
        foreach ($items as $i => $item) {
            if ($item->isEqualsTo($cartItem)) {
                unset($items[$i]);
                $this->saveItems($items);
                return;
            }
        }
    }

    public function clear(): void
    {
        $this->saveItems(new CartItemCollection());
    }
}
