<?php
// Biến $users, $success, $error, $page_current, $total_pages_view, $search_ten, $search_vaitro, $search_trangthai đã có từ controller
?>
<link rel="stylesheet" href="/Admin_MVC/bootstrap-5.0.2-dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<script src="/Admin_MVC/bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php if (!empty($error)): ?>
<div class="alert alert-danger alert-dismissible fade show">
    <i class="bi bi-exclamation-triangle-fill"></i> <?= htmlspecialchars($error) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<?php if (!empty($success)): ?>
<div class="alert alert-success alert-dismissible fade show">
    <i class="bi bi-check-circle-fill"></i> <?= htmlspecialchars($success) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="card shadow-lg border-0">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3">
        <h4 class="mb-0 fw-bold"><i class="bi bi-people-fill me-2"></i> Quản lý người dùng</h4>
        <a href="?page=dashboard" class="btn btn-light btn-sm"><i class="bi bi-house-door"></i> Dashboard</a>
    </div>

    <div class="card-body p-4">

        <!-- FORM TÌM KIẾM -->
        <form action="" method="get" class="row g-3 mb-4">
            <input type="hidden" name="page" value="user">
            <div class="col-12 col-md-4">
                <input type="text" name="search_ten" class="form-control" placeholder="Tên đăng nhập"
                    value="<?= htmlspecialchars($search_ten) ?>">
            </div>
            <div class="col-12 col-md-3">
                <select name="search_vaitro" class="form-select">
                    <option value="">-- Chọn vai trò --</option>
                    <option value="admin" <?= ($search_vaitro=='admin') ? 'selected' : '' ?>>Admin</option>
                    <option value="customer" <?= ($search_vaitro=='customer') ? 'selected' : '' ?>>Khách</option>
                </select>
            </div>
            <div class="col-12 col-md-3">
                <select name="search_trangthai" class="form-select">
                    <option value="">-- Chọn trạng thái --</option>
                    <option value="active" <?= ($search_trangthai=='active') ? 'selected' : '' ?>>Hoạt động</option>
                    <option value="inactive" <?= ($search_trangthai=='inactive') ? 'selected' : '' ?>>Khóa</option>
                </select>
            </div>
            <div class="col-12 col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search"></i> Tìm
                </button>
            </div>
        </form>

        <!-- FORM THÊM/SỬA -->
        <div class="bg-light rounded p-4 mb-4 shadow-sm">
            <form action="?page=user&action=save" method="post" class="row g-3">
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

        <!-- PHÂN TRANG -->
        <div class="d-flex justify-content-between align-items-center mt-4" style="margin-bottom: 10px;">
            <div class="text-muted">
                Tổng: <strong><?= $total ?></strong> người dùng | Trang <?= $page_current ?> / <?= $total_pages_view ?>
            </div>
            <nav>
                <ul class="pagination mb-0">
                    <li class="page-item <?= $page_current<=1 ? 'disabled':'' ?>">
                        <a class="page-link" href="?page=user&p=<?= $page_current-1 ?>&search_ten=<?= urlencode($search_ten) ?>&search_vaitro=<?= urlencode($search_vaitro) ?>&search_trangthai=<?= urlencode($search_trangthai) ?>">Trước</a>
                    </li>
                    <?php for($i=1; $i<=$total_pages_view; $i++): ?>
                        <li class="page-item <?= $i==$page_current?'active':'' ?>">
                            <a class="page-link" href="?page=user&p=<?= $i ?>&search_ten=<?= urlencode($search_ten) ?>&search_vaitro=<?= urlencode($search_vaitro) ?>&search_trangthai=<?= urlencode($search_trangthai) ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?= $page_current>=$total_pages_view?'disabled':'' ?>">
                        <a class="page-link" href="?page=user&p=<?= $page_current+1 ?>&search_ten=<?= urlencode($search_ten) ?>&search_vaitro=<?= urlencode($search_vaitro) ?>&search_trangthai=<?= urlencode($search_trangthai) ?>">Sau</a>
                    </li>
                </ul>
            </nav>
        </div>
        
        <!-- BẢNG -->
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr class="text-center">
                        <th>ID</th>
                        <th>Tên đăng nhập</th>
                        <th>Họ tên</th>
                        <th>Email</th>
                        <th>SĐT</th>
                        <th>Vai trò</th>
                        <th>Trạng thái</th>
                        <th>Ngày đăng ký</th>
                        <th>Xóa</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $u): ?>
                        <tr class="row-select" data-id="<?= $u['ma_nguoi_dung'] ?>"
                            data-ten="<?= htmlspecialchars($u['ten_dang_nhap']) ?>"
                            data-hotenn="<?= htmlspecialchars($u['ho_ten']) ?>"
                            data-email="<?= htmlspecialchars($u['email']) ?>"
                            data-sdt="<?= htmlspecialchars($u['so_dien_thoai']) ?>"
                            data-vaitro="<?= $u['vai_tro'] ?>"
                            data-trangthai="<?= $u['trang_thai'] ?>"
                            style="cursor:pointer">
                            <td class="text-center"><?= $u['ma_nguoi_dung'] ?></td>
                            <td><strong><?= htmlspecialchars($u['ten_dang_nhap']) ?></strong></td>
                            <td><?= htmlspecialchars($u['ho_ten']) ?></td>
                            <td><?= htmlspecialchars($u['email']) ?></td>
                            <td><?= htmlspecialchars($u['so_dien_thoai']) ?></td>
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
                            <td class="text-center small"><?= date("d/m/Y H:i", strtotime($u['ngay_dang_ky'])) ?></td>
                            <td class="text-center" onclick="event.stopPropagation();">
                                <?php if ($u['ma_nguoi_dung'] > 1): ?>
                                    <button onclick="confirmDelete(<?= $u['ma_nguoi_dung'] ?>)" class="btn btn-danger btn-sm">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                <?php endif; ?>
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
        ma_nguoi_dung.value = d.id;
        ten_dang_nhap.value = d.ten;
        email.value = d.email;
        ho_ten.value = d.hotenn;
        so_dien_thoai.value = d.sdt;
        vai_tro.value = d.vaitro;
        trang_thai.value = d.trangthai;
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
        title: "Xóa người dùng này?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Xóa",
        cancelButtonText: "Hủy",
    }).then(res => {
        if(res.isConfirmed){
            window.location = "?page=user&action=delete&id="+id;
        }
    });
}
</script>
