<?php
    $page = isset($_GET['page']) ? $_GET['page'] : 'home'; 
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAHASA</title>
    <link rel="stylesheet" href="./assets/bootstrap-5.0.2-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./assets/css/main.css">
    <?php if ($page === 'login'): ?>
        <link rel="stylesheet" href="./assets/css/login.css">
    <?php elseif ($page === '' || $page === 'home'): ?>
        <link rel="stylesheet" href="./assets/css/goi-y.css">
    <?php elseif ($page === 'books'): ?>
        <link rel="stylesheet" href="./assets/css/book-detail.css">
    <?php elseif ($page === 'cart'): ?>
        <link rel="stylesheet" href="./assets/css/cart.css">
    <?php endif; ?>
</head>
<body>
    <?php include './components/header.php'; ?>
    
    <?php 
        if ($page === '' || $page === 'home') {
            // Trang chủ (index.php hoặc index.php?page=home)
            include './pages/main.php';
            include './components/suggest-book.php'; 
            
        } elseif ($page === 'login') {
            include './components/login.php'; 
        } elseif ($page === 'book') {
            include './pages/book.php';
            include './components/suggest-book.php'; 
        } elseif ($page === 'cart') {
            include './pages/cart.php';
        } else {
            echo "<h1>Trang không tìm thấy</h1>";
        }
    ?>
    
    <?php include './components/footer.php'; ?>


    <script src="./assets/bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js"></script>
    <script src="./help/tool-menu.js" defer></script>

    <?php if ($page === '' || $page === 'home'): ?>
        <script src="./help/tool-banner.js"></script>
    <?php elseif ($page === 'login'): ?>
        <script src="./help/tool-login.js"></script>
    <?php elseif ($page === 'books'): ?>
        <script src="./help/tool-detail.js"></script>
    <?php elseif ($page === 'cart'): ?>
        <script src="./help/tool-cart.js" defer></script>
    <?php endif; ?>
</body>
</html>