<div class="container-fluid">
    <h2 class="mb-4">Quản lý hình ảnh sách</h2>

    <div class="card p-4 mb-4 bg-white shadow-sm">
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Chọn sách:</label>
                    <select name="ma_sach" class="form-select" onchange="window.location.href='?page=bookImage&ma_sach='+this.value">
                        <?php 
                        $currentFolder = "";
                        foreach ($books as $book): 
                            $selected = ($book['ma_sach'] == $selectedBookId) ? 'selected' : '';
                            if ($selected) $currentFolder = $book['folder_name'];
                        ?>
                            <option value="<?= $book['ma_sach'] ?>" <?= $selected ?>>
                                <?= $book['ten_sach'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Chọn thư mục lưu ảnh:</label>
                            <select name="folder_name" class="form-select">
                                <option value="">-- Chọn thư mục --</option>
                                <?php foreach ($folders as $folder): ?>
                                    <option value="<?= $folder ?>">
                                        <?= $folder ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="form-text text-muted">Các folder có sẵn trong assets/img-book</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Chọn file ảnh:</label>
                    <input type="file" name="file_anh" class="form-control" required>
                </div>
                <div class="col-md-6 d-flex align-items-end">
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="la_anh_chinh" id="mainImg" value="1">
                        <label class="form-check-label" for="mainImg">Đặt làm ảnh chính</label>
                    </div>
                </div>
            </div>

            <button type="submit" name="btn_upload" class="btn btn-primary">
                <i class="bi bi-cloud-upload"></i> Tải lên
            </button>
        </form>
    </div>

    <div class="card p-3 shadow-sm bg-white">
        <h5>Danh sách ảnh của sách đang chọn</h5>
        <table class="table table-bordered table-hover mt-3 align-middle">
            <thead class="table-dark">
                <tr>
                    <th style="width: 120px;">Ảnh</th>
                    <th>Đường dẫn</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($currentImages) > 0): ?>
                    <?php foreach ($currentImages as $img): ?>
                    <tr>
                        <td class="text-center">
                            <img src="../../assets/img-book<?= $img['duong_dan_hinh'] ?>" 
                                 style="width: 80px; height: 100px; object-fit: cover; border-radius: 4px;">
                        </td>
                        <td><?= $img['duong_dan_hinh'] ?></td>
                        <td class="text-center">
                            <?php if ($img['la_anh_chinh'] == 1): ?>
                                <span class="badge bg-success">Ảnh Chính</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Ảnh Phụ</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="?page=bookImage&action=delete&id=<?= $img['ma_hinh_anh'] ?>&ma_sach=<?= $selectedBookId ?>" 
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Bạn có chắc chắn muốn xóa?')">
                               <i class="bi bi-trash"></i> Xóa
                            </a>
                            
                            <?php if ($img['la_anh_chinh'] == 0): ?>
                                <a href="?page=bookImage&action=set_main&id=<?= $img['ma_hinh_anh'] ?>&ma_sach=<?= $selectedBookId ?>" 
                                   class="btn btn-warning btn-sm">
                                   <i class="bi bi-star"></i> Chọn làm chính
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="4" class="text-center py-4">Chưa có ảnh nào.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>