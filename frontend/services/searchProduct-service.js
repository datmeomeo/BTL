// frontend/services/searchProduct-service.js
const SearchProductService = {
    BASE_API: 'http://localhost/BTL/backend/api.php?route=book',

    // Lấy danh sách sách
    async getBooks(params = {}) {
        // Chuẩn bị tham số truy vấn
        // Backend đang mong đợi: page, limit, sort, keyword, category_id, price_min, price_max
        const queryParams = new URLSearchParams({
            action: 'list',
            page: params.page || 1,
            limit: params.limit || 12, // Số lượng sách mỗi trang
            sort: params.sort || 'newest',
        });

        // Thêm các bộ lọc nếu có (keyword, category_id, price_min,...)
        if (params.filters) {
            Object.keys(params.filters).forEach(key => {
                if (params.filters[key] !== null && params.filters[key] !== undefined && params.filters[key] !== '') {
                    queryParams.append(key, params.filters[key]);
                }
            });
        }

        const url = `${this.BASE_API}&${queryParams.toString()}`;
        
        try {
            const response = await fetch(url);
            if (!response.ok) throw new Error(`Lỗi mạng: ${response.status}`);
            
            const result = await response.json();
            if (result.status !== 'success') {
                throw new Error(result.message || 'Lỗi lấy dữ liệu');
            }
            return result.data; // Trả về mảng DTO
        } catch (error) {
            console.error("Lỗi getBooks:", error);
            return []; // Trả về mảng rỗng để không gãy giao diện
        }
    },

    // Lấy danh mục
    async getCategories() {
        const url = `${this.BASE_API}&action=categories`;
        return this._fetchData(url);
    },

    // Lấy tác giả
    async getAuthors() {
        const url = `${this.BASE_API}&action=authors`;
        return this._fetchData(url);
    },

    // Hàm phụ trợ fetch chung
    async _fetchData(url) {
        try {
            const response = await fetch(url);
            const result = await response.json();
            return result.status === 'success' ? result.data : [];
        } catch (error) {
            console.error("Lỗi fetch:", error);
            return [];
        }
    }
};

export default SearchProductService;