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
        <h4 class="mb-0 fw-bold">Quản lý sách</h4>
        <a href="?page=dashboard" class="btn btn-light btn-sm">Dashboard</a>
    </div>

    <div class="card-body p-4">
                        <!-- FORM TÌM KIẾM  -->
        <div class="card mb-4 shadow-sm border-0">
            
            <div class="card-body p-4 bg-light">
                <form method="get" class="row g-3">
                    <input type="hidden" name="page" value="book">

                    <!-- Tên sách -->
                    <div class="col-lg-4 col-md-12">
                        <label class="form-label fw-bold">Tên sách</label>
                        <input type="text" name="search_ten" class="form-control form-control-lg" 
                               value="<?= htmlspecialchars($search_ten ?? '') ?>" 
                               placeholder="Nhập tên sách...">
                    </div>

                    <!-- Tác giả -->
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label fw-bold">Tác giả</label>
                        <select name="search_tacgia" class="form-select form-select-lg">
                            <option value="">-- Tất cả tác giả --</option>
                            <?php foreach ($authors as $a): ?>
                                <option value="<?= $a['ma_tac_gia'] ?>" 
                                    <?= (!empty($search_tacgia) && $search_tacgia == $a['ma_tac_gia']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($a['ten_tac_gia']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Nhà xuất bản -->
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label fw-bold">Nhà xuất bản</label>
                        <select name="search_nxb" class="form-select form-select-lg">
                            <option value="">-- Tất cả NXB --</option>
                            <?php foreach ($publishers as $p): ?>
                                <option value="<?= $p['ma_nxb'] ?>" 
                                    <?= (!empty($search_nxb) && $search_nxb == $p['ma_nxb']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($p['ten_nxb']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Nút tìm -->
                    <div class="col-lg-2 col-md-12 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            Tìm kiếm
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <!-- FORM THÊM / SỬA -->
        <div class="bg-light rounded p-4 mb-4 shadow-sm">
            <form action="?page=book&action=save" method="post" class="row g-3">
                <input type="hidden" name="ma_sach" id="ma_sach">

                <div class="col-md-4">
                    <label class="form-label fw-bold">Tên sách*</label>
                    <input type="text" name="ten_sach" id="ten_sach" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Nhà xuất bản</label>
                    <select name="ma_nxb" id="ma_nxb" class="form-control">
                        <option value="">-- Không chọn --</option>
                        <?php foreach ($publishers as $n): ?>
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
                    <label class="form-label fw-bold">Tác giả</label>
                    <select name="ma_tac_gia" id="ma_tac_gia" class="form-control">
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
                    <button type="submit" name="action" value="add" class="btn btn-success flex-fill">Thêm mới</button>
                    <button type="submit" name="action" value="update" class="btn btn-warning text-white flex-fill">Cập nhật</button>
                    <button type="button" onclick="clearForm()" class="btn btn-secondary flex-fill">Làm mới</button>
                </div>
            </form>
        </div>

        <!-- PHÂN TRANG -->
        <div class="d-flex justify-content-between align-items-center mt-4" style="margin-bottom: 10px;">
            <div class="text-muted">
                Tổng: <strong><?= $total ?></strong> sách | Trang <?= $page_current ?> / <?= $total_pages_view ?>
            </div>
            <?php if ($total_pages_view > 1): ?>
                <ul class="pagination mb-0">
                    <li class="page-item <?= $page_current <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=book<?= $search_params ?>&p=<?= $page_current - 1 ?>">Trước</a>
                    </li>
                    <?php for ($i = max(1, $page_current - 2); $i <= min($total_pages_view, $page_current + 2); $i++): ?>
                        <li class="page-item <?= $i == $page_current ? 'active' : '' ?>">
                            <a class="page-link" href="?page=book<?= $search_params ?>&p=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?= $page_current >= $total_pages_view ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=book<?= $search_params ?>&p=<?= $page_current + 1 ?>">Sau</a>
                    </li>
                </ul>
            <?php endif; ?>
        </div>

        <!-- BẢNG DANH SÁCH -->
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
                            data-ten="<?= htmlspecialchars($b['ten_sach'] ?? '') ?>"
                            data-nxb="<?= $b['ma_nxb'] ?? '' ?>"
                            data-tacgia="<?= $b['ma_tac_gia_chinh'] ?? '' ?>"
                            data-giaban="<?= $b['gia_ban'] ?? '' ?>"
                            data-giagoc="<?= $b['gia_goc'] ?? '' ?>"
                            data-soluong="<?= $b['so_luong_ton'] ?? '' ?>"
                            data-sotrang="<?= $b['so_trang'] ?? '' ?>"
                            data-bia="<?= htmlspecialchars($b['hinh_thuc_bia'] ?? '') ?>"
                            data-ngonngu="<?= htmlspecialchars($b['ngon_ngu'] ?? '') ?>"
                            data-nam="<?= $b['nam_xuat_ban'] ?? '' ?>"
                            data-isbn="<?= htmlspecialchars($b['ma_isbn'] ?? '') ?>"
                            data-mota="<?= htmlspecialchars($b['mo_ta'] ?? '') ?>"
                            data-danhmuc='<?= json_encode($b['danh_muc_list'] ?? []) ?>'>
                            <td class="text-center fw-bold"><?= $b['ma_sach'] ?></td>
                            <td><?= htmlspecialchars($b['ten_sach']) ?></td>
                            <td><?= htmlspecialchars($b['ten_nxb'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($b['ten_tac_gia'] ?? '-') ?></td>
                            <td class="text-center"><?= number_format($b['gia_ban']) ?></td>
                            <td class="text-center"><?= $b['so_luong_ton'] ?></td>
                            <td class="text-center"><?= $b['nam_xuat_ban'] ?: '-' ?></td>
                            <td class="text-center" onclick="event.stopPropagation();">
                                <button class="btn btn-danger btn-sm" onclick="confirmDelete(<?= $b['ma_sach'] ?>)">Xóa</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </div>
</div>

<script>
    // Khi click vào dòng → điền dữ liệu vào form (TÁC GIẢ + DANH MỤC HIỆN ĐÚNG 100%)
    document.querySelectorAll(".row-select").forEach(row => {
        row.addEventListener("click", function () {
            const d = this.dataset;

            // Điền các field bình thường
            document.getElementById("ma_sach").value        = d.id;
            document.getElementById("ten_sach").value       = d.ten;
            document.getElementById("ma_nxb").value         = d.nxb || '';
            document.getElementById("gia_ban").value        = d.giaban || '';
            document.getElementById("gia_goc").value        = d.giagoc || '';
            document.getElementById("so_luong_ton").value   = d.soluong || '';
            document.getElementById("so_trang").value       = d.sotrang || '';
            document.getElementById("hinh_thuc_bia").value  = d.bia || '';
            document.getElementById("ngon_ngu").value       = d.ngonngu || '';
            document.getElementById("nam_xuat_ban").value   = d.nam || '';
            document.getElementById("ma_isbn").value        = d.isbn || '';
            document.getElementById("mo_ta").value          = d.mota || '';

            // TÁC GIẢ: chọn đúng option trong <select>
            const selectAuthor = document.getElementById("ma_tac_gia");
            if (selectAuthor && d.tacgia) {
                selectAuthor.value = d.tacgia;
            }

            // DANH MỤC: chọn nhiều (multiple)
            const selectCat = document.getElementById("danh_muc");
            if (selectCat && d.danhmuc) {
                const selectedCats = JSON.parse(d.danhmuc);
                selectCat.querySelectorAll("option").forEach(opt => {
                    opt.selected = selectedCats.includes(opt.value);
                });
            }
        });
    });

    // NÚT LÀM MỚI – HOẠT ĐỘNG HOÀN HẢO
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

    // XÓA SÁCH
    function confirmDelete(id) {
        Swal.fire({
            title: 'Xóa sách này?',
            text: "Không thể hoàn tác!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy'
        }).then(res => {
            if (res.isConfirmed) {
                window.location = '?page=book&action=delete&id=' + id;
            }
        });
    }
</script>