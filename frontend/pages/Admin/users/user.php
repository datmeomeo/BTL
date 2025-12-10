<?php
require_once '../them/connect.php';

// --- PHÂN TRANG ---
$per_page = 10;
$page     = isset($_GET['p']) ? max(1, (int)$_GET['p']) : 1;
$offset   = ($page - 1) * $per_page;

// Tổng số bản ghi
$total       = $conn->query("SELECT COUNT(*) FROM nguoi_dung")->fetchColumn();
$total_pages = ceil($total / $per_page);

// Lấy danh sách người dùng
$stmt = $conn->prepare("SELECT * FROM nguoi_dung ORDER BY ma_nguoi_dung LIMIT ? OFFSET ?");
$stmt->bindValue(1, $per_page, PDO::PARAM_INT);
$stmt->bindValue(2, $offset, PDO::PARAM_INT);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

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

<!-- CARD CHÍNH -->
<div class="card shadow-lg border-0">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3">
        <h4 class="mb-0 fw-bold">
            <i class="bi bi-people-fill me-2"></i> Quản lý người dùng
        </h4>
        <a href="../GiaoDien/index.php?page=dashboard" class="btn btn-light btn-sm">
            <i class="bi bi-house-door"></i> Dashboard
        </a>
    </div>

    <div class="card-body p-4">

        <!-- FORM THÊM/SỬA -->
        <div class="bg-light rounded p-4 mb-4 shadow-sm">
            <form action="../users/user_handle.php" method="post" class="row g-3">
                <input type="hidden" name="ma_nguoi_dung" id="ma_nguoi_dung">

                <div class="col-12 col-md-6 col-lg-3">
                    <label class="form-label fw-bold">Tên đăng nhập *</label>
                    <input name="ten_dang_nhap" id="ten_dang_nhap" class="form-control" required>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <label class="form-label fw-bold">Email *</label>
                    <input name="email" type="email" id="email" class="form-control" required>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <label class="form-label fw-bold">Họ tên</label>
                    <input name="ho_ten" id="ho_ten" class="form-control">
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <label class="form-label fw-bold">Số điện thoại</label>
                    <input name="so_dien_thoai" id="so_dien_thoai" class="form-control">
                </div>

                <div class="col-12 col-md-6 col-lg-2">
                    <label class="form-label fw-bold">Vai trò</label>
                    <select name="vai_tro" id="vai_tro" class="form-select">
                        <option value="customer">Khách hàng</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="col-12 col-md-6 col-lg-2">
                    <label class="form-label fw-bold">Trạng thái</label>
                    <select name="trang_thai" id="trang_thai" class="form-select">
                        <option value="active">Hoạt động</option>
                        <option value="inactive">Đã khóa</option>
                    </select>
                </div>

                <div class="col-12 col-lg-4 d-flex gap-2 align-items-end">
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
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr class="text-center">
                        <th width="60">ID</th>
                        <th>Tên đăng nhập</th>
                        <th>Họ tên</th>
                        <th>Email</th>
                        <th>SĐT</th>
                        <th>Vai trò</th>
                        <th>Trạng thái</th>
                        <th>Ngày đăng ký</th>
                        <th width="80">Xóa</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $u): ?>
                    <tr class="row-select" style="cursor:pointer"
                        data-id="<?= $u['ma_nguoi_dung'] ?>"
                        data-ten="<?= htmlspecialchars($u['ten_dang_nhap']) ?>"
                        data-hotenn="<?= htmlspecialchars($u['ho_ten'] ?? '') ?>"
                        data-email="<?= htmlspecialchars($u['email']) ?>"
                        data-sdt="<?= htmlspecialchars($u['so_dien_thoai'] ?? '') ?>"
                        data-vaitro="<?= $u['vai_tro'] ?>"
                        data-trangthai="<?= $u['trang_thai'] ?>">   
                        <td class="text-center fw-bold"><?= $u['ma_nguoi_dung'] ?></td>
                        <td><strong><?= htmlspecialchars($u['ten_dang_nhap']) ?></strong></td>
                        <td><?= htmlspecialchars($u['ho_ten'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($u['email']) ?></td>
                        <td><?= htmlspecialchars($u['so_dien_thoai'] ?? '-') ?></td>
                        <td class="text-center">
                            <span class="badge <?= $u['vai_tro']=='admin' ? 'bg-danger' : 'bg-secondary' ?>">
                                <?= $u['vai_tro']=='admin' ? 'Admin' : 'Khách' ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="badge <?= $u['trang_thai']=='active' ? 'bg-success' : 'bg-warning text-dark' ?>">
                                <?= $u['trang_thai']=='active' ? 'Hoạt động' : 'Khóa' ?>
                            </span>
                        </td>
                        <td class="text-center small"><?= date('d/m/Y H:i', strtotime($u['ngay_dang_ky'])) ?></td>
                        <td class="text-center" onclick="event.stopPropagation();">
                            <?php if ($u['ma_nguoi_dung'] > 1): ?>
                                <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete(<?= $u['ma_nguoi_dung'] ?>)">
                                    <i class="bi bi-trash"></i>
                                </button>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- PHÂN TRANG -->
        <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="text-muted">
                Tổng: <strong><?= $total ?></strong> người dùng | Trang <?= $page ?> / <?= $total_pages ?>
            </div>
            <?php if ($total_pages > 1): ?>
            <nav>
                <ul class="pagination mb-0">
                    <li class="page-item <?= $page<=1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="../GiaoDien/index.php?page=user&p=<?= $page-1 ?>">Trước</a>
                    </li>
                    <?php for ($i = max(1, $page-2); $i <= min($total_pages, $page+2); $i++): ?>
                    <li class="page-item <?= $i==$page ? 'active' : '' ?>">
                        <a class="page-link" href="../GiaoDien/index.php?page=user&p=<?= $i ?>"><?= $i ?></a>
                    </li>
                    <?php endfor; ?>
                    <li class="page-item <?= $page>=$total_pages ? 'disabled' : '' ?>">
                        <a class="page-link" href="../GiaoDien/index.php?page=user&p=<?= $page+1 ?>">Sau</a>
                    </li>
                </ul>
            </nav>
            <?php endif; ?>
        </div>

    </div>
</div>

<script>
document.querySelectorAll(".row-select").forEach(row => {
    row.addEventListener("click", function(e) {
        if (e.target.closest('button')) return;
        const d = this.dataset;
        document.getElementById("ma_nguoi_dung").value = d.id;
        document.getElementById("ten_dang_nhap").value = d.ten;
        document.getElementById("email").value = d.email;
        document.getElementById("ho_ten").value = d.hotenn;
        document.getElementById("so_dien_thoai").value = d.sdt;
        document.getElementById("vai_tro").value = d.vaitro;
        document.getElementById("trang_thai").value = d.trangthai;
    });
});

function confirmDelete(id) {
    Swal.fire({
        title: 'Xóa người dùng này?',
        text: "Hành động này không thể hoàn tác!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Có, xóa luôn!',
        cancelButtonText: 'Hủy'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = `../users/user_handle.php?action=delete&id=${id}`;
        }
    });
}

function clearForm() {
    document.querySelector("form").reset();
    document.getElementById("ma_nguoi_dung").value = "";
}
</script>
