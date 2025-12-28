<?php
namespace Repositories;

use Models\Cart;
use Models\CartItem;
use PDO;

class CartRepository
{
    private PDO $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }
    public function getCart(int $userId): Cart
    {
        $cart = new Cart();

        // 1. Get Cart ID
        $stmt = $this->conn->prepare("SELECT ma_gio_hang FROM gio_hang WHERE ma_nguoi_dung = ?");
        $stmt->execute([$userId]);
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
        $rawUrl = $item['duong_dan_hinh'] ?? ''; 
        $finalUrl = './assets/img-book/' . ltrim($rawUrl, '/.'); 
        if (empty($rawUrl)) {
            $finalUrl = './assets/img/fahasa-logo.jpg';
        }

            $cartItem = new CartItem(
                (int)$item['ma_sach'],
                (float)$item['gia_tai_thoi_diem'], 
                (int)$item['so_luong'],
                $item['ten_sach'],
                $rawUrl 
            );
            $cart->addItem($cartItem); 
        }

        return $cart;
    }
    public function save(Cart $cart, int $userId): void
    {
        // 1. Ensure Cart Exists
        $stmt = $this->conn->prepare("SELECT ma_gio_hang FROM gio_hang WHERE ma_nguoi_dung = ?");
        $stmt->execute([$userId]);
        $row = $stmt->fetch();

        if ($row) {
            $cartId = $row['ma_gio_hang'];
            // Update timestamp
            $this->conn->prepare("UPDATE gio_hang SET ngay_cap_nhat = NOW() WHERE ma_gio_hang = ?")->execute([$cartId]);
        } else {
            $this->conn->prepare("INSERT INTO gio_hang (ma_nguoi_dung, ngay_tao, ngay_cap_nhat) VALUES (?, NOW(), NOW())")->execute([$userId]);
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
