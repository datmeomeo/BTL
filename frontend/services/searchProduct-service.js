const SearchProductService = {
    BASE_API: 'http://localhost/BTL/backend/api.php?route=book',
    // Lấy danh sách sách với filter và phân trang
    async getBooks(params = {}) {
        const queryParams = new URLSearchParams({
            action: 'list',
            page: params.page || 1,
            limit: params.limit || 24,
            sort: params.sort || 'newest',
            ...params.filters
        });

        const url = `${this.BASE_API}&${queryParams.toString()}`;
        
        try {
            const response = await fetch(url);
            if (!response.ok) throw new Error(`Lỗi mạng: ${response.status}`);
            
            const result = await response.json();
            if (result.status !== 'success' || !result.data) {
                throw new Error(result.message || 'Dữ liệu không hợp lệ');
            }
            return result.data;
        } catch (error) {
            console.error("BookListService Error:", error);
            throw error;
        }
    },

    // Lấy danh sách categories
    async getCategories() {
        const url = `${this.BASE_API}&action=categories`;
        try {
            const response = await fetch(url);
            if (!response.ok) throw new Error(`Lỗi mạng: ${response.status}`);
            
            const result = await response.json();
            if (result.status !== 'success' || !result.data) {
                throw new Error(result.message || 'Dữ liệu không hợp lệ');
            }
            return result.data;
        } catch (error) {
            console.error("BookListService Error:", error);
            throw error;
        }
    },

    // Lấy danh sách tác giả
    async getAuthors() {
        const url = `${this.BASE_API}&action=authors`;
        try {
            const response = await fetch(url);
            if (!response.ok) throw new Error(`Lỗi mạng: ${response.status}`);
            
            const result = await response.json();
            if (result.status !== 'success' || !result.data) {
                throw new Error(result.message || 'Dữ liệu không hợp lệ');
            }
            return result.data;
        } catch (error) {
            console.error("BookListService Error:", error);
            throw error;
        }
    },

    // Lấy danh sách loại sách
    async getBookTypes() {
        const url = `${this.BASE_API}&action=book_types`;
        try {
            const response = await fetch(url);
            if (!response.ok) throw new Error(`Lỗi mạng: ${response.status}`);
            
            const result = await response.json();
            if (result.status !== 'success' || !result.data) {
                throw new Error(result.message || 'Dữ liệu không hợp lệ');
            }
            return result.data;
        } catch (error) {
            console.error("BookListService Error:", error);
            throw error;
        }
    }
};


export default SearchProductService;