// Change main image
function changeImage(src, element) {
    document.getElementById('mainImage').src = src;

    // Remove active class from all thumbnails
    document.querySelectorAll('.thumbnail').forEach(thumb => {
        thumb.classList.remove('active');
    });

    // Add active class to clicked thumbnail
    element.classList.add('active');
}

// Quantity controls
function increaseQty() {
    const input = document.getElementById('quantity');
    const max = parseInt(input.max);
    if (parseInt(input.value) < max) {
        input.value = parseInt(input.value) + 1;
    }
}

function decreaseQty() {
    const input = document.getElementById('quantity');
    if (parseInt(input.value) > 1) {
        input.value = parseInt(input.value) - 1;
    }
}

// Toggle description
function toggleDescription() {
    const content = document.getElementById('description-content');
    const button = document.getElementById('toggleDescription');

    if (content.classList.contains('collapsed')) {
        content.classList.remove('collapsed');
        button.textContent = 'Thu gọn ▲';
    } else {
        content.classList.add('collapsed');
        button.textContent = 'Xem thêm ▼';
        // Scroll to top of description
        content.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
}

// Hàm hiển thị Toast chuyên nghiệp
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.innerText = message;

    // Style cơ bản cho toast
    Object.assign(toast.style, {
        position: 'fixed',
        top: '20px',
        right: '20px',
        zIndex: '9999',
        padding: '12px 24px',
        backgroundColor: type === 'success' ? '#4CAF50' : '#F44336',
        color: 'white',
        borderRadius: '4px',
        boxShadow: '0 2px 5px rgba(0,0,0,0.2)',
        opacity: '0',
        transition: 'opacity 0.3s ease-in-out'
    });

    document.body.appendChild(toast);

    // Animation Fade In
    requestAnimationFrame(() => {
        toast.style.opacity = '1';
    });

    // Tự động tắt sau 3s
    setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Logic Thêm vào giỏ (Sử dụng Fetch API chuẩn)
async function addToCart(productId, quantity = 1, isBuyNow = false) {
    try {
        // Lấy quantity từ input nếu không được truyền vào (mặc định là 1)
        if (quantity === 1) {
            const qtyInput = document.getElementById('quantity');
            if (qtyInput) quantity = parseInt(qtyInput.value);
        }

        // Lấy thông tin sản phẩm từ nút (để gửi lên API nếu cần, nhưng API hiện tại chỉ cần ID và Qty là đủ nếu backend tự query DB)
        // Tuy nhiên, theo code cũ, API nhận cả name, price, image. Hãy giữ nguyên để tương thích.
        const btn = document.querySelector('.js-add-to-cart');
        const name = btn.dataset.name;
        const price = btn.dataset.price;
        const image = btn.dataset.image;

        const data = {
            productId: productId,
            name: name,
            price: price,
            image: image,
            quantity: quantity
        };

        const response = await fetch(BACKEND_URL + '/api.php?route=cart&action=add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });

        const result = await response.json();

        if (result.status === 'success') {
            if (isBuyNow) {
                // Nếu là mua ngay -> chuyển hướng
                window.location.href = '../giaodien/gio-hang.php';
            } else {
                // Nếu chỉ thêm vào giỏ -> hiện Toast
                showToast('Đã thêm sách vào giỏ hàng thành công!');
                // Optional: Update cart count UI here if we had an endpoint for it
            }
        } else {
            showToast(result.message || 'Có lỗi xảy ra', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('Lỗi kết nối server', 'error');
    }
}

// Event Listeners (Tách biệt khỏi HTML)
document.addEventListener('DOMContentLoaded', () => {
    const btnAdd = document.querySelector('.js-add-to-cart');
    const btnBuy = document.querySelector('.js-buy-now');

    if (btnAdd) {
        btnAdd.addEventListener('click', function () {
            const id = this.dataset.id;
            addToCart(id, 1, false);
        });
    }

    if (btnBuy) {
        btnBuy.addEventListener('click', function () {
            const id = this.dataset.id;
            addToCart(id, 1, true);
        });
    }
});
