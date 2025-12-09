<?php
require_once '../them/connect.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';

try {
    if ($action === 'upload') {
        $ma_sach = $_POST['ma_sach'];
        foreach ($_FILES['images']['tmp_name'] as $index => $tmp_name) {
            $name = basename($_FILES['images']['name'][$index]);
            $path = "../../../assets/img-book/$name";
            if (move_uploaded_file($tmp_name, $path)) {
                $conn->prepare("INSERT INTO hinh_anh_sach(ma_sach, duong_dan_hinh) VALUES(?,?)")
                     ->execute([$ma_sach, $name]);
            }
        }
        header("Location: ../GiaoDien/index.php?page=book_images&id=$ma_sach");
        exit;
    }

    if ($action === 'delete') {
        $id = $_GET['id'];
        $ma_sach = $_GET['ma_sach'];
        $file = $conn->query("SELECT file_name FROM hinh_anh_sach WHERE id=$id")->fetchColumn();
        if ($file && file_exists("../uploads/$file")) unlink("../uploads/$file");
        $conn->prepare("DELETE FROM hinh_anh_sach WHERE id=?")->execute([$id]);
        header("Location: ../GiaoDien/index.php?page=book_images&id=$ma_sach");
        exit;
    }

    if ($action === 'set_cover') {
        $id = $_GET['id'];
        $ma_sach = $_GET['ma_sach'];
        // reset tất cả bìa chính
        $conn->prepare("UPDATE hinh_anh_sach SET is_cover=0 WHERE ma_sach=?")->execute([$ma_sach]);
        // đặt bìa chính mới
        $conn->prepare("UPDATE hinh_anh_sach SET is_cover=1 WHERE id=?")->execute([$id]);
        header("Location: ../GiaoDien/index.php?page=book_images&id=$ma_sach");
        exit;
    }

} catch (PDOException $e) {
    die("Lỗi: " . $e->getMessage());
}
?>
