<?php


$host = 'localhost';
$dbname = 'csdl_bansach';          // THAY TÊN DATABASE CỦA BẠN VÀO ĐÂY
$username = 'root';           // mặc định XAMPP là root
$password = '';               // mặc định XAMPP để trống

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    
    // Cấu hình PDO để báo lỗi chi tiết (rất quan trọng khi đang code)
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Lấy dữ liệu dưới dạng mảng kết hợp (dễ dùng hơn fetchObject)
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    // Nếu kết nối thất bại thì hiển thị lỗi rõ ràng (chỉ để khi đang dev)
    die("Kết nối CSDL thất bại: " . $e->getMessage());
}
?>