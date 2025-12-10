<?php
// $categories, $success, $error, $page_current, $total_pages_view đã có từ controller
?>

<link rel="stylesheet" href="/bootstrap-5.0.2-dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<script src="/bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php if ($error): ?>
<div class="alert alert-danger alert-dismissible fade show">
    <?= htmlspecialchars($error) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<?php if ($success): ?>
<div class="alert alert-success alert-dismissible fade show">
    <?= htmlspecialchars($success) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="card shadow-lg border-0">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3">
        <h4 class="mb-0 fw-bold"><i class="bi bi-tags-fill me-2"></i> Quản lý danh mục</h4>
        <a href="?page=dashboard" class="btn btn-light btn-sm"><i class="bi bi-house-door"></i> Dashboard</a>
    </div>

    <div class="card-body p-4">

        <!-- FORM TÌM KIẾM -->
        <form action="" method="get" class="row g-3 mb-4">
            <input type="hidden" name="page" value="category">
            <div class="col-md-6">
                <input type="text" name="search_ten" class="form-control" placeholder="Tên danh mục"
                       value="<?= htmlspecialchars($_GET['search_ten'] ?? '') ?>">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search"></i> Tìm</button>
            </div>
        </form>

        <!-- FORM THÊM/SỬA -->
        <div class="bg-light rounded p-4 mb-4 shadow-sm">
            <form action="?page=category&action=save" method="post" class="row g-3">
                <input type="hidden" name="ma_danh_muc" id="ma_danh_muc">

                <div class="col-md-3">
                    <label class="form-label fw-bold">Tên danh mục *</label>
                    <input type="text" name="ten_danh_muc" id="ten_danh_muc" class="form-control" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-bold">Slug</label>
                    <input type="text" name="slug" id="slug" class="form-control">
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-bold">Danh mục cha</label>
                    <input type="number" name="danh_muc_cha" id="danh_muc_cha" class="form-control">
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-bold">Cấp độ</label>
                    <input type="number" name="cap_do" id="cap_do" class="form-control" value="1">
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-bold">Thứ tự</label>
                    <input type="number" name="thu_tu" id="thu_tu" class="form-control" value="0">
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-bold">Hiển thị menu</label>
                    <select name="hien_thi_menu" id="hien_thi_menu" class="form-control">
                        <option value="1">Có</option>
                        <option value="0">Không</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-bold">Icon</label>
                    <input type="text" name="icon" id="icon" class="form-control">
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-bold">Màu sắc</label>
                    <input type="text" name="mau_sac" id="mau_sac" class="form-control">
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-bold">Nổi bật</label>
                    <select name="la_danh_muc_noi_bat" id="la_danh_muc_noi_bat" class="form-control">
                        <option value="0">Không</option>
                        <option value="1">Có</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Mô tả</label>
                    <input type="text" name="mo_ta" id="mo_ta" class="form-control">
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
                Tổng: <strong><?= $total ?></strong> | Trang <?= $page_current ?> / <?= $total_pages_view ?>
            </div>
            <nav>
                <ul class="pagination mb-0">
                    <li class="page-item <?= $page_current <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=category&p=<?= $page_current - 1 ?>">Trước</a>
                    </li>
                    <?php for ($i = 1; $i <= $total_pages_view; $i++): ?>
                        <li class="page-item <?= $i == $page_current ? 'active' : '' ?>">
                            <a class="page-link" href="?page=category&p=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?= $page_current >= $total_pages_view ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=category&p=<?= $page_current + 1 ?>">Sau</a>
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
                        <th>Tên danh mục</th>
                        <th>Slug</th>
                        <th>Cha</th>
                        <th>Menu</th>
                        <th>Mô tả</th>
                        <th>Xóa</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $c): ?>
                    <tr class="row-select" data-id="<?= $c['ma_danh_muc'] ?>"
                        data-ten="<?= htmlspecialchars($c['ten_danh_muc']) ?>"
                        data-slug="<?= htmlspecialchars($c['slug']) ?>"
                        data-mo_ta="<?= htmlspecialchars($c['mo_ta']) ?>"
                        data-cha="<?= $c['danh_muc_cha'] ?>"
                        data-cap="<?= $c['cap_do'] ?>"
                        data-thutu="<?= $c['thu_tu'] ?>"
                        data-menu="<?= $c['hien_thi_menu'] ?>"
                        data-icon="<?= htmlspecialchars($c['icon']) ?>"
                        data-mau="<?= htmlspecialchars($c['mau_sac']) ?>"
                        data-noibat="<?= $c['la_danh_muc_noi_bat'] ?>"
                        style="cursor:pointer"
                    >
                        <td><?= $c['ma_danh_muc'] ?></td>
                        <td><?= htmlspecialchars($c['ten_danh_muc']) ?></td>
                        <td><?= htmlspecialchars($c['slug']) ?></td>
                        <td><?= $c['danh_muc_cha'] ?: "-" ?></td>
                        <td><?= $c['hien_thi_menu'] ? "✔" : "✖" ?></td>
                        <td><?= htmlspecialchars($c['mo_ta']) ?></td>
                        <td>
                            <button class="btn btn-danger btn-sm" onclick="event.stopPropagation(); confirmDelete(<?= $c['ma_danh_muc'] ?>)">
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
        document.getElementById("ma_danh_muc").value = d.id;
        document.getElementById("ten_danh_muc").value = d.ten;
        document.getElementById("slug").value = d.slug;
        document.getElementById("mo_ta").value = d.mo_ta;
        document.getElementById("danh_muc_cha").value = d.cha;
        document.getElementById("cap_do").value = d.cap;
        document.getElementById("thu_tu").value = d.thutu;
        document.getElementById("hien_thi_menu").value = d.menu;
        document.getElementById("icon").value = d.icon;
        document.getElementById("mau_sac").value = d.mau;
        document.getElementById("la_danh_muc_noi_bat").value = d.noibat;
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
        title: "Xóa danh mục?",
        text: "Hành động không thể hoàn tác!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Xóa",
        cancelButtonText: "Hủy"
    }).then(res => {
        if (res.isConfirmed) {
            window.location = `?page=category&action=delete&id=${id}`;
        }
    });
}
</script>
