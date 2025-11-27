<?php
namespace Repositories;

use Models\Cart;

class SessionCartRepository
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
    
    public function clear(): void
    {
        if (isset($_SESSION['cart'])) {
            unset($_SESSION['cart']);
        }
    }
}
