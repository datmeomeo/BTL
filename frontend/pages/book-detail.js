document.addEventListener('DOMContentLoaded', function () {
    const urlParams = new URLSearchParams(window.location.search);
    const bookId = urlParams.get('id');

    if (!bookId) {
        console.error("Không tìm thấy ID sách trong URL.");
        return;
    }

    const PAGE_DETAIL_API_URL = `http://localhost/BTL/backend/api.php?route=book&action=page_detail&id=${bookId}`;
    const INCREASE_VIEW_API_URL = `http://localhost/BTL/backend/api.php?route=book&action=increase_view&id=${bookId}`;

    // Tăng lượt xem
    fetch(INCREASE_VIEW_API_URL, { method: 'POST' })
        .catch(error => console.error('Lỗi khi tăng lượt xem:', error));

    // Lấy chi tiết sách
    fetch(PAGE_DETAIL_API_URL)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(apiResponse => {
            if (apiResponse.status === 'success') {
                const book = apiResponse.data;
                updateUI(book);
            } else {
                console.error("Lỗi từ API:", apiResponse.message);
            }
        })
        .catch(error => {
            console.error('Lỗi khi fetch dữ liệu:', error);
        });
});

function updateUI(book) {
    // Breadcrumb
    const breadcrumb = document.getElementById('breadcrumb');
    if (book.parentCategoryName) {
        breadcrumb.innerHTML += `<span>›</span><a href="#">${book.parentCategoryName}</a>`;
    }
    if (book.categoryName) {
        breadcrumb.innerHTML += `<span>›</span><a href="#">${book.categoryName}</a>`;
    }
    breadcrumb.innerHTML += `<span>›</span><span>${book.bookName}</span>`;

    // Images
    document.getElementById('mainImage').src = book.mainImage;
    const thumbnailContainer = document.getElementById('thumbnail-images');
    book.thumbnails.forEach((thumb, index) => {
        const thumbDiv = document.createElement('div');
        thumbDiv.className = `thumbnail ${index === 0 ? 'active' : ''}`;
        thumbDiv.onclick = () => changeImage(thumb, thumbDiv);
        thumbDiv.innerHTML = `<img src="${thumb}" alt="Thumbnail">`;
        thumbnailContainer.appendChild(thumbDiv);
    });

    // Main Info
    document.getElementById('product-title').textContent = book.bookName;

    // Meta
    const metaContainer = document.getElementById('product-meta');
    metaContainer.innerHTML = `
        <div class="meta-item"><span class="meta-label">Nhà cung cấp:</span><span class="meta-value">${book.supplierName}</span></div>
        <div class="meta-item"><span class="meta-label">Nhà xuất bản:</span><span class="meta-value">${book.publisherName}</span></div>
        <div class="meta-item"><span class="meta-label">Tác giả:</span><span class="meta-value">${book.authorName}</span></div>
        <div class="meta-item"><span class="meta-label">Hình thức bìa:</span><span class="meta-value">${book.coverForm}</span></div>
    `;

    // Rating
    const ratingContainer = document.getElementById('rating-section');
    const stars = '★'.repeat(Math.round(book.averageRating)) + '☆'.repeat(5 - Math.round(book.averageRating));
    ratingContainer.innerHTML = `
        <div class="stars">${stars}</div>
        <span class="rating-text">(${book.reviewCount} đánh giá)</span>
        <span class="rating-text">Đã bán ${Math.floor(Math.random() * 200) + 10}</span>
    `;

    // Price
    const priceContainer = document.getElementById('price-section');
    priceContainer.innerHTML = `
        <div>
            <span class="current-price">${book.sellingPrice.toLocaleString('vi-VN')}₫</span>
            ${book.originalPrice > book.sellingPrice ? `
                <span class="old-price">${book.originalPrice.toLocaleString('vi-VN')}₫</span>
                <span class="discount-badge">-${book.discountPercent}%</span>
            ` : ''}
        </div>
    `;
    
    // Stock
    document.getElementById('stock-info').textContent = `${book.stockQuantity} nhà sách còn hàng`;
    
    // Action Buttons
    const actionButtons = document.getElementById('action-buttons');
    actionButtons.innerHTML = `
        <button class="btn-action btn-add-cart">Thêm vào giỏ hàng</button>
        <button class="btn-action btn-buy-now">Mua ngay</button>
    `;

    // Specifications
    const specTable = document.getElementById('spec-table');
    specTable.innerHTML = `
        <div class="spec-row"><div class="spec-label">Mã hàng</div><div class="spec-value">${book.isbn}</div></div>
        <div class="spec-row"><div class="spec-label">Tên Nhà Cung Cấp</div><div class="spec-value">${book.supplierName}</div></div>
        <div class="spec-row"><div class="spec-label">Tác giả</div><div class="spec-value">${book.authorName}</div></div>
        <div class="spec-row"><div class="spec-label">NXB</div><div class="spec-value">${book.publisherName}</div></div>
        <div class="spec-row"><div class="spec-label">Năm XB</div><div class="spec-value">${book.publishYear}</div></div>
        <div class="spec-row"><div class="spec-label">Số trang</div><div class="spec-value">${book.pageCount}</div></div>
    `;

    // Description
    document.getElementById('description-content').innerHTML = `<h3>${book.bookName}</h3><div>${book.description.replace(/\n/g, '<br>')}</div>`;

    // Reviews
    const reviewsContainer = document.getElementById('reviews-container');
    if (book.reviews.length > 0) {
        book.reviews.forEach(review => {
            const reviewStars = '★'.repeat(review.rating) + '☆'.repeat(5 - review.rating);
            reviewsContainer.innerHTML += `
                <div class="review-item">
                    <div class="review-header">
                        <span class="reviewer-name">${review.reviewerName}</span>
                        <span class="review-date">${new Date(review.reviewDate).toLocaleDateString('vi-VN')}</span>
                    </div>
                    <div class="review-stars">${reviewStars}</div>
                    <div class="review-content">${review.content}</div>
                </div>
            `;
        });
    } else {
        reviewsContainer.innerHTML = '<p>Chưa có đánh giá nào.</p>';
    }
}

function changeImage(src, element) {
    document.getElementById('mainImage').src = src;
    document.querySelectorAll('.thumbnail').forEach(thumb => thumb.classList.remove('active'));
    element.classList.add('active');
}

function toggleDescription() {
    const content = document.getElementById('description-content');
    const button = document.getElementById('toggleDescription');
    content.classList.toggle('collapsed');
    if (content.classList.contains('collapsed')) {
        button.textContent = 'Xem thêm ▼';
    } else {
        button.textContent = 'Thu gọn ▲';
    }
}
