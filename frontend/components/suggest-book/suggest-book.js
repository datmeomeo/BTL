// frontend/components/suggest-book/suggest-book.js

import BookService from '../../services/book-service.js';
import SuggestBookUI from './suggest-book.ui.js';
// Assuming utils.js is loaded globally for escapeHTML and formatCurrency

document.addEventListener('DOMContentLoaded', async () => {
    await loadAndRenderSuggestedBooks();

    // Gắn event listener cho nút "Xem thêm"
    SuggestBookUI.attachLoadMoreListener(loadAndRenderSuggestedBooks);
});

/**
 * Tải và hiển thị danh sách sách gợi ý.
 */
async function loadAndRenderSuggestedBooks() {
    try {
        const books = await BookService.getSuggestedBooks();
        console.log("Sách gợi ý tải về:", books);
        SuggestBookUI.renderSuggestedBooks(books);
    } catch (error) {
        console.error("Lỗi khi tải sách gợi ý:", error);
        SuggestBookUI.showError(error.message || 'Không thể tải dữ liệu gợi ý. Vui lòng kiểm tra kết nối API.');
    }
}
