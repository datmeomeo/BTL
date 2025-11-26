<?php
require_once 'autoload.php';
require_once '../connect/connect-database.php'; // Use existing connection

use Infrastructure\CartRepositoryFactory;
use Application\CartService;
use Api\CartController;
use Domain\Exception\DomainException;

// Set JSON header
header('Content-Type: application/json');

try {
    // 1. Setup Dependencies
    // $conn is available from connect-database.php
    if (!isset($conn)) {
        throw new Exception("Database connection failed");
    }

    $repoFactory = new CartRepositoryFactory($conn);
    $cartRepo = $repoFactory->createRepository();
    
    $cartService = new CartService($cartRepo);
    $controller = new CartController($cartService);

    // 2. Routing
    $route = $_GET['route'] ?? '';
    $action = $_GET['action'] ?? '';

    if ($route === 'cart') {
        echo $controller->handleRequest($action);
    } else {
        throw new Exception("Route not found");
    }

} catch (DomainException $e) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
