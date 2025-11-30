<?php
namespace Services;

use Models\BookAggregate\IBookRepository;
use Repositories\CartRepository;
use Repositories\SessionCartRepository;
use Models\CartItem;
use Models\Cart;
use Exception;

class CartService
{
    private CartRepository $cartRepository;
    private IBookRepository $bookRepository;
    private SessionCartRepository $sessionRepo;

    public function __construct(CartRepository $cartRepository, IBookRepository $bookRepository, SessionCartRepository $sessionRepo)
    {
        $this->cartRepository = $cartRepository;
        $this->bookRepository = $bookRepository;
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
            return $this->cartRepository->getCart($this->getUserId());
        }
        return $this->sessionRepo->getCart();
    }

    public function addToCart(int $productId, int $quantity): void
    {
        $product = $this->bookRepository->findById($productId);
        if (!$product) {
            throw new Exception("Sản phẩm không tồn tại");
        }
        $cart = $this->getCart();

        $imgUrl = '';
        foreach ($product->getImages() as $img) {
            if ($img->isMainImage()) {
                $imgUrl = $img->getUrl();
                break;
            }
        }
        $item = new CartItem($productId, $product->getSellingPrice(), $quantity, $product->getName(), $imgUrl);
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
            $this->cartRepository->save($cart, $this->getUserId());
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

        $userCart = $this->cartRepository->getCart($userId);
        
        foreach ($guestCart->getItems() as $item) {
            $userCart->addItem($item);
        }

        $this->cartRepository->save($userCart, $userId);
        $this->sessionRepo->clear();
    }
}
