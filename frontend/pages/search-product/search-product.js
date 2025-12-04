// frontend/pages/search-product/search-product.js

import SearchProductService from '../../services/searchProduct-service.js';
import SearchProductUI from './search-product.ui.js';

const SearchProductPage = {
    state: {
        filters: {
            keyword: '',
            category_id: '',
            price_min: '',
            price_max: '',
            sort: 'newest'
        },
        page: 1,
        limit: 12
    },

    async init() {
        // 1. Lấy tham số từ URL (ví dụ: người dùng search từ header)
        const urlParams = new URLSearchParams(window.location.search);
        // Lưu ý: url của bạn có thể là index.php?page=search_product&keyword=abc
        this.state.filters.keyword = urlParams.get('keyword') || '';
        
        // Cập nhật ô input search nếu có
        const searchInput = document.querySelector('input[name="search_query"]'); // ID input trên header hoặc trong trang
        if (searchInput && this.state.filters.keyword) {
            searchInput.value = this.state.filters.keyword;
        }

        // 2. Load dữ liệu ban đầu
        await Promise.all([
            this.loadCategories(),
            this.loadAuthors(),
            this.loadBooks()
        ]);

        // 3. Gắn sự kiện (Event Listeners)
        this.bindEvents();
    },

    async loadBooks() {
        // Hiển thị loading (nếu có UI loading)
        // document.getElementById('product-list-container').innerHTML = 'Loading...';

        const books = await SearchProductService.getBooks({
            page: this.state.page,
            limit: this.state.limit,
            sort: this.state.filters.sort,
            filters: this.state.filters
        });
        
        SearchProductUI.renderListBooks(books);
    },

    async loadCategories() {
        const categories = await SearchProductService.getCategories();
        SearchProductUI.renderCategories(categories);
    },

    async loadAuthors() {
        const authors = await SearchProductService.getAuthors();
        SearchProductUI.renderAuthors(authors);
    },

    bindEvents() {
        // Sự kiện Sort (Sắp xếp)
        const sortSelect = document.getElementById('sort-select'); // Cần thêm ID này vào HTML select box
        if (sortSelect) {
            sortSelect.addEventListener('change', (e) => {
                this.state.filters.sort = e.target.value;
                this.state.page = 1; // Reset về trang 1
                this.loadBooks();
            });
        }

        // Sự kiện Filter (Nút "Áp dụng" hoặc thay đổi input)
        // Giả sử bạn có nút id="btn-apply-filter"
        const btnFilter = document.getElementById('btn-apply-filter'); 
        if (btnFilter) {
            btnFilter.addEventListener('click', () => {
                this.handleFilterChange();
            });
        }
        
        // Sự kiện click vào checkbox Category (vì render động nên dùng delegate hoặc gắn sau khi render)
        // Cách đơn giản nhất: lắng nghe change trên container cha
        document.getElementById('filter-category')?.addEventListener('change', (e) => {
            if(e.target.name === 'category') {
                this.state.filters.category_id = e.target.value;
                this.state.page = 1;
                this.loadBooks();
            }
        });
    },

    handleFilterChange() {
        // Thu thập dữ liệu từ các input filter
        // Ví dụ giá
        const minPrice = document.getElementById('min-price')?.value;
        const maxPrice = document.getElementById('max-price')?.value;
        
        this.state.filters.price_min = minPrice;
        this.state.filters.price_max = maxPrice;
        
        // Keyword từ ô search nội bộ trang (nếu có)
        const keywordInput = document.getElementById('filter-keyword');
        if(keywordInput) {
            this.state.filters.keyword = keywordInput.value;
        }

        this.state.page = 1;
        this.loadBooks();
    }
};

// Khởi chạy khi trang load
document.addEventListener('DOMContentLoaded', () => {
    // Chỉ chạy nếu đang ở trang search_product (kiểm tra element container)
    if (document.getElementById('product-list-container')) {
        SearchProductPage.init();
    }
});

export default SearchProductPage;