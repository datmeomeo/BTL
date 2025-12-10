<?php
// Kiểm tra trạng thái đăng nhập
$isLoggedIn = isset($_SESSION['user_id']);
$userName = $isLoggedIn ? $_SESSION['user_name'] : 'Tài khoản';
// Lấy role từ Session (AuthService đã lưu khi login)
$userRole = $isLoggedIn && isset($_SESSION['user_role']) ? $_SESSION['user_role'] : '';
?>

<style>
    .hidden-box{
        position: absolute;
        top: 65px;    /* nằm dưới icon */
        left: 70%;
        width: 400px;
        height: 600px;
        padding: 10px;
        background: white;
        border: 1px solid #ccc;
        border-radius: 6px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.2);
        display: none; /* Ẩn mặc định */
        z-index: 999;
        text-align: center;
    }
    .link-notify{
        text-decoration: none;
        padding-left: 10px;
        text-align: center;
        transition: 0.3s;
        display: flex;
        flex-direction: column;
        align-items: center;
        color: #333;
        position: relative;
        font-size: 24px;
        cursor: pointer;    
    }
    .link-notify:hover{
        color: var(--primary-red);
    }
    .link-notify:hover svg{
        stroke: var(--primary-red);
    }
</style>

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
                <div class="mega-menu" id="megaMenu"></div>
            </div>

            <div class="col search-wrapper">
                <form action="index.php" method="GET" class="search-box d-flex">
                    <input type="hidden" name="page" value="search_product">
                    <input type="text" name="keyword" class="form-control" placeholder="Tìm kiếm sách...">
                    <button class="btn btn-search" type="submit">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.3-4.3"></path>
                        </svg>
                    </button>
                </form>
            </div>

            <div class="col-auto">
                <div class="d-flex gap-3">
                    <a href="#" class="link-notify" id="notifyIcon">
                        <span class="icon-symbol">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"></path>
                                <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"></path>
                            </svg>
                        </span>
                        <span class="icon-text">Thông Báo</span>
                    </a>

                    <div id="notifyBox" class="hidden-box">
                        Không có thông báo nào
                    </div>

                    <a href="index.php?page=cart" class="header-icon">
                        <span class="icon-symbol">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round">
                                <circle cx="8" cy="21" r="1"></circle>
                                <circle cx="19" cy="21" r="1"></circle>
                                <path
                                    d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12">
                                </path>
                            </svg>
                        </span>
                        <span class="icon-text">Giỏ Hàng</span>
                    </a>

                    <div class="header-icon-box" id="header-account" style="position: relative; cursor: pointer;">
                        <a href="<?php echo $isLoggedIn ? 'javascript:void(0)' : 'index.php?page=login'; ?>"
                            class="header-icon" id="header-account-link"
                            style="text-decoration: none; color: inherit; display: flex; flex-direction: column; align-items: center;">
                            <span class="icon-symbol">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <circle cx="12" cy="8" r="5"></circle>
                                    <path d="M20 21a8 8 0 0 0-16 0"></path>
                                </svg>
                            </span>
                            <span class="icon-text"
                                id="header-username"><?php echo htmlspecialchars($userName); ?></span>
                        </a>

                        <div id="account-dropdown"
                            style="display: none; position: absolute; top: 100%; right: 0; background: white; box-shadow: 0 4px 15px rgba(0,0,0,0.15); border-radius: 8px; min-width: 240px; padding: 15px; z-index: 1000; border: 1px solid #eee;">

                            <div style="border-bottom: 1px solid #eee; padding-bottom: 10px; margin-bottom: 10px;">
                                <div style="font-size: 13px; color: #777; margin-bottom: 4px;">Xin chào,</div>
                                <div id="user-display-name" style="font-weight: bold; font-size: 16px; color: #333;">
                                    <?php echo htmlspecialchars($userName); ?>
                                </div>
                            </div>

                            <button id="btn-go-admin"
                                style="display: none; width: 100%; background: #e3f2fd; color: #1565c0; border: none; padding: 10px; border-radius: 6px; font-weight: 600; cursor: pointer; align-items: center; justify-content: center; gap: 8px; transition: 0.2s; margin-bottom: 8px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <rect x="3" y="3" width="7" height="7"></rect>
                                    <rect x="14" y="3" width="7" height="7"></rect>
                                    <rect x="14" y="14" width="7" height="7"></rect>
                                    <rect x="3" y="14" width="7" height="7"></rect>
                                </svg>
                                Trang quản trị
                            </button>
                            <button id="btn-header-logout"
                                style="width: 100%; background: #ffebee; color: #c62828; border: none; padding: 10px; border-radius: 6px; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; transition: 0.2s;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                    <polyline points="16 17 21 12 16 7"></polyline>
                                    <line x1="21" y1="12" x2="9" y2="12"></line>
                                </svg>
                                Đăng xuất
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
    const icon = document.getElementById("notifyIcon");
    const box = document.getElementById("notifyBox");

    icon.addEventListener("click", function (e) {
        e.preventDefault();   // để thẻ <a> không nhảy trang
        box.style.display = box.style.display === "block" ? "none" : "block";
    });

    // Click ra ngoài để tắt hộp
    document.addEventListener("click", function (e) {
        if (!icon.contains(e.target) && !box.contains(e.target)) {
            box.style.display = "none";
        }
    });
</script>