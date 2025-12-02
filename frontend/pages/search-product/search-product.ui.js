import { escapeHTML, formatCurrency } from '../../utils/utils.js';

const searchProductUI ={
    els: {
        categoryFilter: document.getElementById('category-filter'),//1
        authorFilter: document.getElementById('publisher-filter'), //2
        typeFilter: document.getElementById('type-filter'),
        priceFilter: document.getElementById('price-filter'),
        productGrid: document.getElementById('product-grid'),
        pagination: document.getElementById('pagination'),
        sortSelect: document.getElementById('sort-select'),
        limitSelect: document.getElementById('limit-select')
    },

    // Render Danh mục sản phẩm

    renderCategories (category) {
        if (!categories || categories.length === 0) {
            this.els.categoryFilter.innerHTML = '<p style="color:#999">Không có danh mục</p>';
            return;
        }

        let html = `
            <div class="filter-item">
                <label>
                    <input type="checkbox" name="category" value="all" checked>
                    Tất Cả Nhóm Sản Phẩm
                </label>
            </div>
        `;

        categories.forEach(cat => {
            html += `
                <div class="filter-item">
                    <label>
                        <input type="checkbox" name="category" value="${cat.id}">
                        ${escapeHTML(cat.name)}
                    </label>
                </div>
            `;

            // Nếu có subcategories
            if (cat.subcategories && cat.subcategories.length > 0) {
                html += '<div class="subcategory">';
                cat.subcategories.forEach(sub => {
                    html += `
                        <div class="filter-item">
                            <label>
                                <input type="checkbox" name="category" value="${sub.id}">
                                ${escapeHTML(sub.name)}
                            </label>
                        </div>
                    `;
                });
                html += '</div>';
            }
        });

        html += '<span class="view-more">Xem Thêm</span>';
        this.els.categoryFilter.innerHTML = html;
    },

    // Render nhà xuất bản
    renderAuthors(publisher) {
        if (!publisher || publisher.length === 0) {
            this.els.authorFilter.innerHTML = '<p style="color:#999">Không có nhà xuất bản</p>';
            return;
        }

        let html = '';
        const displayCount = 5;

        authors.slice(0, displayCount).forEach(author => {
            html += `
                <div class="filter-item">
                    <label>
                        <input type="checkbox" name="author" value="${publisher.id}">
                        ${escapeHTML(publisher.name)}
                    </label>
                </div>
            `;
        });

        if (publisher.length > displayCount) {
            html += '<span class="view-more">Xem Thêm</span>';
        }

        this.els.authorFilter.innerHTML = html;
    },


    // Render Loại sách
        renderBookTypes(types) {
        if (!types || types.length === 0) {
            this.els.typeFilter.innerHTML = '<p style="color:#999">Không có loại sách</p>';
            return;
        }

        let html = '';
        types.forEach(type => {
            html += `
                <div class="filter-item">
                    <label>
                        <input type="checkbox" name="type" value="${type.id}">
                        ${escapeHTML(type.name)}
                    </label>
                </div>
            `;
        });

        this.els.typeFilter.innerHTML = html;
    },

    // Render giá sản phẩm
    renderProducts(books) {
        if (!books || books.length === 0) {
            this.els.productGrid.innerHTML = '<div class="loading">Không có sản phẩm nào</div>';
            return;
        }

        this.els.productGrid.innerHTML = books.map(book => {
            const hasDiscount = book.originalPrice > book.sellingPrice;
            const discountHtml = hasDiscount ? 
                `<span class="discount-badge">-${book.discountPercent}%</span>` : '';
            const oldPriceHtml = hasDiscount ? 
                `<div class="original-price">${formatCurrency(book.originalPrice)}</div>` : '';

            return `
                <div class="product-card" onclick="BookListUI.selectProduct(this, ${book.id})">
                    <span class="product-badge">HOT</span>
                    <img src="${escapeHTML(book.mainImage || '../img/no-image.jpg')}" 
                         alt="${escapeHTML(book.bookName)}" 
                         class="product-image">
                    <div class="product-name">${escapeHTML(book.bookName)}</div>
                    <div class="product-price">
                        <span class="current-price">${formatCurrency(book.sellingPrice)}</span>
                        ${discountHtml}
                    </div>
                    ${oldPriceHtml}
                    <div class="product-rating">★★★★★</div>
                </div>
            `;
        }).join('');
    },


        // === RENDER PAGINATION ===
    renderPagination(currentPage, totalPages) {
        if (totalPages <= 1) {
            this.els.pagination.innerHTML = '';
            return;
        }

        let html = '';

        // Previous button
        html += `<button ${currentPage === 1 ? 'disabled' : ''} 
                         onclick="BookListUI.goToPage(${currentPage - 1})">‹</button>`;

        // Page numbers
        const range = 2; // Số trang hiển thị trước và sau trang hiện tại
        
        for (let i = 1; i <= totalPages; i++) {
            if (i === 1 || i === totalPages || (i >= currentPage - range && i <= currentPage + range)) {
                html += `<button class="${i === currentPage ? 'active' : ''}" 
                                 onclick="BookListUI.goToPage(${i})">${i}</button>`;
            } else if (i === currentPage - range - 1 || i === currentPage + range + 1) {
                html += '<span>...</span>';
            }
        }

        // Next button
        html += `<button ${currentPage === totalPages ? 'disabled' : ''} 
                         onclick="BookListUI.goToPage(${currentPage + 1})">›</button>`;

        this.els.pagination.innerHTML = html;
    },

    // === INTERACTIONS ===
    selectProduct(element, bookId) {
        // Remove selected class from all products
        document.querySelectorAll('.product-card').forEach(card => {
            card.classList.remove('selected');
        });
        // Add selected class to clicked product
        element.classList.add('selected');
        
        // Navigate to detail page
        setTimeout(() => {
            window.location.href = `book-detail.php?id=${bookId}`;
        }, 200);
    },

    toggleFilter(element) {
        element.classList.toggle('collapsed');
        const content = element.nextElementSibling;
        content.classList.toggle('hidden');
    },

    goToPage(page) {
        // This will be handled by the main controller
        window.dispatchEvent(new CustomEvent('pageChange', { detail: { page } }));
    },

    // === STATE MANAGEMENT ===
    showLoading() {
        this.els.productGrid.innerHTML = '<div class="loading">Đang tải...</div>';
    },

    hideLoading() {
        // Loading is hidden when products are rendered
    },

    showError(msg) {
        this.els.productGrid.innerHTML = `<div class="error">⚠️ ${escapeHTML(msg)}</div>`;
    }

};
export default searchProductUI;