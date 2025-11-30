import { escapeHTML, formatCurrency } from '../../utils/utils.js';

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
    // === RENDER MAIN ===
    render(book) {
        document.title = book.bookName;
        
        BookUI.renderBreadcrumb(book);
        BookUI.renderImages(book);
        BookUI.els.title.textContent = book.bookName;
        BookUI.renderMeta(book);
        BookUI.renderRating(book);
        BookUI.renderPrice(book);
        BookUI.els.stock.textContent = `${book.stockQuantity} nhà sách còn hàng`;
        BookUI.renderSpecs(book);
        BookUI.renderDescription(book);
        BookUI.renderReviews(book.reviews);
    },

    // === RENDER CHI TIẾT ===
    renderBreadcrumb(book) {
        let html = '<a href="index.php">Trang chủ</a>';
        if (book.parentCategoryName) html += `<span>›</span><a href="#">${escapeHTML(book.parentCategoryName)}</a>`;
        if (book.categoryName) html += `<span>›</span><a href="#">${escapeHTML(book.categoryName)}</a>`;
        html += `<span>›</span><span>${escapeHTML(book.bookName)}</span>`;
        BookUI.els.breadcrumb.innerHTML = html;
    },

    renderImages(book) {
        // Ảnh chính
        BookUI.els.mainImage.src = book.mainImage || '../img/no-image.jpg';
        
        // Thumbnails
        if (book.thumbnails && book.thumbnails.length > 0) {
            BookUI.els.thumbnails.innerHTML = book.thumbnails.map((thumb, index) => `
                <div class="thumbnail ${index === 0 ? 'active' : ''}", BookUI)">
                    <img class="thumbnail-image" src="${escapeHTML(thumb)}" alt="Thumbnail">
                </div>
            `).join('');
        }
    },

    renderMeta(book) {
        BookUI.els.meta.innerHTML = `
            <div class="meta-item"><span class="meta-label">Nhà cung cấp:</span><span class="meta-value">${escapeHTML(book.supplierName)}</span></div>
            <div class="meta-item"><span class="meta-label">Nhà xuất bản:</span><span class="meta-value">${escapeHTML(book.publisherName)}</span></div>
            <div class="meta-item"><span class="meta-label">Tác giả:</span><span class="meta-value">${escapeHTML(book.authorName)}</span></div>
            <div class="meta-item"><span class="meta-label">Hình thức:</span><span class="meta-value">${escapeHTML(book.coverForm)}</span></div>
        `;
    },

    renderRating(book) {
        const full = Math.round(book.averageRating || 0);
        const sold = Math.floor(Math.random() * 200) + 10;
        BookUI.els.rating.innerHTML = `
            <div class="stars">${'★'.repeat(full)}${'☆'.repeat(5 - full)}</div>
            <span class="rating-text">(${book.reviewCount} đánh giá)</span>
            <span class="rating-text"> | Đã bán ${sold}</span>
        `;
    },

    renderPrice(book) {
        const oldPrice = book.originalPrice > book.sellingPrice ? 
            `<span class="old-price">${formatCurrency(book.originalPrice)}</span>
             <span class="discount-badge">-${book.discountPercent}%</span>` : '';
        
        BookUI.els.price.innerHTML = `
            <div><span class="current-price">${formatCurrency(book.sellingPrice)}</span>${oldPrice}</div>
        `;
    },

    renderSpecs(book) {
        BookUI.els.specs.innerHTML = `
            ${BookUI._row('Mã hàng', book.isbn)}
            ${BookUI._row('Nhà Cung Cấp', book.supplierName)}
            ${BookUI._row('Tác giả', book.authorName)}
            ${BookUI._row('NXB', book.publisherName)}
            ${BookUI._row('Năm XB', book.publishYear)}
            ${BookUI._row('Số trang', book.pageCount)}
        `;
    },

    _row(label, val) { // Helper nhỏ cho Spec
        return `<div class="spec-row"><div class="spec-label">${label}</div><div class="spec-value">${escapeHTML(val)}</div></div>`;
    },

    renderDescription(book) {
        BookUI.els.desc.innerHTML = `
            <h3>${escapeHTML(book.bookName)}</h3>
            <div>${(book.description || '').replace(/\n/g, '<br>')}</div>
        `;
    },

    renderReviews(reviews) {
        if (!reviews || reviews.length === 0) {
            BookUI.els.reviews.innerHTML = '<p style="text-align:center; color:#999">Chưa có đánh giá nào.</p>';
            return;
        }
        BookUI.els.reviews.innerHTML = reviews.map(rv => `
            <div class="review-item">
                <div class="review-header">
                    <span class="reviewer-name">${escapeHTML(rv.reviewerName)}</span>
                    <span class="review-date">${new Date(rv.reviewDate).toLocaleDateString('vi-VN')}</span>
                </div>
                <div class="review-stars">${'★'.repeat(rv.rating)}${'☆'.repeat(5 - rv.rating)}</div>
                <div class="review-content">${escapeHTML(rv.content)}</div>
            </div>
        `).join('');
    },

    // === INTERACTION (Được gọi từ HTML) ===
    changeImage(src, element) {
        BookUI.els.mainImage.src = src;
        document.querySelectorAll('.thumbnail').forEach(t => t.classList.remove('active'));
        element.classList.add('active');
    },

    toggleDescription() {
        BookUI.els.desc.classList.toggle('collapsed');
        const isCollapsed = BookUI.els.desc.classList.contains('collapsed');
        document.getElementById('toggleDescription').textContent = isCollapsed ? 'Xem thêm ▼' : 'Thu gọn ▲';
    },

    showLoading() {
        BookUI.els.container.style.opacity = '0.5';
        BookUI.els.container.style.pointerEvents = 'none';
    },

    hideLoading() {
        BookUI.els.container.style.opacity = '1';
        BookUI.els.container.style.pointerEvents = 'auto';
    },
};

export default BookUI;