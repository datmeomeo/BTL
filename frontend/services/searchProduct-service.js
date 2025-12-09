const API_CONFIG = {
    BASE_URL: 'http://localhost/BTL/backend/api.php?route=book' 
};

const SearchProductService = {
    async fetchAPI(params) {
        const queryParams = new URLSearchParams(params);
        const url = `${API_CONFIG.BASE_URL}&${queryParams.toString()}`;
        try {
            const response = await fetch(url);
            const result = await response.json();
            return (result.status === 'success') ? result.data : null;
        } catch (error) {
            console.error("Lỗi API:", error);
            return null;
        }
    },

    async getBooks(filterParams) {
        return await this.fetchAPI({
            action: 'list',
            ...filterParams
        });
    },

    async getCategories() {
        return await this.fetchAPI({ action: 'categories' });
    },

    async getAuthors() {
        return await this.fetchAPI({ action: 'authors' });
    }
};

export default SearchProductService;