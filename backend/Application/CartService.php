<?php
namespace Application;

use Domain\Cart\CartItem;
use Domain\Cart\CartRepositoryInterface;
use Application\DTO\CartDto;

class CartService
{
    private CartRepositoryInterface $cartRepository;

    public function __construct(CartRepositoryInterface $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    public function addToCart(int $productId, int $quantity, float $price, string $name, string $image): CartDto
    {
        $cart = $this->cartRepository->getCart();
        $item = new CartItem($productId, $price, $quantity, $name, $image);
        $cart->addItem($item);
        $this->cartRepository->save($cart);

        return new CartDto($cart);
    }

    public function getCart(): CartDto
    {
        $cart = $this->cartRepository->getCart();
        return new CartDto($cart);
    }

    public function updateQuantity(int $productId, int $quantity): CartDto
    {
        $cart = $this->cartRepository->getCart();
        $cart->updateItem($productId, $quantity);
        $this->cartRepository->save($cart);

        return new CartDto($cart);
    }

    public function removeItem(int $productId): CartDto
    {
        $cart = $this->cartRepository->getCart();
        $cart->removeItem($productId);
        $this->cartRepository->save($cart);

        return new CartDto($cart);
    }
}
