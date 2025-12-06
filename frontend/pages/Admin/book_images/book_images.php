<?php
require_once '../them/connect.php';

// Lấy danh sách sách
$books = $conn->query("SELECT ma_sach, ten_sach FROM sach ORDER BY ten_sach")->fetchAll(PDO::FETCH_ASSOC);

// Lấy sách đang chọn
$selected_id = $_GET['id'] ?? '';
if ($selected_id) {
    $stmt = $conn->prepare("SELECT * FROM hinh_anh_sach WHERE ma_sach=?");
    $stmt->execute([$selected_id]);
    $images = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $images = [];
}
?>

<link rel="stylesheet" href="../bootstrap-5.0.2-dist/css/bootstrap.min.css">
<script src="../bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container mt-4">

    <h3 class="mb-3">Quản lý hình ảnh sách</h3>

    <!-- Chọn sách -->
    <form method="get" action="../GiaoDien/index.php" class="mb-3">
        <input type="hidden" name="page" value="book_images">
        <select name="id" class="form-select w-50 d-inline-block">
            <option value="">-- Chọn sách --</option>
            <?php foreach ($books as $b): ?>
                <option value="<?= $b['ma_sach'] ?>" <?= $b['ma_sach'] == $selected_id ? 'selected' : '' ?>>
                    <?= htmlspecialchars($b['ten_sach']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button class="btn btn-primary">Chọn</button>
    </form>

    <?php if ($selected_id): ?>
        <!-- Upload ảnh -->
        <form action="../book/book_images_handle.php" method="post" enctype="multipart/form-data" class="mb-4">
            <input type="hidden" name="ma_sach" value="<?= $selected_id ?>">
            <input type="file" name="images[]" multiple required>
            <button type="submit" name="action" value="upload" class="btn btn-success">Upload ảnh</button>
        </form>

        <!-- Danh sách ảnh -->
        <div class="row">
            <?php foreach ($images as $img): ?>
                <div class="col-md-3 mb-3 text-center">
                    <img src="../uploads/<?= htmlspecialchars($img['file_name']) ?>" class="img-fluid rounded border mb-1">
                    <?php if ($img['is_cover']): ?>
                        <div class="badge bg-success">Bìa chính</div>
                    <?php else: ?>
                        <button type="button" class="btn btn-sm btn-outline-primary w-100 mb-1" 
                            onclick="setCover(<?= $img['id'] ?>)">Đặt bìa chính</button>
                    <?php endif; ?>
                    <button type="button" class="btn btn-sm btn-danger w-100" onclick="deleteImage(<?= $img['id'] ?>)">Xóa</button>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>

<script>
function deleteImage(id) {
    Swal.fire({
        title: 'Xóa ảnh này?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Có, xóa!',
        cancelButtonText: 'Hủy'
    }).then((result) => {
        if(result.isConfirmed){
            window.location.href = `../book/book_images_handle.php?action=delete&id=${id}&ma_sach=<?= $selected_id ?>`;
        }
    });
}

function setCover(id) {
    window.location.href = `../book/book_images_handle.php?action=set_cover&id=${id}&ma_sach=<?= $selected_id ?>`;
}
</script>
