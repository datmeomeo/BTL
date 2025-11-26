<?php
namespace Api;

use Application\CartService;
use Exception;

class CartController
{
    private CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function handleRequest(string $action): string
    {
        $method = $_SERVER['REQUEST_METHOD'];

        switch ($action) {
            case 'add':
                if ($method !== 'POST') throw new Exception("Method not allowed");
                return $this->addToCart();
            case 'get':
                if ($method !== 'GET') throw new Exception("Method not allowed");
                return $this->getCart();
            case 'update':
                if ($method !== 'POST') throw new Exception("Method not allowed"); // Using POST for simplicity, or PATCH
                return $this->updateCart();
            case 'remove':
                if ($method !== 'POST') throw new Exception("Method not allowed"); // Using POST for simplicity, or DELETE
                return $this->removeFromCart();
            default:
                throw new Exception("Action not found");
        }
    }

    private function addToCart(): string
    {
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Fallback to $_POST if json is empty (for simple form submits if any)
        if (!$data) $data = $_POST;

        $productId = (int)($data['productId'] ?? 0);
        $quantity = (int)($data['quantity'] ?? 1);
        $price = (float)($data['price'] ?? 0);
        $name = $data['name'] ?? '';
        $image = $data['image'] ?? '';

        if ($productId <= 0) throw new Exception("Invalid Product ID");

        $cartDto = $this->cartService->addToCart($productId, $quantity, $price, $name, $image);
        return json_encode(['status' => 'success', 'data' => $cartDto->toArray()]);
    }

    private function getCart(): string
    {
        $cartDto = $this->cartService->getCart();
        return json_encode(['status' => 'success', 'data' => $cartDto->toArray()]);
    }

    private function updateCart(): string
    {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data) $data = $_POST;

        $productId = (int)($data['productId'] ?? 0);
        $quantity = (int)($data['quantity'] ?? 1);

        $cartDto = $this->cartService->updateQuantity($productId, $quantity);
        return json_encode(['status' => 'success', 'data' => $cartDto->toArray()]);
    }

    private function removeFromCart(): string
    {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data) $data = $_POST;

        $productId = (int)($data['productId'] ?? 0);

        $cartDto = $this->cartService->removeItem($productId);
        return json_encode(['status' => 'success', 'data' => $cartDto->toArray()]);
    }
}
