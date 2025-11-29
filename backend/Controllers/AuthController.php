<?php
namespace Controllers;

use Core\BaseController;
use Services\AuthService;
use Services\CartService;
use Exception;

class AuthController extends BaseController
{
    private AuthService $authService;
    private ?CartService $cartService; // Optional dependency for merging cart

    public function __construct(AuthService $authService, ?CartService $cartService = null)
    {
        $this->authService = $authService;
        $this->cartService = $cartService;
    }

    public function handleRequest(string $action)
    {
        try {
            switch ($action) {
                case 'login':
                    $this->login();
                    break;
                case 'register':
                    $this->register();
                    break;
                case 'logout':
                    $this->logout();
                    break;
                case 'me':
                    $this->me();
                    break;
                default:
                    throw new Exception("Action not found");
            }
        } catch (Exception $e) {
            $this->jsonResponse(['status' => 'error', 'message' => $e->getMessage()], 400);
        }
    }

    private function login()
    {
        $data = $this->getInput();
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';

        if (empty($email) || empty($password)) {
            throw new Exception("Vui lòng nhập email và mật khẩu");
        }

        $user = $this->authService->login($email, $password);

        // Merge Cart if service is available
        if ($this->cartService) {
            $this->cartService->mergeGuestCartToUser($user->getId());
        }

        $this->jsonResponse([
            'status' => 'success',
            'message' => 'Đăng nhập thành công',
            'data' => [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'fullName' => $user->getFullName(),
                'role' => $user->getRole()
            ]
        ]);
    }

    private function register()
    {
        $data = $this->getInput();
        $fullName = $data['fullName'] ?? '';
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';

        if (empty($fullName) || empty($email) || empty($password)) {
            throw new Exception("Vui lòng nhập đầy đủ thông tin");
        }

        $this->authService->register($fullName, $email, $password);

        $this->jsonResponse([
            'status' => 'success',
            'message' => 'Đăng ký thành công'
        ]);
    }

    private function logout()
    {
        $this->authService->logout();
        $this->jsonResponse([
            'status' => 'success',
            'message' => 'Đăng xuất thành công'
        ]);
    }

    private function me()
    {
        $user = $this->authService->getCurrentUser();
        
        if (!$user) {
            $this->jsonResponse(['status' => 'error', 'message' => 'Chưa đăng nhập'], 401);
        }

        $this->jsonResponse([
            'status' => 'success',
            'data' => [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'fullName' => $user->getFullName(),
                'role' => $user->getRole()
            ]
        ]);
    }
}