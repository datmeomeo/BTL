const BookService = {
    BASE_API: 'http://localhost/BTL/backend/api.php?route=book',

    // Lấy chi tiết sách
    async getDetail(id) {
        const url = `${BookService.BASE_API}&action=page_detail&id=${id}`;
        try {
            const response = await fetch(url);
            if (!response.ok) throw new Error(`Lỗi mạng: ${response.status}`);
            
            const result = await response.json();
            if (result.status !== 'success' || !result.data) {
                throw new Error(result.message || 'Dữ liệu không hợp lệ');
            }
            return result.data;
        } catch (error) {
            console.error("BookService Error:", error);
            throw error;
        }
    },

    // Tăng lượt xem (Gửi ngầm, không cần chờ kết quả)
    increaseView(id) {
        const url = `${BookService.BASE_API}&action=increase_view&id=${id}`;
        fetch(url, { method: 'POST' }).catch(err => console.warn("Lỗi tăng view:", err));
    },

    // Lấy sách gợi ý
    async getSuggestedBooks() {
        const url = `${BookService.BASE_API}&action=suggest_book`;
        try {
            const response = await fetch(url);
            if (!response.ok) throw new Error(`Lỗi mạng: ${response.status}`);
            
            const result = await response.json();
            if (result.status !== 'success' || !result.data) {
                throw new Error(result.message || 'Dữ liệu không hợp lệ');
            }
            return result.data;
        } catch (error) {
            console.error("BookService Error:", error);
            throw error;
        }
    }
};

export default BookService;
