<?php
require_once '../them/connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' && !isset($_GET['action'])) {
    header('Location: ../GiaoDien/index.php?page=publisher');
    exit();
}

$action = $_POST['action'] ?? $_GET['action'] ?? '';

$id = null;
if ($action === 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
} elseif (!empty($_POST['ma_nxb'])) {
    $id = (int)$_POST['ma_nxb'];
}

$ten_nxb = trim($_POST['ten_nxb'] ?? '');
$dia_chi = trim($_POST['dia_chi'] ?? '');
$so_dien_thoai = trim($_POST['so_dien_thoai'] ?? '');
$email = trim($_POST['email'] ?? '');

if (in_array($action, ['add', 'update'])) {
    if (empty($ten_nxb)) {
        header('Location: ../GiaoDien/index.php?page=publisher&error=' . urlencode('Vui lòng nhập tên NXB'));
        exit();
    }
}

try {
    switch ($action) {

        case 'add':
            $sql = "INSERT INTO nha_xuat_ban (ten_nxb, dia_chi, so_dien_thoai, email)
                    VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$ten_nxb, $dia_chi, $so_dien_thoai, $email]);
            header('Location: ../GiaoDien/index.php?page=publisher&success=' . urlencode('Thêm nhà xuất bản thành công'));
            exit();

        case 'update':
            $sql = "UPDATE nha_xuat_ban SET 
                        ten_nxb = ?, 
                        dia_chi = ?, 
                        so_dien_thoai = ?, 
                        email = ?
                    WHERE ma_nxb = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$ten_nxb, $dia_chi, $so_dien_thoai, $email, $id]);
            header('Location: ../GiaoDien/index.php?page=publisher&success=' . urlencode('Cập nhật thành công'));
            exit();

        case 'delete':
            $stmt = $conn->prepare("DELETE FROM nha_xuat_ban WHERE ma_nxb = ?");
            $stmt->execute([$id]);
            header('Location: ../GiaoDien/index.php?page=publisher&success=' . urlencode('Xóa thành công'));
            exit();

        default:
            header('Location: ../GiaoDien/index.php?page=publisher&error=' . urlencode('Hành động không hợp lệ'));
            exit();
    }

} catch(Exception $e) {
    header('Location: ../GiaoDien/index.php?page=publisher&error=' . urlencode('Lỗi CSDL: ' . $e->getMessage()));
    exit();
}
