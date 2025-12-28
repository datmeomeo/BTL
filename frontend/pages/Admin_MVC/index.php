<?php
session_start();
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    echo "<script>
        localStorage.removeItem('user_info'); 
        window.location.href = '../../index.php?page=login';
    </script>";
    exit(); 
}

$role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : 0;

if ((string)$role !== '1' && strtolower((string)$role) !== 'admin') {
    echo "<script>
        window.location.href = '../../index.php';
    </script>";
    exit();
}

// Kết nối CSDL
require_once "them/connect.php";

$page   = $_GET['page']   ?? 'dashboard';
$action = $_GET['action'] ?? 'index';

// Lấy tên controller
$controllerName = ucfirst($page) . 'Controller';
$file = "controllers/$controllerName.php";

// Controller không tồn tại
if (!file_exists($file)) {
    die("Không tìm thấy controller: $controllerName");
}

require_once $file;

// KHỞI TẠO controller (truyền kết nối DB)
$controller = new $controllerName($conn);

// KIỂM TRA HÀM (method) TRONG CONTROLLER
if (!method_exists($controller, $action)) {
    die("Không tìm thấy action: $action trong controller $controllerName");
}

// GỌI ĐÚNG ACTION
$controller->$action();