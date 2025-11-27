<?php
namespace Repositories;

use Models\User;
use PDO;

class UserRepository
{
    private PDO $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    public function findByEmail(string $email): ?User
    {
        $stmt = $this->conn->prepare("SELECT * FROM nguoi_dung WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }

        return $this->mapRowToUser($row);
    }

    public function findById(int $id): ?User
    {
        $stmt = $this->conn->prepare("SELECT * FROM nguoi_dung WHERE ma_nguoi_dung = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }

        return $this->mapRowToUser($row);
    }

    public function save(User $user): void
    {
        $email = $user->getEmail();
        $password = $user->getPasswordHash();
        $role = $user->getRole();
        $fullName = $user->getFullName();

        if ($user->getId()) {
            // Update
            $stmt = $this->conn->prepare("UPDATE nguoi_dung SET email = :email, mat_khau = :password, vai_tro = :role, ho_ten = :name WHERE ma_nguoi_dung = :id");
            $id = $user->getId();
            $stmt->bindParam(':id', $id);
        } else {
            // Insert
            $stmt = $this->conn->prepare("INSERT INTO nguoi_dung (email, mat_khau, vai_tro, ho_ten, ten_dang_nhap) VALUES (:email, :password, :role, :name, :username)");
            // Generate username from email prefix
            $username = explode('@', $email)[0]; 
            $stmt->bindParam(':username', $username);
        }

        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':name', $fullName);
        
        $stmt->execute();
    }

    private function mapRowToUser(array $row): User
    {
        return new User(
            (int)$row['ma_nguoi_dung'],
            $row['email'],
            $row['mat_khau'],
            $row['vai_tro'],
            $row['ho_ten']
        );
    }
}
