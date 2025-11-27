<?php
namespace Services;

use Repositories\UserRepository;
use Models\User;
use Exception;

class AuthService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function login(string $email, string $password): User
    {
        $user = $this->userRepository->findByEmail($email);

        if (!$user) {
            throw new Exception("Email không tồn tại.");
        }

        if (!$user->verifyPassword($password)) {
            throw new Exception("Mật khẩu không đúng.");
        }

        // Security: Prevent Session Fixation
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_regenerate_id(true);

        $_SESSION['user_id'] = $user->getId();
        $_SESSION['user_role'] = $user->getRole();
        
        return $user;
    }

    public function register(string $fullName, string $email, string $password): void
    {
        if ($this->userRepository->findByEmail($email)) {
            throw new Exception("Email đã được sử dụng.");
        }

        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
        
        // Default role is 'customer' (or 'buyer')
        $user = new User(null, $email, $passwordHash, 'customer', $fullName);
        
        $this->userRepository->save($user);
    }

    public function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_unset();
        session_destroy();
    }

    public function getCurrentUser(): ?User
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            return null;
        }

        return $this->userRepository->findById($_SESSION['user_id']);
    }
}
