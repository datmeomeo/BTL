// frontend/components/header/header.js

document.addEventListener("DOMContentLoaded", () => {
    loadMegaMenu();
    setupMenuToggle(); // Xử lý nút bật/tắt menu mobile (nếu có)
    setupAccountDropdown();
});
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
                window.location.href = './pages/Admin_MVC/index.php';
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
        console.log("Dữ liệu API danh mục:", result.data);
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

// frontend/components/header/header.js

function renderMegaMenuHTML(categories, container) {
    // Helper: Lấy ID an toàn (ưu tiên ma_danh_muc, sau đó id)
    const getId = (item) => item.ma_danh_muc || item.id || item.category_id;
    const getName = (item) => item.ten_danh_muc || item.name;
    const getParent = (item) => item.danh_muc_cha || item.parent_id || 0;

    const roots = categories.filter(c => getParent(c) == 0);
    const getChildren = (parentId) => categories.filter(c => getParent(c) == parentId);

    let sidebarHTML = `<div class="mega-sidebar"><div class="mega-sidebar-title">Danh mục sản phẩm</div>`;
    let contentHTML = ``;

    roots.forEach((root, index) => {
        const rootId = getId(root);
        const isActive = index === 0 ? 'active' : '';
        const menuId = `menu-${rootId}`;

        sidebarHTML += `
            <div class="mega-menu-item ${isActive}" data-menu="${menuId}">
                ${getName(root)}
            </div>
        `;

        contentHTML += `
            <div class="mega-content ${isActive}" id="${menuId}">
                <div class="mega-header"><h2 class="mega-title">${getName(root)}</h2></div>
                <div class="categories-grid">
        `;

        const level2 = getChildren(rootId);
        level2.forEach(l2 => {
            const l2Id = getId(l2);
            // Link chuẩn, chứa đầy đủ tham số page
            const linkUrl = `index.php?page=search_product&category_id=${l2Id}`;

            contentHTML += `
                <div class="category-section">
                    <a href="${linkUrl}" class="text-decoration-none category-title-link">
                        <h3 class="category-title">${getName(l2)}</h3>
                    </a>
                    <ul class="category-list">
            `;

            const level3 = getChildren(l2Id);
            level3.forEach(l3 => {
                const l3Id = getId(l3);
                const subLinkUrl = `index.php?page=search_product&category_id=${l3Id}`;
                contentHTML += `<li><a href="${subLinkUrl}">${getName(l3)}</a></li>`;
            });

            contentHTML += `</ul><a href="${linkUrl}" class="view-all">Xem tất cả</a></div>`;
        });
        contentHTML += `</div></div>`;
    });

    sidebarHTML += `</div>`;
    container.innerHTML = `<div class="d-flex w-100">${sidebarHTML}${contentHTML}</div>`;
    
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