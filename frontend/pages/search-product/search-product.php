<<<<<<< HEAD
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

            <!-- Nhà xuất bản - Collapsible -->
            <div class="filter-section">
                <div class="filter-header" onclick="BookListUI.toggleFilter(this)">
                    Nhà xuất bản
                </div>
                <div class="filter-content hidden" id="publisher-filter">
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
                        <option value="24">20 sản phẩm</option>
                        <option value="48">30 sản phẩm</option>
                        <option value="96">40 sản phẩm</option>
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
=======
<div class="container py-4">
    <div class="row">
        <div class="col-lg-3 mb-4 filter-sidebar">
            <div class="card">
                <div class="card-header">
                    <h5 class="fw-bold m-0"><i class="bi bi-funnel-fill text-primary"></i> Bộ lọc</h5>
                </div>
                <div class="card-body">
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold">Danh mục</label>
                        <div id="category-tree" class="category-tree filter-scroll-box">
                            <p class="text-muted small">Đang tải...</p>
                        </div>
                    </div>
                    <hr class="text-muted opacity-25">

                    <div class="mb-4">
                        <label class="form-label fw-bold">Giá bán</label>
                        <div class="price-list">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="price_filter" value="all" id="price-all" checked>
                                <label class="form-check-label" for="price-all">Tất cả</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="price_filter" value="0-150000" id="p1">
                                <label class="form-check-label" for="p1">0đ - 150.000đ</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="price_filter" value="150000-300000" id="p2">
                                <label class="form-check-label" for="p2">150.000đ - 300.000đ</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="price_filter" value="300000-500000" id="p3">
                                <label class="form-check-label" for="p3">300.000đ - 500.000đ</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="price_filter" value="500000-700000" id="p4">
                                <label class="form-check-label" for="p4">500.000đ - 700.000đ</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="price_filter" value="700000-10000000" id="p5">
                                <label class="form-check-label" for="p5">Trên 700.000đ</label>
                            </div>
                        </div>
                    </div>
                    <hr class="text-muted opacity-25">

                    <div class="mb-3">
                        <label class="form-label fw-bold">Tác giả</label>
                        <div id="filter-author" class="filter-scroll-box">
                            <p class="text-muted small">Đang tải...</p>
                        </div>
                    </div>

                    </div>
            </div>
        </div>

        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4 bg-white p-3 rounded shadow-sm border">
                <h5 class="fw-bold mb-0 text-dark">Kết quả (<span id="total-books-count">0</span>)</h5>
                <select id="sort-select" class="form-select form-select-sm border-secondary" style="width: 180px;">
                    <option value="newest">Mới nhất</option>
                    <option value="price_asc">Giá tăng dần</option>
                    <option value="price_desc">Giá giảm dần</option>
                </select>
            </div>

            <div id="product-list-container" class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-3">
                </div>

            <nav class="mt-5">
                <ul id="pagination-container" class="pagination justify-content-center"></ul>
            </nav>
        </div>
>>>>>>> 6a024c67c3d9ac6366e3fcb74327a42f32e38cf5
    </div>
</div>