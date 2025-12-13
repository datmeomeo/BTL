<<<<<<< HEAD
import SearchProductService from '../../services/book-list-service.js';
import searchProductUI from './search-product.ui.js';


const state = {
    currentPage: 1,
    limit: 24,
    sort: 'newest',
    filters: {
        categories: [],
        publisher: [],
        types: [],
        priceRanges: []
    }
};

// Initialize page
document.addEventListener('DOMContentLoaded', async () => {
    await initFilters();
    await loadBooks();
    setupEventListeners();
});

// Load all filter options
async function initFilters() {
    try {
        // Load categories
        const categories = await BookListService.getCategories();
        BookListUI.renderCategories(categories);

        // Load publisher
        const publisher = await BookListService.getPublisher();
        BookListUI.renderPublisher(publisher);

        // Load book types
        const types = await BookListService.getBookTypes();
        BookListUI.renderBookTypes(types);

    } catch (error) {
        console.error('Error loading filters:', error);
    }
}

// Load books based on current state
async function loadBooks() {
    BookListUI.showLoading();
    
    try {
        const params = {
            page: state.currentPage,
            limit: state.limit,
            sort: state.sort,
            filters: {
                category: state.filters.categories.join(','),
                author: state.filters.authors.join(','),
                type: state.filters.types.join(','),
                price: state.filters.priceRanges.join(',')
            }
        };

        const data = await BookListService.getBooks(params);
        
        BookListUI.renderProducts(data.books);
        BookListUI.renderPagination(data.currentPage, data.totalPages);
        
    } catch (error) {
        console.error('Error loading books:', error);
        BookListUI.showError('Không thể tải dữ liệu. Vui lòng thử lại sau.');
    }
}

// Setup all event listeners
function setupEventListeners() {
    // Sort change
    BookListUI.els.sortSelect.addEventListener('change', (e) => {
        state.sort = e.target.value;
        state.currentPage = 1;
        loadBooks();
    });

    // Limit change
    BookListUI.els.limitSelect.addEventListener('change', (e) => {
        state.limit = parseInt(e.target.value);
        state.currentPage = 1;
        loadBooks();
    });

    // Category filter
    document.addEventListener('change', (e) => {
        if (e.target.name === 'category') {
            updateFilter('categories', e.target.value, e.target.checked);
        } else if (e.target.name === 'publisher') {
            updateFilter('publisher', e.target.value, e.target.checked);
        } else if (e.target.name === 'type') {
            updateFilter('types', e.target.value, e.target.checked);
        } else if (e.target.name === 'price') {
            updateFilter('priceRanges', e.target.value, e.target.checked);
        }
    });

    // Pagination
    window.addEventListener('pageChange', (e) => {
        state.currentPage = e.detail.page;
        loadBooks();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
}

// Update filter state
function updateFilter(filterType, value, isChecked) {
    if (value === 'all') {
        // Uncheck all other options when "Tất cả" is selected
        if (isChecked) {
            state.filters[filterType] = [];
            document.querySelectorAll(`input[name="${filterType === 'categories' ? 'category' : filterType}"]`).forEach(input => {
                if (input.value !== 'all') input.checked = false;
            });
        }
    } else {
        // Uncheck "Tất cả" when any specific option is selected
        document.querySelector(`input[name="${filterType === 'categories' ? 'category' : filterType}"][value="all"]`)?.checked = false;
        
        if (isChecked) {
            if (!state.filters[filterType].includes(value)) {
                state.filters[filterType].push(value);
            }
        } else {
            state.filters[filterType] = state.filters[filterType].filter(v => v !== value);
        }
    }
    
    state.currentPage = 1;
    loadBooks();
}
=======
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
        // 1. LẤY THAM SỐ TỪ URL
        const urlParams = new URLSearchParams(window.location.search);
        this.state.filters.keyword = urlParams.get('keyword') || '';
        this.state.filters.category_id = urlParams.get('category_id') || ''; 

        // 2. Tải dữ liệu
        this.loadCategories();
        this.loadAuthors();
        this.loadBooks(); 
        this.bindEvents();
    },

    async loadCategories() {
        const categories = await SearchProductService.getCategories();
        if(categories) {
            SearchProductUI.renderCategoryTree(categories);

            // Highlight danh mục đang chọn nếu có
            const activeId = this.state.filters.category_id;
            if (activeId) {
                SearchProductUI.highlightActiveCategory(activeId);
            }
        }
    },
    
    // --- [QUAN TRỌNG] HÀM NÀY ĐÃ ĐƯỢC SỬA ---
    updateURL() {
        // Lấy tất cả tham số hiện tại (bao gồm ?page=search_product)
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

        // [QUAN TRỌNG] Đảm bảo tham số page luôn tồn tại để PHP Router hiểu
        if(!params.has('page')) {
            params.set('page', 'search_product');
        }
        
        const newUrl = `${window.location.pathname}?${params.toString()}`;
        window.history.pushState({path: newUrl}, '', newUrl);
    },
    // ----------------------------------------

    async loadAuthors() {
        const authors = await SearchProductService.getAuthors();
        if(authors) SearchProductUI.renderAuthors(authors);
    },

    async loadBooks() {
        this.updateURL(); // Cập nhật URL chuẩn trước khi tải
        
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
        // Các sự kiện giữ nguyên như cũ
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

        document.getElementById('filter-author')?.addEventListener('change', (e) => {
            if(e.target.name === 'author_filter') {
                this.state.filters.author = (e.target.value !== 'all') ? e.target.value : '';
                this.state.pagination.page = 1;
                this.loadBooks();
            }
        });

        document.getElementById('category-tree')?.addEventListener('click', (e) => {
            const link = e.target.closest('.cat-link'); 
            if(link) {
                e.preventDefault(); 
                document.querySelectorAll('.cat-link').forEach(el => el.classList.remove('active'));
                link.classList.add('active');
                
                this.state.filters.category_id = link.dataset.id;
                this.state.pagination.page = 1;
                this.loadBooks();

                // Toggle menu con
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
>>>>>>> 6a024c67c3d9ac6366e3fcb74327a42f32e38cf5
