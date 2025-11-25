<?php
$host = "localhost";
$db   = "csdl_bansach";
$user = "root";
$pass = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
     
} catch (PDOException $e) {
    echo "
    <script>
        alert('⚠️ Không thể kết nối database!\\n\\nLỗi: " . addslashes($e->getMessage()) . "\\n\\nVui lòng kiểm tra:\\n- XAMPP đã khởi động MySQL chưa?\\n- Database fahasa_db đã tồn tại?\\n- Thông tin kết nối đúng chưa?');
        window.location.href = 'index.php';
    </script>
    ";
    exit;
}
?>

