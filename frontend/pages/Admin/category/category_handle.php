<?php
require_once '../them/connect.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';

$ma     = $_POST['ma_danh_muc'] ?? 0;
$ten    = trim($_POST['ten_danh_muc'] ?? '');
$slug   = trim($_POST['slug'] ?? '');
$mo_ta  = trim($_POST['mo_ta'] ?? '');

$cha    = ($_POST['danh_muc_cha'] !== '') ? (int)$_POST['danh_muc_cha'] : null;
$cap    = (int)($_POST['cap_do'] ?? 1);
$thu_tu = (int)($_POST['thu_tu'] ?? 0);
$menu   = (int)($_POST['hien_thi_menu'] ?? 1);
$icon   = trim($_POST['icon'] ?? '');
$mau    = trim($_POST['mau_sac'] ?? '');
$noibat = (int)($_POST['la_danh_muc_noi_bat'] ?? 0);

/* ========== VALIDATE ========== */
if (in_array($action, ['add', 'update'])) {

    if ($ten === '') {
        header("Location: ../GiaoDien/index.php?page=category&error=" . urlencode("Tên danh mục không được để trống!"));
        exit;
    }
}

/* ========== XỬ LÝ ========== */

if ($action === 'add') {

    $stmt = $conn->prepare("
        INSERT INTO danh_muc (ten_danh_muc, slug, mo_ta, danh_muc_cha, cap_do, thu_tu,
                              hien_thi_menu, icon, mau_sac, la_danh_muc_noi_bat)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([$ten, $slug, $mo_ta, $cha, $cap, $thu_tu, $menu, $icon, $mau, $noibat]);

    header("Location: ../GiaoDien/index.php?page=category&success=" . urlencode("Thêm danh mục thành công!"));
    exit;
}

if ($action === 'update') {

    $stmt = $conn->prepare("
        UPDATE danh_muc SET 
            ten_danh_muc=?, slug=?, mo_ta=?, danh_muc_cha=?, cap_do=?, thu_tu=?, 
            hien_thi_menu=?, icon=?, mau_sac=?, la_danh_muc_noi_bat=?
        WHERE ma_danh_muc=?
    ");

    $stmt->execute([$ten, $slug, $mo_ta, $cha, $cap, $thu_tu, $menu, $icon, $mau, $noibat, $ma]);

    header("Location: ../GiaoDien/index.php?page=category&success=" . urlencode("Cập nhật thành công!"));
    exit;
}

if ($action === 'delete') {

    $id = $_GET['id'] ?? 0;

    $stmt = $conn->prepare("DELETE FROM danh_muc WHERE ma_danh_muc=?");
    $stmt->execute([$id]);

    header("Location: ../GiaoDien/index.php?page=category&success=" . urlencode("Xóa danh mục thành công!"));
    exit;
}

header("Location: ../GiaoDien/index.php?page=category&error=" . urlencode("Hành động không hợp lệ!"));
exit;
