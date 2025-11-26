<?php
namespace Infrastructure;

use Infrastructure\Persistence\SessionCartRepository;
use Infrastructure\Persistence\DatabaseCartRepository;
use Domain\Cart\CartRepositoryInterface;
use PDO;

class CartRepositoryFactory
{
    private PDO $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    public function createRepository(): CartRepositoryInterface
    {
        // Check if user is logged in
        // Assuming login stores 'user_id' or similar in session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['user_id'])) { // Adjust key based on actual login logic
            return new DatabaseCartRepository($this->conn, (int)$_SESSION['user_id']);
        }

        // Also check if 'ma_nguoi_dung' is used (based on DB schema)
        if (isset($_SESSION['user'])) {
             // Assuming $_SESSION['user'] is an array or object with id
             $user = $_SESSION['user'];
             $userId = is_array($user) ? ($user['ma_nguoi_dung'] ?? null) : ($user->ma_nguoi_dung ?? null);
             if ($userId) {
                 return new DatabaseCartRepository($this->conn, (int)$userId);
             }
        }

        return new SessionCartRepository();
    }
}
