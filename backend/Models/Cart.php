<?php
namespace Models;

use Exception;

class Cart
{
    /** @var CartItem[] */
    private array $items = [];

    public function addItem(CartItem $newItem): void
    {
        if ($newItem->getQuantity() <= 0) {
            throw new Exception("Quantity must be positive");
        }

        $productId = $newItem->getProductId();

        if (isset($this->items[$productId])) {
            $this->items[$productId]->increaseQuantity($newItem->getQuantity());
        } else {
            $this->items[$productId] = $newItem;
        }
    }

    public function updateItem(int $productId, int $quantity): void
    {
        if ($quantity <= 0) {
            $this->removeItem($productId);
            return;
        }

        if (isset($this->items[$productId])) {
            $this->items[$productId]->updateQuantity($quantity);
        } else {
            throw new Exception("Product not found in cart");
        }
    }

    public function removeItem(int $productId): void
    {
        if (isset($this->items[$productId])) {
            unset($this->items[$productId]);
        }
    }

    /**
     * @return CartItem[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    public function getTotalPrice(): float
    {
        $total = 0;
        foreach ($this->items as $item) {
            $total += $item->getTotal();
        }
        return $total;
    }

    public function getTotalQuantity(): int
    {
        $count = 0;
        foreach ($this->items as $item) {
            $count += $item->getQuantity();
        }
        return $count;
    }
    
    public function isEmpty(): bool
    {
        return empty($this->items);
    }
}
