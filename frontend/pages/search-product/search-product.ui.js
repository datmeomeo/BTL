<<<<<<< HEAD
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
=======
// frontend/pages/search-product/search-product.ui.js
const SearchProductUI = {
  // ... Giữ nguyên fixImagePath, renderBooks, renderPagination ...
  fixImagePath(path) {
    if (!path) return "./assets/img/fahasa-logo.jpg";
    if (path.startsWith("../")) return path.replace("../", "./assets/");
    return path;
  },

  renderBooks(books) {
    const container = document.getElementById("product-list-container");
    if (!container) return;
    if (!books || books.length === 0) {
      container.innerHTML =
        '<div class="col-12 text-center py-5">Không tìm thấy sách.</div>';
      return;
    }
    container.innerHTML = books
      .map((book) => {
        const price = new Intl.NumberFormat("vi-VN", {
          style: "currency",
          currency: "VND",
        }).format(book.sellingPrice);
        const imgSrc = this.fixImagePath(book.mainImage);
        return `
            <div class="col">
                <div class="card h-100 border-0 shadow-sm product-card">
                    <div class="position-relative">
                        ${
                          book.discountPercent > 0
                            ? `<span class="badge bg-danger position-absolute top-0 start-0 m-2">-${book.discountPercent}%</span>`
                            : ""
                        }
                        <a href="index.php?page=book&id=${book.bookId}">
                            <img src="${imgSrc}" class="card-img-top p-3" style="height:220px; object-fit:contain;" onerror="this.src='./assets/img/fahasa-logo.jpg'">
                        </a>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <a href="index.php?page=book&id=${
                          book.bookId
                        }" class="text-decoration-none text-dark">
                            <h6 class="card-title text-truncate" style="-webkit-line-clamp:2; display:-webkit-box; -webkit-box-orient:vertical; overflow:hidden;">${
                              book.bookName
                            }</h6>
                        </a>
                        <div class="mt-auto fw-bold text-danger">${price}</div>
                    </div>
                </div>
            </div>`;
      })
      .join("");
  },

  renderPagination(pagination) {
    const { page, total_pages, total } = pagination;
    const container = document.getElementById("pagination-container");
    const countLabel = document.getElementById("total-books-count");
    if (countLabel) countLabel.innerText = total;
    if (!container || total_pages <= 1) {
      if (container) container.innerHTML = "";
      return;
    }
    let html = `<li class="page-item ${
      page === 1 ? "disabled" : ""
    }"><a class="page-link" href="#" data-page="${page - 1}">&laquo;</a></li>`;
    for (let i = 1; i <= total_pages; i++) {
      if (i === 1 || i === total_pages || (i >= page - 1 && i <= page + 1)) {
        html += `<li class="page-item ${
          i === page ? "active" : ""
        }"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
      } else if (i === page - 2 || i === page + 2) {
        html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
      }
    }
    html += `<li class="page-item ${
      page === total_pages ? "disabled" : ""
    }"><a class="page-link" href="#" data-page="${page + 1}">&raquo;</a></li>`;
    container.innerHTML = html;
  },

  // --- SỬA LOGIC CÂY DANH MỤC: LUÔN HIỂN THỊ HẾT ---
  renderCategoryTree(categories) {
    const buildTree = (items, parentId = null) => {
      return items
        .filter((item) => {
          if (parentId === null)
            return item.danh_muc_cha == null || item.danh_muc_cha == 0;
          return item.danh_muc_cha == parentId;
        })
        .map((item) => ({
          ...item,
          children: buildTree(items, item.ma_danh_muc),
        }));
    };

    const renderHTML = (nodes) => {
      if (!nodes || nodes.length === 0) return "";
      let html = "<ul>";
      nodes.forEach((node) => {
        // BỎ ICON DẤU CỘNG
        // THÊM display: none ĐỂ MẶC ĐỊNH ẨN CON
        html += `
                    <li>
                        <span class="cat-link" data-id="${node.ma_danh_muc}">
                            ${node.ten_danh_muc}
                        </span>
                        <div class="children-container" style="display: none;">
                            ${renderHTML(node.children)}
                        </div>
                    </li>`;
      });
      html += "</ul>";
      return html;
    };

    const container = document.getElementById("category-tree");
    if (container)
      container.innerHTML = renderHTML(buildTree(categories, null));
  },
  highlightActiveCategory(id) {
        // 1. Tìm thẻ link có data-id tương ứng
        const activeLink = document.querySelector(`.cat-link[data-id="${id}"]`);
        
        if (activeLink) {
            // 2. Tô đậm chính nó
            // Xóa active cũ trước (đề phòng)
            document.querySelectorAll('.cat-link').forEach(el => el.classList.remove('active', 'fw-bold', 'text-primary'));
            activeLink.classList.add('active', 'fw-bold', 'text-primary');

            // 3. Mở bung tất cả các cấp Cha/Ông (Parents)
            // Tìm ngược lên trên, cứ gặp .children-container nào đang ẩn thì hiện ra
            let parentContainer = activeLink.closest('.children-container');
            
            while (parentContainer) {
                parentContainer.style.display = 'block'; // Mở ra
                
                // Tiếp tục tìm cha của container này (để mở tiếp cấp ông nội)
                // Cấu trúc: div.children-container -> li -> ul -> div.children-container (cấp trên)
                const grandParentLi = parentContainer.closest('li').parentElement.closest('li');
                if (grandParentLi) {
                    parentContainer = grandParentLi.querySelector('.children-container');
                } else {
                    parentContainer = null; // Hết cha, dừng vòng lặp
                }
            }
            
            // 4. Cuộn màn hình tới vị trí đó cho dễ nhìn
            setTimeout(() => {
                activeLink.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }, 300);
        }
    },

  // Render Tác giả (không ẩn bớt nữa)
  renderAuthors(authors) {
    const container = document.getElementById("filter-author");
    if (!container) return;
    let html = `<div class="form-check"><input class="form-check-input" type="radio" name="author_filter" value="all" id="auth-all" checked><label class="form-check-label" for="auth-all">Tất cả</label></div>`;
    html += authors
      .map(
        (auth) => `
            <div class="form-check">
                <input class="form-check-input" type="radio" name="author_filter" value="${auth.ten_tac_gia}" id="auth-${auth.ma_tac_gia}">
                <label class="form-check-label" for="auth-${auth.ma_tac_gia}">${auth.ten_tac_gia}</label>
            </div>
        `
      )
      .join("");
    container.innerHTML = html;
  },
};
export default SearchProductUI;
>>>>>>> 6a024c67c3d9ac6366e3fcb74327a42f32e38cf5
