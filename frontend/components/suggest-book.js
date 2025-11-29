// suggest-books.js

// const SUGGEST_API_URL = 'http://localhost/BTL/backend/api.php?route=book&action=suggest_book';
const SUGGEST_API_URL = '../backend/api.php?route=book&action=suggest_book';
const gridContainer = document.getElementById('goi-y-content');
const loadMoreButton = document.getElementById('btn-xem-them');

const escapeHTML = (str) => {
    if (typeof str !== 'string') return '';
    const div = document.createElement('div'); 
    div.textContent = str;
    return div.innerHTML;
};

const formatCurrency = (amount) => {
    if (typeof amount !== 'number' || isNaN(amount)) return '0đ';
    return new Intl.NumberFormat('vi-VN', { 
        style: 'currency', 
        currency: 'VND' 
    }).format(amount).replace('₫', 'đ');
};

const createStarIconHTML = () => {
    return '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="#ffc107" stroke="none"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>';
};

const renderBookCardHTML = (book) => {
    const addedDate = new Date(book.addedDate);
    const diffTime = Math.abs(new Date() - addedDate);
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    const isNew = diffDays <= 7;
    const publisherName = book.publisherName ?? 'NXB';
    const imagePath = book.imagePath ?? './assets/img/no-image.jpg';

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
};

async function loadSuggestedBooks() {
    gridContainer.innerHTML = '<p id="loading-message">Đang tải sách gợi ý...</p>';
    
    try {
        const response = await fetch(SUGGEST_API_URL);
        
        // Lấy text trước để debug
        const textResponse = await response.text();
        console.log('Raw API Response:', textResponse);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        // Thử parse JSON
        let apiResponse;
        try {
            apiResponse = JSON.parse(textResponse);
        } catch (parseError) {
            console.error('JSON Parse Error:', parseError);
            console.error('Response was:', textResponse);
            throw new Error('API trả về không phải JSON. Kiểm tra console để xem chi tiết.');
        }

        if (apiResponse.status === 'error') {
            throw new Error(apiResponse.message || 'Unknown API error');
        }
        
        const books = apiResponse.data || [];

        if (books.length === 0) {
            gridContainer.innerHTML = '<p>Không tìm thấy sách gợi ý nào.</p>';
            return;
        }

        const booksHtml = books.map(book => renderBookCardHTML(book)).join('');
        gridContainer.innerHTML = booksHtml;

    } catch (error) {
        console.error("Chi tiết lỗi:", error);
        gridContainer.innerHTML = `
            <p style="color: red;">
                Không thể tải dữ liệu gợi ý.<br>
                Lỗi: ${error.message}<br>
                Kiểm tra Console để biết thêm chi tiết.
            </p>
        `;
    }
}

document.addEventListener('DOMContentLoaded', loadSuggestedBooks);
loadMoreButton.addEventListener('click', loadSuggestedBooks);