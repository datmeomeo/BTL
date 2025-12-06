<?php
session_start();

// Xoá toàn bộ session
$_SESSION = [];
session_unset();
session_destroy();

// Chuyển về trang đăng nhập
header("Location: ../login/login.php");
exit;
