<?php
namespace Application\DTO;

use Domain\Cart\Cart;

class CartDto
{
    public array $items = [];
    public float $totalPrice;
    public int $totalQuantity;

    public function __construct(Cart $cart)
    {
        $this->totalPrice = $cart->getTotalPrice();
        $this->totalQuantity = $cart->getTotalQuantity();

        foreach ($cart->getItems() as $item) {
            $this->items[] = [
                'productId' => $item->getProductId(),
                'name' => $item->getName(),
                'price' => $item->getPrice(),
                'quantity' => $item->getQuantity(),
                'image' => $item->getImage(),
                'total' => $item->getTotal()
            ];
        }
    }

    public function toArray(): array
    {
        return [
            'items' => $this->items,
            'totalPrice' => $this->totalPrice,
            'totalQuantity' => $this->totalQuantity
        ];
    }
}
