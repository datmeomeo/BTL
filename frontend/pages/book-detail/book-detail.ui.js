const BookUI = {
    // Cache các element để không phải query nhiều lần
    els: {
        container: document.querySelector('.container-detail'),
        breadcrumb: document.getElementById('breadcrumb'),
        mainImage: document.getElementById('mainImage'),
        thumbnails: document.getElementById('thumbnail-images'),
        title: document.getElementById('product-title'),
        meta: document.getElementById('product-meta'),
        rating: document.getElementById('rating-section'),
        price: document.getElementById('price-section'),
        stock: document.getElementById('stock-info'),
        actions: document.getElementById('action-buttons'),
        specs: document.getElementById('spec-table'),
        desc: document.getElementById('description-content'),
        reviews: document.getElementById('reviews-container'),
        toggleBtn: document.getElementById('toggleDescription')
    },

    // === HELPER FUNCTIONS ===
    escapeHTML(str) {
        if (!str) return '';
        return String(str).replace(/[&<>"']/g, function(m) {
            return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' }[m];
        });
    },

    formatCurrency(amount) {
        return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount);
    },

    // === RENDER MAIN ===
    render(book) {
        document.title = book.bookName;
        
        this.renderBreadcrumb(book);
        this.renderImages(book);
        this.els.title.textContent = book.bookName;
        this.renderMeta(book);
        this.renderRating(book);
        this.renderPrice(book);
        this.els.stock.textContent = `${book.stockQuantity} nhà sách còn hàng`;
        this.renderActions();
        this.renderSpecs(book);
        this.renderDescription(book);
        this.renderReviews(book.reviews);
    },

    // === RENDER CHI TIẾT ===
    renderBreadcrumb(book) {
        let html = '<a href="index.php">Trang chủ</a>';
        if (book.parentCategoryName) html += `<span>›</span><a href="#">${this.escapeHTML(book.parentCategoryName)}</a>`;
        if (book.categoryName) html += `<span>›</span><a href="#">${this.escapeHTML(book.categoryName)}</a>`;
        html += `<span>›</span><span>${this.escapeHTML(book.bookName)}</span>`;
        this.els.breadcrumb.innerHTML = html;
    },

    renderImages(book) {
        // Ảnh chính
        this.els.mainImage.src = book.mainImage || '../img/no-image.jpg';
        
        // Thumbnails
        if (book.thumbnails && book.thumbnails.length > 0) {
            this.els.thumbnails.innerHTML = book.thumbnails.map((thumb, index) => `
                <div class="thumbnail ${index === 0 ? 'active' : ''}" onclick="BookUI.changeImage('${this.escapeHTML(thumb)}', this)">
                    <img src="${this.escapeHTML(thumb)}" alt="Thumbnail">
                </div>
            `).join('');
        }
    },

    renderMeta(book) {
        this.els.meta.innerHTML = `
            <div class="meta-item"><span class="meta-label">Nhà cung cấp:</span><span class="meta-value">${this.escapeHTML(book.supplierName)}</span></div>
            <div class="meta-item"><span class="meta-label">Nhà xuất bản:</span><span class="meta-value">${this.escapeHTML(book.publisherName)}</span></div>
            <div class="meta-item"><span class="meta-label">Tác giả:</span><span class="meta-value">${this.escapeHTML(book.authorName)}</span></div>
            <div class="meta-item"><span class="meta-label">Hình thức:</span><span class="meta-value">${this.escapeHTML(book.coverForm)}</span></div>
        `;
    },

    renderRating(book) {
        const full = Math.round(book.averageRating || 0);
        const sold = Math.floor(Math.random() * 200) + 10;
        this.els.rating.innerHTML = `
            <div class="stars">${'★'.repeat(full)}${'☆'.repeat(5 - full)}</div>
            <span class="rating-text">(${book.reviewCount} đánh giá)</span>
            <span class="rating-text"> | Đã bán ${sold}</span>
        `;
    },

    renderPrice(book) {
        const oldPrice = book.originalPrice > book.sellingPrice ? 
            `<span class="old-price">${this.formatCurrency(book.originalPrice)}</span>
             <span class="discount-badge">-${book.discountPercent}%</span>` : '';
        
        this.els.price.innerHTML = `
            <div><span class="current-price">${this.formatCurrency(book.sellingPrice)}</span>${oldPrice}</div>
        `;
    },

    renderActions() {
        this.els.actions.innerHTML = `
            <button class="btn-action btn-add-cart">Thêm vào giỏ hàng</button>
            <button class="btn-action btn-buy-now">Mua ngay</button>
        `;
    },

    renderSpecs(book) {
        this.els.specs.innerHTML = `
            ${this._row('Mã hàng', book.isbn)}
            ${this._row('Nhà Cung Cấp', book.supplierName)}
            ${this._row('Tác giả', book.authorName)}
            ${this._row('NXB', book.publisherName)}
            ${this._row('Năm XB', book.publishYear)}
            ${this._row('Số trang', book.pageCount)}
        `;
    },

    _row(label, val) { // Helper nhỏ cho Spec
        return `<div class="spec-row"><div class="spec-label">${label}</div><div class="spec-value">${this.escapeHTML(val)}</div></div>`;
    },

    renderDescription(book) {
        this.els.desc.innerHTML = `
            <h3>${this.escapeHTML(book.bookName)}</h3>
            <div>${(book.description || '').replace(/\n/g, '<br>')}</div>
        `;
    },

    renderReviews(reviews) {
        if (!reviews || reviews.length === 0) {
            this.els.reviews.innerHTML = '<p style="text-align:center; color:#999">Chưa có đánh giá nào.</p>';
            return;
        }
        this.els.reviews.innerHTML = reviews.map(rv => `
            <div class="review-item">
                <div class="review-header">
                    <span class="reviewer-name">${this.escapeHTML(rv.reviewerName)}</span>
                    <span class="review-date">${new Date(rv.reviewDate).toLocaleDateString('vi-VN')}</span>
                </div>
                <div class="review-stars">${'★'.repeat(rv.rating)}${'☆'.repeat(5 - rv.rating)}</div>
                <div class="review-content">${this.escapeHTML(rv.content)}</div>
            </div>
        `).join('');
    },

    // === INTERACTION (Được gọi từ HTML) ===
    changeImage(src, element) {
        this.els.mainImage.src = src;
        document.querySelectorAll('.thumbnail').forEach(t => t.classList.remove('active'));
        element.classList.add('active');
    },

    toggleDescription() {
        this.els.desc.classList.toggle('collapsed');
        const isCollapsed = this.els.desc.classList.contains('collapsed');
        document.getElementById('toggleDescription').textContent = isCollapsed ? 'Xem thêm ▼' : 'Thu gọn ▲';
    },

    showLoading() {
        this.els.container.style.opacity = '0.5';
        this.els.container.style.pointerEvents = 'none';
    },

    hideLoading() {
        this.els.container.style.opacity = '1';
        this.els.container.style.pointerEvents = 'auto';
    },

    showError(msg) {
        this.els.container.innerHTML = `<div style="text-align:center; padding:50px; color:red;"><h3>⚠️ Lỗi</h3><p>${msg}</p></div>`;
    }
};