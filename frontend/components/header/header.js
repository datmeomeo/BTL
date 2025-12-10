// frontend/components/header/header.js

document.addEventListener("DOMContentLoaded", () => {
    loadMegaMenu();
    setupMenuToggle(); // Xử lý nút bật/tắt menu mobile (nếu có)
    setupAccountDropdown();
    notification(); //Xử lý ẩn hiện thông báo
});

function notification(){
    const link = document.getElementById("linkNotify");
    const box = document.getElementById("header-icon-nofity");

    link.addEventListener("click", (e) => {
        e.preventDefault();          // chặn <a> nhảy trang
        box.classList.toggle("hidden-box");  // hiện/ẩn hộp
    });
}

function setupAccountDropdown() {
    const accountWrapper = document.getElementById('header-account');
    const accountLink = document.getElementById('header-account-link');
    const dropdown = document.getElementById('account-dropdown');
    const nameDisplay = document.getElementById('user-display-name');
    const headerName = document.getElementById('header-username');
    const logoutBtn = document.getElementById('btn-header-logout');
    const adminBtn = document.getElementById('btn-go-admin'); 

    // 1. Kiểm tra trạng thái đăng nhập từ LocalStorage
    const storedUser = localStorage.getItem('user_info');
    let user = null;

    if (storedUser) {
        try {
            user = JSON.parse(storedUser);
        } catch (e) {
            console.error("Lỗi parse user_info", e);
            localStorage.removeItem('user_info');
        }
    }

    // 2. Xử lý Logic Hiển thị
    if (user) {
        // --- TRẠNG THÁI: ĐÃ ĐĂNG NHẬP ---
        
        // Cập nhật tên hiển thị trên header (Frontend update ngay lập tức)
        if (headerName) headerName.textContent = getShortName(user.name);
        if (nameDisplay) nameDisplay.textContent = user.name;
    
        // Đảm bảo thẻ a không chuyển trang
        if (accountLink) {
            accountLink.href = "javascript:void(0)"; 
            accountLink.style.cursor = "pointer";
        }
        // Sự kiện Click: Bật/Tắt Dropdown
        if (accountWrapper) {
            accountWrapper.addEventListener('click', (e) => {
                // Ngăn chặn sự kiện nổi bọt (để không bị document click tắt ngay lập tức)
                e.stopPropagation();
                // Ngăn thẻ a chuyển hướng (để chắc chắn)
                e.preventDefault();

                // Toggle hiển thị
                const isVisible = dropdown.style.display === 'block';
                dropdown.style.display = isVisible ? 'none' : 'block';
            }); 
        }
        const isAdmin = (user.role == 1 || user.role === 'admin' || user.role_id == 1);
        
        if (adminBtn && isAdmin) {
            adminBtn.style.display = 'flex'; // Hiện nút lên nếu là admin
        }
        // === SỰ KIỆN NÚT VỀ ADMIN ===
        if (adminBtn) {
            adminBtn.addEventListener('click', (e) => {
                e.stopPropagation(); // Ngăn đóng dropdown
                // Chuyển về trang Admin (Đường dẫn chuẩn bạn đã cung cấp)
                window.location.href = './pages/Admin/GiaoDien/index.php';
            });
        }
        // Sự kiện Logout
        if (logoutBtn) {
            logoutBtn.addEventListener('click', async (e) => {
                e.stopPropagation(); // Ngăn sự kiện click lan ra wrapper
                
                if(confirm('Bạn có chắc chắn muốn đăng xuất?')) {
                    try {
                        await AuthService.logout(); // Gọi API PHP
                    } catch (error) {
                        console.log("Lỗi logout server:", error);
                    }
                    
                    // Xóa bộ nhớ và reload
                    localStorage.removeItem('user_info');
                    window.location.href = 'index.php';
                }
            });
        }

    } else {
        // --- TRẠNG THÁI: CHƯA ĐĂNG NHẬP ---
        if (accountLink) {
            accountLink.href = 'index.php?page=login';
        }
        // Ẩn dropdown nếu lỡ nó đang hiện
        if (dropdown) dropdown.style.display = 'none';
    }

    // 3. Sự kiện bấm ra ngoài thì đóng dropdown
    document.addEventListener('click', (e) => {
        if (dropdown && dropdown.style.display === 'block') {
            // Nếu click không nằm trong wrapper tài khoản
            if (!accountWrapper.contains(e.target)) {
                dropdown.style.display = 'none';
            }
        }
    });
}

// Hàm phụ trợ: Cắt tên cho ngắn gọn nếu cần
function getShortName(fullName) {
    if (!fullName) return 'Tài khoản';
    const parts = fullName.split(' ');
    // Lấy 2 từ cuối cùng của tên (Ví dụ: Nguyễn Văn A -> Văn A)
    return parts.slice(-2).join(' ');
}



async function loadMegaMenu() {
    const menuContainer = document.getElementById('megaMenu');
    if (!menuContainer) return;

    // 1. Cấu hình API
    const API_URL = 'http://localhost/BTL/backend/api.php?route=book&action=categories';

    try {
        const response = await fetch(API_URL);
        const result = await response.json();

        if (result.status === 'success' && result.data.length > 0) {
            renderMegaMenuHTML(result.data, menuContainer);
        } else {
            menuContainer.innerHTML = '<div class="p-3">Không tải được danh mục</div>';
        }
    } catch (error) {
        console.error("Lỗi tải menu:", error);
        menuContainer.innerHTML = '<div class="p-3 text-danger">Lỗi kết nối API</div>';
    }
}

function renderMegaMenuHTML(categories, container) {
    // 1. Phân loại danh mục (Cha - Con - Cháu)
    // Giả sử API trả về mảng phẳng, ta cần xây dựng cây
    // Level 1: Sidebar (Sách Trong Nước, Foreign Books...)
    const roots = categories.filter(c => !c.danh_muc_cha || c.danh_muc_cha == 0);
    
    // Hàm lấy con của một danh mục
    const getChildren = (parentId) => categories.filter(c => c.danh_muc_cha == parentId);

    // 2. Xây dựng HTML Sidebar
    let sidebarHTML = `<div class="mega-sidebar"><div class="mega-sidebar-title">Danh mục sản phẩm</div>`;
    
    // 3. Xây dựng HTML Content
    let contentHTML = ``;

    roots.forEach((root, index) => {
        const isActive = index === 0 ? 'active' : ''; // Mục đầu tiên active
        const menuId = `menu-${root.ma_danh_muc}`;   // Tạo ID duy nhất

        // --- Sidebar Item ---
        sidebarHTML += `
            <div class="mega-menu-item ${isActive}" data-menu="${menuId}">
                ${root.ten_danh_muc}
            </div>
        `;

        // --- Content Block ---
        contentHTML += `
            <div class="mega-content ${isActive}" id="${menuId}">
                <div class="mega-header">
                    <div class="mega-icon"></div>
                    <h2 class="mega-title">${root.ten_danh_muc}</h2>
                </div>
                <div class="categories-grid">
        `;

        // Lấy danh mục con (Level 2 - Ví dụ: Văn Học, Kinh Tế)
        const level2 = getChildren(root.ma_danh_muc);
        
        level2.forEach(l2 => {
            contentHTML += `
                <div class="category-section">
                    <a href="index.php?page=search_product&category_id=${l2.ma_danh_muc}" class="text-decoration-none">
                        <h3 class="category-title">${l2.ten_danh_muc}</h3>
                    </a>
                    <ul class="category-list">
            `;

            // Lấy danh mục cháu (Level 3 - Ví dụ: Tiểu thuyết, Ngôn tình)
            const level3 = getChildren(l2.ma_danh_muc);
            level3.forEach(l3 => {
                contentHTML += `
                    <li>
                        <a href="index.php?page=search_product&category_id=${l3.ma_danh_muc}">
                            ${l3.ten_danh_muc}
                        </a>
                    </li>
                `;
            });

            contentHTML += `
                    </ul>
                    <a href="index.php?page=search_product&category_id=${l2.ma_danh_muc}" class="view-all">Xem tất cả</a>
                </div>
            `;
        });

        contentHTML += `
                </div> </div> `;
    });

    sidebarHTML += `</div>`; // Đóng mega-sidebar

    // 4. Gộp lại và Inject vào Container
    container.innerHTML = `<div class="d-flex w-100">${sidebarHTML}${contentHTML}</div>`;

    // 5. Kích hoạt sự kiện chuyển tab (Hover/Click sidebar)
    setupMegaMenuInteractions();
}

function setupMegaMenuInteractions() {
    const sidebarItems = document.querySelectorAll('.mega-menu-item');
    const contents = document.querySelectorAll('.mega-content');

    // Hàm chuyển tab dùng chung
    const switchTab = (item) => {
        // 1. Bỏ active cũ
        sidebarItems.forEach(el => el.classList.remove('active'));
        contents.forEach(el => el.classList.remove('active'));

        // 2. Active cái mới
        item.classList.add('active');
        const targetId = item.getAttribute('data-menu');
        const targetContent = document.getElementById(targetId);
        if (targetContent) {
            targetContent.classList.add('active');
        }
    };

    sidebarItems.forEach(item => {
        // Sự kiện 1: Di chuột qua (Hover)
        item.addEventListener('mouseenter', () => switchTab(item));

        // Sự kiện 2: Bấm vào (Click)
        item.addEventListener('click', (e) => {
            e.preventDefault(); // Chặn thẻ a (nếu có)
            switchTab(item);
        });
    });
}

function setupMenuToggle() {
    const btn = document.getElementById('menuToggle');
    const menu = document.getElementById('megaMenu');
    if(btn && menu) {
        btn.addEventListener('click', (e) => {
            e.stopPropagation(); // Tránh đóng ngay lập tức
            menu.classList.toggle('show'); // Bạn cần class 'show' trong CSS để hiện menu
            // Hoặc nếu CSS dùng display: none/block thì toggle style
            menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
        });

        // Đóng khi click ra ngoài
        document.addEventListener('click', (e) => {
            if (!menu.contains(e.target) && !btn.contains(e.target)) {
                menu.style.display = 'none';
            }
        });
    }
}