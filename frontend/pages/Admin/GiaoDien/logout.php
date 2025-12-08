<?php
session_start();

// Xoá toàn bộ session
$_SESSION = [];
session_unset();
session_destroy();

// Chuyển về trang chủ
header("Location: ../../../index.php"); 
exit;
?>