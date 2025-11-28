// Menu Toggle
const menuToggle = document.getElementById("menuToggle");
const megaMenu = document.getElementById("megaMenu");
const menuWrapper = document.querySelector(".mega-menu-wrapper");
const menuItems = document.querySelectorAll(".mega-menu-item");
const contents = document.querySelectorAll(".mega-content");

// Hiện menu khi hover vào button
menuToggle.addEventListener("mouseenter", function () {
    megaMenu.classList.add("show");
});

// Hiện menu khi click vào button
menuToggle.addEventListener("click", function (e) {
    e.stopPropagation();
    megaMenu.classList.add("show");
});

// Ẩn menu khi di chuột ra khỏi vùng wrapper (bao gồm cả button và menu)
menuWrapper.addEventListener("mouseleave", function () {
    megaMenu.classList.remove("show");
});

// Giữ menu hiện khi hover vào menu
megaMenu.addEventListener("mouseenter", function () {
    megaMenu.classList.add("show");
});

// Menu items hover functionality
menuItems.forEach((item) => {
    item.addEventListener("mouseenter", function () {
        // Remove active class from all items and contents
        menuItems.forEach((i) => i.classList.remove("active"));
        contents.forEach((c) => c.classList.remove("active"));

        // Add active class to current item
        this.classList.add("active");

        // Show corresponding content
        const menuId = this.getAttribute("data-menu");
        const content = document.getElementById(menuId);
        if (content) {
            content.classList.add("active");
        }
    });
});