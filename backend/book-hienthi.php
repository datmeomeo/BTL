<?php
require_once '../connect/connect-database.php';

// Lấy ID sách từ URL
$ma_sach = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($ma_sach <= 0) {
    header('Location: ../backend/book-hienthi.php');
    exit;
}

// Lấy thông tin chi tiết sách
$sql = "SELECT s.*, 
               nxb.ten_nxb,
               GROUP_CONCAT(DISTINCT tg.ten_tac_gia SEPARATOR ', ') as tac_gia,
               AVG(dg.diem_danh_gia) as diem_trung_binh,
               COUNT(DISTINCT dg.ma_danh_gia) as so_luong_danh_gia,
               ROUND(((s.gia_goc - s.gia_ban) / s.gia_goc * 100), 0) as phan_tram_giam
        FROM SACH s
        LEFT JOIN NHA_XUAT_BAN nxb ON s.ma_nxb = nxb.ma_nxb
        LEFT JOIN SACH_TAC_GIA stg ON s.ma_sach = stg.ma_sach
        LEFT JOIN TAC_GIA tg ON stg.ma_tac_gia = tg.ma_tac_gia
        LEFT JOIN DANH_GIA dg ON s.ma_sach = dg.ma_sach AND dg.trang_thai = 'approved'
        WHERE s.ma_sach = ?
        GROUP BY s.ma_sach";

$stmt = $conn->prepare($sql);
$stmt->execute([$ma_sach]);
$sach = $stmt->fetch();

if (!$sach) {
    header('Location: ../giaodien/trangchu.php');
    exit;
}

// Tăng lượt xem
$sql_update = "UPDATE SACH SET luot_xem = luot_xem + 1 WHERE ma_sach = ?";
$stmt_update = $conn->prepare($sql_update);
$stmt_update->execute([$ma_sach]);

// Lấy tất cả hình ảnh
$sql_images = "SELECT * FROM HINH_ANH_SACH WHERE ma_sach = ? ORDER BY la_anh_chinh DESC, thu_tu ASC";
$stmt_images = $conn->prepare($sql_images);
$stmt_images->execute([$ma_sach]);
$hinh_anh = $stmt_images->fetchAll();

// Lấy danh mục của sách (bao gồm cả cha)
$sql_dm = "SELECT dm.ma_danh_muc, dm.ten_danh_muc, dm.slug, dm.danh_muc_cha
           FROM DANH_MUC dm
           INNER JOIN SACH_DANH_MUC sdm ON dm.ma_danh_muc = sdm.ma_danh_muc
           WHERE sdm.ma_sach = ?
           LIMIT 1";
$stmt_dm = $conn->prepare($sql_dm);
$stmt_dm->execute([$ma_sach]);
$danh_muc = $stmt_dm->fetch();

// Lấy danh mục cha (nếu có)
$danh_muc_cha = null;
if ($danh_muc && $danh_muc['danh_muc_cha']) {
    $sql_dm_cha = "SELECT ten_danh_muc, slug FROM DANH_MUC WHERE ma_danh_muc = ?";
    $stmt_dm_cha = $conn->prepare($sql_dm_cha);
    $stmt_dm_cha->execute([$danh_muc['danh_muc_cha']]);
    $danh_muc_cha = $stmt_dm_cha->fetch();
}

// Lấy đánh giá
$sql_danhgia = "SELECT dg.*, nd.ho_ten, nd.ten_dang_nhap
                FROM DANH_GIA dg
                INNER JOIN NGUOI_DUNG nd ON dg.ma_nguoi_dung = nd.ma_nguoi_dung
                WHERE dg.ma_sach = ? AND dg.trang_thai = 'approved'
                ORDER BY dg.ngay_danh_gia DESC
                LIMIT 5";
$stmt_danhgia = $conn->prepare($sql_danhgia);
$stmt_danhgia->execute([$ma_sach]);
$danh_gia_list = $stmt_danhgia->fetchAll();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($sach['ten_sach']); ?> - Fahasa</title>
</head>
<body>
    <?php include '../com/book.php'; ?>
</body>
</html>