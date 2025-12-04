const SearchProductUI = {
    // Hàm xử lý đường dẫn ảnh để tránh lỗi hiển thị
    fixImagePath(path) {
        if (!path) return './assets/img/default-book.jpg';
        
        // Nếu đường dẫn bắt đầu bằng ../ (thường do lưu từ trang admin)
        // Chúng ta đổi thành ./assets/ để phù hợp với frontend
        if (path.startsWith('../')) {
            return path.replace('../', './assets/');
        }
        
        // Nếu đường dẫn chưa có ./assets (ví dụ chỉ lưu tên file)
        if (!path.startsWith('./') && !path.startsWith('http')) {
            return `./assets/${path}`;
        }

        return path;
    },

    renderListBooks(books) {
        console.log("UI received books:", books); // Debug: Kiểm tra xem dữ liệu vào UI chưa
        
        const container = document.getElementById('product-list-container');
        if (!container) {
            console.error("Lỗi: Không tìm thấy thẻ HTML có id='product-list-container'");
            return;
        }

        if (!books || books.length === 0) {
            container.innerHTML = `
                <div class="col-12 text-center py-5">
                    <i class="bi bi-inbox fs-1 text-muted"></i>
                    <p class="mt-3 text-muted">Không tìm thấy sản phẩm nào phù hợp.</p>
                </div>`;
            return;
        }

        const html = books.map(book => {
            // Format giá tiền
            const sellingPrice = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(book.sellingPrice);
            const originalPrice = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(book.originalPrice);
            
            // Xử lý ảnh (Quan trọng!)
            const imgSrc = this.fixImagePath(book.mainImage);

            // Tính toán hiển thị giảm giá
            const discountBadge = book.discountPercent > 0 
                ? `<span class="position-absolute top-0 start-0 bg-danger text-white px-2 py-1 m-2 rounded small">-${book.discountPercent}%</span>` 
                : '';
            
            const priceBlock = book.discountPercent > 0
                ? `<div class="d-flex align-items-center gap-2">
                     <span class="text-danger fw-bold">${sellingPrice}</span>
                     <span class="text-muted text-decoration-line-through small">${originalPrice}</span>
                   </div>`
                : `<span class="text-danger fw-bold">${sellingPrice}</span>`;

            return `
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm product-card">
                        <div class="position-relative overflow-hidden group-image">
                            ${discountBadge}
                            <a href="index.php?page=book&id=${book.bookId}">
                                <img src="${imgSrc}" 
                                     class="card-img-top object-fit-contain p-3 transition-transform" 
                                     alt="${book.bookName}" 
                                     style="height: 200px; width: 100%;"
                                     onerror="this.src='./assets/img/default-book.jpg'"> 
                            </a>
                        </div>
                        <div class="card-body d-flex flex-column p-3">
                            <div class="mb-2">
                                <span class="badge bg-light text-secondary fw-normal border">${book.categoryName}</span>
                            </div>
                            <a href="index.php?page=book&id=${book.bookId}" class="text-decoration-none text-dark mb-2">
                                <h6 class="card-title text-truncate-2 lh-base" title="${book.bookName}" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; height: 40px;">
                                    ${book.bookName}
                                </h6>
                            </a>
                            <div class="mt-auto">
                                ${priceBlock}
                                <div class="d-flex align-items-center mt-2 text-muted small">
                                    <i class="bi bi-pen me-1"></i> ${book.authorName}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }).join('');

        container.innerHTML = html;
    },

    // Render danh mục (Sidebar)
    renderCategories(categories) {
        const container = document.getElementById('filter-category');
        if (!container) return;

        if(!categories || categories.length === 0) {
            container.innerHTML = '<div class="small text-muted">Chưa có danh mục</div>';
            return;
        }

        const html = categories.map(cat => `
            <div class="form-check my-1">
                <input class="form-check-input" type="radio" name="category_filter" value="${cat.ma_danh_muc}" id="cat-${cat.ma_danh_muc}">
                <label class="form-check-label small" for="cat-${cat.ma_danh_muc}" role="button">
                    ${cat.ten_danh_muc}
                </label>
            </div>
        `).join('');
        container.innerHTML = html;
    },

    // Render tác giả (Sidebar)
    renderAuthors(authors) {
        const container = document.getElementById('filter-author');
        if (!container) return;

        if(!authors || authors.length === 0) {
            container.innerHTML = '<div class="small text-muted">Chưa có tác giả</div>';
            return;
        }

        const html = authors.slice(0, 10).map(auth => `
            <div class="form-check my-1">
                <input class="form-check-input" type="checkbox" name="author_filter" value="${auth.ten_tac_gia}" id="auth-${auth.ma_tac_gia}">
                <label class="form-check-label small" for="auth-${auth.ma_tac_gia}" role="button">
                    ${auth.ten_tac_gia}
                </label>
            </div>
        `).join('');
        container.innerHTML = html;
    }
};

export default SearchProductUI;