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