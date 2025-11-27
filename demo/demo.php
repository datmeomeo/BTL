<?php
require_once '../connect/connect-database.php';

// Lấy tham số từ URL
$ma_danh_muc = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 30;
$offset = ($page - 1) * $limit;

// Lấy danh sách danh mục cha (cấp 1)
$sql_dm_cha = "SELECT * FROM DANH_MUC WHERE danh_muc_cha IS NULL ORDER BY ten_danh_muc";
$stmt_dm_cha = $conn->query($sql_dm_cha);
$danh_muc_cha_list = $stmt_dm_cha->fetchAll();

// Tạo cấu trúc danh mục phân cấp
$danh_muc_tree = [];
foreach ($danh_muc_cha_list as $dm_cha) {
    $sql_dm_con = "SELECT * FROM DANH_MUC WHERE danh_muc_cha = ? ORDER BY ten_danh_muc";
    $stmt_dm_con = $conn->prepare($sql_dm_con);
    $stmt_dm_con->execute([$dm_cha['ma_danh_muc']]);
    $dm_cha['children'] = $stmt_dm_con->fetchAll();
    $danh_muc_tree[] = $dm_cha;
}

// Lấy thông tin danh mục hiện tại
$current_category = null;
$breadcrumb_path = [];
if ($ma_danh_muc > 0) {
    $sql_current = "SELECT * FROM DANH_MUC WHERE ma_danh_muc = ?";
    $stmt_current = $conn->prepare($sql_current);
    $stmt_current->execute([$ma_danh_muc]);
    $current_category = $stmt_current->fetch();
    
    // Build breadcrumb
    if ($current_category) {
        $breadcrumb_path[] = $current_category;
        if ($current_category['danh_muc_cha']) {
            $sql_parent = "SELECT * FROM DANH_MUC WHERE ma_danh_muc = ?";
            $stmt_parent = $conn->prepare($sql_parent);
            $stmt_parent->execute([$current_category['danh_muc_cha']]);
            $parent = $stmt_parent->fetch();
            if ($parent) {
                array_unshift($breadcrumb_path, $parent);
            }
        }
    }
}

// Đếm tổng số sách
if ($ma_danh_muc > 0) {
    $sql_count = "SELECT COUNT(DISTINCT s.ma_sach) as total
                  FROM SACH s
                  INNER JOIN SACH_DANH_MUC sdm ON s.ma_sach = sdm.ma_sach
                  INNER JOIN DANH_MUC dm ON sdm.ma_danh_muc = dm.ma_danh_muc
                  WHERE s.trang_thai = 'available' 
                  AND (dm.ma_danh_muc = ? OR dm.danh_muc_cha = ?)";
    $stmt_count = $conn->prepare($sql_count);
    $stmt_count->execute([$ma_danh_muc, $ma_danh_muc]);
} else {
    $sql_count = "SELECT COUNT(*) as total FROM SACH WHERE trang_thai = 'available'";
    $stmt_count = $conn->query($sql_count);
}
$total_books = $stmt_count->fetch()['total'];
$total_pages = ceil($total_books / $limit);

// Lấy danh sách sách
if ($ma_danh_muc > 0) {
    $sql_books = "SELECT DISTINCT s.*, 
                         h.duong_dan_hinh,
                         nxb.ten_nxb,
                         ROUND(((s.gia_goc - s.gia_ban) / s.gia_goc * 100), 0) as phan_tram_giam
                  FROM SACH s
                  LEFT JOIN HINH_ANH_SACH h ON s.ma_sach = h.ma_sach AND h.la_anh_chinh = TRUE
                  LEFT JOIN NHA_XUAT_BAN nxb ON s.ma_nxb = nxb.ma_nxb
                  INNER JOIN SACH_DANH_MUC sdm ON s.ma_sach = sdm.ma_sach
                  INNER JOIN DANH_MUC dm ON sdm.ma_danh_muc = dm.ma_danh_muc
                  WHERE s.trang_thai = 'available' 
                  AND (dm.ma_danh_muc = ? OR dm.danh_muc_cha = ?)
                  ORDER BY s.ngay_them DESC
                  LIMIT $limit OFFSET $offset";
    $stmt_books = $conn->prepare($sql_books);
    $stmt_books->execute([$ma_danh_muc, $ma_danh_muc, $limit, $offset]);
} else {
    $sql_books = "SELECT s.*, 
                         h.duong_dan_hinh,
                         nxb.ten_nxb,
                         ROUND(((s.gia_goc - s.gia_ban) / s.gia_goc * 100), 0) as phan_tram_giam
                  FROM SACH s
                  LEFT JOIN HINH_ANH_SACH h ON s.ma_sach = h.ma_sach AND h.la_anh_chinh = TRUE
                  LEFT JOIN NHA_XUAT_BAN nxb ON s.ma_nxb = nxb.ma_nxb
                  WHERE s.trang_thai = 'available'
                  ORDER BY s.ngay_them DESC
                  LIMIT $limit OFFSET $offset";
    $stmt_books = $conn->prepare($sql_books);
    $stmt_books->execute();
}
$books = $stmt_books->fetchAll();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $current_category ? htmlspecialchars($current_category['ten_danh_muc']) : 'Sách Tiếng Việt'; ?> - FAHASA</title>
    <link rel="stylesheet" href="../bootstrap-5.0.2-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/danhmuc.css">
    <link rel="stylesheet" href="../css/demo.css">
    <script src="../bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js"></script>
    <script src="../help/tool-menu.js" defer></script>
</head>
<body>
    <?php include '../com/header.php'; ?>
    
    <main class="category-main">
        <div class="container-fluid">
            <!-- Breadcrumb -->
            <div class="breadcrumb-nav">
                <a href="../giaodien/trangchu.php">HOME</a>
                <span>›</span>
                <?php if (empty($breadcrumb_path)): ?>
                    <span>ALL CATEGORY</span>
                    <span>›</span>
                    <span class="active">SÁCH TIẾNG VIỆT</span>
                <?php else: ?>
                    <a href="danhmuc.php">ALL CATEGORY</a>
                    <span>›</span>
                    <?php foreach ($breadcrumb_path as $idx => $bc): ?>
                        <?php if ($idx < count($breadcrumb_path) - 1): ?>
                            <a href="danhmuc.php?id=<?php echo $bc['ma_danh_muc']; ?>">
                                <?php echo strtoupper(htmlspecialchars($bc['ten_danh_muc'])); ?>
                            </a>
                            <span>›</span>
                        <?php else: ?>
                            <span class="active"><?php echo strtoupper(htmlspecialchars($bc['ten_danh_muc'])); ?></span>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <div class="category-content">
                <!-- SIDEBAR BÊN TRÁI -->
                <aside class="category-sidebar">
                    <div class="sidebar-section">
                        <h3 class="sidebar-title">NHÓM SẢN PHẨM</h3>
                        <ul class="category-list">
                            <li>
                                <a href="danhmuc.php" class="<?php echo $ma_danh_muc == 0 ? 'active' : ''; ?>">
                                    All Category
                                </a>
                            </li>
                            <li>
                                <a href="danhmuc.php" class="category-parent <?php echo $ma_danh_muc == 0 ? 'active' : ''; ?>">
                                    Sách Tiếng Việt
                                </a>
                                <ul class="category-children">
                                    <?php foreach ($danh_muc_tree as $dm_cha): ?>
                                        <li>
                                            <a href="danhmuc.php?id=<?php echo $dm_cha['ma_danh_muc']; ?>" 
                                               class="<?php echo $ma_danh_muc == $dm_cha['ma_danh_muc'] ? 'active' : ''; ?>">
                                                <?php echo htmlspecialchars($dm_cha['ten_danh_muc']); ?>
                                            </a>
                                            <?php if (!empty($dm_cha['children'])): ?>
                                                <ul class="category-sub">
                                                    <?php foreach ($dm_cha['children'] as $dm_con): ?>
                                                        <li>
                                                            <a href="danhmuc.php?id=<?php echo $dm_con['ma_danh_muc']; ?>"
                                                               class="<?php echo $ma_danh_muc == $dm_con['ma_danh_muc'] ? 'active' : ''; ?>">
                                                                <?php echo htmlspecialchars($dm_con['ten_danh_muc']); ?>
                                                            </a>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            <?php endif; ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    
                    <!-- Phần lọc giá -->
                    <div class="sidebar-section">
                        <h3 class="sidebar-title">GIÁ</h3>
                        <ul class="filter-list">
                            <li><label><input type="checkbox"> 0đ - 150,000đ</label></li>
                            <li><label><input type="checkbox"> 150,000đ - 300,000đ</label></li>
                            <li><label><input type="checkbox"> 300,000đ - 500,000đ</label></li>
                            <li><label><input type="checkbox"> 500,000đ - 700,000đ</label></li>
                            <li><label><input type="checkbox"> 700,000đ - Trở Lên</label></li>
                        </ul>
                    </div>
                </aside>
                
                <!-- NỘI DUNG BÊN PHẢI -->
                <div class="category-products">
                    <!-- Sort Bar -->
                    <div class="sort-bar">
                        <div class="sort-label">Sắp xếp theo:</div>
                        <select class="sort-select">
                            <option value="newest">Mới nhất</option>
                            <option value="bestseller">Bán chạy</option>
                            <option value="price-asc">Giá tăng dần</option>
                            <option value="price-desc">Giá giảm dần</option>
                        </select>
                        
                        <div class="result-count">
                            <?php echo $total_books; ?> sản phẩm
                        </div>
                    </div>
                    
                    <!-- Products Grid -->
                    <div class="products-grid">
                        <?php foreach ($books as $book): ?>
                            <a href="../giaodien/sach.php?id=<?php echo $book['ma_sach']; ?>" class="product-card">
                                <!-- Badge -->
                                <?php if (!empty($book['phan_tram_giam'])): ?>
                                    <div class="discount-badge">-<?php echo $book['phan_tram_giam']; ?>%</div>
                                <?php endif; ?>
                                
                                <?php
                                $ngay_them = strtotime($book['ngay_them']);
                                $so_ngay = (time() - $ngay_them) / (60 * 60 * 24);
                                if ($so_ngay <= 30):
                                ?>
                                    <div class="new-badge">Mới</div>
                                <?php endif; ?>
                                
                                <!-- Image -->
                                <div class="product-image">
                                    <img src="<?php echo $book['duong_dan_hinh'] ?? '../img/no-image.jpg'; ?>" 
                                         alt="<?php echo htmlspecialchars($book['ten_sach']); ?>">
                                </div>
                                
                                <!-- Info -->
                                <div class="product-info">
                                    <div class="product-title">
                                        <?php echo htmlspecialchars($book['ten_sach']); ?>
                                    </div>
                                    
                                    <div class="product-price">
                                        <span class="current-price">
                                            <?php echo number_format($book['gia_ban'], 0, ',', '.'); ?>đ
                                        </span>
                                        <?php if ($book['gia_goc'] > $book['gia_ban']): ?>
                                            <span class="old-price">
                                                <?php echo number_format($book['gia_goc'], 0, ',', '.'); ?>đ
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="product-rating">
                                        <span class="stars">★★★★★</span>
                                        <span class="rating-count">(<?php echo rand(10, 500); ?>)</span>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- Pagination -->
                    <?php if ($total_pages > 1): ?>
                        <div class="pagination">
                            <?php if ($page > 1): ?>
                                <a href="?id=<?php echo $ma_danh_muc; ?>&page=<?php echo $page - 1; ?>" class="page-link">‹ Trước</a>
                            <?php endif; ?>
                            
                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <?php if ($i == $page): ?>
                                    <span class="page-link active"><?php echo $i; ?></span>
                                <?php else: ?>
                                    <a href="?id=<?php echo $ma_danh_muc; ?>&page=<?php echo $i; ?>" class="page-link"><?php echo $i; ?></a>
                                <?php endif; ?>
                            <?php endfor; ?>
                            
                            <?php if ($page < $total_pages): ?>
                                <a href="?id=<?php echo $ma_danh_muc; ?>&page=<?php echo $page + 1; ?>" class="page-link">Sau ›</a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
    
    <?php include '../com/footer.php'; ?>
</body>
</html>