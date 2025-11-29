<div class="container-detail">
    <!-- Breadcrumb -->
    <div class="breadcrumb" id="breadcrumb">
        <a href="index.php">Trang chủ</a>
    </div>

    <!-- Product Detail Wrapper -->
    <div class="product-detail-wrapper">
        <div class="product-left-column">
            <div class="product-images-card">
                <!-- Main Image -->
                <div class="main-image">
                    <img id="mainImage" src="" alt="Loading...">
                </div>

                <!-- Thumbnail Images -->
                <div class="thumbnail-images" id="thumbnail-images">
                    <!-- Thumbnails will be injected by JS -->
                </div>
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
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: Product Info -->
        <div class="product-right-column">
            <section id="product-main" class="product-info-card">
                <!-- Product Title -->
                <h1 class="product-title" id="product-title"></h1>

                <!-- Product Meta -->
                <div class="product-meta" id="product-meta">
                    <!-- Meta items will be injected by JS -->
                </div>

                <!-- Rating -->
                <div class="rating-section" id="rating-section">
                    <!-- Rating will be injected by JS -->
                </div>

                <!-- Price Section -->
                <div class="price-section" id="price-section">
                    <!-- Price will be injected by JS -->
                </div>

                <!-- Stock Info -->
                <div class="stock-info" id="stock-info">
                    <!-- Stock info will be injected by JS -->
                </div>

                <!-- Quantity -->
                <div class="quantity-section">
                    <span class="quantity-label">Số lượng:</span>
                    <div class="product-view-quantity-box-block">
                        <a class="btn-subtract-qty">
                            <img style="width: 12px; height: auto;vertical-align: middle;" src="https://cdn1.fahasa.com/skin//frontend/ma_vanese/fahasa/images/ico_minus2x.png">
                        </a>
                        <input type="text" class="qty-carts" value="1">
                        <a class="btn-add-qty">
                            <img style="width: 12px; height: auto;vertical-align: middle;" src="https://cdn1.fahasa.com/skin/frontend/ma_vanese/fahasa/images/ico_plus2x.png">
                        </a>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons" id="action-buttons">
                    <button class="btn-action btn-add-cart">Thêm vào giỏ hàng</button>
                    <button class="btn-action btn-buy-now">Mua ngay</button>
                </div>
            </section>

            <!-- Specifications Card -->
            <div class="specifications-card">
                <div class="spec-title">Thông tin chi tiết</div>
                <div class="spec-table" id="spec-table">
                    <!-- Specs will be injected by JS -->
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
                <!-- Description will be injected by JS -->
            </div>
            <div style="text-align: center; margin-top: 20px;">
                <button id="toggleDescription" class="btn-toggle-description" onclick="BookUI.toggleDescription()">
                    Xem thêm ▼
                </button>
            </div>
        </section>

        <!-- Reviews Section -->
        <section id="product-reviews" class="reviews-section">
            <h2 class="section-title" style="color: #333">Đánh giá sản phẩm</h2>
            <div id="reviews-container">
                <!-- Reviews will be injected by JS -->
            </div>
        </section>
    </div>
</div>