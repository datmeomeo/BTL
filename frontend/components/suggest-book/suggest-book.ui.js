// frontend/components/suggest-book/suggest-book.ui.js
import { escapeHTML, formatCurrency, createStarIconHTML } from '../../utils/utils.js';
const SuggestBookUI = {
    els: {
        gridContainer: document.getElementById('goi-y-content'),
        loadMoreButton: document.getElementById('btn-xem-them')
    },

    /**
     * Render một thẻ sách (book card) HTML.
     * @param {object} book Dữ liệu sách.
     * @returns {string} Chuỗi HTML của thẻ sách.
     */
    renderBookCardHTML: (book) => { // This can remain an arrow function as it doesn't use 'this.els'
        const addedDate = new Date(book.addedDate);
        const diffTime = Math.abs(new Date() - addedDate);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        const isNew = diffDays <= 7;
        const publisherName = book.publisherName ?? 'NXB';
        const imagePath = book.imagePath ?? '';

        const discountBadgeHTML = book.discountPercent > 0 ?
            `<div class="discount-badge">-${book.discountPercent}%</div>` : '';

        const newBadgeHTML = isNew ? `<div class="new-badge">Mới</div>` : '';

        const oldPriceHTML = book.originalPrice > book.sellingPrice ?
            `<span class="old-price">${formatCurrency(book.originalPrice)}</span>` : '';

        return `
            <a href="index.php?page=book&id=${book.bookId}" class="sach-card">

                ${discountBadgeHTML}
                ${newBadgeHTML}

                <img class="sach-image"
                    src="${escapeHTML(imagePath)}"
                    alt="${escapeHTML(book.bookName)}"
                    loading="lazy">

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
    renderSuggestedBooks: function(books) { // Use function keyword
        if (!this.els.gridContainer) return;

        if (books.length > 0) {
            const booksHtml = books.map(book => this.renderBookCardHTML(book)).join(''); // Use this.renderBookCardHTML
            this.els.gridContainer.innerHTML = booksHtml;
        } else {
            this.els.gridContainer.innerHTML = '<p>Không tìm thấy sách gợi ý nào.</p>';
        }
    },

    /**
     * Gắn event listener cho nút "Xem thêm".
     * @param {function} onLoadMore Callback khi nút được click.
     */
    attachLoadMoreListener: function(onLoadMore) { // Use function keyword
        if (this.els.loadMoreButton) {
            this.els.loadMoreButton.addEventListener('click', onLoadMore);
        }
    }
};

export default SuggestBookUI;
