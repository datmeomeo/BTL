<div class="container-detail">
    <div class="breadcrumb" id="breadcrumb">
        <a href="index.php">Trang chủ</a>
    </div>

    <div class="product-detail-wrapper">
        <div class="product-left-column">
            <div class="product-images-card">
                <div class="main-image">
                    <img id="mainImage" src="" alt="Loading...">
                </div>

                <div class="thumbnail-images" id="thumbnail-images">
                    </div>
            </div>

            <div class="policy-card">
                <div class="fahasa-policy-section">
                    <div class="policy-title">Chính sách ưu đãi của Fahasa</div>
                    
                    <div class="policy-item">
                        <div class="policy-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
                        </div>
                        <div class="policy-text">
                            <strong>Thời gian giao hàng:</strong> Giao nhanh và uy tín
                        </div>
                    </div>

                    <div class="policy-item">
                        <div class="policy-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                        </div>
                        <div class="policy-text">
                            <strong>Chính sách đổi trả:</strong> Đổi trả miễn phí toàn quốc
                        </div>
                    </div>

                    <div class="policy-item">
                        <div class="policy-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18v-8a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v8z"></path><path d="M5 11l1-7h12l1 7"></path><path d="M9 21v-8"></path><path d="M15 21v-8"></path></svg>
                        </div>
                        <div class="policy-text">
                            <strong>Chính sách khách sỉ:</strong> Ưu đãi khi mua số lượng lớn
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="product-right-column">
            <section id="product-main" class="product-info-card">
                <h1 class="product-title" id="product-title"></h1>

                <div class="product-meta" id="product-meta">
                    </div>

                <div class="rating-section" id="rating-section">
                    </div>

                <div class="price-section" id="price-section">
                    </div>

                <div class="stock-info" id="stock-info">
                    </div>

                <div class="quantity-section">
                    <span class="quantity-label">Số lượng:</span>
                    <div class="quantity-control-group">
                        <button class="qty-btn btn-subtract-qty" type="button">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                        </button>
                        <input type="number" id="qty-carts" class="qty-input" value="1" min="1">
                        <button class="qty-btn btn-add-qty" type="button">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                        </button>
                    </div>
                </div>

                <div class="action-buttons" id="action-buttons">
                    <button class="btn-action btn-add-cart" id="btn-add-cart">Thêm vào giỏ hàng</button>
                    <button class="btn-action btn-buy-now" id="btn-buy-now">Mua ngay</button>
                </div>
            </section>

            <div class="specifications-card">
                <div class="spec-title">Thông tin chi tiết</div>
                <div class="spec-table" id="spec-table">
                    </div>
            </div>
        </div>
    </div>

    <div class="product-bottom-section">
        <section id="product-details" class="description-section">
            <h2 class="section-title" style="color: #333">Mô tả sản phẩm</h2>
            <div id="description-content" class="description-content collapsed">
                </div>
            <div style="text-align: center; margin-top: 20px;">
                <button id="toggleDescription" class="btn-toggle-description" onclick="BookUI.toggleDescription()">
                    Xem thêm ▼
                </button>
            </div>
        </section>

        <section id="product-reviews" class="reviews-section">
            <h2 class="section-title" style="color: #333">Đánh giá sản phẩm</h2>
            <div id="reviews-container">
                </div>
        </section>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const decreaseBtn = document.querySelector('.btn-subtract-qty');
        const increaseBtn = document.querySelector('.btn-add-qty');
        const qtyInput = document.getElementById('qty-carts');

        if (decreaseBtn && increaseBtn && qtyInput) {
            // Tăng
            increaseBtn.addEventListener('click', () => {
                let val = parseInt(qtyInput.value) || 1;
                qtyInput.value = val + 1;
            });
            // Giảm (Không cho xuống dưới 1)
            decreaseBtn.addEventListener('click', () => {
                let val = parseInt(qtyInput.value) || 1;
                if (val > 1) {
                    qtyInput.value = val - 1;
                }
            });
            
            // Chặn nhập tay số âm
            qtyInput.addEventListener('change', () => {
                if (qtyInput.value < 1) qtyInput.value = 1;
            });
        }
    });
</script>