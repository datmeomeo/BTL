<?php
require_once '../them/connect.php';

// --- PHÂN TRANG ---
$per_page = 10;
$page = isset($_GET['p']) ? max(1, (int)$_GET['p']) : 1;
$offset = ($page - 1) * $per_page;

// Tổng số bản ghi
$total = $conn->query("SELECT COUNT(*) FROM tac_gia")->fetchColumn();
$total_page = ceil($total / $per_page);

// Lấy danh sách tác giả
$stmt = $conn->prepare("SELECT * FROM tac_gia ORDER BY ma_tac_gia LIMIT ? OFFSET ?");
$stmt->bindValue(1, $per_page, PDO::PARAM_INT);
$stmt->bindValue(2, $offset, PDO::PARAM_INT);
$stmt->execute();
$authors = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Lấy thông báo
$success = $_GET['success'] ?? '';
$error   = $_GET['error'] ?? '';
?>


<link rel="stylesheet" href="../bootstrap-5.0.2-dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<script src="../bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<!-- HIỂN THỊ THÔNG BÁO -->
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

    <!-- HEADER -->
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3">
        <h4 class="mb-0 fw-bold">
            <i class="bi bi-people-fill me-2"></i> Quản lý tác giả
        </h4>
        <a href="../GiaoDien/index.php?page=dashboard" class="btn btn-light btn-sm">
            <i class="bi bi-house-door"></i> Dashboard
        </a>
    </div>

    <!-- BODY -->
    <div class="card-body p-4">

        <!-- FORM THÊM / SỬA -->
        <div class="bg-light rounded p-4 mb-4 shadow-sm">
            <form action="../author/author_handle.php" method="post" class="row g-3">

                <input type="hidden" name="ma_tac_gia" id="ma_tac_gia">

                <div class="col-md-4">
                    <label class="form-label fw-bold">Tên tác giả*</label>
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

                <div class="col-md-8">
                    <label class="form-label fw-bold">Tiểu sử</label>
                    <textarea name="tieu_su" id="tieu_su" rows="3" class="form-control"></textarea>
                </div>

                <div class="col-md-4 d-flex gap-2 align-items-center">
                    <button type="submit" name="action" value="add" class="btn btn-success flex-fill">
                        <i class="bi bi-plus-lg"></i> Thêm mới
                    </button>

                    <button type="submit" name="action" value="update" class="btn btn-warning text-white flex-fill">
                        <i class="bi bi-save"></i> Cập nhật
                    </button>

                    <button type="button" onclick="clearForm()" class="btn btn-secondary flex-fill">
                        <i class="bi bi-arrow-repeat"></i> Làm mới
                    </button>
                </div>

            </form>
        </div>


        <!-- DANH SÁCH -->
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark text-center">
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
                    <tr class="row-select" style="cursor:pointer"
                        data-id="<?= $a['ma_tac_gia'] ?>"
                        data-ten="<?= htmlspecialchars($a['ten_tac_gia']) ?>"
                        data-ngaysinh="<?= htmlspecialchars($a['ngay_sinh'] ?? '') ?>"
                        data-quoctich="<?= htmlspecialchars($a['quoc_tich'] ?? '') ?>"
                        data-tieusu="<?= htmlspecialchars($a['tieu_su'] ?? '') ?>">

                        <td class="text-center fw-bold"><?= $a['ma_tac_gia'] ?></td>
                        <td><?= htmlspecialchars($a['ten_tac_gia']) ?></td>
                        <td class="text-center"><?= $a['ngay_sinh'] ?: '-' ?></td>
                        <td><?= htmlspecialchars($a['quoc_tich'] ?: '-') ?></td>
                        <td><?= htmlspecialchars($a['tieu_su'] ?: '-') ?></td>

                        <td class="text-center" onclick="event.stopPropagation();">
                            <button class="btn btn-danger btn-sm" onclick="confirmDelete(<?= $a['ma_tac_gia'] ?>)">
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
                Tổng: <strong><?= $total ?></strong> tác giả | Trang <?= $page ?>/<?= $total_page ?>
            </div>

            <?php if ($total_page > 1): ?>
            <nav>
                <ul class="pagination mb-0">

                    <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="../GiaoDien/index.php?page=author&p=<?= $page - 1 ?>">Trước</a>
                    </li>

                    <?php
                    for ($i = max(1, $page - 2); $i <= min($total_page, $page + 2); $i++):
                    ?>
                        <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                            <a class="page-link" href="../GiaoDien/index.php?page=author&p=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>

                    <li class="page-item <?= $page >= $total_page ? 'disabled' : '' ?>">
                        <a class="page-link" href="../GiaoDien/index.php?page=author&p=<?= $page + 1 ?>">Sau</a>
                    </li>

                </ul>
            </nav>
            <?php endif; ?>
        </div>

    </div>
</div>


<script>
// --- CLICK CHỌN DÒNG ---
document.querySelectorAll(".row-select").forEach(row => {
    row.addEventListener("click", function(e) {
        if (e.target.closest('button')) return;

        const d = this.dataset;
        document.getElementById("ma_tac_gia").value = d.id;
        document.getElementById("ten_tac_gia").value = d.ten;
        document.getElementById("ngay_sinh").value = d.ngaysinh;
        document.getElementById("quoc_tich").value = d.quoctich;
        document.getElementById("tieu_su").value = d.tieusu;
    });
});

// --- XÁC NHẬN XÓA ---
function confirmDelete(id) {
    Swal.fire({
        title: 'Xóa tác giả này?',
        text: "Hành động này không thể hoàn tác!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Có, xóa luôn!',
        cancelButtonText: 'Hủy'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = `../author/author_handle.php?action=delete&id=${id}`;
        }
    });
}

// --- LÀM MỚI FORM ---
function clearForm() {
    document.querySelector("form").reset();
    document.getElementById("ma_tac_gia").value = "";
}
</script>
