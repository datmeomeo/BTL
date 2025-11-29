document.addEventListener('DOMContentLoaded', async () => {
    // 1. Lấy ID
    const urlParams = new URLSearchParams(window.location.search);
    const bookId = urlParams.get('id');

    if (!bookId) {
        BookUI.showError('Không tìm thấy ID sách trong đường dẫn.');
        return;
    }

    // 2. Tăng view ngầm
    BookService.increaseView(bookId);

    // 3. Tải và hiển thị dữ liệu
    BookUI.showLoading();
    try {
        const bookData = await BookService.getDetail(bookId);
        BookUI.render(bookData);
    } catch (error) {
        BookUI.showError('Không thể tải dữ liệu. Vui lòng thử lại sau.');
    } finally {
        BookUI.hideLoading();
    }
});