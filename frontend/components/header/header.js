// frontend/components/header/header.js

document.addEventListener("DOMContentLoaded", () => {
    loadMegaMenu();
    setupMenuToggle(); // Xử lý nút bật/tắt menu mobile (nếu có)
});

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