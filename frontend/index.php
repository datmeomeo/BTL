<?php
    $page = isset($_GET['page']) ? $_GET['page'] : 'home'; 
    $assets = [
        'login' => [
            'css' => ['./assets/css/login.css'],
            'js' => ['./pages/login/login.js']
        ],
        'home' => [
            'css' => ['./assets/css/suggest-book.css'],
            'js' => [
                './components/suggest-book/suggest-book.js', 
            ]
        ],
        'book' => [
            'css' => ['./assets/css/book-detail.css', './assets/css/suggest-book.css'],
            'js' => [
                './pages/book-detail/book-detail.js', 
                './components/suggest-book/suggest-book.js', 
            ],
        ],
        'cart' => [
            'css' => ['./assets/css/cart.css'],
            'js' => [
                './pages/cart/cart.js'
            ],
        ],
        'search_product'=> [
            'css' => ['./assets/css/search-product.css'],
            'js'=> [
                './pages/search-product/search-product.js',
                './components/suggest-book/suggest-book.js', 
            ],
        ]
        
    ];
    $currentPage = ($page === '') ? 'home' : $page;
    $currentAssets = $assets[$currentPage] ?? [];
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAHASA</title>
    <link rel="stylesheet" href="./assets/bootstrap-5.0.2-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./assets/css/main.css">
    <?php 
        if (isset($currentAssets['css'])): 
            $cssFiles = is_array($currentAssets['css']) ? $currentAssets['css'] : [$currentAssets['css']];
            foreach ($cssFiles as $cssFile):
    ?>
            <link rel="stylesheet" href="<?php echo $cssFile; ?>">
    <?php 
            endforeach;
        endif; 
    ?>
</head>
<body>
    <?php include './components/header/header.php'; ?>
    <?php 
        if ($page === '' || $page === 'home') {
            include './pages/main.php';
            include './components/suggest-book/suggest-book.php'; 
        } elseif ($page === 'book') {
            include './pages/book-detail/book-detail.php';
            include './components/suggest-book/suggest-book.php'; 
        } elseif ($page === 'login') {
            include './pages/login/login.php'; 
        } elseif ($page === 'cart') {
            include './pages/cart/cart.php';
        } elseif ($page == 'search_product'){
            include './pages/search-product/search-product.php';
        } 
        else {
            echo "<h1>Trang không tìm thấy</h1>";
        }
    ?>
    <?php include './components/footer/footer.php'; ?>

    <script src="./assets/bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js"></script>
    <script src="./components/header/header.js" defer></script>
    
    <?php 
        if (isset($currentAssets['js'])): 
            $jsFiles = is_array($currentAssets['js']) ? $currentAssets['js'] : [$currentAssets['js']];
            foreach ($jsFiles as $jsFile):
    ?>
            <script type="module" src="<?php echo $jsFile; ?>"></script>
    <?php 
            endforeach;
        endif; 
    ?>
</body>
</html>