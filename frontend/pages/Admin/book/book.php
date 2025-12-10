<?php
require_once '../them/connect.php';

// --- PHÂN TRANG ---
$per_page = 10;
$page = isset($_GET['p']) ? max(1, (int)$_GET['p']) : 1;
$offset = ($page - 1) * $per_page;

// Tổng số bản ghi
$total = $conn->query("SELECT COUNT(*) FROM sach")->fetchColumn();
$total_page = ceil($total / $per_page);

// Lấy danh sách sách, tác giả
$stmt = $conn->prepare("
    SELECT s.*, n.ten_nxb, tg.ten_tac_gia, tg.ma_tac_gia
    FROM sach s 
    LEFT JOIN nha_xuat_ban n ON s.ma_nxb = n.ma_nxb
    LEFT JOIN sach_tac_gia stg ON s.ma_sach = stg.ma_sach
    LEFT JOIN tac_gia tg ON tg.ma_tac_gia = stg.ma_tac_gia
    ORDER BY s.ma_sach 
    LIMIT ? OFFSET ?
");
$stmt->bindValue(1, $per_page, PDO::PARAM_INT);
$stmt->bindValue(2, $offset, PDO::PARAM_INT);
$stmt->execute();
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Lấy NXB cho combobox
$nxb = $conn->query("SELECT ma_nxb, ten_nxb FROM nha_xuat_ban ORDER BY ten_nxb")->fetchAll(PDO::FETCH_ASSOC);

// Lấy danh sách tác giả
$authors = $conn->query("SELECT ma_tac_gia, ten_tac_gia FROM tac_gia ORDER BY ten_tac_gia")->fetchAll(PDO::FETCH_ASSOC);

// Lấy danh mục
$categories = $conn->query("SELECT ma_danh_muc, ten_danh_muc FROM danh_muc ORDER BY ten_danh_muc")->fetchAll(PDO::FETCH_ASSOC);

// Lấy danh mục theo sách
$danhmuc_sach = [];
$stmt_dm = $conn->query("SELECT sd.ma_sach, d.ma_danh_muc FROM sach_danh_muc sd JOIN danh_muc d ON sd.ma_danh_muc = d.ma_danh_muc");
while ($row = $stmt_dm->fetch(PDO::FETCH_ASSOC)) {
    $danhmuc_sach[$row['ma_sach']][] = $row['ma_danh_muc'];
}

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
        <h4 class="mb-0 fw-bold"><i class="bi bi-book-half me-2"></i> Quản lý sách</h4>
        <a href="../GiaoDien/index.php?page=dashboard" class="btn btn-light btn-sm"><i class="bi bi-house-door"></i> Dashboard</a>
    </div>

    <div class="card-body p-4">
        <!-- FORM THÊM / SỬA -->
        <div class="bg-light rounded p-4 mb-4 shadow-sm">
            <form action="../book/book_handle.php" method="post" class="row g-3">
                <input type="hidden" name="ma_sach" id="ma_sach">

                <div class="col-md-4">
                    <label class="form-label fw-bold">Tên sách*</label>
                    <input type="text" name="ten_sach" id="ten_sach" class="form-control" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">Nhà xuất bản</label>
                    <select name="ma_nxb" id="ma_nxb" class="form-control">
                        <option value="">-- Không chọn --</option>
                        <?php foreach ($nxb as $n): ?>
                            <option value="<?= $n['ma_nxb'] ?>"><?= htmlspecialchars($n['ten_nxb']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-bold">Giá bán*</label>
                    <input type="number" name="gia_ban" id="gia_ban" class="form-control" required>
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-bold">Giá gốc</label>
                    <input type="number" name="gia_goc" id="gia_goc" class="form-control">
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-bold">Số lượng tồn</label>
                    <input type="number" name="so_luong_ton" id="so_luong_ton" class="form-control">
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-bold">Số trang</label>
                    <input type="number" name="so_trang" id="so_trang" class="form-control">
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-bold">Hình thức bìa</label>
                    <input type="text" name="hinh_thuc_bia" id="hinh_thuc_bia" class="form-control">
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-bold">Ngôn ngữ</label>
                    <input type="text" name="ngon_ngu" id="ngon_ngu" class="form-control">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">Năm xuất bản</label>
                    <input type="number" name="nam_xuat_ban" id="nam_xuat_ban" class="form-control">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">ISBN</label>
                    <input type="text" name="ma_isbn" id="ma_isbn" class="form-control">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Tác giả</label>
                    <select name="ma_tac_gia" id="ma_tac_gia" class="form-control" required>
                        <option value="">-- Chọn tác giả --</option>
                        <?php foreach ($authors as $a): ?>
                            <option value="<?= $a['ma_tac_gia'] ?>"><?= htmlspecialchars($a['ten_tac_gia']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Danh mục</label>
                    <select name="danh_muc[]" id="danh_muc" class="form-control" multiple>
                        <?php foreach ($categories as $c): ?>
                            <option value="<?= $c['ma_danh_muc'] ?>"><?= htmlspecialchars($c['ten_danh_muc']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Mô tả</label>
                    <textarea name="mo_ta" id="mo_ta" rows="4" class="form-control"></textarea>
                </div>

                <div class="col-md-12 d-flex gap-2">
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
        
        <!-- PHÂN TRANG -->
        <div class="d-flex justify-content-between align-items-center mt-4" style="margin-bottom: 10px;">
            <div class="text-muted">
                Tổng: <strong><?= $total ?></strong> sách | Trang <?= $page ?>/<?= $total_page ?>
            </div>
            <?php if ($total_page > 1): ?>
                <ul class="pagination mb-0">
                    <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="../GiaoDien/index.php?page=book&p=<?= $page - 1 ?>">Trước</a>
                    </li>
                    <?php for ($i = max(1, $page - 2); $i <= min($total_page, $page + 2); $i++): ?>
                        <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                            <a class="page-link" href="../GiaoDien/index.php?page=book&p=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?= $page >= $total_page ? 'disabled' : '' ?>">
                        <a class="page-link" href="../GiaoDien/index.php?page=book&p=<?= $page + 1 ?>">Sau</a>
                    </li>
                </ul>
            <?php endif; ?>
        </div>

        <!-- DANH SÁCH SÁCH -->
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark text-center">
                    <tr>
                        <th>ID</th>
                        <th>Tên sách</th>
                        <th>NXB</th>
                        <th>Tác giả</th>
                        <th>Giá bán</th>
                        <th>SL tồn</th>
                        <th>Năm XB</th>
                        <th>Xóa</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($books as $b): ?>
                        <tr class="row-select" style="cursor:pointer"
                            data-id="<?= $b['ma_sach'] ?>"
                            data-ten="<?= htmlspecialchars($b['ten_sach']) ?>"
                            data-nxb="<?= $b['ma_nxb'] ?>"
                            data-tacgia="<?= $b['ma_tac_gia'] ?>"
                            data-giaban="<?= $b['gia_ban'] ?>"
                            data-giagoc="<?= $b['gia_goc'] ?>"
                            data-soluong="<?= $b['so_luong_ton'] ?>"
                            data-sotrang="<?= $b['so_trang'] ?>"
                            data-bia="<?= htmlspecialchars($b['hinh_thuc_bia']) ?>"
                            data-ngonngu="<?= htmlspecialchars($b['ngon_ngu']) ?>"
                            data-nam="<?= $b['nam_xuat_ban'] ?>"
                            data-isbn="<?= htmlspecialchars($b['ma_isbn']) ?>"
                            data-mota="<?= htmlspecialchars($b['mo_ta']) ?>"
                            data-danhmuc='<?= json_encode($danhmuc_sach[$b['ma_sach']] ?? []) ?>'>
                            <td class="text-center fw-bold"><?= $b['ma_sach'] ?></td>
                            <td><?= htmlspecialchars($b['ten_sach']) ?></td>
                            <td><?= htmlspecialchars($b['ten_nxb'] ?: '-') ?></td>
                            <td><?= htmlspecialchars($b['ten_tac_gia'] ?: '-') ?></td>
                            <td class="text-center"><?= number_format($b['gia_ban']) ?></td>
                            <td class="text-center"><?= $b['so_luong_ton'] ?></td>
                            <td class="text-center"><?= $b['nam_xuat_ban'] ?></td>
                            <td class="text-center" onclick="event.stopPropagation();">
                                <button class="btn btn-danger btn-sm" onclick="confirmDelete(<?= $b['ma_sach'] ?>)">
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
const dmSelect = document.getElementById("danh_muc");

document.querySelectorAll(".row-select").forEach(row => {
    row.addEventListener("click", function(e) {
        if (e.target.closest('button')) return;

        const d = this.dataset;
        document.getElementById("ma_sach").value = d.id;
        document.getElementById("ten_sach").value = d.ten;
        document.getElementById("ma_nxb").value = d.nxb;
        document.getElementById("ma_tac_gia").value = d.tacgia;
        document.getElementById("gia_ban").value = d.giaban;
        document.getElementById("gia_goc").value = d.giagoc;
        document.getElementById("so_luong_ton").value = d.soluong;
        document.getElementById("so_trang").value = d.sotrang;
        document.getElementById("hinh_thuc_bia").value = d.bia;
        document.getElementById("ngon_ngu").value = d.ngonngu;
        document.getElementById("nam_xuat_ban").value = d.nam;
        document.getElementById("ma_isbn").value = d.isbn;
        document.getElementById("mo_ta").value = d.mota;

        // --- CHECK DANH MỤC ---
        const dmArr = JSON.parse(d.danhmuc || '[]');
        for (let option of dmSelect.options) {
            option.selected = dmArr.includes(option.value);
        }
    });
});

// Xác nhận xóa
function confirmDelete(id) {
    Swal.fire({
        title: 'Xóa sách này?',
        text: "Hành động này không thể hoàn tác!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Có, xóa!',
        cancelButtonText: 'Hủy'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = `../book/book_handle.php?action=delete&id=${id}`;
        }
    });
}

// Làm mới form
function clearForm() {
    document.querySelector("form").reset();
    document.getElementById("ma_sach").value = "";
}
</script>
