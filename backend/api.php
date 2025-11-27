<?php
// backend/api.php
require_once 'autoload.php';

use Core\Database;

// 1. Setup Core & DB
$db = Database::getConnection();

// Start Session for API
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Instantiate Repositories (Inject DB connection)
$userRepo = new \Repositories\UserRepository($db);
$cartRepo = new \Repositories\CartRepository($db);
$sessionCartRepo = new \Repositories\SessionCartRepository();

// 3. Instantiate Services (Inject Repos)
$authService = new \Services\AuthService($userRepo);

// CartService receives both repos to decide where to save
$cartService = new \Services\CartService($cartRepo, $sessionCartRepo);

// 4. Routing Logic
$route = $_GET['route'] ?? '';
$action = $_GET['action'] ?? '';

try {
    switch ($route) {
        case 'auth':
            // Inject AuthService and CartService (for merging)
            $controller = new \Controllers\AuthController($authService, $cartService);
            $controller->handleRequest($action);
            break;

        case 'cart':
            $controller = new \Controllers\CartController($cartService);
            $controller->handleRequest($action); 
            break;

        default:
            http_response_code(404);
            echo json_encode(['error' => 'Route not found']);
            break;
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
