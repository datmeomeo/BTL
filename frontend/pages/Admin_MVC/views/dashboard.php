<div class="bg-white p-5 rounded shadow">
    <h2>Dashboard - Trang quản trị</h2>
    <p>Chào mừng bạn đến với khu vực quản trị website bán sách.</p>

    <div class="row mt-4 text-center">

        <div class="col-md-3 mb-3">
            <div class="card p-4 shadow-sm">
                <h5>Tổng sách</h5>
                <h3 class="text-primary"><?= $totalBooks ?></h3>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card p-4 shadow-sm">
                <h5>Người dùng</h5>
                <h3 class="text-success"><?= $totalUsers ?></h3>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card p-4 shadow-sm">
                <h5>Danh mục</h5>
                <h3 class="text-warning"><?= $totalCategories ?></h3>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card p-4 shadow-sm">
                <h5>Nhà xuất bản</h5>
                <h3 class="text-danger"><?= $totalPublishers ?></h3>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card p-4 shadow-sm">
                <h5>Tác giả</h5>
                <h3 class="text-danger"><?= $totalAuthors ?></h3>
            </div>
        </div>

    </div>
</div>
