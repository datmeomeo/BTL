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
        this.state.filters.category_id = urlParams.get('category_id') || ''; 

        // Tải dữ liệu
        this.loadCategories();
        this.loadAuthors();
        this.loadBooks(); 
        this.bindEvents();
    },

    async loadCategories() {
        const categories = await SearchProductService.getCategories();
        if(categories) {
            SearchProductUI.renderCategoryTree(categories);
            const activeId = this.state.filters.category_id;
            if (activeId) {
                SearchProductUI.highlightActiveCategory(activeId);
            }
        }
    },
    
    updateURL() {
        const params = new URLSearchParams(window.location.search);
        
        // Cập nhật Keyword
        if(this.state.filters.keyword) {
            params.set('keyword', this.state.filters.keyword);
        } else {
            params.delete('keyword');
        }

        // Cập nhật Category ID
        if(this.state.filters.category_id) {
            params.set('category_id', this.state.filters.category_id);
        } else {
            params.delete('category_id');
        }

        // Cập nhật Author (nếu muốn URL lưu cả tác giả)
        if(this.state.filters.author) {
            params.set('author', this.state.filters.author);
        } else {
            params.delete('author');
        }

        if(!params.has('page')) {
            params.set('page', 'search_product');
        }
        
        const newUrl = `${window.location.pathname}?${params.toString()}`;
        window.history.pushState({path: newUrl}, '', newUrl);
    },

    async loadAuthors() {
        const authors = await SearchProductService.getAuthors();
        if(authors) SearchProductUI.renderAuthors(authors);
    },

    async loadBooks() {
        this.updateURL(); 
        
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

    bindEvents() {
        // Lọc theo giá
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
                this.loadBooks();
            }
        });

        // Lọc theo tác giả
        document.getElementById('filter-author')?.addEventListener('change', (e) => {
            if(e.target.name === 'author_filter') {
                this.state.filters.author = (e.target.value !== 'all') ? e.target.value : '';
                this.state.pagination.page = 1;
                this.loadBooks();
            }
        });

        // --- SỬA CHÍNH: Xử lý click Danh mục ---
        document.getElementById('category-tree')?.addEventListener('click', (e) => {
            const link = e.target.closest('.cat-link'); 
            if(link) {
                e.preventDefault(); 
                
                // Highlight UI
                document.querySelectorAll('.cat-link').forEach(el => el.classList.remove('active'));
                link.classList.add('active');
                
                // 1. Cập nhật Category ID mới
                this.state.filters.category_id = link.dataset.id;

                // 2. [QUAN TRỌNG] Reset các bộ lọc khác để tránh xung đột
                this.state.filters.keyword = '';   // Xóa từ khóa tìm kiếm cũ
                this.state.filters.author = '';    // Xóa lọc tác giả (nếu muốn)
                this.state.filters.price_min = ''; 
                this.state.filters.price_max = '';
                
                // Reset radio button trên UI về mặc định (nếu cần thiết)
                const allAuthorRadio = document.getElementById('auth-all');
                if(allAuthorRadio) allAuthorRadio.checked = true;

                // 3. Load lại sách
                this.state.pagination.page = 1;
                this.loadBooks();

                // Toggle menu con (giữ nguyên logic cũ của bạn)
                const parentItem = link.parentElement;
                const childrenContainer = parentItem.querySelector('.children-container');
                if(childrenContainer) {
                    const isHidden = childrenContainer.style.display === 'none' || !childrenContainer.style.display;
                    childrenContainer.style.display = isHidden ? 'block' : 'none';
                }
            }
        });

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

        document.getElementById('sort-select')?.addEventListener('change', (e) => {
            this.state.filters.sort = e.target.value;
            this.state.pagination.page = 1;
            this.loadBooks();
        });
    }
};

document.addEventListener('DOMContentLoaded', () => SearchProductPage.init());