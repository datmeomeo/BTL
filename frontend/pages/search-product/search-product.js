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
            author: '', // Thêm filter author
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
        if(categories) SearchProductUI.renderCategoryTree(categories);
    },
    async loadAuthors() {
        const authors = await SearchProductService.getAuthors();
        if(authors) SearchProductUI.renderAuthors(authors);
    },

    bindEvents() {
        // 1. NÚT ÁP DỤNG BỘ LỌC
        document.getElementById('btn-apply-filter')?.addEventListener('click', () => {
            // Lấy giá từ Radio Price
            const priceRadio = document.querySelector('input[name="price_filter"]:checked');
            if (priceRadio && priceRadio.value !== 'all') {
                const [min, max] = priceRadio.value.split('-');
                this.state.filters.price_min = min;
                this.state.filters.price_max = max;
            } else {
                this.state.filters.price_min = '';
                this.state.filters.price_max = '';
            }

            // Lấy tác giả từ Radio Author
            const authorRadio = document.querySelector('input[name="author_filter"]:checked');
            if (authorRadio && authorRadio.value !== 'all') {
                this.state.filters.author = authorRadio.value;
            } else {
                this.state.filters.author = '';
            }

            this.state.pagination.page = 1;
            this.loadBooks();
        });

        // 2. Click Danh mục
        document.getElementById('category-tree')?.addEventListener('click', (e) => {
            if(e.target.classList.contains('cat-link')) {
                document.querySelectorAll('.cat-link').forEach(el => el.classList.remove('active'));
                e.target.classList.add('active');
                this.state.filters.category_id = e.target.dataset.id;
                this.state.pagination.page = 1;
                this.loadBooks();
            }
            if(e.target.classList.contains('toggle-icon')) {
                const ul = e.target.parentElement.querySelector('.children-container');
                if(ul) {
                    ul.style.display = ul.style.display === 'none' ? 'block' : 'none';
                    e.target.innerText = ul.style.display === 'block' ? '-' : '+';
                }
            }
        });

        // 3. Phân trang
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

        // 4. Sort
        document.getElementById('sort-select')?.addEventListener('change', (e) => {
            this.state.filters.sort = e.target.value;
            this.state.pagination.page = 1;
            this.loadBooks();
        });
    }
};

document.addEventListener('DOMContentLoaded', () => SearchProductPage.init());