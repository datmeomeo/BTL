<?php
require_once '../them/connect.php';

/* ==================== PHÂN TRANG ==================== */
$per_page = 10;
$page = isset($_GET['p']) ? max(1, (int)$_GET['p']) : 1;
$offset = ($page - 1) * $per_page;

$total = $conn->query("SELECT COUNT(*) FROM danh_muc")->fetchColumn();
$total_page = ceil($total / $per_page);

$stmt = $conn->prepare("
    SELECT * FROM danh_muc 
    ORDER BY ma_danh_muc
    LIMIT :limit OFFSET :offset
");
$stmt->bindValue(':limit', $per_page, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$category = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* ==================== THÔNG BÁO ==================== */
$success = $_GET['success'] ?? '';
$error = $_GET['error'] ?? '';
?>

<link rel="stylesheet" href="../bootstrap-5.0.2-dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<script src="../bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js"></script>
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

<!-- Giao diện của bạn giữ nguyên 100% -->
<div class="card shadow-lg border-0">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3">
        <h4 class="mb-0 fw-bold"><i class="bi bi-tags-fill me-2"></i> Quản lý danh mục</h4>
        <a href="../GiaoDien/index.php?page=dashboard" class="btn btn-light btn-sm">
            <i class="bi bi-house-door"></i> Dashboard
        </a>
    </div>

    <div class="card-body p-4">

        <!-- FORM DANH MỤC -->
        <div class="bg-light rounded p-4 mb-4 shadow-sm">
            <form action="../category/category_handle.php" method="post" class="row g-3">
                <input type="hidden" name="ma_danh_muc" id="ma_danh_muc">

                <!-- Các input giữ nguyên giao diện -->
                <div class="col-md-3">
                    <label class="form-label fw-bold">Tên danh mục*</label>
                    <input name="ten_danh_muc" id="ten_danh_muc" class="form-control" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-bold">Slug</label>
                    <input name="slug" id="slug" class="form-control">
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
                    <input name="icon" id="icon" class="form-control">
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-bold">Màu sắc</label>
                    <input name="mau_sac" id="mau_sac" class="form-control">
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-bold">Nổi bật</label>
                    <select name="la_danh_muc_noi_bat" id="la_danh_muc_noi_bat" class="form-control">
                        <option value="0">Không</option>
                        <option value="1">Có</option>
                    </select>
                </div>

                <div class="col-3">
                    <label class="form-label fw-bold">Mô tả</label>
                    <input name="mo_ta" id="mo_ta" class="form-control">
                </div>

                <!-- Buttons -->
                <div class="col-md-6 d-flex gap-2">
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

        <!-- PHÂN TRANG GIỮ NGUYÊN -->
        <div class="d-flex justify-content-between align-items-center mt-4" style="margin-bottom: 10px;">
            <div class="text-muted">
                Tổng: <b><?= $total ?></b> | Trang <?= $page ?>/<?= $total_page ?>
            </div>

            <nav>
                <ul class="pagination mb-0">
                    <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=category&p=<?= $page - 1 ?>">Trước</a>
                    </li>

                    <?php
                    for ($i = max(1, $page - 2); $i <= min($total_page, $page + 2); $i++):
                    ?>
                    <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                        <a class="page-link" href="?page=category&p=<?= $i ?>"><?= $i ?></a>
                    </li>
                    <?php endfor; ?>

                    <li class="page-item <?= $page >= $total_page ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=category&p=<?= $page + 1 ?>">Sau</a>
                    </li>
                </ul>
            </nav>
        </div>

        <!-- BẢNG DANH MỤC -->
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark text-center">
                    <tr>
                        <th>ID</th>
                        <th>Tên danh mục</th>
                        <th>Slug</th>
                        <th>Cha</th>
                        <th>Menu</th>
                        <th>Mô tả</th>
                        <th width="80">Xóa</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($category as $c): ?>
                    <tr class="row-select"
                        data-id="<?= $c['ma_danh_muc'] ?>"
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
                        <td class="text-center fw-bold"><?= $c['ma_danh_muc'] ?></td>
                        <td><?= htmlspecialchars($c['ten_danh_muc']) ?></td>
                        <td><?= htmlspecialchars($c['slug']) ?></td>
                        <td class="text-center"><?= $c['danh_muc_cha'] ?: "-" ?></td>
                        <td class="text-center"><?= $c['hien_thi_menu'] ? "✔" : "✖" ?></td>
                        <td><?= htmlspecialchars($c['mo_ta']) ?></td>

                        <td class="text-center" onclick="event.stopPropagation()">
                            <button class="btn btn-danger btn-sm" onclick="confirmDelete(<?= $c['ma_danh_muc'] ?>)">
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
/* ======= NẠP DỮ LIỆU VÀO FORM KHI CLICK HÀNG ======= */
document.querySelectorAll(".row-select").forEach(row => {
    row.addEventListener("click", function() {
        let d = this.dataset;

        ma_danh_muc.value = d.id;
        ten_danh_muc.value = d.ten;
        slug.value = d.slug;
        mo_ta.value = d.mo_ta;
        danh_muc_cha.value = d.cha;
        cap_do.value = d.cap;
        thu_tu.value = d.thutu;
        hien_thi_menu.value = d.menu;
        icon.value = d.icon;
        mau_sac.value = d.mau;
        la_danh_muc_noi_bat.value = d.noibat;
    });
});

/* ======= XÁC NHẬN XÓA ======= */
function confirmDelete(id) {
    Swal.fire({
        title: "Xóa danh mục?",
        text: "Hành động này không thể hoàn tác!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        confirmButtonText: "Xóa luôn",
        cancelButtonText: "Hủy"
    }).then(r => {
        if (r.isConfirmed) {
            window.location.href = `category_handle.php?action=delete&id=${id}`;
        }
    });
}

/* ======= RESET FORM ======= */
function clearForm() {
    document.querySelector("form").reset();
    ma_danh_muc.value = "";
}
</script>
