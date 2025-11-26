<?php
namespace Domain\Cart;

interface CartRepositoryInterface
{
    public function save(Cart $cart): void;
    public function getCart(): Cart;
}
