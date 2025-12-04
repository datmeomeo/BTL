<div class="container py-4">
    <div class="row">
        <div class="col-lg-3 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom-0 pt-3">
                    <h5 class="fw-bold m-0"><i class="bi bi-funnel"></i> Bộ lọc tìm kiếm</h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <label class="form-label fw-bold text-secondary">Từ khóa</label>
                        <input type="text" id="filter-keyword" class="form-control" placeholder="Nhập tên sách...">
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-secondary">Danh mục</label>
                        <div id="filter-category" class="filter-scroll-box custom-scrollbar">
                            <p class="text-muted small">Đang tải danh mục...</p>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-secondary">Tác giả</label>
                        <div id="filter-author" class="filter-scroll-box custom-scrollbar">
                            <p class="text-muted small">Đang tải tác giả...</p>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-secondary">Khoảng giá</label>
                        <div class="d-flex align-items-center gap-2">
                            <input type="number" id="min-price" class="form-control form-control-sm" placeholder="Từ">
                            <span>-</span>
                            <input type="number" id="max-price" class="form-control form-control-sm" placeholder="Đến">
                        </div>
                    </div>

                    <button id="btn-apply-filter" class="btn btn-primary w-100">
                        Áp dụng bộ lọc
                    </button>
                </div>
            </div>
        </div>

        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4 bg-white p-3 rounded shadow-sm">
                <h1 class="h5 fw-bold mb-0">Kết quả tìm kiếm</h1>
                <div class="d-flex align-items-center">
                    <span class="me-2 text-muted">Sắp xếp:</span>
                    <select id="sort-select" class="form-select form-select-sm" style="width: 180px;">
                        <option value="newest">Mới nhất</option>
                        <option value="price_asc">Giá tăng dần</option>
                        <option value="price_desc">Giá giảm dần</option>
                        <option value="name_asc">Tên A-Z</option>
                        <option value="view_desc">Xem nhiều nhất</option>
                    </select>
                </div>
            </div>

            <div id="product-list-container" class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4">
                <div class="col-12 text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>

            <div id="pagination" class="mt-4 d-flex justify-content-center"></div>
        </div>
    </div>
</div>
