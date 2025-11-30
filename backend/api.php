<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// backend/api.php
require_once 'autoload.php';

use Core\Database;
use Repositories\UserRepository;
use Repositories\CartRepository;
use Repositories\BookRepository;
use Repositories\SessionCartRepository;

use Services\AuthService;
use Services\CartService;

use Controllers\AuthController;
use Controllers\CartController;
use Controllers\BookController;
use Services\BookService;
use Queries\BookDetailPageQuery;
use Queries\SuggestBookQuery;


function BookAPI(string $action, PDO $db)
{
    $bookRepository = new BookRepository($db);
    $bookService = new BookService($bookRepository);
    $suggestBookQuery = new SuggestBookQuery($db);
    $bookDetailPageQuery = new BookDetailPageQuery($db);
    $controller = new BookController($bookService, $bookDetailPageQuery, $suggestBookQuery);
    $controller->handleRequest($action);
}

function CartAPI(string $action, PDO $db)
{
    $cartRepo = new CartRepository($db);
    $sessionCartRepo = new SessionCartRepository();
    $bookRepository = new BookRepository($db);
    $cartService = new CartService($cartRepo, $bookRepository, $sessionCartRepo);
    $controller = new CartController($cartService);
    $controller->handleRequest($action);
}

function AuthAPI(string $action, PDO $db)
{
    $userRepo = new UserRepository($db);
    $authService = new AuthService($userRepo);
    $cartRepo = new CartRepository($db);

    $cartRepo = new CartRepository($db);
    $sessionCartRepo = new SessionCartRepository();
    $bookRepository = new BookRepository($db);
    $cartService = new CartService($cartRepo, $bookRepository, $sessionCartRepo);

    $controller = new AuthController($authService, $cartService);
    $controller->handleRequest($action);
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$route = $_GET['route'] ?? '';
$action = $_GET['action'] ?? '';

$apiHandlers = [
    'auth' => 'AuthAPI',
    'cart' => 'CartAPI',
    'book' => 'BookAPI',
];
$db = Database::getConnection();

try {
    if (isset($apiHandlers[$route])){
        $handler = $apiHandlers[$route];
        $handler($action, $db);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Route not found']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
