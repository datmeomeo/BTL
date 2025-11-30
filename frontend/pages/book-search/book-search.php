<div class="container-list">
    <div class="content-wrapper">
        <!-- Sidebar Filters -->
        <aside class="sidebar">
            <!-- Nhóm Sản Phẩm - Always visible -->
            <div class="filter-section category-section">
                <div class="filter-header-fixed">
                    NHÓM SẢN PHẨM
                </div>
                <div class="filter-content" id="category-filter">
                    <!-- Categories will be injected by JS -->
                </div>
            </div>

            <!-- Tác Giả - Collapsible -->
            <div class="filter-section">
                <div class="filter-header" onclick="BookListUI.toggleFilter(this)">
                    TÁC GIẢ
                </div>
                <div class="filter-content hidden" id="author-filter">
                    <!-- Authors will be injected by JS -->
                </div>
            </div>

            <!-- Loại Sách - Collapsible -->
            <div class="filter-section">
                <div class="filter-header" onclick="BookListUI.toggleFilter(this)">
                    LOẠI SÁCH
                </div>
                <div class="filter-content hidden" id="type-filter">
                    <!-- Book types will be injected by JS -->
                </div>
            </div>

            <!-- Giá Sản Phẩm - Collapsible -->
            <div class="filter-section">
                <div class="filter-header" onclick="BookListUI.toggleFilter(this)">
                    GIÁ SẢN PHẨM
                </div>
                <div class="filter-content hidden" id="price-filter">
                    <div class="filter-item">
                        <label>
                            <input type="checkbox" name="price" value="0-150000">
                            0đ - 150,000đ
                        </label>
                    </div>
                    <div class="filter-item">
                        <label>
                            <input type="checkbox" name="price" value="150000-300000">
                            150,000đ - 300,000đ
                        </label>
                    </div>
                    <div class="filter-item">
                        <label>
                            <input type="checkbox" name="price" value="300000-500000">
                            300,000đ - 500,000đ
                        </label>
                    </div>
                    <div class="filter-item">
                        <label>
                            <input type="checkbox" name="price" value="500000-700000">
                            500,000đ - 700,000đ
                        </label>
                    </div>
                    <div class="filter-item">
                        <label>
                            <input type="checkbox" name="price" value="700000-999999999">
                            700,000đ - Trở Lên
                        </label>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="content-header">
                <h1>NHÓM SẢN PHẨM</h1>
                <div class="sort-bar">
                    <label>Sắp xếp theo:</label>
                    <select id="sort-select">
                        <option value="newest">Mới nhất</option>
                        <option value="bestseller">Bán chạy</option>
                        <option value="price-asc">Giá tăng dần</option>
                        <option value="price-desc">Giá giảm dần</option>
                    </select>
                    <select id="limit-select">
                        <option value="24">24 sản phẩm</option>
                        <option value="48">48 sản phẩm</option>
                        <option value="96">96 sản phẩm</option>
                    </select>
                </div>
            </div>

            <div class="product-grid" id="product-grid">
                <!-- Products will be injected by JS -->
            </div>

            <!-- Pagination -->
            <div class="pagination" id="pagination">
                <!-- Pagination will be injected by JS -->
            </div>
        </main>
    </div>
</div>