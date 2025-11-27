<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết sản phẩm</title>
</head>
<html>

<body>
    <div class="container-detail">
        <!-- Breadcrumb -->
        <div class="breadcrumb">
            <a href="../giaodien/trangchu.php">Trang chủ</a>
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
                            <div class="policy-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"></path><path d="M15 18H9"></path><path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14"></path><circle cx="17" cy="18" r="2"></circle><circle cx="7" cy="18" r="2"></circle></svg>
                            </div>
                            <div class="policy-text">
                                <strong>Thời gian giao hàng:</strong> Giao nhanh và uy tín
                            </div>
                            <div class="policy-arrow">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"></path></svg>
                            </div>
                        </div>
                        <div class="policy-item">
                            <div class="policy-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path><path d="M3 3v5h5"></path></svg>
                            </div>
                            <div class="policy-text">
                                <strong>Chính sách đổi trả:</strong> Đổi trả miễn phí toàn quốc
                            </div>
                            <div class="policy-arrow">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"></path></svg>
                            </div>
                        </div>
                        <div class="policy-item">
                            <div class="policy-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 12v6a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-6"></path><path d="M12 12V3"></path><path d="m4 8 8-5 8 5"></path><path d="M12 12 4 8"></path><path d="m12 12 8-4"></path></svg>
                            </div>
                            <div class="policy-text">
                                <strong>Chính sách khách sỉ:</strong> Ưu đãi khi mua số lượng lớn
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN: Product Info -->
            <div class="product-right-column">
                <section id="product-main" class="product-info-card">
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
                    </div>

                    <!-- Stock Info -->
                    <div class="stock-info">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle; margin-right: 4px;"><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"></path><path d="m3.3 7 8.7 5 8.7-5"></path><path d="M12 22V12"></path></svg>
                        <?php echo $sach['so_luong_ton']; ?> nhà sách còn hàng
                    </div>

                    <!-- Quantity -->
                    <div class="quantity-section">
                        <span class="quantity-label">Số lượng:</span>
                        <!-- <div class="quantity-control">
                            <button class="qty-btn" onclick="decreaseQty()">-</button>
                            <input type="number" id="quantity" class="qty-input" value="1" min="1" max="<?php echo $sach['so_luong_ton']; ?>">
                            <button class="qty-btn" onclick="increaseQty()">+</button>
                            
                        </div> -->
                        <div class="product-view-quantity-box-block ">
                            <a class="btn-subtract-qty" onclick="cart.subtractQty('424379', event);">
                                <img style="width: 12px; height: auto;vertical-align: middle;" src="https://cdn1.fahasa.com/skin//frontend/ma_vanese/fahasa/images/ico_minus2x.png">
                            </a>
                            <input type="text" class="qty-carts" name="cart[424379][qty]" id="qty-424379" maxlength="12" align="center" value="1" onkeypress="cart.validateNumber(event)" onchange="cart.validateQty(424379)" title="So luong">
                            <a class="btn-add-qty" onclick="cart.addQty('424379', event);">
                                <img style="width: 12px; height: auto;vertical-align: middle;" src="https://cdn1.fahasa.com/skin/frontend/ma_vanese/fahasa/images/ico_plus2x.png">
                            </a>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="action-buttons">
                        <button class="btn-action btn-add-cart js-add-to-cart"
                                data-id="<?php echo $sach['ma_sach']; ?>"
                                data-name="<?php echo htmlspecialchars($sach['ten_sach']); ?>"
                                data-price="<?php echo $sach['gia_ban']; ?>"
                                data-image="<?php echo ($hinh_anh[0]['duong_dan_hinh'] ?? '../img/no-image.jpg'); ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle; margin-right: 6px;"><circle cx="8" cy="21" r="1"></circle><circle cx="19" cy="21" r="1"></circle><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"></path></svg>
                            Thêm vào giỏ hàng
                        </button>
                        <button class="btn-action btn-buy-now js-buy-now"
                                data-id="<?php echo $sach['ma_sach']; ?>"
                                data-name="<?php echo htmlspecialchars($sach['ten_sach']); ?>"
                                data-price="<?php echo $sach['gia_ban']; ?>"
                                data-image="<?php echo ($hinh_anh[0]['duong_dan_hinh'] ?? '../img/no-image.jpg'); ?>">
                            Mua ngay
                        </button>
                    </div>
                </section>

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
            </div>
        </div>

        <!-- BOTTOM SECTION: Description & Reviews (Full Width) -->
        <div class="product-bottom-section">
            <!-- Description Section -->
            <section id="product-details" class="description-section">
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
            </section>
    
            <!-- Reviews Section -->
            <section id="product-reviews" class="reviews-section">
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
            </section>
        </div>
    </div>
</body>

</html>