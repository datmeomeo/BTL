<?php
// $authors, $success, $error, $page_current, $total_pages_view đã có từ controller
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
        <h4 class="mb-0 fw-bold"><i class="bi bi-person-lines-fill me-2"></i> Quản lý tác giả</h4>
        <a href="?page=dashboard" class="btn btn-light btn-sm"><i class="bi bi-house-door"></i> Dashboard</a>
    </div>

    <div class="card-body p-4">
        <!-- FORM TÌM KIẾM -->
        <form action="" method="get" class="row g-3 mb-4">
            <input type="hidden" name="page" value="author">

            <div class="col-md-4">
                <input type="text" name="search_ten" class="form-control" placeholder="Tên tác giả"
                    value="<?= htmlspecialchars($_GET['search_ten'] ?? '') ?>">
            </div>

            <div class="col-md-4">
                <input type="text" name="search_quoc_tich" class="form-control" placeholder="Quốc tịch"
                    value="<?= htmlspecialchars($_GET['search_quoc_tich'] ?? '') ?>">
            </div>

            <div class="col-md-4">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search"></i> Tìm
                </button>
            </div>
        </form>

        <!-- FORM THÊM/SỬA -->
        <div class="bg-light rounded p-4 mb-4 shadow-sm">
            <form action="?page=author&action=save" method="post" class="row g-3">
                <input type="hidden" name="ma_tac_gia" id="ma_tac_gia">

                <div class="col-md-4">
                    <label class="form-label fw-bold">Tên tác giả *</label>
                    <input type="text" name="ten_tac_gia" id="ten_tac_gia" class="form-control" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">Quốc tịch</label>
                    <input type="text" name="quoc_tich" id="quoc_tich" class="form-control">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">Ngày sinh</label>
                    <input type="date" name="ngay_sinh" id="ngay_sinh" class="form-control">
                </div>
                <div class="col-md-12">
                    <label class="form-label fw-bold">Tiểu sử</label>
                    <textarea name="tieu_su" id="tieu_su" class="form-control" rows="2"></textarea>
                </div>
                <div class="col-md-12 d-flex gap-2">
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

        <!-- BẢNG DANH SÁCH -->
        <div class="table-responsive">
            <table class="table table-hover align-middle text-center">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Tên tác giả</th>
                        <th>Ngày sinh</th>
                        <th>Quốc tịch</th>
                        <th>Tiểu sử</th>
                        <th>Xóa</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($authors as $a): ?>
                        <tr class="row-select" data-id="<?= $a['ma_tac_gia'] ?>"
                            data-ten="<?= htmlspecialchars($a['ten_tac_gia']) ?>"
                            data-ngaysinh="<?= $a['ngay_sinh'] ?>"
                            data-quoctich="<?= htmlspecialchars($a['quoc_tich']) ?>"
                            data-tieusu="<?= htmlspecialchars($a['tieu_su']) ?>"
                            style="cursor:pointer">
                            <td><?= $a['ma_tac_gia'] ?></td>
                            <td><?= htmlspecialchars($a['ten_tac_gia']) ?></td>
                            <td><?= $a['ngay_sinh'] ?: '-' ?></td>
                            <td><?= htmlspecialchars($a['quoc_tich'] ?: '-') ?></td>
                            <td><?= htmlspecialchars($a['tieu_su'] ?: '-') ?></td>
                            <td>
                                <button class="btn btn-danger btn-sm" onclick="event.stopPropagation(); confirmDelete(<?= $a['ma_tac_gia'] ?>)">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- PHÂN TRANG -->
        <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="text-muted">
                Tổng: <strong><?= $total ?></strong> tác giả | Trang <?= $page_current ?> / <?= $total_pages_view ?>
            </div>
            <nav>
                <ul class="pagination mb-0">
                    <li class="page-item <?= $page_current <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=author&p=<?= $page_current - 1 ?>">Trước</a>
                    </li>
                    <?php for ($i = 1; $i <= $total_pages_view; $i++): ?>
                        <li class="page-item <?= $i == $page_current ? 'active' : '' ?>">
                            <a class="page-link" href="?page=author&p=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?= $page_current >= $total_pages_view ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=author&p=<?= $page_current + 1 ?>">Sau</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll(".row-select").forEach(r => {
        r.addEventListener("click", () => {
            const d = r.dataset;
            document.getElementById("ma_tac_gia").value = d.id;
            document.getElementById("ten_tac_gia").value = d.ten;
            document.getElementById("ngay_sinh").value = d.ngaysinh;
            document.getElementById("quoc_tich").value = d.quoctich;
            document.getElementById("tieu_su").value = d.tieusu;
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
            title: "Xóa tác giả này?",
            text: "Hành động không thể hoàn tác!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Xóa",
            cancelButtonText: "Hủy"
        }).then(res => {
            if (res.isConfirmed) {
                window.location = `?page=author&action=delete&id=${id}`;
            }
        });
    }

</script>