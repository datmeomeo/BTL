<?php
namespace Infrastructure\Persistence;

use Domain\Cart\Cart;
use Domain\Cart\CartRepositoryInterface;

class SessionCartRepository implements CartRepositoryInterface
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function save(Cart $cart): void
    {
        $_SESSION['cart'] = serialize($cart);
    }

    public function getCart(): Cart
    {
        if (isset($_SESSION['cart'])) {
            $cart = unserialize($_SESSION['cart']);
            if ($cart instanceof Cart) {
                return $cart;
            }
        }
        return new Cart();
    }
}
