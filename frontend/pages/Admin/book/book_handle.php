<?php
require_once '../them/connect.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';

try {

    // ============================
    // ======== THÊM SÁCH =========
    // ============================
    if ($action === 'add') {

        // 1. thêm sách
        $stmt = $conn->prepare("
            INSERT INTO sach(
                ten_sach, ma_nxb, mo_ta, gia_ban, gia_goc,
                so_luong_ton, so_trang, hinh_thuc_bia, ngon_ngu, 
                nam_xuat_ban, ma_isbn
            )
            VALUES (?,?,?,?,?,?,?,?,?,?,?)
        ");

        $stmt->execute([
            $_POST['ten_sach'],
            $_POST['ma_nxb'] ?: null,
            $_POST['mo_ta'] ?: null,
            $_POST['gia_ban'],
            $_POST['gia_goc'] ?: null,
            $_POST['so_luong_ton'] ?: 0,
            $_POST['so_trang'] ?: null,
            $_POST['hinh_thuc_bia'] ?: null,
            $_POST['ngon_ngu'] ?: 'Tiếng Việt',
            $_POST['nam_xuat_ban'] ?: null,
            $_POST['ma_isbn'] ?: null
        ]);

        $ma_sach_moi = $conn->lastInsertId();

        // 2. thêm tác giả cho sách
        if (!empty($_POST['ma_tac_gia'])) {
            $stmt2 = $conn->prepare("INSERT INTO sach_tac_gia(ma_sach, ma_tac_gia) VALUES(?, ?)");
            $stmt2->execute([$ma_sach_moi, $_POST['ma_tac_gia']]);
        }

        // 3. thêm danh mục cho sách
        if (!empty($_POST['danh_muc']) && is_array($_POST['danh_muc'])) {
            $stmt3 = $conn->prepare("INSERT INTO sach_danh_muc(ma_sach, ma_danh_muc) VALUES(?, ?)");
            foreach ($_POST['danh_muc'] as $dm) {
                $stmt3->execute([$ma_sach_moi, $dm]);
            }
        }

        header("Location: ../GiaoDien/index.php?page=book&success=Thêm thành công!");
        exit;
    }

    // ============================
    // ======== CẬP NHẬT ==========
    // ============================
    if ($action === 'update') {

        $stmt = $conn->prepare("
            UPDATE sach SET 
                ten_sach=?, ma_nxb=?, mo_ta=?, gia_ban=?, gia_goc=?,
                so_luong_ton=?, so_trang=?, hinh_thuc_bia=?, ngon_ngu=?,
                nam_xuat_ban=?, ma_isbn=?
            WHERE ma_sach=?
        ");

        $stmt->execute([
            $_POST['ten_sach'],
            $_POST['ma_nxb'] ?: null,
            $_POST['mo_ta'] ?: null,
            $_POST['gia_ban'],
            $_POST['gia_goc'] ?: null,
            $_POST['so_luong_ton'] ?: 0,
            $_POST['so_trang'] ?: null,
            $_POST['hinh_thuc_bia'] ?: null,
            $_POST['ngon_ngu'],
            $_POST['nam_xuat_ban'] ?: null,
            $_POST['ma_isbn'] ?: null,
            $_POST['ma_sach']
        ]);

        $ma_sach = $_POST['ma_sach'];

        // xóa tác giả cũ và thêm mới
        $conn->prepare("DELETE FROM sach_tac_gia WHERE ma_sach=?")->execute([$ma_sach]);
        if (!empty($_POST['ma_tac_gia'])) {
            $stmt2 = $conn->prepare("INSERT INTO sach_tac_gia(ma_sach, ma_tac_gia) VALUES(?, ?)");
            $stmt2->execute([$ma_sach, $_POST['ma_tac_gia']]);
        }

        // xóa danh mục cũ và thêm danh mục mới
        $conn->prepare("DELETE FROM sach_danh_muc WHERE ma_sach=?")->execute([$ma_sach]);
        if (!empty($_POST['danh_muc']) && is_array($_POST['danh_muc'])) {
            $stmt3 = $conn->prepare("INSERT INTO sach_danh_muc(ma_sach, ma_danh_muc) VALUES(?, ?)");
            foreach ($_POST['danh_muc'] as $dm) {
                $stmt3->execute([$ma_sach, $dm]);
            }
        }

        header("Location: ../GiaoDien/index.php?page=book&success=Cập nhật thành công!");
        exit;
    }

    // ============================
    // ========= XÓA ==============
    // ============================
    if ($action === 'delete') {
        $id = $_GET['id'];
        $stmt = $conn->prepare("DELETE FROM sach WHERE ma_sach=?");
        $stmt->execute([$id]);
        header("Location: ../GiaoDien/index.php?page=book&success=Xóa thành công!");
        exit;
    }

} catch (PDOException $e) {
    $msg = urlencode("Lỗi: " . $e->getMessage());
    header("Location: ../GiaoDien/index.php?page=book&error=$msg");
    exit;
}
?>
