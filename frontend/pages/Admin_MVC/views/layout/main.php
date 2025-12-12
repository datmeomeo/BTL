<?php
// đảm bảo biến $page và $content tồn tại
$page = $page ?? ($_GET['page'] ?? 'dashboard');
$content = $content ?? '';
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Nhà sách Online</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="bootstrap-5.0.2-dist/css/bootstrap.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            min-height: 100vh;
            background: #f8f9fa;
        }

        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 220px;
            height: 100vh;
            background: linear-gradient(180deg, #d74747, #b71c1c);
            color: #fff;
            padding-top: 20px;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.2);
            z-index: 1000;
        }

        .sidebar h3 {
            text-align: center;
            font-size: 26px;
            margin-bottom: 30px;
            font-weight: bold;
        }

        .sidebar .nav-link {
            display: block;
            padding: 18px 20px;
            font-size: 17px;
            font-weight: bold;
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            margin: 5px 10px;
            transition: all 0.3s ease;
            text-align: center;
        }

        .sidebar .nav-link:hover {
            background-color: #e53935;
            transform: translateX(-5px);
        }

        .sidebar .nav-link.active {
            background-color: #e53935;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
            transform: translateX(-5px);
        }

        /* Xoá dấu chấm menu bootstrap */
        .sidebar .nav,
        .sidebar .nav-item {
            list-style: none !important;
            padding-left: 0;
        }

        .sidebar .nav-item::before,
        .sidebar .nav-link::before {
            content: none !important;
        }

        .main-content {
            margin-left: 220px;
            padding: 30px;
            min-height: 100vh;
        }

        .header-info {
            background: #fff;
            padding: 15px 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logout-btn {
            background: #dc3545;
            color: white;
            padding: 10px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: 0.3s;
        }

        .logout-btn:hover {
            background: #c82333;
            transform: scale(1.05);
        }
    </style>
</head>

<body>

    <!-- SIDEBAR -->
    <div class="sidebar">
        <h3>ADMIN PANEL</h3>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?= $page == 'dashboard' ? 'active' : '' ?>" href="?page=dashboard">Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $page == 'user' ? 'active' : '' ?>" href="?page=user">Người dùng</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $page == 'book' ? 'active' : '' ?>" href="?page=book">Sách</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $page == 'category' ? 'active' : '' ?>" href="?page=category">Danh mục</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $page == 'author' ? 'active' : '' ?>" href="?page=author">Tác giả</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $page == 'publisher' ? 'active' : '' ?>" href="?page=publisher">Nhà xuất bản</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $page == 'bookImage' ? 'active' : '' ?>" href="?page=bookImage">Hình ảnh sách</a>
            </li>
        </ul>
    </div>

    <!-- MAIN CONTENT -->
    <div class="main-content">
        
        <!-- Nếu đang ở dashboard thì hiển thị phần chào -->
        <?php if ($page === 'dashboard'): ?>
            <div class="header-info">
                <div>
                    <h4>Xin chào, <strong><?= htmlspecialchars($_SESSION['ten_dang_nhap'] ?? 'Admin') ?></strong>!</h4>
                    <small>Chào mừng quay lại khu vực quản trị</small>
                </div>
                <a href="../../index.php" class="logout-btn">Đăng xuất</a>
            </div>
        <?php endif; ?>

        <!-- HIỂN THỊ VIEW TỪ CONTROLLER -->
        <?= $content ?>

    </div>

    <!-- Bootstrap JS -->
    <script src="bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
