<?php
namespace Infrastructure\Persistence;

use Domain\Cart\Cart;
use Domain\Cart\CartItem;
use Domain\Cart\CartRepositoryInterface;
use PDO;

class DatabaseCartRepository implements CartRepositoryInterface
{
    private PDO $conn;
    private int $userId;

    public function __construct(PDO $conn, int $userId)
    {
        $this->conn = $conn;
        $this->userId = $userId;
    }

    public function getCart(): Cart
    {
        $cart = new Cart();

        // 1. Get Cart ID
        $stmt = $this->conn->prepare("SELECT ma_gio_hang FROM gio_hang WHERE ma_nguoi_dung = ?");
        $stmt->execute([$this->userId]);
        $row = $stmt->fetch();

        if (!$row) {
            return $cart; // Empty cart
        }

        $cartId = $row['ma_gio_hang'];

        // 2. Get Cart Items
        $sql = "SELECT c.*, s.ten_sach, s.gia_ban, h.duong_dan_hinh 
                FROM chi_tiet_gio_hang c
                JOIN sach s ON c.ma_sach = s.ma_sach
                LEFT JOIN hinh_anh_sach h ON s.ma_sach = h.ma_sach AND h.la_anh_chinh = 1
                WHERE c.ma_gio_hang = ?";
        
        $stmtItems = $this->conn->prepare($sql);
        $stmtItems->execute([$cartId]);
        $items = $stmtItems->fetchAll();

        foreach ($items as $item) {
            $cartItem = new CartItem(
                (int)$item['ma_sach'],
                (float)$item['gia_tai_thoi_diem'], // Or current price? Usually use current price for cart, snapshot for order. Using stored price for now.
                (int)$item['so_luong'],
                $item['ten_sach'],
                $item['duong_dan_hinh'] ?? ''
            );
            // We use a reflection hack or a public method to add without validation if needed, 
            // but addItem is fine as long as we trust DB data.
            // However, addItem logic merges, so we need to be careful if we are rebuilding.
            // Since we are rebuilding from DB, we can just add them.
            $cart->addItem($cartItem); 
        }

        return $cart;
    }

    public function save(Cart $cart): void
    {
        // 1. Ensure Cart Exists
        $stmt = $this->conn->prepare("SELECT ma_gio_hang FROM gio_hang WHERE ma_nguoi_dung = ?");
        $stmt->execute([$this->userId]);
        $row = $stmt->fetch();

        if ($row) {
            $cartId = $row['ma_gio_hang'];
            // Update timestamp
            $this->conn->prepare("UPDATE gio_hang SET ngay_cap_nhat = NOW() WHERE ma_gio_hang = ?")->execute([$cartId]);
        } else {
            $this->conn->prepare("INSERT INTO gio_hang (ma_nguoi_dung, ngay_tao, ngay_cap_nhat) VALUES (?, NOW(), NOW())")->execute([$this->userId]);
            $cartId = $this->conn->lastInsertId();
        }

        // 2. Clear old items (Simple Strategy)
        $this->conn->prepare("DELETE FROM chi_tiet_gio_hang WHERE ma_gio_hang = ?")->execute([$cartId]);

        // 3. Insert new items
        $stmtInsert = $this->conn->prepare("INSERT INTO chi_tiet_gio_hang (ma_gio_hang, ma_sach, so_luong, gia_tai_thoi_diem) VALUES (?, ?, ?, ?)");
        
        foreach ($cart->getItems() as $item) {
            $stmtInsert->execute([
                $cartId,
                $item->getProductId(),
                $item->getQuantity(),
                $item->getPrice()
            ]);
        }
    }
}
