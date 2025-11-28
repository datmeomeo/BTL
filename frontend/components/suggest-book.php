<?php
require_once './connect/connect-database.php';

// Lấy 30 sách ngẫu nhiên
$sql = "SELECT s.*, 
               h.duong_dan_hinh,
               nxb.ten_nxb,
               ROUND(((s.gia_goc - s.gia_ban) / s.gia_goc * 100), 0) as phan_tram_giam
        FROM SACH s
        LEFT JOIN HINH_ANH_SACH h ON s.ma_sach = h.ma_sach AND h.la_anh_chinh = TRUE
        LEFT JOIN NHA_XUAT_BAN nxb ON s.ma_nxb = nxb.ma_nxb
        WHERE s.trang_thai = 'available'
        ORDER BY RAND()
        LIMIT 30";

$stmt = $conn->query($sql);
$danhSachSach = $stmt->fetchAll();
?>

<div class="goi-y-section">
    <div class="container">
        <!-- Header -->
        <div class="section-header">
            <h2 class="section-title">Gợi ý cho bạn</h2>
        </div>

        <!-- Grid sách -->
        <div class="sach-grid">
            <?php foreach ($danhSachSach as $sach): ?>
                <!-- ✅ ĐÃ SỬA: Thêm ?id= và <?php ?> -->
                <a href="index.php?page=book&id=<?php echo $sach['ma_sach']; ?>" class="sach-card">
                    <!-- Badge giảm giá -->
                    <?php if ($sach['phan_tram_giam'] > 0): ?>
                        <div class="discount-badge">
                            -<?php echo $sach['phan_tram_giam']; ?>%
                        </div>
                    <?php endif; ?>

                    <!-- Badge Mới -->
                    <?php 
                    $ngay_them = strtotime($sach['ngay_them']);
                    $ngay_hien_tai = time();
                    $so_ngay = ($ngay_hien_tai - $ngay_them) / (60 * 60 * 24);
                    if ($so_ngay <= 7): 
                    ?>
                        <div class="new-badge">Mới</div>
                    <?php endif; ?>

                    <!-- Hình ảnh -->
                    <img class="sach-image" 
                            src="<?php echo $sach['duong_dan_hinh'] ?? './assets/img/no-image.jpg'; ?>" 
                            alt="<?php echo htmlspecialchars($sach['ten_sach']); ?>"
                            loading="lazy">

                    <!-- Thông tin -->
                    <div class="sach-info">
                        <div class="sach-title" title="<?php echo htmlspecialchars($sach['ten_sach']); ?>">
                            <?php echo htmlspecialchars($sach['ten_sach']); ?>
                        </div>

                        <div class="sach-publisher">
                            <?php echo htmlspecialchars($sach['ten_nxb'] ?? 'NXB'); ?>
                        </div>

                        <!-- Giá -->
                        <div class="price-section">
                            <span class="current-price">
                                <?php echo number_format($sach['gia_ban'], 0, ',', '.'); ?>đ
                            </span>
                            
                            <?php if ($sach['gia_goc'] > $sach['gia_ban']): ?>
                                <span class="old-price">
                                    <?php echo number_format($sach['gia_goc'], 0, ',', '.'); ?>đ
                                </span>
                            <?php endif; ?>
                        </div>

                        <!-- Rating -->
                        <div class="rating">
                            <span class="stars">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="#ffc107" stroke="none"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="#ffc107" stroke="none"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="#ffc107" stroke="none"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="#ffc107" stroke="none"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="#ffc107" stroke="none"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                            </span>
                            <span style="color: #666;">(<?php echo rand(10, 500); ?>)</span>
                        </div>

                        <!-- Số lượng đã bán -->
                        <div class="sold-count">
                            Đã bán <?php echo rand(10, 200); ?>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>

        <!-- Button xem thêm -->
        <div class="view-more">
            <button class="btn-view-more" onclick="xemThem()">
                Xem thêm sản phẩm
            </button>
        </div>
    </div>
</div>

<script>
    // Xem thêm sách (reload để lấy 30 sách ngẫu nhiên mới)
    function xemThem() {
        window.location.reload();
    }
</script>