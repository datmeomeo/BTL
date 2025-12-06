<?php
session_start();

// Xoá toàn bộ session
$_SESSION = [];
session_unset();
session_destroy();

// Chuyển về trang chủ
// Từ frontend/pages/Admin/GiaoDien đi ngược ra 4 cấp để về thư mục gốc
header("Location: ../../../index.php"); 
exit;
?>