<?php
require_once '../them/connect.php';

// Chỉ cho phép POST, trừ delete dùng GET
if ($_SERVER['REQUEST_METHOD'] !== 'POST' && !isset($_GET['action'])) {
    header('Location: ../GiaoDien/index.php?page=author');
    exit();
}

$action = $_POST['action'] ?? $_GET['action'] ?? '';

$id = null;
if ($action === 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
} elseif (!empty($_POST['ma_tac_gia'])) {
    $id = (int)$_POST['ma_tac_gia'];
}

// Lấy dữ liệu form
$ten_tac_gia = trim($_POST['ten_tac_gia'] ?? '');
$ngay_sinh   = trim($_POST['ngay_sinh'] ?? '');
$quoc_tich   = trim($_POST['quoc_tich'] ?? '');
$tieu_su     = trim($_POST['tieu_su'] ?? '');

// Kiểm tra trước add/update
if (in_array($action, ['add', 'update'])) {
    if (empty($ten_tac_gia)) {
        header('Location: ../GiaoDien/index.php?page=author&error=' . urlencode('Vui lòng nhập tên tác giả'));
        exit();
    }
}

try {
    switch ($action) {

        case 'add':
            $sql = "INSERT INTO tac_gia (ten_tac_gia, ngay_sinh, quoc_tich, tieu_su) 
                    VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$ten_tac_gia, $ngay_sinh, $quoc_tich, $tieu_su]);

            header('Location: ../GiaoDien/index.php?page=author&success=' . urlencode('Thêm tác giả thành công'));
            exit();

        case 'update':
            $sql = "UPDATE tac_gia SET
                    ten_tac_gia = ?, 
                    ngay_sinh = ?, 
                    quoc_tich = ?, 
                    tieu_su = ?
                    WHERE ma_tac_gia = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$ten_tac_gia, $ngay_sinh, $quoc_tich, $tieu_su, $id]);

            header('Location: ../GiaoDien/index.php?page=author&success=' . urlencode('Cập nhật thành công'));
            exit();

        case 'delete':
            $stmt = $conn->prepare("DELETE FROM tac_gia WHERE ma_tac_gia = ?");
            $stmt->execute([$id]);

            header('Location: ../GiaoDien/index.php?page=author&success=' . urlencode('Xóa thành công'));
            exit();

        default:
            header('Location: ../GiaoDien/index.php?page=author&error=' . urlencode('Hành động không hợp lệ!'));
            exit();
    }

} catch (Exception $e) {
    header('Location: ../GiaoDien/index.php?page=author&error=' . urlencode('Lỗi CSDL: ' . $e->getMessage()));
    exit();
}
