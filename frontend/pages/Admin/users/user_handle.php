<?php
// File: admin/users/user_handle.php

require_once '../them/connect.php';

// === BẢO VỆ: Chỉ cho phép POST (trừ xóa bằng GET) ===
if ($_SERVER['REQUEST_METHOD'] !== 'POST' && !isset($_GET['action'])) {
    header('Location: ../GiaoDien/index.php?page=user');
    exit();
}

// Lấy action
$action = $_POST['action'] ?? $_GET['action'] ?? '';

// Xử lý ID
$id = null;
if ($action === 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
} elseif (!empty($_POST['ma_nguoi_dung'])) {
    $id = (int)$_POST['ma_nguoi_dung'];
}

// Dữ liệu form
$ten_dang_nhap = trim($_POST['ten_dang_nhap'] ?? '');
$email         = trim($_POST['email'] ?? '');
$ho_ten        = trim($_POST['ho_ten'] ?? '');
$so_dien_thoai = trim($_POST['so_dien_thoai'] ?? '');
$vai_tro       = $_POST['vai_tro'] ?? 'customer';
$trang_thai    = $_POST['trang_thai'] ?? 'active';

// Kiểm tra dữ liệu trước khi thêm/sửa
if (in_array($action, ['add', 'update'])) {
    if (empty($ten_dang_nhap) || empty($email)) {
        header('Location: ../GiaoDien/index.php?page=user&error=' . urlencode('Vui lòng nhập tên đăng nhập và email!'));
        exit();
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header('Location: ../GiaoDien/index.php?page=user&error=' . urlencode('Email không hợp lệ!'));
        exit();
    }
}

try {
    switch ($action) {

        // ==================== THÊM MỚI ====================
        case 'add':
            $check = $conn->prepare("SELECT ma_nguoi_dung FROM nguoi_dung WHERE ten_dang_nhap = ? OR email = ?");
            $check->execute([$ten_dang_nhap, $email]);
            if ($check->rowCount() > 0) {
                header('Location: ../GiaoDien/index.php?page=user&error=' . urlencode('Tên đăng nhập hoặc email đã tồn tại!'));
                exit();
            }

            $sql = "INSERT INTO nguoi_dung 
                    (ten_dang_nhap, mat_khau, email, ho_ten, so_dien_thoai, vai_tro, trang_thai, ngay_dang_ky) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";

            $stmt = $conn->prepare($sql);
            $stmt->execute([
                $ten_dang_nhap,
                password_hash('123456', PASSWORD_DEFAULT),
                $email,
                $ho_ten,
                $so_dien_thoai,
                $vai_tro,
                $trang_thai
            ]);

            header('Location: ../GiaoDien/index.php?page=user&success=' . urlencode('Thêm thành công! Mật khẩu mặc định: 123456'));
            exit();

        // ==================== CẬP NHẬT ====================
        case 'update':
            if (!$id || $id <= 1) {
                header('Location: ../GiaoDien/index.php?page=user&error=' . urlencode('Không thể sửa tài khoản admin chính!'));
                exit();
            }

            $sql = "UPDATE nguoi_dung SET 
                    ten_dang_nhap = ?, email = ?, ho_ten = ?, so_dien_thoai = ?, vai_tro = ?, trang_thai = ? 
                    WHERE ma_nguoi_dung = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                $ten_dang_nhap, $email, $ho_ten, $so_dien_thoai, $vai_tro, $trang_thai, $id
            ]);

            header('Location: ../GiaoDien/index.php?page=user&success=' . urlencode('Cập nhật thành công!'));
            exit();

        // ==================== XÓA ====================
        case 'delete':
            if (!$id || $id <= 1) {
                header('Location: ../GiaoDien/index.php?page=user&error=' . urlencode('Không thể xóa tài khoản admin chính!'));
                exit();
            }

            $stmt = $conn->prepare("DELETE FROM nguoi_dung WHERE ma_nguoi_dung = ?");
            $stmt->execute([$id]);

            header('Location: ../GiaoDien/index.php?page=user&success=' . urlencode('Xóa thành công!'));
            exit();

        // ==================== KHÔNG HỢP LỆ ====================
        default:
            header('Location: ../GiaoDien/index.php?page=user&error=' . urlencode('Hành động không hợp lệ!'));
            exit();
    }

} catch (Exception $e) {
    header('Location: ../GiaoDien/index.php?page=user&error=' . urlencode('Lỗi CSDL: ' . $e->getMessage()));
    exit();
}
