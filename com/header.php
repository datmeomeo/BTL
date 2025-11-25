<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
        <header class="header">
        <div class="container-fluid py-2 px-3">
            <div class="row align-items-center g-3">
                <!-- Logo -->
                <div class="col-auto">
                    <a href="../giaodien/trangchu.php" class="logo">
                        <img src="../img/fahasa-logo.jpg" alt="Fahasa-logo">
                    </a>
                </div>
                <!-- Menu Icon with Mega Menu -->
                <div class="col-auto mega-menu-wrapper">
                    <button class="menu-icon-btn" id="menuToggle">
                        <div class="grid-icon">
                            <div class="grid-dot"></div>
                            <div class="grid-dot"></div>
                            <div class="grid-dot"></div>
                            <div class="grid-dot"></div>
                        </div>
                    </button>
                    <!-- Mega Menu -->
                    <div class="mega-menu" id="megaMenu">
                        <div class="d-flex w-100">
                            <!-- Sidebar -->
                            <div class="mega-sidebar">
                                <div class="mega-sidebar-title">Danh mục sản phẩm</div>
                                <div class="mega-menu-item active" data-menu="sach-trong-nuoc">Sách Trong Nước</div>
                                <div class="mega-menu-item" data-menu="foreign-books">FOREIGN BOOKS</div>
                                <div class="mega-menu-item" data-menu="sach-giao-khoa">SGK 2025</div>
                            </div>
                            <!-- Content: Sách Trong Nước -->
                            <div class="mega-content active" id="sach-trong-nuoc">
                                <div class="mega-header">
                                    <div class="mega-icon"></div>
                                    <h2 class="mega-title">Sách Trong Nước</h2>
                                </div>
                                <div class="categories-grid">
                                    <div class="category-section">
                                        <h3 class="category-title">VĂN HỌC</h3>
                                        <ul class="category-list">
                                            <li><a href="#">Tiểu Thuyết</a></li>
                                            <li><a href="#">Truyện Ngắn - Tản Văn</a></li>
                                            <li><a href="#">Light Novel</a></li>
                                            <li><a href="#">Ngôn Tình</a></li>
                                        </ul>
                                        <a href="#" class="view-all">Xem tất cả</a>
                                    </div>

                                    <div class="category-section">
                                        <h3 class="category-title">KINH TẾ</h3>
                                        <ul class="category-list">
                                            <li><a href="#">Nhân Vật - Bài Học KD</a></li>
                                            <li><a href="#">Quản Trị - Lãnh Đạo</a></li>
                                            <li><a href="#">Marketing - Bán Hàng</a></li>
                                            <li><a href="#">Phân Tích Kinh Tế</a></li>
                                        </ul>
                                        <a href="#" class="view-all">Xem tất cả</a>
                                    </div>

                                    <div class="category-section">
                                        <h3 class="category-title">TÂM LÝ - KỸ NĂNG</h3>
                                        <ul class="category-list">
                                            <li><a href="#">Kỹ Năng Sống</a></li>
                                            <li><a href="#">Rèn Luyện Nhân Cách</a></li>
                                            <li><a href="#">Tâm Lý</a></li>
                                            <li><a href="#">Sách Tuổi Mới Lớn</a></li>
                                        </ul>
                                        <a href="#" class="view-all">Xem tất cả</a>
                                    </div>

                                    <div class="category-section">
                                        <h3 class="category-title">NUÔI DẠY CON</h3>
                                        <ul class="category-list">
                                            <li><a href="#">Cẩm Nang Làm Cha Mẹ</a></li>
                                            <li><a href="#">Phương Pháp Giáo Dục</a></li>
                                            <li><a href="#">Phát Triển Trí Tuệ</a></li>
                                            <li><a href="#">Phát Triển Kỹ Năng</a></li>
                                        </ul>
                                        <a href="#" class="view-all">Xem tất cả</a>
                                    </div>

                                    <div class="category-section">
                                        <h3 class="category-title">SÁCH THIẾU NHI</h3>
                                        <ul class="category-list">
                                            <li><a href="#">Manga - Comic</a></li>
                                            <li><a href="#">Kiến Thức Bách Khoa</a></li>
                                            <li><a href="#">Sách Tranh Kỹ Năng</a></li>
                                            <li><a href="#">Vừa Học Vừa Chơi</a></li>
                                        </ul>
                                        <a href="#" class="view-all">Xem tất cả</a>
                                    </div>

                                    <div class="category-section">
                                        <h3 class="category-title">TIỂU SỬ - HỒI KÝ</h3>
                                        <ul class="category-list">
                                            <li><a href="#">Câu Chuyện Cuộc Đời</a></li>
                                            <li><a href="#">Chính Trị</a></li>
                                            <li><a href="#">Kinh Tế</a></li>
                                            <li><a href="#">Nghệ Thuật - Giải Trí</a></li>
                                        </ul>
                                        <a href="#" class="view-all">Xem tất cả</a>
                                    </div>

                                    <div class="category-section">
                                        <h3 class="category-title">GIÁO KHOA - THAM KHẢO</h3>
                                        <ul class="category-list">
                                            <li><a href="#">Sách Giáo Khoa</a></li>
                                            <li><a href="#">Sách Tham Khảo</a></li>
                                            <li><a href="#">Luyện Thi THPT QG</a></li>
                                            <li><a href="#">Mẫu Giáo</a></li>
                                        </ul>
                                        <a href="#" class="view-all">Xem tất cả</a>
                                    </div>

                                    <div class="category-section">
                                        <h3 class="category-title">HỌC NGOẠI NGỮ</h3>
                                        <ul class="category-list">
                                            <li><a href="#">Tiếng Anh</a></li>
                                            <li><a href="#">Tiếng Nhật</a></li>
                                            <li><a href="#">Tiếng Hoa</a></li>
                                            <li><a href="#">Tiếng Hàn</a></li>
                                        </ul>
                                        <a href="#" class="view-all">Xem tất cả</a>
                                    </div>

                                    <div class="category-section">
                                        <ul class="category-list">
                                            <li><a href="#" class="special-item">SÁCH MỚI <span class="heart">♥</span></a></li>
                                            <li><a href="#" class="special-item">SÁCH BÁN CHẠY <span class="heart">♥</span></a></li>
                                            <li><a href="#" class="special-item">MANGA MỚI <span class="heart">♥</span></a></li>
                                            <li><a href="#" class="special-item">LIGHT NOVEL <span class="heart">♥</span></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Content: Foreign Books -->
                            <div class="mega-content" id="foreign-books">
                                <div class="mega-header">
                                    <div class="mega-icon"></div>
                                    <h2 class="mega-title">FOREIGN BOOKS</h2>
                                </div>
                                
                                <div class="categories-grid">
                                    <div class="category-section">
                                        <h3 class="category-title">FICTION</h3>
                                        <ul class="category-list">
                                            <li><a href="#">Contemporary Fiction</a></li>
                                            <li><a href="#">Fantasy</a></li>
                                            <li><a href="#">Science Fiction</a></li>
                                            <li><a href="#">Mystery & Thriller</a></li>
                                        </ul>
                                        <a href="#" class="view-all">Xem tất cả</a>
                                    </div>

                                    <div class="category-section">
                                        <h3 class="category-title">NON-FICTION</h3>
                                        <ul class="category-list">
                                            <li><a href="#">Biography</a></li>
                                            <li><a href="#">Business & Economics</a></li>
                                            <li><a href="#">Self-Help</a></li>
                                            <li><a href="#">History</a></li>
                                        </ul>
                                        <a href="#" class="view-all">Xem tất cả</a>
                                    </div>

                                    <div class="category-section">
                                        <h3 class="category-title">CHILDREN'S BOOKS</h3>
                                        <ul class="category-list">
                                            <li><a href="#">Picture Books</a></li>
                                            <li><a href="#">Early Readers</a></li>
                                            <li><a href="#">Middle Grade</a></li>
                                            <li><a href="#">Young Adult</a></li>
                                        </ul>
                                        <a href="#" class="view-all">Xem tất cả</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Search Bar -->
                <div class="col search-wrapper">
                    <div class="search-box d-flex">
                        <input type="text" class="form-control">
                        <button class="btn btn-search">🔍</button>
                    </div>
                </div>

                <!-- Header Icons -->
                <div class="col-auto">
                    <div class="d-flex gap-3">
                        <a href="#" class="header-icon">
                            <span class="icon-symbol">🔔</span>
                            <span class="icon-text">Thông Báo</span>
                        </a>
                        <a href="#" class="header-icon">
                            <span class="icon-symbol">🛒</span>
                            <span class="icon-text">Giỏ Hàng</span>
                        </a>
                        <a href="../giaodien/login.php" class="header-icon">
                            <span class="icon-symbol">👤</span>
                            <span class="icon-text">Tài khoản</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>
</body>
</html>