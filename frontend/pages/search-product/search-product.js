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
            author: '',
            sort: 'newest'
        },
        pagination: { page: 1, limit: 20, total_pages: 1 }
    },

    init() {
        const urlParams = new URLSearchParams(window.location.search);
        this.state.filters.keyword = urlParams.get('keyword') || '';
        this.loadCategories();
        this.loadAuthors();
        this.loadBooks();
        this.bindEvents();
    },

    async loadBooks() {
        const data = await SearchProductService.getBooks({
            page: this.state.pagination.page,
            limit: this.state.pagination.limit,
            sort: this.state.filters.sort,
            ...this.state.filters
        });

        if (data) {
            this.state.pagination.total_pages = data.pagination.total_pages;
            this.state.pagination.total = data.pagination.total;
            SearchProductUI.renderBooks(data.books);
            SearchProductUI.renderPagination(this.state.pagination);
        }
    },

    async loadCategories() {
        const categories = await SearchProductService.getCategories();
        if(categories) {
            // 1. Vẽ cây danh mục
            SearchProductUI.renderCategoryTree(categories);

            // 2. LOGIC MỚI: Highlight danh mục đang chọn dựa trên URL
            // (Lấy từ state.filters.category_id đã được init từ URL)
            const activeId = this.state.filters.category_id;
            if (activeId) {
                SearchProductUI.highlightActiveCategory(activeId);
            }
        }
    },
    async loadAuthors() {
        const authors = await SearchProductService.getAuthors();
        if(authors) SearchProductUI.renderAuthors(authors);
    },

    bindEvents() {
        // 1. TỰ ĐỘNG LỌC KHI CHỌN GIÁ
        // Lắng nghe sự kiện change trên container cha của radio giá
        document.querySelector('.price-list')?.addEventListener('change', (e) => {
            if(e.target.name === 'price_filter') {
                if (e.target.value !== 'all') {
                    const [min, max] = e.target.value.split('-');
                    this.state.filters.price_min = min;
                    this.state.filters.price_max = max;
                } else {
                    this.state.filters.price_min = '';
                    this.state.filters.price_max = '';
                }
                this.state.pagination.page = 1;
                this.loadBooks(); // Gọi ngay
            }
        });

        // 2. TỰ ĐỘNG LỌC KHI CHỌN TÁC GIẢ
        document.getElementById('filter-author')?.addEventListener('change', (e) => {
            if(e.target.name === 'author_filter') {
                this.state.filters.author = (e.target.value !== 'all') ? e.target.value : '';
                this.state.pagination.page = 1;
                this.loadBooks(); // Gọi ngay
            }
        });

        // 3. TỰ ĐỘNG LỌC KHI BẤM DANH MỤC (Bỏ toggle icon)
        document.getElementById('category-tree')?.addEventListener('click', (e) => {
            if(e.target.classList.contains('cat-link')) {
                // 1. Highlight & Lọc sách (Logic cũ)
                document.querySelectorAll('.cat-link').forEach(el => el.classList.remove('active'));
                e.target.classList.add('active');
                
                this.state.filters.category_id = e.target.dataset.id;
                this.state.pagination.page = 1;
                this.loadBooks();

                // 2. LOGIC MỚI: Tự động Bật/Tắt danh mục con
                const childrenContainer = e.target.parentElement.querySelector('.children-container');
                if(childrenContainer) {
                    // Nếu đang ẩn thì hiện, đang hiện thì ẩn
                    if (childrenContainer.style.display === 'none') {
                        childrenContainer.style.display = 'block';
                    } else {
                        childrenContainer.style.display = 'none';
                    }
                }
            }
        });

        // 4. Phân trang
        document.getElementById('pagination-container')?.addEventListener('click', (e) => {
            e.preventDefault();
            if(e.target.classList.contains('page-link')) {
                const newPage = parseInt(e.target.dataset.page);
                if (newPage && newPage !== this.state.pagination.page) {
                    this.state.pagination.page = newPage;
                    this.loadBooks();
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            }
        });

        // 5. Sort
        document.getElementById('sort-select')?.addEventListener('change', (e) => {
            this.state.filters.sort = e.target.value;
            this.state.pagination.page = 1;
            this.loadBooks();
        });
    }
};

document.addEventListener('DOMContentLoaded', () => SearchProductPage.init());