<?php
require_once '../them/connect.php';

// --- PHÂN TRANG ---
$per_page = 10;
$page = isset($_GET['p']) ? max(1, (int)$_GET['p']) : 1;
$offset = ($page - 1) * $per_page;

// Tổng số bản ghi
$total = $conn->query("SELECT COUNT(*) FROM nha_xuat_ban")->fetchColumn();
$total_page = ceil($total / $per_page);

// Lấy danh sách
$stmt = $conn->prepare("SELECT * FROM nha_xuat_ban ORDER BY ma_nxb DESC LIMIT ? OFFSET ?");
$stmt->bindValue(1, $per_page, PDO::PARAM_INT);
$stmt->bindValue(2, $offset, PDO::PARAM_INT);
$stmt->execute();
$publishers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Thông báo
$success = $_GET['success'] ?? '';
$error   = $_GET['error'] ?? '';
?>

<link rel="stylesheet" href="../bootstrap-5.0.2-dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<script src="../bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js"></script>
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
        <h4 class="mb-0 fw-bold">
            <i class="bi bi-building me-2"></i> Quản lý nhà xuất bản
        </h4>
        <a href="../GiaoDien/index.php?page=dashboard" class="btn btn-light btn-sm">
            <i class="bi bi-house-door"></i> Dashboard
        </a>
    </div>

    <div class="card-body p-4">

        <!-- Form thêm/sửa -->
        <div class="bg-light p-4 rounded shadow-sm mb-4">
            <form action="../publisher/publisher_handle.php" method="post" class="row g-3">

                <input type="hidden" name="ma_nxb" id="ma_nxb">

                <div class="col-md-3">
                    <label class="form-label fw-bold">Tên NXB*</label>
                    <input type="text" name="ten_nxb" id="ten_nxb" class="form-control" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-bold">Số điện thoại</label>
                    <input type="text" name="so_dien_thoai" id="so_dien_thoai" class="form-control">
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-bold">Email</label>
                    <input type="email" name="email" id="email" class="form-control">
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-bold">Địa chỉ</label>
                    <input type="text" name="dia_chi" id="dia_chi" class="form-control">
                </div>

                <div class="col-md-12 d-flex gap-2">
                    <button type="submit" name="action" value="add" class="btn btn-success flex-fill">
                        <i class="bi bi-plus-lg"></i> Thêm mới
                    </button>

                    <button type="submit" name="action" value="update" class="btn btn-warning text-white flex-fill">
                        <i class="bi bi-save"></i> Cập nhật
                    </button>

                    <button type="button" class="btn btn-secondary flex-fill" onclick="clearForm()">
                        <i class="bi bi-arrow-repeat"></i> Làm mới
                    </button>
                </div>

            </form>
        </div>

        <!-- Danh sách -->
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr class="text-center">
                        <th>ID</th>
                        <th>Tên NXB</th>
                        <th>Địa chỉ</th>
                        <th>SĐT</th>
                        <th>Email</th>
                        <th>Xóa</th>
                    </tr>
                </thead>

                <tbody>
                <?php foreach($publishers as $p): ?>
                    <tr class="row-select" style="cursor:pointer"
                        data-id="<?= $p['ma_nxb'] ?>"
                        data-ten="<?= htmlspecialchars($p['ten_nxb']) ?>"
                        data-diachi="<?= htmlspecialchars($p['dia_chi']) ?>"
                        data-sdt="<?= htmlspecialchars($p['so_dien_thoai']) ?>"
                        data-email="<?= htmlspecialchars($p['email']) ?>">

                        <td class="text-center fw-bold"><?= $p['ma_nxb'] ?></td>
                        <td><?= htmlspecialchars($p['ten_nxb']) ?></td>
                        <td><?= htmlspecialchars($p['dia_chi'] ?? '-') ?></td>
                        <td class="text-center"><?= $p['so_dien_thoai'] ?? '-' ?></td>
                        <td><?= htmlspecialchars($p['email'] ?? '-') ?></td>

                        <td class="text-center" onclick="event.stopPropagation();">
                            <button class="btn btn-danger btn-sm" onclick="confirmDelete(<?= $p['ma_nxb'] ?>)">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Phân trang -->
        <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="text-muted">
                Tổng: <strong><?= $total ?></strong> NXB | Trang <?= $page ?>/<?= $total_page ?>
            </div>

            <?php if($total_page > 1): ?>
            <ul class="pagination mb-0">
                <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                    <a class="page-link" href="../GiaoDien/index.php?page=publisher&p=<?= $page-1 ?>">Trước</a>
                </li>

                <?php for($i=max(1,$page-2); $i<=min($total_page,$page+2); $i++): ?>
                    <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                        <a class="page-link" href="../GiaoDien/index.php?page=publisher&p=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>

                <li class="page-item <?= $page >= $total_page ? 'disabled' : '' ?>">
                    <a class="page-link" href="../GiaoDien/index.php?page=publisher&p=<?= $page+1 ?>">Sau</a>
                </li>
            </ul>
            <?php endif; ?>
        </div>

    </div>
</div>


<script>
// Đổ dữ liệu khi click row
document.querySelectorAll(".row-select").forEach(row => {
    row.addEventListener("click", function(e) {
        if (e.target.closest('button')) return;

        const d = this.dataset;
        document.getElementById("ma_nxb").value = d.id;
        document.getElementById("ten_nxb").value = d.ten;
        document.getElementById("dia_chi").value = d.diachi;
        document.getElementById("so_dien_thoai").value = d.sdt;
        document.getElementById("email").value = d.email;
    });
});

// Xác nhận xóa
function confirmDelete(id) {
    Swal.fire({
        title: "Xóa nhà xuất bản?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        confirmButtonText: "Xóa",
        cancelButtonText: "Hủy"
    }).then((r) => {
        if (r.isConfirmed) {
            window.location.href = `../publisher/publisher_handle.php?action=delete&id=${id}`;
        }
    });
}

// Làm mới form
function clearForm() {
    document.querySelector("form").reset();
    document.getElementById("ma_nxb").value = "";
}
</script>
