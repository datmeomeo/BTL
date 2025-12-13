<?php
// $publishers, $success, $error, $page_current, $total_pages_view đã có từ controller
?>

<link rel="stylesheet" href="/bootstrap-5.0.2-dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<script src="/bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php if ($error): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="bi bi-exclamation-triangle-fill"></i> <?= htmlspecialchars($error) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if ($success): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle-fill"></i> <?= htmlspecialchars($success) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="card shadow-lg border-0">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3">
        <h4 class="mb-0 fw-bold"><i class="bi bi-building me-2"></i> Quản lý nhà xuất bản</h4>
        <a href="?page=dashboard" class="btn btn-light btn-sm"><i class="bi bi-house-door"></i> Dashboard</a>
    </div>

    <div class="card-body p-4">

        <!-- FORM TÌM KIẾM -->
        <form action="" method="get" class="row g-3 mb-4">
            <input type="hidden" name="page" value="publisher">
            <div class="col-md-4">
                <input type="text" name="search_ten" class="form-control" placeholder="Tên NXB"
                       value="<?= htmlspecialchars($_GET['search_ten'] ?? '') ?>">
            </div>
            <div class="col-md-4">
                <input type="text" name="search_email" class="form-control" placeholder="Email"
                       value="<?= htmlspecialchars($_GET['search_email'] ?? '') ?>">
            </div>
            <div class="col-4">
                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search"></i> Tìm</button>
            </div>
        </form>

        <!-- FORM THÊM/SỬA -->
        <div class="bg-light rounded p-4 mb-4 shadow-sm">
            <form action="?page=publisher&action=save" method="post" class="row g-3">
                <input type="hidden" name="ma_nxb" id="ma_nxb">

                <div class="col-md-3">
                    <label class="form-label fw-bold">Tên NXB *</label>
                    <input type="text" name="ten_nxb" id="ten_nxb" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Địa chỉ</label>
                    <input type="text" name="dia_chi" id="dia_chi" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Số điện thoại</label>
                    <input type="text" name="so_dien_thoai" id="so_dien_thoai" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Email</label>
                    <input type="email" name="email" id="email" class="form-control">
                </div>

                <div class="col-12 d-flex gap-2">
                    <button type="submit" name="action" value="add" class="btn btn-success flex-fill">
                        <i class="bi bi-plus-lg"></i> Thêm mới
                    </button>
                    <button type="submit" name="action" value="update" class="btn btn-warning flex-fill text-white">
                        <i class="bi bi-save"></i> Cập nhật
                    </button>
                    <button type="button" onclick="clearForm()" class="btn btn-secondary flex-fill">
                        <i class="bi bi-arrow-repeat"></i> Làm mới
                    </button>
                </div>
            </form>
        </div>

        <!-- PHÂN TRANG -->
        <div class="d-flex justify-content-between align-items-center mt-4" style="margin-bottom: 10px;">
            <div class="text-muted">
                Tổng: <strong><?= $total ?></strong> NXB | Trang <?= $page_current ?> / <?= $total_pages_view ?>
            </div>
            <nav>
                <ul class="pagination mb-0">
                    <li class="page-item <?= $page_current <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=publisher&p=<?= $page_current - 1 ?>">Trước</a>
                    </li>
                    <?php for ($i = 1; $i <= $total_pages_view; $i++): ?>
                        <li class="page-item <?= $i == $page_current ? 'active' : '' ?>">
                            <a class="page-link" href="?page=publisher&p=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?= $page_current >= $total_pages_view ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=publisher&p=<?= $page_current + 1 ?>">Sau</a>
                    </li>
                </ul>
            </nav>
        </div>

        <!-- BẢNG DANH SÁCH -->
        <div class="table-responsive">
            <table class="table table-hover align-middle text-center">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Tên NXB</th>
                        <th>Địa chỉ</th>
                        <th>SĐT</th>
                        <th>Email</th>
                        <th>Xóa</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($publishers as $p): ?>
                        <tr class="row-select" data-id="<?= $p['ma_nxb'] ?>"
                            data-ten="<?= htmlspecialchars($p['ten_nxb']) ?>"
                            data-diachi="<?= htmlspecialchars($p['dia_chi']) ?>"
                            data-sdt="<?= htmlspecialchars($p['so_dien_thoai']) ?>"
                            data-email="<?= htmlspecialchars($p['email']) ?>"
                            style="cursor:pointer">
                            <td><?= $p['ma_nxb'] ?></td>
                            <td><?= htmlspecialchars($p['ten_nxb']) ?></td>
                            <td><?= htmlspecialchars($p['dia_chi'] ?: '-') ?></td>
                            <td><?= htmlspecialchars($p['so_dien_thoai'] ?: '-') ?></td>
                            <td><?= htmlspecialchars($p['email'] ?: '-') ?></td>
                            <td>
                                <button class="btn btn-danger btn-sm" onclick="event.stopPropagation(); confirmDelete(<?= $p['ma_nxb'] ?>)">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </div>
</div>

<script>
document.querySelectorAll(".row-select").forEach(r => {
    r.addEventListener("click", () => {
        const d = r.dataset;
        document.getElementById("ma_nxb").value = d.id;
        document.getElementById("ten_nxb").value = d.ten;
        document.getElementById("dia_chi").value = d.diachi;
        document.getElementById("so_dien_thoai").value = d.sdt;
        document.getElementById("email").value = d.email;
    });
});

function clearForm() {
        const button = document.querySelector('button[onclick="clearForm()"]');
        const form = button.closest('form');
        if (!form) return;

        form.reset();

        // Xóa sạch tất cả field
        form.querySelectorAll('input, textarea, select').forEach(field => {
            if (field.type === 'hidden') {
                field.value = '';
            } else if (field.tagName === 'SELECT') {
                if (field.multiple) {
                    field.querySelectorAll('option').forEach(opt => opt.selected = false);
                } else {
                    field.selectedIndex = 0;
                }
            } else {
                field.value = '';
            }
        });

    }

function confirmDelete(id) {
    Swal.fire({
        title: "Xóa nhà xuất bản?",
        text: "Hành động không thể hoàn tác!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Xóa",
        cancelButtonText: "Hủy"
    }).then(res => {
        if (res.isConfirmed) {
            window.location = `?page=publisher&action=delete&id=${id}`;
        }
    });
}
</script>
