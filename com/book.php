<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết sản phẩm</title>
</head>

<body>
    <div class="container-detail">
        <!-- Breadcrumb -->
        <div class="breadcrumb">
            <a href="../giaodien/trangchu.php">SÁCH TIẾNG VIỆT</a>
            <span>›</span>

            <?php if ($danh_muc_cha): ?>
                <a href="#"><?php echo htmlspecialchars($danh_muc_cha['ten_danh_muc']); ?></a>
                <span>›</span>
            <?php endif; ?>

            <?php if ($danh_muc): ?>
                <a href="#"><?php echo htmlspecialchars($danh_muc['ten_danh_muc']); ?></a>
                <span>›</span>
            <?php endif; ?>

            <span><?php echo htmlspecialchars($sach['ten_sach']); ?></span>
        </div>

        <!-- Product Detail Wrapper -->
        <div class="product-detail-wrapper">
            <!-- LEFT COLUMN: Images + Shipping + Policy -->
            <div class="product-left-column">
                <div class="product-images-card">
                    <!-- Main Image -->
                    <div class="main-image">
                        <img id="mainImage" src="<?php echo ($hinh_anh[0]['duong_dan_hinh'] ?? '../img/no-image.jpg'); ?>"
                            alt="<?php echo htmlspecialchars($sach['ten_sach']); ?>">
                    </div>

                    <!-- Thumbnail Images -->
                    <?php if (count($hinh_anh) > 1): ?>
                        <div class="thumbnail-images">
                            <?php foreach ($hinh_anh as $index => $img): ?>
                                <div class="thumbnail <?php echo $index === 0 ? 'active' : ''; ?>"
                                    onclick="changeImage('<?php echo $img['duong_dan_hinh']; ?>', this)">
                                    <img src="<?php echo $img['duong_dan_hinh']; ?>" alt="Thumbnail">
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Fahasa Policy Card -->
                <div class="policy-card">
                    <div class="fahasa-policy-section">
                        <div class="policy-title">Chính sách ưu đãi của Fahasa</div>
                        <div class="policy-item">
                            <div class="policy-icon">🚚</div>
                            <div class="policy-text">
                                <strong>Thời gian giao hàng:</strong> Giao nhanh và uy tín
                            </div>
                            <div class="policy-arrow">›</div>
                        </div>
                        <div class="policy-item">
                            <div class="policy-icon">🔄</div>
                            <div class="policy-text">
                                <strong>Chính sách đổi trả:</strong> Đổi trả miễn phí toàn quốc
                            </div>
                            <div class="policy-arrow">›</div>
                        </div>
                        <div class="policy-item">
                            <div class="policy-icon">🎁</div>
                            <div class="policy-text">
                                <strong>Chính sách khách sỉ:</strong> Ưu đãi khi mua số lượng lớn
                            </div>
                            <div class="policy-arrow">›</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN: Product Info -->
            <div class="product-right-column">
                <div class="product-info-card">
                    <!-- Product Title -->
                    <h1 class="product-title"><?php echo htmlspecialchars($sach['ten_sach']); ?></h1>

                    <!-- Product Meta -->
                    <div class="product-meta">
                        <div class="meta-item">
                            <span class="meta-label">Nhà cung cấp:</span>
                            <span class="meta-value"><?php echo htmlspecialchars($sach['ten_nxb'] ?? 'Alpha Books'); ?></span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Nhà xuất bản:</span>
                            <span class="meta-value"><?php echo htmlspecialchars($sach['ten_nxb']); ?></span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Tác giả:</span>
                            <span class="meta-value"><?php echo htmlspecialchars($sach['tac_gia'] ?? 'Đang cập nhật'); ?></span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Hình thức bìa:</span>
                            <span class="meta-value"><?php echo htmlspecialchars($sach['hinh_thuc_bia'] ?? 'Bìa Mềm'); ?></span>
                        </div>
                    </div>

                    <!-- Rating -->
                    <div class="rating-section">
                        <?php
                        $diem_tb = round($sach['diem_trung_binh'] ?? 0);
                        $stars = str_repeat('★', $diem_tb) . str_repeat('☆', 5 - $diem_tb);
                        ?>
                        <div class="stars"><?php echo $stars; ?></div>
                        <span class="rating-text">(<?php echo $sach['so_luong_danh_gia']; ?> đánh giá)</span>
                        <span class="rating-text">Đã bán <?php echo rand(10, 200); ?></span>
                    </div>

                    <!-- Price Section -->
                    <div class="price-section">
                        <div>
                            <span class="current-price"><?php echo number_format($sach['gia_ban'], 0, ',', '.'); ?>₫</span>
                            <?php if ($sach['gia_goc'] > $sach['gia_ban']): ?>
                                <span class="old-price"><?php echo number_format($sach['gia_goc'], 0, ',', '.'); ?>₫</span>
                                <span class="discount-badge">-<?php echo $sach['phan_tram_giam']; ?>%</span>
                            <?php endif; ?>
                        </div>

                        <div class="promo-link-wrapper">
                            <a href="#" class="promo-link">
                                Chính sách khuyến mãi trên chỉ áp dụng tại Fahasa.com →
                            </a>
                        </div>
                    </div>

                    <!-- Stock Info -->
                    <div class="stock-info">
                        📦 <?php echo $sach['so_luong_ton']; ?> nhà sách còn hàng
                    </div>

                    <!-- Quantity -->
                    <div class="quantity-section">
                        <span class="quantity-label">Số lượng:</span>
                        <div class="quantity-control">
                            <button class="qty-btn" onclick="decreaseQty()">-</button>
                            <input type="number" id="quantity" class="qty-input" value="1" min="1" max="<?php echo $sach['so_luong_ton']; ?>">
                            <button class="qty-btn" onclick="increaseQty()">+</button>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="action-buttons">
                        <button class="btn-add-cart" onclick="themVaoGio(<?php echo $sach['ma_sach']; ?>)">
                            🛒 Thêm vào giỏ hàng
                        </button>
                        <button class="btn-buy-now" onclick="muaNgay(<?php echo $sach['ma_sach']; ?>)">
                            Mua ngay
                        </button>
                    </div>
                </div>

                <!-- Specifications Card -->
                <div class="specifications-card">
                    <div class="spec-title">Thông tin chi tiết</div>
                    <div class="spec-table">
                        <div class="spec-row">
                            <div class="spec-label">Mã hàng</div>
                            <div class="spec-value"><?php echo $sach['ma_isbn'] ?? $sach['ma_sach']; ?></div>
                        </div>
                        <div class="spec-row">
                            <div class="spec-label">Tên Nhà Cung Cấp</div>
                            <div class="spec-value"><?php echo htmlspecialchars($sach['ten_nxb']); ?></div>
                        </div>
                        <div class="spec-row">
                            <div class="spec-label">Tác giả</div>
                            <div class="spec-value"><?php echo htmlspecialchars($sach['tac_gia']); ?></div>
                        </div>
                        <div class="spec-row">
                            <div class="spec-label">Người Dịch</div>
                            <div class="spec-value"><?php echo htmlspecialchars($sach['nguoi_dich'] ?? 'Đặng Hồng Quân'); ?></div>
                        </div>
                        <div class="spec-row">
                            <div class="spec-label">NXB</div>
                            <div class="spec-value"><?php echo htmlspecialchars($sach['ten_nxb']); ?></div>
                        </div>
                        <div class="spec-row">
                            <div class="spec-label">Năm XB</div>
                            <div class="spec-value"><?php echo $sach['nam_xuat_ban']; ?></div>
                        </div>
                        <div class="spec-row">
                            <div class="spec-label">Ngôn Ngữ</div>
                            <div class="spec-value"><?php echo htmlspecialchars($sach['ngon_ngu']); ?></div>
                        </div>
                        <div class="spec-row">
                            <div class="spec-label">Trọng lượng (gr)</div>
                            <div class="spec-value"><?php echo $sach['trong_luong'] ?? rand(200, 800); ?></div>
                        </div>
                        <div class="spec-row">
                            <div class="spec-label">Kích Thước Bao Bì</div>
                            <div class="spec-value"><?php echo $sach['kich_thuoc'] ?? '24 x 16 x 2.3 cm'; ?></div>
                        </div>
                        <div class="spec-row">
                            <div class="spec-label">Số trang</div>
                            <div class="spec-value"><?php echo $sach['so_trang']; ?></div>
                        </div>
                        <div class="spec-row">
                            <div class="spec-label">Hình thức</div>
                            <div class="spec-value"><?php echo htmlspecialchars($sach['hinh_thuc_bia']); ?></div>
                        </div>
                    </div>
                </div>
                <!-- Description Section -->
                <div class="description-section" style="max-width: 1400px;">
                    <h2 class="section-title" style="color: #333">Mô tả sản phẩm</h2>

                    <div id="description-content" class="description-content collapsed">
                        <h3><?php echo htmlspecialchars($sach['ten_sach']); ?></h3>

                        <div style="margin-bottom: 20px;">
                            <?php echo nl2br(htmlspecialchars($sach['mo_ta'] ?? 'Đang cập nhật mô tả sản phẩm.')); ?>
                        </div>
                    </div>

                    <div style="text-align: center; margin-top: 20px;">
                        <button id="toggleDescription" class="btn-toggle-description" onclick="toggleDescription()">
                            Xem thêm ▼
                        </button>
                    </div>
                </div>

                <!-- Reviews Section -->
                <div class="reviews-section">
                    <h2 class="section-title" style="color: #333">Đánh giá sản phẩm</h2>

                    <?php if (count($danh_gia_list) > 0): ?>
                        <?php foreach ($danh_gia_list as $dg): ?>
                            <div class="review-item">
                                <div class="review-header">
                                    <span class="reviewer-name"><?php echo htmlspecialchars($dg['ho_ten']); ?></span>
                                    <span class="review-date"><?php echo date('d/m/Y', strtotime($dg['ngay_danh_gia'])); ?></span>
                                </div>
                                <div class="review-stars">
                                    <?php echo str_repeat('★', $dg['diem_danh_gia']) . str_repeat('☆', 5 - $dg['diem_danh_gia']); ?>
                                </div>
                                <div class="review-content">
                                    <?php echo nl2br(htmlspecialchars($dg['noi_dung'])); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p style="color: #999; text-align: center; padding: 40px 0;">
                            Chưa có đánh giá nào cho sản phẩm này.
                        </p>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>
</body>

</html>