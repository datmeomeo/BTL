// frontend/components/suggest-book/suggest-book.ui.js
import { escapeHTML, formatCurrency, createStarIconHTML } from '../../utils/utils.js';

const SuggestBookUI = {
    els: {
        gridContainer: document.getElementById('goi-y-content'),
        loadMoreButton: document.getElementById('btn-xem-them')
    },

    /**
     * CẤU HÌNH ĐƯỜNG DẪN ẢNH
     * Bạn cần sửa 'assets/uploads/' thành đường dẫn thực tế chứa ảnh của bạn.
     * Ví dụ: 'public/images/', '/uploads/', v.v...
     */
    config: {
        imageBasePath: 'assets/img-book', // <-- SỬA ĐƯỜNG DẪN NÀY
        defaultImage: 'assets/images/no-image.png' // Ảnh hiển thị khi bị lỗi
    },

    /**
     * Xử lý logic đường dẫn ảnh
     */
    getImageUrl: function(imagePath) {
        if (!imagePath) return this.config.defaultImage;
        
        // Nếu trong DB đã lưu full link (http...) thì dùng luôn
        if (imagePath.startsWith('http') || imagePath.startsWith('data:')) {
            return imagePath;
        }
        
        // Nếu chưa có dấu / ở đầu thì nối với base path
        return this.config.imageBasePath + imagePath;
    },

    /**
     * Render một thẻ sách (book card) HTML.
     * @param {object} book Dữ liệu sách.
     * @returns {string} Chuỗi HTML của thẻ sách.
     */
    renderBookCardHTML: function(book) { // Đổi thành function thường để dùng 'this' nếu cần
        const addedDate = new Date(book.addedDate);
        const diffTime = Math.abs(new Date() - addedDate);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        const isNew = diffDays <= 7;
        const publisherName = book.publisherName ?? 'NXB Khác';
        
        // --- SỬA LỖI ẢNH TẠI ĐÂY ---
        // Lấy đường dẫn ảnh đã xử lý
        const finalImagePath = this.getImageUrl(book.imagePath);

        const discountBadgeHTML = book.discountPercent > 0 ?
            `<div class="discount-badge">-${book.discountPercent}%</div>` : '';

        const newBadgeHTML = isNew ? `<div class="new-badge">Mới</div>` : '';

        const oldPriceHTML = book.originalPrice > book.sellingPrice ?
            `<span class="old-price">${formatCurrency(book.originalPrice)}</span>` : '';

        // Thêm sự kiện onerror để nếu ảnh lỗi thì hiện ảnh mặc định
        // Lưu ý: book.bookId cần tồn tại, nếu không link sẽ bị lỗi
        const bookId = book.bookId || book.id; 

        return `
            <a href="index.php?page=book&id=${bookId}" class="sach-card">

                ${discountBadgeHTML}
                ${newBadgeHTML}

                <div class="img-wrapper">
                    <img class="sach-image"
                        src="${escapeHTML(finalImagePath)}"
                        alt="${escapeHTML(book.bookName)}"
                        loading="lazy"
                        onerror="this.onerror=null; this.src='${this.config.defaultImage}';"
                    >
                </div>

                <div class="sach-info">
                    <div class="sach-title" title="${escapeHTML(book.bookName)}">
                        ${escapeHTML(book.bookName)}
                    </div>

                    <div class="sach-publisher">
                        ${escapeHTML(publisherName)}
                    </div>

                    <div class="price-section">
                        <span class="current-price">
                            ${formatCurrency(book.sellingPrice)}
                        </span>
                        ${oldPriceHTML}
                    </div>

                    <div class="rating">
                        <span class="stars">
                            ${createStarIconHTML().repeat(5)}
                        </span>
                        <span style="color: #666;">(${Math.floor(Math.random() * 491) + 10})</span>
                    </div>

                    <div class="sold-count">
                        Đã bán ${Math.floor(Math.random() * 191) + 10}
                    </div>
                </div>
            </a>
        `;
    },

    /**
     * Render danh sách sách gợi ý vào container.
     * @param {Array<object>} books Mảng các đối tượng sách.
     */
    renderSuggestedBooks: function(books) {
        if (!this.els.gridContainer) return;

        if (books && books.length > 0) {
            // Dùng bind(this) hoặc arrow function bên trong map để truy cập được this.getImageUrl
            const booksHtml = books.map(book => this.renderBookCardHTML(book)).join('');
            this.els.gridContainer.innerHTML = booksHtml;
        } else {
            this.els.gridContainer.innerHTML = '<p style="grid-column: 1/-1; text-align: center;">Không tìm thấy sách gợi ý nào.</p>';
        }
    },

    /**
     * Gắn event listener cho nút "Xem thêm".
     * @param {function} onLoadMore Callback khi nút được click.
     */
    attachLoadMoreListener: function(onLoadMore) {
        if (this.els.loadMoreButton) {
            // Xóa listener cũ (nếu cần thiết để tránh double click) rồi mới thêm
            const newBtn = this.els.loadMoreButton.cloneNode(true);
            this.els.loadMoreButton.parentNode.replaceChild(newBtn, this.els.loadMoreButton);
            this.els.loadMoreButton = newBtn;
            
            this.els.loadMoreButton.addEventListener('click', onLoadMore);
        }
    }
};

export default SuggestBookUI;