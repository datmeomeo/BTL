<?php
namespace Controllers;

use Core\BaseController;
use Services\CartService;
use Exception;

class CartController extends BaseController
{
    private CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function handleRequest(string $action)
    {
        try {
            switch ($action) {
                case 'add':
                    $this->addToCart();
                    break;
                case 'get':
                    $this->getCart('Lấy thông tin giỏ hàng thành công');
                    break;
                case 'update':
                    $this->updateQuantity();
                    break;
                case 'remove':
                    $this->removeItem();
                    break;
                default:
                    throw new Exception("Action not found");
            }
        } catch (Exception $e) {
            $this->jsonResponse(['status' => 'error', 'message' => $e->getMessage()], 400);
        }
    }

    private function addToCart()
    {
        // Support both JSON and FormData
        $productId = $this->getInput('productId');
        $quantity = $this->getInput('quantity', 1);

        $this->cartService->addToCart(
            (int)$productId,
            (int)$quantity,
        );

        $this->getCart('Thêm vào giỏ hàng thành công'); // Return updated cart
    }

    private function getCart(string $message = '')
    {
        // 1. Lấy dữ liệu giỏ hàng từ Service
        // Nếu Service hoặc Repository bị lỗi cú pháp, dòng này sẽ dừng chương trình.
        // Hãy đảm bảo bạn đã undo (hoàn tác) các sửa đổi phức tạp bên Service/Repo nếu có.
        $cart = $this->cartService->getCart();
        
        $data = [
            'items' => [],
            'totalQuantity' => $cart->getTotalQuantity(),
            'totalPrice' => $cart->getTotalPrice()
        ];

        foreach ($cart->getItems() as $item) {
            $rawImg = $item->getImage(); 
            $finalImg = './assets/img/fahasa-logo.jpg'; 

            if (!empty($rawImg)) {
                if (strpos($rawImg, 'assets/') === false) {
                    $finalImg = './assets/img-book/' . ltrim($rawImg, '/');
                } else {
                    $finalImg = $rawImg;
                }
            }

            $data['items'][] = [
                'productId' => $item->getProductId(),
                'name'      => $item->getName(),
                'price'     => $item->getPrice(),
                'quantity'  => $item->getQuantity(),
                'image'     => $finalImg, 
                'total'     => $item->getTotal()
            ];
        }
        $this->jsonResponse([
            'status'=> 'success',
            'message'=> $message,
            'data'=> $data
        ]);
    }
    private function updateQuantity()
    {
        $productId = $this->getInput('productId');
        $quantity = $this->getInput('quantity');

        $this->cartService->updateQuantity((int)$productId, (int)$quantity);
        $this->getCart('Cập nhật số lượng thành công');
    }

    private function removeItem()
    {
        $productId = $this->getInput('productId');
        $this->cartService->removeItem((int)$productId);
        $this->getCart('Xóa sản phẩm thành công');
    }
}