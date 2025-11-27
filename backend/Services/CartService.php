<?php
namespace Services;

use Repositories\CartRepository;
use Repositories\SessionCartRepository;
use Models\CartItem;
use Models\Cart;

class CartService
{
    private CartRepository $dbRepo;
    private SessionCartRepository $sessionRepo;

    public function __construct(CartRepository $dbRepo, SessionCartRepository $sessionRepo)
    {
        $this->dbRepo = $dbRepo;
        $this->sessionRepo = $sessionRepo;
    }

    private function isLogged(): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION['user_id']);
    }

    private function getUserId(): int
    {
        return $_SESSION['user_id'] ?? 0;
    }

    public function getCart(): Cart
    {
        if ($this->isLogged()) {
            return $this->dbRepo->getCart($this->getUserId());
        }
        return $this->sessionRepo->getCart();
    }

    public function addToCart(int $productId, int $quantity, float $price, string $name, string $image): void
    {
        $cart = $this->getCart();
        $item = new CartItem($productId, $price, $quantity, $name, $image);
        $cart->addItem($item);
        $this->saveCart($cart);
    }

    public function updateQuantity(int $productId, int $quantity): void
    {
        $cart = $this->getCart();
        $cart->updateItem($productId, $quantity);
        $this->saveCart($cart);
    }

    public function removeItem(int $productId): void
    {
        $cart = $this->getCart();
        $cart->removeItem($productId);
        $this->saveCart($cart);
    }

    private function saveCart(Cart $cart): void
    {
        if ($this->isLogged()) {
            $this->dbRepo->save($cart, $this->getUserId());
        } else {
            $this->sessionRepo->save($cart);
        }
    }

    public function mergeGuestCartToUser(int $userId): void
    {
        $guestCart = $this->sessionRepo->getCart();
        if ($guestCart->isEmpty()) {
            return;
        }

        $userCart = $this->dbRepo->getCart($userId);
        
        foreach ($guestCart->getItems() as $item) {
            $userCart->addItem($item);
        }

        $this->dbRepo->save($userCart, $userId);
        $this->sessionRepo->clear();
    }
}
