<header class="header">
    <div class="container-fluid py-2 px-3">
        <div class="row align-items-center g-3">
            <div class="col-auto">
                <a href="index.php" class="logo">
                    <img src="./assets/img/fahasa-logo.jpg" alt="Fahasa-logo">
                </a>
            </div>
            
            <div class="col-auto mega-menu-wrapper">
                <button class="menu-icon-btn" id="menuToggle">
                    <div class="grid-icon">
                        <div class="grid-dot"></div>
                        <div class="grid-dot"></div>
                        <div class="grid-dot"></div>
                        <div class="grid-dot"></div>
                    </div>
                </button>
                
                <div class="mega-menu" id="megaMenu">
                    <div class="d-flex w-100 justify-content-center align-items-center py-5">
                        <div class="spinner-border text-danger" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col search-wrapper">
                <form action="index.php" method="GET" class="search-box d-flex">
                    <input type="hidden" name="page" value="search_product">
                    <input type="text" name="keyword" class="form-control" placeholder="Tìm kiếm sách...">
                    <button class="btn btn-search" type="submit">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.3-4.3"></path></svg>
                    </button>
                </form>
            </div>

            <div class="col-auto">
                <div class="d-flex gap-3">
                    <a href="#" class="header-icon">
                        <span class="icon-symbol">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"></path><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"></path></svg>
                        </span>
                        <span class="icon-text">Thông Báo</span>
                    </a>
                    <a href="index.php?page=cart" class="header-icon">
                        <span class="icon-symbol">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="8" cy="21" r="1"></circle><circle cx="19" cy="21" r="1"></circle><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"></path></svg>
                        </span>
                        <span class="icon-text">Giỏ Hàng</span>
                    </a>
                    <a href="<?php echo isset($_SESSION['user']) ? '#' : 'index.php?page=login'; ?>" class="header-icon">
                        <span class="icon-symbol">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="5"></circle><path d="M20 21a8 8 0 0 0-16 0"></path></svg>
                        </span>
                        <span class="icon-text"><?php echo isset($_SESSION['user']) ? $_SESSION['user']['ho_ten'] : 'Tài khoản'; ?></span>
                    </a>
                    <?php if (isset($_SESSION['user'])): ?>
                        <a href="index.php?action=logout" class="header-icon text-danger">
                            <span class="icon-text ms-0">Đăng xuất</span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</header>