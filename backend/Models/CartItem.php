<?php
namespace Models;

class CartItem
{
    private int $productId;
    private float $price;
    private int $quantity;
    private string $name;
    private string $image;

    public function __construct(int $productId, float $price, int $quantity, string $name = '', string $image = '')
    {
        $this->productId = $productId;
        $this->price = $price;
        $this->quantity = $quantity;
        $this->name = $name;
        $this->image = $image;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function increaseQuantity(int $amount): void
    {
        $this->quantity += $amount;
    }

    public function updateQuantity(int $amount): void
    {
        $this->quantity = $amount;
    }

    public function getTotal(): float
    {
        return $this->price * $this->quantity;
    }
}
?>
