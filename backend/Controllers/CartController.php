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
                    $this->getCart();
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
        $productId = $this->getInput('product_id');
        $quantity = $this->getInput('quantity', 1);
        
        // In a real app, we might fetch price/name from DB here to be safe, 
        // or trust the client if it's just a demo. 
        // For now, let's assume we might need to fetch it or it's passed.
        // The previous implementation fetched it in the Service or Repo?
        // Actually, the previous `CartService::addToCart` took `productId` and `quantity`.
        // But here `CartService::addToCart` requires price, name, image.
        // So we should probably fetch the product details here or in the service.
        // To keep it simple and consistent with previous logic, let's assume we need to fetch it.
        // BUT, I don't have a ProductRepository ready in this plan.
        // Let's check `CartService` again. It takes `productId, quantity, price, name, image`.
        // So the Controller needs to provide these.
        // Since I don't have a ProductRepo, I will rely on the client sending them for now (as per the `tool-bookhienthi.js` logic which sends `product_id` and `quantity`).
        // WAIT: `tool-bookhienthi.js` ONLY sends `product_id` and `quantity`.
        // So I MUST fetch product details from DB.
        
        // Quick fix: I'll add a quick query here or inject a ProductRepo later.
        // For now, I'll use a direct DB query via the Database class to get product info.
        // This breaks the pattern slightly but is pragmatic for this refactor step.
        
        $db = \Core\Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM sach WHERE ma_sach = ?");
        $stmt->execute([$productId]);
        $product = $stmt->fetch();

        if (!$product) {
            throw new Exception("Sản phẩm không tồn tại");
        }

        // Get image
        $stmtImg = $db->prepare("SELECT duong_dan_hinh FROM hinh_anh_sach WHERE ma_sach = ? AND la_anh_chinh = 1");
        $stmtImg->execute([$productId]);
        $imgRow = $stmtImg->fetch();
        $image = $imgRow['duong_dan_hinh'] ?? '';

        $this->cartService->addToCart(
            (int)$productId,
            (int)$quantity,
            (float)$product['gia_ban'], // Or gia_khuyen_mai if exists
            $product['ten_sach'],
            $image
        );

        $this->getCart(); // Return updated cart
    }

    private function getCart()
    {
        $cart = $this->cartService->getCart();
        
        $data = [
            'items' => [],
            'totalQuantity' => $cart->getTotalQuantity(),
            'totalPrice' => $cart->getTotalPrice()
        ];

        foreach ($cart->getItems() as $item) {
            $data['items'][] = [
                'product_id' => $item->getProductId(),
                'name' => $item->getName(),
                'price' => $item->getPrice(),
                'quantity' => $item->getQuantity(),
                'image' => $item->getImage(),
                'total' => $item->getTotal()
            ];
        }

        $this->jsonResponse($data);
    }

    private function updateQuantity()
    {
        $productId = $this->getInput('product_id');
        $quantity = $this->getInput('quantity');

        $this->cartService->updateQuantity((int)$productId, (int)$quantity);
        $this->getCart();
    }

    private function removeItem()
    {
        $productId = $this->getInput('product_id');
        $this->cartService->removeItem((int)$productId);
        $this->getCart();
    }
}